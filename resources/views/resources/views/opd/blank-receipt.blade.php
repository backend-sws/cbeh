<!DOCTYPE html>
<html>

<head>
    <title>OPD Blank Receipt - {{ $opdPatient->opd_no }}</title>
    <style>
        /* Base Settings */
        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            background: #fff;
            /* Light background for screen */
            color: #222;
            line-height: 1.3;
        }

        .bill-container {
            width: 750px;
            /* Standard width */
            margin: 10px auto;
            background: #fff;
            min-height: 100vh;
            padding: 20px 40px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* --- Header Styles --- */
        .header-table {
            width: 100%;
            border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!};
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        /* --- Patient Info Table --- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid #333;
        }

        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
            border: 1px solid #333;
            width: 25%;
        }

        .info-table td:nth-child(odd) {
            background-color: #fff;
            font-weight: bold;
            color: #444;
            width: 15%;
        }

        .info-table td:nth-child(even) {
            width: 35%;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 5px;
            color: {!! setting('theme_color', '#e91e63') !!};
            
            padding-bottom: 3px;
            text-transform: uppercase;
        }

        /* --- Vitals Table --- */
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

        .vitals-label {
            font-weight: bold;
            background-color: #fff;
            white-space: nowrap;
            width: 1%;
        }

        /* --- Writing Area --- */
        .writing-area {
            flex: 1;
            border-radius: 4px;
            min-height: 300px;
            margin-top: 10px;
            margin-bottom: 50px;
        }

        /* --- Footer Styles --- */
        .footer-content {
            margin-top: auto;
            text-align: center;
            
            padding-top: 10px;
            font-size: 11px;
            color: #555;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: {!! setting('theme_color', '#e91e63') !!};
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            z-index: 2000;
        }

        @media print {
            .footer-spacer { display: none !important; }
            .bill-container, .receipt-container, .prescription-container, .discharge-container, .slip-container { min-height: auto !important; height: auto !important; }

            body {
                background: #fff;
            }

            .bill-container {
                width: 100%;
                border: none;
                margin: 0;
                padding: 0;
            }

            .print-btn {
                display: none;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Ensure writing area takes available space */
            .writing-area {
                min-height: auto;
                height: auto;
            }
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_opd_blank', '{}'), true) ?? []; @endphp

    <button onclick="window.print()" class="print-btn">Print Blank Prescription</button>

    <div class="bill-container">

        @if(setting('show_header_opd_receipt', 'yes') == 'yes')
                @if(setting('opd_header_image'))
                    <table width="100%" style="margin:0; padding:0; border:none; border-spacing:0;">
                        <tr>
                            <td style="padding-top:{{ $m['header']['top'] ?? 0 }}px; padding-left:{{ $m['header']['left'] ?? 0 }}px; padding-right:{{ $m['header']['right'] ?? 0 }}px; padding-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
                                <img src="{{ asset('img/' . setting('opd_header_image')) }}" alt="Header" style="width: 100%; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
                            </td>
                        </tr>
                    </table>
                @else
                    <div style="padding: 20px 40px 0 40px;">
                        <table width="100%" style="border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; padding-bottom:10px;">
                            <tr>
                                <td style="width:90px; vertical-align:top;">
                                    <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Logo" style="height:70px;">
                                </td>
                                <td style="text-align:right; vertical-align:top;">
                                    <div style="font-size:18px; font-weight:bold; color:{!! setting('theme_color', '#e91e63') !!}; margin-bottom:5px;">
                                        {{ setting('hospital_name', 'Hospital Name') }}
                                    </div>
                                    <div style="font-size:12px; color:#555;">
                                        {{ setting('tagline', 'Tagline') }}<br>
                                        {{ setting('address', 'Hospital Address Here') }}<br>
                                        Phone: +91 {{ setting('phone', '0000000000') }}<br>
                                        Email: {{ setting('email', 'email@example.com') }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif
            @else
                <div style="height: {{ setting('spacer_opd_receipt', '180') }}px;"></div>
            @endif
    </div>

        <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">


        <div
            style="text-align:center; font-weight:bold; font-size:16px; margin-top:45px; text-decoration: underline; color: #333;">
            OPD PRESCRIPTION
        </div>


            </div>

        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">


        <div class="section-title">Patient Information</div>
                @include('components.pdf-patient-info', [
            'docKey' => 'opd_blank',
            'patient' => $opdPatient->patient ?? null,
            'uhid' => $patient_uhid ?? null,
            'ipd_no' => $opdPatient->opd_no ?? null,
            'invoice_no' => $opdPatient->invoice_no ?? null,
            'appointment_date' => $opdPatient->appointment_date ? \Carbon\Carbon::parse($opdPatient->appointment_date)->format('d-m-Y') : null,
            'doctor_name' => $opdPatient->doctor->name ?? null,
            'department_name' => $opdPatient->doctor->department->name ?? null,
        ])


            </div>

        @php
            $spo2 = $opdPatient->spo2 ?? ($opdPatient->SpO2 ?? null);
            $patientObj = $opdPatient;
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

        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
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
                            <div style="font-weight: bold; font-size: 11px; margin-bottom: 8px; color: #111;">Vital Signs (At Admission)</div>
                            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                                <tr>
                                    <td style="padding: 3px 0; width: 95px; font-weight: bold; border: none;">Blood Pressure</td>
                                    <td style="padding: 3px 4px; width: 10px; border: none;">:</td>
                                    <td style="padding: 3px 0; border: none;">
                                        @if(!empty($patientObj->blood_pressure) || !empty($patientObj->bp))
                                            <strong style="text-decoration: underline;">{{ $patientObj->blood_pressure ?? $patientObj->bp }}</strong> mm/Hg
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
                        <div style="font-weight: bold; font-size: 11px; margin-top: 15px; margin-bottom: 5px; color: #111; text-transform: uppercase;">ADVICE</div>
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
                                    <td style="padding: 3px 0; font-weight: bold; vertical-align: bottom; border: none;">Ext.</td>
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
        </div>

        <div style="margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px;">
            <div class="section-title"></div>
            <div class="writing-area">
            </div>
        </div>

        @if(setting('show_footer_opd_blank', 'yes') == 'yes')
        <div style="padding-top:{{ $m['footer']['top'] ?? 0 }}px; padding-left:{{ $m['footer']['left'] ?? 0 }}px; padding-right:{{ $m['footer']['right'] ?? 0 }}px; padding-bottom:{{ $m['footer']['bottom'] ?? 0 }}px; box-sizing: border-box;">
        

        @if(!setting('opd_footer_image'))
        <div class="default-text-footer" class="footer-content">
            <div style="font-weight:bold; color: {!! setting('theme_color', '#e91e63') !!};">
                For Emergency Services: +91 {{ setting('phone', '0000000000') }}
            </div>
            <div>
                {{ setting('address', 'Patna: Opposite SBI, Bhootnath Road, Patna-800026') }}
            </div>
        </div>
        @endif
        </div>
        @endif

        @if(setting('show_consultant_signature', 'yes') == 'yes')
<div class="consultant-signature-wrapper" style="position: fixed; bottom: 85px; left: 0; right: 0; width: 100%; z-index: 99;">
    <table class="consultant-signature-table" width="100%" style="margin-bottom: 0;">
            <tr>
                <td style="width:50%; text-align: center;"></td>
                <td style="width:50%; text-align:right; padding-right: 30px;">
                    <div style="border-top:1px solid #333; width:200px; display:inline-block; margin-bottom: 5px;">
                    </div>
                    <div style="font-weight: bold; margin-right: 35px;">Consultant's Signature</div>
                </td>
            </tr>
        </table>
</div>
@endif
        <div class="footer-spacer" style="height: 170px;"></div>
        @if(setting('opd_footer_image'))
            <div style="position:fixed; bottom:0; left:0; left:0; right:0; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . setting('opd_footer_image')) }}" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block;" alt="Footer">
            </div>
        @endif
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>


@if(setting('opd_footer_image'))
<style>
    .default-text-footer { display: none !important; }
</style>
@endif
</body>
</html>