<?php

namespace App\Console\Commands;

use App\Http\Controllers\DataController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class MqttListener extends Command
{
    
    protected $signature = 'mqtt:fetch-data';

    protected $description = 'Fetch and save data from MQTT broker';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Call the saveData method from your controller
        $controller = new DataController();
        $controller->saveData(new \Illuminate\Http\Request());

        $this->info('MQTT data fetched and saved successfully!');
    }
}
