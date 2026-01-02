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

    public function addHotspotUser($username, $user_pwd)
    {
        $query = new Query('/ip/hotspot/user/add');
        $query->equal('name', $username);
        $query->equal('password', $user_pwd);
        // $query->equal('profile', $package_name); // Assign to created profile
        // $query->equal('comment', 'Created by CloudTik system');

        $this->client->query($query)->read();

        // try {
        //     $this->client->query($query)->read();
        //     // echo "Hotspot user created successfully!";
        // } catch (\Exception $e) {
        //     echo "Error creating hotspot user: " . $e->getMessage();
        // }
    }

    public function getAllhotspotUsers()
    {
        $query = new Query('/ip/hotspot/user/print');
        $query->equal('.proplist', '.id,name,password,profile,comment');

        try {
            $response = $this->client->query($query)->read();
            return $response;
        } catch (\Exception $e) {
            echo "Error fetching hotspot users: " . $e->getMessage();
            return [];
        }
    }

    public function getHotspotUserByUsername($username)
    {
        $query = new Query('/ip/hotspot/user/print');
        $query->equal('name', $username);
        $query->equal('.proplist', '.id,name,password,profile,comment');

        try {
            $response = $this->client->query($query)->read();
            return $response;
        } catch (\Exception $e) {
            echo "Error fetching hotspot user: " . $e->getMessage();
            return [];
        }
    }

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
