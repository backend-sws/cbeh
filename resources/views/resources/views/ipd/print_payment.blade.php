<!DOCTYPE html>
<html>

<head>
    <title>IPD Payment Receipt - #{{ $payment->id }}</title>
    <style>
        /* Base Settings */
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            background: #fff; /* Light background for screen */
            color: #222;
            line-height: 1.3;
        }

        .bill-container {
            width: 750px; /* Standard width */
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
            background-color: #fff;
            font-weight: bold;
            color: #444;
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

        /* --- Items Table --- */
        .charges-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .charges-table th, .charges-table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        .charges-table th {
            background: #fff; /* Light Gray Header */
            color: #1e293b;
            font-weight: bold;
            text-align: center;
        }

        /* --- Summary Table --- */
        .summary-table {
            width: 45%;
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
            margin-top: auto; /* Pushes to bottom */
            padding-top: 20px;
        }
        .qr-section { width: 35%; text-align: center; }
        .signature-section { width: 40%; }
        
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
            .footer-spacer { display: none !important; }
            .bill-container, .receipt-container, .prescription-container, .discharge-container, .slip-container { min-height: auto !important; height: auto !important; }

            html, body { background: #fff !important; }
            .bill-container { width: 100%; border: none; margin: 0; padding: 0; }
            .print-btn { display: none; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_ipd_payment', '{}'), true) ?? []; @endphp

    <button onclick="window.print()" class="print-btn">Print Receipt</button>

    <div class="bill-container">
        
        @if(setting('show_header_ipd_bill', 'yes') == 'yes')
                @if(setting('ipd_header_image'))
                    <table width="100%" style="margin:0; padding:0; border:none; border-spacing:0;">
                        <tr>
                            <td style="padding-top:{{ $m['header']['top'] ?? 0 }}px; padding-left:{{ $m['header']['left'] ?? 0 }}px; padding-right:{{ $m['header']['right'] ?? 0 }}px; padding-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
                                <img src="{{ asset('img/' . setting('ipd_header_image')) }}" alt="Header" style="width: 100%; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
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
                <div style="height: {{ setting('spacer_ipd_bill', '180') }}px;"></div>
            @endif

        <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
            <div style="text-align:center; font-weight:bold; font-size:16px; margin-top:5px; text-decoration: underline; color: #333;">
                HOSPITAL PAYMENT RECEIPT
            </div>
        </div>

        @php
            $ipdPatient = $payment->ipdPatient ?? null;
            $uhid = $ipdPatient && $ipdPatient->patient ? ($ipdPatient->patient->patient_unique_id ?? '-') : '-'; 
            $billNo = $ipdPatient ? $ipdPatient->ipd_no : 'N/A';
            $patientName = $ipdPatient && $ipdPatient->patient ? $ipdPatient->patient->name : 'N/A';
            
            // Calculate Total Bill Amount (Sum of Charges)
            $totalBillAmount = 0;
            if($ipdPatient && $ipdPatient->charges) {
                $totalBillAmount = $ipdPatient->charges->sum('total');
            }
        @endphp

        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
        <div class="section-title">Patient Information</div>
        @include('components.pdf-patient-info', [
            'docKey' => 'ipd_payment',
            'patient' => $ipdPatient->patient ?? null,
            'uhid' => $uhid ?? null,
            'ipd_no' => $ipdPatient->ipd_no ?? null,
            'invoice_no' => $invoice ?? null,
            'appointment_date' => now()->format('d-m-Y'),
            'doa' => $ipdPatient->admission_date ? \Carbon\Carbon::parse($ipdPatient->admission_date)->format('d-m-Y h:i A') : null,
            'dod' => $ipdPatient->discharge_date ? \Carbon\Carbon::parse($ipdPatient->discharge_date)->format('d-m-Y h:i A') : (isset($ipdPatient->discharge->discharge_date) ? \Carbon\Carbon::parse($ipdPatient->discharge->discharge_date)->format('d-m-Y h:i A') : 'Not Discharged'),
            'bed_ward' => ($ipdPatient->bed->name ?? '') . ' - ' . ($ipdPatient->ward->name ?? ''),
            'doctor_name' => $ipdPatient->doctor->name ?? null,
            'department_name' => $ipdPatient->doctor->department->name ?? null,
        ])
        </div>

        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
        <div class="section-title">Transaction Details</div>
        <table class="charges-table">
            <thead>
                <tr>
                    <th width="10%">S.No</th>
                    <th width="50%" style="text-align: left;">Description</th>
                    <th width="20%">Payment Mode</th>
                    <th width="20%" style="text-align: right;">Paid Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>
                        Payment Received 
                        {{-- <br><small style="color: #666;">Ref IPD No: {{ $billNo }}</small>
                        @if($payment->note)
                            <br><small style="color: #666; font-style: italic;">Note: {{ $payment->note }}</small>
                        @endif --}}
                    </td>
                    <td style="text-align: center;">{{ $payment->payment_mode->name ?? 'Cash' }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <div style="margin-top: 10px; font-style: italic; background: #fff; padding: 5px; border-left: 3px solid #ccc;">
            <strong>Amount in words:</strong> {{ amountToWords($payment->amount) }} Only.
        </div>
        </div>

        <div style="margin-top: 20px;">
            <table class="summary-table">
                <tr class="total-row">
                    <td style="border: none;">Amount Received:</td>
                    <td style="border: none;">? {{ number_format($payment->amount, 2) }}</td>
                </tr>
                
                <tr><td colspan="2" style="padding:5px;"></td></tr>

                {{-- <tr>
                    <td>Total Bill Amount (Charges):</td>
                    <td>? {{ number_format($totalBillAmount, 2) }}</td>
                </tr>
                <tr>
                    <td style="color: #dc2626; font-weight: bold;">Remaining Due Balance:</td>
                    <td style="color: #dc2626; font-weight: bold;">₹ {{ number_format($balanceDue, 2) }}</td>
                </tr> --}}
            </table>
        </div>
        </div>

        @if(setting('show_footer_ipd_payment', 'yes') == 'yes')
        <div class="footer-split" style="padding-top:{{ $m['footer']['top'] ?? 0 }}px; padding-left:{{ $m['footer']['left'] ?? 0 }}px; padding-right:{{ $m['footer']['right'] ?? 0 }}px; padding-bottom:{{ $m['footer']['bottom'] ?? 0 }}px; box-sizing: border-box;">
            <div class="qr-section">
                @if(setting('hospital_qr'))
                    <img src="{{ asset('img/' . setting('hospital_qr')) }}" style="height: 80px; width: 80px; border: 1px solid #ddd; padding: 3px;">
                @else
                    <div style="height: 80px; width: 80px;  display:flex; align-items:center; justify-content:center; margin:0 auto; color:#999; font-size:10px;">No QR</div>
                @endif
                <div style="font-weight: bold; font-size: 12px; margin-top: 5px; color: #333;">Scan & Pay</div>
                <div class="small-points">
                    <div>* E & O.E. </div>
                        <div>* This is a System Generated Receipt. </div>
                        <div>* This receipt is not Valid for Medico-legal Purpose. </div>
                        <div>* All Legal Claims are Subject to {{ setting('city', 'Muzaffarpur') }} Judicial Only.  </div>
                </div>
            </div>

            <div class="signature-section">
                <table width="100%">
                    <tr>
                        <td style="width:50%; text-align:center; border:none;">
                            <div style="height:30px;"></div>
                            <div style="border-top:1px solid #333; width:60%; margin:0 auto;"></div>
                            <div style="font-size:11px; margin-top:3px;">Authorized Signatory</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="default-text-footer" style="padding-top:5px; margin-top: 10px;  font-size:11px; text-align:center; color:#555;">
            {{-- <div style="display: {{ setting('ipd_footer_image') ? 'none' : 'block' }} !important; font-weight:bold; color: {!! setting('theme_color', '#e91e63') !!};">
                For Emergency: +91 {{ setting('phone') }}
            </div> --}}
            <div class="thank-you-msg" style="margin-top: 2px; font-size: 14px; color: #000000; font-family: 'Monotype Corsiva', cursive; font-style: italic;">
                Thank you for Payment . Get Well Soon!
            </div>
        </div>
    </div>

            @endif
    <script>
        window.onload = function() {
            window.print(); 
        };
    </script>


        <div class="footer-spacer" style="height: 130px;"></div>
        @if(setting('ipd_footer_image'))
            <div style="position:fixed; bottom:0; left:0; left:0; right:0; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . setting('ipd_footer_image')) }}" style="width:100%; width:100%; height:auto; display:block;" alt="Footer">
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

@if(setting('ipd_footer_image'))
<style>
    .default-text-footer { display: none !important; }
</style>
@endif
</body>
</html>