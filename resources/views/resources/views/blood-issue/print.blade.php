<!DOCTYPE html>
<html>

<head>
    <title>Blood Issue Bill - {{ $issue->issue_no }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            background: #fff;
            color: #222;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }

        .bill-container {
            width: 700px;
            margin: 10px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            position: relative;
            /* ✅ Removed min-height:100vh */
        }

        .header {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e91e63;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .logo {
            width: 70px;
            margin-right: 15px;
        }

        .hospital-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: #e91e63;
            margin-bottom: 2px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            color: #333;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
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
            text-align: right;
        }

        .charges-table th {
            background: #f8f8f8;
            text-align: left;
        }

        .total-row {
            font-weight: bold;
            background: #f1f1f1;
        }

        .amount-in-words {
            padding: 8px;
            border: 1px solid #ccc;
            border-top: none;
            font-style: italic;
            text-align: center;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 10px;
            
        }

        .signature-box {
            text-align: center;
            width: 45%;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 30px;
            width: 80%;
            display: inline-block;
        }

        .print-btn {
            margin: 20px auto 0 auto;
            display: block;
            padding: 6px 15px;
            font-size: 12px;
            background-color: #e91e63;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .footer {
            text-align: center;
            padding: 10px;
            border-top: 1px solid #e91e63;
            margin-top: 20px;
            font-size: 11px;
            position: relative;
        }

        @media print {
            .print-btn {
                display: none !important;
            }

            .bill-container {
                border: none;
                padding: 10px;
                page-break-inside: avoid; /* ✅ Prevent split */
            }

            .bill-container * {
                page-break-inside: avoid;
            }

            .footer {
                position: relative !important; /* ✅ Avoid pushing to new page */
                bottom: auto !important;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                zoom: 90%; /* ✅ Shrink if content is slightly larger than 1 page */
            }
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_blood_issue', '{}'), true) ?? []; @endphp
    <div class="bill-container">

        <!-- Header Section -->
        <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">

        @if(setting('show_header_blood_issue', 'yes') == 'yes')
        
            @if(setting('opd_header_image'))
                <img src="{{ asset('img/' . setting('opd_header_image')) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
            @else
<table width="100%" style="border-bottom:2px solid #e91e63; margin-bottom:20px; padding-bottom:15px;">
            <tr>
                <td style="width:90px; vertical-align:top;">
                    <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Hospital Logo"
                        style="height:70px;">
                </td>
                <td style="text-align:right; vertical-align:top;">
                    <div style="font-size:18px; font-weight:bold; color:#e91e63; margin-bottom:5px;">
                        {{ setting('hospital_name', 'Hospital') }}
                    </div>
                    <div style="font-size:12px; color:#555;">
                        {{ setting('address', 'Address') }}<br>
                        Phone: +91 {{ setting('phone', '0000000000') }},
                        {{ setting('mobile', '0000000000') }}<br>
                        Website:
                        <a href="{{ setting('website', 'https://www.patliputranetralaya.in/') }}"
                            style="color:#e91e63; text-decoration:none;">
                            {{ setting('website', 'www.patliputranetralaya.in') }}
                        </a>
                    </div>
                </td>
            </tr>
        </table>
            @endif
        @else
        <div style="height: {{ setting('spacer_blood_issue', '180') }}px;"></div>
        @endif
    </div>

        <!-- Patient Info -->
        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">

        <div class="section-title">Patient Information</div>
                @include('components.pdf-patient-info', [
            'docKey' => 'blood_issue',
            'patient' => $bloodIssue->patient ?? null,
            'uhid' => $bloodIssue->patient->patient_unique_id ?? null,
            'doctor_name' => $bloodIssue->doctor->name ?? null,
        ])


            </div>

        <!-- Charges Table -->
        <div class="section-title">Charges</div>
        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
        <table class="charges-table">
            <tr>
                <th width="70%">Particulars</th>
                <th width="30%">Amount (₹)</th>
            </tr>
            <tr>
                <td>Unit</td>
                <td>{{ $issue->bags }}</td>
            </tr>
            <tr>
                <td>Blood Group</td>
                <td>{{ $issue->blood_group }}</td>
            </tr>
            <tr>
                <td>Tax ({{ $issue->tax ?? 0 }}%)</td>
                <td>{{ number_format(($issue->standard_charge * ($issue->tax ?? 0)) / 100, 2) }}</td>
            </tr>
            <tr>
                <td>Discount ({{ $issue->discount ?? 0 }}%)</td>
                <td>{{ number_format(($issue->standard_charge * ($issue->discount ?? 0)) / 100, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Final Amount</td>
                <td>{{ number_format($issue->amount) }}</td>
            </tr>
        </table>
        </div>

        <!-- Amount in Words -->
        <div class="amount-in-words">
            <strong>Amount in words:</strong>
            @php
                echo amountToWords($issue->amount) . ' Rupees Only';
            @endphp
        </div>

        <!-- Signature Section -->
        @if(setting('show_footer_blood_issue', 'yes') == 'yes')
        <div class="signature-section">
            <div class="signature-box">
                <div>Staff Name</div>
                <div class="signature-line"></div>
                <div>Authorized Signatory</div>
            </div>
            <div class="signature-box">
                <div>Receiver's Signature</div>
                <div class="signature-line"></div>
                <div>Patient/Guardian</div>
            </div>
        </div>

        <!-- Print Button -->
        <div class="print-btn-container">
            <button onclick="window.print()" class="print-btn">Print Bill</button>
        </div>

        <!-- Footer -->
        <div class="footer" style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 0 }}px;">
            <div class="default-text-footer" style="display: {{ setting('opd_footer_image') ? 'none' : 'block' }} !important; font-weight:bold; color:#e91e63;">
                For Emergency Services: +91{{ setting('phone', '0000000000') }},
                {{ setting('mobile', '0000000000') }}
            </div>
            <div>
                Website:
                <a href="{{ setting('website', 'https://www.patliputranetralaya.in/') }}"
                    style="color:#e91e63; text-decoration:none;">
                    {{ setting('website', 'www.patliputranetralaya.in') }}
                </a>
            </div>
            <div>
                {{ setting('address', 'Patna: Opposite SBI, Bhootnath Road, Patna-800026') }}
            </div>
        </div>
        @endif
    </div>

        <div style="height: 130px;"></div>
        @if(setting('opd_footer_image'))
            <div style="position:fixed; bottom:0; left:0; width:100%; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . setting('opd_footer_image')) }}" style="width:100%; width:100%; height:auto; display:block;" alt="Footer">
            </div>
        @endif
    
@if(setting('opd_footer_image'))
<style>
    .default-text-footer { display: none !important; }
</style>
@endif
</body>

</html>
