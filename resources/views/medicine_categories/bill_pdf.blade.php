<!DOCTYPE html>
<html>

<head>
    <title>Medicine Bill - {{ $bill->bill_number }}</title>
    <style>
        /* Base Settings */
        html,
        body {
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

        .header-table {
            width: 100%;
            border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!};
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid #333;
        }

        .info-table td {
            padding: 5px 8px;
            border: 1px solid #333;
            width: 25%;
        }

        .info-table td:nth-child(odd) {
            background-color: #fff;
            font-weight: bold;
            color: #444;
        }

        .charges-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
            margin-bottom: 20px;
        }

        .charges-table th,
        .charges-table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: right;
        }

        .charges-table th {
            background: #f3f4f6;
            text-align: center;
            font-weight: bold;
            color: #1e293b;
        }

        .charges-table td:nth-child(2) {
            text-align: left;
        }

        /* --- LAYOUT FOR SUMMARY & QR --- */
        .summary-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 10px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .left-section {
            width: 45%;
            text-align: center;
            padding-right: 10px;
        }

        .right-section {
            width: 50%;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 5px;
            border-bottom: 1px solid #eee;
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: {!! setting('theme_color', '#e91e63') !!};
            color: white;
            font-size: 14px;
        }

        .small-points {
            font-size: 9px;
            color: #555;
            text-align: left;
            margin-top: 10px;
            line-height: 1.4;
            border: 1px solid #eee;
            padding: 8px;
            background: #f9f9f9;
            border-radius: 4px;
        }

        .signature-section {
            margin-top: 0px;
            text-align: center;
        }

        /* --- Footer Styles --- */
        .footer-split {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            padding-top: 20px;
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
            .bill-container { width: 100%; margin: 0; padding: 0; border: none; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_pharmacy_bill', '{}'), true) ?? []; @endphp

    <button onclick="window.print()" class="print-btn">Print Bill</button>

    <div class="bill-container">

        {{-- HEADER --}}
        <div style="padding-top:{{ $m['header']['top'] ?? 0 }}px; padding-left:{{ $m['header']['left'] ?? 0 }}px; padding-right:{{ $m['header']['right'] ?? 0 }}px; padding-bottom:{{ $m['header']['bottom'] ?? 0 }}px; box-sizing: border-box; width: 100%;">

            @if(setting('show_header_pharmacy_bill', 'yes') == 'yes')

                @if(setting('pharmacy_header_image'))
                    <img src="{{ asset('img/' . setting('pharmacy_header_image')) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
                @else
                    <table class="header-table">
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
            <div style="text-align:center; font-weight:bold; font-size:16px; text-decoration: underline; color: #333;">
                DEPARTMENT OF PHARMACY
            </div>
            <div style="text-align:center; font-weight:bold; font-size:14px; text-decoration:underline; color: #555; margin-bottom: 10px;">
                INVOICE
            </div>
        </div>

        {{-- PATIENT INFO --}}
        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
                    @include('components.pdf-patient-info', [
            'docKey' => 'pharmacy_bill',
            'patient' => $bill->patient ?? null,
            'uhid' => $bill->patient->patient_unique_id ?? null,
            'ipd_no' => $bill->ipd_no ?? null,
            'invoice_no' => $bill->invoice_no ?? null,
            'appointment_date' => $bill->created_at ? \Carbon\Carbon::parse($bill->created_at)->format('d-m-Y') : null,
            'doctor_name' => $bill->doctor->name ?? null,
        ])

        </div>

        {{-- CONTENT / MEDICINES TABLE --}}
        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
            <table class="charges-table">
                <thead>
                    <tr>
                        <th width="5%">S.No</th>
                        <th style="text-align: left;">Product Name</th>
                        <th width="12%">Batch No</th>
                        <th width="12%">Expiry</th>
                        <th width="10%">Qty</th>
                        <th width="12%">Rate</th>
                        <th width="15%">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bill->saleMedicine as $index => $item)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td><strong>{{ $item->medicine->medicine }}</strong></td>
                            <td style="text-align: center;">{{ $item->lot_no ?? '-' }}</td>
                            <td style="text-align: center;">
                                {{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('m/y') : '-' }}
                            </td>
                            <td style="text-align: center;">
                                @php
                                    $stripSize = (int) ($item->medicine->strip_quantity > 0 ? $item->medicine->strip_quantity : 1);
                                    $unitType = strtolower($item->unit_type ?? 'piece');
                                    if ($unitType == 'strip') {
                                        echo (float) $item->quantity . ' Strip (' . ((float) $item->quantity * $stripSize) . ' Pcs)';
                                    } else {
                                        echo $item->quantity . ' (Pcs)';
                                    }
                                @endphp
                            </td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary-container">
                <div class="left-section">
                    @if (setting('hospital_qr'))
                        <img src="{{ asset('img/' . setting('hospital_qr')) }}" style="height: 100px; width: 100px; border: 1px solid #ddd; padding: 5px;">
                    @else
                        <div style="height: 100px; width: 100px; display:flex; align-items:center; justify-content:center; margin:0 auto; color:#999; font-size:10px;">
                            No QR
                        </div>
                    @endif
                    <div style="font-weight: bold; font-size: 11px; margin-top: 5px;">Scan &amp; Pay</div>

                    <div class="small-points">
                        <div>* E &amp; O.E. </div>
                        <div>* This is a System Generated Receipt. </div>
                        <div>* This receipt is not Valid for Medico-legal Purpose. </div>
                        <div>* All Legal Claims are Subject to {{ setting('city', 'Muzaffarpur') }} Jurisdiction Only. </div>
                    </div>
                </div>

                <div class="right-section">
                    <table class="qr-hide-block summary-table">
                        <tr>
                            <td>Sub Total:</td>
                            <td>&#x20B9; {{ number_format($bill->total, 2) }}</td>
                        </tr>
                        @if ($bill->tax_amount > 0)
                            <tr>
                                <td>Total Tax:</td>
                                <td>+ &#x20B9; {{ number_format($bill->tax_amount, 2) }}</td>
                            </tr>
                        @endif
                        @if ($bill->discount > 0)
                            <tr>
                                <td>Discount:</td>
                                <td>- &#x20B9; {{ number_format($bill->discount, 2) }}</td>
                            </tr>
                        @endif

                        @php
                            $paidTotal = $bill->paid_cash + $bill->paid_online;
                            $netAmount = $bill->net_amount;
                            $balance = round($netAmount - $paidTotal, 2);
                        @endphp

                        <tr class="total-row">
                            <td style="border:none;">Net Amount:</td>
                            <td style="border:none;">&#x20B9; {{ number_format($netAmount, 2) }}</td>
                        </tr>

                        <tr><td colspan="2" style="height: 5px;"></td></tr>

                        {{-- Payment Status --}}
                        @if ($bill->paid_cash > 0)
                            <tr><td>Paid (Cash):</td><td>&#x20B9; {{ number_format($bill->paid_cash, 2) }}</td></tr>
                        @endif
                        @if ($bill->paid_online > 0)
                            <tr><td>Paid (Online):</td><td>&#x20B9; {{ number_format($bill->paid_online, 2) }}</td></tr>
                        @endif

                        @if ($balance > 0.05)
                            <tr><td style="color:red; font-weight:bold;">Balance Due:</td><td style="color:red; font-weight:bold;">&#x20B9; {{ number_format($balance, 2) }}</td></tr>
                        @else
                            <tr><td colspan="2" style="text-align:center; background:#e6fffa; color:#047857; font-weight:bold; padding:8px;">PAID FULLY</td></tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- FOOTER SECTION --}}
        @if(setting('show_footer_pharmacy_bill', 'yes') == 'yes')
            <div class="signature-section qr-hide-block" style="padding-top:{{ $m['footer']['top'] ?? 0 }}px; padding-left:{{ $m['footer']['left'] ?? 0 }}px; padding-right:{{ $m['footer']['right'] ?? 0 }}px; padding-bottom:{{ $m['footer']['bottom'] ?? 0 }}px; box-sizing: border-box;">
                <div style="float: right; width: 200px;">
                    <div style="height: 40px;"></div>
                    <div style="border-top:1px solid #333;"></div>
                    <div style="font-size:11px; margin-top:3px;">Authorized Signature</div>
                </div>
                <div style="clear: both;"></div>
            </div>

            <div class="qr-hide-block" style="padding-top:10px; margin-top: auto; font-size:11px; text-align:center; color:#555;">
                <div class="thank-you-msg" style="font-size: 20px; color: #000; font-family: 'Monotype Corsiva', cursive; font-style: italic;">
                    Thank you for Payment. Visit Again!
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