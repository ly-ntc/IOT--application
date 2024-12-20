<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Facades\Mqtt;
use PhpMqtt\Client\MqttClient;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\MqttClientException;

class ActionController extends Controller
{
    public function getAllAction(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10);

        $device = $request->input('device');
        $action = $request->input('action');

        $time = $request->input('time');
        $sortField = $request->input('sortField', 'time');
        $sortDirection = $request->input('sortDirection', 'desc');

        $query = Action::query();

        if (!empty($device)) {
            $query->where('device', 'like', '%' . $device . '%');
        }

        if (!empty($action)) {
            $query->where('action', 'like', '%' . $action . '%');
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
    public function getAcStatus()
    {
        
        $latestAction = DB::table('actions')
            ->where('device', 'ac')
            ->orderBy('time', 'desc')
            ->first();

        if ($latestAction) {
            return response()->json([
                'success' => true,
                'status' => $latestAction->action
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No AC status found'
            ]);
        }
    }
    public function getFanStatus()
    {
       
        $latestAction = DB::table('actions')
            ->where('device', 'fan')
            ->orderBy('time', 'desc')
            ->first();

        if ($latestAction) {
            return response()->json([
                'success' => true,
                'status' => $latestAction->action
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No AC status found'
            ]);
        }
    }
    public function getLightStatus()
    {
        
        $latestAction = DB::table('actions')
            ->where('device', 'light')
            ->orderBy('time', 'desc')
            ->first();

        if ($latestAction) {
            return response()->json([
                'success' => true,
                'status' => $latestAction->action
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No AC status found'
            ]);
        }
    }
    public function getCookStatus()
    {
        
        $latestAction = DB::table('actions')
            ->where('device', 'cook')
            ->orderBy('time', 'desc')
            ->first();

        if ($latestAction) {
            return response()->json([
                'success' => true,
                'status' => $latestAction->action
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No AC status found'
            ]);
        }
    }
    // Toggle AC
    public function toggleAC(Request $request)
    {
        $status = $request->input('status'); 

        
        $this->publishToMqtt('device/ac', $status);
     
        return response()->json(['success' => true]);
    }

    // Toggle Fan
    public function toggleFan(Request $request)
    {
        $status = $request->input('status'); // 'ON' or 'OFF'

        // Publish message to MQTT
        $this->publishToMqtt('device/fan', $status);
        //  $this->subscribeFromMqtt('device/log');

        return response()->json(['success' => true]);
    }

    // Toggle Light
    public function toggleLight(Request $request)
    {
        $status = $request->input('status'); 

        $this->publishToMqtt('device/light', $status);

        return response()->json(['success' => true]);
    }
    //Toggle cook
    public function toggleCook(Request $request)
    {
        $status = $request->input('status'); 

        $this->publishToMqtt('device/cook', $status);

        return response()->json(['success' => true]);
    }
    public function alter(Request $request)
    {
      
        $status = $request->input('status'); // 'ON' or 'OFF'

        if (!in_array($status, ['ON', 'OFF'])) {
            return response()->json(['success' => false, 'message' => 'Invalid status value'], 400);
        }

        try {
           
            $this->publishToMqtt('device/alter', $status);

          
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
          
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish to MQTT: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCb()
    {
        $count = DB::table('datas')
            ->where('dust', '>', 70)
            ->whereDate('created_at', '=', Carbon::today()) 
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    private function publishToMqtt($topic, $message)
    {
        $server = '172.20.10.3'; // MQTT broker IP
        $port = 1993; // MQTT port
        $clientId = 'mqtt_client';

        // Connect to MQTT broker
        $client = new MqttClient($server, $port, $clientId);
        $connectionSettings = (new ConnectionSettings)
            ->setUsername('ly')
            ->setPassword('123');

        $client->connect($connectionSettings, true);
        $client->publish($topic, $message);

        $client->disconnect();
    }
   
    public function getMqttData(Request $request)
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
            $check = false;
            $timeout = 10; // Timeout in seconds
            $startTime = time();

            $client->subscribe('device/log', function (string $topic, string $message) use (&$mqttData, &$check, $client) {
                $mqttData = $message;

                list($device, $action) = explode(',', $mqttData);

                DB::table('actions')->insert([
                    'device' => $device,
                    'action' => $action,
                    'time' => now(),
                    'user_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $check = true;
                $client->interrupt();
            }, 0);

            while (!$check && (time() - $startTime) < $timeout) {
                $client->loop(true, true);
            }

            $client->disconnect();

            if ($mqttData) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data retrieved and saved to database successfully',
                    'data' => $mqttData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No data received from MQTT'
                ]);
            }
        } catch (MqttClientException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to MQTT: ' . $e->getMessage()
            ]);
        }
    }
}
