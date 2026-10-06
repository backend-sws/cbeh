<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Progress Note - {{ $note->ipdPatient->ipd_no ?? ($note->ipdPatient->patient->name ?? 'Patient') }}</title>
    <style>
        /* Base Settings matching hospital prescription design */
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
            padding: 20px 25px 25px 25px;
            position: relative;
            min-height: 1050px; /* Standard A4 page height in browser preview */
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

        /* --- Header Styles --- */
        .section-title {
            font-size: 1rem;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            color: {!! setting('theme_color', '#054bf0') !!};
            border-bottom: 1px solid {!! setting('theme_color', '#054bf0') !!};
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
            border: 1px solid #333;
            word-wrap: break-word;
            font-size: 11px;
        }

        /* --- Medicine Table --- */
        .med-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .med-table th {
            background-color: {!! setting('theme_color', '#054bf0') !!};
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ccc;
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact;
        }

        .med-table td {
            padding: 8px;
            border: 1px solid #ccc;
        }

        .med-table tr:nth-child(even) {
            background-color: #f2f2f2;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .note-box {
            background: #fdfdfd;
            border: 1px solid #ddd;
            border-left: 4px solid {!! setting('theme_color', '#054bf0') !!};
            padding: 10px 12px;
            margin: 10px 0;
            font-size: 12px;
            color: #222;
            line-height: 1.5;
        }

        /* --- Footer Button --- */
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
                height: 160px;
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

            .med-table th {
                background-color: #333 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact;
                border: 1px solid #000;
            }
            
            .med-table tr:nth-child(even) {
                background-color: #e0e0e0 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    @php
        $docKey = 'ipd_progress_note';

        // 1. Margins from PDF Print Settings
        $m = json_decode(setting('pdf_margins_' . $docKey, setting('pdf_margins_opd_prescription', '{}')), true) ?? [];

        // 2. Header Visibility & Top Spacer for pre-printed letterheads
        $showHeader = setting('show_header_' . $docKey, setting('show_header_opd_prescription', 'yes')) == 'yes';
        $headerImg = setting('ipd_header_image', setting('opd_header_image'));
        $spacerHeight = setting('spacer_' . $docKey, setting('spacer_opd_prescription', '180'));

        // 3. Title Visibility
        $hideTitle = setting('hide_title_' . $docKey, setting('hide_title_opd_prescription', 'no')) == 'yes';

        // 4. Footer Visibility & Footer Image
        $showFooter = setting('show_footer_' . $docKey, setting('show_footer_opd_prescription', 'yes')) == 'yes';
        $footerImg = setting('ipd_footer_image', setting('opd_footer_image'));

        // 5. Consultant Signature Visibility
        $showSignatures = setting('show_consultant_signature', 'yes') == 'yes';

        // Patient info details
        $bedName = $note->ipdPatient->beds->name ?? null;
        $bedTypeName = $note->ipdPatient->beds->bedType->name ?? null;
        $bedWard = $bedName ? ($bedTypeName ? "{$bedName} ({$bedTypeName})" : $bedName) : null;
        $noteDateTime = !empty($note->date_time) ? \Carbon\Carbon::parse($note->date_time)->format('d-m-Y h:i A') : null;
        $doa = !empty($note->ipdPatient->admission_date) ? \Carbon\Carbon::parse($note->ipdPatient->admission_date)->format('d-m-Y h:i A') : null;
        $uhid = \Illuminate\Support\Facades\DB::table('patients')->where('user_id', $note->patient_id)->value('patient_unique_id');
    @endphp

    <div class="bill-container">

        <!-- Top Main Content Area -->
        <div class="main-content">

            <!-- 1. Header Section (Controlled by Settings) -->
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

            <!-- 2. Document Title (Controlled by Settings) -->
            @if(!$hideTitle)
            <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
                <div style="text-align:center; font-weight:bold; font-size:16px; margin-top:10px; margin-bottom: 12px; text-decoration: underline; color: #333; text-transform: uppercase; letter-spacing: 0.5px;">
                    CLINICAL PROGRESS NOTE
                </div>
            </div>
            @endif

            <!-- 3. Patient Information (Controlled by Settings -> Patient Info Fields) -->
            <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
                <div class="section-title" style="margin-top: 5px;">Patient Information</div>
                <div class="patient-info-box">
                    @include('components.pdf-patient-info', [
                        'docKey' => $docKey,
                        'patient' => $note->ipdPatient->patient ?? null,
                        'uhid' => $uhid ?? null,
                        'ipd_no' => $note->ipdPatient->ipd_no ?? null,
                        'invoice_no' => null,
                        'appointment_date' => $noteDateTime,
                        'doctor_name' => $note->staff_name ?? ($note->ipdPatient->doctor->name ?? null),
                        'department_name' => $note->ipdPatient->doctor->department->name ?? null,
                        'bed_ward' => $bedWard,
                        'doa' => $doa,
                    ])
                </div>
            </div>

            <!-- 4. Clinical Note Content & Medicines -->
            <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">

                <!-- Clinical Progress Note (Blank Box) -->
                <div class="section-title" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>{{ $note->title }}</span>
                    <span style="font-size: 11px; font-weight: normal; color: #555;">
                        <strong>Recorded By:</strong> {{ $note->staff_name }} &nbsp;|&nbsp; <strong>Date:</strong> {{ \Carbon\Carbon::parse($note->date_time)->format('d-m-Y h:i A') }}
                    </span>
                </div>
                <div class="note-box" style="white-space: pre-wrap; word-wrap: break-word;">{!! nl2br(e($note->note)) !!}</div>

                <!-- Attached Prescriptions -->
                @if ($note->medicines && count($note->medicines) > 0)
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
                            @foreach ($note->medicines as $med)
                                <tr>
                                    <td>
                                        <strong>{{ $med->medicine_name }}</strong>
                                        @if(!empty($med->category->medicine_category))
                                            <span style="color: #666; font-size: 10px;">({{ strtoupper($med->category->medicine_category) }})</span>
                                        @endif
                                        @if(!empty($med->instructions))
                                            <div style="font-size: 10px; color: #555; font-style: italic; margin-top: 2px;">
                                                Note: {{ $med->instructions }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $med->dosage ?? '—' }}</td>
                                    <td>{{ $med->route ?? 'Oral' }}</td>
                                    <td><strong>{{ $med->dose_interval ?? '—' }}</strong></td>
                                    <td>{{ $med->duration ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <!-- Chronological Addendums Section -->
                @if ($note->addendums && count($note->addendums) > 0)
                    <div class="section-title" style="margin-top: 20px; color: #b45309; border-bottom: 1px solid #f59e0b;">
                        Clinical Addendums ({{ count($note->addendums) }})
                    </div>
                    @foreach ($note->addendums as $addendum)
                        <div class="note-box" style="border-left-color: #f59e0b; background: #fffdf5; border-color: #fde68a; margin-top: 8px;">
                            <div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: bold; color: #92400e; margin-bottom: 6px; border-bottom: 1px dashed #fcd34d; padding-bottom: 4px;">
                                <span><i class="fas fa-pen-nib"></i> Addendum by: {{ $addendum->staff_name }}</span>
                                <span>{{ \Carbon\Carbon::parse($addendum->date_time)->format('d-m-Y h:i A') }}</span>
                            </div>
                            <div style="white-space: pre-wrap; word-wrap: break-word; font-size: 12px; color: #222;">{!! nl2br(e($addendum->note)) !!}</div>
                        </div>
                    @endforeach
                @endif

            </div>

            <!-- Print bottom spacer so content never overlaps fixed footer on print -->
            <div class="content-bottom-spacer"></div>

        </div>

        <!-- Pinned Bottom Footer Section (Always at the very bottom) -->
        <div class="footer-pinned">

            <!-- Consultant & Staff Signatures (Controlled by show_consultant_signature) -->
            @if($showSignatures)
            <div class="consultant-signature-wrapper" style="width: 100%; margin-bottom: 15px;">
                <table width="100%" style="border-collapse: collapse; border: none;">
                    <tr>
                        <td style="width: 50%; text-align: left; vertical-align: top; border: none; padding-left: 10px;">
                            <div style="display: inline-block; text-align: center; width: 220px;">
                                <div style="border-top: 1.5px solid #333; width: 100%; margin-bottom: 5px;"></div>
                                <div style="font-weight: bold; font-size: 11.5px; color: #111;">Staff / Nurse Signature</div>
                            </div>
                        </td>
                        <td style="width: 50%; text-align: right; vertical-align: top; border: none; padding-right: 15px;">
                            <div style="display: inline-block; text-align: center; width: 240px;">
                                <div style="border-top: 1.5px solid #333; width: 100%; margin-bottom: 5px;"></div>
                                <div style="font-weight: bold; font-size: 11.5px; color: #111;">Consultant's Signature</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            @endif

            <!-- Bottom Hospital Emergency Contact & Address / Footer Image (Controlled by show_footer_ipd_progress_note) -->
            @if($showFooter)
            <div class="footer-wrapper" style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 10 }}px;">
                @if($footerImg)
                    <img src="{{ asset('img/' . $footerImg) }}" style="width:100%; height:auto; display:block;" alt="Footer">
                @else
                    <div class="default-text-footer" style="text-align:center; font-size:11px; border-top: 1px solid #ddd; padding-top: 8px;">
                        <div style="font-weight:bold; color:{!! setting('theme_color', '#054bf0') !!}; font-size: 11.5px;">
                            For Emergency Services: +91{{ setting('phone', '7070899941') }}
                        </div>
                        <div style="color: #444; margin-top: 2px;">
                            {{ setting('address', 'Mithanpura-Bela Road, Kailash Vivaah Bhawan, Muzaffarpur, Bihar, 842002') }}
                        </div>
                    </div>
                @endif
            </div>
            @endif

        </div>

        <!-- Floating Print Button (Hidden in Print) -->
        <button onclick="window.print()" class="print-btn">Print Progress Note</button>

    </div>

</body>

</html>
