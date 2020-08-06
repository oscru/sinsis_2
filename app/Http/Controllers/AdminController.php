<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\UserPassword;
use App\Project;
use App\Enterprise;
use App\User;
use App\Enterview;
use App\Question;
use Symfony\Component\Console\Input\Input;

class AdminController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function dashboard()
    {
        switch (Auth::user()->access_level) {
            case 1:               
                $enterprise = Enterprise::where('client_id', Auth::user()->id)->first();                
                return view('clients', compact('enterprise'));
                break;
            case 2:
                $users = User::getUsers();
                $side_projects = Project::getProjects();
                $side_enterprises = Enterprise::getEnterprises();
                return view('admin.index', compact('side_projects', 'side_enterprises','users'));
                break;
            case 3:
                $users = User::getUsers();
                $side_projects = Project::getProjects();
                $side_enterprises = Enterprise::getEnterprises();
                return view('admin.index', compact('side_projects', 'side_enterprises','users'));
                break;
        }
    }

    public function indexProject(Request $request)
    {
        $enterprises  = Enterprise::all();
        $side_enterprises = Enterprise::getEnterprises();
        $side_projects = Project::getProjects();
        $users = User::getUsers();
        $project = Project::where('slug', $request->project_name)->first();
        return view('admin.projects.index', compact('side_projects', 'enterprises', 'side_enterprises','users', 'project'));
    }

    public function createProject(Request $request)
    {
        switch ($request->project_name != null) {
            case true:
                $project = new Project;
                $project->name = $request->project_name;
                $project->description = $request->project_description;
                $project->status = 1;
                $project->slug = Str::slug($request->project_name);
                $project->enterprise_id = $request->project_enterprise;
                $project->save();
                $user = User::where('id', Auth::user()->id)->first();
                $user->projects()->attach(['id_user' => Auth::user()->id]);
                return redirect()->route('set-project-view', $project->slug);
            case false:
                $side_projects = Project::getProjects();
                $users = User::getUsers();
                $managers = User::where('access_level', 1)->get();
                $enterprises  = Enterprise::all();
                $side_enterprises = Enterprise::getEnterprises();
                return view('admin.projects.create', compact('enterprises', 'managers', 'side_projects', 'side_enterprises','users'));
        }
    }

    public function setProject(Request $request)
    {
        $side_projects = Project::getProjects();
        $users = User::getUsers();
        $project = Project::where('slug', $request->project_name)->first();
        $enterprise = Enterprise::where('id', $project->enterprise_id)->first();
        $side_enterprises = Enterprise::getEnterprises();
        $enterviews = $project->load('enterviews');
        $users_id = [];
        foreach ($project->users as $user) {
            array_push($users_id, $user->id);
        }
        $users = User::whereNotIn('id', $users_id)
            ->where('access_level', '>=', 2)->get();
        return view('admin.projects.project', compact('project', 'side_projects', 'users', 'enterprise', 'enterviews', 'side_enterprises','users'));
    }

    public function indexEnterview()
    {
        $side_enterprises = Enterprise::getEnterprises();
        $enterview = ['Entrevista 1', 'Entrevista 2', 'Entrevista 3'];
        return view('admin/enterview/index', compact('enterview', 'side_enterprises','users'));
    }

    public function createEnterview(Request $request)
    {
        switch ($request->_token != null) {
            case true:
                $enterview = new Enterview;
                $enterview->consultor_id = Auth::user()->id;
                $enterview->id_project = $request->project;
                $enterview->save();
                $questions = array_diff($request->all(), [$request->_token, "Enviar", $request->project]);
                foreach ($questions as $key => $question) {
                    $question_id = array_keys($questions);
                    $enterview->questions()->attach(['question_id' => $question_id[$key - 1]], ['answer' => $question]);
                };
                return redirect()->back();
            case false:
                $side_enterprises = Enterprise::getEnterprises();
                $project_id = $request->project_id;
                $side_projects = Project::getProjects();
                $users = User::getUsers();
                $questions = Question::where('status', 1)->get();
                $conta = 1;
                return view('admin.enterview.create', compact('questions', 'side_projects', 'conta', 'project_id', 'side_enterprises','users'));
        }
    }

    public function indexUser()
    {        
        $side_projects = Project::getProjects();
        $users = User::getUsers();
        $side_enterprises = Enterprise::getEnterprises();
        $clients = User::where('access_level',1)->get();
        if(Auth::user()->access_level == 3){
            $managers = User::where('access_level',2)->get();
        }
        else{
            $managers = null;
        }
        return view('admin/users/index', compact('clients', 'side_projects', 'side_enterprises','users','managers'));
    }

    public function createUser(Request $request)
    {
        switch ($request->name != null) {
            case true:
                $pass = Str::random(12);
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($pass);
                $user->access_level = 1;
                $user->charge = $request->charge;
                $user->save();
                $data = [
                    'name' => $user->name,
                    'id' => $user->id,
                    'email' => $user->email,
                ];
                $message = [
                    'name' => $request->name,
                    'pass' => $pass
                ];
                //Mail::to($request->email)->queue(new UserPassword($message));
                return response()->json(array('success' => true, 'data' => $data), 200);
                break;
                // dd($user->password);
            case false:
                $side_projects = Project::getProjects();
                $users = User::getUsers();
                $side_enterprises = Enterprise::getEnterprises();
                return view('admin/users/create', compact('side_projects', 'side_enterprises','users'));
                break;
        }
    }

    public function indexDiagnostics(Request $request)
    {
        $project = Project::where('slug',$request->project_name)->first();
        $side_enterprises = Enterprise::getEnterprises();
        $side_projects = Project::getProjects();
        $users = User::getUsers();
        $diagnostics = $project->diagnostics;
        return view('admin/diagnostics/index', compact('diagnostics','project','side_projects','side_enterprises','users'));
    }

    public function createDiagnostics()
    {
        $mytime = date('d-m-Y');
        $project= $request->project_id; 
        $side_enterprises = Enterprise::getEnterprises();
        $side_projects = Project::getProjects();
        $users = User::getUsers();
        return view('admin/diagnostics/create', compact('mytime','side_enterprises','users','side_projects','project'));
        
    }
    public function storeDiagnostics(Request $request)
    {
        $extension = $request->file('file')->extension();
        
        if($extension == "pdf" || $extension=="PDF"){

            $diagnostico = new Diagnostic;
            $diagnostico->project_id = $request->project_id;
            $diagnostico->pdf_file =  $request->file('file')->store('public');
            $diagnostico->description= $request->texto;
            $diagnostico->save();

            return redirect()->back();
        }
        return redirect()->back();
        
    }

    public function indexProposals(Request $request)
    {           
        $project = Project::where('slug',$request->project_name)->first();
        $side_enterprises = Enterprise::getEnterprises();
        $side_projects = Project::getProjects();
        $users = User::getUsers();
        $proposals = $project->proposals;
        return view('admin/proposal/index', compact('proposals','project','side_projects','side_enterprises','users'));
    }

    public function indexEnterprise()
    {
        $side_projects = Project::getProjects();
        $users = User::getUsers();
        $side_enterprises = Enterprise::getEnterprises();
        $enterprises = ['empresa 1', 'empresa 2', 'empresa 3'];
        return view('admin/enterprises/index', compact('enterprises', 'side_projects', 'side_enterprises','users'));
    }

    public function createEnterprise(Request $request)
    {
        switch ($request->name != null) {
            case true:
                $enterprise = new Enterprise;
                $enterprise->name = $request->name;
                $enterprise->business_name = $request->business_name;
                $enterprise->location = $request->location;
                $enterprise->slug = Str::slug($request->name);
                $enterprise->client_id = $request->manager;
                $enterprise->save();
                $data = [
                    'name' => $enterprise->name,
                    'id' => $enterprise->id,
                ];
                return response()->json(array('success' => true, 'data' => $data), 200);
                break;
            case false:
                $side_projects = Project::getProjects();
                $users = User::getUsers();
                $side_enterprises = Enterprise::getEnterprises();
                return view('admin.enterprises.create', compact('side_projects', 'side_enterprises','users'));
                break;
        }
    }

    public function getProjectsbyUser(Request $request)
    {
        $enterprises  = Enterprise::all();
        $side_enterprises = Enterprise::getEnterprises();
        $side_projects = Project::getProjects();
        $projects = Project::projectsByAdmin($request->user_id);
        $manager = User::where('id',$request->user_id)->first();
        $users = User::getUsers();
        $project = null;
        return view('admin.projects.index', compact('side_projects', 'projects','enterprises', 'side_enterprises','users', 'project','manager'));
    }

    public function getProjectsbyEnterprise(Request $request)
    {        
        $enterprises  = Enterprise::all();
        $side_enterprises = Enterprise::getEnterprises();
        $side_projects = Project::getProjects();        
        $enterprise = $enterprises->where('slug',$request->enterprise_name)->first();
        $projects = Project::where('enterprise_id',$enterprise->id)->get();
        $manager = User::where('id',$request->user_id)->first();
        $users = User::getUsers();        
        return view('admin.projects.index', compact('side_projects', 'projects','enterprises', 'side_enterprises','users', 'manager','enterprise'));
    }
}
