<!DOCTYPE html>
<html>
<head>
    <title>Discharge & Gate Pass - {{ $ipdPatient->ipd_no }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            margin: 0; padding: 0;
        }
        .container {
            width: 750px;
            margin: 0 auto;
            padding: 10px;
        }
        
        /* --- HEADER STYLE --- */
        .header-table {
            width: 100%;
            border-bottom: 2px solid {!! setting('theme_color', '#000') !!};
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        
        /* Common Box Style */
        .box {
            border: 2px solid #000;
            padding: 10px;
            margin-bottom: 20px; 
        }

        .title {
            text-align: center; font-weight: bold; font-size: 16px;
            text-decoration: underline; text-transform: uppercase; margin-bottom: 10px;
        }
        .sub-title {
            text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 10px;
        }

        table.details { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.details td { border: 1px solid #000; padding: 3px 6px; vertical-align: top; }
        
        .label { font-weight: bold; width: 18%; background: #eee; }
        .val { font-weight: bold; width: 32%; }

        .content-text { margin: 15px 0; line-height: 1.5; text-align: justify; }
        .signature { text-align: right; margin-top: 40px; font-weight: bold; }

        .print-btn {
            position: fixed; top: 10px; right: 10px; background: #333; color: #fff; padding: 10px; cursor: pointer;
        }

        @media print {
            .print-btn { display: none; }
            @page { margin: 5mm; size: A4; }
            .label { background: #eee !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    @php $m = json_decode(setting('pdf_margins_ipd_discharge', '{}'), true) ?? []; @endphp
    <button onclick="window.print()" class="print-btn">Print Slip</button>

    <div class="container">

        <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
            @if(setting('show_header_ipd_discharge', 'yes') == 'yes')
                @if(setting('ipd_header_image'))
                    <img src="{{ asset('img/' . setting('ipd_header_image')) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
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
                <div style="height: {{ setting('spacer_ipd_discharge', '180') }}px;"></div>
            @endif
        </div>

        <div class="box">
            <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px; display: {{ setting('hide_title_ipd_discharge', 'no') == 'yes' ? 'none' : 'block' }};">
                <div class="title">DISCHARGE REQUEST LETTER</div>
            </div>

            <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
                <table class="details">
                    <tr>
                        <td class="label">UHID</td> <td class="val">{{ $uhid ?? ($ipdPatient->patient->patientProfile->patient_unique_id ?? ($ipdPatient->patient->uhid ?? '-')) }}</td>
                        <td class="label">Admission No</td> <td class="val">{{ $ipdPatient->ipd_no }}</td>
                    </tr>
                    <tr>
                        <td class="label">Patient Name</td> <td class="val">{{ strtoupper($ipdPatient->patient->name ?? '-') }}</td>
                        <td class="label">Admission Date</td> <td class="val">{{ $ipdPatient->admission_date ? date('d-m-Y', strtotime($ipdPatient->admission_date)) : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Gender/Age</td> <td class="val">
                            {{ ucfirst($ipdPatient->patient->gender ?? '-') }} / 
                            {{ $ipdPatient->patient->age ?? '0' }} Y 
                            @if(!empty($ipdPatient->patient->age_month))
                                {{ $ipdPatient->patient->age_month }} M
                            @endif
                        </td>
                        <td class="label">Ward-Bed</td> <td class="val">{{ $ipdPatient->beds->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Guardian</td> <td class="val">{{ $ipdPatient->patient->guardian_name ?? '-' }}</td>
                        <td class="label">Mobile</td> <td class="val">{{ $ipdPatient->patient->phone ?? '-' }}</td>
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

            <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
                <div class="content-text">
                    <b>Dear In-Charge,</b><br><br>
                    Please arrange for the discharge of the above mentioned patient from the ICU/Ward. All bills and dues related to hospital services, including Pathology, Pharmacy and other facilities have been cleared. There are no outstanding payments of dues pending.
                    <br><br>
                    We request your prompt attention to facilitate a smooth discharge process.
                    <br>
                    Thank you for your cooperation.
                </div>
            </div>

            @if(setting('show_footer_ipd_discharge', 'yes') == 'yes')
            <div style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 0 }}px;">
                <div class="signature">Authorized Signature</div>
            </div>
            @endif
        </div>

        <div style="margin: 20px 0; position: relative; text-align: center;">
            <span style="background: #fff; padding: 0 10px; font-size: 10px; position: relative; top: -8px;">CUT HERE</span>
        </div>

        @if(setting('show_header_ipd_discharge', 'yes') == 'yes')
        <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
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
        </div>
        @else
        <div style="height: {{ setting('spacer_ipd_discharge', '180') }}px;"></div>
        @endif

        <div class="box">
            <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px; display: {{ setting('hide_title_ipd_discharge', 'no') == 'yes' ? 'none' : 'block' }};">
                <div class="title">GATE PASS</div>
            </div>

            <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
                <table class="details">
                    <tr>
                        <td class="label">UHID</td> <td class="val">{{ $uhid ?? ($ipdPatient->patient->patientProfile->patient_unique_id ?? ($ipdPatient->patient->uhid ?? '-')) }}</td>
                        <td class="label">Admission No</td> <td class="val">{{ $ipdPatient->ipd_no }}</td>
                    </tr>
                    <tr>
                        <td class="label">Patient Name</td> <td class="val">{{ strtoupper($ipdPatient->patient->name ?? '-') }}</td>
                        <td class="label">Admission Date</td> <td class="val">{{ $ipdPatient->admission_date ? date('d-m-Y', strtotime($ipdPatient->admission_date)) : '-' }}</td>
                    </tr>
                    <tr>
                        @php
                            $rawDischargeDoc = $ipdPatient->doctor->name ?? '';
                            $cleanDischargeDoc = ($rawDischargeDoc && $rawDischargeDoc !== '-') ? preg_replace('/^(dr\.?\s*)+/i', '', trim($rawDischargeDoc)) : '';
                        @endphp
                        <td class="label">Consultant</td> <td class="val">{{ $cleanDischargeDoc ? 'Dr. ' . strtoupper($cleanDischargeDoc) : '-' }}</td>
                        <td class="label">Discharge Time</td> <td class="val">
                            @php
                                $dischargeDate = $ipdPatient->discharge && $ipdPatient->discharge->discharge_date ? $ipdPatient->discharge->discharge_date : $ipdPatient->discharge_date;
                            @endphp
                            {{ $dischargeDate ? \Carbon\Carbon::parse($dischargeDate)->format('d-m-Y h:i A') : 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
                <div class="content-text">
                    This is to certify that all dues have been settled and there are no outstanding payments pending. This gate pass is valid for the patient to exit the premises. Please ensure proper verification before allowing departure.
                </div>
            </div>

            @if(setting('show_footer_ipd_discharge', 'yes') == 'yes')
            <div style="margin-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 0 }}px;">
                <div class="signature" style="margin-top: 50px;">Authorized Signature</div>
            </div>
            @endif
        </div>

    </div>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>

    <div style="height: 130px;"></div>
    @if(setting('ipd_footer_image'))
        <div style="position:fixed; bottom:0; left:0; width:100%; text-align:center; z-index: 100;">
            <img src="{{ asset('img/' . setting('ipd_footer_image')) }}" style="width:100%; height:auto; display:block;" alt="Footer">
        </div>
    @endif
</body>
</html>