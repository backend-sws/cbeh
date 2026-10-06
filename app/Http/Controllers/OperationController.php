<?php

namespace App\Http\Controllers;

use App\Models\OpdDoctorCharge;
use App\Models\Operation;
use App\Models\OperationCategory;
use App\Models\OperationName;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class OperationController extends Controller
{
   public function opnamelist($id){
$Operation=Operation::with('operation_name','operation_category')->where('patient_id',$id)->get();
return response()->json(['data'=>$Operation]);
    }
 public function   getOperationsByCategory( $category_id){
$operations = \App\Models\OperationName::where('category_id', $category_id)->get();

    return response()->json($operations);
    }

public function opnemestore(Request $request){
    try {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'operation_id' => 'required',
            'operation_date' => 'required',
            'doctor_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Some validation errors occurred!',
                'errors' => $validator->errors(),
                'status_code' => 422
            ], 422);
        }
 $data = $request->except('_token');
        $data['reference_id'] = 'REF-' . strtoupper(Str::random(8)); // or use custom logic

        Operation::create($data);

        return response()->json([
            'message' => 'Operation saved successfully!',
            'status_code' =>1
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'An error occurred while saving the operation!',
            'errors' => $e->getMessage(),
            'status_code' => 500
        ]);
    }
}
public function opnemedeletecharge($id){
    try{
        Operation::findOrFail($id)->delete();
      return response()->json([
            'message' => 'Operation Delete successfully!',
            'status_code' =>1
        ]);   

    }catch(\Throwable $e) {
        return response()->json([
            'message' => 'An error occurred while Delete the operation!',
            'errors' => $e->getMessage(),
            'status_code' => 500
        ]);
    }
}
public function opnemeupdate(Request $request, $id)
{
    try {
        // Validate input
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'operation_id' => 'required',
            'operation_date' => 'required',
            'doctor_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
                'status_code' => 422
            ], 422);
        }

        // Find the operation by ID
        $operation = Operation::findOrFail($id);

        // Update operation fields
        $operation->update([
            'category_id' => $request->category_id,
            'operation_id' => $request->operation_id,
            'operation_date' => $request->operation_date,
            'doctor_id' => $request->doctor_id,
            'assistant_consultant1' => $request->assistant_consultant1,
            'assistant_consultant2' => $request->assistant_consultant2,
            'anesthetist' => $request->anesthetist,
            'anesthesia_type' => $request->anesthesia_type,
            'ot_technician' => $request->ot_technician,
            'ot_assistant' => $request->ot_assistant,
            'remark' => $request->remark,
            'result' => $request->result,
        ]);

        return response()->json([
            'message' => 'Operation updated successfully!',
            'status_code' => 1
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'An error occurred while updating the operation!',
            'errors' => $e->getMessage(),
            'status_code' => 500
        ]);
    }
}
public function operationCAtegoryAdd(Request $request){
$operation=new OperationCategory();
$operation->name=$request->name;
$operation->save();
    return response()->json([
            'message' => 'Operation category Add successfully!',
            'status_code' => 1
        ]);
}
    
public function operationNameAdd(Request $request){

    $operation=new OperationName();
    $operation->category_id=$request->category_id;
$operation->name=$request->name;
$operation->save();
    return response()->json([
            'message' => 'Operation Name successfully!',
            'status_code' => 1
        ]);
}
  

public function operationCatList(){
$operations = OperationCategory::all();

    return response()->json($operations);
}
public function operationNameList(){
$operations =OperationName::all();

    return response()->json($operations);
}
public function operationCatDelete($id){
    try{
$category=OperationCategory::findOrFail($id)->delete();
 return response()->json([
            'message' => 'Operation Category Delete successfully!',
            'status_code' => 1
        ]);
    }
    catch (\Throwable $e) {
        return response()->json([
            'message' => 'An error occurred while Delete the operation!',
            'errors' => $e->getMessage(),
            'status_code' => 500
        ]);
    }
    
    

}
public function operationNameDelete($id){
    try{
$category=OperationName::findOrFail($id)->delete();
 return response()->json([
            'message' => 'Operation Name Delete successfully!',
            'status_code' => 1
        ]);
    }
    catch (\Throwable $e) {
        return response()->json([
            'message' => 'An error occurred while Delete the operation!',
            'errors' => $e->getMessage(),
            'status_code' => 500
        ]);
    }
}
}
