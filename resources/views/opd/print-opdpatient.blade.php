<!DOCTYPE html>
<html>

<head>
    <title>OPD Bill - {{ $opdPatient->opd_no }}</title>
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
            margin-top: 15px;
            margin-bottom: 5px;
            color: {!! setting('theme_color', '#e91e63') !!};
            
            padding-bottom: 3px;
            text-transform: uppercase;
        }

        /* --- Charges Table --- */
        .charges-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .charges-table th,
        .charges-table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: right;
        }

        .charges-table th {
            background: #ccc;
            /* Light Gray Header */
            color: #1e293b;
            font-weight: bold;
            text-align: center;
        }

        .charges-table td:first-child,
        .charges-table th:first-child {
            text-align: left;
        }

        /* --- Summary Table --- */
        .summary-table {
            width: 50%;
            margin-left: auto;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 6px;
            border-bottom: 1px solid #eee;
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: {!! setting('theme_color', '#e91e63') !!};
            color: white;
            font-size: 13px;
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

            .info-table th {
                background-color: {!! setting('theme_color', '#e91e63') !!} !important;
                color: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>

    @php $m = json_decode(setting('pdf_margins_opd_bill', '{}'), true) ?? []; @endphp
    <button onclick="window.print()" class="print-btn">Print Bill</button>

    <div class="bill-container">

        <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
        @if(setting('show_header_opd_bill', 'yes') == 'yes')
        
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
                        Phone: +91 {{ setting('phone') }} | Email: {{ setting('email') }}<br>
                        Website: {{ setting('website', 'www.example.com') }}
                    </div>
                </td>
            </tr>
        </table>
            @endif
        @else
        <div style="height: {{ setting('spacer_opd_bill', '180') }}px;"></div>
        @endif
        </div>

        <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
        <div
            style="text-align:center; font-weight:bold; font-size:16px; margin-top:5px; text-decoration: underline; color: #333;">
            OPD DEPARTMENT
        </div>
        </div>
        {{-- <div
            style="text-align:center; font-weight:bold; font-size:14px; text-decoration:underline; color: #555; margin-bottom: 10px;">
            OPD BILL / RECEIPT
        </div> --}}

        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
        <div class="section-title">Patient Information</div>
        @include('components.pdf-patient-info', [
            'docKey' => 'opd_bill',
            'patient' => $opdPatient->patient ?? null,
            'uhid' => $patient_uhid ?? null,
            'ipd_no' => $opdPatient->opd_no ?? null,
            'invoice_no' => $opdPatient->invoice_no ?? null,
            'appointment_date' => $opdPatient->appointment_date ? \Carbon\Carbon::parse($opdPatient->appointment_date)->format('d-m-Y') : null,
            'doctor_name' => $opdPatient->doctor->name ?? null,
            'department_name' => $opdPatient->doctor->department->name ?? null,
        ])
        </div>

        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
        <div class="section-title">Billing Details</div>
        <table class="charges-table">
            <thead>
                <tr>
                    <th width="70%" style="text-align: left;">Particulars</th>
                    <th width="30%">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>OPD Consultant Charge</td>
                    <td>{{ number_format($opdPatient->standard_charge, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <div style="margin-top: 20px; text-align: center; font-style: italic; border: 1px solid #eee; padding: 5px;">
            Amount in words: {{ amountToWords($opdPatient->final_amount) }} Only.
        </div>
        <div style="margin-top: 10px;">
            <table class="summary-table">
                <tr>
                    <td>Consultant Charge:</td>
                    <td style="font-weight: bold;">{{ number_format($opdPatient->standard_charge, 2) }}</td>
                </tr>
                @if ($opdPatient->tax > 0)
                    <tr>
                        <td>Tax ({{ $opdPatient->tax ?? 0 }}%):</td>
                        <td>+ {{ number_format(($opdPatient->standard_charge * ($opdPatient->tax ?? 0)) / 100, 2) }}
                        </td>
                    </tr>
                @endif
                @if ($opdPatient->discount > 0)
                    <tr>
                        <td>Discount ({{ $opdPatient->discount ?? 0 }}%):</td>
                        <td>-
                            {{ number_format(($opdPatient->standard_charge * ($opdPatient->discount ?? 0)) / 100, 2) }}
                        </td>
                    </tr>
                @endif

                <tr class="total-row">
                    <td style="border:none;">Net Payable Amount:</td>
                    <td style="border:none; font-size: 14px;">{{ number_format($opdPatient->final_amount, 2) }}</td>
                </tr>
            </table>
        </div>
        </div>



        <div style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 0 }}px;">
        <div class="footer-split qr-hide-block">
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
                    <div>* E & O.E. </div>
                    <div>* This is a System Generated Receipt. </div>
                    <div>* This receipt is not Valid for Medico-legal Purpose. </div>
                    <div>* All Legal Claims are Subject to {{ setting('city', 'Muzaffarpur') }} Judicial Only. </div>
                </div>
            </div>

            <div class="signature-section">
                <table class="qr-hide-block" width="100%">
                    <tr>
                        <td style="width:50%; text-align:center; border:none;">
                            <div style="height:30px;"></div>
                            <div style="border-top:1px solid #333; width:60%; margin:0 auto;"></div>
                            <div style="font-size:11px; margin-top:3px;">Authorized Signature</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="default-text-footer"
            style="padding-top:5px; margin-top: 10px;  font-size:11px; text-align:center; color:#555;">
            {{-- <div style="display: {{ setting('opd_footer_image') ? 'none' : 'block' }} !important; font-weight:bold; color: {!! setting('theme_color', '#e91e63') !!};">
                For Emergency: +91 {{ setting('phone') }}
            </div> --}}
            <div class="thank-you-msg"
                style="margin-top: 2px; font-size: 16px; color: #000000; font-family: 'Monotype Corsiva', cursive; font-style: italic;">
                Thank you for Payment. Get Well Soon!
            </div>
        </div>

    </div>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>


        <div style="height: 130px;"></div>
        @if(setting('show_footer_opd_bill', 'yes') == 'yes' && setting('opd_footer_image'))
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
