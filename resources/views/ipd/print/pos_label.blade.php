<!DOCTYPE html>
<html>
<head>
    <title>POS Label - {{ $ipdPatient->ipd_no }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #fff;
            margin: 0;
        }

        @media print {
            @page {
                size: 2in 1in; /* Exact 2x1 inch size */
                margin: 0;
            }
            
            body {
                margin: 0;
                padding: 0;
            }
        }

        .pos-label {
            width: 2in;
            height: 1in;
            padding: 0;
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #fff;
            color: #000;
            text-align: center;
            line-height: 1.38;
            overflow: hidden;
        }

        .uhid-row, .name-row, .info-row {
            width: 100%;
            padding: 0 5px; /* Equal horizontal margin */
        }

        .uhid-row {
            font-size: 11px;
        }

        .name-row {
            font-weight: 800;
            font-size: 13.5px;
            text-transform: uppercase;
            margin: 1px 0;
        }

        .info-row {
            font-size: 10.5px;
        }
    </style>
</head>
<body>
    <div class="pos-label">
        <div class="uhid-row">UHID: {{ $uhid ?? 'N/A' }}</div>
        
        <div class="name-row">{{ $user->name ?? 'N/A' }}</div>
        
        <div class="info-row">
            Age & Sex: {{ $ipdPatient->patient->age ?? '0' }} Y 
            @if($ipdPatient->patient->age_month)
                {{ $ipdPatient->patient->age_month }} M
            @endif
            / {{ $ipdPatient->patient->gender ?? 'N/A' }}
        </div>
        
        <!--<div class="info-row" style="font-size: 11.5px;">-->
        <!--    Consultant: {{ $ipdPatient->doctor->name ?? 'N/A' }}-->
        <!--</div>-->
        
        <div class="info-row" style="font-size: 11.5px;">
            Patient Mob: {{ $user->phone ?? 'N/A' }}
        </div>
    </div>

    <script>
        // Auto-print on page load
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
