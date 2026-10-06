@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
    <div class=" mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Hospital Settings</h2>
                <p class="mt-1 text-sm text-gray-600">Manage your hospital's basic information and appearance</p>
            </div>

            <form id="hospital-settings-form" action="{{ route('admin.hospital-web-settings.update') }}" method="POST"
                enctype="multipart/form-data" class="divide-y divide-gray-200">
                @csrf

                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">General Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="hospital_name" class="block text-sm font-medium text-gray-700 mb-1">Hospital
                                Name *</label>
                            <input type="text" id="hospital_name" name="hospital_name"
                                value="{{ setting('hospital_name') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500"
                                required>
                        </div>

                        <div>
                            <label for="tagline" class="block text-sm font-medium text-gray-700 mb-1">Tagline / Certification</label>
                            <input type="text" id="tagline" name="tagline" value="{{ setting('tagline') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500"
                                placeholder="e.g. ISO 9001:2015 Certified">
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" id="address" name="address" value="{{ setting('address') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City/Jurisdiction</label>
                            <input type="text" id="city" name="city" value="{{ setting('city', 'Muzaffarpur') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ setting('email') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                            <input type="text" id="website" name="website" value="{{ setting('website') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ setting('phone') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                            <input type="text" id="mobile" name="mobile" value="{{ setting('mobile') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="free_visit_day" class="block text-sm font-medium text-gray-700 mb-1">Free Visit Day</label>
                            <input type="number" id="free_visit_day" name="free_visit_day" 
                                value="{{ setting('free_visit_day', 1) }}"
                                min="1" max="30"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500"
                                placeholder="Enter number of days ">
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Hospital Logo</h3>

                    <div class="flex items-center">
                        <div class="mr-6" id="preview_container_logo">
                            <p class="text-sm font-medium text-gray-700 mb-2">Current Logo</p>
                            @if (setting('logo'))
                                <div>
                                    <img src="{{ asset('img/' . setting('logo')) }}" alt="Hospital Logo"
                                        class="h-16 w-auto object-contain border border-gray-200 rounded p-1 bg-white">
                                    <button type="button" onclick="deleteSettingImage('logo')" title="Delete Logo"
                                        class="mt-2 w-full bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-xs font-semibold py-1 px-2 rounded flex items-center justify-center gap-1 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Delete Logo
                                    </button>
                                </div>
                            @else
                                <div
                                    class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Upload New
                                Logo</label>
                            <div class="mt-1 flex items-center">
                                <input type="file" id="logo" name="logo"
                                    class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-pink-50 file:text-pink-700
                                hover:file:bg-pink-100">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment QR Code</h3>

                    <div class="flex items-center">
                        <div class="mr-6" id="preview_container_hospital_qr">
                            <p class="text-sm font-medium text-gray-700 mb-2">Current QR</p>
                            @if (setting('hospital_qr'))
                                <div>
                                    <img src="{{ asset('img/' . setting('hospital_qr')) }}" alt="Payment QR"
                                        class="h-24 w-auto object-contain border border-gray-200 rounded p-1 bg-white">
                                    <button type="button" onclick="deleteSettingImage('hospital_qr')" title="Delete QR"
                                        class="mt-2 w-full bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-xs font-semibold py-1 px-2 rounded flex items-center justify-center gap-1 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Delete QR
                                    </button>
                                </div>
                            @else
                                <div
                                    class="h-24 w-24 bg-gray-200 rounded flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 6h6v6H6V6zm12 0h-6v6h6V6zm-6 12H6v-6h6v6z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <label for="hospital_qr" class="block text-sm font-medium text-gray-700 mb-1">Upload New QR Code</label>
                            <div class="mt-1 flex items-center">
                                <input type="file" id="hospital_qr" name="hospital_qr"
                                    class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-pink-50 file:text-pink-700
                                hover:file:bg-pink-100">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF (Recommended size: 300x300px)</p>
                        </div>
                        
                        <div class="ml-6 border-l pl-6 border-gray-200">
                            <label for="show_qr_payment_section" class="block text-sm font-medium text-gray-700 mb-1">Show QR & Signature Section on Invoices</label>
                            <select id="show_qr_payment_section" name="show_qr_payment_section"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm rounded-md">
                                <option value="yes" {{ setting('show_qr_payment_section', 'yes') == 'yes' ? 'selected' : '' }}>Yes, Show on All Invoices</option>
                                <option value="no" {{ setting('show_qr_payment_section', 'yes') == 'no' ? 'selected' : '' }}>No, Hide on All Invoices</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">This will hide the QR, "Scan & Pay", and "Thank you" messages across all billing PDFs.</p>
                            
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <label for="show_consultant_signature" class="block text-sm font-medium text-gray-700 mb-1">Show Consultant's Signature</label>
                                <select id="show_consultant_signature" name="show_consultant_signature"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm rounded-md">
                                    <option value="yes" {{ setting('show_consultant_signature', 'yes') == 'yes' ? 'selected' : '' }}>Yes, Show on Invoices</option>
                                    <option value="no" {{ setting('show_consultant_signature', 'yes') == 'no' ? 'selected' : '' }}>No, Hide on Invoices</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">This will hide the "Consultant's Signature" line on OPD and IPD blank receipts.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Department-wise Header & Footer Images</h3>
                    <p class="mb-4 text-xs text-gray-500">Upload custom header and footer images for different departments. These can be used to override the default hospital logo and text header in their respective PDFs. (Recommended: 800x150 pixels)</p>
                    
                    @php
                        $dept_images = [
                            ['key' => 'opd', 'name' => 'OPD'],
                            ['key' => 'ipd', 'name' => 'IPD'],
                            ['key' => 'pathology', 'name' => 'Pathology Lab'],
                            ['key' => 'pharmacy', 'name' => 'Pharmacy / Medicine'],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($dept_images as $dept)
                            <div class="border border-gray-200 p-4 rounded-lg bg-gray-50">
                                <h4 class="font-medium text-gray-800 mb-3">{{ $dept['name'] }}</h4>
                                
                                <div class="mb-4">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Header Image</label>
                                    <div class="flex items-center">
                                        <div class="mr-4 w-24 flex-shrink-0" id="preview_container_{{ $dept['key'] }}_header_image">
                                            @if (setting($dept['key'] . '_header_image'))
                                                <div>
                                                    <img src="{{ asset('img/' . setting($dept['key'] . '_header_image')) }}" alt="Header" class="h-10 w-full object-contain border border-gray-200 rounded bg-white">
                                                    <button type="button" onclick="deleteSettingImage('{{ $dept['key'] }}_header_image')" title="Delete Header Image" class="mt-1 w-full bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-[10px] font-semibold py-0.5 px-1 rounded flex items-center justify-center gap-1 transition">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            @else
                                                <div class="h-10 w-full bg-gray-200 rounded flex items-center justify-center text-gray-400 text-[10px] text-center">None</div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" name="{{ $dept['key'] }}_header_image" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Footer Image</label>
                                    <div class="flex items-center">
                                        <div class="mr-4 w-24 flex-shrink-0" id="preview_container_{{ $dept['key'] }}_footer_image">
                                            @if (setting($dept['key'] . '_footer_image'))
                                                <div>
                                                    <img src="{{ asset('img/' . setting($dept['key'] . '_footer_image')) }}" alt="Footer" class="h-10 w-full object-contain border border-gray-200 rounded bg-white">
                                                    <button type="button" onclick="deleteSettingImage('{{ $dept['key'] }}_footer_image')" title="Delete Footer Image" class="mt-1 w-full bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-[10px] font-semibold py-0.5 px-1 rounded flex items-center justify-center gap-1 transition">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            @else
                                                <div class="h-10 w-full bg-gray-200 rounded flex items-center justify-center text-gray-400 text-[10px] text-center">None</div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" name="{{ $dept['key'] }}_footer_image" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pharmacy Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="medical_name" class="block text-sm font-medium text-gray-700 mb-1">Pharmacy Store Name</label>
                            <input type="text" id="medical_name" name="medical_name" value="{{ setting('medical_name') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500"
                                placeholder="e.g. Titanium Medical Store (Used on pharmacy bills)">
                        </div>

                        <div>
                            <label for="medical_lic_no_1" class="block text-sm font-medium text-gray-700 mb-1">Medical License No. 1</label>
                            <input type="text" id="medical_lic_no_1" name="medical_lic_no_1" value="{{ setting('medical_lic_no_1') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="medical_lic_no_2" class="block text-sm font-medium text-gray-700 mb-1">Medical License No. 2</label>
                            <input type="text" id="medical_lic_no_2" name="medical_lic_no_2" value="{{ setting('medical_lic_no_2') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pathology Report Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="pathology_left_sig_name" class="block text-sm font-medium text-gray-700 mb-1">Technician / Left Side Name</label>
                            <input type="text" id="pathology_left_sig_name" name="pathology_left_sig_name" value="{{ setting('pathology_left_sig_name', 'MD Sahboob Alam') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="pathology_left_sig_designation" class="block text-sm font-medium text-gray-700 mb-1">Technician / Left Side Designation</label>
                            <input type="text" id="pathology_left_sig_designation" name="pathology_left_sig_designation" value="{{ setting('pathology_left_sig_designation', 'B.M.L.T. (Technician)') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="pathology_right_sig_name" class="block text-sm font-medium text-gray-700 mb-1">Pathologist / Right Side Name (Doctor Name)</label>
                            <input type="text" id="pathology_right_sig_name" name="pathology_right_sig_name" value="{{ setting('pathology_right_sig_name', '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500"
                                placeholder="Leave empty to hide doctor's name">
                        </div>

                        <div>
                            <label for="pathology_right_sig_designation" class="block text-sm font-medium text-gray-700 mb-1">Pathologist / Right Side Designation</label>
                            <input type="text" id="pathology_right_sig_designation" name="pathology_right_sig_designation" value="{{ setting('pathology_right_sig_designation', 'MBBS, DCP (Pathologist)') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>

                        <div>
                            <label for="pathology_report_prefix" class="block text-sm font-medium text-gray-700 mb-1">Pathology Report Number Prefix</label>
                            <input type="text" id="pathology_report_prefix" name="pathology_report_prefix" value="{{ setting('pathology_report_prefix', 'PATH-REP-') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Dashboard Settings</h3>
                    <div class="flex items-center mb-1">
                        <input type="hidden" name="hide_dashboard_data" value="no">
                        <input type="checkbox" id="hide_dashboard_data" name="hide_dashboard_data" value="yes"
                            {{ setting('hide_dashboard_data', 'no') == 'yes' ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500 cursor-pointer">
                        <label for="hide_dashboard_data" class="ml-2 block text-sm font-medium text-gray-900 cursor-pointer">
                            Hide Financial and Statistical Data on Admin Dashboard
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 ml-6">When checked, revenue figures, patient collections, and staff counts will be hidden on the dashboard.</p>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">PDF & Print Settings (Independent Toggles)</h3>
                    <p class="mb-4 text-xs text-gray-500">Configure header visibility, footer visibility, and top spacing spacer height for each printable document type independently.</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document Type</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Show Header</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Show Footer</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Show Title</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Spacer Height (px)</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-64">Patient Info Fields</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $docs = [
                                        ['key' => 'pathology_bill', 'name' => 'Pathology Invoice / Bill'],
                                        ['key' => 'pathology_report', 'name' => 'Pathology Test Report'],
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
                                @endphp
                                @foreach($docs as $doc)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $doc['name'] }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <input type="hidden" name="show_header_{{ $doc['key'] }}" value="no">
                                            <input type="checkbox" id="show_header_{{ $doc['key'] }}" name="show_header_{{ $doc['key'] }}" value="yes"
                                                {{ setting('show_header_' . $doc['key'], 'yes') == 'yes' ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500 cursor-pointer">
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <input type="hidden" name="show_footer_{{ $doc['key'] }}" value="no">
                                            <input type="checkbox" id="show_footer_{{ $doc['key'] }}" name="show_footer_{{ $doc['key'] }}" value="yes"
                                                {{ setting('show_footer_' . $doc['key'], 'yes') == 'yes' ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500 cursor-pointer">
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            <input type="hidden" name="hide_title_{{ $doc['key'] }}" value="yes">
                                            <input type="checkbox" id="hide_title_{{ $doc['key'] }}" name="hide_title_{{ $doc['key'] }}" value="no"
                                                {{ setting('hide_title_' . $doc['key'], 'no') == 'no' ? 'checked' : '' }}
                                                class="h-4 w-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500 cursor-pointer">
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <input type="number" id="spacer_{{ $doc['key'] }}" name="spacer_{{ $doc['key'] }}" 
                                                value="{{ setting('spacer_' . $doc['key'], '180') }}"
                                                min="0" max="500"
                                                class="w-24 px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-pink-500 focus:border-pink-500 text-sm"
                                                placeholder="180">
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @php
                                                // Default fields if no setting exists
                                                $defaultFields = ["uhid", "name", "age_gender", "address", "phone", "doctor", "department", "blood_group", "guardian", "appointment_date", "invoice_no", "bed_ward"];
                                                $savedFields = setting('patient_fields_' . $doc['key']);
                                                $selectedFields = $savedFields ? json_decode($savedFields, true) : $defaultFields;
                                                if (!is_array($selectedFields)) $selectedFields = $defaultFields;
                                            @endphp
                                            <div class="w-full px-2 py-2 border border-gray-300 rounded text-sm bg-white overflow-y-auto" style="height: 120px; min-width: 150px;">
                                                @php
                                                    $options = [
                                                        'uhid' => 'UHID / Patient ID',
                                                        'ipd_no' => 'IPD / OPD No',
                                                        'name' => 'Patient Name',
                                                        'age_gender' => 'Age / Gender',
                                                        'address' => 'Address',
                                                        'phone' => 'Phone / Mobile',
                                                        'doctor' => 'Doctor Name',
                                                        'department' => 'Department',
                                                        'blood_group' => 'Blood Group',
                                                        'guardian' => 'Guardian Name',
                                                        'appointment_date' => 'Date / Time',
                                                        'doa' => 'Admission Date (DOA)',
                                                        'dod' => 'Discharge Date (DOD)',
                                                        'invoice_no' => 'Invoice / Bill No',
                                                        'bed_ward' => 'Bed / Ward No'
                                                    ];
                                                @endphp
                                                @foreach($options as $val => $label)
                                                    <label class="flex items-center space-x-2 cursor-pointer mb-1 hover:bg-gray-50 p-1 rounded">
                                                        <input type="checkbox" name="patient_fields_{{ $doc['key'] }}[]" value="{{ $val }}" 
                                                            {{ in_array($val, $selectedFields) ? 'checked' : '' }}
                                                            class="h-4 w-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                                                        <span class="text-gray-700">{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Theme Settings</h3>

                    <div>
                        <label for="theme_color" class="block text-sm font-medium text-gray-700 mb-1">Theme
                            Color</label>
                        <div class="flex items-center">
                            <input type="color" id="theme_color" name="theme_color"
                                value="{{ setting('theme_color', '#3B82F6') }}"
                                class="h-10 w-10 rounded border border-gray-300 cursor-pointer">
                            <span class="ml-3 text-sm text-gray-600">Click to select a color</span>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 text-right">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save General Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function deleteSettingImage(key) {
        if (!confirm('Are you sure you want to delete this image?')) {
            return;
        }

        const deleteUrl = "{{ route('admin.hospital-web-settings.delete_image') }}";
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

        fetch(deleteUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ key: key })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById('preview_container_' + key);
                if (container) {
                    if (key === 'logo') {
                        container.innerHTML = `
                            <p class="text-sm font-medium text-gray-700 mb-2">Current Logo</p>
                            <div class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        `;
                    } else if (key === 'hospital_qr') {
                        container.innerHTML = `
                            <p class="text-sm font-medium text-gray-700 mb-2">Current QR</p>
                            <div class="h-24 w-24 bg-gray-200 rounded flex items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 6h6v6H6V6zm12 0h-6v6h6V6zm-6 12H6v-6h6v6z" />
                                </svg>
                            </div>
                        `;
                    } else {
                        container.innerHTML = `<div class="h-10 w-full bg-gray-200 rounded flex items-center justify-center text-gray-400 text-[10px] text-center">None</div>`;
                    }
                }
                
                // Show toast notification
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-5 right-5 bg-green-600 text-white px-4 py-2.5 rounded-lg shadow-lg text-sm z-50 flex items-center space-x-2 transition-opacity duration-300';
                toast.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>${data.message || 'Image deleted successfully!'}</span>`;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            } else {
                alert(data.message || 'Failed to delete image.');
            }
        })
        .catch(error => {
            console.error('Error deleting image:', error);
            alert('An error occurred while deleting the image.');
        });
    }
    </script>
@endsection