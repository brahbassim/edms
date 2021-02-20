<?php

    namespace App\Http\Controllers;

    use App\Models\Folder;
    use App\Http\Resources\FolderCollection;
    use Exception;
    use Illuminate\Http\Request;


    class FolderController extends Controller
    {

        public function index()
        {
            $title = 'Gestion des dossiers';
            if(request()->wantsJson()){
                return new FolderCollection(Folder::orderBy('created_at','DESC')->paginate(6));
            }
            return view('folder.index',['title' => $title]);
        }

        public function search($field,$value)
        {
            return response()->json(Folder::where($field,'LIKE',"%$value%")->orderBy('id','DESC')->paginate(10));
        }

        public function store(Request $request)
        {
            $this->validateFolder($request);
            $folder = new Folder();
            $folder->name = $request->name;
            $folder->description = $request->description;
            $folder->reference = $request->reference;
            $folder->save();
            return response()->json(['success' => true],200);
        }

        public function update(Request $request, $id)
        {
            $this->validateFolder($request,$id);
            $folder = Folder::find($id);
            $folder->name = $request->name;
            $folder->description = $request->description;
            $folder->reference = $request->reference;
            $folder->save();
            return response()->json(['success' => true],200);
        }

        public function destroy($id)
        {
            $folder = Folder::find($id);
            $folder->delete();
            return response()->json(['OK'],200);
        }

        private function validateFolder(Request $request,$id = null){
            $rules =  [];
            if ($id){
                $rules['reference'] = 'required|unique:folders,id,' . $id;;
                $rules['description'] = 'required';
                $rules['name'] = 'required';
            }else{
                $rules['reference'] = 'required|unique:folders';
                $rules['description'] = 'required';
                $rules['name'] = 'required';
            }
            return $request->validate($rules);
        }
    }
