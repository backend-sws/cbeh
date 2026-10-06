<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>IPD Prescription - {{ $ipd_patient->ipd_no ?? ($prescription->patient->name ?? 'Patient') }}</title>
    <style>
        /* Base Settings */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            background: #fff;
            color: #222;
            line-height: 1.3;
        }

        .bill-container {
            width: 700px;
            margin: 10px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- Header Styles --- */
        .header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!};
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 5px;
            color: {!! setting('theme_color', '#e91e63') !!};
            
            padding-bottom: 3px;
        }

        /* --- Patient Info Box --- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 1px solid #333;
        }

        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
            border: none;
            word-wrap: break-word;
            border: 1px solid #333;
        }

        /* --- Medicine Table --- */
        .med-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .med-table th {
            background-color: {!! setting('theme_color', '#e91e63') !!};
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ccc;
            /* Fix for printing background colors */
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact;
        }

        .med-table td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        .med-table tr:nth-child(even) {
            background-color: #f2f2f2; /* Light gray for screen */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* --- VITALS TABLE SPECIFIC STYLES --- */
        .vitals-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #333;
            margin-bottom: 10px;
        }

        .vitals-table td {
            border: 1px solid #333;
            padding: 6px 8px;
            vertical-align: top;
        }

        .vitals-table td {
            width: auto !important;
        }

        .vitals-label {
            font-weight: bold;
            background-color: #f9f9f9;
            white-space: nowrap;
            width: 1% !important;
            -webkit-print-color-adjust: exact;
        }

        .note-box {
            background: #fdfdfd;
            border-left: 4px solid {!! setting('theme_color', '#e91e63') !!};
            padding: 10px;
            margin: 10px 0;
            font-size: 12px;
            color: #444;
        }

        /* --- Footer --- */
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 12px 25px;
            background-color: {!! setting('theme_color', '#e91e63') !!};
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            z-index: 9999;
        }

        .print-btn:hover {
            opacity: 0.9;
        }

        @media print {
            .print-btn {
                display: none;
            }

            .bill-container {
                border: none;
                padding: 0;
                width: 100%;
                margin: 0;
            }

            /* --- DARKER HEADER FIX --- */
            .med-table th {
                background-color: #333 !important; /* Dark Gray Background */
                color: #fff !important; /* White Text */
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
                border: 1px solid #000;
            }
            
            .med-table tr:nth-child(even) {
                background-color: #e0e0e0 !important; /* Slightly darker gray for rows */
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_prescription', setting('pdf_margins_opd_prescription', '{}')), true) ?? []; @endphp
    <div class="bill-container">

        <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
        @if(setting('show_header_prescription', setting('show_header_opd_prescription', 'yes')) == 'yes')
        
            @if(setting('opd_header_image'))
                <img src="{{ asset('img/' . setting('opd_header_image')) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
            @else
        <table width="100%"
            style="border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
            <tr>
                <td style="width:90px; vertical-align:top;">
                    <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Hospital Logo"
                        style="height:70px;">
                </td>
                <td style="text-align:right; vertical-align:top;">
                    <div style="font-size:18px; font-weight:bold; color:{!! setting('theme_color', '#e91e63') !!}; margin-bottom:5px;">
                        {{ setting('hospital_name', 'Hospital Name') }}
                    </div>
                    <div style="font-size:12px; color:#555;">
                        {{ setting('address', 'Address Here') }}<br>
                        Phone: +91 {{ setting('phone', '0000000000') }}<br>
                        Website: {{ setting('website', 'www.example.com') }}
                    </div>
                </td>
            </tr>
        </table>
            @endif
        @else
        <div style="height: {{ setting('spacer_prescription', setting('spacer_opd_prescription', '180')) }}px;"></div>
        @endif
        </div>

        @if(setting('hide_title_prescription', setting('hide_title_opd_prescription', 'no')) == 'no')
        <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
        <div
            style="text-align:center; font-weight:bold; font-size:16px; margin-top:45px; text-decoration: underline; color: #333;">
            IPD PRESCRIPTION
        </div>
        </div>
        @endif

        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
        <div class="section-title" style="margin-top: 5px;">Patient Information</div>
        <div class="patient-info-box">
            @php
                $bedName = $ipd_patient->beds->name ?? ($beds->beds->name ?? null);
                $bedTypeName = $ipd_patient->beds->bedType->name ?? ($beds->beds->bedType->name ?? null);
                $bedWard = $bedName ? ($bedTypeName ? "{$bedName} ({$bedTypeName})" : $bedName) : null;
                $appointmentDate = $prescription->prescription_date ? \Carbon\Carbon::parse($prescription->prescription_date)->format('d-m-Y') : (!empty($prescription->created_at) ? \Carbon\Carbon::parse($prescription->created_at)->format('d-m-Y') : null);
                $doa = !empty($ipd_patient->admission_date) ? \Carbon\Carbon::parse($ipd_patient->admission_date)->format('d-m-Y h:i A') : null;
            @endphp
            @include('components.pdf-patient-info', [
                'docKey' => 'opd_prescription',
                'patient' => $prescription->patient ?? ($ipd_patient->patient ?? null),
                'uhid' => $uhid ?? null,
                'ipd_no' => $ipd_patient->ipd_no ?? ($prescription->ipd_no ?? null),
                'invoice_no' => null,
                'appointment_date' => $appointmentDate,
                'doctor_name' => $prescription->doctor->name ?? ($ipd_patient->doctor->name ?? null),
                'department_name' => $prescription->doctor->department->name ?? ($ipd_patient->doctor->department->name ?? null),
                'bed_ward' => $bedWard,
                'doa' => $doa,
            ])
        </div>
        </div>

        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
        @php
            $vObj = $visit_vitals ?? null;
            $patientObj = (object)[
                'height' => $vObj?->height ?? ($ipd_patient->height ?? ($opd_patient->height ?? null)),
                'weight' => $vObj?->weight ?? ($ipd_patient->weight ?? ($opd_patient->weight ?? null)),
                'blood_pressure' => $vObj?->blood_pressure ?? ($ipd_patient->blood_pressure ?? ($opd_patient->blood_pressure ?? ($opd_patient->bp ?? null))),
                'pulse' => $vObj?->pulse ?? ($ipd_patient->pulse ?? ($opd_patient->pulse ?? null)),
                'respiratory_rate' => $vObj?->respiratory_rate ?? ($ipd_patient->respiratory_rate ?? ($opd_patient->respiratory_rate ?? null)),
                'temperature' => $vObj?->temperature ?? ($ipd_patient->temperature ?? ($opd_patient->temperature ?? null)),
                'spo2' => $vObj?->spo2 ?? ($ipd_patient->spo2 ?? ($ipd_patient->SpO2 ?? ($opd_patient->spo2 ?? ($opd_patient->SpO2 ?? null)))),
                'rbs' => $vObj?->rbs ?? ($ipd_patient->rbs ?? ($opd_patient->rbs ?? null)),
                'general_appearance' => $vObj?->general_appearance ?? ($ipd_patient->general_appearance ?? ($opd_patient->general_appearance ?? null)),
                'cvs' => $vObj?->cvs ?? ($ipd_patient->cvs ?? ($opd_patient->cvs ?? null)),
                'rs' => $vObj?->rs ?? ($ipd_patient->rs ?? ($opd_patient->rs ?? null)),
                'pa' => $vObj?->pa ?? ($ipd_patient->pa ?? ($opd_patient->pa ?? null)),
                'cns' => $vObj?->cns ?? ($ipd_patient->cns ?? ($opd_patient->cns ?? null)),
                'other_systems' => $vObj?->other_systems ?? ($ipd_patient->other_systems ?? ($opd_patient->other_systems ?? null)),
                'symptoms' => $ipd_patient->symptoms ?? ($opd_patient->symptoms ?? null),
            ];

            $vitalsLabel = $vObj
                ? 'Vital Signs (' . \Carbon\Carbon::parse($vObj->recorded_at)->format('d/m/Y h:i A') . ($vObj->shift ? ' · ' . $vObj->shift : '') . ')'
                : 'Vital Signs (At Admission)';

            $spo2 = $patientObj->spo2;
            $bmi = null;
            if (!empty($patientObj->weight) && !empty($patientObj->height)) {
                $cleanH = floatval(preg_replace('/[^0-9.]/', '', $patientObj->height));
                $cleanW = floatval(preg_replace('/[^0-9.]/', '', $patientObj->weight));
                if ($cleanH > 0 && $cleanW > 0) {
                    if ($cleanH < 3) {
                        $hMeters = $cleanH;
                    } elseif ($cleanH <= 8.5) {
                        $hMeters = $cleanH * 0.3048;
                    } else {
                        $hMeters = $cleanH / 100;
                    }
                    if ($hMeters > 0) {
                        $bmi = round($cleanW / ($hMeters * $hMeters), 1);
                    }
                }
            }
        @endphp

        @if (!empty($patientObj->symptoms))
            <div style="margin-top: 10px; margin-bottom: 5px; font-size: 12px; border: 1px solid #333; padding: 6px 8px; border-radius: 4px; background-color: #f9f9f9;">
                <strong>Chief Complaint / Symptoms:</strong> {{ $patientObj->symptoms }}
            </div>
        @endif

        <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 15px; table-layout: fixed;">
            <tr>
                <!-- Left Box: Vital Signs -->
                <td style="width: 36%; vertical-align: top; border: none; padding: 0;">
                    <div style="border: 1px solid #333; border-radius: 6px; padding: 8px 10px; background: #fff; height: 230px; box-sizing: border-box;">
                        <div style="font-weight: bold; font-size: 11px; margin-bottom: 8px; color: #111;">{{ $vitalsLabel }}</div>
                        <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                            <tr>
                                <td style="padding: 3px 0; width: 95px; font-weight: bold; border: none;">Blood Pressure</td>
                                <td style="padding: 3px 4px; width: 10px; border: none;">:</td>
                                <td style="padding: 3px 0; border: none;">
                                    @if(!empty($patientObj->blood_pressure))
                                        <strong style="text-decoration: underline;">{{ $patientObj->blood_pressure }}</strong> mm/Hg
                                    @else
                                        _______ / _______ mm/Hg
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; border: none;">Pulse Rate</td>
                                <td style="padding: 3px 4px; border: none;">:</td>
                                <td style="padding: 3px 0; border: none;">
                                    @if(!empty($patientObj->pulse))
                                        <strong style="text-decoration: underline;">{{ $patientObj->pulse }}</strong> /min
                                    @else
                                        _______ /min
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; border: none;">Respiratory rate</td>
                                <td style="padding: 3px 4px; border: none;">:</td>
                                <td style="padding: 3px 0; border: none;">
                                    @if(!empty($patientObj->respiratory_rate))
                                        <strong style="text-decoration: underline;">{{ $patientObj->respiratory_rate }}</strong> /min
                                    @else
                                        _______ /min
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; border: none;">Temp.</td>
                                <td style="padding: 3px 4px; border: none;">:</td>
                                <td style="padding: 3px 0; border: none;">
                                    @if(!empty($patientObj->temperature))
                                        <strong style="text-decoration: underline;">{{ $patientObj->temperature }}</strong> °F
                                    @else
                                        _______ °F
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; border: none;">SpO2</td>
                                <td style="padding: 3px 4px; border: none;">:</td>
                                <td style="padding: 3px 0; border: none;">
                                    @if(!empty($spo2))
                                        <strong style="text-decoration: underline;">{{ $spo2 }}</strong> %
                                    @else
                                        _______ %
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; border: none;">Height</td>
                                <td style="padding: 3px 4px; border: none;">:</td>
                                <td style="padding: 3px 0; border: none;">
                                    @if(!empty($patientObj->height))
                                        <strong style="text-decoration: underline;">{{ $patientObj->height }}</strong> cm
                                    @else
                                        _______ cm
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; border: none;">Weight</td>
                                <td style="padding: 3px 4px; border: none;">:</td>
                                <td style="padding: 3px 0; border: none;">
                                    @if(!empty($patientObj->weight))
                                        <strong style="text-decoration: underline;">{{ $patientObj->weight }}</strong> kg
                                    @else
                                        _______ kg
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; border: none;">BMI</td>
                                <td style="padding: 3px 4px; border: none;">:</td>
                                <td style="padding: 3px 0; border: none;">
                                    @if(!empty($bmi))
                                        <strong style="text-decoration: underline;">{{ $bmi }}</strong> kg/m2
                                    @else
                                        _______ kg/m2
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; border: none;">RBS</td>
                                <td style="padding: 3px 4px; border: none;">:</td>
                                <td style="padding: 3px 0; border: none;">
                                    @if(!empty($patientObj->rbs))
                                        <strong style="text-decoration: underline;">{{ $patientObj->rbs }}</strong> mg/dL
                                    @else
                                        _______ mg/dL
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <!-- Spacer between boxes -->
                <td style="width: 2%; border: none; padding: 0;">&nbsp;</td>
                <!-- Right Box: Examination & Finding -->
                <td style="width: 62%; vertical-align: top; border: none; padding: 0;">
                    <div style="border: 1px solid #333; border-radius: 6px; padding: 8px 10px; background: #fff; height: 230px; box-sizing: border-box;">
                        <div style="font-weight: bold; font-size: 11px; margin-bottom: 8px; color: #111;">EXAMINATION & FINDING</div>
                        <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                            <tr>
                                <td style="padding: 3px 0; width: 115px; font-weight: bold; vertical-align: bottom; border: none;">General Appearance</td>
                                <td style="padding: 3px 4px; width: 10px; vertical-align: bottom; border: none;">:</td>
                                <td style="padding: 3px 0; border-bottom: 1px solid #777; vertical-align: bottom;">
                                    @if(!empty($patientObj->general_appearance))
                                        <strong style="text-decoration: underline;">{{ $patientObj->general_appearance }}</strong>
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; vertical-align: bottom; border: none;">CVS</td>
                                <td style="padding: 3px 4px; vertical-align: bottom; border: none;">:</td>
                                <td style="padding: 3px 0; border-bottom: 1px solid #777; vertical-align: bottom;">
                                    @if(!empty($patientObj->cvs))
                                        <strong style="text-decoration: underline;">{{ $patientObj->cvs }}</strong>
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; vertical-align: bottom; border: none;">RS</td>
                                <td style="padding: 3px 4px; vertical-align: bottom; border: none;">:</td>
                                <td style="padding: 3px 0; border-bottom: 1px solid #777; vertical-align: bottom;">
                                    @if(!empty($patientObj->rs))
                                        <strong style="text-decoration: underline;">{{ $patientObj->rs }}</strong>
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; vertical-align: bottom; border: none;">P/A</td>
                                <td style="padding: 3px 4px; vertical-align: bottom; border: none;">:</td>
                                <td style="padding: 3px 0; border-bottom: 1px solid #777; vertical-align: bottom;">
                                    @if(!empty($patientObj->pa))
                                        <strong style="text-decoration: underline;">{{ $patientObj->pa }}</strong>
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; vertical-align: bottom; border: none;">CNS</td>
                                <td style="padding: 3px 4px; vertical-align: bottom; border: none;">:</td>
                                <td style="padding: 3px 0; border-bottom: 1px solid #777; vertical-align: bottom;">
                                    @if(!empty($patientObj->cns))
                                        <strong style="text-decoration: underline;">{{ $patientObj->cns }}</strong>
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 3px 0; font-weight: bold; vertical-align: bottom; border: none;">Other Systems</td>
                                <td style="padding: 3px 4px; vertical-align: bottom; border: none;">:</td>
                                <td style="padding: 3px 0; border-bottom: 1px solid #777; vertical-align: bottom;">
                                    @if(!empty($patientObj->other_systems))
                                        <strong style="text-decoration: underline;">{{ $patientObj->other_systems }}</strong>
                                    @else
                                        &nbsp;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding-top: 6px; border: none;">
                                    <div style="border-bottom: 1px solid #777; height: 14px; width: 100%; margin-bottom: 6px;"></div>
                                    <div style="border-bottom: 1px solid #777; height: 14px; width: 100%;"></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        @if ($prescription->headernotes)
            <div class="section-title">Diagnosis / Advice</div>
            <div class="note-box" style="white-space: pre-wrap; word-wrap: break-word;">{!! nl2br(e($prescription->headernotes)) !!}</div>
        @endif

        @if ($prescription->medicines && count($prescription->medicines) > 0)
            <div class="section-title">Prescribed Medicines</div>
            <table class="med-table">
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
                    @foreach ($prescription->medicines as $med)
                        <tr>
                            <td>
                                @php
                                    $medName = $med->medicine_name ?: ($med->medicine->medicine ?? ($med->medicine->name ?? '—'));
                                @endphp
                                <strong>{{ $medName }}</strong>
                                @if(!empty($med->instructions ?? $med->instruction))
                                    <div style="font-size: 10px; color: #555; font-style: italic; margin-top: 2px;">
                                        Note: {{ $med->instructions ?? $med->instruction }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ $med->dosage ?? '—' }}</td>
                            <td>{{ $med->route ?: ($med->time ?: 'Oral') }}</td>
                            <td><strong>{{ $med->doseInterval ?? '—' }}</strong></td>
                            <td>{{ $med->duration ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if ($prescription->footernotes)
            <div class="section-title">Doctor Advice</div>
            <div class="note-box" style="white-space: pre-wrap; word-wrap: break-word;">{!! nl2br(e($prescription->footernotes)) !!}</div>
        @endif

        
        </div>

        <div style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 0 }}px;">

        @if(setting('show_footer_prescription', setting('show_footer_opd_prescription', 'yes')) == 'yes')
        <div class="default-text-footer"
            style="text-align:center;  padding-top:10px; font-size:11px;">
            <div style="font-weight:bold; color:{!! setting('theme_color', '#e91e63') !!};">
                For Emergency Services: +91{{ setting('phone', '0000000000') }}
            </div>
            <div>
                {{ setting('address', 'Patna: Opposite SBI, Bhootnath Road, Patna-800026') }}
            </div>
        </div>
        @endif

        <button onclick="window.print()" class="print-btn">Print Prescription</button>
    </div>

    <script>
        // Auto print on load
        window.onload = function() {
            window.print();
        };
    </script>

        @if(setting('show_consultant_signature', 'yes') == 'yes')
<div class="consultant-signature-wrapper" style="position: fixed; bottom: 85px; left: 0; right: 0; width: 100%; z-index: 99;">
    <table class="consultant-signature-table" width="100%" style="margin-bottom: 0;">
            <tr>
                <td style="width:50%;">
                </td>
                <td style="width:50%; text-align:right; padding-right: 30px;">
                    <div style="border-top:1px solid #333; width:200px; display:inline-block; margin-bottom: 5px;">
                    </div>
                    <div style="font-weight: bold; margin-right: 35px;">Consultant's Signature</div>
                </td>
            </tr>
        </table>
</div>
@endif
        <div style="height: 170px;"></div>
        @if(setting('opd_footer_image'))
            <div style="position:fixed; bottom:0; left:0; width:100%; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . setting('opd_footer_image')) }}" style="width:100%; height:auto; display:block;" alt="Footer">
            </div>
        @endif
    

@if(setting('opd_footer_image'))
<style>
    .default-text-footer { display: none !important; }
</style>
@endif
</body>

</html>