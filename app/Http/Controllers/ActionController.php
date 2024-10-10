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

        // Nhận giá trị từ các input tìm kiếm
        $device = $request->input('device');
        $action = $request->input('action');

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $sortField = $request->input('sortField', 'time'); // Cột mặc định là 'time'
        $sortDirection = $request->input('sortDirection', 'desc'); // Hướng mặc định là 'desc'

        $query = Action::query();

        if (!empty($device)) {
            //những thiết bị có tên chứa chuỗi device
            $query->where('device', 'like', '%' . $device . '%');
        }

        if (!empty($action)) {
            $query->where('action', 'like', '%' . $action . '%');
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
    public function getAcStatus()
{
    // Giả sử bạn lưu trạng thái AC trong bảng actions
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

    public function toggleAC(Request $request)
    {
        $status = $request->input('status'); // 'ON' or 'OFF'

        // Publish message to MQTT
        $this->publishToMqtt('device/ac', $status);
        // $this->subscribeFromMqtt('device/log');

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
        $status = $request->input('status'); // 'ON' or 'OFF'

        $this->publishToMqtt('device/light', $status);

        return response()->json(['success' => true]);
    }

    // Helper function to publish MQTT message

    private function publishToMqtt($topic, $message)
    {
        $server = '192.168.0.103'; // MQTT broker IP
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
    // public function getMqttData(Request $request)
    // {
    //     $server = '192.168.0.103'; // MQTT broker IP
    //     $port = 1993; // MQTT port
    //     $clientId = 'mqtt_client_' . uniqid(); // Unique client ID

    //     // Kết nối với MQTT broker
    //     $client = new MqttClient($server, $port, $clientId);
    //     $connectionSettings = (new ConnectionSettings)
    //         ->setUsername('ly')
    //         ->setPassword('123');
        
    //     try {
    //         $client->connect($connectionSettings, true);

           
    //         $mqttData = null;
    //         $check = false;
            
    //         $client->subscribe('device/log', function (string $topic, string $message) use (&$mqttData, &$check) {
               
    //             $mqttData = $message;

    //             list($device, $action) = explode(',', $mqttData);

    //             DB::table('actions')->insert([
    //                 'device' => $device,
    //                 'action' => $action,
    //                 'time' => now(),
    //                 'user_id' => 1, 
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //             $check = true;
    //         }, 0); 

            
    //         while (!$check) {
    //             $client->loop(true, true);
    //         }
            
    //         $client->disconnect();

    //         if ($mqttData) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Data retrieved and saved to database successfully',
    //                 'data' => $mqttData
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No data received from MQTT'
    //             ]);
    //         }
    //     } catch (MqttClientException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to connect to MQTT: ' . $e->getMessage()
    //         ]);
    //     }
    // }
    public function getMqttData(Request $request)
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
