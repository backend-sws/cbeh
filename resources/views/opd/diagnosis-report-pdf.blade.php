<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Diagnosis Report - {{ $group_test_no }}</title>

    <style>
        /* Base Settings */
        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            background: #f8f9fa;
            /* Light background for screen */
            color: #222;
            line-height: 1.3;
        }

        .bill-container {
            width: 750px;
            /* Standard A4 width */
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
            margin-bottom: 25px;
        }

        /* --- Patient Info Table --- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #333;
        }

        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
            border: 1px solid #333;
            width: 25%;
        }

        .info-table td:nth-child(odd) {
            background-color: #f9f9f9;
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

        /* --- Test/Investigation Table --- */
        .test-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .test-table th,
        .test-table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
        }

        .test-table th {
            background: #ccc;
            /* Light Gray Header */
            color: #1e293b;
            font-weight: bold;
        }

        /* Badges for Urgency */
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            border: 1px solid transparent;
        }

        /* --- Footer Styles (QR & Signature) --- */
        .footer-split {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            padding-top: 20px;
        }

        .qr-section {
            width: 35%;
            text-align: center;
        }

        .signature-section {
            width: 40%;
        }

        .small-points {
            font-size: 9px;
            color: #555;
            text-align: left;
            margin-top: 5px;
            line-height: 1.4;
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

            /* Print Specific Border Fixes */
            .info-table,
            .info-table td,
            .test-table,
            .test-table th,
            .test-table td {
                border: 1px solid #000 !important;
            }
        }
    </style>
</head>

<body>
    @php
        $m = json_decode(setting('pdf_margins_opd_diagnosis'), true);
    @endphp

    <button onclick="window.print()" class="print-btn">Print Report</button>

    <div class="bill-container">

        {{-- ===== HEADER ===== --}}
        @if(setting('show_header_opd_diagnosis', 'yes') == 'yes')
        
            <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
            @if(setting('opd_header_image'))
                <img src="{{ asset('img/' . setting('opd_header_image')) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
            @else
<table class="header-table">
            <tr>
                <td style="width:90px; vertical-align:top;">
                    <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" style="height: 70px;">
                </td>
                <td style="text-align:right; vertical-align:top;">
                    <div style="font-size:18px; font-weight:bold; color: {!! setting('theme_color', '#e91e63') !!}; margin-bottom: 5px;">
                        {{ setting('hospital_name', 'Hospital Name') }}
                    </div>
                    <div style="font-size: 12px; color: #555;">
                        {{ setting('tagline', 'Tagline') }}<br>
                        {{ setting('address', 'Address Here') }}<br>
                        Phone: +91 {{ setting('phone') }}, {{ setting('mobile') }}<br>
                        Website: {{ setting('website', 'www.example.com') }}
                    </div>
                </td>
            </tr>
        </table>
            @endif
        </div>
        @else
        <div style="height: {{ setting('spacer_opd_diagnosis', '180') }}px;"></div>
        @endif

        <div style="margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
        <div
            style="text-align:center; font-weight:bold; font-size:16px; margin-top:45px; text-decoration: underline; color: #333;"">
            DIAGNOSIS 
        </div>

        {{-- ===== PATIENT INFORMATION ===== --}}
        <div class="section-title">Patient Information</div>
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


        {{-- ===== REQUIRED INVESTIGATIONS ===== --}}
        <div class="section-title">Required Investigations</div>
        <table class="test-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="45%">Test Name</th>
                    <th width="30%">Department / Category</th>
                    <th width="20%">Priority</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tests as $index => $test)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $test->test_name }}</strong>
                        </td>
                        <td>{{ $test->chargeCategory->charge_category ?? 'N/A' }}</td>
                        <td>
                            @php
                                $urgency = $test->urgency ?? 'Normal';
                                $bg = '#e8f5e9';
                                $color = '#2e7d32'; // Normal (Green)

                                if ($urgency == 'Urgent') {
                                    $bg = '#fff8e1';
                                    $color = '#ff8f00'; // Orange
                                } elseif ($urgency == 'Stat') {
                                    $bg = '#ffebee';
                                    $color = '#c62828'; // Red
                                }
                            @endphp
                            <span class="badge"
                                style="background-color: {{ $bg }}; color: {{ $color }}; border-color: {{ $bg }};">
                                {{ $urgency }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

</div>

                {{-- ===== FOOTER SPLIT (QR + SIGNATURE) ===== --}}
        @if(setting('show_footer_opd_diagnosis', 'yes') == 'yes')
        <div class="footer-split" style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 0 }}px;">
            <div class="qr-section">
                @if (setting('hospital_qr'))
                    <img src="{{ asset('img/' . setting('hospital_qr')) }}"
                        style="height: 80px; width: 80px; border: 1px solid #ddd; padding: 3px;">
                @else
                    <div
                        style="height: 80px; width: 80px;  display:flex; align-items:center; justify-content:center; margin:0 auto; color:#999; font-size:10px;">
                        No QR</div>
                @endif
                <div style="font-weight: bold; font-size: 12px; margin-top: 5px; color: #333;">Scan & Pay</div>
                <div class="small-points">
                    <div>* Sample collection as per standard protocols.</div>
                    <div>* Report delivery time depends on test type.</div>
                </div>
            </div>

            <div class="signature-section">
                <table width="100%">
                    <tr>
                        <td style="width:50%; text-align:center; border:none;">
                            <div style="height:30px;"></div>
                            <div style="border-top:1px solid #333; width:60%; margin:0 auto;"></div>
                            <div style="font-size:11px; margin-top:3px;">Authorized Signatory / Lab In-charge</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ===== BOTTOM STRIP ===== --}}
        {{-- <div class="default-text-footer" style="padding-top:5px; margin-top: 10px;  font-size:11px; text-align:center; color:#555;">
            <div style="display: {{ setting('opd_footer_image') ? 'none' : 'block' }} !important; font-weight:bold; color: {!! setting('theme_color', '#e91e63') !!};">
                For Emergency Services: +91 {{ setting('phone') }}, {{ setting('mobile') }}
            </div>
            <div>
                {{ setting('address') }}
            </div>
        </div> --}}

    </div>

            @endif
    <script>
        window.onload = function() {
            window.print();
        };
    </script>


        <div style="height: 130px;"></div>
        @if(setting('opd_footer_image'))
            <div style="position:fixed; bottom:0; left:0; width:100%; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . setting('opd_footer_image')) }}" style="width:100%; width:100%; height:auto; display:block;" alt="Footer">
            </div>
        @endif
    
@if(setting('show_qr_payment_section', 'yes') == 'no')
<style>
    .qr-section,
    .signature-section,
    .small-points,
    .thank-you-msg, .qr-hide-block {
        display: none !important;
    }
</style>
@endif

@if(setting('opd_footer_image'))
<style>
    .default-text-footer { display: none !important; }
</style>
@endif
</body>

</html>
