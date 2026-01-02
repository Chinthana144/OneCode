<?php

namespace App\Http\Controllers;

use App\Models\Camps;
use App\Models\ClientSubscriptions;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientReportController extends Controller
{
    public function showSaleReports(){
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        return view('ClientReports.sale_reports', compact('camp'));

    }//show sale reports

    public function showDailySaleReport(){
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        $yesterday = now()->subDay()->toDateString('Y-m-d');

        $sale = ClientSubscriptions::where('camp_id', $camp_id)
            ->whereDate('purchaseDate', $yesterday)
            ->paginate(10);

        return view('ClientReports.rpt_daily_sale', compact('camp', 'sale'));
    }

    public function rptDailySaleSearch(Request $request){
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $camp_name = $camp->name;

        $yesterday = now()->subDay()->toDateString('Y-m-d');

        $start_date = $request->input('start_date') ?? $yesterday;
        $end_date = $request->input('end_date') ?? $yesterday;

        switch($request->action){
            case 'search' :
                $sale = ClientSubscriptions::where('camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->paginate(10);

                return view('ClientReports.rpt_daily_sale', compact('camp', 'sale'));
            break;
            case 'excel':
                $data = ClientSubscriptions::join('customers', 'client_subscriptions.customer_id', '=', 'customers.id')
                    ->join('packages', 'client_subscriptions.package_id', '=', 'packages.id')
                    ->where('client_subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->get(['client_subscriptions.id as id', 'purchaseDate', 'customers.fullname as fullname', 'customers.username as username', 'packages.name as name', 'packages.duration as duration', 'client_subscriptions.price as price']);

                return Excel::download(
                    new class($data) implements FromCollection, WithHeadings {
                        protected $data;
                        public function __construct($data)
                        {
                            $this->data = $data;
                        }
                        public function collection()
                        {
                            return $this->data;
                        }
                        public function headings(): array
                        {
                            return ['ID', 'Date Time', 'Customer Name', 'Username', 'Package Name', 'Duration (days)', 'Price'];
                        }
                    },
                    'daily_sales_from_'.$start_date.'_to_'. $end_date .'.xlsx'
                );
            break;
            case 'pdf':
                $data = ClientSubscriptions::join('customers', 'client_subscriptions.customer_id', '=', 'customers.id')
                    ->join('packages', 'client_subscriptions.package_id', '=', 'packages.id')
                    ->where('client_subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->get(['client_subscriptions.id as id', 'purchaseDate', 'customers.fullname as fullname', 'customers.username as username', 'packages.name as name', 'packages.duration as duration', 'client_subscriptions.price as price']);

                $pdf = Pdf::loadView('pdf.sales_pdf', compact('data','camp_name','start_date','end_date'));
                return $pdf->stream('daily_sales_from_'.$start_date.'_to_'. $end_date .'.pdf');
            break;
        }//switch
    }//rpt daily sale

    public function showSaleSummary(){
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        $sales = ClientSubscriptions::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(client_subscriptions.price) as total_sales')
            ->where('client_subscriptions.camp_id', $camp_id)
            ->whereYear('purchaseDate', date('Y'))
            ->whereMonth('purchaseDate', date('m'))
            ->groupBy('purchaseDate')
            ->paginate(10);

        return view('ClientReports.rpt_sale_summary', compact('camp', 'sales'));
    }//rpt sale summary

    public function rptSaleSummarySearch(Request $request){
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $camp_name = $camp->name;

        $year_month = $request->input('month') ?? date('Y-m');
        $year = substr($year_month, 0, 4);
        $month = substr($year_month, 5, 2);

        switch ($request->action) {
            case 'search':
                $sales = ClientSubscriptions::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(client_subscriptions.price) as total_sales')
                ->where('client_subscriptions.camp_id', $camp_id)
                ->whereYear('purchaseDate', $year)
                ->whereMonth('purchaseDate', $month)
                ->groupBy('purchaseDate')
                ->paginate(10);

                return view('ClientReports.rpt_sale_summary', compact('camp', 'sales', 'year_month'));
                break;

            case 'excel':
                $data = ClientSubscriptions::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(client_subscriptions.price) as total_sales')
                    ->where('client_subscriptions.camp_id', $camp_id)
                    ->whereYear('purchaseDate', $year)
                    ->whereMonth('purchaseDate', $month)
                    ->groupBy('purchaseDate')
                    ->paginate(10);

                return Excel::download(
                    new class($data) implements FromCollection, WithHeadings {
                        protected $data;
                        public function __construct($data)
                        {
                            $this->data = $data;
                        }
                        public function collection()
                        {
                            return $this->data;
                        }
                        public function headings(): array
                        {
                            return ['Purchase Date', 'Invoice Count', 'Total Sales'];
                        }
                    },
                    'sales_summary_'.$year_month.'.xlsx'
                );
                break;

            case 'pdf':
                $data = ClientSubscriptions::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(client_subscriptions.price) as total_sales')
                    ->where('client_subscriptions.camp_id', $camp_id)
                    ->whereYear('purchaseDate', $year)
                    ->whereMonth('purchaseDate', $month)
                    ->groupBy('purchaseDate')
                    ->get();

                $monthName = Carbon::createFromFormat('Y-m', $year_month)->format('F');
                $pdf = Pdf::loadView('pdf.sales_summary_pdf', compact('data', 'year_month', 'camp_name', 'monthName', 'year'));
                return $pdf->stream('sales_summary_'.$year_month.'.pdf');
            break;
        }
    }//rpt sale summary search
}//class
