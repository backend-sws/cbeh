<!DOCTYPE html>
<html>

<head>
    <title>Payment Receipt - #{{ $payment->id }}</title>
    <style>
        /* Base Settings */
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            background: #fff;
            color: #222;
            line-height: 1.3;
        }

        .bill-container {
            width: 750px;
            margin: 10px auto;
            background: #fff;
            min-height: 100vh;
            padding: 20px 40px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Wrappers to push footer down */
        .bill-content { flex: 1; }
        .bill-footer { margin-top: auto; }

        /* --- Header Styles --- */
        .header-table {
            width: 100%;
            border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!};
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            color: {!! setting('theme_color', '#e91e63') !!};
            padding-bottom: 3px;
        }

        /* --- Info Tables --- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid #333;
        }
        .info-table td {
            padding: 6px 8px;
            vertical-align: top;
            border: 1px solid #333;
            width: 25%;
        }
        .info-table td:nth-child(odd) {
            background-color: #fff;
            font-weight: bold;
            color: #444;
        }

        /* --- Items Table --- */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background: #f3f4f6;
            font-weight: bold;
            color: #1e293b;
            text-align: center;
        }
        .items-table td:last-child {
            text-align: right;
            font-weight: bold;
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
            font-size: 14px;
        }

        /* --- Footer Split (QR & Sign) --- */
        .footer-split {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
            margin-bottom: 10px;
            padding-top: 15px;
        }
        .qr-section { width: 35%; text-align: center; }
        .signature-section { width: 60%; }

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
            .bill-container { min-height: auto !important; height: auto !important; }
            body { background: #fff; }
            .print-btn { display: none; }
            .bill-container { width: 100%; border: none; padding: 0; margin: 0; }
            .bill-footer { page-break-inside: avoid; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_pharmacy_bill', '{}'), true) ?? []; @endphp

    <button onclick="window.print()" class="print-btn">Print Receipt</button>

    <div class="bill-container">

        {{-- HEADER --}}
        <div style="padding-top:{{ $m['header']['top'] ?? 0 }}px; padding-left:{{ $m['header']['left'] ?? 0 }}px; padding-right:{{ $m['header']['right'] ?? 0 }}px; padding-bottom:{{ $m['header']['bottom'] ?? 0 }}px; box-sizing: border-box; width: 100%;">

            @if(setting('show_header_pharmacy_bill', 'yes') == 'yes')

                @if(setting('pharmacy_header_image'))
                    <img src="{{ asset('img/' . setting('pharmacy_header_image')) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
                @else
                    <table class="header-table" width="100%">
                        <tr>
                            <td style="width:90px; vertical-align:top;">
                                <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Logo" style="height:70px;">
                            </td>
                            <td style="text-align:right; vertical-align:top;">
                                <div style="font-size:18px; font-weight:bold; color:{!! setting('theme_color', '#e91e63') !!}; margin-bottom:5px;">
                                    {{ setting('medical_name') ?: setting('hospital_name', 'Hospital Name') }}
                                </div>
                                <div style="font-size:12px; color:#555;">
                                    @if(setting('medical_lic_no_1') || setting('medical_lic_no_2'))
                                        <p style="margin:0;">
                                            @if(setting('medical_lic_no_1')) DL No: {{ setting('medical_lic_no_1') }} @endif
                                            @if(setting('medical_lic_no_2')) {{ setting('medical_lic_no_1') ? ',' : 'Lic No:' }} {{ setting('medical_lic_no_2') }} @endif
                                        </p>
                                    @endif
                                    {{ setting('address', 'Hospital Address Here') }}<br>
                                    Phone: +91 {{ setting('phone', '0000000000') }}
                                </div>
                            </td>
                        </tr>
                    </table>
                @endif
            @else
                <div style="height: {{ setting('spacer_pharmacy_bill', '180') }}px;"></div>
            @endif
        </div>

        {{-- TITLE --}}
        <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
            <div style="text-align:center; font-weight:bold; font-size:16px; margin-bottom:5px; text-decoration:underline;">
                DEPARTMENT OF PHARMACY
            </div>
            <div style="text-align:center; font-weight:bold; font-size:14px; margin-bottom:15px; text-decoration:underline;">
                PAYMENT RECEIPT
            </div>
        </div>

        <div class="bill-content">

            {{-- PATIENT & PAYMENT DETAILS --}}
            <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
                <div class="section-title">Patient &amp; Payment Details</div>
                        @include('components.pdf-patient-info', [
            'docKey' => 'pharmacy_bill',
            'patient' => $sale->patient ?? null,
            'uhid' => $sale->patient->patient_unique_id ?? null,
            'invoice_no' => $sale->invoice_no ?? null,
        ])

            </div>

            {{-- TRANSACTION DETAILS --}}
            <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
                <div class="section-title">Transaction Details</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th width="10%">#</th>
                            <th width="60%">Description</th>
                            <th width="30%">Paid Amount (&#x20B9;)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;">1</td>
                            <td>
                                @if($payment->medicine_bill_id)
                                    Payment received for Medicine Bill #{{ $payment->bill->bill_number ?? '' }}
                                @else
                                    Payment received towards IPD Pharmacy Account
                                @endif

                                @if($payment->discount && $payment->discount > 0)
                                    <br><small style="color: #666;">(Includes discount of &#x20B9;{{ number_format($payment->discount, 2) }})</small>
                                @endif
                            </td>
                            <td style="font-size: 14px;">&#x20B9; {{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="7" style="text-align: left; background: #f9f9f9; font-style: italic;">
                                In words : ({{ amountToWords($payment->amount) }} Only)
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-top: 10px;">
                    <table class="summary-table">
                        <tr class="total-row">
                            <td style="border: none;">Amount Received:</td>
                            <td style="border: none;">&#x20B9; {{ number_format($payment->amount, 2) }}</td>
                        </tr>

                        <tr><td colspan="2" style="padding:10px;"></td></tr>

                        @if($payment->medicine_bill_id && $payment->bill)
                            <tr>
                                <td>Total Bill Value:</td>
                                <td>&#x20B9; {{ number_format($payment->bill->net_amount, 2) }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- FOOTER SECTION --}}
        @if(setting('show_footer_pharmacy_bill', 'yes') == 'yes')
            <div class="bill-footer">
                <div class="footer-split qr-hide-block" style="padding-top:{{ $m['footer']['top'] ?? 0 }}px; padding-left:{{ $m['footer']['left'] ?? 0 }}px; padding-right:{{ $m['footer']['right'] ?? 0 }}px; padding-bottom:{{ $m['footer']['bottom'] ?? 0 }}px; box-sizing: border-box;">
                    <div class="qr-section">
                        @if(setting('hospital_qr'))
                            <img src="{{ asset('img/' . setting('hospital_qr')) }}" style="height: 80px; width: 80px; border: 1px solid #ddd; padding: 3px;">
                        @else
                            <div style="height: 80px; width: 80px; display:flex; align-items:center; justify-content:center; margin:0 auto; color:#999; font-size:10px;">No QR</div>
                        @endif
                        <div style="font-weight: bold; font-size: 12px; margin-top: 5px;">Scan &amp; Pay</div>
                        <div class="small-points">
                            <div>* E &amp; O.E. </div>
                            <div>* This is a System Generated Receipt. </div>
                            <div>* This receipt is not Valid for Medico-legal Purpose. </div>
                            <div>* All Legal Claims are Subject to {{ setting('city', 'Muzaffarpur') }} Jurisdiction Only. </div>
                        </div>
                    </div>

                    <div class="signature-section">
                        <table class="qr-hide-block" width="100%">
                            <tr>
                                <td style="width:100%; text-align:right; padding-right: 20px;">
                                    <div style="height:40px;"></div>
                                    <div style="border-top:1px solid #333; width:200px; display:inline-block;"></div>
                                    <div style="font-size:11px; margin-top:3px; margin-right: 40px;">Authorized Signature</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="qr-hide-block" style="padding-top:10px; font-size:11px; text-align:center; color:#555;">
                    <div class="thank-you-msg default-text-footer" style="margin-top: 5px; font-size: 20px; color: #222; font-family: 'Monotype Corsiva', cursive; font-style: italic;">
                        Thank you for Payment. Visit Again!
                    </div>
                </div>
            </div>
        @endif

    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

        <div class="footer-spacer" style="height: 130px;"></div>
        @if(setting('pharmacy_footer_image'))
            <div style="position:fixed; bottom:0; left:0; left:0; right:0; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . setting('pharmacy_footer_image')) }}" style="width:100%; width:100%; height:auto; display:block;" alt="Footer">
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

@if(setting('pharmacy_footer_image'))
<style>
    .default-text-footer { display: none !important; }
</style>
@endif
</body>

</html>