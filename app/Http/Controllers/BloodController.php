<?php

namespace App\Http\Controllers;

use App\Models\BloodBank;
use App\Models\BloodDonor;
use App\Models\BloodDonation;
use App\Models\BloodIssue;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BloodController extends Controller
{


    // Blood Bank Methods
    public function bloodBankIndex()
    {
        $bloodStocks = BloodBank::latest()->get();
        return view('blood-bank.index', compact('bloodStocks'));
    }

    public function bloodBankCreate()
    {
        return view('blood-bank.add');
    }

    public function bloodBankStore(Request $request)
    {
        $request->validate([
            'blood_group' => 'required|string',
            'remaining_bags' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        BloodBank::create($request->all());
        return redirect()->route('admin.blood-bank.index')->with('success', 'Blood stock added successfully');
    }

    public function bloodBankEdit(BloodBank $bloodBank)
    {
        return view('blood-bank.edit', compact('bloodBank'));
    }

    public function bloodBankUpdate(Request $request, BloodBank $bloodBank)
    {
        $request->validate([
            'blood_group' => 'required|string',
            'remaining_bags' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $bloodBank->update($request->all());
        return redirect()->route('admin.blood-bank.index')->with('success', 'Blood stock updated successfully');
    }

    public function bloodBankDestroy(BloodBank $bloodBank)
    {
        $bloodBank->delete();
        return redirect()->route('admin.blood-bank.index')->with('success', 'Blood stock deleted successfully');
    }

    // Blood Donor Methods
    public function bloodDonorIndex()
    {
        $donors = BloodDonor::latest()->get();
        return view('blood-donor.index', compact('donors'));
    }

    public function bloodDonorCreate()
    {
        return view('blood-donor.add');
    }

    public function bloodDonorStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer|min:18',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'last_donation_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        BloodDonor::create($request->all());
        return redirect()->route('admin.blood-donor.index')->with('success', 'Blood donor added successfully');
    }

    public function bloodDonorEdit(BloodDonor $bloodDonor)
    {
        return view('blood-donor.edit', compact('bloodDonor'));
    }

    public function bloodDonorUpdate(Request $request, BloodDonor $bloodDonor)
    {
        $request->validate([
            'name' => 'required|string',
            'age' => 'required|integer|min:18',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'last_donation_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $bloodDonor->update($request->all());
        return redirect()->route('admin.blood-donor.index')->with('success', 'Blood donor updated successfully');
    }

    public function bloodDonorDestroy(BloodDonor $bloodDonor)
    {
        $bloodDonor->delete();
        return redirect()->route('admin.blood-donor.index')->with('success', 'Blood donor deleted successfully');
    }

    // Blood Donation Methods
    public function bloodDonationIndex()
    {
        $donations = BloodDonation::with('donor')->latest()->get();
        return view('blood-donation.index', compact('donations'));
    }

    public function bloodDonationCreate()
    {
        $donors = BloodDonor::all();
        return view('blood-donation.add', compact('donors'));
    }

    public function bloodDonationStore(Request $request)
    {
        $request->validate([
            'donor_id' => 'required|exists:blood_donors,id',
            'blood_group' => 'required|string',
            'bags' => 'required|integer|min:1',
            'donation_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Create donation record
        $donation = BloodDonation::create($request->all());

        // Update blood bank stock
        $bloodBank = BloodBank::where('blood_group', $request->blood_group)->first();
        if ($bloodBank) {
            $bloodBank->remaining_bags += $request->bags;
            $bloodBank->save();
        } else {
            BloodBank::create([
                'blood_group' => $request->blood_group,
                'remaining_bags' => $request->bags
            ]);
        }

        // Update donor's last donation date
        $donor = BloodDonor::find($request->donor_id);
        $donor->last_donation_date = $request->donation_date;
        $donor->save();

        return redirect()->route('admin.blood-donation.index')->with('success', 'Blood donation recorded successfully');
    }

    public function bloodDonationEdit(BloodDonation $bloodDonation)
    {
        $donors = BloodDonor::all();
        return view('blood-donation.edit', compact('bloodDonation', 'donors'));
    }

    public function bloodDonationUpdate(Request $request, BloodDonation $bloodDonation)
    {
        $request->validate([
            'donor_id' => 'required|exists:blood_donors,id',
            'blood_group' => 'required|string',
            'bags' => 'required|integer|min:1',
            'donation_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Update blood bank stock (reverse previous and add new)
        $oldBloodBank = BloodBank::where('blood_group', $bloodDonation->blood_group)->first();
        if ($oldBloodBank) {
            $oldBloodBank->remaining_bags -= $bloodDonation->bags;
            $oldBloodBank->save();
        }

        $newBloodBank = BloodBank::where('blood_group', $request->blood_group)->first();
        if ($newBloodBank) {
            $newBloodBank->remaining_bags += $request->bags;
            $newBloodBank->save();
        } else {
            BloodBank::create([
                'blood_group' => $request->blood_group,
                'remaining_bags' => $request->bags
            ]);
        }

        $bloodDonation->update($request->all());
        return redirect()->route('admin.blood-donation.index')->with('success', 'Blood donation updated successfully');
    }

    public function bloodDonationDestroy(BloodDonation $bloodDonation)
    {
        // Update blood bank stock
        $bloodBank = BloodBank::where('blood_group', $bloodDonation->blood_group)->first();
        if ($bloodBank) {
            $bloodBank->remaining_bags -= $bloodDonation->bags;
            $bloodBank->save();
        }

        $bloodDonation->delete();
        return redirect()->route('admin.blood-donation.index')->with('success', 'Blood donation deleted successfully');
    }

    // Blood Issue Methods
  public function bloodIssueIndex()
{
    $issues = BloodIssue::latest()->get(); // no need for with()
    return view('blood-issue.index', compact('issues'));
}


    public function bloodIssueCreate()
    {
        // Fetch patients and doctors from Users table
        $patients = User::where('role', 'Patient')->get();
        $doctors  = User::where('role', 'Doctor')->get();
        $bloodStocks = BloodBank::where('remaining_bags', '>', 0)->get();

        return view('blood-issue.form', compact('patients', 'doctors', 'bloodStocks'));
    }
    public function bloodIssueStore(Request $request)
    {

        $request->validate([
            'patient_name' => 'required|string',
            'doctor_name' => 'required|string',
            'blood_group' => 'required|string',
            'bags' => 'required|integer|min:1',
            'issue_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);


        // Check blood bank stock
        $bloodBank = BloodBank::where('blood_group', $request->blood_group)->first();
        if (!$bloodBank || $bloodBank->remaining_bags < $request->bags) {
            return back()->with('error', 'Insufficient blood stock available');
        }


        // Update blood bank stock
        $bloodBank->remaining_bags -= $request->bags;
        $bloodBank->save();

        // Create blood issue record
        BloodIssue::create([
            'patient_name'  => $request->patient_name,
            'doctor_name'   => $request->doctor_name,
            'blood_group' => $request->blood_group,
            'bags'        => $request->bags,
            'issue_date'  => $request->issue_date,
            'amount'      => $request->amount,
            'notes'       => $request->notes,
        ]);

        return redirect()->route('admin.blood-issue.index')->with('success', 'Blood issued successfully');
    }

    public function bloodIssueEdit(BloodIssue $bloodIssue)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $bloodStocks = BloodBank::where('remaining_bags', '>', 0)
            ->orWhere('blood_group', $bloodIssue->blood_group)
            ->get();
        return view('blood-issue.form', compact('bloodIssue', 'patients', 'doctors', 'bloodStocks'));
    }

    public function bloodIssueUpdate(Request $request, BloodIssue $bloodIssue)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'blood_group' => 'required|string',
            'bags' => 'required|integer|min:1',
            'issue_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Update blood bank stock (reverse previous and add new)
        $oldBloodBank = BloodBank::where('blood_group', $bloodIssue->blood_group)->first();
        if ($oldBloodBank) {
            $oldBloodBank->remaining_bags += $bloodIssue->bags;
            $oldBloodBank->save();
        }

        $newBloodBank = BloodBank::where('blood_group', $request->blood_group)->first();
        if (!$newBloodBank || $newBloodBank->remaining_bags < $request->bags) {
            return back()->with('error', 'Insufficient blood stock available');
        }

        $newBloodBank->remaining_bags -= $request->bags;
        $newBloodBank->save();

        $bloodIssue->update($request->all());
        return redirect()->route('admin.blood-issue.index')->with('success', 'Blood issue updated successfully');
    }

    public function bloodIssueDestroy(BloodIssue $bloodIssue)
    {
        // Return blood to bank stock
        $bloodBank = BloodBank::where('blood_group', $bloodIssue->blood_group)->first();
        if ($bloodBank) {
            $bloodBank->remaining_bags += $bloodIssue->bags;
            $bloodBank->save();
        }

        $bloodIssue->delete();
        return redirect()->route('admin.blood-issue.index')->with('success', 'Blood issue deleted successfully');
    }

   public function bloodIssuePrint(BloodIssue $bloodIssue)
{
    return view('blood-issue.print', ['issue' => $bloodIssue]);
}

public function bloodIssueView($id)
{
    // Fetch single blood issue by ID
    $issues = BloodIssue::findOrFail($id);

    return view('blood-issue.blood_bill_details', compact('issues'));
}



    public function issue_index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $query = BloodIssue::query();
        $query =  $query->where('patient_name', 'like', '%' . $search . '%')
            ->orWhere('doctor_name', 'like', '%' . $search . '%')
            ->orWhere('donor_name', 'like', '%' . $search . '%')
            ->paginate($perPage);
        $bloodIssue = $query ?? collect();
        return view('blood.issue', compact('bloodIssue'));
    }

    public function issue_save(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'donor_name' => 'required|string|max:255',
            'patient_name' => 'required|string|max:255',
            'doctor_name' => 'required|string|max:255',
            'blood_group' => 'required|string|max:10',
            'amount' => 'required|numeric|min:1',
            'remarks' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Some validation errors occurred!')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }


        try {
            // Save the record
            DB::beginTransaction();

            BloodIssue::create([
                'donor_name' => $request->donor_name,
                'patient_name' => $request->patient_name,
                'doctor_name' => $request->doctor_name,
                'blood_group' => $request->blood_group,
                'amount' => $request->amount,
                'remarks' => $request->remarks,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Blood issue record saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong while saving the record.')->withInput();
        }
    }


    public function bloodBank()
    {
        return view('blood.bank');
    }

    public function bloodGroup()
    {
        return view('blood.group');
    }

    public function newBloodDonor()
    {
        return view('blood.newdonor');
    }

    public function donation_index()
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $query = BloodDonation::query();
        $query =  $query->where('donor_name', 'like', '%' . $search . '%')
            ->orWhere('bags', 'like', '%' . $search . '%')
            ->paginate($perPage);
        $bloodDonation = $query ?? collect();
        return view('blood.donation', compact('bloodDonation'));
    }

    public function donation_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donor_name' => 'required|string|max:255',
            'bags' => 'required|numeric|min:1|max:9223372036854775807',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Please fix the following errors:')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            BloodDonation::create([
                'donor_name' => $request->donor_name,
                'bags' => $request->bags,
            ]);

            return redirect()->back()->with('success', 'Blood donation recorded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while saving the donation.')
                ->withInput();
        }
    }

    public function donation_update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'donor_name' => 'required|string|max:255',
                'bags' => 'required|numeric|min:1|max:9223372036854775807',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('error', 'Please fix the following errors:')
                    ->with('errorsop', $validator->errors())
                    ->withInput();
            }

            BloodDonation::where('id', $id)->update([
                'donor_name' => $request->donor_name,
                'bags' => $request->bags,
            ]);

            return redirect()->back()->with('success', 'Blood donation record Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while updating the donation.')
                ->withInput();
        }
    }

    public function donation_delete(Request $request, $id)
    {
        try {
            $donation = BloodDonation::findOrFail($id);
            $donation->delete();
            return redirect()->back()->with('success', 'Blood donation record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the donation.')
                ->withInput();
        }
    }

    public function bank_index(Request $request)
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $query = BloodBank::query();
        $query =  $query->where('blood_group', 'like', '%' . $search . '%')
            ->orWhere('remained_bags', 'like', '%' . $search . '%')
            ->paginate($perPage);
        $bloodBank = $query ?? collect();
        return view('blood.bank', compact('bloodBank'));
    }

    public function bank_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blood_group' => 'required|string|max:255',
            'remained_bags' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Please fix the following errors:')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            BloodBank::create([
                'blood_group' => $request->blood_group,
                'remained_bags' => $request->remained_bags,
            ]);

            return redirect()->back()->with('success', 'Blood group record created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while saving the group.')
                ->withInput();
        }
    }

    public function bank_update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'blood_group' => 'required|string|max:255',
                'remained_bags' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('error', 'Please fix the following errors:')
                    ->with('errorsop', $validator->errors())
                    ->withInput();
            }

            BloodBank::where('id', $id)->update([
                'blood_group' => $request->blood_group,
                'remained_bags' => $request->remained_bags,
            ]);

            return redirect()->back()->with('success', 'Blood group record Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while updating the blood group.')
                ->withInput();
        }
    }

    public function bank_delete(Request $request, $id)
    {
        try {
            $bank = BloodBank::findOrFail($id);
            $bank->delete();
            return redirect()->back()->with('success', 'Blood group record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the blood group.')
                ->withInput();
        }
    }

    public function donor_index(Request $request)
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $query = BloodDonor::query();
        $query =  $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('age', 'like', '%' . $search . '%')
            ->orWhere('blood_group', 'like', '%' . $search . '%')
            ->orWhere('gender', 'like', '%' . $search . '%')
            ->orWhere('last_donation_date', 'like', '%' . $search . '%')
            ->paginate($perPage);
        $bloodDonor = $query ?? collect();
        return view('blood.donor', compact('bloodDonor'));
    }

    public function donor_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:100',
            'blood_group' => 'required|string|max:100',
            'gender' => 'required|in:male,female',
            'last_donation_date' => 'required|date|before_or_equal:today',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Please fix the following errors:')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            BloodDonor::create([
                'name' => $request->name,
                'age' => $request->age,
                'blood_group' => $request->blood_group,
                'gender' => $request->gender,
                'last_donation_date' => $request->last_donation_date,
            ]);

            return redirect()->back()->with('success', 'Blood donor record created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while saving the group.')
                ->withInput();
        }
    }

    public function donor_update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'age' => 'required|integer|min:18|max:100',
                'blood_group' => 'required|string|max:100',
                'gender' => 'required|in:male,female',
                'last_donation_date' => 'required|date|before_or_equal:today',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('error', 'Please fix the following errors:')
                    ->with('errorsop', $validator->errors())
                    ->withInput();
            }

            BloodDonor::where('id', $id)->update([
                'name' => $request->name,
                'age' => $request->age,
                'blood_group' => $request->blood_group,
                'gender' => $request->gender,
                'last_donation_date' => $request->last_donation_date,
            ]);

            return redirect()->back()->with('success', 'Blood donor record Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while updating the blood donor.')
                ->withInput();
        }
    }

    public function donor_delete(Request $request, $id)
    {
        try {
            $bank = BloodDonor::findOrFail($id);
            $bank->delete();
            return redirect()->back()->with('success', 'Blood donor record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the blood donor.')
                ->withInput();
        }
    }
}
