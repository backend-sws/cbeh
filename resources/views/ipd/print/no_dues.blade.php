<!DOCTYPE html>
<html>
<head>
    <title>No Dues Certificate - {{ $ipdPatient->ipd_no }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 13px;
            margin: 0; padding: 0;
        }
        .container {
            width: 700px;
            margin: 10px auto;
            padding: 10px;
            min-height: 95vh;
            position: relative;
             /* Helper border for screen */
        }

        /* --- HEADER STYLE --- */
        .header-table {
            width: 100%;
            border-bottom: 2px solid {!! setting('theme_color', '#000') !!};
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        /* --- PATIENT INFO BOX --- */
        /* .box {
            border: 2px solid #000;
            padding: 5px;
            margin-bottom: 20px;
        } */

        table.details { width: 100%; border-collapse: collapse; }
        table.details td { 
            border: 1px solid #000; 
            padding: 4px 6px; 
            vertical-align: top; 
        }
        .label { font-weight: bold; width: 15%; background: #eee; }
        .val { font-weight: bold; width: 35%; }

        /* --- TITLES --- */
        .main-title {
            text-align: center; font-weight: bold; font-size: 16px;
            text-decoration: underline; text-transform: uppercase; margin-bottom: 5px;
            margin-top: 10px;
        }
        .sub-title {
            text-align: center; font-weight: bold; font-size: 13px; margin-bottom: 15px;
        }
        .section-header {
            text-align: center; font-weight: bold; text-decoration: underline; margin: 20px 0 5px 0;
        }

        /* --- DATA TABLES (Bill & Payment) --- */
        table.data-table {
            width: 100%; border-collapse: collapse; margin-bottom: 10px;
        }
        table.data-table th {
            border: 1px solid #000; padding: 5px; background: #eee; text-align: center; font-weight: bold;
        }
        table.data-table td {
            border: 1px solid #000; padding: 5px; height: 30px; /* Fixed height for empty rows */
        }

        /* --- FOOTER SIGNATURES --- */
        .signature-box {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-top: 80px; margin-bottom: 10px;
        }
        .signature-item {
            text-align: center; font-weight: bold; font-size: 12px; width: 30%;
             padding-top: 5px;
        }

        .print-btn {
            position: fixed; top: 10px; right: 10px; background: #333; color: #fff; padding: 10px; cursor: pointer;
        }

        @media print {
            .footer-spacer { display: none !important; }
            .bill-container, .receipt-container, .prescription-container, .discharge-container, .slip-container { min-height: auto !important; height: auto !important; }

            .print-btn { display: none; }
            .container { border: none; margin: 0; width: 100%; padding: 0; min-height: auto; }
            @page { margin: 5mm; size: A4; }
            .label, th { background: #eee !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    @php $m = json_decode(setting('pdf_margins_ipd_no_dues', '{}'), true) ?? []; @endphp
    <button onclick="window.print()" class="print-btn">Print No Dues</button>

    <div class="container">

        <div style="padding-top:{{ $m['header']['top'] ?? 0 }}px; padding-left:{{ $m['header']['left'] ?? 0 }}px; padding-right:{{ $m['header']['right'] ?? 0 }}px; padding-bottom:{{ $m['header']['bottom'] ?? 0 }}px; box-sizing: border-box; width: 100%;">


        @if(setting('show_header_ipd_no_dues', 'yes') == 'yes')
        
            @if(setting('ipd_header_image'))
                <img src="{{ asset('img/' . setting('ipd_header_image')) }}" alt="Header" style="width:100%; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
            @else
<table class="header-table">
            <tr>
                <td style="width:80px; vertical-align:top;">
                    <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" style="height: 70px;">
                </td>
                <td style="text-align:right; vertical-align:top;">
                    <div style="font-size:20px; font-weight:bold; color: {!! setting('theme_color', '#000') !!}; margin-bottom: 5px;">
                        {{ setting('hospital_name', 'Hospital Name') }}
                    </div>
                    <div style="font-size: 12px; color: #333;">
                        {{ setting('tagline', 'Tagline Here') }}<br>
                        {{ setting('address', 'Address Line 1, City') }}<br>
                        Phone: +91 {{ setting('phone') }}
                    </div>
                </td>
            </tr>
        </table>
            @endif
        @else
        <div style="height: {{ setting('spacer_ipd_no_dues', '180') }}px;"></div>
        @endif
    </div>

        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
            <div class="box">
                <table class="details">
                    <tr>
                        <td class="label">UHID</td> <td class="val">{{ $uhid }}</td>
                        <td class="label">Admission No</td> <td class="val">{{ $ipdPatient->ipd_no }}</td>
                    </tr>
                    <tr>
                        <td class="label">Patient Name</td> <td class="val">{{ strtoupper($ipdPatient->patient->name) }}</td>
                        <td class="label">Admission Date</td> <td class="val">{{ date('d-m-Y', strtotime($ipdPatient->admission_date)) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Gender/Age</td> <td class="val">
                            {{ ucfirst($ipdPatient->patient->gender) }} / 
                            {{ $ipdPatient->patient->age ?? '0' }} Y 
                            @if($ipdPatient->patient->age_month)
                                {{ $ipdPatient->patient->age_month }} M
                            @endif
                        </td>
                        <td class="label">Admission Time</td> <td class="val">{{ date('h:i A', strtotime($ipdPatient->admission_date)) }}</td>
                    </tr>
                    <tr>
                        <td class="label">Guardian</td> <td class="val">{{ $ipdPatient->patient->guardian_name }}</td>
                        <td class="label">Ward-Bed</td> <td class="val">{{ $ipdPatient->beds->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Address</td> <td class="val" colspan="3">{{ $ipdPatient->patient->address }}</td>
                    </tr>
                    <tr>
                        <td class="label">Discharge Date</td> <td class="val">
                            @php
                                $dischargeDate = $ipdPatient->discharge && $ipdPatient->discharge->discharge_date ? $ipdPatient->discharge->discharge_date : $ipdPatient->discharge_date;
                            @endphp
                            {{ $dischargeDate ? \Carbon\Carbon::parse($dischargeDate)->format('d-m-Y') : 'N/A' }}
                        </td>
                        <td class="label">Discharge Time</td> <td class="val">
                            {{ $dischargeDate ? \Carbon\Carbon::parse($dischargeDate)->format('h:i A') : 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
            <div class="main-title ">NO DUES CERTIFICATE</div>
        </div>

        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="40%">DEPARTMENT</th>
                        <th width="20%">DUES</th>
                        <th width="40%">SIGNATURE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Hospital Bill</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Pharmacy Bill</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Pathology Bill</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Any Other (If any) Bill</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div class="signature-box">
                <div style="visibility: hidden" class="signature-item">Verified By</div>
                <div style="visibility: hidden" class="signature-item">Approved By</div>
                <div class="signature-item">Patient/Guardian's Signature</div>
            </div>

            <div style=" margin: 20px 0; position: relative; text-align: center;">
                <span style="background: #fff; padding: 0 10px; font-size: 10px; position: relative; top: -8px;">CUT HERE</span>
            </div>

            <div class="section-header">PAYMENT RECEIVED DETAILS (FOR OFFICE OFFICIAL USE)</div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th width="40%">DEPARTMENT</th>
                        <th width="20%">TOTAL BILL</th>
                        <th width="40%">RECEIVED AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Hospital</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Pharmacy</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Pathology</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Any Other (If any)</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if(setting('show_footer_ipd_no_dues', 'yes') == 'yes')
        <div style="padding-top:{{ $m['footer']['top'] ?? 0 }}px; padding-left:{{ $m['footer']['left'] ?? 0 }}px; padding-right:{{ $m['footer']['right'] ?? 0 }}px; padding-bottom:{{ $m['footer']['bottom'] ?? 0 }}px; box-sizing: border-box;">
            <div class="signature-box">
                <div class="signature-item">Verified By</div>
                <div class="signature-item">Approved By</div>
            </div>
        </div>
        @endif

    </div>

        <div class="footer-spacer" style="height: 130px;"></div>
        @if(setting('show_footer_ipd_no_dues', 'yes') == 'yes' && setting('ipd_footer_image'))
            <div style="position:fixed; bottom:0; left:0; left:0; right:0; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . setting('ipd_footer_image')) }}" style="width:100%; height:auto; display:block;" alt="Footer">
            </div>
        @endif
        @if(setting('show_footer_ipd_no_dues', 'yes') == 'yes' && !setting('ipd_footer_image'))
            <div class="default-text-footer" style="position:fixed; bottom:0; left:0; width:100%; text-align:center; padding-bottom:20px; z-index: 100;">
                <div style="font-weight: bold; color: {!! setting('theme_color', '#e91e63') !!}">
                    For Emergency Contact: +91 {{ setting('phone', '0000000000') }}
                </div>
                <div>{{ setting('address', 'Hospital Address') }}</div>
            </div>
        @endif
    </body>
<script>
    window.onload = function() {
        window.print();
    };
</script>
</html>