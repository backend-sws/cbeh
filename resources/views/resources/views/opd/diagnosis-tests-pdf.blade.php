{{-- filepath: resources/views/opd/diagnosis-tests-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Diagnosis Test PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #222; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .clinic-info { font-size: 13px; }
        .clinic-info .name { font-size: 18px; font-weight: bold; color: {!! setting('theme_color', '#e91e63') !!}; }
        .clinic-info .address { font-size: 12px; color: #555; }
        .clinic-info .contact { font-size: 12px; color: #555; }
        .section { margin-top: 18px; }
        .section-title { font-size: 15px; font-weight: bold; color: {!! setting('theme_color', '#e91e63') !!}; margin-bottom: 8px; }
        .info-table, .tests-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .info-table td { padding: 4px 8px; font-size: 13px; }
        .tests-table th, .tests-table td { border: 1px solid #ddd; padding: 6px 8px; font-size: 12px; }
        .tests-table th { background: #f3f4f6; color: #444; }
        .tests-table tr:nth-child(even) { background: #fafafa; }
        .footer { margin-top: 30px; font-size: 12px; color: #888; text-align: center; }
        .logo { height: 48px; }
    </style>
</head>
<body>
    @php $m = json_decode(setting('pdf_margins_opd_diagnosis', '{}'), true) ?? []; @endphp
    {{-- Header --}}
    <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
    @if(setting('show_header_opd_diagnosis', 'yes') == 'yes')
    <div class="header">
        <div class="clinic-info">
            <div>
                <img src="{{ public_path('img/site-logo.png') }}" class="logo" alt="Logo">
            </div>
            <div class="name">Your Clinic Name</div>
            <div class="address">C/303, Atlanta Shopping Mall Sudama Chowk, Surat, Gujarat 394101</div>
            <div class="contact">Phone: +917096336561 | Email: labs@infyom.in</div>
        </div>
        <div style="text-align:right; font-size:13px;">
            <div><b>Created On:</b><br>
                {{ $tests->first()?->created_at ? \Carbon\Carbon::parse($tests->first()->created_at)->format('jS M, Y h:i A') : '--' }}
            </div>
        </div>
    </div>
    @else
    <div style="height: {{ setting('spacer_opd_diagnosis', '180') }}px;"></div>
    @endif
    </div>

    {{-- Patient & OPD Info --}}
    <div class="section" style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
                @include('components.pdf-patient-info', [
            'docKey' => 'opd_diagnosis',
            'patient' => $opdPatient->patient ?? null,
            'uhid' => $patient_uhid ?? null,
            'ipd_no' => $opdPatient->opd_no ?? null,
            'invoice_no' => $opdPatient->invoice_no ?? null,
            'appointment_date' => $opdPatient->appointment_date ? \Carbon\Carbon::parse($opdPatient->appointment_date)->format('d-m-Y') : null,
            'doctor_name' => $opdPatient->doctor->name ?? null,
            'department_name' => $opdPatient->doctor->department->name ?? null,
        ])

    </div>

    {{-- Diagnosis Tests Table --}}
    <div class="section" style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
        <div class="section-title">Diagnosis Tests</div>
        <table class="tests-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Test Name</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Urgency</th>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Instructions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tests as $i => $test)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $test->test_name }}</td>
                        <td>{{ $test->test_code }}</td>
                        <td>{{ $test->test_type }}</td>
                        <td>{{ $test->urgency }}</td>
                        <td>{{ $test->test_date ? \Carbon\Carbon::parse($test->test_date)->format('d-m-Y') : '' }}</td>
                        <td>{{ $test->clinical_indication }}</td>
                        <td>{{ $test->special_instructions }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; color:#aaa;">No diagnosis tests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(setting('show_footer_opd_diagnosis', 'yes') == 'yes')
    <div class="footer" style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 0 }}px;">
        Generated by HMS | {{ now()->format('d-m-Y H:i') }}
    </div>
    @endif
</body>
</html>