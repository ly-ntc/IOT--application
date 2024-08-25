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
}
