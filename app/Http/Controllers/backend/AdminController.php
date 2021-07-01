<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\User;
use URL;
use Redirect;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class AdminController extends Controller
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
        $admins = User::where(function ($query) use ($q) {
            $keyword = '%' . $q . '%';
            $query->orWhere('name', 'like', $keyword);
            $query->orWhere('email', 'like', $keyword);
        })->paginate($this->item_per_page);
        $datas = [
            'admins' => $admins
        ];
        return view('backends.admin.index',$datas)->withPageItems($this->item_per_page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $datas = [
            'roles' => $roles
        ];
        return view('backends.admin.create',$datas);
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

        $admin = new User;
        $admin->fill($data);
        $admin->save();

        $admin->assignRole($request->role);


        session()->flash('error_message', [
            'type' => 'success',
            'message' => 'Admin has been created.'
        ]);

        return redirect()->route('backends.admin.index');
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
        $roles = Role::all();
        $admin = User::findOrFail($id);
        $datas = [
            'admin' => $admin,
            'roles' => $roles
        ];
        return view('backends.admin.edit',$datas);
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

        $admin = User::findOrFail($id);
        $admin->removeRole($admin->role);
        $admin->fill($data);
        $admin->save();
        $admin->assignRole($request->role);

        session()->flash('error_message', [
            'type' => 'success',
            'message' => 'Admin has been updated.'
        ]);

        return redirect()->route('backends.admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function destroy(Request $request)
     {
         $admin = User::findOrFail($request->id);
         $admin->admin_id = Auth::User()->id;
         $admin->save();
         $admin->delete();
     }
}
