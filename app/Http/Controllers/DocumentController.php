<?php

    namespace App\Http\Controllers;

    use App\Models\Document;
    use App\Osaas\Upload;
    use Exception;
    use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
    {
        public function index($id)
        {
            $title = 'Gestion des documents';
            if(request()->wantsJson()){
                return collect(Document::orderBy('created_at','DESC')->paginate(10));
            }
            return view('document.index',['title' => $title,'id' => $id]);
        }

        public function search($folder_id, $field,$value)
        {
            return response()->json(Document::where($field,'LIKE',"%$value%")->where('folder_id',$folder_id)->orderBy('id','DESC')->paginate(10));
        }

        public function download()
        {
            $file= public_path(). "/download/info.pdf";
            $headers = array('Content-Type: application/pdf',);

            return response()->download($file, 'filename.pdf', $headers);

        }

        public function store(Request $request,$folder_id)
        {
            $this->validateDocument($request);
            $document = new Document();
            $document->name = $request->name;
            $document->folder_id = $folder_id;
            $document->description = $request->description;
           
            if ($request->hasFile('doc')) {
                // filename with extension
                $fileNameWithExt = $request->file('doc')->getClientOriginalName();
                // filename
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // extension
                $extension = $request->file('doc')->getClientOriginalExtension();
                // filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // upload file
                $path = $request->file('doc')->storeAs('documents/'.$folder_id, $fileNameToStore);
            }

            $document->file = $path;
            $document->mimetype = Storage::mimeType($path);
            $size = Storage::size($path);
            if ($size >= 1000000) {
            $document->filesize = round($size/1000000) . 'MB';
            }elseif ($size >= 1000) {
            $document->filesize = round($size/1000) . 'KB';
            }else {
            $document->filesize = $size;
            }

            $document->save();
            return response()->json(['success' => true],200);
        }

        public function update(Request $request, $folder_id,$file_id)
        {
            $this->validateDocument($request,$file_id);
            $document = Document::find($file_id);
            $document->folder_id = $folder_id;
            $document->name = $request->name;
            $document->description = $request->description;
           
            if ($request->hasFile('doc')) {
                // filename with extension
                $fileNameWithExt = $request->file('doc')->getClientOriginalName();
                // filename
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // extension
                $extension = $request->file('doc')->getClientOriginalExtension();
                // filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // upload file
                $path = $request->file('doc')->storeAs('documents/'.$folder_id, $fileNameToStore);
            }

            $document->file = $path;
            $document->mimetype = Storage::mimeType($path);
            $size = Storage::size($path);
            if ($size >= 1000000) {
            $document->filesize = round($size/1000000) . 'MB';
            }elseif ($size >= 1000) {
            $document->filesize = round($size/1000) . 'KB';
            }else {
            $document->filesize = $size;
            }
            $document->save();
            return response()->json(['success' => true],200);
        }

        public function destroy($id)
        {
            $document = Document::find($id);
            $document->delete();
            return response()->json(['OK'],200);
        }

        private function validateDocument(Request $request,$id = null){
            $rules =  [];
            if ($id){
                $rules['name'] = 'required|unique:documents,id,' . $id;
                $rules['doc'] = 'nullable';
                $rules['description'] = 'required';
               
            }else{
                $rules['name'] = 'required|unique:documents';
                $rules['doc'] = 'required';
                $rules['description'] = 'required';
            }
            return $request->validate($rules);
        }
    }
