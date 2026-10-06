<?php

namespace App\Listeners;

use App\Events\PaymentCollectionEvent;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\PaymentCollection;

class SendPodcastNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentCollectionEvent $event)
    {
        $payment = $event->payment;
        $paymentType = $event->paymentType;
        $departmentType = $event->department_type;

        // dd($payment, $paymentType, $departmentType); // Debugging line to check values

        if($payment->payment_mode_id == 1){
            $payment->payment_mode_id = DB::table('payment_modes')->where('id', 1)->first()->name; // Assuming 1 is cash and you want to change it to card
        }elseif($payment->payment_mode_id == 2){
            $payment->payment_mode_id = DB::table('payment_modes')->where('id', 2)->first()->name; // Assuming 2 is card and you want to change it to cash
        }elseif($payment->payment_mode_id == 3){
            $payment->payment_mode_id = DB::table('payment_modes')->where('id', 3)->first()->name; // Assuming 3 is UPI and you want to change it to cash
        }elseif($payment->payment_mode_id == 4){
            $payment->payment_mode_id = DB::table('payment_modes')->where('id', 4)->first()->name; // Assuming 4 is Net Banking and you want to change it to cash
        }elseif($payment->payment_mode_id == 5){
            $payment->payment_mode_id = DB::table('payment_modes')->where('id', 5)->first()->name; // Assuming 5 is Cheque and you want to change it to cash
        }

        // dd($payment->payment_mode_id, $payment['payment_mode_id']); // Debugging line to check values


        PaymentCollection::create([
            'user_id' => Auth::id(),
            'amount' =>$payment->amount,
            'payment_method' =>$payment->payment_mode_id,
            'Payment_type' => $event->paymentType,
            'patient_id' =>$payment->patient_id,
            'department_type' => $event->department_type, // Default to 1 if not provided
        ]);
    }
}
