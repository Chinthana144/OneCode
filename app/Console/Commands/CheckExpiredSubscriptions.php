<?php

namespace App\Console\Commands;

use App\Models\Camps;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Customers;
use App\Services\HotspotUsers;
use App\Models\Subscriptions;

class CheckExpiredSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired subscriptions and remove users from MikroTik';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $expiredCustomers = Customers::where('expiry_datetime', '<=', $now)->get();

        foreach($expiredCustomers as $customer){
            $customer_camp_id = $customer->camp_id;
            $customer_username = $customer->username;
            $customer_mac = $customer->mac_address;

            $camp_data = Camps::find($customer_camp_id);

            if ($camp_data) {
                $hotspotService = new HotspotUsers(
                    $camp_data->mikritikIP,
                    $camp_data->mikrotikUsername,
                    $camp_data->mikrotikPassword,
                    $camp_data->mikritikPort
                );

                if($hotspotService->isConnected) {
                    // Remove the user from MikroTik
                    $hotspotService->deleteHotspotUser($customer_username);

                    if(!empty($customer_mac)) {
                        // Unbind the MAC address from the user
                        $hotspotService->unbindMacAddressFromUser($customer_mac);
                    }

                    $this->info("Removed expired user: {$customer_username} from camp: {$camp_data->name}");
                }//check connection

            } else {
                $this->error("Camp data not found for customer ID: {$customer->id}");
            }
        }//foreach

        //get expired subscriptions
        $expiredSubscriptions = Subscriptions::where('subscriptionEndTime', '<=', $now)->get();
        foreach($expiredSubscriptions as $subs){

            $subs->status = 3; //expired
            $subs->save();
        }
    }//handle
}//Command class
