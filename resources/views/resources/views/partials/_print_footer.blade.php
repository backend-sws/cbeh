{{--
Print Footer Partial
Usage: @include('partials._print_footer', ['settingKey' => 'print_opd_bill_footer', 'defaultOn' => true])

Parameters:
- settingKey: The setting key to check (e.g. 'print_opd_bill_footer')
- defaultOn: Default visibility (true = visible by default, false = hidden by default)
--}}
@php
    $defaultValue = isset($defaultOn) && $defaultOn ? '1' : '0';
    $showFooter = setting($settingKey ?? '', $defaultValue);
@endphp

<div class="footer-content"
    style="text-align:center; border-top:1px dashed {!! setting('theme_color', '#e91e63') !!}; padding-top:10px; font-size:11px; color:#555; {{ $showFooter ? '' : 'visibility: hidden;' }}">
    <div style="font-weight:bold; color: {!! setting('theme_color', '#e91e63') !!};">
        For Emergency Services: +91 {{ setting('phone', '0000000000') }}
    </div>
    <div>
        {{ setting('address', 'Address Here') }}
    </div>
</div>