<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #e91e63;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .hospital-name {
            font-size: 18px;
            font-bold: bold;
            color: #e91e63;
            margin-bottom: 5px;
        }

        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }

        td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .expired {
            color: #dc2626;
            font-weight: bold;
        }

        .near-expiry {
            color: #d97706;
            font-weight: bold;
        }

        footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td style="border:none; padding:0;">
                <div class="hospital-name">{{ setting('hospital_name', 'Hospital Name') }}</div>
                <div style="font-size:10px; color:#666;">
                    {{ setting('address', 'Address Here') }}<br>
                    Phone: {{ setting('phone', 'Phone Here') }}
                </div>
            </td>
            <td style="border:none; padding:0; text-align:right; vertical-align:middle;">
                <div style="font-size:12px; font-weight:bold;">MEDICINE EXPIRY REPORT</div>
                <div style="font-size:10px; color:#666;">Generated on: {{ date('d M, Y H:i A') }}</div>
            </td>
        </tr>
    </table>

    <div class="report-title">{{ $title }}</div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">#</th>
                <th width="25%">Medicine / Category</th>
                <th width="15%">Batch No</th>
                <th width="15%" class="text-center">Expiry Date</th>
                <th width="12%" class="text-center">Status</th>
                <th width="10%" class="text-center">Stock</th>
                <th width="10%" class="text-right">Price</th>
                <th width="10%" class="text-right">Loss Value</th>
            </tr>
        </thead>
        <tbody>
            @php $totalLoss = 0; @endphp
            @forelse($expired_batches as $index => $batch)
                @php
                    $expiryDate = \Carbon\Carbon::parse($batch->expiry_date);
                    $daysLeft = now()->diffInDays($expiryDate, false);
                    $batchLoss = ($batch->purchase_price ?? 0) * $batch->available_quantity;
                    $totalLoss += $batchLoss;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div class="font-bold">{{ $batch->medicine->medicine ?? 'Unknown' }}</div>
                        <div style="font-size:9px; color:#666;">
                            {{ $batch->medicine->categoryRelation->medicine_category ?? '' }}
                        </div>
                    </td>
                    <td>{{ $batch->lot_no ?? '-' }}</td>
                    <td class="text-center">{{ $expiryDate->format('d M, Y') }}</td>
                    <td class="text-center">
                        @if($daysLeft < 0)
                            <span class="expired">EXPIRED ({{ abs($daysLeft) }}d)</span>
                        @elseif($daysLeft == 0)
                            <span class="expired">TODAY</span>
                        @else
                            <span class="{{ $daysLeft <= 30 ? 'near-expiry' : '' }}">{{ $daysLeft }}d left</span>
                        @endif
                    </td>
                    <td class="text-center">{{ $batch->available_quantity }}</td>
                    <td class="text-right">Rs {{ number_format($batch->purchase_price ?? 0, 2) }}</td>
                    <td class="text-right font-bold">Rs {{ number_format($batchLoss, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">No batches found for this report.</td>
                </tr>
            @endforelse
        </tbody>
        @if($expired_batches->count() > 0)
            <tfoot>
                <tr style="background-color: #f9fafb;">
                    <td colspan="7" class="text-right font-bold" style="padding: 10px;">TOTAL POTENTIAL LOSS:</td>
                    <td class="text-right font-bold" style="padding: 10px; color: #dc2626; font-size: 13px;">
                        Rs {{ number_format($totalLoss, 2) }}
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>

    <footer>
        This is a system generated inventory report. {{ setting('hospital_name') }}
    </footer>
</body>

</html>