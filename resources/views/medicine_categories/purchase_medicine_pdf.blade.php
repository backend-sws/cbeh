<!DOCTYPE html>
<html>

<head>
    <title>Purchase Invoice - {{ $purchase->purchase_no }}</title>
    <style>
        /* Base Settings */
        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            background: #fff;
            color: #222;
            line-height: 1.3;
        }

        .bill-container {
            width: 750px;
            margin: 10px auto;
            background: #fff;
            min-height: 100vh;
            padding: 20px 40px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* --- Patient Info Table --- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #333;
        }

        .info-table td {
            padding: 5px 8px;
            vertical-align: top;
            border: 1px solid #333;
            width: 25%;
        }

        .info-table td:nth-child(odd) {
            background-color: #fff;
            font-weight: bold;
            color: #444;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            color: {!! setting('theme_color', '#e91e63') !!};
            padding-bottom: 3px;
            text-transform: uppercase;
        }

        /* --- Charges Table --- */
        .charges-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .charges-table th,
        .charges-table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: center;
        }

        .charges-table th {
            background: #f3f4f6;
            color: #1e293b;
            font-weight: bold;
            text-align: center;
        }

        .charges-table td:first-child,
        .charges-table th:first-child {
            text-align: left;
        }

        /* --- Summary Table --- */
        .summary-container {
            display: flex;
            gap: 20px;
            margin-top: 15px;
            align-items: flex-start;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 6px;
            border-bottom: 1px solid #eee;
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: {!! setting('theme_color', '#e91e63') !!};
            color: white;
            font-size: 13px;
        }

        /* --- Footer Styles --- */
        .footer-split {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            padding-top: 20px;
        }

        .signature-section {
            width: 40%;
        }

        .small-points {
            font-size: 9px;
            color: #555;
            text-align: left;
            margin-top: 5px;
            line-height: 1.4;
        }

        /* Note Style */
        .note {
            background: #f9fafb;
            border-radius: 4px;
            padding: 8px 12px;
            margin-top: 10px;
            font-style: italic;
            border-left: 3px solid #ccc;
            color: #555;
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: {!! setting('theme_color', '#e91e63') !!};
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            z-index: 2000;
        }

        @media print {
            .footer-spacer { display: none !important; }
            .bill-container, .receipt-container, .prescription-container, .discharge-container, .slip-container { min-height: auto !important; height: auto !important; }

            body {
                background: #fff;
            }

            .bill-container {
                width: 100%;
                border: none;
                margin: 0;
                padding: 0;
            }

            .print-btn {
                display: none;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>
    @php $m = json_decode(setting('pdf_margins_pharmacy_purchase', '{}'), true) ?? []; @endphp

    <button onclick="window.print()" class="print-btn">Print Invoice</button>

    <div class="bill-container">

        {{-- HEADER --}}
        <div style="padding-top:{{ $m['header']['top'] ?? 0 }}px; padding-left:{{ $m['header']['left'] ?? 0 }}px; padding-right:{{ $m['header']['right'] ?? 0 }}px; padding-bottom:{{ $m['header']['bottom'] ?? 0 }}px; box-sizing: border-box; width: 100%;">

            @if(setting('show_header_pharmacy_purchase', 'yes') == 'yes')

                @if(setting('pharmacy_header_image'))
                    <img src="{{ asset('img/' . setting('pharmacy_header_image')) }}" alt="Header" style="width:calc(100% + 80px); margin-left:-40px; margin-right:-40px; height:auto; display:block; border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; margin-bottom:15px; padding-bottom:10px;">
                @else
                    <table class="header-table" width="100%" style="border-bottom:2px solid {!! setting('theme_color', '#e91e63') !!}; padding-bottom:10px; margin-bottom:15px;">
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
                                    Phone: +91 {{ setting('phone') }} | Email: {{ setting('email') }}
                                </div>
                            </td>
                        </tr>
                    </table>
                @endif
            @else
            <div style="height: {{ setting('spacer_pharmacy_purchase', '180') }}px;"></div>
            @endif
        </div>

        {{-- TITLE --}}
        <div style="margin-top:{{ $m['title']['top'] ?? 0 }}px; margin-left:{{ $m['title']['left'] ?? 0 }}px; margin-right:{{ $m['title']['right'] ?? 0 }}px; margin-bottom:{{ $m['title']['bottom'] ?? 0 }}px;">
            <div style="text-align:center; font-weight:bold; font-size:16px; margin-top:5px; text-decoration: underline; color: #333;">
                PURCHASE INVOICE
            </div>
        </div>

        {{-- PURCHASE DETAILS (Patient Info Section) --}}
        <div style="margin-top:{{ $m['patient_info']['top'] ?? 0 }}px; margin-left:{{ $m['patient_info']['left'] ?? 0 }}px; margin-right:{{ $m['patient_info']['right'] ?? 0 }}px; margin-bottom:{{ $m['patient_info']['bottom'] ?? 0 }}px;">
            <div class="section-title">Purchase Details</div>
                    @include('components.pdf-patient-info', [
            'docKey' => 'pharmacy_purchase',
        ])

        </div>

        {{-- PURCHASED ITEMS (Content Section) --}}
        <div style="margin-top:{{ $m['content']['top'] ?? 0 }}px; margin-left:{{ $m['content']['left'] ?? 0 }}px; margin-right:{{ $m['content']['right'] ?? 0 }}px; margin-bottom:{{ $m['content']['bottom'] ?? 0 }}px;">
            <div class="section-title">Purchased Items</div>
            <table class="charges-table">
                <thead>
                    <tr>
                        <th width="5%" style="text-align:center;">S.No</th>
                        <th style="text-align:center;">Medicine Name</th>
                        <th width="15%">Batch No</th>
                        <th width="12%">Expiry</th>
                        <th width="10%">Qty</th>
                        <th width="10%">Tax%</th>
                        <th width="15%">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase->purchasedMedicines as $index => $item)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td style="text-align: center;">
                                {{ $item->medicine->medicine ?? 'N/A' }}
                            </td>
                            <td style="text-align: center;">{{ $item->lot_no ?? '-' }}</td>
                            <td style="text-align: center;">
                                {{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('M-Y') : '-' }}
                            </td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: center;">{{ $item->tax }}%</td>
                            <td style="text-align: right;">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #f9f9f9; font-weight:bold;">
                        <td colspan="6" style="text-align: right;">Total Amount:</td>
                        <td style="text-align: right;">{{ number_format($purchase->total, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="summary-container">
                <div style="flex: 0 0 45%; margin-left: auto;">
                    <table class="summary-table">
                        <tr>
                            <td>Sub Total:</td>
                            <td style="font-weight: bold;">&#x20B9; {{ number_format($purchase->total, 2) }}</td>
                        </tr>
                        @if($purchase->tax > 0)
                            <tr>
                                <td>Total Tax:</td>
                                <td>+ &#x20B9; {{ number_format($purchase->tax, 2) }}</td>
                            </tr>
                        @endif
                        @if($purchase->discount > 0)
                            <tr>
                                <td>Discount:</td>
                                <td>- &#x20B9; {{ number_format($purchase->discount, 2) }}</td>
                            </tr>
                        @endif
                        <tr class="total-row">
                            <td style="border:none;">Net Payable:</td>
                            <td style="border:none; font-size: 14px;">&#x20B9; {{ number_format($purchase->net_amount, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @if ($purchase->payment_note)
                <div class="note"><strong>Payment Note:</strong> {{ $purchase->payment_note }}</div>
            @endif

            @if ($purchase->note)
                <div class="note"><strong>Note:</strong> {{ $purchase->note }}</div>
            @endif
        </div>

        {{-- FOOTER SECTION --}}
        @if(setting('show_footer_pharmacy_purchase', 'yes') == 'yes')
            <div class="footer-split qr-hide-block" style="padding-top:{{ $m['footer']['top'] ?? 0 }}px; padding-left:{{ $m['footer']['left'] ?? 0 }}px; padding-right:{{ $m['footer']['right'] ?? 0 }}px; padding-bottom:{{ $m['footer']['bottom'] ?? 0 }}px; box-sizing: border-box; justify-content: flex-end;">
                <div class="signature-section">
                    <table class="qr-hide-block" width="100%">
                        <tr>
                            <td style="width:50%; text-align:center; border:none;">
                                <div style="height:30px;"></div>
                                <div style="border-top:1px solid #333; width:60%; margin:0 auto;"></div>
                                <div style="font-size:11px; margin-top:3px;">Authorized Signature</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="default-text-footer" style="padding-top:5px; margin-top: 10px; font-size:11px; text-align:center; color:#555;">
                <div class="thank-you-msg" style="margin-top: 2px; font-size: 16px; color: #000000; font-family: 'Monotype Corsiva', cursive; font-style: italic;">
                    Thank you for your Purchase!
                </div>
            </div>
        @endif

    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

        <div class="footer-spacer" style="height: 130px;"></div>
        @if(setting('pharmacy_footer_image'))
            <div style="position:fixed; bottom:0; left:0; left:0; right:0; text-align:center; z-index: 100;">
                <img src="{{ asset('img/' . setting('pharmacy_footer_image')) }}" style="width:100%; width:100%; height:auto; display:block;" alt="Footer">
            </div>
        @endif

@if(setting('show_qr_payment_section', 'yes') == 'no')
<style>
    .qr-section,
    .signature-section,
    .small-points,
    .thank-you-msg, .qr-hide-block {
        display: none !important;
    }
</style>
@endif

@if(setting('pharmacy_footer_image'))
<style>
    .default-text-footer { display: none !important; }
</style>
@endif
</body>

</html>