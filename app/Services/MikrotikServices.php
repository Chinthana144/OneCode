<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;

class MikrotikServices
{
    protected $client;
    public $isConnected = false;

    public function __construct()
    {
        try{
            $this->client = new Client([
                'host' => 'hh50a4k5h3f.sn.mynetname.net',
                'user' => 'admin',
                'pass' => 'Admin*123',
                'port' => 8721,
                'timeout' => 3, // seconds
            ]);

            $this->isConnected = true; // success
        }
        catch (\Exception $e) {

            $this->isConnected = false; // failed
            // echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getInterfaces()
    {
        $query = new Query('/interface/print');
        return $this->client->query($query)->read();
    }

    public function getUsers()
    {
        if ($this->isConnected) {
            // Fetch all users
            $query = new Query('/user/print');
            return $this->client->query($query)->read();
        }
        else{
            return []; // Return empty array if not connected
        }
    }

    public function addUser($name, $password)
    {
        $query = new Query('/user/add');
        $query->equal('name', $name)->equal('password', $password)->equal('group', 'read');

        return $this->client->query($query)->read();

        $response = $this->client->read();
        dd($response);
    }

    public function checkConnection()
    {
        try {
            $this->client->query('/system/resource/print')->read();
            return true; // Connection successful
        } catch (\Exception $e) {
            return false; // Connection failed
        }
    }
}//class
