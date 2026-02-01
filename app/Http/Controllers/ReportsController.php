<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Exports\SubscriptionExport;
use App\Models\AccessPlanes;
use App\Models\Camps;
use App\Models\CampUsers;
use App\Models\Subscriptions;
use App\Models\User;
use App\Models\Vouchers;
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
        $sales = AccessPlanes::where('camp_id', $camp_id)
            ->whereDate('purchaseDate', $today)
            ->paginate(10);

        // $sales = Subscriptions::where('camp_id', $camp_id)
        //     ->whereDate('purchaseDateTime', $today)
        //     ->paginate(10);

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

        $data = AccessPlanes::with('accessable')
            ->where('camp_id', $camp_id)
            ->whereBetween('purchaseDate', [$start_date, $end_date])
            ->whereHasMorph(
                'accessable',
                [Subscriptions::class, Vouchers::class]
            )
            ->get()
            ->loadMorph('accessable', [
                Subscriptions::class => ['customer'],
                Vouchers::class => [] // no relations to load
            ]);

        switch($request->action){
            case 'search':
                $sales = AccessPlanes::where('camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->orderBy('id', 'ASC')
                    ->paginate(5);

                return view('Reports.rpt_daily_sales', compact('camp', 'sales', 'start_date', 'end_date'));
                break;

            case 'excel':
                // dd($data);
                $rows = $data->map(function($plan){
                    return [
                        'id' => $plan->id,
                        'purchase_date' => $plan->purchaseDate,
                        'Type' => $plan->accessable_type == 'App\Models\Subscriptions' ? "Subscription" : "Voucher",
                        'username' =>$plan->accessable instanceof Subscriptions
                            ? optional($plan->accessable->customer)->username
                            : null,
                        'voucher_code' =>$plan->accessable instanceof Vouchers
                            ? $plan->accessable->code
                            : null,
                        'package_name' =>$plan->package->name,
                        'duration' => $plan->package->duration,
                        'price' => $plan->price,
                    ];
                });

                return Excel::download(
                    new class($rows) implements FromCollection, WithHeadings {
                        protected $rows;
                        public function __construct($rows)
                        {
                            $this->rows = $rows;
                        }
                        public function collection()
                        {
                            return $this->rows;
                        }
                        public function headings(): array
                        {
                            return ['ID', 'Date', 'Type', 'Username', 'Voucher', 'Package Name', 'Duration (days)', 'Price'];
                        }
                    },
                    'daily_sales_from_'.$start_date.'_to_'. $end_date .'.xlsx'
                );
            break;

            case 'pdf':
                $rows = $data->map(function($plan){
                    return [
                        'id' => $plan->id,
                        'purchase_date' => $plan->purchaseDate,
                        'Type' => $plan->accessable_type == 'App\Models\Subscriptions' ? "Subscription" : "Voucher",
                        'username' =>$plan->accessable instanceof Subscriptions
                            ? optional($plan->accessable->customer)->username
                            : null,
                        'voucher_code' =>$plan->accessable instanceof Vouchers
                            ? $plan->accessable->code
                            : null,
                        'package_name' =>$plan->package->name,
                        'duration' => $plan->package->duration,
                        'price' => $plan->price,
                    ];
                });
                $pdf = Pdf::loadView('pdf.sales_pdf', compact('rows','camp_name','start_date','end_date'));
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

        $sales = AccessPlanes::selectRaw('package_id, COUNT(*) AS package_count, SUM(access_planes.price) as total_sales')
            ->where('access_planes.camp_id', $camp_id)
            ->whereDate('purchaseDate', $today)
            ->join('packages', 'access_planes.package_id', '=', 'packages.id')
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
                $sales = AccessPlanes::selectRaw('package_id, COUNT(*) AS package_count, packages.price, SUM(access_planes.price) as total_sales')
                    ->where('access_planes.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->join('packages', 'access_planes.package_id', '=', 'packages.id')
                    ->groupBy('package_id')
                    ->paginate(10);
                    return view('Reports.rpt_daily_summary', compact('camp', 'sales', 'start_date', 'end_date'));
                break;

            case 'excel':
                $data = AccessPlanes::join('packages', 'access_planes.package_id', '=', 'packages.id')
                    ->where('access_planes.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->selectRaw('packages.name, packages.duration, COUNT(*) as package_count, packages.price, SUM(access_planes.price) as total_sales')
                    ->groupBy('access_planes.package_id', 'packages.name', 'packages.duration', 'packages.price')
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
                $data = AccessPlanes::join('packages', 'access_planes.package_id', '=', 'packages.id')
                    ->where('access_planes.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->selectRaw('packages.name, packages.duration, packages.price, COUNT(*) as package_count, SUM(access_planes.price) as total_sales')
                    ->groupBy('access_planes.package_id', 'packages.name', 'packages.duration', 'packages.price')
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

        $sales = AccessPlanes::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(access_planes.price) as total_sales')
            ->where('access_planes.camp_id', $camp_id)
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
                $sales = AccessPlanes::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(access_planes.price) as total_sales')
                ->where('access_planes.camp_id', $camp_id)
                ->whereYear('purchaseDate', $year)
                ->whereMonth('purchaseDate', $month)
                ->groupBy('purchaseDate')
                ->paginate(10);

            return view('Reports.rpt_sales_summary', compact('camp', 'sales', 'year_month'));
            break;

            case 'excel':
                $data = AccessPlanes::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(access_planes.price) as total_sales')
                    ->where('access_planes.camp_id', $camp_id)
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
                $data = AccessPlanes::selectRaw('purchaseDate, COUNT(*) AS invoice_count, SUM(access_planes.price) as total_sales')
                    ->where('access_planes.camp_id', $camp_id)
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

        $sales = AccessPlanes::where('camp_id', $camp_id)
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
                $sales = AccessPlanes::where('camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('user_id', $selected_user)
                    ->paginate(10);

                return view('Reports.rpt_user_sales', compact('camp', 'sales', 'users', 'start_date', 'end_date', 'selected_user'));
            break;

            case 'excel':
                $data = AccessPlanes::join('packages', 'access_planes.package_id', '=', 'packages.id')
                    ->join('users', 'access_planes.user_id', '=', 'users.id')
                    ->where('access_planes.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('access_planes.user_id', $selected_user)
                    ->get();

                $rows = $data->map(function($plan){
                    return [
                        'id' => $plan->id,
                        'datetime' => $plan->purchaseDateTime,
                        'customer' =>$plan->accessable instanceof Subscriptions
                            ? optional($plan->accessable->customer)->fullname
                            : $plan->accessable->username,
                        'username' =>$plan->accessable instanceof Subscriptions
                            ? optional($plan->accessable->customer)->username
                            : $plan->accessable->code,
                        'package' => $plan->package->name,
                        'duration' => $plan->package->duration,
                        'price' => $plan->price,
                        'salesman' => $plan->user->name,
                    ];
                });

                return Excel::download(
                    new class($rows) implements FromCollection, WithHeadings {
                        protected $rows;
                        public function __construct($rows)
                        {
                            $this->rows = $rows;
                        }
                        public function collection()
                        {
                            return $this->rows;
                        }
                        public function headings(): array
                        {
                            return ['ID', 'Date Time', 'Customer', 'username', 'Package Name', 'Duration (days)', 'Price', 'Salesman'];
                        }
                    },
                    'user_sales_from_'.$start_date.'_to_'. $end_date .'.xlsx'
                );
            break;

            case 'pdf':
                $data = AccessPlanes::join('packages', 'access_planes.package_id', '=', 'packages.id')
                    ->join('users', 'access_planes.user_id', '=', 'users.id')
                    ->where('access_planes.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('access_planes.user_id', $selected_user)
                    ->get();

                // dd($data);

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

        $sales = AccessPlanes::selectRaw('COUNT(*) AS row_count, SUM(access_planes.price) as total_sales, packages.name as package_name, packages.duration as package_duration')
            ->where('access_planes.camp_id', $camp_id)
            ->where('user_id', $user_id)
            ->whereDate('purchaseDate', $today)
            ->join('packages', 'access_planes.package_id', '=', 'packages.id')
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
                $sales = AccessPlanes::selectRaw('COUNT(*) AS row_count, SUM(access_planes.price) as total_sales, packages.name as package_name, packages.duration as package_duration')
                    ->where('access_planes.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('user_id', $selected_user)
                    ->join('packages', 'access_planes.package_id', '=', 'packages.id')
                    ->groupBy('package_id', 'packages.name', 'packages.duration')
                    ->paginate(10);

                return view('Reports.rpt_user_package_summary', compact('camp', 'sales', 'users', 'start_date', 'end_date', 'selected_user'));
            break;

            case 'excel':
                $data = AccessPlanes::selectRaw('COUNT(*) AS row_count, SUM(access_planes.price) as total_sales, packages.name as package_name, packages.duration as package_duration')
                    ->where('access_planes.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('user_id', $selected_user)
                    ->join('packages', 'access_planes.package_id', '=', 'packages.id')
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
                $data = AccessPlanes::selectRaw('COUNT(*) AS row_count, SUM(access_planes.price) as total_sales, packages.name as package_name, packages.duration as package_duration')
                    ->where('access_planes.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->where('user_id', $selected_user)
                    ->join('packages', 'access_planes.package_id', '=', 'packages.id')
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

        $sales = AccessPlanes::selectRaw('COUNT(*) AS row_count, SUM(access_planes.price) as total_sales, users.name as user_name')
            ->where('access_planes.camp_id', $camp_id)
            ->whereDate('purchaseDate', $today)
            ->join('users', 'access_planes.user_id', '=', 'users.id')
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
                $sales = AccessPlanes::selectRaw('COUNT(*) AS row_count, SUM(access_planes.price) as total_sales, users.name as user_name')
                    ->where('access_planes.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->join('users', 'access_planes.user_id', '=', 'users.id')
                    ->groupBy('user_id', 'users.name')
                    ->paginate(10);

                return view('Reports.rpt_user_sales_summary', compact('camp', 'sales', 'start_date', 'end_date'));
            break;

            case 'excel':
                $data = AccessPlanes::selectRaw('COUNT(*) AS row_count, SUM(access_planes.price) as total_sales, users.name as user_name')
                    ->where('access_planes.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->join('users', 'access_planes.user_id', '=', 'users.id')
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
                $data = AccessPlanes::selectRaw('COUNT(*) AS row_count, SUM(access_planes.price) as total_sales, users.name as user_name')
                    ->where('access_planes.camp_id', $camp_id)
                    ->whereBetween('purchaseDate', [$start_date, $end_date])
                    ->join('users', 'access_planes.user_id', '=', 'users.id')
                    ->groupBy('user_id', 'users.name')
                    ->get();
                $pdf = Pdf::loadView('pdf.user_sales_summary_pdf', compact('data','camp_name','start_date','end_date'));
                return $pdf->stream('user_sales_summary_from_'.$start_date.'_to_'. $end_date .'.pdf');
            break;
        }//switch
    } //rptUserSalesSummarySearch

    public function showCampSaleSummary()
    {
        $today = date('Y-m-d');
        $sales = [];
        $camps = Camps::where('status', 1)->get();

        foreach($camps as $camp)
        {
            $subscription_sale = AccessPlanes::where('camp_id', $camp->id)
                ->whereDate('purchaseDate', $today)
                ->where('accessable_type', 'App\Models\Subscriptions')
                ->sum('price');

            $subscription_count = AccessPlanes::where('camp_id', $camp->id)
                ->whereDate('purchaseDate', $today)
                ->where('accessable_type', 'App\Models\Subscriptions')
                ->count('price');

            $voucher_count = AccessPlanes::where('camp_id', $camp->id)
                ->whereDate('purchaseDate', $today)
                ->where('accessable_type', 'App\Models\Vouchers')
                ->count('price');

            $voucher_sale = AccessPlanes::where('camp_id', $camp->id)
                ->whereDate('purchaseDate', $today)
                ->where('accessable_type', 'App\Models\Vouchers')
                ->sum('price');

            $total_sale = AccessPlanes::where('camp_id', $camp->id)
                ->whereDate('purchaseDate', $today)
                ->sum('price');

            $sales [] = [
                'camp' => $camp->name,
                'subscription_count' => $subscription_count,
                'subscription_sale' => $subscription_sale,
                'voucher_count' => $voucher_count,
                'voucher_sale' => $voucher_sale,
                'total_sale' => $total_sale,
            ];
        }//foreach

        return view('Reports.rpt_camp_sale_summary', compact('sales'));
    }//show camp sale summary

    public function rptCampSaleSummarySearch(Request $request){
        $today = date('Y-m-d');
        $sales = [];
        $camp_total = 0;
        $camps = Camps::where('status', 1)->get();

        $start_date = $request->input('start_date') ?? $today;
        $end_date = $request->input('end_date') ?? $today;

        foreach($camps as $camp)
        {
            $subscription_sale = AccessPlanes::where('camp_id', $camp->id)
                ->whereBetween('purchaseDate', [$start_date, $end_date])
                ->where('accessable_type', 'App\Models\Subscriptions')
                ->sum('price');

            $subscription_count = AccessPlanes::where('camp_id', $camp->id)
                ->whereBetween('purchaseDate', [$start_date, $end_date])
                ->where('accessable_type', 'App\Models\Subscriptions')
                ->count('price');

            $voucher_count = AccessPlanes::where('camp_id', $camp->id)
                ->whereBetween('purchaseDate', [$start_date, $end_date])
                ->where('accessable_type', 'App\Models\Vouchers')
                ->count('price');

            $voucher_sale = AccessPlanes::where('camp_id', $camp->id)
                ->whereBetween('purchaseDate', [$start_date, $end_date])
                ->where('accessable_type', 'App\Models\Vouchers')
                ->sum('price');

            $total_sale = AccessPlanes::where('camp_id', $camp->id)
                ->whereBetween('purchaseDate', [$start_date, $end_date])
                ->sum('price');

            $sales [] = [
                'camp' => $camp->name,
                'subscription_count' => $subscription_count,
                'subscription_sale' => $subscription_sale,
                'voucher_count' => $voucher_count,
                'voucher_sale' => $voucher_sale,
                'total_sale' => $total_sale,
            ];

            $camp_total += floatVal($total_sale);
        }//foreach

        switch ($request->action) {
            case 'search':
                return view('Reports.rpt_camp_sale_summary', compact('sales', 'start_date', 'end_date'));
            break;

            case 'excel':

                $salesForExcel = collect($sales)->map(function ($row) {
                    return [
                        $row['camp'],
                        $row['subscription_count'],
                        $row['subscription_sale'],
                        $row['voucher_count'],
                        $row['voucher_sale'],
                        $row['total_sale'],
                    ];
                });

                return Excel::download(
                    new class($salesForExcel) implements FromCollection, WithHeadings {
                        protected $salesForExcel;
                        public function __construct($salesForExcel)
                        {
                            $this->salesForExcel = $salesForExcel;
                        }
                        public function collection()
                        {
                            return $this->salesForExcel;
                        }
                        public function headings(): array
                        {
                            return ['Camp', 'Subscription Count', 'Subscription Sale', 'Voucher Count', 'Voucher Sale', 'Total Sale'];
                        }
                    },
                    'camps_sales_summary_from_'.$start_date.'_to_'. $end_date .'.xlsx'
                );
            break;

            case 'pdf':
                $pdf = Pdf::loadView('pdf.camp_sale_summary_pdf', compact('sales', 'start_date','end_date', 'camp_total'));
                return $pdf->stream('camps_sales_summary_from_'.$start_date.'_to_'. $end_date .'.pdf');
            break;

        }//switch
    }//camp sale summary
}//class
