<!DOCTYPE html>
<html>
<head>
    <title>IPD Pathology Statement - {{ $ipdPatient->patient->name }}</title>
    <style>
        /* --- Base Settings --- */
        html, body {
            margin: 0; padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            background: #f3f4f6; color: #222; line-height: 1.3;
        }

        /* --- Page Layout --- */
        .page-container {
            width: 750px; margin: 0 auto; background: #fff;
            min-height: 100vh; padding: 20px 40px;
            box-sizing: border-box; display: flex; flex-direction: column;
            position: relative;
        }

        .content-wrap { flex: 1; }

        /* --- Header Styles --- */
        .header-table {
            width: 100%; border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!};
            padding-bottom: 10px; margin-bottom: 15px;
        }

        /* --- Info Tables --- */
        .info-table {
            width: 100%; border-collapse: collapse; margin-bottom: 10px; border: 1px solid #333;
        }
        .info-table td {
            padding: 5px 8px; vertical-align: top; border: 1px solid #333; width: 25%;
        }
        .info-table td:nth-child(odd) {
            background-color: #f9f9f9; font-weight: bold; color: #444;
        }

        /* --- Items Table --- */
        .items-table {
            width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 11px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ccc; padding: 6px; text-align: center;
        }
        .items-table th {
            background: #c2bebe; text-align: center; font-weight: bold; color: #1e293b;
        }
        .items-table td.amount-col { text-align: center; }

        /* --- Section Header --- */
        .section-header {
            font-size: 13px; font-weight: bold; 
            color: {!! setting('theme_color', '#e91e63') !!}; 
             margin-top: 20px; margin-bottom: 5px;
            text-transform: uppercase;
        }

        /* --- Summary Table --- */
        .summary-table {
            width: 45%; margin-left: auto; border-collapse: collapse; margin-top: 15px;
        }
        .summary-table td {
            padding: 5px; border-bottom: 1px solid #eee; text-align: right;
        }
        .total-row {
            font-weight: bold; background-color: {!! setting('theme_color', '#e91e63') !!}; color: white; font-size: 13px;
        }

        /* --- Footer --- */
        .footer-split {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px;
        }
        .print-btn {
            position: fixed; top: 20px; right: 20px; padding: 10px 20px;
            background-color: {!! setting('theme_color', '#e91e63') !!}; color: white;
            border: none; border-radius: 4px; cursor: pointer; z-index: 2000;
        }

        @media print {
            body { background: #fff; }
            .print-btn { display: none; }
            .page-container { height: auto; min-height: auto; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">Print Statement</button>

    @php $m = json_decode(setting('pdf_margins_pathology_statement', '{}'), true) ?? []; @endphp

    <div class="page-container">
        <div class="content-wrap">

            <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
            @if(setting('show_header_pathology_statement', 'yes') == 'yes')
            
            @if(setting('pathology_header_image'))
                <img src="{{ asset('img/' . setting('pathology_header_image')) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
            @else
<table class="header-table">
                <tr>
                    <td style="width:90px; vertical-align:top;">
                        <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" style="height:70px;">
                    </td>
                    <td style="text-align:right; vertical-align:top;">
                        <div style="font-size:18px; font-weight:bold; color:{!! setting('theme_color', '#e91e63') !!}; margin-bottom:5px;">
                            {{ setting('hospital_name', 'Hospital Name') }}
                        </div>
                        <div style="font-size:12px; color:#555;">
                              {{ setting('tagline', 'Tagline') }}<br>
                            {{ setting('address', 'Hospital Address') }}<br>
                            Phone: +91 {{ setting('phone', '0000000000') }}
                        </div>
                    </td>
                </tr>
            </table>
            @endif
            @else
            <div style="height: {{ setting('spacer_pathology_statement', '180') }}px;"></div>
            @endif
            </div>

            <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
            <div style="text-align:center; font-weight:bold; font-size:16px; text-decoration: underline; margin-top:5px; color: #333;">
               DEPARTMENT OF PATHOLOGY
            </div>
            <div style="text-align:center; font-weight:bold; font-size:14px;  margin-top:5px; color: #333;">
                CONSOLIDATED STATEMENT
            </div>
            </div>


            <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
            <div class="section-header">Patient Details</div>
                    @include('components.pdf-patient-info', [
            'docKey' => 'pathology_statement',
            'patient' => $testRequest->patient ?? null,
            'uhid' => $testRequest->patient->patient_unique_id ?? null,
            'ipd_no' => $testRequest->ipd_no ?? ($testRequest->opd_no ?? null),
            'invoice_no' => $testRequest->invoice_no ?? null,
            'appointment_date' => $testRequest->created_at ? \Carbon\Carbon::parse($testRequest->created_at)->format('d-m-Y h:i A') : null,
            'doctor_name' => $testRequest->doctor->name ?? null,
            'department_name' => $testRequest->doctor->department->name ?? null,
        ])
            </div>

            <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
            <div class="section-header">Test Charges Summary</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th >Date</th>
                        <th >Bill No</th>
                        {{-- <th >Test</th> --}}
                        <th >Amount (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bills as $bill)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($bill->bill_date)->format('d-M-Y') }}</td>
                        <td>{{ $bill->bill_no }}</td>
                        {{-- <td>
                            <ul style="margin: 0; padding-left: 15px;">
                                @foreach($bill->tests as $testItem)
                                    <li>{{ $testItem->test->test_name ?? 'Unknown' }}</li>
                                @endforeach
                            </ul>
                        </td> --}}
                        <td class="amount-col">{{ number_format($bill->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: bold; background: #f0f0f0;">Total Billed Amount:</td>
                        <td class="amount-col" style="font-weight: bold; background: #f0f0f0;">₹{{ number_format($totalBill, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="section-header" style="margin-top: 25px;">Payment History</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th width="15%">Date</th>
                        <th width="15%">Receipt No</th>
                        <th width="15%">Mode</th>
                        <th width="40%">Particulars / Note</th>
                        <th width="15%">Paid (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $txn)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($txn->payment_date)->format('d-M-Y') }}</td>
                        <td>#{{ $txn->id }}</td>
                        <td>{{ $txn->payment_mode }}</td>
                        <td>{{ $txn->note ?? '-' }}</td>
                        <td class="amount-col">{{ number_format($txn->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #999;">No payments recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold; background: #f0f0f0;">Total Received:</td>
                        <td class="amount-col" style="font-weight: bold; background: #f0f0f0;">₹{{ number_format($totalPaid, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <table class="summary-table">
                <tr>
                    <td>Total Charges:</td>
                    <td>₹ {{ number_format($totalBill, 2) }}</td>
                </tr>
                <tr>
                    <td> Total Paid:</td>
                    <td> ₹ {{ number_format($totalPaid, 2) }}</td>
                </tr>
                <tr >
                    <td style="border: none;">
                        @if($balance > 0) NET DUE BALANCE:
                        @else ADVANCE BALANCE:
                        @endif
                    </td>
                    <td style="border: none;">
                        ₹ {{ number_format(abs($balance), 2) }}
                    </td>
                </tr>
                
            </table>
            </div>

              <div class="page-footer" style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 0 }}px;">
            @if(setting('show_footer_pathology_statement', 'yes') == 'yes')
            <div class="footer-split qr-hide-block">
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

            <div style="padding-top:5px; margin-top: 10px;  font-size:11px; text-align:center; color:#555;">
                <div style="margin-top: 2px; font-size: 18px; color: #000000; font-family: 'Monotype Corsiva', cursive; font-style: italic;">
                    Get Well Soon!
                </div>
            </div>
        </div>        </div>

    </div>

            @endif
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