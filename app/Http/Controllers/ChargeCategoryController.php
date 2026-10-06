<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChargeCategory;
use App\Models\HospitalCharge;

class ChargeCategoryController extends Controller
{
    public function getAllCategories()
    {
        // Hum 'charge_type' relation bhi load kar rahe hain agar zarurat ho
        $categories = ChargeCategory::select('id', 'charge_category', 'charge_type')->get();
        return response()->json($categories);
    }

    public function getCategoriesWithDetails()
{
    // Yahan hum 'whereHas' laga kar filter kar rahe hain ki ChargeType ka name 'Pathology' na ho
    $categories = ChargeCategory::with(['chargeType' => function ($query) {
        $query->select('id', 'name');
    }])
    ->whereHas('chargeType', function ($query) {
        $query->where('name', '!=', 'Pathology');
    })
    ->select('id', 'charge_category', 'charge_type')
    ->get();

    // Har category ke liye HospitalCharge se price bhi fetch karte hain
    $categoriesWithPrice = $categories->map(function ($category) {
        $hospitalCharge = HospitalCharge::where('charge_category', $category->id)->first();
        return [
            'id' => $category->id,
            'charge_category' => $category->charge_category,
            'charge_type_id' => $category->charge_type,
            'charge_type_name' => $category->chargeType ? $category->chargeType->name : '',
            'code' => $hospitalCharge ? $hospitalCharge->code : '',
            'standard_charge' => $hospitalCharge ? $hospitalCharge->standard_charge : 0,
        ];
    });

    return response()->json($categoriesWithPrice);
}
}
