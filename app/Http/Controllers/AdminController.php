<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use Redirect;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:can-query']);
    }
    
    public function users()
    {
        $users = User::all();
        
        return view('admin.users')->with('users', $users);
    }
    
    public function suspendUser($id)
    {
        $user = User::find($id);
        
        $user->suspended = 1;
        $user->save();
        
        // TODO: Unpublish any listings they have
        
        // TODO: Cancel any future bookings they have
        
        // TODO: Cancel any future bookings their listings have
        
        return Redirect::route('admin-users');
    }
    
    public function unsuspendUser($id)
    {
        $user = User::find($id);
        
        $user->suspended = 0;
        $user->save();
        
        return Redirect::route('admin-users');
    }
    
    public function query()
    {
        
        return view('admin.query');
    }
    
    public function runQuery(Request $request)
    {
        \Debugbar::disable();
        $input = $request->all();
        try{
            $results = DB::connection('mysql_select')->select($input['query'], [1]);
        }
        catch(\Exception $e){
            return view('admin.query')->with('results', $e->getMessage()); 
        }
        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        
        // Headers
        $variables = get_object_vars($results[0]);
        $array = array ();
        foreach ( $variables as $key => $value ) {
            $array[] = $key;
        }
        $csv->insertOne($array);
        
        foreach ($results as $result) {
            $csv->insertOne( $this->toArray($result) );
        }
        
        $csv->output('report-'.date('d-m-Y-u').'.csv');
        
        //return view('admin.query')->with('results', $results);
        
    }
    
    private function toArray($obj) {
        $vars = get_object_vars ( $obj );
        $array = array ();
        foreach ( $vars as $key => $value ) {
            $array [ltrim ( $key, '_' )] = $value;
        }
        return $array;
    }
}
