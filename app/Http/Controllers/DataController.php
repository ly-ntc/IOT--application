<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Carbon\Carbon;
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
        $temperature = $request->input('temperature');
        $humidity = $request->input('humidity');
        $light = $request->input('light');
        $time = $request->input('time');
        $sortField = $request->input('sortField', 'time'); 
        $sortDirection = $request->input('sortDirection', 'desc'); 

        $query = Data::query();

        if (!empty($temperature)) {
            $query->where('temperature', '>=', $temperature)
                  ->where('temperature', '<', $temperature + 1);
        }
        
        if (!empty($humidity)) {
            $query->where('humidity', '>=', $humidity)
                  ->where('humidity', '<', $humidity + 1);
        }
        
        if (!empty($light)) {
            $query->where('light', '>=', $light)
                  ->where('light', '<', $light + 1);
        }
        if(!empty($time)){
            try {
                $time1 = Carbon::createFromFormat('Y-m-d\TH:i', $time);
            } catch (\Exception $e) {
                return back()->withErrors(['searchTime' => 'Invalid datetime format']);
            }
        
            $startOfMinute = $time1->format('Y-m-d H:i:00'); 
            $endOfMinute = $time1->format('Y-m-d H:i:59');    
            $query->whereBetween('time', [$startOfMinute, $endOfMinute]);
        }

        $query->orderBy($sortField, $sortDirection);
        $allData = $query->orderBy('time', 'desc')->paginate($itemsPerPage);
        
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
        $port = 1993; // MQTT broker port
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
   
            $client->loop(true, 1000); // Duration of loop for processing
    
          
            $client->disconnect();
    
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
