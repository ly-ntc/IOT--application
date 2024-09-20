<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpMqtt\Client\MqttClient;

class MqttController extends Controller
{
    public function receive()
    {
        $server   = '192.168.0.100'; // Địa chỉ IP của MQTT broker
        $port     = 1993;            // Cổng của MQTT broker
        $clientId = 'laravel-client';
        $mqtt_user = 'ly';          // Username
        $mqtt_password = '123';     // Password

        try {
            $mqtt = new MqttClient($server, $port, $clientId);
            $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)
                ->setUsername($mqtt_user)
                ->setPassword($mqtt_password);
            $mqtt->connect($connectionSettings); // Thêm username và password
            echo "Connected to MQTT Broker.\n";

            $mqtt->subscribe('sensor/data', function ($topic, $message) {
                $data = explode(',', $message);
                if (count($data) === 3) {
                    // Lưu dữ liệu vào cơ sở dữ liệu
                    Data::create([
                        'temperature' => (float)$data[0],
                        'humidity' => (float)$data[1],
                        'light' => (float)$data[2],
                        'time' => Carbon::now(),
                    ]);
                    echo "Data saved: " . $message . "\n"; // In ra console
                } else {
                    echo "Invalid data format: " . $message . "\n";
                }
            });

            while ($mqtt->loop(true)) {
                // Giữ vòng lặp chạy
            }
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } finally {
            $mqtt->disconnect();
        }
    }
}
