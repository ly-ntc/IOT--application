<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\MqttClient;

class DataController extends Controller
{

    public function getLatestData()
    {

        $latestData = Data::latest('time')->first();

        return response()->json($latestData);
    }

    public function getAllData(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10);

        // Nhận giá trị từ các input tìm kiếm
        $temperature = $request->input('temperature');
        $humidity = $request->input('humidity');
        $light = $request->input('light');

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $sortField = $request->input('sortField', 'time'); // Cột mặc định là 'time'
        $sortDirection = $request->input('sortDirection', 'desc'); // Hướng mặc định là 'desc'

        $query = Data::query();

        if (!empty($temperature)) {
            $query->whereBetween('temperature', [$temperature, $temperature + 1]);
        }

        if (!empty($humidity)) {
            $query->whereBetween('humidity', [$humidity, $humidity + 1]);
        }

        if (!empty($light)) {
            $query->whereBetween('light', [$light, $light + 1]);
        }

        if (!empty($startDate) && !empty($endDate)) {

            $query->whereBetween('time', [$startDate, $endDate]);
        } elseif (!empty($startDate)) {
            
            $query->whereDate('time', '>=', $startDate);
        } elseif (!empty($endDate)) {
           
            $query->whereDate('time', '<=', $endDate);
        }
        $query->orderBy($sortField, $sortDirection);
        // Sắp xếp và phân trang dữ liệu
        $allData = $query->orderBy('time', 'desc')->paginate($itemsPerPage);
        
        // Trả về dữ liệu dưới dạng JSON
        return response()->json([
            'message' => 'Get all data successfully',
            'data' => $allData->items(),
            'total' => $allData->total(),
            'perPage' => $allData->perPage(),
            'currentPage' => $allData->currentPage(),
            'lastPage' => $allData->lastPage(),
            'links' => (string) $allData->links() 
        ]);
        
    }

    public function get10LatestData()
    {
        try {

            $latestData = Data::orderBy('time', 'desc')->take(10)->get();


            $formattedData = $latestData->map(function ($item) {
                return [
                    'time' => $item->time,
                    'temperature' => $item->temperature,
                    'humidity' => $item->humidity,
                    'light' => $item->light,
                ];
            });


            return response()->json($formattedData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveData(Request $request)
    {
        $server = '192.168.0.103'; // MQTT broker IP
        $port = 1993; // MQTT port
        $clientId = 'mqtt_client_' . uniqid(); // Unique client ID
    
        // Kết nối với MQTT broker
        $client = new MqttClient($server, $port, $clientId);
        $connectionSettings = (new ConnectionSettings)
            ->setUsername('ly')
            ->setPassword('123');
    
        try {
            // Connect to the MQTT broker
            $client->connect($connectionSettings, true);
    
            // Variable to store the received data
            $mqttData = null;
    
            // Subscribe to the 'sensor/data' topic and define the callback
            $client->subscribe('sensor/data', function (string $topic, string $message) use (&$mqttData) {
                // Split the incoming message into temperature, humidity, and light values
                list($temperature, $humidity, $light) = explode(',', $message);
    
                // Save the data into the database
                DB::table('datas')->insert([
                    'temperature' => $temperature,
                    'humidity' => $humidity,
                    'light' => $light,
                    'time' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                // Store the data for response
                $mqttData = [
                    'temperature' => $temperature,
                    'humidity' => $humidity,
                    'light' => $light
                ];
            }, 0); // QoS level set to 0
    
            // Wait for a short duration to receive the message
            $client->loop(true, 1000); // Duration of loop for processing
    
            // Disconnect from the MQTT broker after processing
            $client->disconnect();
    
            // Check if data was received and return a response accordingly
            if ($mqttData) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data retrieved and saved to the database successfully',
                    'data' => $mqttData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No data received from MQTT'
                ]);
            }
        } catch (MqttClientException $e) {
            // Handle any exceptions during the MQTT connection or subscription
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to MQTT: ' . $e->getMessage()
            ]);
        }
    }
    
    
}
