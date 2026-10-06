<?php

if (!function_exists('create_input_fields')) {
    function create_input_fields($model, $columns, $excludedColumns, $dropdownSources = [])
    {
        // Default excluded columns
        $excludedColumns = array_merge($excludedColumns, ['id', 'created_at', 'updated_at']);
        $editableColumns = array_diff($columns, $excludedColumns);
    
        $html = '';
        foreach ($editableColumns as $column) {
            $value = $model->$column ?? '';
    
            if (isset($dropdownSources[$column])) {
                // Dropdown Field
                $options = $dropdownSources[$column];
                $html .= '<div class="mb-4">';
                $html .= '<label for="' . $column . '" class="block text-gray-700 font-medium mb-1">' . ucfirst(str_replace('_', ' ', $column)) . '</label>';
                $html .= '<select name="' . $column . '" id="' . $column . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">';
                $html .= '<option value="">Select ' . ucfirst(str_replace('_', ' ', $column)) . '</option>';
                foreach ($options as $key => $optionValue) {
                    $selected = $key == $value ? 'selected' : '';
                    $html .= '<option value="' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($optionValue, ENT_QUOTES, 'UTF-8') . '</option>';
                }
                $html .= '</select>';
                $html .= '</div>';
            } else if ($column === 'description') {
                // Textarea Field
                $html .= '<div class="mb-4">';
                $html .= '<label for="' . $column . '" class="block text-gray-700 font-medium mb-1">' . ucfirst(str_replace('_', ' ', $column)) . '</label>';
                $html .= '<textarea name="' . $column . '" id="' . $column . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</textarea>';
                $html .= '</div>';
            } else if ($column === 'ips') {
                // IPs Field (Textarea)
                $ipAddresses = is_array($value) ? implode(',', $value) : ''; 
                $html .= '<div class="mb-4">';
                $html .= '<label for="' . $column . '" class="block text-gray-700 font-medium mb-1">Add New IPs</label>';
                $html .= '<textarea name="' . $column . '" id="' . $column . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">' . htmlspecialchars($ipAddresses, ENT_QUOTES, 'UTF-8') . '</textarea>';
                $html .= '</div>';
            } else if ($column === 'password') {
                // Password Field
                $html .= '<div class="mb-4">';
                $html .= '<label for="' . $column . '" class="block text-gray-700 font-medium mb-1">' . ucfirst(str_replace('_', ' ', $column)) . '</label>';
                $html .= '<input type="password" name="' . $column . '" id="' . $column . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">';
                $html .= '</div>';
            } else {
                // Default Text Input Field
                $html .= '<div class="mb-4">';
                $html .= '<label for="' . $column . '" class="block text-gray-700 font-medium mb-1">' . ucfirst(str_replace('_', ' ', $column)) . '</label>';
                $html .= '<input type="text" name="' . $column . '" id="' . $column . '" value="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">';
                $html .= '</div>';
            }
        }
        return $html;
    }
    
}

