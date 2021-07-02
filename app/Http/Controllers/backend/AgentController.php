<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Host;
use App\Models\Formula;
use URL;
use Redirect;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->item_per_page = 10;
    }
    public function index(Request $request)
    {
        $q = $request->q;
        $agents = User::where(function ($query) use ($q) {
            $keyword = '%' . $q . '%';
            $query->orWhere('name', 'like', $keyword);
            $query->orWhere('email', 'like', $keyword);
        })->where('role','agent')->paginate($this->item_per_page);
        $datas = [
            'agents' => $agents
        ];
        return view('backends.agent.index',$datas)->withPageItems($this->item_per_page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('name','agent')->get();
        $datas = [
            'roles' => $roles
        ];
        return view('backends.agent.create',$datas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required',
        ]);
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['admin_id'] = Auth::User()->id;
        $data['status'] = 1;

        $agent = new User;
        $agent->fill($data);
        $agent->save();

        $agent->assignRole($request->role);


        session()->flash('error_message', [
            'type' => 'success',
            'message' => 'Agent has been created.'
        ]);

        return redirect()->route('backends.agent.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::where('name','agent')->get();
        $agent = User::findOrFail($id);
        $hosts = Host::where('user_id',$agent->id)->get();
        $formulas = Formula::where('user_id',$agent->id)->get();
        $datas = [
            'agent' => $agent,
            'roles' => $roles,
            'hosts' => $hosts,
            'formulas' => $formulas
        ];
        return view('backends.agent.edit',$datas);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|string|email|unique:users,email,'.$id,
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required',
        ]);

        $data = $request->all();
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }else{
            unset($data['password']);
        }
        $data['admin_id'] = Auth::User()->id;
        $data['status'] = 1;

        $agent = User::findOrFail($id);
        $agent->removeRole($agent->role);
        $agent->fill($data);
        $agent->save();
        $agent->assignRole($request->role);

        session()->flash('error_message', [
            'type' => 'success',
            'message' => 'Agent has been updated.'
        ]);

        return redirect()->route('backends.agent.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy(Request $request)
     {
         $agent = User::findOrFail($request->id);
         $agent->admin_id = Auth::User()->id;
         $agent->save();
         $agent->delete();
     }
}
