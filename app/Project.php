<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Enterview;
use App\Diagnostic;
use App\Proposal;

class Project extends Model
{
    //

    static public function getProjects()
    {
        if (Auth::user()->access_level == 3) {
            $projects = Project::orderBy('created_at', 'desc')->take(10)->get();
        } else {
            $projects_id = \DB::table('consultants_project')->where('user_id','=',Auth::user()->id)->get();
            $id_arr = [];
            foreach ($projects_id as $key => $id) {                
                array_push($id_arr,$id->project_id);
            }            
            $projects = Project::whereIn('id', $id_arr)->orderBy('created_at', 'desc')->take(10)->get();
        }
        return $projects;
    }

    static public function projectsByAdmin($admin_id)
    {       
        $projects_id = \DB::table('consultants_project')->where('user_id','=',$admin_id)->get();
        $id_arr = [];
        foreach ($projects_id as $key => $id) {                
            array_push($id_arr,$id->project_id);
        }            
        $projects = Project::whereIn('id', $id_arr)->orderBy('created_at', 'desc')->take(10)->get();        
        return $projects;
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'consultants_project')
            ->withPivot('user_id');
    }

    public function enterviews()
    {
        return $this->hasMany('App\Enterview');
    }

    public function proposals()
    {
        return $this->hasMany('App\Proposal');
    }

    public function diagnostics()
    {
        return $this->hasMany('App\Diagnostic');
    }
}
