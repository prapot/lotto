<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\Host;
use Auth;

class HostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function host(){

      $user = Auth::User();
      $hosts = Host::where('user_id',$user->id)->get();

      $datas = [
        'hosts' => $hosts
      ];

      return view('backends.host.index',$datas);

    }

    public function store(Request $request){

      $datas = $request->all();
      $datas['user_id'] = Auth::User()->id;
      $host = new Host;
      $host->fill($datas);
      $host->save();
  
    }
    
    public function destroy(Request $request){
      
      $datas = $request->all();
      $host = Host::findOrFail($datas['id']);
      $host->delete();
  
    }
    
}