if (!function_exists('create_display_fields')) {
    function create_display_fields($model, $columns, $excludedColumns, $dropdownSources = [])
    {
        // Default excluded columns
        $excludedColumns = array_merge($excludedColumns, ['id', 'created_at', 'updated_at']);
        $displayColumns = array_diff($columns, $excludedColumns);
    
        $html = '';
        foreach ($displayColumns as $column) {
            $value = $model->$column ?? '';
    
            if (isset($dropdownSources[$column])) {
                $options = $dropdownSources[$column];
                $displayValue = $options[$value] ?? 'N/A';
            } else if ($column === 'ips') {
                $ipAddresses = is_array($value) ? implode(', ', $value) : '';
                $displayValue = htmlspecialchars($ipAddresses, ENT_QUOTES, 'UTF-8');
            } else if ($column === 'password') {
                $displayValue = '********';
            } else {
                $displayValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
    
            // Display Field with Tailwind Styling
            $html .= '<div class="mb-4 flex items-start">';
            $html .= '<label class="text-gray-700 font-medium w-1/3">' . ucfirst(str_replace('_', ' ', $column)) . ':</label>';
            $html .= '<p class="ml-2 text-gray-900 w-2/3">' . $displayValue . '</p>';
            $html .= '</div>';
        }
    
        return $html;
    }
    
}



function show_validation_error($validation,$reponse_return=false)
{
    $validation_error = $validation->errors()->toArray();
    $error_msg = "";

    foreach ($validation_error  as $value) {
        $error_msg = $value[0];
        break;
    }

    if($reponse_return){
        return response()->json([
            'status_code' => 2,
            'status' => 'error',
            'message' => $error_msg
        ]);
    }
    return back()->withInput()->with([
        'status_code' => 2,
        'status' => 'error',
        'message' => $error_msg
    ]);
}

function cleanString($string)
{
    $string = trim($string);

    $string = preg_replace('/[^A-Za-z0-9]/', '-', $string);

    return strtolower($string);
}


if (!function_exists('generateNumbersBetween')) {
    function generateNumbersBetween($start, $end) {
        $start = (int) $start;
        $end = (int) $end;
        if (!is_int($start) || !is_int($end)) {
            return new InvalidArgumentException('Both start and end must be integers.');
        }

        if ($start > $end) {
            return new InvalidArgumentException('Start number must be less than or equal to the end number.');
        }

        // return range($start, $end);
        return array_map(fn($number) => '+' . $number, range($start, $end));
    }
}

function generateUniquePurchaseNumber()
{
    do {
        $code = random_int(100000, 999999);
    } while (\App\Models\PurchaseMedicine::where('purchase_no', '=', $code)->first());

    return $code;
}
function amountToWords($amount) {
    $ones = array(
        0 => "", 1 => "One", 2 => "Two", 3 => "Three", 4 => "Four", 
        5 => "Five", 6 => "Six", 7 => "Seven", 8 => "Eight", 9 => "Nine",
        10 => "Ten", 11 => "Eleven", 12 => "Twelve", 13 => "Thirteen", 
        14 => "Fourteen", 15 => "Fifteen", 16 => "Sixteen", 17 => "Seventeen", 
        18 => "Eighteen", 19 => "Nineteen"
    );
    
    $tens = array(
        0 => "", 2 => "Twenty", 3 => "Thirty", 4 => "Forty", 5 => "Fifty", 
        6 => "Sixty", 7 => "Seventy", 8 => "Eighty", 9 => "Ninety"
    );
    
    $hundreds = array(
        "Hundred", "Thousand", "Lakh", "Crore"
    );

    if ($amount == 0) {
        return "Zero Rupees Only";
    }

    $rupees = floor($amount);
    $paise = round(($amount - $rupees) * 100);

    $words = "";

    // Convert Crores
    $crores = floor($rupees / 10000000);
    if ($crores > 0) {
        $words .= amountToWords($crores) . " Crore ";
        $rupees = $rupees % 10000000;
    }

    // Convert Lakhs
    $lakhs = floor($rupees / 100000);
    if ($lakhs > 0) {
        if ($lakhs > 99) {
            $words .= amountToWords($lakhs) . " Lakh ";
        } else {
            $words .= numberToWords($lakhs) . " Lakh ";
        }
        $rupees = $rupees % 100000;
    }

    // Convert Thousands
    $thousands = floor($rupees / 1000);
    if ($thousands > 0) {
        $words .= numberToWords($thousands) . " Thousand ";
        $rupees = $rupees % 1000;
    }

    // Convert Hundreds
    $hundreds = floor($rupees / 100);
    if ($hundreds > 0) {
        $words .= numberToWords($hundreds) . " Hundred ";
        $rupees = $rupees % 100;
    }

    // Convert Tens and Ones
    if ($rupees > 0) {
        if ($words != "") {
            $words .= "and ";
        }
        if ($rupees < 20) {
            $words .= $ones[$rupees];
        } else {
            $words .= $tens[floor($rupees / 10)];
            $ones_digit = $rupees % 10;
            if ($ones_digit > 0) {
                $words .= " " . $ones[$ones_digit];
            }
        }
    }

    $words .= " Rupees";

    // Add paise if present
    if ($paise > 0) {
        $words .= " and ";
        if ($paise < 20) {
            $words .= $ones[$paise];
        } else {
            $words .= $tens[floor($paise / 10)];
            $ones_digit = $paise % 10;
            if ($ones_digit > 0) {
                $words .= " " . $ones[$ones_digit];
            }
        }
        $words .= " Paise";
    }

    return $words . " Only";
}
function numberToWords($num) {
    $ones = array(
        0 => "", 1 => "One", 2 => "Two", 3 => "Three", 4 => "Four", 
        5 => "Five", 6 => "Six", 7 => "Seven", 8 => "Eight", 9 => "Nine", 
        10 => "Ten", 11 => "Eleven", 12 => "Twelve", 13 => "Thirteen", 
        14 => "Fourteen", 15 => "Fifteen", 16 => "Sixteen", 17 => "Seventeen", 
        18 => "Eighteen", 19 => "Nineteen"
    );
    
    $tens = array(
        0 => "", 2 => "Twenty", 3 => "Thirty", 4 => "Forty", 5 => "Fifty", 
        6 => "Sixty", 7 => "Seventy", 8 => "Eighty", 9 => "Ninety"
    );

    if ($num < 20) {
        return $ones[$num];
    }

    $words = $tens[floor($num / 10)];
    $ones_digit = $num % 10;
    if ($ones_digit > 0) {
        $words .= " " . $ones[$ones_digit];
    }
    return $words;
}



function setting($key, $default = null) {
    $setting = \App\Models\Setting::where('key', $key)->first();
    return $setting ? $setting->value : $default;
}
