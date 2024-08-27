<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function getAllAction(Request $request)
    {
        $itemsPerPage = $request->input('itemsPerPage', 10); // Default to 10 if not specified
        $allData = Action::paginate($itemsPerPage);

        return view('pages.action_history ', compact('allData'));
    }
}
