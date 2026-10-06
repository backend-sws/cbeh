<table class="info-table">
    @php
        $defaultFields = ["uhid", "ipd_no", "name", "age_gender", "address", "phone", "doctor", "department", "blood_group", "guardian", "appointment_date", "invoice_no", "bed_ward", "doa", "dod"];
        $savedFields = setting('patient_fields_' . ($docKey ?? 'default'));
        $selectedFields = $savedFields ? json_decode($savedFields, true) : $defaultFields;
        if (!is_array($selectedFields)) $selectedFields = $defaultFields;

        $patientInfo = [];
        
        if (in_array('invoice_no', $selectedFields) && !empty($invoice_no)) {
            $patientInfo['Invoice No'] = $invoice_no;
        }
        if (in_array('appointment_date', $selectedFields) && !empty($appointment_date)) {
            $patientInfo['Date/Time'] = $appointment_date;
        }
        if (in_array('name', $selectedFields)) {
            $patientInfo['Patient Name'] = '<strong>' . ($patient->name ?? '-') . '</strong>';
        }
        if (in_array('ipd_no', $selectedFields) && !empty($ipd_no)) {
            $patientInfo['IPD/OPD No'] = $ipd_no;
        }
        if (in_array('uhid', $selectedFields)) {
            $patientInfo['UHID'] = $uhid ?? '-';
        }
        if (in_array('age_gender', $selectedFields) && !empty($patient)) {
            $age = $patient->age ?? '0';
            $age_m = !empty($patient->age_month) ? ' ' . $patient->age_month . ' M' : '';
            $gender = $patient->gender ?? '';
            $patientInfo['Age/Gender'] = "{$age} Y{$age_m} / {$gender}";
        }
        if (in_array('phone', $selectedFields)) {
            $patientInfo['Phone'] = $patient->phone ?? '-';
        }
        if (in_array('address', $selectedFields)) {
            $patientInfo['Address'] = $patient->address ?? '-';
        }
        if (in_array('doctor', $selectedFields)) {
            $patientInfo['Consultant'] = $doctor_name ?? '-';
        }
        if (in_array('department', $selectedFields)) {
            $patientInfo['Department'] = $department_name ?? '-';
        }
        if (in_array('blood_group', $selectedFields)) {
            $patientInfo['Blood Group'] = $patient->blood_group ?? '-';
        }
        if (in_array('guardian', $selectedFields)) {
            $patientInfo['Guardian'] = $patient->guardian_name ?? '-';
        }
        if (in_array('bed_ward', $selectedFields) && !empty($bed_ward)) {
            $patientInfo['Bed/Ward'] = $bed_ward;
        }
        if (in_array('doa', $selectedFields) && !empty($doa)) {
            $patientInfo['D.O.A'] = $doa;
        }
        if (in_array('dod', $selectedFields) && !empty($dod)) {
            $patientInfo['D.O.D'] = $dod;
        }
    @endphp
    @foreach(array_chunk($patientInfo, 2, true) as $chunk)
    <tr>
        @foreach($chunk as $label => $value)
            <td>{{ $label }}:</td>
            <td>{!! $value !!}</td>
        @endforeach
        @if(count($chunk) == 1)
            <td colspan="2"></td>
        @endif
    </tr>
    @endforeach
</table>
