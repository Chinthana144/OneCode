<?php

namespace App\Console\Commands;

use App\Models\Camps;
use App\Models\ClientSubscriptions;
use App\Models\Subscriptions;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DailySaleTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-sale-transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'transfer daily sale to client side';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $camps = Camps::where('monthly_target', '>=', 0)
        ->where('status', 1)
        ->get();

        foreach ($camps as $camp) {
            $camp_id = $camp->id;
            $monthly_target = (float)$camp->monthly_target;

            $today = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day'));

            $daily_sale = Subscriptions::where('camp_id', $camp_id)
                ->whereDate('purchaseDate', $yesterday)
                ->selectRaw('SUM(price) as daily_sale')
                ->first();

            $sale = $daily_sale->daily_sale ?? 0;

            if($sale > 0)
            {
                //get monthly sale
                $currentMonth = now()->month;
                $currentYear  = now()->year;

                $month_sale = Subscriptions::where('camp_id', $camp_id)
                    ->whereMonth('purchaseDate', $currentMonth)
                    ->whereYear('purchaseDate', $currentYear)
                    ->selectRaw('SUM(price) as monthly_sale')
                    ->first();

                $monthly_sale = $month_sale->monthly_sale ?? 0;

                //client month sale
                $client_month_sale = ClientSubscriptions::where('camp_id', $camp_id)
                    ->whereMonth('purchaseDate', $currentMonth)
                    ->whereYear('purchaseDate', $currentYear)
                    ->selectRaw('SUM(price) as client_monthly_sale')
                    ->first();

                $client_monthly_sale = $client_month_sale->client_monthly_sale ?? 0;

                //remaining days
                $today = Carbon::today();
                $endOfMonth = Carbon::now()->endOfMonth();
                $remainingDays = $today->diffInDays($endOfMonth) ?? 1;

                //get remaining target
                $completed_target = (float)$monthly_sale - (float)$client_monthly_sale - (float)$sale;

                $pending_target = $monthly_target - $completed_target;

                $current_daily_target = $pending_target / $remainingDays;

                //get upper rounded value of daily current target
                $ceiling = ceil($current_daily_target / 10) * 10;

                $transfer_amount = (float)$sale - $ceiling;

                $transfer_sale = 0;

                if($completed_target > 0)
                {
                    /*
                    * the ceiling amount is less than 30 percent of daily sale
                    */
                    if($sale * 0.3 > $ceiling)
                    {
                        $subs = Subscriptions::where('camp_id', $camp_id)
                        ->whereDate('purchaseDate', $yesterday)
                        ->get();

                        foreach ($subs as $sub) {
                            $price = (float)$sub->price;

                            $transfer_sale += $price;

                            if($transfer_sale >= $transfer_amount)
                            {
                                //break the loop when the transfer amount is completed
                                break;
                            }
                            else
                            {
                                // insert data to client table
                                $client_sub = ClientSubscriptions::create([
                                    'camp_id' => $sub->camp_id,
                                    'user_id' => $sub->user_id,
                                    'customer_id' => $sub->customer_id,
                                    'package_id' => $sub->package_id,
                                    'paymethod_id' => $sub->paymethod_id,
                                    'purchaseDate' => $sub->purchaseDate,
                                    'purchaseDateTime' => $sub->purchaseDateTime,
                                    'price' => $sub->price,
                                    'macAddress' => '0',
                                    'status' => $sub->status,
                                ]);
                            }
                        }//foreach
                    }//check sale
                }//completed target > 0

            }//has daily sale
        }//foreach
    }//handle
}//class
