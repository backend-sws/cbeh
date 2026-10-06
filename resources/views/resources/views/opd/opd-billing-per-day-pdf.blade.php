<!DOCTYPE html>
<html>
<head>
    <title>Daily OPD Bill - {{ $opd_patient->opd_no }} ({{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }})</title>
    <style>
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

        .header-table {
            width: 100%;
            border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!};
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

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
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: {!! setting('theme_color', '#e91e63') !!};
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            z-index: 999;
        }

        @media print {
            @page {
                size: A4;
                margin: 5mm;
            }
            body, .bill-container {
                width: 100%;
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
    @php $m = json_decode(setting('pdf_margins_opd_day_bill', '{}'), true) ?? []; @endphp

    <button onclick="window.print()" class="print-btn">Print Daily Bill</button>

    @php
        $discountPayments = $payments->filter(function ($p) {
            return optional($p->payment_mode)->name == 'Discount';
        });
        $totalDiscountAmount = $discountPayments->sum('amount');
        $actualPaidAmount = $TotalPayment - $totalDiscountAmount;
    @endphp

    <div class="bill-container">
        @if(setting('show_header_opd_day_bill', 'yes') == 'yes')
            @if(setting('opd_header_image') || setting('ipd_header_image'))
                <table width="100%" style="margin:0; padding:0; border:none; border-spacing:0;">
                    <tr>
                        <td style="padding-top:{{ $m['header']['top'] ?? 0 }}px; padding-left:{{ $m['header']['left'] ?? 0 }}px; padding-right:{{ $m['header']['right'] ?? 0 }}px; padding-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
                            <img src="{{ asset('img/' . (setting('opd_header_image') ?: setting('ipd_header_image'))) }}" alt="Header" style="width: 100%; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
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
            <div style="height: {{ setting('spacer_opd_day_bill', '180') }}px;"></div>
        @endif

        <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
            <div style="text-align:center; font-weight:bold; font-size:16px; margin-top:5px; text-decoration: underline; color: #333;">
                DAILY OPD BILL ({{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }})
            </div>
        </div>

        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
            <div class="section-title">Patient Information</div>
            @include('components.pdf-patient-info', [
                'docKey' => 'opd_day_bill',
                'patient' => $opd_patient->patient ?? null,
                'uhid' => $patient_uhid,
                'ipd_no' => $opd_patient->opd_no ?? null,
                'invoice_no' => $invoice ?? null,
                'appointment_date' => $opd_patient->appointment_date ? \Carbon\Carbon::parse($opd_patient->appointment_date)->format('d-m-Y') : now()->format('d-m-Y'),
                'doa' => $opd_patient->appointment_date ? \Carbon\Carbon::parse($opd_patient->appointment_date)->format('d-m-Y') : null,
                'dod' => 'OPD Consultation',
                'bed_ward' => 'OPD Department',
                'doctor_name' => $opd_patient->doctor->name ?? null,
                'department_name' => $opd_patient->doctor->department->name ?? 'General Medicine',
            ])
        </div>

        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
            <div class="section-title">Billing Details for {{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}</div>
            <table class="charges-table">
                <thead>
                    <tr>
                        <th width="8%" style="text-align: center;">S.No</th>
                        <th width="22%" style="text-align: left;">Charge Type</th>
                        <th width="32%" style="text-align: left;">Particulars / Service</th>
                        <th width="14%" style="text-align: center;">Rate</th>
                        <th width="8%" style="text-align: center;">Qty</th>
                        <th width="16%" style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowIdx = 1; @endphp
                    @if($includeConsultantCharge && (float)$opd_patient->standard_charge > 0)
                    <tr>
                        <td style="text-align: center;">{{ $rowIdx++ }}</td>
                        <td style="text-align: left;">Consultation</td>
                        <td style="text-align: left;">
                            Standard Consultation Fee (Dr. {{ $opd_patient->doctor->name ?? 'Consultant' }})
                        </td>
                        <td style="text-align: center;">{{ number_format($opd_patient->standard_charge, 2) }}</td>
                        <td style="text-align: center;">1</td>
                        <td style="text-align: right;">{{ number_format($opd_patient->standard_charge, 2) }}</td>
                    </tr>
                    @endif

                    @forelse($charges as $charge)
                    <tr>
                        <td style="text-align: center;">{{ $rowIdx++ }}</td>
                        <td style="text-align: left;">
                            {{ $charge->chargeType->charge_type ?? ($charge->chargeType->name ?? ($charge->chargeCategory->chargeType->charge_type ?? '-')) }}
                        </td>
                        <td style="text-align: left;">
                            {{ $charge->chargeCategory->charge_category ?? 'Hospital Charge' }} - {{ $charge->code ?? 'N/A' }}
                        </td>
                        <td style="text-align: center;">{{ number_format($charge->applied_charge, 2) }}</td>
                        <td style="text-align: center;">{{ $charge->quantity }}</td>
                        <td style="text-align: right;">{{ number_format($charge->total, 2) }}</td>
                    </tr>
                    @empty
                        @if(!$includeConsultantCharge)
                        <tr>
                            <td colspan="6" style="text-align:center;">No charges found for this date.</td>
                        </tr>
                        @endif
                    @endforelse

                    <tr style="background-color: #fff; font-weight:bold;">
                        <td colspan="5" style="text-align: right;">Total Amount:</td>
                        <td style="text-align: right;">{{ number_format($Totalcharge, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            @if(count($payments) > 0 || ($includeConsultantCharge && $initialPayment > 0))
            <div class="section-title" style="margin-top: 20px;">Payments Received on {{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}</div>
            <table class="charges-table">
                <thead>
                    <tr>
                        <th width="50%" style="text-align: left;">Payment Mode / Reference</th>
                        <th width="50%" style="text-align: right;">Paid Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @if($includeConsultantCharge && (float)$initialPayment > 0)
                    <tr>
                        <td style="text-align: left;">{{ $opd_patient->payment_mode ?? 'Cash' }} (OPD Consultation Registration)</td>
                        <td style="text-align: right;">{{ number_format($initialPayment, 2) }}</td>
                    </tr>
                    @endif

                    @foreach ($payments as $payment)
                        @if (optional($payment->payment_mode)->name == 'Discount')
                            @continue
                        @endif
                        <tr>
                            <td style="text-align: left;">{{ $payment->payment_mode->name ?? 'Cash' }} {{ $payment->notes ? '('.$payment->notes.')' : '' }}</td>
                            <td style="text-align: right;">{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    @endforeach

                    <tr style="background-color: #fff; font-weight:bold;">
                        <td style="text-align: right;">Total Paid Today:</td>
                        <td style="text-align: right;">{{ number_format($actualPaidAmount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
            @endif

            <div class="summary-container">
                <div class="qr-section" style="width: auto; flex: 0 0 auto;">
                    @if (setting('hospital_qr'))
                        <img src="{{ asset('img/' . setting('hospital_qr')) }}"
                            style="height: 80px; width: 80px; border: 1px solid #ddd; padding: 3px;">
                    @else
                        <div style="height: 80px; width: 80px; display:flex; align-items:center; justify-content:center; margin:0 auto; color:#999; font-size:10px;">
                            No QR
                        </div>
                    @endif
                    <div style="font-weight: bold; font-size: 12px; margin-top: 5px; color: #333;">Scan & Pay</div>
                </div>

                <div style="flex: 0 0 45%; margin-left: auto;">
                    <table class="summary-table">
                        <tr>
                            <td>Today's Charges:</td>
                            <td style="font-weight: bold;">{{ number_format($Totalcharge, 2) }}</td>
                        </tr>
                        @if ($totalDiscountAmount > 0)
                        <tr>
                            <td>Discount Today:</td>
                            <td>- {{ number_format($totalDiscountAmount, 2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Paid Today:</td>
                            <td>- {{ number_format($actualPaidAmount, 2) }}</td>
                        </tr>
                        <tr class="total-row">
                            <td style="border:none;">Day Net Payable:</td>
                            <td style="border:none; font-size: 14px;">{{ number_format($netPayable, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        @if(setting('show_footer_opd_day_bill', 'yes') == 'yes')
        <div class="small-points" style="margin-top: 30px;">
            <div>* E & O.E. </div>
            <div>* This is a System Generated Receipt. </div>
            <div>* This receipt is not Valid for Medico-legal Purpose. </div>
            <div>* All Legal Claims are Subject to {{ setting('city', 'Muzaffarpur') }} Jurisdiction Only. </div>
        </div>

        <div class="footer-split qr-hide-block" style="padding-top:{{ $m['footer']['top'] ?? 0 }}px; padding-left:{{ $m['footer']['left'] ?? 0 }}px; padding-right:{{ $m['footer']['right'] ?? 0 }}px; padding-bottom:{{ $m['footer']['bottom'] ?? 0 }}px; box-sizing: border-box; margin-top: 20px;">
            <table class="qr-hide-block" width="100%">
                <tr>
                    <td style="width:60%; border:none;"></td>
                    <td style="width:40%; text-align:center; border:none;">
                        <div style="height:30px;"></div>
                        <div style="border-top:1px solid #333; width:80%; margin:0 auto;"></div>
                        <div style="font-size:11px; font-weight:bold; margin-top:3px;">Authorized Signature</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="default-text-footer" style="padding-top:5px; margin-top: 10px; font-size:11px; text-align:center; color:#555;">
            <div class="thank-you-msg" style="margin-top: 2px; font-size: 16px; color: #000000; font-family: 'Monotype Corsiva', cursive; font-style: italic;">
                Thank you for Visiting. Get Well Soon!
            </div>
        </div>
        @endif

        <div class="footer-spacer" style="height: 100px;"></div>
        @if(setting('opd_footer_image') || setting('ipd_footer_image'))
            <div style="position:fixed; bottom:0; left:0; right:0; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . (setting('opd_footer_image') ?: setting('ipd_footer_image'))) }}" style="width:100%; height:auto; display:block;" alt="Footer">
            </div>
        @endif
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
