<!DOCTYPE html>
<html>

<head>
    <title>IPD Continuation Sheet - {{ $ipdPatient->ipd_no }}</title>
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
            margin: 10px auto 0 auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            position: relative;
            min-height: 90vh;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .header-space {
            height: 180px;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 5px;
            color: #333;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid #333;
            table-layout: fixed;
        }

        .info-table td {
            padding: 6px 8px;
            vertical-align: top;
            border: 1px solid #333;
            word-wrap: break-word;
        }

        /* Label Column Style */
        .info-table td:nth-child(odd) {
            background-color: #f9f9f9;
            width: 15%;
            /* Labels chote rakhe hain */
            font-weight: bold;
        }

        /* Value Column Style */
        .info-table td:nth-child(even) {
            width: 35%;
        }

        .blank-space {
            flex: 1;
            min-height: 250px;
            margin: 30px 0 30px 0;
        }

        .print-btn {
            margin: 20px auto 0 auto;
            display: block;
            padding: 8px 20px;
            font-size: 12px;
            background-color:
                {!! setting('theme_color', '#e91e63') !!}
            ;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: flex-start;
                min-height: 100vh;
            }

            .print-btn {
                display: none;
            }

            .bill-container { border: none; padding: 0;
                margin: 0 auto;
                width: 100%;
                max-width: 100%;
                box-shadow: none;
            }

            .header-space {
                height: 120px;
            }

            @page {
                margin: 0.5cm;
                size: A4;
            }

            /* Print colors fix */
            .info-table td:nth-child(odd) {
                background-color: #f9f9f9 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    
        @media print {
            .print-fixed-bottom {
                position: fixed !important;
                bottom: 130px !important;
                left: 0 !important;
                width: 100% !important;
                padding-left: 20px !important;
                padding-right: 20px !important;
                box-sizing: border-box !important;
            }
        }
</style>
</head>

<body>
    <div class="bill-container">
                <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">
        @if(setting('show_header_ipd_blank', 'yes') == 'yes')
            @if(setting('ipd_header_image'))
                <img src="{{ asset('img/' . setting('ipd_header_image')) }}" alt="Header" style="width:100%; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
            @else
<table class="header-table" style="width: 100%; border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom: 15px; padding-bottom: 10px;">
            <tr>
                <td style="width:90px; vertical-align:top;">
                    <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" style="height: 70px;">
                </td>
                <td style="text-align:right; vertical-align:top;">
                    <div style="font-size:18px; font-weight:bold; color: {!! setting('theme_color', '#e91e63') !!}; margin-bottom: 5px;">
                        {{ setting('hospital_name', 'Hospital Name') }}
                    </div>
                    <div style="font-size: 12px; color: #555;">
                        {{ setting('tagline', 'Tagline') }}<br>
                        {{ setting('address', 'Address Here') }}<br>
                        Phone: +91 {{ setting('phone') }}, {{ setting('mobile') }}<br>
                        Website: {{ setting('website', 'www.example.com') }}
                    </div>
                </td>
            </tr>
        </table>
            @endif
        @else
        <div style="height: {{ setting('spacer_ipd_blank', '180') }}px;"></div>
        @endif
        </div>

        <div
            style="text-align:center; font-weight:bold; font-size:18px; margin-bottom:5px; text-decoration:underline; text-transform: uppercase;">
            IPD PRESCRIPTION
        </div>

                <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
<div class="section-title">Patient Information</div>
                @include('components.pdf-patient-info', [
            'docKey' => 'ipd_blank',
            'patient' => $ipdPatient->patient ?? null,
            'uhid' => $patient_uhid ?? null,
            'ipd_no' => $ipdPatient->ipd_no ?? null,
            'doa' => $ipdPatient->admission_date ? \Carbon\Carbon::parse($ipdPatient->admission_date)->format('d-m-Y h:i A') : null,
            'bed_ward' => $ipdPatient->beds->name ?? '',
            'doctor_name' => $ipdPatient->doctor->name ?? null,
        ])

        </div>

        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">




        <div class="blank-space"></div>

        
        <button onclick="window.print()" class="print-btn">
            Print Blank Receipt
        </button>
            </div> <!-- End of content wrapper -->
        
        <div class="footer-split print-fixed-bottom" style="display:flex; justify-content:space-between; margin-top: auto; padding-top:{{ $m['footer']['top'] ?? 0 }}px; margin-left:{{ $m['footer']['left'] ?? 0 }}px; margin-right:{{ $m['footer']['right'] ?? 0 }}px; margin-bottom:{{ $m['footer']['bottom'] ?? 20 }}px;">
            <div class="qr-section" style="display: {{ setting('show_qr_payment_section', 'yes') == 'no' ? 'none' : 'block' }}">
                @if(setting('hospital_qr'))
                    <img src="{{ asset('img/' . setting('hospital_qr')) }}" style="height: 80px; width: 80px; border: 1px solid #ddd; padding: 3px;">
                @else
                    <div style="height: 80px; width: 80px;  display:flex; align-items:center; justify-content:center; border: 1px solid #ddd; margin:0 auto; color:#999; font-size:10px;">No QR</div>
                @endif
                <div style="font-weight: bold; font-size: 12px; margin-top: 5px; color: #333;">Scan & Pay</div>
            </div>

            <div class="signature-section" style="margin-left: auto; display: {{ setting('show_consultant_signature', 'yes') == 'yes' ? 'block' : 'none' }};">
                <table width="200px">
                    <tr>
                        <td style="text-align:center; border:none;">
                            <div style="height:60px;"></div>
                            <div style="border-top:1px solid #333; width:100%; margin:0 auto;"></div>
                            <div style="font-size:11px; margin-top:3px; font-weight:bold;">Consultant's Signature</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

                @if(setting('show_footer_ipd_blank', 'yes') == 'yes' && setting('ipd_footer_image'))
            <div style="height: 130px;"></div>
            <div style="position:fixed; bottom:0; left:0; width:100%; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . setting('ipd_footer_image')) }}" style="width:100%; height:auto; display:block;" alt="Footer">
            </div>
        @endif</div>
    <script>
        window.onload = function () {
            // Uncomment to auto print
            window.print();
        };
    </script>

</body>

</html>