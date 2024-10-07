<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;

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
            $query->where('device','like' , '%'.$device.'%');
        }

        if (!empty($action)) {
            $query->where('action', 'like', '%'.$action.'%');
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
}
