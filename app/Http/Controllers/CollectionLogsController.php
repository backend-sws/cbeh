<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentCollection;

class CollectionLogsController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = PaymentCollection::with(['user', 'patient'])->orderBy('created_at', 'desc');

        // Only show own records if not admin
        if ($user->role != 'Admin' ) { // Adjust as per your role logic
            $query->where('user_id', $user->id);
        }

        // Search by staff or patient name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                })->orWhereHas('patient', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
            });
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        // Filter by from date
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }

        // Filter by to date
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        $collections = $query->paginate(15);

        return view('logs.collection', compact('collections'));
    }
}