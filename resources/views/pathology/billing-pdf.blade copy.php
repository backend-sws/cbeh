{{-- filepath: resources/views/pathology/billing-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Pathology Billing Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #222;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 24px;
            background: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f3f3f3;
            padding-bottom: 12px;
        }
        .clinic-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .clinic-info img {
            width: 60px;
            height: auto;
        }
        .text-info {
            display: flex;
            flex-direction: column;
        }
        .clinic-info .name {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
        }
        .clinic-info .meta {
            font-size: 12px;
            color: #64748b;
        }
        .created-on {
            text-align: right;
            font-size: 12px;
            color: #64748b;
        }
        .created-on .date {
            font-weight: bold;
            color: #1e293b;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #ec4899;
            margin-top: 24px;
            margin-bottom: 8px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .info-table td {
            padding: 6px 8px;
            font-size: 13px;
        }
        .info-table .label {
            color: #64748b;
            width: 30%;
        }
        .info-table .value {
            color: #1e293b;
            font-weight: 500;
        }
        .bill-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        .bill-table th, .bill-table td {
            border: 1px solid #e5e7eb;
            padding: 8px 12px;
            font-size: 13px;
        }
        .bill-table th {
            background: #f9fafb;
            color: #1e293b;
            font-weight: 600;
            text-align: left;
        }
        .bill-table td {
            text-align: left;
        }
        .bill-table .amount {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px dashed #e91e63;
            font-size: 12px;
            text-align: center;
        }
        .emergency-contact {
            font-weight: bold;
            color: #e91e63;
            margin-bottom: 5px;
        }
        .signature-area {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 70%;
            margin: 0 auto 5px;
        }
        .total-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .total-table td {
            padding: 6px 8px;
            text-align: right;
        }
        .total-table .label {
            color: #64748b;
        }
        .total-table .value {
            color: #1e293b;
            font-weight: 500;
        }
        .net-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Clinic Info -->
        <table width="100%" style="border-bottom:2px solid #e91e63; margin-bottom:20px; padding-bottom:15px;">
            <tr>
                <td style="width:90px; vertical-align:top;">
                    <img src="{{ public_path('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Hospital Logo" style="height:70px;">
                </td>
                <td style="text-align:right; vertical-align:top;">
                    <div style="font-size:18px; font-weight:bold; color:#e91e63; margin-bottom:5px;">
                        {{ setting('hospital_name', 'Hospital') }} - Pathology Department
                    </div>
                    <div style="font-size:12px; color:#555;">
                        {{ setting('address', 'Address') }}<br>
                        Phone: +91 {{ setting('phone', '0000000000') }}, {{ setting('mobile', '0000000000') }}<br>
                        Email: pathology@hospital.com
                    </div>
                </td>
            </tr>
        </table>

        <!-- Invoice and Patient Details -->
        <table class="info-table">
            <tr>
                <td class="label">Bill No:</td>
                <td class="value">{{ $invoice }}</td>
                <td class="label">Date:</td>
                <td class="value">{{ now()->format('d-m-Y h:i A') }}</td>
            </tr>
            <tr>
           
                <td class="label">Patient ID:</td>
                <td class="value">{{ App\Models\Patient::where('user_id',$bill->patient->id)->first()->patient_unique_id ?? 'N/A' }}</td>
                <td class="label">Patient Name:</td>
                <td class="value">{{ $bill->patient->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Age/Gender:</td>
                <td class="value">{{ $bill->patient->age ?? 'N/A' }} / {{ $bill->patient->gender ?? 'N/A' }}</td>
                <td class="label">Contact No:</td>
                <td class="value">{{ $bill->patient->phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Referring Doctor:</td>
                <td class="value">{{ $bill->doctor->name ?? 'N/A' }}</td>
                <td class="label">Test Category:</td>
                <td class="value">{{ $bill->category->name ?? 'N/A' }}</td>
            </tr>
        </table>

     
        <!-- Test Parameters Section -->
<div class="section-title">Pathology Test Details</div>
<table class="bill-table">
    <thead>
        <tr>
            <th>Parameter Name</th>
            <th>Reference Range</th>
            <th>Patient Result</th>
            <th>Unit</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($parameters))
            @foreach($parameters as $param)
                @if(isset($param['details']))
                <tr>
                    <td>{{ $param['details']->name ?? 'N/A' }}</td>
                    <td>{{ $param['details']->reference_range ?? 'N/A' }}</td>
                    <td>{{ $param['patient_result'] ?? 'N/A' }}</td>
                    <td>
                        @if(isset($param['details']->unit))
                            {{ $param['details']->unit->name ?? 'N/A' }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
        @else
            <tr>
                <td colspan="4" style="text-align: center;">No parameters recorded</td>
            </tr>
        @endif
    </tbody>
</table>

        <!-- Payment Summary -->
        <div class="section-title">Payment Summary</div>
        <table class="total-table">
           
           
            <tr>
                <td class="label">Tax (Rs):</td>
                <td class="value">{{ number_format($tax_amount, 2) }}</td>
            </tr>
            
            <tr class="net-total">
                <td class="label">Total Amount:</td>
                <td class="value">Rs. {{ number_format($total_amount, 2) }}</td>
            </tr>
           
           
        </table>

        <!-- Test Instructions -->
        @if($bill->test_instructions)
        <div style="margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 4px;">
            <strong>Test Instructions:</strong> {{ $bill->test_instructions }}
        </div>
        @endif

        <!-- Report Collection Details -->
        <div style="margin-top: 20px; font-size: 13px;">
            <strong>Report Collection:</strong> Reports will be available after {{ $bill->report_delivery_time ?? '24 hours' }}.
            Please bring this invoice when collecting your report.
        </div>

        <!-- Signature Area -->
        <div class="signature-area">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div style="font-size:12px;">Authorized Signatory</div>
            </div>
            
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="default-text-footer" class="emergency-contact">
                For Emergency Reports: +91 {{ setting('phone', '0000000000') }}
            </div>
            <div>
                Website: <a href="{{ setting('website', 'https://www.patliputranetralaya.in/') }}"
                    style="color:#e91e63; text-decoration:none;">{{ setting('website', 'www.patliputranetralaya.in') }}</a>
                | Email: pathology@hospital.com
            </div>
            <div>
                {{ setting('address', 'Patna: Opposite SBI, Bhootnath Road, Patna-800026') }}
            </div>
        </div>
    </div>

@if(setting('pathology_footer_image'))
<style>
    .default-text-footer { display: none !important; }
</style>
@endif
</body>
</html>