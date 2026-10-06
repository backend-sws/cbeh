<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Document;
use App\Models\DocumentType;


class DocumentController extends Controller
{
    public function document_index(Request $request)
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $query = Document::query();
        $query =  $query->where('doc_type', 'like', '%' . $search . '%')
                  ->orWhere('patient', 'like', '%' . $search . '%')
                  ->orWhere('attachment', 'like', '%' . $search . '%')
                  ->paginate($perPage);
        $documents = $query ?? collect();
        return view('document.doc',compact('documents'));
    }

    public function document_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'doc_type' => 'required|string|max:255',
            'patient' => 'required|string|max:255',
            'attachment' => 'required|string|max:255',
            'notes' => 'required|string|max:500',
        ]);
        

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Please fix the following errors:')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            Document::create([
            'title' =>$request->title,
            'doc_type' =>$request->doc_type,
            'patient' =>$request->patient,
            'attachment' =>$request->attachment,
            'notes' =>$request->notes    
            ]);

            return redirect()->back()->with('success', 'Document record created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while saving the document.')
                ->withInput();
        }
    }   

    public function document_update(Request $request,$id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'doc_type' => 'required|string|max:255',
                'patient' => 'required|string|max:255',
                'attachment' => 'required|string|max:255',
                'notes' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('error', 'Please fix the following errors:')
                    ->with('errorsop', $validator->errors())
                    ->withInput();
            }

            Document::where('id',$id)->update([
                'title' =>$request->title,
                'doc_type' =>$request->doc_type,
                'patient' =>$request->patient,
                'attachment' =>$request->attachment,
                'notes' =>$request->notes
            ]);

            return redirect()->back()->with('success', 'Document record Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while updating the document.')
                ->withInput();
        }
    }

    public function document_delete(Request $request,$id){
        try{
        $document=Document::findOrFail($id);
        $document->delete();
        return redirect()->back()->with('success', 'Document record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the document.')
                ->withInput();
        }
    }

    public function document_type_index(Request $request)
    {
        $perPage = request()->input('perPage', 10);
        $search = request()->input('search', '');
        $query = DocumentType::query();
        $query =  $query->where('doc_type', 'like', '%' . $search . '%')
                  ->orWhere('formate', 'like', '%' . $search . '%')
                  ->paginate($perPage);
        $document_types = $query ?? collect();
        return view('document.doctype',compact('document_types'));
    }

    public function document_type_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'formate' => 'required|string|max:255',
            'doc_type' => 'required|string|max:255',
        ]);
        

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Please fix the following errors:')
                ->with('errorsop', $validator->errors())
                ->withInput();
        }

        try {
            DocumentType::create([
            'formate' =>$request->formate,
            'doc_type' =>$request->doc_type,
            ]);

            return redirect()->back()->with('success', 'Document type record created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while saving the document type.')
                ->withInput();
        }
    }   

    public function document_type_update(Request $request,$id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'formate' => 'required|string|max:255',
                'doc_type' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('error', 'Please fix the following errors:')
                    ->with('errorsop', $validator->errors())
                    ->withInput();
            }

            DocumentType::where('id',$id)->update([
               'formate' =>$request->formate,
                'doc_type' =>$request->doc_type,
            ]);

            return redirect()->back()->with('success', 'Document type record Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while updating the document type.')
                ->withInput();
        }
    }

    public function document_type_delete(Request $request,$id){
        try{
        $document=DocumentType::findOrFail($id);
        $document->delete();
        return redirect()->back()->with('success', 'Document Type record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the document type.')
                ->withInput();
        }
    }
}
