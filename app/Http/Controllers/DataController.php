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
    public function getLatestDust()
    {
        // Lấy 10 bản ghi mới nhất dựa trên cột 'time' và chỉ lấy giá trị 'dust' và 'time'
        $latestData = Data::latest('time')->select('time', 'dust')->take(10)->get();
    
        // Kiểm tra nếu không có dữ liệu
        if ($latestData->isEmpty()) {
            return response()->json(['error' => 'No data found'], 404);
        }
    
        // Trả về dữ liệu dưới dạng JSON
        return response()->json($latestData);
    }
    
    public function getAllData(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $temperature = $request->input('temperature');
        $humidity = $request->input('humidity');
        $light = $request->input('light');
        $dust = $request->input('dust');
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
        if(!empty($dust)){
            $query->where('dust', '>=', $dust)
                ->where('dust', '<', $dust + 1);
        }
        if (!empty($time)) {
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
        $server = '172.20.10.3'; 
        $port = 1993; 
        $clientId = 'mqtt_client_' . uniqid(); 

        
        $client = new MqttClient($server, $port, $clientId);
        $connectionSettings = (new ConnectionSettings)
            ->setUsername('ly')
            ->setPassword('123');

        try {
            
            $client->connect($connectionSettings, true);

            
            $mqttData = null;

            
            $client->subscribe('sensor/data', function (string $topic, string $message) use (&$mqttData) {
                
                list($temperature, $humidity, $light, $dust) = explode(',', $message);

                
                DB::table('datas')->insert([
                    'temperature' => $temperature,
                    'humidity' => $humidity,
                    'light' => $light,
                    'dust' => $dust,
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
            }, 0); 

            $client->loop(true, 1000); 

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
  