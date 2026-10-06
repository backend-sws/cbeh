{{--
Print Header Partial
Usage: @include('partials._print_header', ['settingKey' => 'print_opd_bill_header', 'defaultOn' => true])

Parameters:
- settingKey: The setting key to check (e.g. 'print_opd_bill_header')
- defaultOn: Default visibility (true = visible by default, false = hidden by default)
--}}
@php
    $defaultValue = isset($defaultOn) && $defaultOn ? '1' : '0';
    $showHeader = setting($settingKey ?? '', $defaultValue);
@endphp

@if($showHeader)
    <table class="header-table"
        style="width:100%; border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!}; padding-bottom: 10px; margin-bottom: 15px;">
        <tr>
            <td style="width:90px; vertical-align:top;">
                <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" style="height: 70px;">
            </td>
            <td style="text-align:right; vertical-align:top;">
                <div
                    style="font-size:18px; font-weight:bold; color: {!! setting('theme_color', '#e91e63') !!}; margin-bottom: 5px;">
                    {{ $headerName ?? setting('hospital_name', 'Hospital Name') }}
                </div>
                <div style="font-size: 12px; color: #555;">
                    @if(isset($showLicense) && $showLicense && (setting('medical_lic_no_1') || setting('medical_lic_no_2')))
                        <p style="margin:0;">
                            @if(setting('medical_lic_no_1')) Lic No: {{ setting('medical_lic_no_1') }} @endif
                            @if(setting('medical_lic_no_2')) {{ setting('medical_lic_no_1') ? ',' : 'Lic No:' }}
                            {{ setting('medical_lic_no_2') }} @endif
                        </p>
                    @else
                        {{ setting('tagline', 'Tagline') }}<br>
                    @endif
                    {{ setting('address', 'Address Here') }}<br>
                    Phone: +91 {{ setting('phone') }}
                    @if(setting('email')) | Email: {{ setting('email') }} @endif
                    <br>
                    Website: {{ setting('website', 'www.example.com') }}
                </div>
            </td>
        </tr>
    </table>
@else
    {{-- Hidden header for letterhead spacing --}}
    <table
        style="width:100%; border-bottom: 2px solid {!! setting('theme_color', '#e91e63') !!}; padding-bottom: 10px; margin-bottom: 15px; visibility: hidden;">
        <tr>
            <td style="width:90px; vertical-align:top;">
                <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" style="height: 70px;">
            </td>
            <td style="text-align:right; vertical-align:top;">
                <div style="font-size:18px; font-weight:bold; margin-bottom: 5px;">
                    {{ setting('hospital_name', 'Hospital Name') }}
                </div>
                <div style="font-size: 12px;">
                    {{ setting('tagline', 'Tagline') }}<br>
                    {{ setting('address', 'Address Here') }}<br>
                    Phone: +91 {{ setting('phone') }}<br>
                    Website: {{ setting('website', 'www.example.com') }}
                </div>
            </td>
        </tr>
    </table>
@endif