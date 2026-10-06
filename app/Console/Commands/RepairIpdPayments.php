<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MedicineBillPayment;
use App\Models\MedicineBill;
use App\Models\PathologyPayment;
use App\Models\PathologyBill;
use Illuminate\Support\Facades\DB;

class RepairIpdPayments extends Command
{
    protected $signature = 'repair:ipd-payments';
    protected $description = 'Link orphan payments to IPD patients';

    public function handle()
    {
        $this->info("Starting Data Repair...");

        DB::beginTransaction();
        try {
            // 1. Medicine
            $medicineUpdated = 0;
            $medicinePayments = MedicineBillPayment::where(function($q) {
                    $q->whereNull('ipd_patient_id')
                      ->orWhere('ipd_patient_id', 0)
                      ->orWhere('ipd_patient_id', '');
                })
                ->whereNotNull('medicine_bill_id')
                ->get();

            foreach ($medicinePayments as $payment) {
                $bill = MedicineBill::find($payment->medicine_bill_id);
                if ($bill && $bill->ipd_patient_id) {
                    $payment->ipd_patient_id = $bill->ipd_patient_id;
                    $payment->save();
                    $medicineUpdated++;
                }
            }
            $this->info("Pharmacy: Linked $medicineUpdated payments.");

            // 2. Pathology
            $pathologyUpdated = 0;
            $pathologyPayments = PathologyPayment::where(function($q) {
                    $q->whereNull('ipd_patient_id')
                      ->orWhere('ipd_patient_id', 0)
                      ->orWhere('ipd_patient_id', '');
                })
                ->whereNotNull('bill_id')
                ->get();

            foreach ($pathologyPayments as $payment) {
                $bill = PathologyBill::find($payment->bill_id);
                if ($bill && $bill->ipd_patient_id) {
                    $payment->ipd_patient_id = $bill->ipd_patient_id;
                    $payment->save();
                    $pathologyUpdated++;
                }
            }
            $this->info("Pathology: Linked $pathologyUpdated payments.");

            DB::commit();
            $this->info("Repair Completed Successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
        }
    }
}
