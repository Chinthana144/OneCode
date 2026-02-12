<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;

class HotspotUsers
{
    protected $client;
    public $isConnected = false;

    public function __construct($host, $user, $pwd, $port)
    {
        try{
            $this->client = new Client([
                'host' => $host,
                'user' => $user,
                'pass' => $pwd,
                'port' => (int)$port,
                'timeout' => 3, // seconds
            ]);
            $this->isConnected = true; // success

        } catch (\Exception $e) {
            // Handle connection error
            // echo "Connection failed: " . $e->getMessage();
            $this->isConnected = false; // failed
        }
    } //constructor

    public function getIdentity(){

        $response = [];

        if($this->isConnected){
            $query = new Query('/system/identity/print');
            $response = $this->client->query($query)->read();

            return $response;
        }
        else{
            return $response;
        }
    }//get identity


    //========================= HOOTSPOT USERS =========================//
        public function addHotspotUser($username, $user_pwd, $macAddress = null)
    {
        $query = new Query('/ip/hotspot/user/add');

        $query->equal('name', $username);
        $query->equal('password', $user_pwd);

        // Limit login to 1 device
        // $query->equal('shared-users', '1');

        // Bind MAC address (optional but recommended)
        if (!empty($macAddress)) {
            $query->equal('mac-address', $macAddress);
        }

        // Optional extras
        // $query->equal('profile', $package_name);
        $query->equal('comment', 'Added by web app API');

        $this->client->query($query)->read();
    }

    //get hotspos user
    public function getHotspotUser($username)
    {
        $printQuery = (new Query('/ip/hotspot/user/print'))->where('name', $username);

        $activeUser = $this->client->query($printQuery)->read();

        return $activeUser;
    }

    public function getAllhotspotUsers()
    {
        $query = new Query('/ip/hotspot/user/print');

        $response = $this->client->query($query)->read();
        return $response;
    }

    public function removeHotspotUserAndSession($username)
    {
        $printQuery = (new Query('/ip/hotspot/user/print'))->where('name', $username);

        $activeUser = $this->client->query($printQuery)->read();

        if (!empty($activeUser)) {

            $activeId = $activeUser[0]['.id'];

            //Remove active session
            $removeQuery = (new Query('/ip/hotspot/active/remove'))->where('.id', $activeId);
            $this->client->query($removeQuery)->read();

            //Remove user
            $deleteQuery = (new Query('/ip/hotspot/user/remove'))->where('.id', $activeId);
            $this->client->query($deleteQuery)->read();
        }
    }//remove hotspot user and session

    public function deleteHotspotUser($username)
    {
        $query = new Query('/ip/hotspot/user/remove');
        $query->equal('numbers', $username);

        try {
            $this->client->query($query)->read();
            // echo "Hotspot user deleted successfully!";
        } catch (\Exception $e) {
            echo "Error deleting hotspot user: " . $e->getMessage();
        }
    }

    //bind mac address to hotspot user
    public function bindMacAddressToUser($username, $mac)
    {
        $bindQuery = (new Query('/ip/hotspot/ip-binding/print'))->where('mac-address', $mac);
        $bound = $this->client->query($bindQuery)->read();

        if(empty($bound))
        {
            $bindAdd = new Query('/ip/hotspot/ip-binding/add');
            $bindAdd->equal('mac-address', $mac)
                    ->equal('type', 'bypassed')
                    ->equal('comment', "CloudTik bound for $username");

            $this->client->query($bindAdd)->read();
        }
    }

    //unbind mac address from hotspot user
    public function unbindMacAddressFromUser($mac)
    {
        $unbindQuery = (new Query('/ip/hotspot/ip-binding/print'))->where('mac-address', $mac);
        $bound = $this->client->query($unbindQuery)->read();

        if (!empty($bound)) {
            $unbindRemove = new Query('/ip/hotspot/ip-binding/remove');
            $unbindRemove->equal('numbers', $bound[0]['.id']);

            $this->client->query($unbindRemove)->read();
        }
    }

    //check connection
    public function CheckConnection(): bool
    {
        try {
            $query = new \RouterOS\Query('/system/identity/print');
            $this->client->query($query)->read();
            return true;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            //Log::error('MikroTik connection failed: ' . $e->getMessage());
            return false;
        }
    }
}//hotspot users class
