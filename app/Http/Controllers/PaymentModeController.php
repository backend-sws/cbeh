<?php

namespace App\Http\Controllers;

use App\Models\PaymentMode;
use Illuminate\Http\Request;

class PaymentModeController extends Controller
{
    public function   list(){
$types = PaymentMode::select('id', 'name')->get();
    return response()->json($types);
 }
}
