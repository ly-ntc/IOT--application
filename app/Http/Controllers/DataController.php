<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    
}
