<!DOCTYPE html>
<html>

<head>
    <title>IPD Bill - {{ $ipd_patient->ipd_no }}</title>
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
            text-align: center;
        }

        .charges-table th {
            background: #fff;
            color: #1e293b;
            font-weight: bold;
            text-align: center;
        }

        .charges-table td:first-child,
        .charges-table th:first-child {
            text-align: left;
        }

        /* --- Summary Table --- */
        .summary-container {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            align-items: flex-start;
        }

        .summary-table {
            width: 100%;
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
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_ipd_bill', '{}'), true) ?? []; @endphp

    <button onclick="window.print()" class="print-btn">Print Bill</button>

    {{-- LOGIC: Calculate Discount vs Actual Payment --}}
    @php
        // Filter out payments where mode name is 'Discount'
        $discountPayments = $payments->filter(function ($p) {
            return optional($p->payment_mode)->name == 'Discount';
        });

        // Sum the discount amount
        $totalDiscountAmount = $discountPayments->sum('amount');

        // Calculate actual money paid (Total Payment - Discount)
        $actualPaidAmount = $TotalPayment - $totalDiscountAmount;
    @endphp

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
    </div>

        <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
        <div style="text-align:center; font-weight:bold; font-size:16px; margin-top:5px; text-decoration: underline; color: #333;">
            HOSPITAL BILL
        </div>
        </div>

        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
        <div class="section-title">Patient Information</div>
        @include('components.pdf-patient-info', [
            'docKey' => 'ipd_bill',
            'patient' => $ipd_patient->patient ?? null,
            'uhid' => isset($UHID) ? $UHID->patient_unique_id : null,
            'ipd_no' => $ipd_patient->ipd_no ?? null,
            'invoice_no' => $invoice ?? null,
            'appointment_date' => now()->format('d-m-Y'),
            'doa' => $ipd_patient->admission_date ? \Carbon\Carbon::parse($ipd_patient->admission_date)->format('d-m-Y h:i A') : null,
            'dod' => $ipd_patient->discharge_date ? \Carbon\Carbon::parse($ipd_patient->discharge_date)->format('d-m-Y h:i A') : (isset($ipd_patient->discharge->discharge_date) ? \Carbon\Carbon::parse($ipd_patient->discharge->discharge_date)->format('d-m-Y h:i A') : 'Not Discharged'),
            'bed_ward' => ($ipd_patient->bed->name ?? '') . ' / ' . ($ipd_patient->ward->name ?? ''),
            'doctor_name' => $ipd_patient->doctor->name ?? null,
            'department_name' => $ipd_patient->doctor->department->name ?? null,
        ])
        </div>

        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
        <div class="section-title">Billing Details</div>
        <table class="charges-table">
            <thead>
                <tr>
                    <th width="10%" style="text-align: center";>S.No</th>
                    <th width="10%" style="text-align: center";>Date</th>
                    <th width="30%" style="text-align: center;">Particulars</th>
                    <th width="15%">Rate</th>
                    <th width="15%">Qty</th>
                    <th width="20%">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($charges as $charge)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td style="text-align: center;">
                            {{ \Carbon\Carbon::parse($charge->date)->format('d-m-Y') }}
                        </td>
                        <td style="text-align: center;">
                           {{ $charge->chargeCategory->charge_category ?? '-' }}
                        </td>
                        <td>{{ number_format($charge->applied_charge, 2) }}</td>
                        <td style="text-align: center;">{{ $charge->quantity }}</td>
                        <td>{{ number_format($charge->total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">No charges found.</td>
                    </tr>
                @endforelse
                <tr style="background-color: #fff; font-weight:bold;">
                    <td colspan="5" style="text-align: right;">Total Amount:</td>
                    <td>{{ number_format($Totalcharge, 2) }}</td>
                </tr>
            </tbody>
        </table>

        @if (count($payments) > 0)
            <div class="section-title">Payment History</div>
            <table class="charges-table">
                <thead>
                    <tr>
                        <th width="40%" style="text-align: center;">Payment Mode</th>
                        <th width="30%" style="text-align: center;">Date</th>
                        <th width="30%">Paid Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        {{-- FILTER: Skip if mode is Discount --}}
                        @if (optional($payment->payment_mode)->name == 'Discount')
                            @continue
                        @endif

                        <tr>
                            <td style="text-align: center">{{ $payment->payment_mode->name ?? 'N/A' }}</td>
                            <td style="text-align: center;">
                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d-m-Y') }}</td>
                            <td>{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #fff; font-weight:bold;">
                        <td colspan="2" style="text-align: right;">Total Paid:</td>
                        {{-- Show calculated Actual Paid Amount --}}
                        <td>{{ number_format($actualPaidAmount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        {{-- <div style="margin-top: 20px; text-align: center; font-style: italic; border: 1px solid #eee; padding: 5px;">
            Amount in words: {{ amountToWords($netPayable > 0 ? $netPayable : $grossTotal) }} Only.
        </div> --}}
        <div class="summary-container">
            <div class="qr-section" style="width: auto; flex: 0 0 auto;">
                @if (setting('hospital_qr'))
                    <img src="{{ asset('img/' . setting('hospital_qr')) }}"
                        style="height: 80px; width: 80px; border: 1px solid #ddd; padding: 3px;">
                @else
                    <div
                        style="height: 80px; width: 80px;  display:flex; align-items:center; justify-content:center; margin:0 auto; color:#999; font-size:10px;">
                        No QR</div>
                @endif
                <div style="font-weight: bold; font-size: 12px; margin-top: 5px; color: #333;">Scan & Pay</div>
            </div>

            <div style="flex: 0 0 45%; margin-left: auto;">
                <table class="summary-table">
                    <tr>
                        <td>Total Bill Amount:</td>
                        <td style="font-weight: bold;">{{ number_format($Totalcharge, 2) }}</td>
                    </tr>

                    {{-- DISCOUNT SECTION: Display here if discount exists --}}
                    @if ($totalDiscountAmount > 0)
                        <tr>
                            <td>Discount:</td>
                            <td>- {{ number_format($totalDiscountAmount, 2) }}</td>
                        </tr>
                    @endif

                    {{-- Original Tax Logic (Uncomment if needed) --}}
                    {{-- <tr>
                        <td>Tax ({{ $tax }}%):</td>
                        <td>+ {{ number_format(($grossTotal * $tax) / 100, 2) }}</td>
                    </tr> --}}

                    {{-- <tr style="background: #fff;">
                        <td><strong>Gross Total:</strong></td>
                        <td><strong>{{ number_format($grossTotal, 2) }}</strong></td>
                    </tr> --}}
                    <tr>
                        <td>Paid Amount:</td>
                        {{-- Show Actual Paid --}}
                        <td>- {{ number_format($actualPaidAmount, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td style="border:none;">Net Payable (Unpaid):</td>
                        <td style="border:none; font-size: 14px;">{{ number_format($netPayable, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        </div>

                </div>

@if(setting('show_footer_ipd_bill', 'yes') == 'yes')
        <div class="small-points">
            <div>* E & O.E. </div>
            <div>* This is a System Generated Receipt. </div>
            <div>* This receipt is not Valid for Medico-legal Purpose. </div>
            <div>* All Legal Claims are Subject to {{ setting('city', 'Muzaffarpur') }} Jurisdiction Only. </div>
        </div>

        <div class="footer-split qr-hide-block" style="padding-top:{{ $m['footer']['top'] ?? 0 }}px; padding-left:{{ $m['footer']['left'] ?? 0 }}px; padding-right:{{ $m['footer']['right'] ?? 0 }}px; padding-bottom:{{ $m['footer']['bottom'] ?? 0 }}px; box-sizing: border-box;" style="justify-content: flex-end;" >
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
            {{-- <div style="display: {{ setting('ipd_footer_image') ? 'none' : 'block' }} !important; font-weight:bold; color: {!! setting('theme_color', '#e91e63') !!};">
                For Emergency: +91 {{ setting('phone') }}
            </div> --}}
            <div class="thank-you-msg"
                style="margin-top: 2px; font-size: 16px; color: #000000; font-family: 'Monotype Corsiva', cursive; font-style: italic;">
                Thank you for Payment. Get Well Soon!
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