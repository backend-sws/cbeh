<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BedController;
use App\Http\Middleware\CheckPermission;
use App\Http\Controllers\BloodController;
use App\Http\Controllers\Chargecontroller;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PathologyController;
use App\Http\Controllers\AddmissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\IpdPatientController;
use App\Http\Controllers\IpdPaymentController;
use App\Http\Controllers\OpdPatientController;
use App\Http\Controllers\OpdPaymentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BirthReportController;
use App\Http\Controllers\DeathReportController;
use App\Http\Controllers\PatientCaseController;
use App\Http\Controllers\PaymentLogsController;
use App\Http\Controllers\PaymentModeController;
use App\Http\Controllers\DoctorChargeController;
use App\Http\Controllers\MedicineDumpController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ChargeCategoryController;
use App\Http\Controllers\CollectionLogsController;
use App\Http\Controllers\PrescriptionOpdController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\OpdDiagnosisTestController;
use App\Http\Controllers\IpdDiagnosisUploadController;
use App\Http\Controllers\IpdProgressNoteController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\OpdProgressNoteController;
use App\Http\Controllers\IpdVitalController;
use App\Http\Controllers\OngoingMedicationController;
use App\Http\Controllers\IpdInvestigationController;
use App\Http\Controllers\Settings\HospitalWebSettingsController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('/user/{name}', function ($name) {
//     return view('user-list');
// })->name('admin.user_list');
Route::prefix('admin')->middleware(['auth', 'verified', CheckPermission::class])->group(function () {
    Route::resource('patient-cases', PatientCaseController::class);
    Route::resource('/roles', RoleController::class);

    Route::get('/patients/{id}/cases', [PatientCaseController::class, 'getCases'])->name('getCases');
    // Route::get('/dashboard', [ProfileController::class, 'Dashboard'])->name('dashboard');
    // Route::get('/user/{name}', [ProfileController::class, 'user_list'])->name('admin.user_list');
    Route::get('/dashboard', [DashboardController::class, 'Dashboard'])->name('dashboard');


    Route::controller(UserController::class)->name('admin.users.')->group(function () {
        Route::get('/user/{name}', 'user_list')->name('user_list');
        Route::get('/user-add/{name}', 'add_user')->name('add');
        Route::post('/user-add-save/{name}', 'add_user_save')->name('add_save');
        Route::get('/user-edit/{name}/{id}', 'edit_user')->name('edit');
        Route::post('/user-edit/{name}/{id}', 'edit_user_save')->name('edit_save');
        Route::delete('/user/delete/{id}', 'delete_user')->name('delete');
    });

    Route::controller(CollectionLogsController::class)->prefix('collection/logs')->name('admin.collection.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    // Blood Management Routes
    Route::controller(BloodController::class)->group(function () {
        // Blood Bank Routes
        Route::prefix('blood-bank')->name('admin.blood-bank.')->group(function () {
            Route::get('/', 'bloodBankIndex')->name('index');
            Route::get('/create', 'bloodBankCreate')->name('create');
            Route::post('/', 'bloodBankStore')->name('store');
            Route::get('/{bloodBank}/edit', 'bloodBankEdit')->name('edit');
            Route::put('/{bloodBank}', 'bloodBankUpdate')->name('update');
            Route::delete('/{bloodBank}', 'bloodBankDestroy')->name('destroy');
        });

        // Blood Donor Routes
        Route::prefix('blood-donor')->name('admin.blood-donor.')->group(function () {
            Route::get('/', 'bloodDonorIndex')->name('index');
            Route::get('/create', 'bloodDonorCreate')->name('create');
            Route::post('/', 'bloodDonorStore')->name('store');
            Route::get('/{bloodDonor}/edit', 'bloodDonorEdit')->name('edit');
            Route::put('/{bloodDonor}', 'bloodDonorUpdate')->name('update');
            Route::delete('/{bloodDonor}', 'bloodDonorDestroy')->name('destroy');
        });

        // Blood Donation Routes
        Route::prefix('blood-donation')->name('admin.blood-donation.')->group(function () {
            Route::get('/', 'bloodDonationIndex')->name('index');
            Route::get('/create', 'bloodDonationCreate')->name('create');
            Route::post('/', 'bloodDonationStore')->name('store');
            Route::get('/{bloodDonation}/edit', 'bloodDonationEdit')->name('edit');
            Route::put('/{bloodDonation}', 'bloodDonationUpdate')->name('update');
            Route::delete('/{bloodDonation}', 'bloodDonationDestroy')->name('destroy');
        });

        // Blood Issue Routes
        Route::prefix('blood-issue')->name('admin.blood-issue.')->group(function () {
            Route::get('/', 'bloodIssueIndex')->name('index');
            Route::get('/create', 'bloodIssueCreate')->name('create');
            Route::post('/', 'bloodIssueStore')->name('store');
            Route::get('/{bloodIssue}/edit', 'bloodIssueEdit')->name('edit');
            Route::put('/{bloodIssue}', 'bloodIssueUpdate')->name('update');
            Route::delete('/{bloodIssue}', 'bloodIssueDestroy')->name('destroy');
            Route::get('/{bloodIssue}/print', 'bloodIssuePrint')->name('print');
            Route::get('/{bloodIssue}/view', 'bloodIssueView')->name('view');
        });
    });
    Route::controller(ScheduleController::class)->prefix('schedule')->name('admin.schedule.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'doctors_schedule')->name('doctors_schedule');
        Route::post('/add', 'doctor_schedule_save')->name('doctor_schedule_save');
        Route::get('/get/{id}', 'get_doctor_schedule')->name('doctor_schedule_get');
    });
    Route::controller(IpdPatientController::class)->prefix('ipd-patient')->name('admin.ipd.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'add_ipd_patient')->name('add');
        Route::post('/add', 'ipd_patient_save')->name('save');
        Route::get('/edit/{id}', 'edit_ipd_patient')->name('edit');
        Route::post('/edit/{id}', 'ipd_patient_edit_save')->name('edit_save');
        Route::get('/ipd-patient/detail/{id}', 'ipd_patient_detail')->name('detail');
        Route::post('/update-vitals/{id}', 'updateVitals')->name('update_vitals');
        Route::get('/add-prescription/{id}', 'ipd_patient_add_prescription')->name('add_prescription');
        Route::get('/get-charge/{id}', 'ipdcharge')->name('charge');
        Route::get('/discharge-slip/{id}', 'dischargeslip')->name('discharge');
        Route::put('/discharge/{id}', 'discharge')->name('discharge.patient');

        Route::get('/print/admission-slip/{id}', 'print_admission_slip')->name('print.admission_slip');

        Route::get('/print/discharge-slip/{id}', 'print_discharge_slip')->name('print.discharge_slip');
        Route::get('/print/no-dues/{id}', 'print_no_dues')->name('print.no_dues');
        Route::get('/print/pos-label/{id}', 'print_pos_label')->name('print.pos_label');

        Route::get('/ipd/blankreceipt/{id}', 'blankReceipt')->name('blankreceipt');
        Route::get('/print/service-chart/{id}', 'print_service_chart')->name('print.service_chart');
        // Discharge Slip Print Route
        Route::get('ipd/discharge-slip/{id}', [IpdPatientController::class, 'dischargeSlip'])->name('discharge.slip');

        Route::get('ipd/discharge/{id}', [IpdPatientController::class, 'dischargeForm'])->name('discharge.form');

        // 2. Save Discharge Data 
        Route::post('ipd/discharge/save/{id}', [IpdPatientController::class, 'dischargeSave'])->name('discharge.save');

        // 3. Print Discharge 
        Route::get('ipd/discharge-slip/{id}', [IpdPatientController::class, 'dischargeSlip'])->name('discharge.slip');

        // IPD Payment History Route
        Route::get('/payment-history/{id}', [IpdPatientController::class, 'getPaymentHistory']);

        // IPD Payment Receipt Print Route (Agar ye bhi missing ho)
        Route::get('ipd/payment/print/{id}', [IpdPatientController::class, 'printPaymentReceipt'])->name('payment.print');

        // Ongoing Medication & MAR (Medication Administration Record) routes
        Route::post('/medication-chart/order/{id}', [OngoingMedicationController::class, 'storeOrder'])->name('medication.store_order');
        Route::post('/medication-chart/administer/{doseId}', [OngoingMedicationController::class, 'administerDose'])->name('medication.administer');
        Route::post('/medication-chart/sos/{medicationId}', [OngoingMedicationController::class, 'administerSos'])->name('medication.sos');
        Route::post('/medication-chart/discontinue/{id}', [OngoingMedicationController::class, 'discontinue'])->name('medication.discontinue');
        Route::get('/medication-chart/print/{id}', [OngoingMedicationController::class, 'printMar'])->name('medication.print');
        Route::get('/medication-station', [OngoingMedicationController::class, 'wardStation'])->name('medication.station');
    });

    Route::get('/charge/category/all', [ChargeCategoryController::class, 'getAllCategories'])->name('admin.charge.category.all');
    Route::get('/charge/category/with-details', [ChargeCategoryController::class, 'getCategoriesWithDetails'])->name('admin.charge.category.details');
    Route::controller(OpdPatientController::class)->prefix('opd-patient')->name('admin.opd.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'add_opd_patient')->name('add');
        Route::post('/add', 'opd_patient_save')->name('save');
        Route::get('/edit/{id}', 'edit_ipd_patient')->name('edit');
        Route::post('/edit/{id}', 'opd_patient_edit_save')->name('edit_save');
        Route::get('/patient-cases/{patientId}', 'getPatientCases');
        Route::get('/generate-opd-no', 'generateOpdNo');
        Route::get('detail/{id}', 'opd_patient_detail')->name('detail');
        Route::get('/print/{id}', 'print')->name('print');
        Route::get('/search', 'searchPatients')->name('patient.search');
        Route::post('/opd/{id}/move-to-ipd', 'moveToIpd')->name('moveToIpd');

        Route::get('/opd/blankreceipt/{id}', 'blankReceipt')->name('blankreceipt');
        Route::post('/update-vitals/{id}', 'updateVitals')->name('update_vitals');
        Route::get('/get-charge/{id}', 'opdcharge')->name('charge')->where('id', '.*');
    });

    Route::resource('diagnosis-tests', OpdDiagnosisTestController::class)
        ->names('admin.diagnosis-tests');

    // Route::get('get-charge-types', [OpdDiagnosisTestController::class, 'getChargeTypes'])->name('diagnosis-tests.getChargeTypes');
    // Route::get('get-standard-charge', [OpdDiagnosisTestController::class, 'getStandardCharge'])->name('diagnosis-tests.getStandardCharge');

    Route::get('get-charge-categories', [OpdDiagnosisTestController::class, 'getChargeCategories'])->name('diagnosis-tests.getChargeCategories');
    Route::get('get-standard-charge', [OpdDiagnosisTestController::class, 'getStandardCharge'])->name('diagnosis-tests.getStandardCharge');
    Route::post('/diagnosis-tests', [OpdDiagnosisTestController::class, 'store'])->name('admin.diagnosis-tests.store');
    Route::get('diagnosis-tests/download/pdf', [OpdDiagnosisTestController::class, 'groupPdf'])->name('admin.diagnosis-tests.pdf');

    Route::get('/diagnosis-tests/pdf/{groupTestNo}', [OpdDiagnosisTestController::class, 'generateDiagnosisPdf'])
        ->name('diagnosis-tests.pdf');

    Route::get('/opd-patient/patient-details/{id}', function ($id) {
        $patient = \App\Models\User::findOrFail($id);
        return response()->json($patient);
    })->name('opd-patient.patient-details');
    Route::get('/opd-patient/doctor-charge/{doctorId}', function ($doctorId) {
        $charge = DB::table('opd_doctor_charges')->where('doctor_id', $doctorId)->value('charge');
        return response()->json(['charge' => $charge]);
    })->name('opd-patient.doctor-charge');

    Route::controller(AddmissionController::class)->prefix('admissions')->name('admin.admissions.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('delete');
        Route::patch('/admin/admissions/{id}/toggle-status', 'toggleStatus')->name('toggleStatus');

    });

    Route::controller(AppointmentController::class)->prefix('appointment')->name('admin.appointment.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'add_appointment')->name('add');
        Route::post('/add', 'appointment_save')->name('save');
        Route::get('/edit/{id}', 'edit_appointment')->name('edit');
        Route::post('/edit/{id}', 'edit_appointment_save')->name('edit_save');
    });
    Route::controller(DiagnosisController::class)->prefix('diagnosis')->name('admin.diagnosis.')->group(function () {
        Route::prefix('category')->name('diagnosis_category.')->group(function () {
            Route::get('/', 'diagnosis_category_index')->name('index');
            Route::post('/add', 'diagnosis_category_save')->name('save');
            Route::post('/edit/{id}', 'diagnosis_category_update')->name('update');
            Route::post('/delete/{id}', 'diagnosis_category_delete')->name('delete');

        });
        Route::prefix('test')->name('diagnosis_test.')->group(function () {
            Route::get('/', 'diagnosis_test_index')->name('index');
            Route::get('/add', 'diagnosis_test_add')->name('add');
            Route::post('/add', 'diagnosis_test_save')->name('save');
            Route::get('/edit/{id}', 'diagnosis_test_edit')->name('edit');
            Route::post('/edit/{id}', 'diagnosis_test_update')->name('update');
            Route::post('/delete/{id}', 'diagnosis_test_delete')->name('delete');
            Route::get('/Diagnosisbypatient/{id}', 'Diagnosisbypatient')->name('Diagnosisbypatient');
        });
        Route::post('/store', 'store')->name('store');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::put('/{id}', 'update')->name('update');
    });
    Route::controller(PrescriptionController::class)->prefix('prescription')->name('admin.prescription.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/add', 'add')->name('add');
        Route::post('ipd/add', 'save')->name('save');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/edit/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'delete')->name('delete');
        Route::get('/prescription-by-patient/{id}', 'prescription_by_patient')->name('prescription_by_patient');
        Route::get('/prescription-Details/{id}', 'prescriptionDetail')->name('prescription_detail');
        Route::get('/ipd/print/{id}', 'print')->name('print');


        // Route::post('/add', 'save')->name('save');
        // Route::get('/edit/{id}', 'edit')->name('edit');
        // Route::post('/edit/{id}', 'update')->name('update');
        // Route::post('/delete/{id}', 'delete')->name('delete');
    });
    Route::post('/opd/prescriptions', [PrescriptionOpdController::class, 'store'])->name('opd.prescriptions.store');
    Route::put('/opd/prescriptions/{prescription}', [PrescriptionOpdController::class, 'update'])->name('opd.prescriptions.update');
    Route::delete('/opd/prescriptions/{prescription}', [PrescriptionOpdController::class, 'destroy'])->name('opd.prescriptions.destroy');
    Route::get('/medicines/by-category/{category_id}', [PrescriptionOpdController::class, 'getMedicinesByCategory'])->name('opd.medicines.byCategory');

    Route::get('/opd/prescriptions/{prescription}', [PrescriptionOpdController::class, 'show'])->name('opd.prescriptions.show');
    Route::get('/opd/prescriptions/{prescription}/pdf', [PrescriptionOpdController::class, 'pdf'])->name('opd.prescriptions.pdf');


    Route::controller(DocumentController::class)->prefix('document')->name('admin.document.')->group(function () {
        Route::get('/', 'document_index')->name('document_index');
        Route::post('/add', 'document_save')->name('document_save');
        Route::post('/edit/{id}', 'document_update')->name('document_update');
        Route::post('/delete/{id}', 'document_delete')->name('document_delete');
        Route::get('/type', 'document_type_index')->name('document_type_index');
        Route::post('/type/add', 'document_type_save')->name('document_type_save');
        Route::post('/type/edit/{id}', 'document_type_update')->name('document_type_update');
        Route::post('/type/delete/{id}', 'document_type_delete')->name('document_type_delete');
    });



    Route::controller(ChargeController::class)->prefix('charge')->name('admin.charge.')->group(function () {

        // Add/Update/Delete Charges
        Route::post('add', 'store')->name('add');
        Route::put('edit', 'update')->name('update');
        Route::delete('delete/{id}', 'deletecharge')->name('delete');

        // Charge Types
        Route::get('charge-type', 'chargetype')->name('type');
        Route::post('charge-type-store', 'chargetypestore')->name('type.store');
        Route::put('HospitalchargeType/update/{id}', 'chargetypeupdate')->name('type.update');
        Route::delete('charge-type-delete/{id}', 'chargetypedelete')->name('type.delete');
        Route::get('charge-type-list', 'chargetypelist')->name('typelist');
        Route::get('typeById/{id}', 'typeById')->name('type.typeById');

        Route::get('charge-type-list', 'chargetypelist')->name('typelist');
        // Charge Categories

        Route::get('charge-category', 'chargecatlist')->name('chargeCategorylist');
        Route::post('charge-add', 'chargecatstore')->name('chargeType.store');
        Route::put('edit-charge-cat/{id}', 'EditHospitalchargetype')->name('HospitalchargeType.update');
        Route::put('edit/charge-category/{id}', 'EditHospitalchargecat')->name('HospitalchargeType.update');
        Route::delete('delete/chargeType/{id}', 'deletechargeType')->name('delete.charge.type');
        Route::get('get-categories-by-type/{id}', 'getCategoriesByType')->name('get.chargecatbytype');
        Route::get('get/Category/{id}', 'getCategoriesById')->name('get.chargecatbyid');
        Route::get('category/by/charge/type/{id}', 'categoryByChargeType')->name('category.data');
        // Hospital Charges
        Route::get('charge-list', 'Hospitalchargelist')->name('Hospitalcharge.list');
        Route::get('charge/{id}', 'Hospitalchargebyid')->name('Hospitalcharge.byid');
        Route::post('add-charge', 'storeHospitalcharge')->name('Hospitalcharge.add');
        Route::put('edit-charge/{id}', 'EditHospitalcharge')->name('Hospitalcharge.update');
        Route::delete('hospital-delete/{id}', 'deleteHospitalcharge')->name('Hospitalcharge.delete');
        Route::post('import-excel', 'importHospitalCharges')->name('Hospitalcharge.import');
        Route::get('download-sample', 'downloadSampleCharges')->name('Hospitalcharge.sample');

        // Additional
        Route::get('add-charge-data/{id}', 'chargedata')->name('charge-data');

    });

    Route::controller(DoctorChargeController::class)->prefix('doctor/charge')->name('admin.doctor.charge.')->group(function () {
        Route::get('list', 'list')->name('list');
        Route::post('add', 'store')->name('add');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('edit/{id}', 'update')->name('update');
        Route::delete('delete/{id}', 'deletecharge')->name('delete');

    });


    Route::controller(OperationController::class)->prefix('operation')->name('admin.operation.')->group(function () {
        Route::get('opnemelist/{id}', 'opnamelist')->name('opnamelist');
        Route::post('opnemeadd', 'opnemestore')->name('opnemeadd');
        Route::get('opnemeedit/{id}', 'opnemeedit')->name('opnemeedit');
        Route::put('opnemeedit/{id}', 'opnemeupdate')->name('opnemeupdate');
        Route::delete('opnemedelete/{id}', 'opnemedeletecharge')->name('opnemedelete');
        Route::get('/get-operations-by-category/{category_id}', 'getOperationsByCategory')->name('get-operations-by-category');
        Route::post('operation/category/add', 'operationCAtegoryAdd')->name('category.add');
        Route::post('operation/Name/add', 'operationNameAdd')->name('name.add');

        Route::get('operation/category/list', 'operationCatList')->name('cat.list');
        Route::get('operation/Name/list', 'operationNameList')->name('name.list');
        Route::delete('category/delete/{id}', 'operationCatDelete')->name('category.delete');
        Route::delete('name/delete/{id}', 'operationNameDelete')->name('name.delete');

    });

    Route::controller(BedController::class)->prefix('bed')->name('admin.bed.')->group(function () {

        Route::get('/status', [BedController::class, 'bedStatus'])->name('status');

        Route::get('/new-assign', [BedController::class, 'newBedAssign'])->name('newassign');
        Route::post('/assign/post', [BedController::class, 'assignpost'])->name('assign.store');
        Route::get('/new-assign-list', [BedController::class, 'newBedAssignList'])->name('newassignlist');
        Route::get('/assign/edit/{id}', [BedController::class, 'bedAssignEdit'])->name('assign.edit');
        Route::post('/assign/update/{id}', [BedController::class, 'bedAssignUpdate'])->name('assign.update');
        Route::post('/assign/delete/{id}', [BedController::class, 'bedAssignDelete'])->name('assign.delete');
        Route::get('/bed-list', [BedController::class, 'beds'])->name('beds');
        Route::post('/bed-store', [BedController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BedController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [BedController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [BedController::class, 'delete'])->name('delete');
        Route::get('/bulk-bed', [BedController::class, 'bulkBed'])->name('bulkbed');
        Route::post('/bulk-bed/add', 'bulk_bed_save')->name('bulk_bed_save');
        Route::post('/bulk-bed/edit/{id}', 'bulk_bed_update')->name('bulk_bed_update');
        Route::post('/bulk-bed/delete/{id}', 'bulk_bed_delete')->name('bulk_bed_delete');
        Route::get('/type', [BedController::class, 'bedType'])->name('type');
        Route::post('/type/add', 'bed_type_save')->name('type_save');
        Route::get('/type/edit/{id}', 'bed_type_edit')->name('type_edit');
        Route::put('/type/update/{id}', 'bed_type_update')->name('type_update');
        Route::delete('/type/delete/{id}', 'bed_type_delete')->name('type_delete');

        Route::get('/allbeds/{bedtype_id}', [BedController::class, 'allbeds'])->name('allbeds');
        Route::get('/bed-assign/{id}', [BedController::class, 'bedAssignById'])->name('bed.assign');

    });

    Route::controller(BillingController::class)->group(function () {
        //bill
        Route::prefix('bill')->name('admin.billing.')->group(function () {
            Route::get('/', 'index')->name('list_bill');
            // Route::get('/', 'list_bill')->name('list_bill');
            // Route::get('add', 'add_bill')->name('add_bill');
            // Route::post('add', 'add_bill_save')->name('add_bill_save');
            // Route::get('edit/{id}', 'edit_bill')->name('edit_bill');
            // Route::post('edit/{id}', 'edit_bill_save')->name('edit_bill_save');
            // Route::post('delete/{id}', 'delete_bill')->name('delete_bill');
            // Route::get('get-billing-charge/{id}', 'get_billing_charge')->name('get_billing_charge');
        });
        // employee pay roll
        Route::prefix('employee-payroll')->name('admin.billing.')->group(function () {
            Route::get('/', 'list_employee_payroll')->name('list_employee_payroll');
            Route::get('add', 'add_employee_payroll')->name('add_employee_payroll');
            Route::post('add', 'add_employee_payroll_save')->name('add_employee_payroll_save');
            Route::get('edit/{id}', 'edit_employee_payroll')->name('edit_employee_payroll');
            Route::post('edit/{id}', 'edit_employee_payroll_save')->name('edit_employee_payroll_save');
            Route::post('delete/{id}', 'delete_employee_payroll')->name('delete_employee_payroll');
        });

        Route::get('/invoice', 'Invoice')->name('billing.invoice');
        Route::get('/newinvoice', 'NewInvoice')->name('billing.newinvoice');
        Route::get('/account', 'Account')->name('billing.account');

        Route::get('/advancepayment', 'advancePayment')->name('billing.advancepayment');
        Route::get('/new-advance-payment', 'newAdvancePayment')->name('billing.newadvancepayment');
        Route::get('/payment', 'payment')->name('billing.payment');
        Route::get('/new-payment', 'newPayment')->name('billing.newpayment');
        Route::get('/payment-report', 'paymentReport')->name('billing.paymentreport');
        // Route::get('/employee-payroll','employeePayroll')->name('billing.employeepayroll');
        // Route::get('/new-employee-payroll','newEmployeePayroll')->name('billing.newemployeepayroll');
        Route::get('/manual-billing-payment', 'manualBillingPayment')->name('billing.manualbillingpayment');
    });

    Route::get('/get-medicine-details/{id}', [MedicineController::class, 'getMedicineDetails'])->name('get.medicine.details');
    // Medicine Department ke andar IPD Management
    Route::prefix('medicine/ipd')->group(function () {
        // 1. Search Page
        Route::get('/search', [App\Http\Controllers\MedicineController::class, 'ipd_search_index'])->name('admin.medicine.ipd.search');

        // 2. Patient Dashboard (Ledger + History)
        Route::get('/dashboard/{id}', [App\Http\Controllers\MedicineController::class, 'ipd_pharmacy_profile'])->name('admin.medicine.ipd.dashboard');

        // 3. New Bill Form (Specific for IPD Patient)
        Route::get('/new-bill/{ipd_id}', [App\Http\Controllers\MedicineController::class, 'bill_add_for_ipd'])->name('admin.medicine.ipd.bill.add');

        // Pay Route
        Route::post('/pay-account', [App\Http\Controllers\MedicineController::class, 'save_account_payment'])->name('admin.medicine.ipd.pay_account');
        // Delete payment
        Route::delete('/delete-payment/{id}', [App\Http\Controllers\MedicineController::class, 'delete_account_payment'])->name('admin.medicine.ipd.delete_payment');
        // Update payment
        Route::post('/update-payment/{id}', [App\Http\Controllers\MedicineController::class, 'update_account_payment'])->name('admin.medicine.ipd.update_payment');

        // Consolidated Print Route
        Route::get('/print-consolidated/{id}', [App\Http\Controllers\MedicineController::class, 'print_consolidated_bill'])->name('admin.medicine.ipd.print_consolidated');
    });
    Route::get('/medicine/expired-list', [MedicineDumpController::class, 'index'])->name('admin.medicine.expired');
    Route::post('/medicine/dump', [MedicineDumpController::class, 'store'])->name('admin.medicine.dump.save');
    Route::get('/medicine/dump-report', [MedicineDumpController::class, 'report'])->name('admin.medicine.dump.report');
    Route::controller(MedicineController::class)->prefix('medicine')->name('admin.medicine.')->group(function () {

        Route::get('/', 'medicine_index')->name('index');
        Route::get('/add', 'medicine_add')->name('add');
        Route::post('/add', 'medicine_save')->name('save');
        Route::get('/edit/{id}', 'medicine_edit')->name('edit');
        Route::post('/edit/{id}', 'medicine_update')->name('update');
        Route::delete('/delete/{id}', 'medicine_delete')->name('delete');

        // Bulk Excel/CSV Import Routes
        Route::post('/import', 'medicine_import')->name('import');
        Route::post('/import-root', 'medicine_import_root')->name('import.root');
        Route::get('/import-sample', 'medicine_import_sample')->name('import.sample');


        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/', 'medicine_category_index')->name('index');
            Route::post('/add', 'medicine_category_save')->name('save');
            Route::post('/toggle', 'medicine_category_toggle')->name('toggle');
            Route::post('/delete/{id}', 'medicine_category_delete')->name('delete');
            // Route::post('/edit/{id}', 'medicine_category_update')->name('update'); 
        });
        Route::prefix('brand')->name('brand.')->group(function () {
            Route::get('/', 'brand_index')->name('index');
            Route::post('/add', 'brand_save')->name('save');
            Route::post('/edit/{id}', 'brand_update')->name('update');
            Route::post('/delete/{id}', 'brand_delete')->name('delete');
        });

        Route::prefix('purchase')->name('purchase.')->group(function () {
            Route::get('/', 'purchase_index')->name('index');
            Route::get('/add', 'purchase_medicine_add')->name('add');
            Route::post('/add', 'purchase_medicine_save')->name('save');
            Route::get('/edit/{id}', 'purchase_medicine_edit')->name('edit');
            Route::post('/edit/{id}', 'purchase_medicine_update')->name('update');
            Route::get('/show/{id}', 'purchase_medicine_show')->name('show');
            Route::delete('/delete/{id}', 'purchase_medicine_delete')->name('delete');
        });
        Route::prefix('bill')->name('bill.')->group(function () {
            Route::get('/', 'bill_index')->name('index');
            Route::get('/add', 'bill_add')->name('add');
            Route::get('/show/{id}', 'bill_show')->name('show');
            Route::post('/add', 'bill_save')->name('save');
            Route::get('/edit/{id}', 'bill_edit')->name('edit');
            Route::post('/edit/{id}', 'bill_update')->name('update');
            Route::delete('/delete/{id}', 'bill_delete')->name('delete');

        });

        // Due Bills List Page
        Route::get('/due-bills', [MedicineController::class, 'due_bills_index'])->name('bill.due');
        // Save Due Payment
        Route::post('/bill/pay-due', [MedicineController::class, 'pay_due_save'])->name('bill.pay_due');
        Route::get('/payment/print/{id}', [MedicineController::class, 'print_payment_receipt'])->name('payment.print');
        // Fetch Payment History
        Route::get('/payment-history/{id}', [MedicineController::class, 'getPaymentHistory'])->name('bill.history');
        Route::get('sale', 'sale_index')->name('sale_medicine');
        route::get('get-medicine-by-category/{id}', [MedicineController::class, 'getMedicineByCategory'])->name('get-medicine-by-category');
        Route::get('all', [MedicineController::class, 'getAllMedicines']);
    });
    Route::controller(BedController::class)->prefix('bed')->name('admin.bed.')->group(function () {
        Route::get('/', 'index')->name('index');
        // Route::get('/add', 'add_bed')->name('add'); 
        // Route::post('/add', 'appointment_save')->name('save'); 
        // Route::get('/edit/{id}', 'edit_appointment')->name('edit'); 
        // Route::post('/edit/{id}', 'edit_appointment_save')->name('edit_save');
    });
    Route::controller(PaymentModeController::class)->prefix('Payment-Mode')->name('admin.Payment.Mode.')->group(function () {
        Route::post('add', 'store')->name('add');
        Route::put('edit', 'update')->name('update');
        Route::delete('delete/{id}', 'deletecharge')->name('delete');
        //
        Route::get('list', 'list')->name('list');
    });
    Route::controller(IpdPaymentController::class)->prefix('ipd-Payment')->name('admin.Payment.')->group(function () {
        Route::post('add', 'store')->name('add');
        Route::put('edit', 'update')->name('update');
        Route::delete('delete/{id}', 'deletecharge')->name('delete');
        //
        Route::get('list/{id}', 'list')->name('list')->where('id', '.*');
        Route::get('billcharge/{id}', 'billcharge')->name('billcharge')->where('id', '.*');
        Route::get('billcharge-pdf/{id}', 'pdf')->name('pdf')->where('id', '.*');
        Route::get('billcharge-per-day-pdf/{id}', 'pdfPerDay')->name('pdf_per_day')->where('id', '.*');

    });
    Route::controller(OpdPaymentController::class)->prefix('opd-Payment')->name('admin.OpdPayment.')->group(function () {
        Route::post('add', 'store')->name('add');
        Route::delete('delete/{id}', 'deletecharge')->name('delete');
        Route::get('list/{id}', 'list')->name('list')->where('id', '.*');
        Route::get('billcharge/{id}', 'billcharge')->name('billcharge')->where('id', '.*');
        Route::get('billcharge-pdf/{id}', 'pdf')->name('pdf')->where('id', '.*');
        Route::get('billcharge-per-day-pdf/{id}', 'pdfPerDay')->name('pdf_per_day')->where('id', '.*');
    });

    Route::controller(PathologyController::class)->prefix('pathology')->name('admin.pathology.')->group(function () {
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/', 'pathology_category_index')->name('index');
            Route::post('/save', 'pathology_category_save')->name('save');
            Route::post('/update/{id}', 'pathology_category_update')->name('update');
            Route::post('/delete/{id}', 'pathology_category_delete')->name('delete');
        });
        Route::prefix('test')->name('test.')->group(function () {
            Route::get('/', 'pathology_test_index')->name('index');
            Route::get('/add', 'pathology_test_add')->name('add');
            Route::post('/save', 'pathology_test_save')->name('save');
            Route::get('/edit/{id}', 'pathology_test_edit')->name('edit');
            Route::post('/update/{id}', 'pathology_test_update')->name('update');
            Route::delete('/delete/{id}', 'pathology_test_delete')->name('delete');
        });
        Route::prefix('bill')->name('bill.')->group(function () {
            Route::get('/', 'pathology_bill_index')->name('index');
            Route::get('/add', 'pathology_bill_add')->name('add');
            Route::post('/save', 'pathology_bill_save')->name('save');
            Route::get('/bill/detail/{id}', 'pathology_bill_show')->name('show');
            Route::get('/edit/{id}', 'pathology_bill_edit')->name('edit');
            Route::post('/update/{id}', 'pathology_bill_update')->name('update');
            Route::delete('/delete/{id}', 'pathology_bill_delete')->name('delete');
            Route::get('/pathology/bill/{id}', 'pathology_bill_print')->name('bill');
            Route::post('/pathology/payment/add', 'add_payment')->name('payment.add');
            // Print Individual Transaction Receipt
            Route::get('/payment/receipt/{id}', 'print_transaction_receipt')->name('receipt.print');
            // Fetch Bill Payment Details (AJAX)
            Route::get('/payments/{id}', 'getPaymentDetails')->name('payments');
            Route::post('/settle-ipd', 'settleFromIpdAdvance')->name('settle_ipd');
            Route::post('/settle-account', 'save_account_payment')->name('settle_account');
            Route::get('/ipd-profile/{id}', 'ipd_pathology_profile')->name('ipd.profile');
            Route::get('/ipd/statement/{id}', 'print_consolidated_bill')->name('ipd.statement');
            Route::get('/ipd/search-ajax', 'ajax_ipd_search')->name('ipd.search.ajax');

        });
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/', 'pathology_report_index')->name('index');
            Route::get('/get/{id}/{bill_id}', 'pathology_report_detail')->name('detail');
            Route::get('/add', 'pathology_report_add')->name('add');
            Route::post('/save', 'pathology_report_save')->name('store');
            Route::get('/edit/{id}', 'pathology_report_edit')->name('edit');
            Route::post('/update/{id}', 'pathology_report_update')->name('update');
            Route::post('/delete/{id}', 'pathology_report_delete')->name('delete');
            Route::get('/download/{Iid}', 'pathology_report_download')->name('download');
        });
        Route::prefix('unit')->name('unit.')->group(function () {
            Route::get('/', 'pathology_unit_index')->name('index');
            Route::post('/save', 'pathology_unit_save')->name('save');
            Route::post('/update/{id}', 'pathology_unit_update')->name('update');
            Route::post('/delete/{id}', 'pathology_unit_delete')->name('delete');
        });
        Route::prefix('parameter')->name('parameter.')->group(function () {
            Route::get('/', 'pathology_parameter_index')->name('index');
            Route::post('/save', 'pathology_parameter_save')->name('save');
            Route::post('/update/{id}', 'pathology_parameter_update')->name('update');
            Route::post('/delete/{id}', 'pathology_parameter_delete')->name('delete');
        });
    });


    Route::prefix('birth-report')->name('admin.birth_report.')->group(function () {
        Route::get('/', [BirthReportController::class, 'index'])->name('index');
        Route::get('/create', [BirthReportController::class, 'create'])->name('create');
        Route::post('/store', [BirthReportController::class, 'store'])->name('store');
        Route::get('/print/{id}', [BirthReportController::class, 'print'])->name('print');
    });

    Route::prefix('death-report')->name('admin.death_report.')->group(function () {
        Route::get('/', [DeathReportController::class, 'index'])->name('index');
        Route::get('/create', [DeathReportController::class, 'create'])->name('create');
        Route::post('/store', [DeathReportController::class, 'store'])->name('store');
        Route::get('/print/{id}', [DeathReportController::class, 'print'])->name('print');
    });

    Route::controller(HospitalWebSettingsController::class)->prefix('hospital-web-settings')->name('admin.hospital-web-settings.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/save-settings', 'update')->name('update');
        Route::post('/delete-image', 'deleteImage')->name('delete_image');
    });

    Route::controller(HospitalWebSettingsController::class)->prefix('hospital-print-settings')->name('admin.hospital-print-settings.')->group(function () {
        Route::get('/', 'printIndex')->name('index');
        Route::post('/save-settings', 'printUpdate')->name('update');
    });

    // IPD Diagnosis Uploads / Lab Uploads Routes
    Route::get('/ipd/{ipdPatientId}/diagnosis-uploads', [IpdDiagnosisUploadController::class, 'index'])->name('admin.ipd.diagnosis_uploads.index');
    Route::post('/ipd/{ipdPatientId}/diagnosis-uploads', [IpdDiagnosisUploadController::class, 'store'])->name('admin.ipd.diagnosis_uploads.store');
    Route::delete('/ipd/diagnosis-uploads/{id}', [IpdDiagnosisUploadController::class, 'destroy'])->name('admin.ipd.diagnosis_uploads.destroy');
    Route::get('/ipd/diagnosis-uploads/{id}/download', [IpdDiagnosisUploadController::class, 'download'])->name('admin.ipd.diagnosis_uploads.download');

    // IPD Diagnosis & Investigation Orders & Nursing Workflow Routes
    Route::get('/ipd/{ipdPatientId}/investigations', [IpdInvestigationController::class, 'index'])->name('admin.ipd.investigations.index');
    Route::post('/ipd/{ipdPatientId}/investigations', [IpdInvestigationController::class, 'store'])->name('admin.ipd.investigations.store');
    Route::post('/ipd/investigations/{id}/update-status', [IpdInvestigationController::class, 'updateStatus'])->name('admin.ipd.investigations.update_status');
    Route::delete('/ipd/investigations/{id}', [IpdInvestigationController::class, 'destroy'])->name('admin.ipd.investigations.destroy');
    Route::get('/ipd/investigations/{id}', [IpdInvestigationController::class, 'show'])->name('admin.ipd.investigations.show');

    // IPD Progress Notes Routes
    Route::get('/ipd/{ipdPatientId}/progress-notes', [IpdProgressNoteController::class, 'index'])->name('admin.ipd.progress_notes.index');
    Route::post('/ipd/{ipdPatientId}/progress-notes', [IpdProgressNoteController::class, 'store'])->name('admin.ipd.progress_notes.store');
    Route::post('/ipd/progress-notes/{id}/addendum', [IpdProgressNoteController::class, 'addAddendum'])->name('admin.ipd.progress_notes.addendum');
    Route::get('/ipd/progress-notes/{id}/print', [IpdProgressNoteController::class, 'print'])->name('admin.ipd.progress_notes.print');
    Route::delete('/ipd/progress-notes/{id}', [IpdProgressNoteController::class, 'destroy'])->name('admin.ipd.progress_notes.destroy');

    // OPD Progress Notes Routes
    Route::get('/opd/{opdPatientId}/progress-notes', [OpdProgressNoteController::class, 'index'])->name('admin.opd.progress_notes.index');
    Route::post('/opd/{opdPatientId}/progress-notes', [OpdProgressNoteController::class, 'store'])->name('admin.opd.progress_notes.store');
    Route::post('/opd/progress-notes/{id}/addendum', [OpdProgressNoteController::class, 'addAddendum'])->name('admin.opd.progress_notes.addendum');
    Route::get('/opd/progress-notes/{id}/print', [OpdProgressNoteController::class, 'print'])->name('admin.opd.progress_notes.print');
    Route::delete('/opd/progress-notes/{id}', [OpdProgressNoteController::class, 'destroy'])->name('admin.opd.progress_notes.destroy');

    // IPD Vitals Tracking & Chart Routes
    Route::get('/ipd/{ipdPatientId}/vitals', [IpdVitalController::class, 'index'])->name('admin.ipd.vitals.index');
    Route::post('/ipd/{ipdPatientId}/vitals', [IpdVitalController::class, 'store'])->name('admin.ipd.vitals.store');
    Route::delete('/ipd/vitals/{id}', [IpdVitalController::class, 'destroy'])->name('admin.ipd.vitals.destroy');
    Route::get('/ipd/{ipdPatientId}/vitals-chart', [IpdVitalController::class, 'chart'])->name('admin.ipd.vitals.chart');

    // OPD Vitals History & Destroy Routes
    Route::get('/opd/{opdPatientId}/vitals-history', [OpdPatientController::class, 'vitalsHistory'])->name('admin.opd.vitals.history');
    Route::delete('/opd/vitals/{id}', [OpdPatientController::class, 'destroyVital'])->name('admin.opd.vitals.destroy');

    // Audit Logs Routes
    Route::prefix('audit-logs')->name('admin.audit_logs.')->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('index');
        Route::get('/export/csv', [AuditLogController::class, 'export'])->name('export');
        Route::get('/{id}', [AuditLogController::class, 'show'])->name('show');
    });

});

//End Admin
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Route::resource('/roles', RoleController::class);
Route::middleware([CheckPermission::class, 'auth'])->group(function () {

    Route::name('roles.')->prefix('role')->group(function () {
        Route::resource('/permission', PermissionController::class);
        Route::get('/permissions', [PermissionController::class, 'permissions'])->name('permissions');
        Route::post('/assing-permission', [PermissionController::class, 'assing_permission'])->name('assing_permission');
    });

    Route::get('/role/role-permission', [PermissionController::class, 'role_permission'])->name('roles.role_permission');

    Route::get('/user/{name}', [ProfileController::class, 'user_list'])->name('admin.user_list');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Route::get('/roles/create', function () {return view('roles.index');})->name('roles.index');
Route::get('/admin/permissions', function () {
    return view('roles.permission_new');
})->name('admin.permissions.index');

require __DIR__ . '/auth.php';