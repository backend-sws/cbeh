@extends('layouts.admin')

@section('title', 'Discharge Patient')

@section('content')

    <div class="max-w-5xl mx-auto py-8 px-4">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">

            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Discharge Patient Summary</h2>
                    <p class="text-sm text-gray-500 mt-1">Fill in the medical details for the discharge slip.</p>
                </div>

                <div class="text-right bg-white border px-4 py-2 rounded shadow-sm">
                    <div class="text-sm text-gray-500">Patient Name</div>
                    <div class="font-bold text-main-color text-lg">{{ $ipdPatient->patient->name }}</div>
                    <div class="text-xs text-gray-600 font-semibold">IPD: {{ $ipdPatient->ipd_no }}</div>
                </div>
            </div>

            <form action="{{ route('admin.ipd.discharge.save', $ipdPatient->id) }}" method="POST" class="p-6">
                @csrf

                <div class="bg-blue-50 p-4 rounded-md border border-blue-100 mb-6">
                    <h3 class="text-sm font-bold text-blue-800 uppercase mb-3 border-b border-blue-200 pb-1">
                        <i class="ph ph-calendar-check mr-1"></i> Discharge Details
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Discharge Date & Time <span
                                    class="text-red-500">*</span></label>
                            <input type="datetime-local" name="discharge_date"
                                value="{{ ($ipdPatient->discharge && $ipdPatient->discharge->discharge_date) ? \Carbon\Carbon::parse($ipdPatient->discharge->discharge_date)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i') }}"
                                class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border focus:ring-main-color focus:border-main-color">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Discharge Status <span
                                    class="text-red-500">*</span></label>
                            <select name="discharge_status"
                                class="w-full border-gray-300 rounded-md shadow-sm p-2.5 border focus:ring-main-color focus:border-main-color">
                                <option value="">Select Status</option>
                                @php $status = $ipdPatient->discharge->discharge_status ?? ''; @endphp
                                <option value="Normal" {{ $status == 'Normal' ? 'selected' : '' }}>Normal / Cured</option>
                                <option value="Referred" {{ $status == 'Referred' ? 'selected' : '' }}>Referred</option>
                                <option value="LAMA" {{ $status == 'LAMA' ? 'selected' : '' }}>LAMA (Left Against Medical
                                    Advice)</option>
                                <option value="Death" {{ $status == 'Death' ? 'selected' : '' }}>Death</option>
                                <option value="Absconded" {{ $status == 'Absconded' ? 'selected' : '' }}>Absconded</option>
                                <option value="DOPR" {{ $status == 'DOPR' ? 'selected' : '' }}>DOPR (Discharge on Patient
                                    Request)</option>
                                <option value="Improved" {{ $status == 'Improved' ? 'selected' : '' }}>Improved</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            1. Final Diagnosis <span class="text-xs font-normal text-gray-500">(Main reason for
                                admission)</span>
                        </label>
                        <textarea name="diagnosis" rows="2"
                            class="w-full border-gray-300 rounded-md shadow-sm p-3 border focus:ring-main-color focus:border-main-color"
                            placeholder="E.g. Acute Gastroenteritis with Dehydration">{{ $ipdPatient->discharge->diagnosis ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            2. Case Summary / History <span class="text-xs font-normal text-gray-500">(Complaints on
                                admission)</span>
                        </label>
                        <textarea name="history" rows="3"
                            class="w-full border-gray-300 rounded-md shadow-sm p-3 border focus:ring-main-color focus:border-main-color"
                            placeholder="E.g. Patient presented with fever, vomiting for 3 days...">{{ $ipdPatient->discharge->history ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            3. Treatment Given <span class="text-xs font-normal text-gray-500">(Medications/Procedures
                                during stay)</span>
                        </label>
                        <textarea name="treatment_given" rows="3"
                            class="w-full border-gray-300 rounded-md shadow-sm p-3 border focus:ring-main-color focus:border-main-color"
                            placeholder="E.g. IV Fluids (RL), Inj. Pantop, Inj. Emset...">{{ $ipdPatient->discharge->treatment_given ?? '' }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">
                                4. Investigations <span class="text-xs font-normal text-gray-500">(Key Lab/X-Ray
                                    Results)</span>
                            </label>
                            <textarea name="investigations" rows="4"
                                class="w-full border-gray-300 rounded-md shadow-sm p-3 border focus:ring-main-color focus:border-main-color"
                                placeholder="E.g. CBC: Hb 10.5, TLC 12000. Widal Test: Positive.">{{ $ipdPatient->discharge->investigations ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">
                                5. Advice on Discharge <span class="text-xs font-normal text-gray-500">(Meds to
                                    continue/Follow up)</span>
                            </label>
                            <textarea name="advice" rows="4"
                                class="w-full border-gray-300 rounded-md shadow-sm p-3 border focus:ring-main-color focus:border-main-color"
                                placeholder="1. Tab Cefixime 200mg x 5 days&#10;2. Soft Diet&#10;3. Review after 7 days">{{ $ipdPatient->discharge->advice ?? '' }}</textarea>
                        </div>
                    </div>

                </div>

                <div class="flex justify-end items-center gap-3 mt-8 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.ipd.index') }}"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" name="submit_action" value="draft"
                        class="px-5 py-2.5 bg-amber-500 text-white rounded-md hover:bg-amber-600 font-bold shadow-md transition-colors flex items-center">
                        <i class="ph ph-file-text mr-2 text-lg"></i> Save as Draft
                    </button>
                    <button type="submit" name="submit_action" value="discharge"
                        class="px-6 py-2.5 bg-red-600 text-white rounded-md hover:bg-red-700 font-bold shadow-md transition-colors flex items-center">
                        <i class="ph ph-check-circle mr-2 text-lg"></i> Save & Discharge
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection