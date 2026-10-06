<!DOCTYPE html>
<html>
<head>
    <title>Pathology Billing Report</title>
    <style>
        @page { margin: 0px; }
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
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
            display: block;
            position: relative;
            min-height: 100vh;
        }

        /* Section Titles */
        .section-title { font-size: 1rem; font-weight: bold; margin-top: 15px; margin-bottom: 5px; color: #333; }
        
        /* Patient Information Table */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; border: 1px solid #333; table-layout: fixed; }
        .info-table td { padding: 6px 8px; vertical-align: top; border-bottom: 1px solid #333; word-wrap: break-word; width: 25%; }
        .right { border-right: 1px solid #333; }
        .info-table td:first-child { background-color: #f9f9f9; }

        /* Category Section */
        .category-section { text-align: center; margin: 10px 0; padding: 7.5px; }
        .category-title { font-size: 1.3rem; font-weight: bold; margin-bottom: 5px; }
        .test-name { font-size: 1.1rem; font-weight: bold; }

        /* Test Parameters Table */
        .test-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 11px; }
        .test-table th, .test-table td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        .test-table th { background: #f8f8f8; }

        /* --- HIGHLIGHT LOGIC CSS --- */
        .val-high { font-weight: bold !important; color: #cc0000 !important; }
        .val-low { font-weight: bold !important; color: #0055aa !important; }
        .val-abnormal { font-weight: bold !important; color: #000 !important; }
        .hl-badge { display: inline-block; font-size: 9px; font-weight: bold; padding: 0 3px; border-radius: 2px; margin-left: 4px; vertical-align: middle; }
        .badge-h { background: #ffd0d0; color: #cc0000; border: 1px solid #cc0000; }
        .badge-l { background: #d0e4ff; color: #0055aa; border: 1px solid #0055aa; }

        .test-result { margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 4px; border: 1px solid #e5e7eb; }
        .note-section { margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 4px; border: 1px solid #e5e7eb; }
        
        .footer-container {
            padding: 10px;
            margin-top: 20px;
        }

        .hospital-footer { text-align: center; border-top: 1px solid #e91e63; margin-top: 10px; padding-top: 5px; font-size: 11px; }
        .emergency-contact { font-weight: bold; color: #e91e63; margin-bottom: 5px; }
    </style>
</head>
<body>
    @php $m = json_decode(setting('pdf_margins_pathology_report', '{}'), true) ?? []; @endphp

    <div class="bill-container">
        
        <!-- HEADER BLOCK -->
        <div style="margin-top:{{ $m['header']['top'] ?? 0 }}px; margin-left:{{ $m['header']['left'] ?? 0 }}px; margin-right:{{ $m['header']['right'] ?? 0 }}px; margin-bottom:{{ $m['header']['bottom'] ?? 0 }}px; width: 100%; overflow:hidden;">
            @if(setting('show_header_pathology_report', 'yes') == 'yes')
                @if(setting('pathology_header_image'))
                    <img src="{{ public_path('img/' . setting('pathology_header_image')) }}" alt="Header" style="width: 100%; max-width: 100%; height: auto; display: block; border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!};">
                @else
                    <table width="100%" style="border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:20px; padding-bottom:15px;">
                        <tr>
                            <td style="width:90px; vertical-align:top;">
                                <img src="{{ public_path('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Hospital Logo" style="height:70px;">
                            </td>
                            <td style="text-align:right; vertical-align:top;">
                                <div style="font-size:18px; font-weight:bold; color:{!! setting('theme_color', '#e91e63 ') !!}; margin-bottom:5px;">
                                    {{ setting('hospital_name', 'Hospital') }}
                                </div>
                                <div style="font-size:12px; color:#555;">
                                    {{ setting('address', 'Address') }}<br>
                                    Phone: +91 {{ setting('phone', '0000000000') }}, {{ setting('mobile', '0000000000') }}<br>
                                    Website: <a href="{{ setting('website', 'https://www.patliputranetralaya.in/') }}" style="color:{!! setting('theme_color', '#e91e63') !!}; text-decoration:none;">{{ setting('website', 'www.patliputranetralaya.in') }}</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                @endif
            @else
                <div style="height: {{ setting('spacer_pathology_report', '180') }}px;"></div>
            @endif
        </div>

        <div style="text-align:center; font-weight:bold; font-size:16px; text-decoration: underline; margin-top:{{ ($m['title']['top'] ?? 0) + 5 }}px; margin-bottom:15px; color: #333; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px;">
            DEPARTMENT OF PATHOLOGY
        </div>

        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
            <div class="section-title">Patient Information</div>
                    @include('components.pdf-patient-info', [
            'docKey' => 'pathology_report',
            'patient' => $bill->patient ?? null,
            'uhid' => $bill->patient->patient_unique_id ?? null,
            'ipd_no' => $bill->ipd_no ?? ($bill->opd_no ?? null),
            'invoice_no' => $bill->invoice_no ?? null,
            'appointment_date' => $bill->created_at ? \Carbon\Carbon::parse($bill->created_at)->format('d-m-Y h:i A') : null,
            'doctor_name' => $bill->doctor->name ?? null,
            'department_name' => $bill->doctor->department->name ?? null,
        ])
        </div>

        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
            <div class="category-section">
                <div class="category-title">{{ $category->name ?? 'N/A' }}</div>
                <div class="test-name">{{ $tests->test_name ?? 'N/A' }}</div>
            </div>

            <table class="test-table">
                <thead>
                    <tr>
                        <th width="40%">Test Parameter Name</th>
                        <th width="30%">Reference Range</th>
                        <th width="30%">Report Value</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        function getValueStatus($value, $reference_range) {
                            if (empty($value) || empty($reference_range) || $value === 'N/A' || $reference_range === 'N/A') return false;
                            
                            $ref_clean = str_replace(['–', '—'], '-', $reference_range);
                            $clean_val = trim($value);
                            $clean_ref = trim($ref_clean);
                            $val_lower = strtolower($clean_val);
                            $ref_lower = strtolower($clean_ref);

                            // COMMA LIST
                            if (strpos($clean_ref, ',') !== false) {
                                $has_dash = strpos($clean_ref, '-') !== false;
                                $has_text_or_symbol = preg_match('/[a-zA-Z]/', $clean_ref) || strpos($clean_ref, '+') !== false;
                                if (!$has_dash || $has_text_or_symbol) {
                                    $allowed = array_map('trim', explode(',', $ref_lower));
                                    return !in_array($val_lower, $allowed) ? 'abnormal' : false;
                                }
                            }

                            // Numeric
                            $val_num_str = preg_replace('/[^\d.-]/', '', str_replace(',', '', $clean_val));
                            if ($val_num_str === '' || !is_numeric($val_num_str)) {
                                if (!preg_match('/[<>-]/', $clean_ref)) {
                                    return $val_lower !== $ref_lower ? 'abnormal' : false;
                                }
                                return false;
                            }

                            $val_float = floatval($val_num_str);
                            $ref_num_clean = str_replace(',', '', $clean_ref);

                            // Range (Min - Max)
                            if (strpos($clean_ref, '-') !== false) {
                                $parts = explode('-', $ref_num_clean);
                                if (count($parts) >= 2) {
                                    $min = floatval(trim($parts[0]));
                                    $max = floatval(trim($parts[1]));
                                    if ($val_float > $max) return 'H';
                                    if ($val_float < $min) return 'L';
                                    return false;
                                }
                            }

                            // Inequality
                            if (preg_match('/[<>]=?/', $clean_ref)) {
                                $threshold = floatval(preg_replace('/[^\d.-]/', '', $ref_num_clean));
                                if (strpos($clean_ref, '<=') !== false) return $val_float > $threshold ? 'H' : false;
                                if (strpos($clean_ref, '>=') !== false) return $val_float < $threshold ? 'L' : false;
                                if (strpos($clean_ref, '<') !== false)  return $val_float >= $threshold ? 'H' : false;
                                if (strpos($clean_ref, '>') !== false)  return $val_float <= $threshold ? 'L' : false;
                            }

                            return false;
                        }
                    @endphp

                    @foreach ($parameters as $parameter)
                        @php
                            $val = $parameter['value'] ?? '';
                            $ref = $parameter['reference_range'] ?? '';
                            $status = getValueStatus($val, $ref);
                        @endphp
                        <tr>
                            <td>{{ $parameter['name'] ?? 'N/A' }}</td>
                            <td>{{ $parameter['reference_range'] ?? 'N/A' }}</td>
                            <td class="@if($status === 'H') val-high @elseif($status === 'L') val-low @elseif($status === 'abnormal') val-abnormal @endif">
                                {{ $val ? $val : 'N/A' }}
                                {{ isset($parameter['unit']) && $parameter['unit'] !== '-' ? ' ' . $parameter['unit'] : '' }}
                                
                                @if($status === 'H')
                                    <span class="hl-badge badge-h">H</span>
                                @elseif($status === 'L')
                                    <span class="hl-badge badge-l">L</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($report->result)
                <div class="test-result">
                    <strong>Result:</strong> {{ $report->result ?? 'N/A' }}
                </div>
            @endif

            @if ($bill->Note)
                <div class="note-section">
                    <strong>Note:</strong> {{ $bill->Note }}
                </div>
            @endif

            <div style="text-align: center; margin-top: 25px; margin-bottom: 15px; font-size: 11px; font-weight: bold; color: #555; letter-spacing: 1px;">
                *** END OF REPORT ***
            </div>
        </div>

        <div class="footer-container" style="position: absolute; bottom: {{ ($m['footer']['bottom'] ?? 0) + 120 }}px; left: {{ $m['footer']['left'] ?? 0 }}px; right: {{ $m['footer']['right'] ?? 0 }}px;">
            <table style="width: 100%; border-collapse: collapse; border: none; margin-bottom: 10px;">
                <tr>
                    <td style="width: 50%; text-align: left; vertical-align: bottom; border: none; padding: 0;">
                        <div style="height: 35px;"></div>
                        @if(setting('pathology_left_sig_name', 'MD Sahboob Alam'))
                            <div style="font-size: 12px; font-weight: bold; color: #333; margin-bottom: 3px;">
                                {{ setting('pathology_left_sig_name', 'MD Sahboob Alam') }}
                            </div>
                        @endif
                        @if(setting('pathology_left_sig_designation', 'B.M.L.T. (Technician)'))
                            <div style="font-size: 11px; color: #555;">
                                {{ setting('pathology_left_sig_designation', 'B.M.L.T. (Technician)') }}
                            </div>
                        @endif
                    </td>
                    <td style="width: 50%; text-align: right; vertical-align: bottom; border: none; padding: 0;">
                        <div style="display: inline-block; text-align: left;">
                            <div style="height: 35px;"></div>
                            @if(setting('pathology_right_sig_name'))
                                <div style="font-size: 12px; font-weight: bold; color: #333; margin-bottom: 3px;">
                                    {{ setting('pathology_right_sig_name') }}
                                </div>
                            @endif
                            @if(setting('pathology_right_sig_designation', 'MBBS, DCP (Pathologist)'))
                                <div style="font-size: 11px; color: #555;">
                                    {{ setting('pathology_right_sig_designation', 'MBBS, DCP (Pathologist)') }}
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

            @if(setting('show_footer_pathology_report', 'yes') == 'yes')
                <!-- Disclaimer Line -->
                <div style="border-top: 1px solid #d1d5db; margin-top: 15px; padding-top: 10px; text-align: center;">
                    <span style="color: #2b6cb0; font-weight: bold; font-size: 11px;">
                        This report is not meant for medico legal purpose. Correlate clinically if abnormal report found.
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Fixed Footer Image for PDF generation -->
    @if(setting('pathology_footer_image'))
        <div style="position:fixed; bottom:0; left:0; right:0; width:100%; text-align:center; z-index: 100;">
            <img src="{{ public_path('img/' . setting('pathology_footer_image')) }}" style="width:100%; max-width:100%; height:auto; display:block;" alt="Footer">
        </div>
    @endif
</body>
</html>
