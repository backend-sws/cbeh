<!DOCTYPE html>
<html>

<head>
    <title>Pathology Bill - {{ $bill->bill_no }}</title>
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
            border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!};
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        /* --- Info Tables --- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
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

        /* --- Items Table --- */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        .items-table th {
            background: #c2bebe;
            text-align: center;
            font-weight: bold;
            color: #1e293b;
        }

        .items-table td:last-child {
            text-align: right;
        }

        /* --- Summary Table --- */
        .summary-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
            margin-top: 15px;
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
            font-size: 13px;
        }

        .paid-row {
            color: #166534;
            font-weight: bold;
        }

        .due-row {
            color: #dc2626;
            font-weight: bold;
        }

        /* --- Footer Styles --- */
        .footer-split {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .qr-section {
            width: 35%;
            text-align: center;
        }

        .signature-section {
            width: 60%;
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

            .page-break {
                page-break-after: always;
            }

            .page-container {
                height: 100vh;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>

    <button onclick="window.print()" class="print-btn">Print Bill</button>

    @php
        // Break tests into chunks of 6 for pagination
        $chunks = $tests->chunk(6);
        $totalChunks = $chunks->count();
        $m = json_decode(setting('pdf_margins_pathology_bill', '{}'), true) ?? [];
    @endphp

    @foreach ($chunks as $pageIndex => $chunk)
        <div class="page-container {{ !$loop->last ? 'page-break' : '' }}">

            <div class="content-wrap">

                <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
                @if(setting('show_header_pathology_bill', 'yes') == 'yes')
                
            @if(setting('pathology_header_image'))
                <img src="{{ asset('img/' . setting('pathology_header_image')) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
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
                                {{ setting('hospital_name', 'Hospital Name') }}
                            </div>
                            <div style="font-size:12px; color:#555;">
                                {{ setting('tagline', 'Tagline') }}<br>
                                {{ setting('address', 'Hospital Address Here') }}<br>
                                Phone: +91 {{ setting('phone', '0000000000') }}<br>
                                Email: {{ setting('email', 'email@example.com') }} | Website:
                                {{ setting('website', 'www.example.com') }}
                            </div>
                        </td>
                    </tr>
                </table>
            @endif
                @else
                <div style="height: {{ setting('spacer_pathology_bill', '180') }}px;"></div>
                @endif
                </div>

                <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
                <div
                    style="text-align:center; font-weight:bold; font-size:16px; text-decoration: underline; margin-top:5px; color: #333;">
                    DEPARTMENT OF PATHOLOGY
                </div>
                <div
                    style="text-align:center; font-weight:bold; font-size:14px; text-decoration:underline; color: #555; margin-bottom: 10px;">
                    INVOICE
                </div>
                </div>

                <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
                <div
                    style="font-size: 14px; font-weight: bold; color: {!! setting('theme_color', '#e91e63') !!};  margin-bottom: 5px;">
                    Patient Details (Page {{ $loop->iteration }} of {{ $totalChunks }})
                </div>
                        @include('components.pdf-patient-info', [
            'docKey' => 'pathology_bill',
            'patient' => $bill->patient ?? null,
            'uhid' => $bill->patient->patient_unique_id ?? null,
            'ipd_no' => $bill->ipd_no ?? ($bill->opd_no ?? null),
            'invoice_no' => $bill->invoice_no ?? null,
            'appointment_date' => $bill->created_at ? \Carbon\Carbon::parse($bill->created_at)->format('d-m-Y h:i A') : null,
            'doctor_name' => $bill->doctor->name ?? null,
            'department_name' => $bill->doctor->department->name ?? null,
        ])
                </div>

                <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
                <div
                    style="font-size: 14px; font-weight: bold; color: {!! setting('theme_color', '#e91e63') !!};  margin-top: 10px;">
                    Test Details
                </div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th width="5%" style="text-align: center;">#</th>
                            <th width="45%">Test Name</th>
                            <th width="20%" style="text-align: center;">Report Time</th>
                            <th width="15%" style="text-align: center;">Tax %</th>
                            <th width="15%" style="text-align: right;">Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chunk as $index => $item)
                            <tr>
                                <td style="text-align: center;">{{ $pageIndex * 6 + $loop->iteration }}</td>
                                <td>
                                    <span style="font-weight: bold;">{{ $item->test->test_name }}</span>
                                    <br><small style="color: #666;">{{ $item->test->short_name }}</small>
                                </td>
                                <td style="text-align: center;">
                                    {{ $item->test->report_days ? $item->test->report_days . ' Days' : 'Same Day' }}
                                </td>
                                <td style="text-align: center;">
                                    {{ $item->tax > 0 ? $item->tax . '%' : '-' }}
                                </td>
                                <td style="text-align: right;">{{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($loop->last)
                    <div style="margin-top: 10px;">
                        <table class="summary-table">
                            {{-- Determine Subtotal logic if needed, usually passed or calc --}}
                            {{-- <tr><td>Sub Total:</td><td>₹ {{ number_format($subTotal, 2) }}</td></tr> --}}

                            @if ($bill->tax > 0)
                                <tr>
                                    <td>Total Tax:</td>
                                    <td>+ ₹ {{ number_format($bill->tax, 2) }}</td>
                                </tr>
                            @endif
                            @if ($bill->discount > 0)
                                <tr>
                                    <td>Discount:</td>
                                    <td>- ₹ {{ number_format($bill->discount, 2) }}</td>
                                </tr>
                            @endif

                            <tr class="total-row">
                                <td style="border: none;">Total Bill Amount:</td>
                                <td style="border: none;">₹ {{ number_format($bill->amount, 2) }}</td>
                            </tr>

                            @if ($bill->paid_amount > 0)
                                <tr class="paid-row">
                                    <td style="border: none;">Paid Amount:</td>
                                    <td style="border: none;">₹ {{ number_format($bill->paid_amount, 2) }}</td>
                                </tr>
                            @endif

                            @if ($bill->due_amount > 0)
                                <tr class="due-row">
                                    <td style="border: none;">Due Balance:</td>
                                    <td style="border: none;">₹ {{ number_format($bill->due_amount, 2) }}</td>
                                </tr>
                            @else
                                <tr class="paid-row">
                                    <td style="border: none;">Status:</td>
                                    <td style="border: none;">PAID</td>
                                </tr>
                            @endif
                        </table>
                    </div>

                    @if ($bill->notes)
                        <div
                            style="background:#f9f9f9; padding:8px; border-left:3px solid #ccc; margin-top:10px; font-style:italic;">
                            <strong>Note:</strong> {{ $bill->notes }}
                        </div>
                    @endif
                @else
                    <div style="text-align: right; margin-top: 20px; font-style: italic; color: #666;">
                        Continued on next page...
                    </div>
                @endif
                </div>

            </div>

            @if(setting('show_footer_pathology_bill', 'yes') == 'yes')
            <div class="page-footer" style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 0 }}px;">
                <div class="footer-split">
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
                        <table width="100%">
                            <tr>
                                <td style="width:50%; text-align:center; border:none; visibility: hidden;">
                                    <div style="height:30px;"></div>
                                    <div style="border-top:1px solid #333; width:80%; margin:0 auto;"></div>
                                    <div style="font-size:11px; margin-top:3px;">Checked By</div>
                                </td>
                                <td style="width:50%; text-align:center; border:none;">
                                    <div style="height:30px;"></div>
                                    <div style="border-top:1px solid #333; width:80%; margin:0 auto;"></div>
                                    <div style="font-size:11px; margin-top:3px;">Authorized Signatory</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="qr-hide-block"
                    style="padding-top:5px;  font-size:11px; text-align:center; color:#555;">
                    <div class="thank-you-msg"
                        style="margin-top: 2px; font-size: 20px; color: #000000; font-family: 'Monotype Corsiva', cursive; font-style: italic;">
                        Thank you for Payment. Visit Again!
                    </div>
                </div>
            </div>

        </div>
    @endif
    @endforeach

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

        <div style="height: 130px;"></div>
        @if(setting('pathology_footer_image'))
            <div style="position:fixed; bottom:0; left:0; width:100%; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . setting('pathology_footer_image')) }}" style="width:100%; width:100%; height:auto; display:block;" alt="Footer">
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
