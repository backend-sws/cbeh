<!DOCTYPE html>
<html>

<head>
    <title>Birth Certificate - {{ $birth->reg_no }}</title>
    <style>
        /* Base Reset */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            background: #e0e0e0;
            /* Grey background for screen to see the page */
        }

        /* A4 Page Simulation on Screen */
        .container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
            border: 2px solid #333;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .content-body {
            padding: 15mm;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        /* --- Header --- */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .cert-title {
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
            font-family: 'Monotype Corsiva', cursive;

        }

        .reg-no {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 40px;
        }

        /* --- Content --- */
        .cert-text {
            text-align: justify;
            font-size: 16px;
            line-height: 2;
            /* Better spacing for reading */
            margin-bottom: 30px;
            padding: 0 10px;
        }

        .highlight {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px dotted #000;
            padding: 0 5px;
        }

        .section-header {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            text-decoration: underline;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* --- Details Table --- */
        table.details {
            width: 100%;
            border-collapse: collapse;
            /* margin-bottom: auto;  */
        }

        table.details td {
            padding: 8px 12px;
            border: 1px solid #000;
            vertical-align: top;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
            width: 35%;
            background-color: #f0f0f0;
        }

        .val {
            font-weight: bold;
            width: 65%;
            text-transform: uppercase;
        }

        /* --- Footer --- */
        .footer-note {
            text-align: center;
            font-style: italic;
            margin-top: 10px;
            font-size: 12px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        /* Signature box ki margin-top ko thoda kam karein */
        .signature-box {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
            /* <--- Jyada gap ki wajah se overlap ho sakta hai */
            margin-bottom: 10px;
        }

        .sign-item {
            text-align: center;
            font-weight: bold;
        }

        /* Print Button */
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        /* --- PRINT MEDIA QUERY --- */
        @media print {
            body {
                background: #fff;
                margin: 0;
                padding: 0;
            }

            .print-btn {
                display: none;
            }


            .container {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                border: none;
            }

            /* Page Margins */
            @page {
                size: A4;
                margin: 0;
            }

            /* Print Color Adjustments */
            .label {
                background-color: #f0f0f0 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_birth_report', '{}'), true) ?? []; @endphp
    <button onclick="window.print()" class="print-btn">Print Certificate</button>

    <div class="container">

        <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">


        @if(setting('show_header_birth_report', 'yes') == 'yes')
        
            @if(setting('opd_header_image'))
                <img src="{{ asset('img/' . setting('opd_header_image')) }}" alt="Header" style="width: 100%; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
            @else
                <table class="header-table" style="margin: 15mm 15mm 0 15mm; width: calc(100% - 30mm);">
                    <tr>
                        <td style="width:100px; vertical-align:top;">
                            <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}"
                                style="height: 80px; width: auto;">
                        </td>
                        <td style="text-align:right; vertical-align:middle;">
                            <div style="font-size:24px; font-weight:bold; color: #000; margin-bottom: 5px;">
                                {{ setting('hospital_name', 'Titanium Hospital') }}
                            </div>
                            <div style="font-size: 12px; color: #333; line-height: 1.4;">
                                {{ setting('address', 'Address Here') }}<br>
                                Phone: +91 {{ setting('phone') }} | Email: {{ setting('email') }}
                            </div>
                        </td>
                    </tr>
                </table>
            @endif
        @else
        <div style="height: {{ setting('spacer_birth_report', '180') }}px;"></div>
        @endif
        </div>

        <div class="content-body">
            <div class="cert-title">Birth Certificate</div>
            <div class="reg-no">Reg. No:- {{ $birth->reg_no }}</div>

            <div class="cert-text">
                This is to certify that a child was born to
                <span class="highlight">{{ $birth->mother_name }}</span> and
                <span class="highlight">{{ $birth->father_name }}</span> at
                <span class="highlight">{{ date('h:i A', strtotime($birth->birth_time)) }}</span> on
                <span class="highlight">{{ date('d-m-Y', strtotime($birth->birth_date)) }}</span> at
                <strong>{{ setting('hospital_name', 'Titanium Hospital') }}</strong>.
            </div>

            <div class="section-header">BIRTH INFORMATION</div>
            <table class="details">
                <tr>
                    <td class="label">Date of Birth</td>
                    <td class="val">{{ date('d/m/Y', strtotime($birth->birth_date)) }}</td>
                </tr>
                <tr>
                    <td class="label">Time of Birth</td>
                    <td class="val">{{ date('h:i A', strtotime($birth->birth_time)) }}</td>
                </tr>
                <tr>
                    <td class="label">Weight of Child</td>
                    <td class="val">{{ $birth->weight }}</td>
                </tr>
                <tr>
                    <td class="label">Gender</td>
                    <td class="val">{{ ucfirst($birth->gender) }}</td>
                </tr>
                <tr>
                    <td class="label">Child's Name</td>
                    <td class="val">{{ $birth->child_name ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Mother's Name</td>
                    <td class="val">{{ $birth->mother_name }}</td>
                </tr>
                <tr>
                    <td class="label">Father's Name</td>
                    <td class="val">{{ $birth->father_name }}</td>
                </tr>
                <tr>
                    <td class="label">Address</td>
                    <td class="val">{{ $birth->address }}</td>
                </tr>
            </table>

            <div style="flex: 1;"></div>

            @if(setting('show_footer_birth_report', 'yes') == 'yes')
            <div class="footer-note" style="text-align: justify; font-size: 10px; line-height: 1.4; color: #555; margin-top: 15px;">
                This certificate is issued by {{ strtoupper(setting('hospital_name', 'TITANIUM HOSPITAL')) }} based on the birth records maintained by the hospital at the time of delivery. The information contained herein is true to the best of the hospital's records. This certificate is intended exclusively for hospital record, identification, and administrative purposes. It does not confer any legal status and shall not be deemed to replace, supersede, or substitute the Birth Certificate issued by the competent Government Authority under the Registration of Births and Deaths Act or any other applicable law.
            </div>

            <div class="signature-box">
                <div class="sign-item" style="text-align: left;">
                    Date of Issue: {{ date('d/m/Y', strtotime($birth->issue_date)) }}
                </div>
                <div class="sign-item">
                    <div style="height: 50px;">
                    </div>
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
