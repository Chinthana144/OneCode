<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Exports\SubscriptionExport;
use App\Models\Camps;
use App\Models\CampUsers;
use App\Models\Subscriptions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function showSalesReports()
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        return view('Reports.sales_reports', compact('camp'));
    }

    /*
    * show daily sales
    */
    public function showDailySalesReport()
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        $today = date('Y-m-d');
        $sales = Subscriptions::where('camp_id', $camp_id)
            ->whereDate('purchaseDateTime', $today)
            ->paginate(10);

        return view('Reports.rpt_daily_sales', compact('camp', 'sales'));
    }

    public function rptDailySalesSearch(Request $request)
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $camp_name = $camp->name;

        $today = date('Y-m-d');

        $start_date = $request->input('start_date') ?? $today;
        $end_date = $request->input('end_date') ?? $today;

        switch($request->action){
            case 'search':
                $sales = Subscriptions::where('camp_id', $camp_id)
                    ->whereBetween('purchaseDateTime', [$start_date, $end_date])
                    ->paginate(10);

                return view('Reports.rpt_daily_sales', compact('camp', 'sales', 'start_date', 'end_date'));
                break;

            case 'excel':
                $data = Subscriptions::join('customers', 'subscriptions.customer_id', '=', 'customers.id')
                    ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->get(['subscriptions.id as id', 'purchaseDate', 'customers.fullname', 'customers.username', 'packages.name', 'packages.duration', 'subscriptions.price']);

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
                $data = Subscriptions::join('customers', 'subscriptions.customer_id', '=', 'customers.id')
                    ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->get(['subscriptions.id as id', 'purchaseDate', 'customers.fullname as fullname', 'customers.username as username', 'packages.name as name', 'packages.duration as duration', 'subscriptions.price as price']);

                $pdf = Pdf::loadView('pdf.sales_pdf', compact('data','camp_name','start_date','end_date'));
                return $pdf->stream('daily_sales_from_'.$start_date.'_to_'. $end_date .'.pdf');
            break;

            default:
                # code...
            break;
        }//switch
    }

    /*
    * Daily Sales Summary Report
    */
    public function showDailySummaryReport()
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        $today = date('Y-m-d');

        $sales = Subscriptions::selectRaw('package_id, COUNT(*) AS package_count, SUM(subscriptions.price) as total_sales')
            ->where('subscriptions.camp_id', $camp_id)
            ->whereDate('purchaseDate', $today)
            ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
            ->groupBy('package_id')
            ->paginate(10);

        return view('Reports.rpt_daily_summary', compact('camp', 'sales'));
    }

    public function rptDailySummarySearch(Request $request){
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $camp_name = $camp->name;

        $today = date('Y-m-d');

        $start_date = $request->input('start_date') ?? $today;
        $end_date = $request->input('end_date') ?? $today;

        switch ($request->action) {
            case 'search':
                $sales = Subscriptions::selectRaw('package_id, COUNT(*) AS package_count, SUM(subscriptions.price) as total_sales')
                ->where('subscriptions.camp_id', $camp_id)
                ->whereBetween('purchaseDate', [$start_date, $end_date])
                ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
                ->groupBy('package_id')
                ->paginate(10);
                return view('Reports.rpt_daily_summary', compact('camp', 'sales', 'start_date', 'end_date'));
                break;

            case 'excel':
                 $data = Subscriptions::join('packages', 'subscriptions.package_id', '=', 'packages.id')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->selectRaw('packages.name, packages.duration, COUNT(*) as package_count, packages.price, SUM(subscriptions.price) as total_sales')
                    ->groupBy('subscriptions.package_id', 'packages.name', 'packages.duration', 'packages.price')
                    ->get();

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
                            return ['Package Name', 'Duration', 'Package Count', 'Price', 'Sale'];
                        }
                    },
                    'sales_summary_from_'.$start_date.'_to_'. $end_date .'.xlsx'
                );

                break;
            case 'pdf':
                $data = Subscriptions::join('packages', 'subscriptions.package_id', '=', 'packages.id')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->selectRaw('packages.name, packages.duration, packages.price, COUNT(*) as package_count, SUM(subscriptions.price) as total_sales')
                    ->groupBy('subscriptions.package_id', 'packages.name', 'packages.duration', 'packages.price')
                    ->get();

                $pdf = Pdf::loadView('pdf.daily_summary_pdf', compact('data', 'camp_name', 'start_date', 'end_date'));
                return $pdf->stream('daily_sales_from_'.$start_date.'_to_'. $end_date .'.pdf');
                break;

            default:
                # code...
                break;
        }//switch
    }

    /*
    * sales summary report
    */
    public function showSalesSummaryReport(){
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);

        $sales = Subscriptions::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(subscriptions.price) as total_sales')
            ->where('subscriptions.camp_id', $camp_id)
            ->whereYear('purchaseDate', date('Y'))
            ->whereMonth('purchaseDate', date('m'))
            ->groupBy('purchaseDate')
            ->paginate(10);

        return view('Reports.rpt_sales_summary', compact('camp', 'sales'));
    }

    public function rptSalesSummarySearch(Request $request){
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $camp_name = $camp->name;

        $year_month = $request->input('month') ?? date('Y-m');
        $year = substr($year_month, 0, 4);
        $month = substr($year_month, 5, 2);

        switch($request->action){
            case 'search':
                $sales = Subscriptions::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(subscriptions.price) as total_sales')
                ->where('subscriptions.camp_id', $camp_id)
                ->whereYear('purchaseDate', $year)
                ->whereMonth('purchaseDate', $month)
                ->groupBy('purchaseDate')
                ->paginate(10);

            return view('Reports.rpt_sales_summary', compact('camp', 'sales', 'year_month'));
            break;

            case 'excel':
                $data = Subscriptions::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(subscriptions.price) as total_sales')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereYear('purchaseDate', $year)
                    ->whereMonth('purchaseDate', $month)
                    ->groupBy('purchaseDate')
                    ->get();

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
                $data = Subscriptions::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(subscriptions.price) as total_sales')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereYear('purchaseDate', $year)
                    ->whereMonth('purchaseDate', $month)
                    ->groupBy('purchaseDate')
                    ->get();

                $monthName = Carbon::createFromFormat('Y-m', $year_month)->format('F');
                $pdf = Pdf::loadView('pdf.sales_summary_pdf', compact('data', 'year_month', 'camp_name', 'monthName', 'year'));
                return $pdf->stream('sales_summary_'.$year_month.'.pdf');
        }
    }

    /*
    * user sales report
    */
    public function showUserSalesReport()
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $users = CampUsers::selectRaw('users.id, users.name')
            ->where('camp_id', $camp_id)
            ->join('users', 'camp_users.user_id', '=', 'users.id')
            ->get();

        $today = date('Y-m-d');

        $sales = Subscriptions::where('camp_id', $camp_id)
            ->whereDate('purchaseDate', $today)
            ->paginate(10);

        return view('Reports.rpt_user_sales', compact('camp', 'users', 'sales'));
    }

    public function rptUserSalesSearch(Request $request){
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $camp_name = $camp->name;
        $users = CampUsers::selectRaw('users.id, users.name')
            ->where('camp_id', $camp_id)
            ->join('users', 'camp_users.user_id', '=', 'users.id')
            ->get();

        $today = date('Y-m-d');

        $selected_user = $request->input('cmb_salesman') ?? null;
        $user = User::find($selected_user);
        $user_name = $user->name;
        $start_date = $request->input('start_date') ?? $today;
        $end_date = $request->input('end_date') ?? $today;

        switch($request->action){
            case 'search':
                $sales = Subscriptions::where('camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('user_id', $selected_user)
                    ->paginate(10);

                return view('Reports.rpt_user_sales', compact('camp', 'sales', 'users', 'start_date', 'end_date', 'selected_user'));
            break;

            case 'excel':
                $data = Subscriptions::join('customers', 'subscriptions.customer_id', '=', 'customers.id')
                    ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
                    ->join('users', 'subscriptions.user_id', '=', 'users.id')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('subscriptions.user_id', $selected_user)
                    ->get(['subscriptions.id as id', 'purchaseDate', 'customers.username', 'packages.name', 'packages.duration', 'subscriptions.price', 'users.name as user_name']);

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
                            return ['ID', 'Date Time', 'Customer', 'Package Name', 'Duration (days)', 'Price', 'Salesman'];
                        }
                    },
                    'user_sales_from_'.$start_date.'_to_'. $end_date .'.xlsx'
                );
            break;

            case 'pdf':
                $data = Subscriptions::join('customers', 'subscriptions.customer_id', '=', 'customers.id')
                    ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
                    ->join('users', 'subscriptions.user_id', '=', 'users.id')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('subscriptions.user_id', $selected_user)
                    ->get(['subscriptions.id as id', 'purchaseDate', 'customers.fullname as fullname', 'customers.username as username', 'packages.name as name', 'packages.duration as duration', 'subscriptions.price as price', 'users.name as user_name']);

                $pdf = Pdf::loadView('pdf.user_sale_pdf', compact('data','camp_name','start_date','end_date', 'user_name'));
                return $pdf->stream('user_sales_from_'.$start_date.'_to_'. $end_date .'.pdf');
            break;
        }//switch
    }//user sales search

    /*
    * user sales summary report
    */
    public function showUserPackageSummaryReport()
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $today = date('Y-m-d');

        $users = CampUsers::selectRaw('users.id, users.name')
            ->where('camp_id', $camp_id)
            ->join('users', 'camp_users.user_id', '=', 'users.id')
            ->get();
        $user_id = $users->first()->id ?? 1;

        $sales = Subscriptions::selectRaw('COUNT(*) AS row_count, SUM(subscriptions.price) as total_sales, packages.name as package_name, packages.duration as package_duration')
            ->where('subscriptions.camp_id', $camp_id)
            ->where('user_id', $user_id)
            ->whereDate('purchaseDate', $today)
            ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
            ->groupBy('package_id', 'packages.name', 'packages.duration')
            ->paginate(10);

        return view('Reports.rpt_user_package_summary', compact('camp', 'users', 'sales'));
    }

    public function rptUserPackageSummarySearch(Request $request)
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $camp_name = $camp->name;

         $users = CampUsers::selectRaw('users.id, users.name')
            ->where('camp_id', $camp_id)
            ->join('users', 'camp_users.user_id', '=', 'users.id')
            ->get();

        $today = date('Y-m-d');

        $selected_user = $request->input('cmb_salesman') ?? null;
        $user = User::find($selected_user);
        $user_name = $user->name;
        $start_date = $request->input('start_date') ?? $today;
        $end_date = $request->input('end_date') ?? $today;

        switch ($request->action) {
            case 'search':
                $sales = Subscriptions::selectRaw('COUNT(*) AS row_count, SUM(subscriptions.price) as total_sales, packages.name as package_name, packages.duration as package_duration')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('user_id', $selected_user)
                    ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
                    ->groupBy('package_id', 'packages.name', 'packages.duration')
                    ->paginate(10);

                return view('Reports.rpt_user_package_summary', compact('camp', 'sales', 'users', 'start_date', 'end_date', 'selected_user'));
            break;

            case 'excel':
                $data = Subscriptions::selectRaw('COUNT(*) AS row_count, SUM(subscriptions.price) as total_sales, packages.name as package_name, packages.duration as package_duration')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('user_id', $selected_user)
                    ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
                    ->groupBy('package_id', 'packages.name', 'packages.duration')
                    ->get();

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
                            return ['Package Name', 'Duration (days)', 'Count', 'Sale'];
                        }
                    },
                    'user_package_summary_from_'.$start_date.'_to_'. $end_date .'.xlsx'
                );
            break;
            case 'pdf':
                $data = Subscriptions::selectRaw('COUNT(*) AS row_count, SUM(subscriptions.price) as total_sales, packages.name as package_name, packages.duration as package_duration')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('user_id', $selected_user)
                    ->join('packages', 'subscriptions.package_id', '=', 'packages.id')
                    ->groupBy('package_id', 'packages.name', 'packages.duration')
                    ->get();

                $pdf = Pdf::loadView('pdf.user_package_summary_pdf', compact('data','camp_name','start_date','end_date', 'user_name'));
                return $pdf->stream('user_package_summary_from_'.$start_date.'_to_'. $end_date .'.pdf');
            break;
        }//switch
    } //rptUserPackageSummarySearch

    public function showUserSalesSummaryReport()
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $today = date('Y-m-d');

        $sales = Subscriptions::selectRaw('COUNT(*) AS row_count, SUM(subscriptions.price) as total_sales, users.name as user_name')
            ->where('subscriptions.camp_id', $camp_id)
            ->whereDate('purchaseDate', $today)
            ->join('users', 'subscriptions.user_id', '=', 'users.id')
            ->groupBy('user_id', 'users.name')
            ->paginate(10);

        return view('Reports.rpt_user_sales_summary', compact('camp', 'sales'));
    }//showUserSalesSummaryReport

    public function rptUserSalesSummarySearch(Request $request)
    {
        $camp_id = Session::get('active_camp_id');
        $camp = Camps::find($camp_id);
        $camp_name = $camp->name;

        $today = date('Y-m-d');

        $start_date = $request->input('start_date') ?? $today;
        $end_date = $request->input('end_date') ?? $today;

        switch ($request->action) {
            case 'search':
                $sales = Subscriptions::selectRaw('COUNT(*) AS row_count, SUM(subscriptions.price) as total_sales, users.name as user_name')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->join('users', 'subscriptions.user_id', '=', 'users.id')
                    ->groupBy('user_id', 'users.name')
                    ->paginate(10);

                return view('Reports.rpt_user_sales_summary', compact('camp', 'sales', 'start_date', 'end_date'));
            break;

            case 'excel':
                $data = Subscriptions::selectRaw('COUNT(*) AS row_count, SUM(subscriptions.price) as total_sales, users.name as user_name')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->join('users', 'subscriptions.user_id', '=', 'users.id')
                    ->groupBy('user_id', 'users.name')
                    ->get();

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
                            return ['User Name', 'Count', 'Sale'];
                        }
                    },
                    'user_sales_summary_from_'.$start_date.'_to_'. $end_date .'.xlsx'
                );
            break;

            case 'pdf':
                $data = Subscriptions::selectRaw('COUNT(*) AS row_count, SUM(subscriptions.price) as total_sales, users.name as user_name')
                    ->where('subscriptions.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->join('users', 'subscriptions.user_id', '=', 'users.id')
                    ->groupBy('user_id', 'users.name')
                    ->get();
                $pdf = Pdf::loadView('pdf.user_sales_summary_pdf', compact('data','camp_name','start_date','end_date'));
                return $pdf->stream('user_sales_summary_from_'.$start_date.'_to_'. $end_date .'.pdf');
            break;
        }//switch
    } //rptUserSalesSummarySearch
}//class
