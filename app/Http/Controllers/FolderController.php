<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Member;
use App\Http\Resources\FolderCollection;
use App\Models\Category;
use App\Models\SubCategory;
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

    public function create()
    {
        $title = 'Creation | Dossier';
        $categories = Category::all();

        $data = [];
        foreach($categories as $key => $cat){
            $data[$key] = ['id' => $cat->id,'name' => $cat->name];
        }
        return view('folder.create',[
            'title' => $title,
            'categories' => collect($data),
        ]);
    }

    public function search($field,$value)
    {
        return response()->json(Folder::where($field,'LIKE',"%$value%")->orderBy('id','DESC')->paginate(10));
    }

    public function store(Request $request)
    {
        $this->validateFolder($request);
        $folder = new Folder();
        $folder->number = $request->number;
        $folder->description = $request->description;
        $folder->date_decret = $request->date_decret;
        $folder->date_decoration = $request->date_decoration;
        $folder->year = explode('-',$request->date_decret)[0];
        $folder->save();

        $members = [];
        foreach ($request->members as $key => $member){
            array_push($members,[
                'folder_id' => $folder->id,
                'category_id' => $member['category_id'] ? $member['category_id']['id'] : null,
                'sub_category_id' => $member['sub_category_id'] ? $member['sub_category_id']['id'] : null,
                'structure' => $member['structure'],
                'description' => $member['description'],
            ]);
        }

        Member::insert($members);
        toast()->success('Le décret a bien été créé...');
        return response()->json(['success' => true,'folder_id' => $folder->id],200);
    }

    public function edit($id)
    {
        $title = 'Modification | Dossier';
        $categories = Category::all();
        $data = Folder::with(['members'])->find($id);
        $folder_members = [];
        foreach ($data->members as $member){
            $cat = Category::find($member->category_id);
            $subCat = SubCategory::where('id',$member->sub_category_id)->first();
            array_push($folder_members,[
                'id' => $member->id,
                'category_id' => $cat,
                'sub_category_id' => $subCat,
                'description' => $member->description,
                'structure' => $member->structure
            ]);
        }
        $folder = [
            'id' => $data->id,
            'date_decret' => $data->date_decret,
            'date_decoration' => $data->date_decoration,
            'number' => $data->number,
            'description' => $data->description,
            'members' => $folder_members
        ];

        return view('folder.edit',[
            'title' => $title,
            'members' => collect($folder_members),
            'categories' => collect($categories),
            'folder' => $folder,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validateFolder($request,$id);
       
        $folder = Folder::find($id);
        $folder->number = $request->number;
        $folder->description = $request->description;
        $folder->date_decret = $request->date_decret;
        $folder->date_decoration = $request->date_decoration;
        $folder->year = explode('-',$request->date_decret)[0];
        $folder->save();

        $members = [];
        foreach ($request->members as $key => $member){
            array_push($members,[
                'folder_id' => $folder->id,
                'category_id' => $member['category_id'] ? $member['category_id']['id'] : null,
                'sub_category_id' => $member['sub_category_id'] ? $member['sub_category_id']['id'] : null,
                'structure' => $member['structure'],
                'description' => $member['description'],
            ]);
        }

        $members_del = Member::where('folder_id', $folder->id)->get()->pluck('id');
        Member::destroy($members_del);
        Member::insert($members);
        toast()->success('Le décret a bien mis à jour...');

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
            $rules['number'] = 'required|unique:folders,id,' . $id;
            $rules['date_decret'] = 'required';
            $rules['date_decoration'] = 'required';
            $rules['description'] = 'required';
        }else{
            $rules['number'] = 'required|unique:folders';
            $rules['date_decret'] = 'required';
            $rules['date_decoration'] = 'required';
            $rules['description'] = 'required';
        }
        return $request->validate($rules);
    }
}
