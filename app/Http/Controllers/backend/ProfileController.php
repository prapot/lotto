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

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit($id)
    {
        $roles = Role::all();
        $admin = User::findOrFail($id);
        $datas = [
            'admin' => $admin,
            'roles' => $roles
        ];
        return view('backends.profile.edit',$datas);
    }

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
            'message' => 'Profile has been updated.'
        ]);

        return redirect()->route('backends.profile.edit',['id' => $id ]);
    }


}
