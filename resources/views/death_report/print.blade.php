<!DOCTYPE html>
<html>
<head>
    <title>Death Certificate - {{ $death->reg_no }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; margin: 0; padding: 20px; background: #eee; }
        
        .container {
            width: 210mm; min-height: 297mm; margin: 0 auto;
            background: #fff; border: 2px solid #333; position: relative;
            display: flex; flex-direction: column;
        }
        .content-body {
            padding: 15mm;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .header-table { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 30px; }
        
        .cert-title {
            text-align: center; font-size: 32px; font-weight: bold;
            text-decoration: underline; margin-bottom: 10px; font-family: 'Monotype Corsiva', cursive;
        }

        .content-text {
            text-align: center; font-size: 16px; margin-bottom: 30px; line-height: 1.6;
        }

        .section-header {
            text-align: center; font-weight: bold; font-size: 18px;
            text-decoration: underline; margin-bottom: 20px; text-transform: uppercase;
        }

        table.details { width: 100%; border-collapse: collapse; margin-bottom: auto; }
        table.details td { padding: 12px; border: 1px solid #000; font-size: 15px; }
        .label { font-weight: bold; width: 35%; background-color: #f5f5f5; }
        .val { font-weight: bold; width: 65%; text-transform: uppercase; }

        .signature-box {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-top: 60px; margin-bottom: 20px;
        }
        .sign-item { text-align: center; font-weight: bold; }

        @media print {
            body { margin: 0; padding: 0; }
            .print-btn { display: none; }
            .container { width: 100%; min-height: auto; margin: 0; border: 2px solid #333; box-shadow: none; }
            @page { size: A4; margin: 5mm; }
            .label { background-color: #f5f5f5 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    @php $m = json_decode(setting('pdf_margins_death_report', '{}'), true) ?? []; @endphp
    <button onclick="window.print()" class="print-btn" style="position:fixed; top:20px; right:20px; padding:10px 20px; background:#333; color:#fff; cursor:pointer;">Print</button>

    <div class="container">
        <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">

        @if(setting('show_header_death_report', 'yes') == 'yes')
        
            @if(setting('opd_header_image'))
                <img src="{{ asset('img/' . setting('opd_header_image')) }}" alt="Header" style="width: 100%; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
            @else
                <table class="header-table" style="margin: 15mm 15mm 0 15mm; width: calc(100% - 30mm);">
                    <tr>
                        <td style="width:100px; vertical-align:top;">
                            <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" style="height: 80px;">
                        </td>
                        <td style="text-align:right;">
                            <div style="font-size:24px; font-weight:bold; margin-bottom: 5px;">
                                {{ setting('hospital_name', 'Hospital Name') }}
                            </div>
                            <div style="font-size: 12px; color: #333;">
                                {{ setting('address', 'Address Here') }}<br>
                                Phone: +91 {{ setting('phone') }}
                            </div>
                        </td>
                    </tr>
                </table>
            @endif
        @else
            <div style="height: {{ setting('spacer_death_report', '180') }}px;"></div>
        @endif
        </div>

        <div class="content-body">
            <div class="cert-title">Death Certificate</div>
            
            <div class="content-text">
                This is to certify that the Patient has expired in our hospital. <br>
                The details are given below:
            </div>

            <div class="section-header">DEATH INFORMATION</div>

            <table class="details">
                <tr>
                    <td class="label">UHID NO</td>
                    <td class="val">{{ $death->ipdPatient->patient->patientProfile->patient_unique_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Patient's Name</td>
                    <td class="val">{{ $death->patient_name }}</td>
                </tr>
                <tr>
                    <td class="label">Guardian Name</td>
                    <td class="val">{{ $death->ipdPatient->patient->guardian_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Date of Death</td>
                    <td class="val">{{ date('d/m/Y', strtotime($death->death_date)) }}</td>
                </tr>
                <tr>
                    <td class="label">Time of Death</td>
                    <td class="val">{{ date('h:i A', strtotime($death->death_time)) }}</td>
                </tr>
                <tr>
                    <td class="label">Gender</td>
                    <td class="val">{{ $death->gender }}</td>
                </tr>
                <tr>
                    <td class="label">Cause of Death</td>
                    <td class="val">{{ $death->cause_of_death }}</td>
                </tr>
                <tr>
                    <td class="label">Address</td>
                    <td class="val">{{ $death->address ?? 'N/A' }}</td>
                </tr>
            </table>

            <div style="flex:1;"></div>

            @if(setting('show_footer_death_report', 'yes') == 'yes')
            <div class="signature-box">
                <div class="sign-item" style="text-align: left;">
                    Date of Issue: {{ date('d/m/Y', strtotime($death->created_at)) }}
                </div>
                <div class="sign-item">
                    <div style="height: 50px;"></div>
                    Authorized Signature<br>
                    <span style="font-size: 10px;">(Hospital Seal/Stamp)</span>
                </div>
            </div>
            @endif
        </div>
        
        @if(setting('opd_footer_image'))
            <div style="width: 100%; text-align: center; margin-top: auto;">
                <img src="{{ asset('img/' . setting('opd_footer_image')) }}" style="width:100%; height:auto; display:block;" alt="Footer">
            </div>
        @endif
    </div>
</body>
</html>