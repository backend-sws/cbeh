@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-5xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">PDF Print Settings</h1>
            <p class="text-gray-600 text-sm mt-1">Manage print margins for all PDF documents and reports.</p>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
            <form action="{{ route('admin.hospital-print-settings.update') }}" method="POST">
                @csrf

                {{-- ============================================================ --}}
                {{-- PDF SECTION-WISE MARGINS --}}
                {{-- ============================================================ --}}
                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">PDF Section-wise Margins</h3>
                    <p class="mb-4 text-xs text-gray-500">Har PDF document ke andar har section (Header, Title, Patient Info, Content, Footer) ko independently move kar sakte hain. Values pixels (px) me hain. Pre-printed stationery par overlap se bachne ke liye Left margin badhayein.</p>

                    @php
                        $margin_docs = [
                            ['key' => 'pathology_report', 'name' => 'Pathology Test Report'],
                            ['key' => 'pathology_bill', 'name' => 'Pathology Invoice / Bill'],
                            ['key' => 'pathology_statement', 'name' => 'Pathology Consolidated Statement'],
                            ['key' => 'opd_bill', 'name' => 'OPD Patient Invoice / Bill'],
                            ['key' => 'opd_day_bill', 'name' => 'OPD Day-wise Bill'],
                            ['key' => 'opd_prescription', 'name' => 'OPD/IPD Prescription'],
                            ['key' => 'opd_progress_note', 'name' => 'OPD Clinical Progress Note'],
                            ['key' => 'opd_diagnosis', 'name' => 'OPD Diagnosis / Test Sheet'],
                            ['key' => 'opd_blank', 'name' => 'OPD Blank Prescription Receipt'],
                            ['key' => 'ipd_payment', 'name' => 'IPD Payment Receipt'],
                            ['key' => 'ipd_service_chart', 'name' => 'IPD Service Chart'],
                            ['key' => 'ipd_blank', 'name' => 'IPD Blank Prescription'],
                            ['key' => 'ipd_no_dues', 'name' => 'IPD No Dues Slip'],
                            ['key' => 'ipd_discharge', 'name' => 'IPD Gate Pass & Discharge Slip'],
                            ['key' => 'ipd_admission', 'name' => 'IPD Admission Slip'],
                            ['key' => 'ipd_bill', 'name' => 'IPD Final Bill'],
                            ['key' => 'ipd_day_bill', 'name' => 'IPD Day-wise Bill'],
                            ['key' => 'ipd_progress_note', 'name' => 'IPD Clinical Progress Note'],
                            ['key' => 'ipd_vitals_chart', 'name' => 'IPD Vitals & Observation Chart'],
                            ['key' => 'pharmacy_bill', 'name' => 'Pharmacy Invoice / Receipt'],
                            ['key' => 'pharmacy_statement', 'name' => 'Pharmacy Consolidated Statement'],
                            ['key' => 'pharmacy_purchase', 'name' => 'Pharmacy Purchase Invoice'],
                            ['key' => 'birth_report', 'name' => 'Birth Certificate'],
                            ['key' => 'death_report', 'name' => 'Death Certificate'],
                            ['key' => 'blood_issue', 'name' => 'Blood Issue Slip'],
                            ['key' => 'prescription', 'name' => 'Prescription Print'],
                        ];
                        $sections = ['header' => 'Header', 'title' => 'Title', 'patient_info' => 'Patient Info', 'content' => 'Content / Table', 'footer' => 'Footer / Signatures'];
                        $directions = ['top' => 'Top', 'left' => 'Left', 'right' => 'Right', 'bottom' => 'Bottom'];
                    @endphp

                    <div class="space-y-2">
                        @foreach($margin_docs as $mdoc)
                            @php
                                $saved_margins = json_decode(setting('pdf_margins_' . $mdoc['key'], '{}'), true) ?? [];
                            @endphp
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                {{-- Accordion Header --}}
                                <button type="button" onclick="toggleMarginAccordion('margin_accordion_{{ $mdoc['key'] }}')"
                                    class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
                                    <span class="text-sm font-medium text-gray-800">{{ $mdoc['name'] }}</span>
                                    <svg id="margin_accordion_{{ $mdoc['key'] }}_icon" class="w-4 h-4 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                {{-- Accordion Body --}}
                                <div id="margin_accordion_{{ $mdoc['key'] }}" class="hidden">
                                    <div class="px-4 py-3 bg-white">
                                        <table class="w-full text-xs border border-gray-200 rounded">
                                            <thead>
                                                <tr class="bg-gray-50">
                                                    <th class="px-2 py-2 text-left text-gray-600 font-medium">Section</th>
                                                    @foreach($directions as $dKey => $dName)
                                                        <th class="px-2 py-2 text-center text-gray-600 font-medium w-20">{{ $dName }} (px)</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100">
                                                @foreach($sections as $sKey => $sName)
                                                    <tr>
                                                        <td class="px-2 py-2 text-gray-700 font-medium">{{ $sName }}</td>
                                                        @foreach($directions as $dKey => $dName)
                                                            <td class="px-2 py-2 text-center">
                                                                <input type="number" 
                                                                    name="pdf_margins_{{ $mdoc['key'] }}[{{ $sKey }}][{{ $dKey }}]"
                                                                    value="{{ $saved_margins[$sKey][$dKey] ?? 0 }}"
                                                                    min="-500" max="500" step="1"
                                                                    class="w-16 px-1 py-1 border border-gray-300 rounded text-center text-xs focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 text-right">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        Save Print Settings
                    </button>
                </div>
            </form>

            <script>
                // Accordion toggle
                function toggleMarginAccordion(id) {
                    var el = document.getElementById(id);
                    var icon = document.getElementById(id + '_icon');
                    if (el.classList.contains('hidden')) {
                        el.classList.remove('hidden');
                        icon.classList.add('rotate-180');
                    } else {
                        el.classList.add('hidden');
                        icon.classList.remove('rotate-180');
                    }
                }
            </script>
        </div>
    </div>
@endsection
