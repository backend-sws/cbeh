<!DOCTYPE html>
<html>

<head>
    <title>IPD Service Chart - {{ $ipdPatient->ipd_no }}</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .hospital-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .hospital-address {
            font-size: 10px;
            margin-bottom: 10px;
        }

        .patient-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .patient-info-table td {
            border: 1px solid #000;
            padding: 5px 8px;
        }

        .label {
            font-weight: bold;
            background-color: #f2f2f2;
            width: 15%;
        }

        .value {
            width: 35%;
        }

        .service-table {
            width: 100%;
            border-collapse: collapse;
        }

        .service-table th,
        .service-table td {
            border: 1px solid #000;
            padding: 4px 5px;
            text-align: left;
        }

        .service-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .sl-no {
            width: 30px;
            text-align: center !important;
        }

        .service-name {
            width: 180px;
        }

        .date-col {
            width: 45px;
        }

        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 30%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 40px;
            display: inline-block;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #e91e63;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_ipd_service_chart', '{}'), true) ?? []; @endphp

    <button onclick="window.print()" class="print-btn no-print">Print Chart</button>

    <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">


    @if(setting('show_header_ipd_service_chart', 'yes') == 'yes')
    <div class="header">
        <div class="hospital-name">{{ setting('hospital_name', 'TITANIUM HOSPITAL') }}</div>
        <div class="hospital-address">{{ setting('address', 'NH-57, Vijay Chhapra, Muzaffarpur, Bihar') }}</div>
    </div>
    @else
    <div style="height: {{ setting('spacer_ipd_service_chart', '180') }}px;"></div>
    @endif
    </div>

    <table class="patient-info-table">
        <tr>
            <td class="label">Patient Name:</td>
            <td class="value"><strong>{{ $ipdPatient->patient->name }}</strong></td>
            <td class="label">Age / Gender:</td>
            <td class="value">
                {{ $ipdPatient->patient->age ?? '0' }} Y 
                @if($ipdPatient->patient->age_month)
                    {{ $ipdPatient->patient->age_month }} M
                @endif
                / {{ $ipdPatient->patient->gender ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td class="label">UHID No:</td>
            <td class="value">{{ $patient_uhid }}</td>
            <td class="label">Ward / Bed:</td>
            <td class="value">{{ $ipdPatient->beds->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">IPD No:</td>
            <td class="value">{{ $ipdPatient->ipd_no }}</td>
            <td class="label">Date of Admission:</td>
            <td class="value">{{ \Carbon\Carbon::parse($ipdPatient->admission_date)->format('d-m-Y h:i A') }}</td>
        </tr>
    </table>

        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
    <table class="service-table">
        <thead>
            <tr>
                <th rowspan="2" class="sl-no">SL</th>
                <th rowspan="2" class="service-name">SERVICES</th>
                <th colspan="8">Date of Services</th>
            </tr>
            <tr>
                @for($i = 0; $i < 8; $i++)
                    <th class="date-col">&nbsp;</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php
                $services = [
                    'IPD Registration',
                    'Emergency',
                    'ICU',
                    'PVT Room',
                    'General Ward',
                    'Infusion Pump',
                    'Dr Visit',
                    'Duty Doctor / RMO',
                    'Nursing Charge (Day)',
                    'Nursing Charge (Night)',
                    'Monitor',
                    'RBS',
                    'Nebulization',
                    'Foley\'s',
                    'Ryles Tube (RT)',
                    'Blood Transfusion',
                    'FFP / Platelet',
                    'Central Line',
                    'Intubation',
                    'Ventilator',
                    'Bi-PAP / C-PAP',
                    'Dressing Measure',
                    'Dressing Minor',
                    'OT',
                    'Oxygen',
                    'Thrombolysis',
                    'LP',
                    'Suction',
                    'Physiotherapy',
                    'Gastric Lavage',
                    'POP Charge',
                    'Skull Traction'
                ];
            @endphp
            @foreach($services as $index => $service)
                <tr>
                    <td class="sl-no">{{ $index + 1 }}</td>
                    <td>{{ $service }}</td>
                    @for($i = 0; $i < 8; $i++)
                        <td></td>
                    @endfor
                </tr>
            @endforeach
            {{-- Extra empty rows for manual entry --}}
            <!-- @for($j=0; $j<5; $j++)
        <tr>
            <td class="sl-no">{{ count($services) + $j + 1 }}</td>
            <td>&nbsp;</td>
            @for($i=0; $i<10; $i++)
            <td></td>
            @endfor
        </tr>
        @endfor -->
        </tbody>
    </table>
        </div>



    <script>
        window.onload = function () {
            // window.print();
        };
    </script>

</body>

</html>