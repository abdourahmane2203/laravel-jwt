<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BeepCallController extends Controller
{
    public function findByMdn($mdn) {
        return response()->json([
            'success' => true,
            'message' => 'Customer beep call infos',
            'data' => 'infos'
        ]);
    }
}
