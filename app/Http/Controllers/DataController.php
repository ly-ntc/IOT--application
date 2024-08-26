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

    // public function getAllData(){
    //     // Lấy tất cả dữ liệu nhiệt độ theo thời gian sớm nhất
    //     $allData = Data::orderBy('time', 'asc')->paginate(10) ;
    //     // $allData = Data::all();

    //     // Trả về dữ liệu dưới dạng JSON
    //     return view('pages.data_sensors', compact('allData'));
    // }
    public function getAllData(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10); // Default to 10 if not specified
        $allData = Data::paginate($itemsPerPage);

        return view('pages.data_sensors ', compact('allData'));
    }
}
