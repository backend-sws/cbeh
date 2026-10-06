<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Vitals Chart - {{ $ipdPatient->ipd_no ?? ($ipdPatient->patient->name ?? 'Patient') }}</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 11px;
            background: #fff;
            color: #222;
            line-height: 1.3;
        }

        .bill-container {
            width: 820px;
            margin: 10px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px 25px 25px 25px;
            position: relative;
            min-height: 1050px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .main-content {
            flex: 1 0 auto;
        }

        .footer-pinned {
            flex-shrink: 0;
            width: 100%;
            margin-top: auto;
            padding-top: 25px;
        }

        .content-bottom-spacer {
            height: 20px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            color: {!! setting('theme_color', '#054bf0') !!};
            border-bottom: 1px solid {!! setting('theme_color', '#054bf0') !!};
            padding-bottom: 3px;
        }

        .chart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        .chart-table th {
            background-color: {!! setting('theme_color', '#054bf0') !!};
            color: white;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #333;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .chart-table td {
            padding: 5px 4px;
            border: 1px solid #333;
            vertical-align: middle;
        }

        .chart-table tr:nth-child(even) {
            background-color: #f7f9fc;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            padding: 12px 25px;
            background-color: {!! setting('theme_color', '#054bf0') !!};
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
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }

            .print-btn {
                display: none !important;
            }

            .bill-container {
                border: none !important;
                padding: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .main-content {
                flex: 1 0 auto;
            }

            .content-bottom-spacer {
                height: 120px;
            }

            .footer-pinned {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                margin-top: 0;
                background: #fff;
                z-index: 999;
            }

            .chart-table th {
                background-color: #333 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    @php
        $docKey = 'ipd_vitals_chart';

        // Margins from settings
        $m = json_decode(setting('pdf_margins_' . $docKey, setting('pdf_margins_opd_prescription', '{}')), true) ?? [];

        // Header & Footer
        $showHeader = setting('show_header_' . $docKey, setting('show_header_opd_prescription', 'yes')) == 'yes';
        $headerImg = setting('ipd_header_image', setting('opd_header_image'));
        $spacerHeight = setting('spacer_' . $docKey, setting('spacer_opd_prescription', '180'));
        $hideTitle = setting('hide_title_' . $docKey, 'no') == 'yes';
        $showFooter = setting('show_footer_' . $docKey, setting('show_footer_opd_prescription', 'yes')) == 'yes';
        $footerImg = setting('ipd_footer_image', setting('opd_footer_image'));
        $showSignatures = setting('show_consultant_signature', 'yes') == 'yes';

        $bedName = $ipdPatient->beds->name ?? null;
        $bedTypeName = $ipdPatient->beds->bedType->name ?? null;
        $bedWard = $bedName ? ($bedTypeName ? "{$bedName} ({$bedTypeName})" : $bedName) : null;
        $doa = !empty($ipdPatient->admission_date) ? \Carbon\Carbon::parse($ipdPatient->admission_date)->format('d-m-Y h:i A') : null;
    @endphp

    <div class="bill-container">

        <!-- Top Main Content Area -->
        <div class="main-content">

            <!-- 1. Header Section -->
            <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
            @if($showHeader)
                @if($headerImg)
                    <img src="{{ asset('img/' . $headerImg) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#054bf0') !!}; margin-bottom:15px; padding-bottom:10px;">
                @else
                    <table width="100%" style="border-bottom:2px solid {!! setting('theme_color', '#054bf0') !!}; margin-bottom:15px; padding-bottom:10px;">
                        <tr>
                            <td style="width:90px; vertical-align:top;">
                                <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Hospital Logo" style="height:70px;">
                            </td>
                            <td style="text-align:right; vertical-align:top;">
                                <div style="font-size:18px; font-weight:bold; color:{!! setting('theme_color', '#054bf0') !!}; margin-bottom:5px;">
                                    {{ setting('hospital_name', 'Code Blue Emergency Hospital') }}
                                </div>
                                <div style="font-size:12px; color:#555; line-height: 1.4;">
                                    {{ setting('address', 'Mithanpura-Bela Road, Kailash Vivaah Bhawan, Muzaffarpur, Bihar, 842002') }}<br>
                                    Phone: +91 {{ setting('phone', '7070899941') }} &nbsp;|&nbsp; Email: {{ setting('email', 'codebluehospital1@gmail.com') }}<br>
                                    Website: {{ setting('website', 'https://codebluehospital.com/') }}
                                </div>
                            </td>
                        </tr>
                    </table>
                @endif
            @else
                <div style="height: {{ $spacerHeight }}px;"></div>
            @endif
            </div>

            <!-- 2. Document Title -->
            @if(!$hideTitle)
            <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
                <div style="text-align:center; font-weight:bold; font-size:15px; margin-top:8px; margin-bottom: 10px; text-decoration: underline; color: #333; text-transform: uppercase; letter-spacing: 0.5px;">
                    IN-PATIENT VITALS &amp; OBSERVATION CHART
                </div>
            </div>
            @endif

            <!-- 3. Patient Information -->
            <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
                <div class="patient-info-box">
                    @include('components.pdf-patient-info', [
                        'docKey' => $docKey,
                        'patient' => $ipdPatient->patient ?? null,
                        'uhid' => $uhid ?? null,
                        'ipd_no' => $ipdPatient->ipd_no ?? null,
                        'invoice_no' => null,
                        'appointment_date' => now()->format('d-m-Y'),
                        'doctor_name' => $ipdPatient->doctor->name ?? null,
                        'department_name' => $ipdPatient->doctor->department->name ?? null,
                        'bed_ward' => $bedWard,
                        'doa' => $doa,
                    ])
                </div>
            </div>

            <!-- 4. Vitals Observations Table -->
            <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
                <div class="section-title" style="display:flex; justify-content:space-between; align-items:center;">
                    <span>Daily Observations &amp; Ward Rounds Log</span>
                    <span style="font-size:10px; font-weight:normal; color:#555;">Total Records: {{ $vitals->count() }}</span>
                </div>

                @if($vitals->count() > 0)
                <table class="chart-table">
                    <thead>
                        <tr>
                            <th width="12%">Date &amp; Time</th>
                            <th width="8%">Shift</th>
                            <th width="10%">BP (mm/Hg)</th>
                            <th width="8%">Pulse (/min)</th>
                            <th width="8%">Temp (°F)</th>
                            <th width="7%">SpO2 (%)</th>
                            <th width="7%">RR (/min)</th>
                            <th width="9%">RBS (mg/dL)</th>
                            <th width="9%">Wt / BMI</th>
                            <th width="12%">Remarks / Notes</th>
                            <th width="10%">Recorded By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vitals as $v)
                        <tr>
                            <td style="text-align:center; font-weight:bold;">
                                {{ \Carbon\Carbon::parse($v->recorded_at)->format('d/m/Y') }}<br>
                                <span style="font-weight:normal; color:#555;">{{ \Carbon\Carbon::parse($v->recorded_at)->format('h:i A') }}</span>
                            </td>
                            <td style="text-align:center;">
                                <strong style="text-transform:uppercase;">{{ $v->shift ?: '-' }}</strong>
                            </td>
                            <td style="text-align:center; font-weight:bold; color:#111;">
                                {{ $v->blood_pressure ?: '-' }}
                            </td>
                            <td style="text-align:center;">
                                {{ $v->pulse ?: '-' }}
                            </td>
                            <td style="text-align:center;">
                                {{ $v->temperature ?: '-' }}
                            </td>
                            <td style="text-align:center; font-weight:bold;">
                                {{ $v->spo2 ? $v->spo2 . '%' : '-' }}
                            </td>
                            <td style="text-align:center;">
                                {{ $v->respiratory_rate ?: '-' }}
                            </td>
                            <td style="text-align:center;">
                                {{ $v->rbs ?: '-' }}
                            </td>
                            <td style="text-align:center;">
                                {{ $v->weight ? $v->weight . ' kg' : '-' }}
                                @if($v->bmi)
                                    <br><small style="color:#555;">BMI: {{ $v->bmi }}</small>
                                @endif
                            </td>
                            <td style="font-size:9px;">
                                @if(!empty($v->general_appearance))
                                    <div><strong>GA:</strong> {{ $v->general_appearance }}</div>
                                @endif
                                @if(!empty($v->cvs))
                                    <div><strong>CVS:</strong> {{ $v->cvs }}</div>
                                @endif
                                @if(!empty($v->rs))
                                    <div><strong>RS:</strong> {{ $v->rs }}</div>
                                @endif
                                @if(!empty($v->notes))
                                    <div>{{ $v->notes }}</div>
                                @endif
                                @if(empty($v->general_appearance) && empty($v->cvs) && empty($v->rs) && empty($v->notes))
                                    -
                                @endif
                            </td>
                            <td style="text-align:center; font-size:9px;">
                                {{ $v->recorded_by ?: 'Staff' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="border:1px dashed #ccc; padding:20px; text-align:center; color:#888; margin-top:15px; border-radius:4px;">
                    No vitals recordings found for this IPD admission.
                </div>
                @endif
            </div>

            <div class="content-bottom-spacer"></div>
        </div>

        <!-- Footer Section (Pinned) -->
        <div class="footer-pinned">
            @if($showSignatures)
            <div style="margin-top:{{ $m['signature']['top'] ?? 0 }}px; margin-left:{{ $m['signature']['left'] ?? 0 }}px; margin-right:{{ $m['signature']['right'] ?? 0 }}px; margin-bottom:{{ $m['signature']['bottom'] ?? 0 }}px;">
                <table style="width: 100%; border: none; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; border: none; text-align: left; vertical-align: top; padding: 0;">
                            <div style="display: inline-block; text-align: center; width: 220px;">
                                <div style="border-top: 1.5px solid #333; width: 100%; margin-bottom: 5px;"></div>
                                <div style="font-size: 11px; font-weight: bold; color: #111;">Staff / Duty Nurse Sign</div>
                            </div>
                        </td>
                        <td style="width: 50%; border: none; text-align: right; vertical-align: top; padding: 0;">
                            <div style="display: inline-block; text-align: center; width: 240px;">
                                <div style="border-top: 1.5px solid #333; width: 100%; margin-bottom: 5px;"></div>
                                <div style="font-size: 11px; font-weight: bold; color: #111;">Attending Doctor's Sign</div>
                                <div style="font-size: 9.5px; color: #555; line-height: 1.3; margin-top: 3px;">{{ $ipdPatient->doctor->name ?? '' }}</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            @endif

            @if($showFooter)
            <div style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 0 }}px;">
                @if($footerImg)
                    <img src="{{ asset('img/' . $footerImg) }}" alt="Footer" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block;">
                @else
                    <div style="border-top: 1px solid #ccc; padding-top: 5px; text-align: center; font-size: 10px; color: #777;">
                        {{ setting('hospital_name', 'Code Blue Emergency Hospital') }} &bull; In-Patient Vitals &amp; Clinical Observation Record
                    </div>
                @endif
            </div>
            @endif
        </div>

    </div>

    <!-- Print Button -->
    <button class="print-btn" onclick="window.print();">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: middle; margin-right: 5px;">
            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
            <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
        </svg>
        Print Vitals Chart
    </button>
</body>

</html>
