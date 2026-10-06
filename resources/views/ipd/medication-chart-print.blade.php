<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Medication Administration Record - {{ $ipd_patient->ipd_no ?? ($ipd_patient->patient->name ?? 'Patient') }}</title>
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
            padding: 16px 20px 20px 20px;
            position: relative;
            min-height: 980px;
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
            padding-top: 15px;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        .content-bottom-spacer {
            height: 10px;
        }

        .section-title {
            font-size: 11.5px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            color: {!! setting('theme_color', '#054bf0') !!};
            border-bottom: 1px solid {!! setting('theme_color', '#054bf0') !!};
            padding-bottom: 2px;
            text-transform: uppercase;
        }

        /* --- Patient Info Box --- */
        .patient-info-box {
            border: 1px solid #333;
            margin-bottom: 12px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 1px solid #333;
        }

        .info-table td {
            padding: 4px 6px;
            font-size: 10.5px;
            border: 1px solid #ddd;
            line-height: 1.3;
        }

        /* --- Tables --- */
        .chart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 10px;
            font-size: 10px;
        }

        .chart-table th {
            background-color: {!! setting('theme_color', '#054bf0') !!};
            color: white;
            padding: 6px 5px;
            text-align: left;
            font-weight: bold;
            font-size: 9.5px;
            border: 1px solid #333;
            text-transform: uppercase;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .chart-table td {
            padding: 5px;
            border: 1px solid #333;
            vertical-align: top;
        }

        .chart-table tr:nth-child(even) {
            background-color: #f7f9fc;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .badge {
            display: inline-block;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-given { background-color: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-not-given { background-color: #ffe4e6; color: #9f1239; border: 1px solid #fecdd3; }
        .badge-held { background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .badge-refused { background-color: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe; }
        .badge-scheduled { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }

        .legend-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 4px;
            margin-bottom: 10px;
            font-size: 9.5px;
            background: #f8fafc;
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
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
                height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .print-btn {
                display: none !important;
            }

            .bill-container {
                border: none !important;
                padding: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                min-height: auto !important;
                display: block !important;
            }

            .main-content {
                display: block !important;
            }

            .content-bottom-spacer {
                display: none !important;
            }

            .footer-pinned {
                position: relative !important;
                bottom: auto !important;
                left: auto !important;
                right: auto !important;
                width: 100% !important;
                margin-top: 25px !important;
                padding-top: 10px !important;
                background: transparent !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .footer-pinned,
            .footer-pinned table,
            .footer-pinned tr,
            .footer-pinned td,
            .footer-pinned div {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .chart-table {
                page-break-inside: auto;
            }

            .chart-table tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .chart-table th {
                background-color: #333 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    @php
        $docKey = 'ipd_medication_chart';

        // 1. Margins from settings
        $m = json_decode(setting('pdf_margins_' . $docKey, setting('pdf_margins_ipd_vitals_chart', setting('pdf_margins_opd_prescription', '{}'))), true) ?? [];

        // 2. Header & Top Spacer
        $showHeader = setting('show_header_' . $docKey, 'yes') == 'yes';
        $headerImg = setting('ipd_header_image', setting('opd_header_image'));
        $spacerHeight = setting('spacer_' . $docKey, setting('spacer_ipd_vitals_chart', setting('spacer_opd_prescription', '180')));

        // 3. Title & Footer & Signatures
        $hideTitle = setting('hide_title_' . $docKey, 'no') == 'yes';
        $showFooter = setting('show_footer_' . $docKey, 'yes') == 'yes';
        $footerImg = setting('ipd_footer_image', setting('opd_footer_image'));
        $showSignatures = setting('show_signatures_' . $docKey, 'yes') == 'yes';

        $bedName = $ipd_patient->beds->name ?? null;
        $bedTypeName = $ipd_patient->beds->bedType->name ?? null;
        $bedWard = $bedName ? ($bedTypeName ? "{$bedName} ({$bedTypeName})" : $bedName) : null;
        $doa = !empty($ipd_patient->admission_date) ? \Carbon\Carbon::parse($ipd_patient->admission_date)->format('d-m-Y h:i A') : null;
        $doctorName = $ipd_patient->doctor->name ?? null;
        $departmentName = $ipd_patient->doctor->department->name ?? null;
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
                    <table width="100%" style="border-bottom:2px solid {!! setting('theme_color', '#054bf0') !!}; margin-bottom:15px; padding-bottom:10px; border-collapse: collapse;">
                        <tr>
                            <td style="width:90px; vertical-align:top; border:none;">
                                <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Hospital Logo" style="height:70px;">
                            </td>
                            <td style="text-align:right; vertical-align:top; border:none;">
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
                    MEDICATION ADMINISTRATION RECORD (MAR)
                </div>
            </div>
            @endif

            <!-- 3. Patient Information -->
            <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
                <div class="patient-info-box">
                    @include('components.pdf-patient-info', [
                        'docKey' => $docKey,
                        'patient' => $ipd_patient->patient ?? null,
                        'uhid' => $uhid ?? null,
                        'ipd_no' => $ipd_patient->ipd_no ?? $ipd_patient->id ?? null,
                        'invoice_no' => null,
                        'appointment_date' => now()->format('d-m-Y h:i A'),
                        'doctor_name' => $doctorName,
                        'department_name' => $departmentName,
                        'bed_ward' => $bedWard,
                        'doa' => $doa,
                    ])
                </div>
            </div>

            <!-- 4. Clinical Status Legend Bar -->
            <div class="legend-bar">
                <strong style="color: #333;">Status Legend:</strong>
                <div><span class="badge badge-given">G</span> Given</div>
                <div><span class="badge badge-not-given">NG</span> Not Given</div>
                <div><span class="badge badge-held">H</span> Skipped / Held</div>
                <div><span class="badge badge-refused">R</span> Refused by Patient</div>
                <div><span class="badge badge-scheduled">DUE</span> Scheduled</div>
            </div>

            <!-- 5. Active Prescriptions (Doctor's Orders) -->
            <div class="section-title">1. Active Prescribed Medications (Doctor's Medication Orders)</div>
            <table class="chart-table">
                <thead>
                    <tr>
                        <th style="width: 25px; text-align: center;">#</th>
                        <th>Medicine Name &amp; Strength</th>
                        <th style="width: 70px;">Type</th>
                        <th style="width: 75px;">Route</th>
                        <th style="width: 80px;">Frequency</th>
                        <th style="width: 75px;">Duration</th>
                        <th style="width: 80px;">Start Date</th>
                        <th>Doctor Instructions</th>
                        <th style="width: 60px; text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($active_medications as $idx => $m)
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td><strong>{{ $m->medicine_name }}</strong> @if($m->dose) <span style="color:#555;">({{ $m->dose }})</span> @endif</td>
                            <td>{{ $m->dose_type ?: 'Tablet' }}</td>
                            <td><strong>{{ $m->route ?: 'Oral' }}</strong></td>
                            <td><strong>{{ $m->frequency ?: 'OD' }}</strong></td>
                            <td>{{ $m->duration ?: '5 Days' }}</td>
                            <td>{{ $m->start_date ? $m->start_date->format('d-m-Y') : '--' }}</td>
                            <td>{{ $m->instructions ?: '--' }}</td>
                            <td style="text-align: center;">
                                @if($m->status === 'active')
                                    <span style="color: #166534; font-weight: bold;">ACTIVE</span>
                                @elseif($m->status === 'discontinued')
                                    <span style="color: #991b1b; font-weight: bold;">STOPPED</span>
                                @else
                                    {{ strtoupper($m->status) }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; color: #888; font-style: italic;">No active prescribed medications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- 6. Medication Administration Record Log -->
            <div class="section-title">2. Medication Administration Record (Execution &amp; Audit Trail)</div>
            <table class="chart-table">
                <thead>
                    <tr>
                        <th style="width: 25px; text-align: center;">#</th>
                        <th style="width: 110px;">Scheduled Time</th>
                        <th style="width: 55px;">Shift</th>
                        <th>Medicine &amp; Dose</th>
                        <th style="width: 65px;">Route</th>
                        <th style="width: 65px; text-align: center;">Status</th>
                        <th style="width: 100px;">Actual Given</th>
                        <th style="width: 65px;">Qty Given</th>
                        <th>Administered By</th>
                        <th style="width: 110px;">Audit Timestamp</th>
                        <th>Remarks / Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($administrations as $idx => $admin)
                        <tr>
                            <td style="text-align: center;">{{ $idx + 1 }}</td>
                            <td><strong>{{ $admin->scheduled_at ? $admin->scheduled_at->format('d-m-Y, h:i A') : '--' }}</strong></td>
                            <td>{{ $admin->shift ?? '--' }}</td>
                            <td>
                                <strong>{{ $admin->medication->medicine_name ?? 'N/A' }}</strong>
                                @if(!empty($admin->medication->dose))
                                    <span style="color: #555;">({{ $admin->medication->dose }})</span>
                                @endif
                            </td>
                            <td>{{ $admin->route_administered ?: ($admin->medication->route ?? 'Oral') }}</td>
                            <td style="text-align: center;">
                                @if($admin->status === 'given')
                                    <span class="badge badge-given">GIVEN</span>
                                @elseif($admin->status === 'not_given')
                                    <span class="badge badge-not-given">NOT GIVEN</span>
                                @elseif($admin->status === 'held')
                                    <span class="badge badge-held">HELD</span>
                                @elseif($admin->status === 'refused')
                                    <span class="badge badge-refused">REFUSED</span>
                                @else
                                    <span class="badge badge-scheduled">DUE</span>
                                @endif
                            </td>
                            <td>{{ $admin->actual_administered_at ? $admin->actual_administered_at->format('d-m-Y, h:i A') : '--' }}</td>
                            <td>{{ $admin->given_quantity ?: ($admin->medication->dose ?: '--') }}</td>
                            <td>
                                <strong>{{ $admin->staff_name ?? '--' }}</strong>
                                @if(!empty($admin->staff_id))
                                    <span style="font-size: 8.5px; color: #666; font-family: monospace;">({{ $admin->staff_id }})</span>
                                @endif
                            </td>
                            <td style="font-family: monospace; font-size: 8.5px; color: #444;">
                                {{ $admin->recorded_at ? $admin->recorded_at->format('d-m-Y H:i:s') : '--' }}
                            </td>
                            <td style="font-style: italic;">{{ $admin->remarks ?: '--' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" style="text-align: center; color: #888; font-style: italic;">No dose administration records registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="content-bottom-spacer"></div>
        </div>

        <!-- Footer Section (Pinned) -->
        <div class="footer-pinned">
            @if($showSignatures)
            <div style="margin-top:{{ $m['signature']['top'] ?? 0 }}px; margin-left:{{ $m['signature']['left'] ?? 0 }}px; margin-right:{{ $m['signature']['right'] ?? 0 }}px; margin-bottom: 12px;">
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
                                <div style="font-size: 9.5px; color: #555; line-height: 1.3; margin-top: 3px;">{{ $ipd_patient->doctor->name ?? '' }}</div>
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
                    <div style="border-top: 1px solid #ddd; padding-top: 6px; text-align: center; font-size: 10.5px; color: #555;">
                        <span style="font-weight: bold; color: {!! setting('theme_color', '#054bf0') !!};">
                            For Emergency Services: +91 {{ setting('phone', '7070899941') }}
                        </span>
                        @if(setting('address'))
                            &nbsp;|&nbsp; <span>{{ setting('address', 'Mithanpura-Bela Road, Kailash Vivaah Bhawan, Muzaffarpur, Bihar, 842002') }}</span>
                        @endif
                        <div style="font-size: 9.5px; color: #777; margin-top: 3px;">
                            {{ setting('hospital_name', 'Code Blue Emergency Hospital') }} &bull; In-Patient Medication Administration Record (MAR)
                        </div>
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
        Print MAR Chart
    </button>
</body>

</html>
