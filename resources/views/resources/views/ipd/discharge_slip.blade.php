<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discharge Summary - {{ $ipdPatient->ipd_no }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            margin: 0;
            padding: 0;
            background: #e2e8f0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.4;
        }

        /* Top Action Bar (hidden in print) */
        .top-action-bar {
            background: #1e293b;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .top-action-bar .btn {
            padding: 7px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            transition: all 0.2s;
        }
        .btn-print {
            background: #1565c0;
            color: #fff;
        }
        .btn-print:hover {
            background: #0d47a1;
        }
        .btn-edit {
            background: #f59e0b;
            color: #fff;
        }
        .btn-edit:hover {
            background: #d97706;
        }
        .btn-back {
            background: #475569;
            color: #fff;
        }
        .btn-back:hover {
            background: #334155;
        }

        /* A4 Page Container */
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: #ffffff;
            padding: 9mm 13mm 7mm 13mm;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .screen-page-divider {
            width: 210mm;
            margin: 15px auto;
            text-align: center;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .screen-page-divider::before,
        .screen-page-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #cbd5e1;
        }

        /* Header Layout */
        .doc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 9px;
            border-bottom: 2px solid #0f172a;
            margin-bottom: 9px;
        }
        .header-logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 32%;
        }
        .header-logo-area img {
            height: 56px;
            max-width: 155px;
            object-fit: contain;
        }
        .header-title-area {
            text-align: center;
            width: 44%;
        }
        .header-title-area h1 {
            margin: 0;
            font-size: 23px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header-title-area p {
            margin: 3px 0 0 0;
            font-size: 11px;
            color: #475569;
            font-weight: 600;
        }
        .header-mrn-area {
            text-align: right;
            width: 24%;
        }
        .mrn-label {
            font-size: 10.5px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .mrn-value {
            font-size: 15.5px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
        }

        /* Section Bar */
        .section-bar {
            background-color: #1565c0 !important;
            color: #ffffff !important;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 4.5px 9px;
            margin-top: 7px;
            margin-bottom: 0;
            border-radius: 3px 3px 0 0;
            display: flex;
            align-items: center;
        }

        /* Section Content Containers */
        .section-box {
            border: 1px solid #cbd5e1;
            border-top: none;
            padding: 7px 9px;
            background: #ffffff;
            margin-bottom: 7px;
            border-radius: 0 0 3px 3px;
            min-height: 32px;
            font-size: 12px;
            line-height: 1.45;
            color: #0f172a;
            word-break: break-word;
        }

        /* Standard Table Grid */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            border-top: none;
            margin-bottom: 7px;
            font-size: 11.5px;
            table-layout: fixed;
        }
        .grid-table td, .grid-table th {
            border: 1px solid #cbd5e1;
            padding: 5px 7px;
            vertical-align: middle;
            line-height: 1.35;
        }
        .grid-table .label-col {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            width: 22%;
            font-size: 11.5px;
        }
        .grid-table .val-col {
            color: #0f172a;
            font-weight: 700;
            width: 28%;
            font-size: 12px;
        }

        /* Examination Grid */
        .exam-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            border-top: none;
            margin-bottom: 7px;
            font-size: 11.5px;
            table-layout: fixed;
        }
        .exam-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 7px;
            vertical-align: top;
            line-height: 1.35;
        }
        .exam-table .exam-title {
            color: #475569;
            font-size: 10px;
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .exam-table .exam-val {
            color: #0f172a;
            font-weight: 700;
            font-size: 12.5px;
        }

        /* Investigations & Procedures Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            border-top: none;
            margin-bottom: 7px;
            font-size: 11.5px;
            table-layout: fixed;
        }
        .data-table th {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            border: 1px solid #cbd5e1;
            padding: 5.5px 8px;
            text-align: left;
            font-size: 11.5px;
            letter-spacing: 0.3px;
        }
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 8px;
            vertical-align: top;
            color: #0f172a;
            line-height: 1.35;
        }

        /* Advice checklist */
        .advice-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .advice-list li {
            position: relative;
            padding-left: 16px;
            margin-bottom: 4px;
            font-size: 12px;
            color: #0f172a;
            line-height: 1.45;
        }
        .advice-list li::before {
            content: "■";
            position: absolute;
            left: 0;
            top: 0;
            font-size: 9px;
            color: #1565c0;
        }

        /* Bottom Split Section */
        .bottom-split-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            margin-top: 7px;
            margin-bottom: 5px;
            table-layout: fixed;
        }
        .bottom-split-table td {
            border: 1px solid #cbd5e1;
            vertical-align: top;
            padding: 7px 9px;
        }
        .bottom-header-text {
            font-size: 11.5px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 6px;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 3px;
            letter-spacing: 0.4px;
        }

        /* Footer line */
        .doc-footer {
            border-top: 1.5px solid #0f172a;
            padding-top: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10.5px;
            color: #475569;
            font-weight: 600;
            margin-top: auto;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .no-print,
            .top-action-bar,
            .screen-page-divider {
                display: none !important;
            }
            .page {
                width: 100% !important;
                min-height: 297mm !important;
                max-height: 297mm !important;
                height: 297mm !important;
                margin: 0 !important;
                padding: 8mm 12mm 6mm 12mm !important;
                box-shadow: none !important;
                border: none !important;
                page-break-after: always !important;
                break-after: page !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
            }
            .page-2 {
                page-break-after: avoid !important;
                break-after: avoid !important;
            }
            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>
</head>
<body>

@php
    // Logo resolution
    $logoPath = null;
    $possibleLogos = [
        '1788500069_codeblue_LOGO-removebg-preview.png',
        '1784891002_1784378272_Code-Blue-logo.webp',
        '1784378272_Code-Blue-logo.png',
        '1788499904_codeblue_LOGO.png',
    ];
    foreach ($possibleLogos as $l) {
        if (file_exists(public_path('img/' . $l))) {
            $logoPath = asset('img/' . $l);
            break;
        }
    }
    if (!$logoPath && !empty(setting('logo')) && file_exists(public_path('img/' . setting('logo')))) {
        $logoPath = asset('img/' . setting('logo'));
    }

    $discharge = $ipdPatient->discharge ?? new \App\Models\IpdDischarge();

    // Dates
    $doa = $ipdPatient->admission_date ? \Carbon\Carbon::parse($ipdPatient->admission_date)->format('d/m/Y h:i A') : '—';
    $dod = ($discharge && !empty($discharge->discharge_date)) 
        ? \Carbon\Carbon::parse($discharge->discharge_date)->format('d/m/Y h:i A') 
        : ($ipdPatient->discharge_date ? \Carbon\Carbon::parse($ipdPatient->discharge_date)->format('d/m/Y h:i A') : '—');

    // Bed & Ward
    $wardBed = trim(($ipdPatient->beds->bed_name ?? ($ipdPatient->beds->name ?? '')) . (!empty($ipdPatient->beds->bedType->name) ? ' / ' . $ipdPatient->beds->bedType->name : ''));
    if (empty($wardBed)) $wardBed = '—';

    // Doctor
    $doctorName = $discharge->treating_physician ?? ($ipdPatient->doctor->name ?? '—');


    // Patient
    $patientName = $ipdPatient->patient->name ?? '—';
    $age = $ipdPatient->patient->patientProfile->age ?? ($ipdPatient->patient->age ?? '—');
    $gender = ucfirst($ipdPatient->patient->patientProfile->gender ?? ($ipdPatient->patient->gender ?? '—'));
    $fatherSpouse = $discharge->father_spouse ?? ($ipdPatient->patient->patientProfile->guardian_name ?? ($ipdPatient->patient->patientProfile->father_name ?? '—'));
    $address = $ipdPatient->patient->patientProfile->address ?? ($ipdPatient->patient->address ?? '—');
    $mobile = $ipdPatient->patient->phone ?? ($ipdPatient->patient->patientProfile->mobile ?? '—');

    // Decode Structured Data
    $investigationsList = [];
    if (!empty($discharge->investigations)) {
        $decodedInv = json_decode($discharge->investigations, true);
        if (is_array($decodedInv)) {
            $investigationsList = $decodedInv;
        }
    }

    $proceduresList = [];
    if (!empty($discharge->procedures)) {
        $decodedProc = json_decode($discharge->procedures, true);
        if (is_array($decodedProc)) {
            $proceduresList = $decodedProc;
        }
    }

    $medicationsList = [];
    if (!empty($discharge->discharge_medications)) {
        $decodedMed = json_decode($discharge->discharge_medications, true);
        if (is_array($decodedMed)) {
            $medicationsList = $decodedMed;
        }
    }
@endphp

<!-- Top Navigation & Actions (Hidden during Print) -->
<div class="top-action-bar no-print">
    <div style="display:flex; align-items:center; gap:12px;">
        <a href="{{ route('admin.ipd.index') }}" class="btn btn-back">
            ← Back to IPD Patients
        </a>
        <a href="{{ route('admin.ipd.discharge.form', $ipdPatient->id) }}" class="btn btn-edit">
            ✎ Edit Discharge Data
        </a>
    </div>
    <div style="font-size:13px; font-weight:700;">
        IPD: {{ $ipdPatient->ipd_no }} | Patient: {{ $patientName }}
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-print">
            🖨 Print Discharge Summary (2 Pages)
        </button>
    </div>
</div>

<!-- ==================== PAGE 1 ==================== -->
<div class="page page-1">
    <div>
        <!-- Document Header -->
        <div class="doc-header">
            <div class="header-logo-area">
                @if($logoPath)
                    <img src="{{ $logoPath }}" alt="Code Blue Emergency Hospital">
                @else
                    <div style="font-size:18px; font-weight:800; color:#1565c0; text-transform:uppercase;">CODE BLUE</div>
                @endif
            </div>
            <div class="header-title-area">
                <h1>DISCHARGE SUMMARY</h1>
                <p>Emergency &amp; Critical Care Services &bull; Muzaffarpur, Bihar</p>
            </div>
            <div class="header-mrn-area">
                <span class="mrn-label">MRN / UHID</span><br>
                <span class="mrn-value">{{ $uhid ?? $ipdPatient->ipd_no }}</span>
            </div>
        </div>

        <!-- Section 1: Patient & Admission Details -->
        <div class="section-bar">1. PATIENT &amp; ADMISSION DETAILS</div>
        <table class="grid-table">
            <tr>
                <td class="label-col">Patient Name</td>
                <td class="val-col">{{ $patientName }}</td>
                <td class="label-col">Age / Sex</td>
                <td class="val-col">{{ $age }} Y / {{ $gender }}</td>
            </tr>
            <tr>
                <td class="label-col">Father / Spouse</td>
                <td class="val-col">{{ $fatherSpouse ?: '—' }}</td>
                <td class="label-col">UHID / MRN</td>
                <td class="val-col">{{ $uhid ?? $ipdPatient->ipd_no }}</td>
            </tr>
            <tr>
                <td class="label-col">Address</td>
                <td class="val-col">{{ $address ?: '—' }}</td>
                <td class="label-col">Mobile</td>
                <td class="val-col">{{ $mobile ?: '—' }}</td>
            </tr>
            <tr>
                <td class="label-col">Date &amp; Time of Admission</td>
                <td class="val-col">{{ $doa }}</td>
                <td class="label-col">Date &amp; Time of Discharge</td>
                <td class="val-col">{{ $dod }}</td>
            </tr>
            <tr>
                <td class="label-col">Consultant / Treating Doctor</td>
                <td class="val-col">{{ $doctorName }}</td>
                <td class="label-col">Ward / Bed</td>
                <td class="val-col">{{ $wardBed }}</td>
            </tr>
        </table>

        <!-- Section 2: Final Diagnosis -->
        <div class="section-bar">2. FINAL DIAGNOSIS</div>
        <div class="section-box" style="min-height: 48px;">
            @if(!empty($discharge->diagnosis))
                {!! nl2br(e($discharge->diagnosis)) !!}
            @else
                <span style="color:#94a3b8; font-style:italic;">No final diagnosis recorded.</span>
            @endif
        </div>

        <!-- Section 3: Presenting Complaints & Relevant History -->
        <div class="section-bar">3. PRESENTING COMPLAINTS &amp; RELEVANT HISTORY</div>
        <div class="section-box" style="min-height: 64px;">
            @if(!empty($discharge->history))
                {!! nl2br(e($discharge->history)) !!}
            @else
                <span style="color:#94a3b8; font-style:italic;">No clinical history recorded.</span>
            @endif
        </div>

        <!-- Section 4: Clinical Examination on Admission -->
        <div class="section-bar">4. CLINICAL EXAMINATION ON ADMISSION</div>
        <table class="exam-table">
            <tr>
                <td style="width: 25%;">
                    <span class="exam-title">BP (mmHg)</span>
                    <span class="exam-val">{{ $discharge->bp ?? (!empty($discharge->condition_at_admission) && preg_match('/BP[:\s]*([0-9\/\s]+)/i', $discharge->condition_at_admission, $m) ? $m[1] : '—') }}</span>
                </td>
                <td style="width: 25%;">
                    <span class="exam-title">Pulse (/min)</span>
                    <span class="exam-val">{{ $discharge->pulse ?? '—' }}</span>
                </td>
                <td style="width: 25%;">
                    <span class="exam-title">RR (/min)</span>
                    <span class="exam-val">{{ $discharge->rr ?? '—' }}</span>
                </td>
                <td style="width: 25%;">
                    <span class="exam-title">SpO₂ (%)</span>
                    <span class="exam-val">{{ $discharge->spo2 ?? '—' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="exam-title">Temperature (&deg;F)</span>
                    <span class="exam-val">{{ $discharge->temperature ?? '—' }}</span>
                </td>
                <td>
                    <span class="exam-title">GCS (/15)</span>
                    <span class="exam-val">{{ $discharge->gcs ?? '—' }}</span>
                </td>
                <td colspan="2">
                    <span class="exam-title">Systemic examination / significant findings</span>
                    <span class="exam-val" style="font-weight: 500; font-size: 11.5px; line-height: 1.4;">
                        {{ $discharge->systemic_exam ?? ($discharge->condition_at_admission ?? 'Stable, conscious & oriented.') }}
                    </span>
                </td>
            </tr>
        </table>

        <!-- Section 5: Investigations -->
        <div class="section-bar">5. INVESTIGATIONS</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Investigation / Date</th>
                    <th style="width: 60%;">Key Findings / Results</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($investigationsList))
                    @foreach($investigationsList as $inv)
                        <tr>
                            <td style="font-weight: 600;">{{ $inv['name'] ?? '' }}</td>
                            <td>{{ $inv['result'] ?? '' }}</td>
                        </tr>
                    @endforeach
                @elseif(!empty($discharge->investigations))
                    <tr>
                        <td colspan="2">{!! nl2br(e($discharge->investigations)) !!}</td>
                    </tr>
                @else
                       <tr>
                        <td style="height: 22px;">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="height: 22px;">&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Section 6: Hospital Course / Treatment Given -->
        <div class="section-bar">6. HOSPITAL COURSE / TREATMENT GIVEN</div>
        <div class="section-box" style="min-height: 72px;">
            @if(!empty($discharge->treatment_given))
                {!! nl2br(e($discharge->treatment_given)) !!}
            @else
                <span style="color:#94a3b8; font-style:italic;">Patient admitted, evaluated, managed with supportive and indicated medical therapy with regular monitoring and showed improvement.</span>
            @endif
        </div>
    </div>

    <!-- Page 1 Footer -->
    <div class="doc-footer">
        <div>Code Blue Emergency Hospital &bull; Muzaffarpur, Bihar</div>
        <div>Page 1 of 2</div>
    </div>
</div>

<!-- Screen Divider between pages -->
<div class="screen-page-divider no-print">
    <span>Page 2 Continuation</span>
</div>

<!-- ==================== PAGE 2 ==================== -->
<div class="page page-2">
    <div>
        <!-- Document Header (repeated on Page 2) -->
        <div class="doc-header">
            <div class="header-logo-area">
                @if($logoPath)
                    <img src="{{ $logoPath }}" alt="Code Blue Emergency Hospital">
                @else
                    <div style="font-size:18px; font-weight:800; color:#1565c0; text-transform:uppercase;">CODE BLUE</div>
                @endif
            </div>
            <div class="header-title-area"></div>
            <div class="header-mrn-area">
                <span class="mrn-label">MRN / UHID</span><br>
                <span class="mrn-value">{{ $uhid ?? $ipdPatient->ipd_no }}</span>
            </div>
        </div>

        <!-- Section 7: Procedures / Interventions -->
        <div class="section-bar">7. PROCEDURES / INTERVENTIONS</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 22%;">Date</th>
                    <th style="width: 40%;">Procedure / Intervention</th>
                    <th style="width: 38%;">Indication / Findings</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($proceduresList))
                    @foreach($proceduresList as $proc)
                        <tr>
                            <td>{{ $proc['date'] ?? '—' }}</td>
                            <td style="font-weight: 600;">{{ $proc['name'] ?? '' }}</td>
                            <td>{{ $proc['findings'] ?? '' }}</td>
                        </tr>
                    @endforeach
                @elseif(!empty($discharge->procedures))
                    <tr>
                        <td colspan="3">{!! nl2br(e($discharge->procedures)) !!}</td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $doa }}</td>
                        <td>IV Cannulation, Continuous Vitals Monitoring</td>
                        <td>Supportive Care &amp; Monitoring</td>
                    </tr>
                    <tr>
                        <td style="height: 22px;">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Section 8: Condition at Discharge -->
        <div class="section-bar">8. CONDITION AT DISCHARGE</div>
        <table class="grid-table">
            <tr>
                <td class="label-col" style="width: 25%;">General condition</td>
                <td class="val-col" style="width: 25%;">{{ $discharge->general_condition ?? ($discharge->discharge_status ?? 'Stable') }}</td>
                <td class="label-col" style="width: 25%;">Consciousness</td>
                <td class="val-col" style="width: 25%;">{{ $discharge->consciousness ?? 'Conscious & Oriented' }}</td>
            </tr>
            <tr>
                <td class="label-col">Mobility</td>
                <td class="val-col">{{ $discharge->mobility ?? 'Ambulatory' }}</td>
                <td class="label-col">Diet</td>
                <td class="val-col">{{ $discharge->diet ?? 'Normal diet as advised' }}</td>
            </tr>
        </table>

        <!-- Section 9: Discharge Medications -->
        <div class="section-bar">9. DISCHARGE MEDICATIONS</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 32%;">Medicine Name</th>
                    <th style="width: 16%;">Dose</th>
                    <th style="width: 14%;">Route</th>
                    <th style="width: 18%;">Frequency</th>
                    <th style="width: 20%;">Duration</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($medicationsList))
                    @foreach($medicationsList as $med)
                        <tr>
                            <td style="font-weight: 700; color:#0f172a;">{{ $med['medicine'] ?? '' }}</td>
                            <td>{{ $med['dose'] ?? '—' }}</td>
                            <td>{{ $med['route'] ?? 'Oral' }}</td>
                            <td style="font-weight: 600;">{{ $med['frequency'] ?? '—' }}</td>
                            <td>{{ $med['duration'] ?? '—' }}</td>
                        </tr>
                    @endforeach
                @elseif(!empty($discharge->discharge_medications))
                    <tr>
                        <td colspan="5">{!! nl2br(e($discharge->discharge_medications)) !!}</td>
                    </tr>
                @elseif(!empty($discharge->advice))
                    <tr>
                        <td colspan="5">{!! nl2br(e($discharge->advice)) !!}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="5" style="color:#94a3b8; font-style:italic;">No discharge medications prescribed.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Section 10: Advice at Discharge -->
        <div class="section-bar">10. ADVICE AT DISCHARGE</div>
        <div class="section-box" style="padding: 9px 12px; min-height: 80px;">
            <ul class="advice-list">
                <li>Continue medications as prescribed.</li>
                <li>Follow diet, activity and lifestyle advice.</li>
                <li>Follow up as advised / on: <strong style="color:#0f172a;">{{ $discharge->followup_date ?? 'As indicated below' }}</strong></li>
                <li>Seek immediate medical attention for worsening symptoms, fever, breathlessness, chest pain, bleeding, vomiting, or altered sensorium.</li>
            </ul>
            @if(!empty($discharge->advice) && empty($medicationsList))
                <div style="margin-top: 8px; padding-top: 7px; border-top: 1px dashed #cbd5e1; font-size: 11.5px; line-height: 1.45;">
                    {!! nl2br(e($discharge->advice)) !!}
                </div>
            @endif
        </div>

        <!-- Bottom Split Section: Follow-up, Doctor/Treating Physician & Patient/Attendant Acknowledgement -->
        <table class="bottom-split-table">
            <tr>
                <!-- Col 1: Follow-Up & Care Plan -->
                <td style="width: 44%; vertical-align: top;">
                    <div style="display: flex; flex-direction: column; justify-content: space-between; height: 100%; min-height: 120px;">
                        <div>
                            <div class="bottom-header-text">FOLLOW-UP &amp; INSTRUCTIONS</div>
                            <table style="width:100%; font-size:11.5px; border-collapse:collapse; line-height: 1.45;">
                                <tr>
                                    <td style="border:none; padding:2px 0; width:36%; font-weight:600; color:#475569;">Next Visit Date:</td>
                                    <td style="border:none; padding:2px 0; font-weight:700; color:#0f172a;">
                                        {{ $discharge->followup_date ?: 'After 5 - 7 Days / As advised' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:none; padding:2px 0; font-weight:600; color:#475569;">Doctor / Dept:</td>
                                    <td style="border:none; padding:2px 0; font-weight:700; color:#0f172a;">
                                        {{ $discharge->followup_doctor ?: $doctorName }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:none; padding:2px 0; font-weight:600; color:#475569;">Emergency 24x7:</td>
                                    <td style="border:none; padding:2px 0; font-weight:700; color:#1565c0;">
                                        {{ setting('phone') ? '+91 ' . setting('phone') : '+91 9431254196' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border:none; padding:2px 0; font-weight:600; color:#475569; vertical-align: top;">Instructions:</td>
                                    <td style="border:none; padding:2px 0; color:#0f172a;">
                                        {{ $discharge->special_instructions ?: ($discharge->precautions_followup ?: 'Take regular medications and report immediately in case of emergency.') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>

                <!-- Col 2: Doctor / Treating Physician -->
                <td style="width: 28%; vertical-align: top;">
                    <div style="display: flex; flex-direction: column; justify-content: space-between; height: 100%; min-height: 120px;">
                        <div>
                            <div class="bottom-header-text">DOCTOR / TREATING PHYSICIAN</div>
                            <div style="font-size:12px; font-weight:700; color:#0f172a; margin-top:3px; line-height:1.35;">
                                {{ $doctorName }}
                            </div>
                            <div style="font-size:10px; color:#64748b; margin-top:2px;">
                                Consultant Physician / Emergency Care
                            </div>
                        </div>

                        <div style="margin-top: 28px; text-align: center; border-top: 1.5px solid #0f172a; padding-top: 4px;">
                            <span style="font-size: 10px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px;">
                                Doctor's Signature &amp; Stamp
                            </span>
                        </div>
                    </div>
                </td>

                <!-- Col 3: Patient / Attendant Acknowledgement -->
                <td style="width: 28%; vertical-align: top;">
                    <div style="display: flex; flex-direction: column; justify-content: space-between; height: 100%; min-height: 120px;">
                        <div>
                            <div class="bottom-header-text">PATIENT / ATTENDANT</div>
                            <div style="font-size:10px; color:#334155; line-height:1.4; margin-top:3px;">
                                Discharge summary, medications &amp; warning red-flag signs explained. Received all original reports.
                            </div>
                            <div style="font-size:10px; color:#64748b; margin-top:6px; font-weight:500;">
                                Relation: ___________________
                            </div>
                        </div>

                        <div style="margin-top: 28px; text-align: center; border-top: 1.5px solid #0f172a; padding-top: 4px;">
                            <span style="font-size: 10px; font-weight: 700; color: #334155; text-transform: uppercase; letter-spacing: 0.5px;">
                                Signature / Thumb Impression
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Page 2 Footer -->
    <div class="doc-footer">
        <div>Code Blue Emergency Hospital &bull; Muzaffarpur, Bihar</div>
        <div>Page 2 of 2</div>
    </div>
</div>

</body>
</html>