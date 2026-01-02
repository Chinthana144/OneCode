<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;

class UserProfiles
{
    protected $client;

    public function __construct($host, $user, $pwd, $port)
    {
        $this->client = new Client([
            'host' => $host,
            'user' => $user,
            'pass' => $pwd,
            'port' => (int)$port,
        ]);
    }

    public function getPackages()
    {
        $query = new Query('/ip/hotspot/user/profile/print');
        return $this->client->query($query)->read();
    } //get packages

    public function createPackage($package_name, $download, $upload, $duration)
    {
        $speed = $upload . "M/" . $download . "M";
        $duration = $duration . "d";
        $query = new Query('/ip/hotspot/user/profile/add');
        $query->equal('name', $package_name);
        $query->equal('rate-limit', $speed);          // Upload/Download limit
        $query->equal('shared-users', 1);             // Number of concurrent logins
        $query->equal('session-timeout', $duration);  // Optional timeout
        //$query->equal('keepalive-timeout', '30m');  // dont add this occur errors for API

        $this->client->query($query);
        $response = $this->client->read();
    }

    public function updatePackage($old_name, $package_name, $download, $upload, $duration)
    {
        $speed = $download . "M/" . $upload . "M";
        $duration = $duration . "d";

        $findQuery = (new Query('/ip/hotspot/user/profile/print'))
            ->where('name', $old_name);
        if (!empty($findQuery)) {
            $profile = $this->client->query($findQuery)->read()[0];

            $editQuery = new Query('/ip/hotspot/user/profile/set');
            $editQuery->equal('.id', $profile['.id']);
            $editQuery->equal('name', $package_name);
            $editQuery->equal('rate-limit', $speed);
            $editQuery->equal('session-timeout', $duration);

            $this->client->query($editQuery);
            $response = $this->client->read();
        }
    }

    //check connection
    public function CheckConnection()
    {
        try {
            // Attempt a lightweight command
            $query = new \RouterOS\Query('/system/identity/print');
            $this->client->query($query)->read();
            return true;
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            //Log::error('MikroTik connection failed: ' . $e->getMessage());
            return false;
        }
    }

    public function addHotspotUser($user_name, $user_pwd)
    {
        $query = new Query('/ip/hotspot/user/add');
        $query->equal('name', $user_name);
        $query->equal('password', $user_pwd);

        $this->client->query($query);

        $response = $this->client->read();
        //dd($response);
    }
}//class user profiles
