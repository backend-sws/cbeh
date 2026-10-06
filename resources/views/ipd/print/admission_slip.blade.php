<!DOCTYPE html>
<html>

<head>
    <title>Admission Slip - {{ $ipdPatient->ipd_no }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 13px;
            background: #fff;
            color: #222;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .bill-container {
            width: 750px;
            margin: 0 auto;
            /* Removed top margin for print accuracy */
            background: #fff;
            /* Border removed for actual print on letterhead, kept dashed for preview */
            
            padding: 0 40px;
            /* Side padding */
            position: relative;
            min-height: 100vh;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        /* --- LETTERHEAD ADJUSTMENTS --- */
        .header-space {
            height: 180px;
            /* Increased for Letterhead Header */
            width: 100%;
            display: block;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!};
            padding-bottom: 10px;
            margin-bottom: 15px;
            margin-top: 20px;
        }
        .header-table td {
            border: none !important;
        }

        .footer-space {
            height: 100px;
            /* Increased for Letterhead Footer */
            width: 100%;
            display: block;
        }

        /* --------------------------- */

        .main-title {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 20px;
            text-decoration: underline;
            text-transform: uppercase;
            color: #000;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #000;
        }

        .info-table td {
            padding: 6px 8px;
            vertical-align: top;
            border: 1px solid #000;
        }

        .info-table td:nth-child(odd) {
            background-color: #f9f9f9;
            width: 18%;
            font-weight: bold;
        }

        .info-table td:nth-child(even) {
            width: 32%;
            font-weight: bold;
        }

        .declaration-header {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            text-decoration: underline;
            margin: 15px 0 10px 0;
        }

        .declaration-content {
            text-align: justify;
            font-size: 13px;
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .footer-info {
            margin-top: 15px;
            font-weight: bold;
            font-size: 13px;
            line-height: 1.8;
        }

        .signature-box {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 20px;
            padding-bottom: 35px;
        }

        .signature-item {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            width: 30%;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            font-size: 14px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            z-index: 1000;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .print-btn {
                display: none;
            }

            .bill-container {
                border: none;
                /* No border in print */
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0 40px;
                /* Keep side padding */
            }

            .info-table td {
                background-color: transparent !important;
                /* Remove grey bg for clean print */
                -webkit-print-color-adjust: exact;
            }

            @page {
                margin: 0;
                /* Remove default page margins to control via CSS */
                size: A4 portrait;
            }
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_ipd_admission', '{}'), true) ?? []; @endphp

    <button onclick="window.print()" class="print-btn">Print Slip</button>

    <div class="bill-container">

        <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px;">


        @if(setting('show_header_ipd_admission', 'yes') == 'yes')
        
            @if(setting('ipd_header_image'))
                <img src="{{ asset('img/' . setting('ipd_header_image')) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
            @else
<table class="header-table">
            <tr>
                <td style="width:80px; vertical-align:top;">
                    <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" style="height: 70px;">
                </td>
                <td style="text-align:right; vertical-align:top;">
                    <div style="font-size:20px; font-weight:bold; color: {!! setting('theme_color', '#e91e63') !!}; margin-bottom: 5px;">
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
        <div style="height: {{ setting('spacer_ipd_admission', '180') }}px;"></div>
        @endif
    </div>

        <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px; display: {{ setting('hide_title_ipd_admission', 'no') == 'yes' ? 'none' : 'block' }};">
            <div class="main-title">ADMISSION DETAILS</div>
        </div>

        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
            @include('components.pdf-patient-info', [
                'docKey' => 'ipd_admission',
                'patient' => $ipdPatient->patient ?? null,
                'uhid' => $uhid ?? ($ipdPatient->patient->patientProfile->patient_unique_id ?? ($ipdPatient->patient->uhid ?? null)),
                'ipd_no' => $ipdPatient->ipd_no ?? null,
                'doa' => $ipdPatient->admission_date ? \Carbon\Carbon::parse($ipdPatient->admission_date)->format('d-m-Y h:i A') : null,
                'bed_ward' => trim(($ipdPatient->ward->name ?? '') . (!empty($ipdPatient->beds->name) ? ' - ' . $ipdPatient->beds->name : '')),
                'doctor_name' => $ipdPatient->doctor->name ?? null,
            ])
        </div>


        <div class="declaration-header">**घोषणा / सहमति पत्र**</div>

        <div class="declaration-content">
            मैं यह घोषणा करता/करती हूँ कि मैं अपने मरीज को अपनी पूर्ण इच्छा से इस अस्पताल में भर्ती करा रहा/रही हूँ। मैं
            यह पूर्णतः समझता/समझती हूँ कि यह एक निजी अस्पताल है तथा इलाज के दौरान होने वाले सभी खर्चों की जिम्मेदारी
            मेरी स्वयं की होगी।
        </div>

        <div class="declaration-content">
            मैं अस्पताल के सभी नियमों, शर्तों एवं नीतियों से पूर्णतः अवगत हूँ और बिना किसी दबाव, प्रलोभन या जबरदस्ती के
            यह निर्णय ले रहा/रही हूँ।
        </div>

        <div class="declaration-content">
            मैं यह भी पुष्टि करता/करती हूँ कि अपने मरीज के इलाज एवं अस्पताल में रहने के दौरान आवश्यक सभी भुगतान करने के
            लिए मैं पूर्णतः सहमत एवं सक्षम हूँ।
        </div>
        <div class="declaration-content">
            मैं अपने मरीज का इस अस्पताल में इलाज कराने के लिए अपनी स्वेच्छा से सहमति प्रदान करता/करती हूँ। मुझे इलाज के
            दौरान होने वाली संभावित जोखिमों, जटिलताओं एवं अनहोनी घटनाओं के बारे में अस्पताल के चिकित्सक द्वारा
            विस्तारपूर्वक समझाया गया है।
        </div>
        <div class="declaration-content">
            उपरोक्त सभी तथ्यों को भली-भांति समझने के उपरांत, मैं अपने मरीज के इलाज हेतु स्वेच्छा से सहमति देता/देती हूँ
            तथा अस्पताल प्रशासन, चिकित्सकों एवं चिकित्सा स्टाफ को आवश्यक उपचार प्रदान करने की अनुमति देता/देती हूँ। इलाज
            के दौरान किसी भी प्रकार की अनहोनी घटना के लिए मैं अस्पताल, उसके प्रबंधन, डॉक्टर अथवा किसी भी संबंधित व्यक्ति
            को उत्तरदायी नहीं ठहराऊँगा/ठहराऊँगी।
        </div>


        <div class="footer-info">
            <div>Guardian Name :- {{ strtoupper($ipdPatient->patient->guardian_name) }}</div>
            <div>Relation with Patient :- </div>
            <div>Address :- {{ $ipdPatient->patient->address }}</div>
            <div>Mobile Number :- {{ $ipdPatient->patient->phone }}</div>
        </div>

        <div style="flex: 1;"></div>

        <div class="signature-box">
            <div class="signature-item">
                Sign of Patient or Representative
            </div>
            <div class="signature-item">
                Signatory Relationship
            </div>
            <div class="signature-item">
                Sign of Admitting clerk
            </div>
        </div>

        @if(setting('show_footer_ipd_admission', 'yes') == 'no')
        <div class="footer-space"></div>
        @endif

    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

        <div style="height: 130px;"></div>
        @if(setting('ipd_footer_image'))
            <div style="position:fixed; bottom:0; left:0; width:100%; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . setting('ipd_footer_image')) }}" style="width:100%; width:100%; height:auto; display:block;" alt="Footer">
            </div>
        @endif
    </body>

</html>
