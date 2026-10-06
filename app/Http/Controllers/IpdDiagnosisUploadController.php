<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\IpdDiagnosisUpload;
use App\Models\IpdPatient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IpdDiagnosisUploadController extends Controller
{
    public function index($ipdPatientId)
    {
        $diagnosisUploads = IpdDiagnosisUpload::where('ipd_patient_id', $ipdPatientId)
            ->orderBy('report_date', 'desc')
            ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $diagnosisUploads
        ]);
    }

    public function store(Request $request, $ipdPatientId)
    {
        $request->validate([
            'report_type' => 'required|string|max:255',
            'report_date' => 'required|date',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            'description' => 'nullable|string'
        ]);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('diagnosis_documents', 'public');
        }

        $diagnosisUpload = IpdDiagnosisUpload::create([
            'ipd_patient_id' => $ipdPatientId,
            'report_type' => $request->report_type,
            'report_date' => $request->report_date,
            'document' => $documentPath,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Diagnosis report uploaded successfully',
            'data' => $diagnosisUpload
        ]);
    }

    public function destroy($id)
    {
        $diagnosisUpload = IpdDiagnosisUpload::findOrFail($id);
        
        if ($diagnosisUpload->document) {
            Storage::disk('public')->delete($diagnosisUpload->document);
        }
        
        $diagnosisUpload->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Diagnosis report deleted successfully'
        ]);
    }

    public function download($id)
    {
        // dd($id);
        $diagnosisUpload = IpdDiagnosisUpload::findOrFail($id);
        
        if (!$diagnosisUpload->document) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        return Storage::disk('public')->download($diagnosisUpload->document);
    }
}