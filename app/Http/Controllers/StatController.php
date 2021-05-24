<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Folder;
use App\Models\Member;
use App\Models\SubCategory;

class StatController extends Controller
{
    /**
     * Show stat page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'Liste des statistiques';
        $categories = Category::all();

            $data = [];
            foreach($categories as $key => $cat){
                $data[$key] = ['id' => $cat->id,'name' => $cat->name];
            }
        
        return view('stat.index',[
            'title' => $title,
            'categories' => collect($data)
        ]);
    }

    public function search($id, $date)
    {
        
        $folders = null;
        $total = 0;
        $data = [];

        if($date != "null"){
            $folders = Folder::where('year','LIKE', '%'.$date.'%')->get();

            $subCategories = SubCategory::where('category_id',$id)->with('category')->get();
            $subTotal = Member::where('category_id',$id)->get();
            

            foreach($subCategories as $k => $subCat){
                $members = Member::whereIn('folder_id',$folders->pluck('id'))->where('sub_category_id',$subCat->id)->get();
                array_push($data,[
                    'grade' => $subCat->name,
                    'number' => $members != null ? $members->count() : 0
                ]);
            }

            $response =  [
                'data' => $data,
                'total' => $subTotal != null ? $subTotal->count() : 0
            ];

            return $response;

        }else{
            
            $subCategories = SubCategory::where('category_id',$id)->with('category')->get();
            $subTotal = Member::where('category_id',$id)->get();

            foreach($subCategories as $k => $subCat){
                $members = Member::where('sub_category_id',$subCat->id)->get();
                array_push($data,[
                    'grade' => $subCat->name,
                    'number' => $members != null ? $members->count() : 0
                ]);
            }

            $response =  [
                'data' => $data,
                'total' => $subTotal != null ? $subTotal->count() : 0
            ];

            return $response;
        }
    }
    
}
