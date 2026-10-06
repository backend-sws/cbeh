<!DOCTYPE html>
<html>

<head>
    <title>IPD Pharmacy Statement - {{ $ipdPatient->ipd_no }}</title>
    <style>
        /* --- Base Settings --- */
        html,
        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            background: #f3f4f6;
            color: #222;
            line-height: 1.3;
        }

        /* --- Page Layout --- */
        .page-container {
            width: 750px;
            margin: 0 auto;
            background: #fff;
            min-height: 100vh;
            padding: 20px 40px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .content-wrap {
            flex: 1;
        }

        /* --- Header Styles --- */
        .header-table {
            width: 100%;
            border-bottom: 2px solid
                {!! setting('theme_color', '#e91e63') !!}
            ;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        /* --- Info Tables --- */
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
        }

        /* --- Data/Charges Table --- */
        .charges-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 20px;
            font-size: 12px;
        }

        .charges-table th,
        .charges-table td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .charges-table th {
            background: #e0e0e0;
            text-align: center;
            font-weight: bold;
            color: #333;
        }

        /* --- Section Titles --- */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color:
                {!! setting('theme_color', '#e91e63') !!}
            ;
            
            margin-bottom: 5px;
            margin-top: 15px;
            text-transform: uppercase;
        }

        /* --- Summary Table --- */
        .summary-table {
            width: 50%;
            margin-left: auto;
            border-collapse: collapse;
            margin-top: 15px;
            border: 1px solid #ccc;
        }

        .summary-table td {
            padding: 6px;
            border-bottom: 1px solid #eee;
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color:
                {!! setting('theme_color', '#e91e63') !!}
            ;
            color: white;
            font-size: 13px;
        }

        .discount-row {
            color: #d32f2f;
            font-style: italic;
        }

        /* --- Footer Styles --- */
        .footer-split {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
            padding-top: 10px;
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

        /* Print Button */
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color:
                {!! setting('theme_color', '#e91e63') !!}
            ;
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

            .print-btn {
                display: none;
            }

            .page-container {
                margin: 0;
                border: none;
                width: 100%;
                padding: 0 20px;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_pharmacy_statement', '{}'), true) ?? []; @endphp

    <button onclick="window.print()" class="print-btn">Print Statement</button>

    <div class="page-container">

        <div class="content-wrap">
            <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">

            @if(setting('show_header_pharmacy_statement', 'yes') == 'yes')
            
            @if(setting('pharmacy_header_image'))
                <img src="{{ asset('img/' . setting('pharmacy_header_image')) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
            @else
<table class="header-table">
                <tr>
                    <td style="width:90px; vertical-align:top;">
                        <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Logo"
                            style="height:70px;">
                    </td>
                    <td style="text-align:right; vertical-align:top;">
                        <div
                            style="font-size:18px; font-weight:bold; color:{!! setting('theme_color', '#e91e63') !!}; margin-bottom:5px;">
                            {{ setting('medical_name') ?: setting('hospital_name', 'Hospital Name') }}
                        </div>
                        <div style="font-size:12px; color:#555;">
                            @if(setting('medical_lic_no_1') || setting('medical_lic_no_2'))
                                <p style="margin:0;">
                                    @if(setting('medical_lic_no_1')) DL No: {{ setting('medical_lic_no_1') }} @endif
                                    @if(setting('medical_lic_no_2')) {{ setting('medical_lic_no_1') ? ',' : 'Lic No:' }} {{ setting('medical_lic_no_2') }} @endif
                                </p>
                            @endif
                            <!-- {{ setting('tagline', 'Tagline') }}<br> -->
                            {{ setting('address', 'Address Here') }}<br>
                            Phone: {{ setting('phone') }}
                        </div>
                    </td>
                </tr>
            </table>
            @endif
            @else
            <div style="height: {{ setting('spacer_pharmacy_statement', '180') }}px;"></div>
            @endif
    </div>

            <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">


            <div
                style="text-align:center; font-weight:bold; font-size:16px; margin-top:5px; text-decoration: underline; color: #333;">
                DEPARTMENT OF PHARMACY
            </div>


                </div>
            <div
                style="text-align:center; font-weight:bold; font-size:14px; text-decoration:underline; color: #555; margin-bottom: 15px;">
                CONSOLIDATED STATEMENT (BILL WISE)
            </div>

            <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">


            <div class="section-title">Patient Details</div>
                    @include('components.pdf-patient-info', [
            'docKey' => 'pharmacy_statement',
            'patient' => $patient ?? null,
            'uhid' => $patient->patient_unique_id ?? null,
        ])



                </div>

            <div class="section-title">1. Pharmacy Bill Summary</div>
        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
            <table class="charges-table">
                <thead>
                    <tr>
                        <th width="20%">Date</th>
                        <th width="30%">Bill Number</th>
                        <th width="25%">Description</th>
                        <th width="25%" style="text-align: right;">Bill Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ipdPatient->medicineBills as $bill)
                        <tr>
                            <td style="text-align: center;">{{ \Carbon\Carbon::parse($bill->bill_date)->format('d-m-Y') }}
                            </td>
                            <td style="text-align: center;">{{ $bill->bill_number }}</td>
                            <td style="text-align: center;">Pharmacy Charges</td>
                            <td style="text-align: right;">₹ {{ number_format($bill->net_amount, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr style="background: #f9f9f9; font-weight: bold;">
                        <td colspan="3" style="text-align: right; border-right: none;">Total Billed Amount:</td>
                        <td style="text-align: right; border-left: none;">₹ {{ number_format($totalBill, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

            <div class="section-title">2. Payment History</div>
            <table class="charges-table">
                <thead>
                    <tr>
                        <th width="15%">Date</th>
                        <th width="20%">Receipt No</th>
                        <th style="text-align: center;">Payment Mode</th>
                        <th width="30%" style="text-align: left;">Note</th>
                        <th width="15%" style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actualPayments as $pay)
                        <tr>
                            <td style="text-align: center;">{{ \Carbon\Carbon::parse($pay->payment_date)->format('d-m-Y') }}
                            </td>
                            <td style="text-align: center;">#{{ $pay->id }}</td>
                            <td style="text-align: center;">{{ $pay->payment_mode }}</td>
                            <td>{{ $pay->note ?? '-' }}</td>
                            <td style="text-align: right;">₹ {{ number_format($pay->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #777;">No payment records found.</td>
                        </tr>
                    @endforelse
                    <tr style="background: #f9f9f9; font-weight: bold;">
                        <td colspan="4" style="text-align: right; border-right: none;">Total Paid (Cash/Online):</td>
                        <td style="text-align: right; border-left: none;">₹ {{ number_format($totalPaid, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            @if($discountRecords->count() > 0)
                <div class="section-title">3. Discount Details</div>
                <table class="charges-table">
                    <thead>
                        <tr>
                            <th width="15%">Date</th>
                            <th width="20%">Ref No</th>
                            <th style="text-align: center;">Type</th>
                            <th width="30%" style="text-align: left;">Note</th>
                            <th width="15%" style="text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($discountRecords as $disc)
                            <tr>
                                <td style="text-align: center;">
                                    {{ \Carbon\Carbon::parse($disc->payment_date)->format('d-m-Y') }}</td>
                                <td style="text-align: center;">#{{ $disc->id }}</td>
                                <td style="text-align: center; font-weight: bold; color: #d32f2f;">DISCOUNT</td>
                                <td>{{ $disc->note ?? 'Discount applied to account' }}</td>
                                <td style="text-align: right; color: #d32f2f;">₹ {{ number_format($disc->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <table class="summary-table">
                <tr>
                    <td>Total Bill Amount:</td>
                    <td>₹ {{ number_format($totalBill, 2) }}</td>
                </tr>
                <tr class="discount-row">
                    <td>Total Discount Given:</td>
                    <td>- ₹ {{ number_format($totalDiscount, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Amount Paid:</td>
                    <td>₹ {{ number_format($totalPaid, 2) }}</td>
                </tr>

                @if($balance > 0)
                    <tr class="total-row">
                        <td style="border:none;">NET PAYABLE DUE:</td>
                        <td style="border:none;">₹ {{ number_format($balance, 2) }}</td>
                    </tr>
                @else
                    <tr class="total-row" style="background: #f9f9f9; color: #000; border: 1px solid #ccc;">
                        <td style="border:none;">ADVANCE BALANCE:</td>
                        <td style="border:none;">₹ {{ number_format(abs($balance), 2) }}</td>
                    </tr>
                @endif
            </table>

        </div>

        @if(setting('show_footer_pharmacy_statement', 'yes') == 'yes')
        <div class="page-footer" style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 0 }}px;">
            <div class="footer-split qr-hide-block">
                <div class="qr-section">
                    @if(setting('hospital_qr'))
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
                        <div>* This is a System Generated Statement. </div>
                        <div>* This receipt is not Valid for Medico-legal Purpose. </div>
                        <div>* All Legal Claims are Subject to {{ setting('city', 'Muzaffarpur') }} Jurisdiction Only. </div>
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

            <div
                style="padding-top:5px; margin-top: 10px;  font-size:11px; text-align:center; color:#555;">
                <div
                    style="margin-top: 2px; font-size: 18px; color: #000000; font-family: 'Monotype Corsiva', cursive; font-style: italic;">
                    Get Well Soon!
                </div>
            </div>
        </div>

    </div>

            @endif
    <script>
        window.onload = function () {
            window.print();
        };
    </script>


        <div style="height: 130px;"></div>
        @if(setting('pharmacy_footer_image'))
            <div style="position:fixed; bottom:0; left:0; width:100%; text-align:center; z-index: 100;">
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
</body>

</html>