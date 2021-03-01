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
            $folders_id = Member::where($request->where,'LIKE', '%'.$request->search.'%')->get()->pluck('folder_id');
            $documents = Document::whereIn('folder_id',$folders_id)->with('folder')->get();
        }

        //Date Decret
        if($request->where=="date_decret"){
            //Fullname
            $folders = Folder::where('year','LIKE', '%'.$request->search.'%')->get()->pluck('id');
            $documents = Document::whereIn('id',$folders)->with('folder')->get();
        }

        //Decret
        if($request->where=="number"){
            //Fullname
            $folders = Folder::where($request->where,'LIKE', '%'.$request->search.'%')->get()->pluck('id');
            $documents = Document::whereIn('id',$folders)->with('folder')->get();
        }
        
        return $documents;
    }
}
