<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MedicineBill;
use App\Models\MedicineBillPayment;
use App\Models\PathologyBill;
use App\Models\PathologyPayment;
use Illuminate\Support\Facades\DB;

class SyncBillPayments extends Command
{
    protected $signature = 'sync:bill-payments';
    protected $description = 'Sync bill paid columns with actual payment transactions';

    public function handle()
    {
        $this->info("Starting Bill-Payment Sync...");

        DB::beginTransaction();
        try {
            // 1. Get all IPD Patients who have pharmacy/pathology activity
            $ipdPatients = \App\Models\IpdPatient::all();

            foreach ($ipdPatients as $ipd) {
                // --- PHARMACY RECONSTRUCTION ---
                // a. Reset all pharmacy bills for this patient
                MedicineBill::where('ipd_patient_id', $ipd->id)->update([
                    'paid_cash' => 0,
                    'paid_online' => 0,
                    'discount' => 0,
                    'payment_status' => 'unpaid'
                ]);

                // b. Get all payments (Initial + Account)
                $payments = MedicineBillPayment::where('ipd_patient_id', $ipd->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

                // c. Re-distribute using FIFO
                foreach ($payments as $pay) {
                    $remaining = $pay->amount;
                    $unpaid = MedicineBill::where('ipd_patient_id', $ipd->id)
                        ->where('payment_status', '!=', 'paid')
                        ->orderBy('id', 'asc')
                        ->get();

                    foreach ($unpaid as $bill) {
                        if ($remaining <= 0.01) break;
                        
                        $due = $bill->net_amount - ($bill->paid_cash + $bill->paid_online + $bill->discount);
                        if ($due <= 0.1) continue;

                        $toPay = min($remaining, $due);
                        
                        if ($pay->payment_mode == 'Online') $bill->increment('paid_online', $toPay);
                        elseif ($pay->payment_mode == 'Discount') $bill->increment('discount', $toPay);
                        else $bill->increment('paid_cash', $toPay);

                        $bill->refresh();
                        if (($bill->paid_cash + $bill->paid_online + $bill->discount) >= ($bill->net_amount - 0.1)) {
                            $bill->update(['payment_status' => 'paid']);
                        } else {
                            $bill->update(['payment_status' => 'partial']);
                        }
                        
                        $remaining -= $toPay;
                    }
                }

                // --- PATHOLOGY RECONSTRUCTION ---
                PathologyBill::where('ipd_patient_id', $ipd->id)->update([
                    'paid_amount' => 0,
                    'due_amount' => DB::raw('amount'),
                    'payment_status' => 'unpaid'
                ]);

                $pathPayments = PathologyPayment::where('ipd_patient_id', $ipd->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($pathPayments as $pay) {
                    $remaining = $pay->amount;
                    $unpaid = PathologyBill::where('ipd_patient_id', $ipd->id)
                        ->where('payment_status', '!=', 'paid')
                        ->orderBy('id', 'asc')
                        ->get();

                    foreach ($unpaid as $bill) {
                        if ($remaining <= 0.01) break;
                        
                        $due = $bill->amount - $bill->paid_amount;
                        if ($due <= 0.1) continue;

                        $toPay = min($remaining, $due);
                        $bill->increment('paid_amount', $toPay);
                        $bill->decrement('due_amount', $toPay);
                        
                        $bill->refresh();
                        if ($bill->paid_amount >= $bill->amount - 0.1) {
                            $bill->update(['payment_status' => 'paid']);
                        }
                        $remaining -= $toPay;
                    }
                }
            }

            DB::commit();
            $this->info("All IPD Ledgers Reconstructed Successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
        }
    }
}
