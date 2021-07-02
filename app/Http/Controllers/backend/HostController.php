<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Models\Host;
use App\Models\Formula;
use App\Models\User;
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
      $formulas = Formula::where('user_id',$user->id)->orderby('value','asc')->get();

      $datas = [
        'hosts' => $hosts,
        'formulas' => $formulas,
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

    public function formula(Request $request){

      $datas = $request->all();
      $datas['user_id'] = Auth::User()->id;
      $formula = Formula::find($datas['id']);
      if(empty($formula)){
        $formula = new Formula;
      }
      $formula->fill($datas);
      $formula->save();

      return $formula;
    }


    public function formulaDestroy(Request $request){
      
      $datas = $request->all();
      $formula = Formula::findOrFail($datas['id']);
      $formula->delete();
  
    }

    
    
}
