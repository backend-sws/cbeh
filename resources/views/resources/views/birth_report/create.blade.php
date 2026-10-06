@extends('layouts.admin')
@section('title', 'Generate Birth Certificate')

@section('content')
<div class="min-h-screen bg-gray-50 p-6 font-sans">
    
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Generate Birth Certificate</h1>
            <p class="text-sm text-gray-500">Fill in the details to create a new birth record.</p>
        </div>
        <a href="{{ route('admin.birth_report.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors shadow-sm text-sm">
            <i class="ph ph-arrow-left mr-2 text-lg"></i> Back to List
        </a>
    </div>

    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 border-b border-blue-800">
            <h2 class="text-white text-lg font-semibold flex items-center">
                <i class="ph ph-file-plus mr-2 text-xl"></i> Birth Registration Form
            </h2>
        </div>

        <form action="{{ route('admin.birth_report.store') }}" method="POST" class="p-6 md:p-8">
            @csrf
            
            <div class="mb-8">
                <h3 class="text-gray-800 font-bold text-base mb-4 flex items-center border-b pb-2">
                    <i class="ph ph-users text-blue-600 mr-2 text-lg"></i> Parent Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Select IPD Mother (Optional)</label>
                        <div class="relative">
                            <select name="ipd_patient_id" id="ipdSelect" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2.5 pl-3 pr-10 text-sm bg-gray-50 focus:bg-white transition-colors cursor-pointer">
                                <option value="">-- Manual Entry / External Patient --</option>
                                @foreach ($patients as $p)
                                    <option value="{{ $p->id }}" 
                                        data-mother="{{ $p->patient->name }}"
                                        data-father="{{ $p->guardian_name }}" 
                                        data-address="{{ $p->patient->address }}">
                                        {{ $p->patient->name }} (IPD: {{ $p->ipd_no }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="ph ph-caret-down text-gray-500"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 flex items-center">
                            <i class="ph ph-info mr-1 text-blue-500"></i> Select to auto-fill Parent names & Address.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Mother's Name <span class="text-red-500">*</span></label>
                        <input type="text" name="mother_name" id="motherName" required placeholder="Enter mother's full name"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Father's Name <span class="text-red-500">*</span></label>
                        <input type="text" name="father_name" id="fatherName" required placeholder="Enter father's full name"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 py-2 px-3 text-sm">
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-gray-800 font-bold text-base mb-4 flex items-center border-b pb-2">
                    <i class="ph ph-baby text-pink-600 mr-2 text-lg"></i> Child Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Child Name (Optional)</label>
                        <input type="text" name="child_name" placeholder="Leave blank if not named yet"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 py-2 px-3 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 py-2 px-3 bg-white text-sm">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="birth_date" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 py-2 px-3 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Time of Birth <span class="text-red-500">*</span></label>
                        <input type="time" name="birth_time" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 py-2 px-3 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Weight <span class="text-gray-400 font-normal text-xs">(e.g. 3.2 Kg)</span></label>
                        <input type="text" name="weight" placeholder="2.5 Kg"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500 py-2 px-3 text-sm">
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-gray-800 font-bold text-base mb-4 flex items-center border-b pb-2">
                    <i class="ph ph-map-pin text-green-600 mr-2 text-lg"></i> Location Details
                </h3>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Address</label>
                    <textarea name="address" id="address" rows="3" placeholder="Full permanent address..."
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 py-2 px-3 text-sm"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                <button type="reset" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors text-sm">
                    Reset
                </button>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-bold shadow-md hover:bg-blue-700 hover:shadow-lg transition-all transform active:scale-95 flex items-center text-sm">
                    <i class="ph ph-check-circle text-lg mr-2"></i> Save & Generate Certificate
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    // Auto-fill logic
    document.getElementById('ipdSelect').addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option.value) {
            document.getElementById('motherName').value = option.getAttribute('data-mother') || '';
            document.getElementById('fatherName').value = option.getAttribute('data-father') || '';
            document.getElementById('address').value = option.getAttribute('data-address') || '';
        }
    });
</script>
@endpush