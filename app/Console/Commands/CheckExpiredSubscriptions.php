<?php

namespace App\Console\Commands;

use App\Models\AccessPlanes;
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

        $expired_planes = AccessPlanes::where('expire_at', '<=', $now)
            ->where('status', '<', 3)
            ->get();

        foreach ($expired_planes as $plan) {
            $camp_id = $plan->camp_id;
            $mac_address = $plan->mac_address;

            $customer = $plan->accessable_type == 'App\Models\Subscriptions' ? $plan->accessable->customer->fullname : $plan->accessable->username;
            $username = $plan->accessable_type == 'App\Models\Subscriptions' ? $plan->accessable->customer->username : $plan->accessable->code;

            $camp = Camps::find($camp_id);

            if($camp){
                $hotspotService = new HotspotUsers(
                    $camp->mikritikIP,
                    $camp->mikrotikUsername,
                    $camp->mikrotikPassword,
                    $camp->mikritikPort
                );

                if($hotspotService->isConnected) {

                    //remove hotspot and session
                    $hotspotService->removeHotspotUserAndSession($username);
                    
                    // if(!empty($mac_address)) {
                    //     // Unbind the MAC address from the user
                    //     $hotspotService->unbindMacAddressFromUser($mac_address);
                    // }

                    $this->info("Removed expired user: {$customer} - {$username} from camp: {$camp->name}");
                }//check connection
                else{
                    $this->info("Camp Mikrotik connection failed! at : {$now}");
                }
            }//has camp

            //change status
            $plan->status = 3;
            $plan->save();
        }//foreach
    }//handle
}//Command class
