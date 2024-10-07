<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function getAllAction(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10); // Default to 10 if not specified
        $deviceName = $request->input('deviceName');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Query lấy dữ liệu, áp dụng bộ lọc nếu có
        $query = Action::query();

        if (!empty($deviceName)) {
            $query->where('device_name', 'like', '%' . $deviceName . '%');
        }

        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif (!empty($startDate)) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif (!empty($endDate)) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Sắp xếp và phân trang dữ liệu
        $allData = $query->orderBy('created_at', 'desc')->paginate($itemsPerPage);

        // Trả về dữ liệu dưới dạng JSON
        return response()->json([
            'message' => 'Get all actions',
            'data' => $allData,
            'total' => $allData->total(),
            'perPage' => $allData->perPage(),
            'currentPage' => $allData->currentPage(),
        ]);
    }
}
