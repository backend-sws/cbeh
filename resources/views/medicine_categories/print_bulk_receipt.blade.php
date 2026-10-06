<!DOCTYPE html>
<html>
<head>
    <title>Receipt #{{ $receipt_no }}</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .box { border: 1px solid #333; padding: 15px; width: 600px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px solid #e91e63; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
    </style>
</head>
<body onload="window.print()">
    <div class="box">
        @if(setting('show_header_pharmacy_bill', 'yes') == 'yes')
        <div class="header">
            <h2 style="margin:0; color: #e91e63;">{{ setting('hospital_name') }}</h2>
            <p style="margin:2px;">Pharmacy Payment Receipt</p>
        </div>
        @else
        <div style="height: {{ setting('spacer_pharmacy_bill', '180') }}px;"></div>
        @endif
        
        <p><strong>Receipt No:</strong> {{ $receipt_no }} <span style="float:right"><strong>Date:</strong> {{ date('d-m-Y h:i A') }}</span></p>
        <p><strong>Patient:</strong> {{ $ipdPatient->patient->name }} ({{ $ipdPatient->ipd_no }})</p>
        
        <table style="font-size: 12px;">
            <thead>
                <tr style="background: #f0f0f0;">
                    <th>Bill No Adjusted</th>
                    <th>Date</th>
                    <th style="text-align:right">Paid Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $pay)
                <tr>
                    <td>{{ $pay->bill->bill_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($pay->bill->bill_date)->format('d-m-Y') }}</td>
                    <td style="text-align:right">₹ {{ number_format($pay->amount, 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="text-align:right; font-weight:bold;">Total Received:</td>
                    <td style="text-align:right; font-weight:bold;">₹ {{ number_format($totalPaidInBills, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        @if(setting('show_footer_pharmacy_bill', 'yes') == 'yes')
        <p style="margin-top: 10px; font-size: 11px;">* Any excess amount has been credited to Advance.</p>
        <br><br>
        <div style="text-align: right;">Authorized Signature</div>
        @endif
    </div>
</body>
</html>