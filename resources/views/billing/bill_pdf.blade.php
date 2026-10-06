{{-- resources/views/billing/bill_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill - {{ $bill->bill_id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            color: #333;
            padding: 20px;
            line-height: 1.5;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #e83e8c;
            padding-bottom: 10px;
        }

        .logo img {
            height: 50px;
        }

        .title {
            text-align: right;
        }

        .title h2 {
            margin: 0;
            color: #e83e8c;
        }

        .address {
            font-size: 12px;
            color: #666;
            text-align: right;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            margin-top: 25px;
            margin-bottom: 10px;
            border-bottom: 1px solid #e83e8c;
            padding-bottom: 3px;
            color: #e83e8c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            vertical-align: top;
        }

        th {
            background-color: #fce4ec;
            font-weight: bold;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .total-row {
            background-color: #f8bbd0;
            font-weight: bold;
        }

        .info-table td {
            border: none;
            padding: 5px 0;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
              <img src="{{ public_path('img/site-logo.png') }}" alt="Logo">
        </div>
        <div class="title">
            <h2>BILL</h2>
            <div class="address">
                HMS<br>
                C/303, Atlanta Shopping Mall, Sudama Chowk,<br>
                Mota Varachha, Surat, Gujarat - 394101
            </div>
        </div>
    </div>

            @include('components.pdf-patient-info', [
            'docKey' => 'ipd_bill',
            'patient' => $patient ?? null,
            'uhid' => $patient->patient_unique_id ?? null,
        ])


    <div class="section-title">Patient Details</div>
    <table>
        <tr>
            <th>Patient</th>
            <td>{{ $patient->name ?? '-' }}</td>
            <th>Email</th>
            <td>{{ $patient->email ?? '-' }}</td>
        </tr>
        <tr>
            <th>Cell No</th>
            <td>{{ $patient->phone ?? '-' }}</td>
            <th>Gender / Age</th>
            <td>
                {{ ucfirst($patient->gender ?? '-') }} / 
                {{ $patient->age ?? '0' }} Y 
                @if($patient->age_month)
                    {{ $patient->age_month }} M
                @endif
            </td>
        </tr>
        <tr>
            <th>Date Of Birth</th>
            <td>{{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('d M, Y') : '-' }}</td>
            <th>Doctor</th>
            <td>{{ $doctor->name ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Admission Info</div>
    <table>
        <tr>
            <th>Admission Date</th>
            <td>{{ $bill->admission_date ? \Carbon\Carbon::parse($bill->admission_date)->format('d M, Y h:i A') : '-' }}</td>
            <th>Discharge Date</th>
            <td>{{ $bill->discharge_date ? \Carbon\Carbon::parse($bill->discharge_date)->format('d M, Y h:i A') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Package</th>
            <td>{{ $bill->package_name ?? '-' }}</td>
            <th>Insurance</th>
            <td>{{ $bill->insurance_id ?? '-' }}</td>
        </tr>
        <tr>
            <th>Policy No</th>
            <td>{{ $bill->policy_no ?? 'N/A' }}</td>
            <th>Total Days</th>
            <td>{{ $bill->total_days ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Bill Items</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 50%;">Item Name</th>
                <th style="width: 10%;" class="right">Qty</th>
                <th style="width: 15%;" class="right">Price (Rs)</th>
                <th style="width: 20%;" class="right">Amount (Rs)</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($charges as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->Item_name }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">Rs{{ number_format($item->price, 2) }}</td>
                    <td class="right">Rs{{ number_format($item->amount, 2) }}</td>
                </tr>
                @php $total += $item->amount; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="right">Total Amount:</td>
                <td class="right">Rs{{ number_format($total, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
