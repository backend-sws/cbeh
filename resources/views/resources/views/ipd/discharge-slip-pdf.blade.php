{{-- filepath: resources/views/ipd/ipd-billing-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>IPD Billing PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; }
        .container { max-width: 900px; margin: 0 auto; padding: 24px; background: #fff; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f3f3f3; padding-bottom: 12px; }
        .clinic-info img { height: 48px; }
        .clinic-info .name { font-size: 18px; font-weight: bold; color: #1e293b; }
        .clinic-info .meta { font-size: 12px; color: #64748b; }
        .created-on { text-align: right; font-size: 12px; color: #64748b; }
        .created-on .date { font-weight: bold; color: #1e293b; }
        .section-title { font-size: 16px; font-weight: bold; color: #ec4899; margin-top: 24px; margin-bottom: 8px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .info-table td { padding: 6px 8px; font-size: 13px; }
        .info-table .label { color: #64748b; width: 30%; }
        .info-table .value { color: #1e293b; font-weight: 500; }
        .bill-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .bill-table th, .bill-table td { border: 1px solid #e5e7eb; padding: 6px 8px; font-size: 13px; }
        .bill-table th { background: #f9fafb; color: #1e293b; font-weight: 600; }
        .note { background: #f9fafb; border-radius: 4px; padding: 8px 12px; margin-bottom: 10px; color: #334155; }
        .footer { margin-top: 24px; font-size: 12px; color: #64748b; text-align: center; }
    </style>
</head>
<body>
    <div class="container">

        <!-- Header -->
        <div class="header">
            <div class="clinic-info">
                <img src="{{ public_path('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Logo" >
                <div class="name">DISCHARGE SLIP</div>
                <div class="meta" style="text-align: right;">{{ setting('address', 'Address') }}</div>
            </div>
            <div class="created-on">
               
                <div class="date">
                    
                </div>
            </div>
        </div>

        <!-- Patient Details -->
        <div class="section-title">Patient Details</div>
                @include('components.pdf-patient-info', [
            'docKey' => 'ipd_discharge',
            'patient' => $ipd_patient->patient ?? null,
            'uhid' => isset($UHID) ? $UHID->patient_unique_id : null,
            'ipd_no' => $ipd_patient->ipd_no ?? null,
            'doa' => $ipd_patient->admission_date ? \Carbon\Carbon::parse($ipd_patient->admission_date)->format('d-m-Y h:i A') : null,
            'dod' => $ipd_patient->discharge_date ? \Carbon\Carbon::parse($ipd_patient->discharge_date)->format('d-m-Y h:i A') : null,
            'bed_ward' => ($ipd_patient->bed->name ?? '') . ' / ' . ($ipd_patient->ward->name ?? ''),
            'doctor_name' => $ipd_patient->doctor->name ?? null,
        ])


        <!-- Charges Table -->
        <div class="section-title">Diagnosis</div>
        <table class="bill-table">
            <thead>
                <tr>
                    <th>Report Type</th>
                    <th>Report Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse($diagnosis as $diagnos)
                    <tr>
                        <td>{{ $diagnos->diagnosisCategory->diagnosis_category ?? 'N/A' }}</td>
                      
                        <td>{{ \Carbon\Carbon::parse($diagnos->created_at)->format('d-m-Y') }}</td>
                        <td>{{ $diagnos->cholesterol }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#888;">No Diagnosis found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Prescriptions Table -->
        <div class="section-title">Prescriptions</div>
        <table class="bill-table">
    <thead>
        <tr>
            <th>Medicine Category</th>
            <th>Medicine</th>
            <th>Dosage</th>
            <th>Dose Interval</th>
            <th>Instruction</th>
        </tr>
    </thead>
    <tbody>
       @forelse($prescriptions as $prescription)
    @foreach($prescription->prescriptionMedicines as $med)
        <tr>
            <td>{{ $med->medicine->categoryRelation->medicine_category ?? 'N/A' }}</td>
            <td>{{ $med->medicine->medicine ?? 'N/A' }}</td>
            <td>{{ $med->dosage ?? 'N/A' }}</td>
            <td>{{ $med->doseInterval ?? 'N/A' }}</td>
            <td>{{ $med->instructions ?? 'N/A' }}</td>
        </tr>
    @endforeach
@empty
    <tr>
        <td colspan="5">No prescriptions found.</td>
    </tr>
@endforelse

    </tbody>
</table>



        <!-- Footer Notes -->
        @if(!empty($prescription->footernotes))
            <div class="note" style="margin-top:16px;">
                <strong>Footer Note:</strong> {{ $prescription->footernotes }}
            </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <hr style="margin: 24px 0 12px 0;">
            <div>Generated by HMS | Powered by {{   {{ setting('hospital_name', 'Hospital') }}}}</div>
        </div>

    </div>
</body>
</html>
