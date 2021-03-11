<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Folder;
use App\Models\Document;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class DashboardController extends Controller
{
    /**
     * Show dashboard page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'Tableau de bord';
        $folders_nbr = Folder::all()->count();
        $documents_nbr = Document::all()->count();
        $users_nbr = User::all()->count();
        
        return view('dashboard.index',[
            'title' => $title,
            'folders_nbr' => $folders_nbr,
            'documents_nbr' => $documents_nbr,
            'users_nbr' => $users_nbr
        ]);
    }
    
    public function search(Request $request)
    {

        $folders = [];
        $documents = [];


        if($request->where=="description"){
            //Fullname
            $members = Member::where($request->where,'LIKE', '%'.$request->search.'%')->with('category')->with('subCategory')->get();
            $folders_id = $members->pluck('folder_id');
            $documents = Document::whereIn('folder_id',$folders_id)->with('folder')->get();

            $data = [];

            for($i=0;$i<count($members);$i++){
                $f = Folder::where('id', $members[$i]->folder_id)->first();
                $d = Document::where('folder_id', $members[$i]->folder_id)->first();
                array_push($data,[
                    'number' => $f->number,
                    'date_decret' => $f->date_decret,
                    'date_decoration' => $f->date_decoration,
                    'file' => $d->file,
                    'decoration' => $members[$i]->category->name,
                    'grade' => $members[$i]->subCategory ? $members[$i]->subCategory->name : '----------',
                    'structure' => $members[$i]->structure ? $members[$i]->structure : '----------',
                ]);
            }
        }

        //Date Decret
        if($request->where=="date_decret"){
            //Fullname
            $folders = Folder::where('year','LIKE', '%'.$request->search.'%')->get()->pluck('id');
            $documents = Document::whereIn('folder_id',$folders)->with('folder')->get();

            $data = [];

            for($j=0;$j<count($documents);$j++){
                array_push($data,[
                    'number' => $documents[$j]->folder->number,
                    'date_decret' => $documents[$j]->folder->date_decret,
                    'date_decoration' => $documents[$j]->folder->date_decoration,
                    'file' => $documents[$j]->file,
                    'decoration' => '----------',
                    'grade' => '----------',
                    'structure' => '----------',
                ]);
            }
        }

        //Decret
        if($request->where=="number"){
            //Fullname
            $folders = Folder::where($request->where,'LIKE', '%'.$request->search.'%')->get()->pluck('id');
            $documents = Document::whereIn('folder_id',$folders)->with('folder')->get();

            $data = [];
            
            for($j=0;$j<count($documents);$j++){
                array_push($data,[
                    'number' => $documents[$j]->folder->number,
                    'date_decret' => $documents[$j]->folder->date_decret,
                    'date_decoration' => $documents[$j]->folder->date_decoration,
                    'file' => $documents[$j]->file,
                    'decoration' => '----------',
                    'grade' => '----------',
                    'structure' => '----------',
                ]);
            }
        }
        
        return $data;
    }
}
