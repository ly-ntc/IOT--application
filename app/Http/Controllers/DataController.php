<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        //day du lieu den trang dashboard
        //luon cap nhat nhiet do moi nhat theo thoi gian
    }
    //test nhiet do
    public function getLatestData()
    {
        // Lấy dữ liệu nhiệt độ mới nhất theo thời gian
        $latestData = Data::latest('time')->first();

        // Trả về dữ liệu dưới dạng JSON
        return response()->json($latestData);
    }

    public function getAllData(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10); // Default to 10 if not specified

        // Fetch all data ordered by 'time' in ascending order
        $allData = Data::orderBy('time', 'desc')->paginate($itemsPerPage);

        return view('pages.data_sensors', compact('allData'));
    }


    public function get10LatestData()
    {
        try {
            // Fetch the 10 latest records sorted by 'time' in descending order
            $latestData = Data::orderBy('time', 'desc')->take(10)->get();

            // Optionally transform the data if needed, e.g., formatting dates
            $formattedData = $latestData->map(function ($item) {
                return [
                    'time' => $item->time, // Adjust format as needed
                    'temperature' => $item->temperature,
                    'humidity' => $item->humidity,
                    'light' => $item->light,
                ];
            });

            // Return the data as JSON
            return response()->json($formattedData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
