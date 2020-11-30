<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \App\Models\Todo;
use \App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(request('type') === "priority" || request('type') === "created_at"){
            $type = request('type');
        }else{
            $type = "priority";
        }
        if(request('order') === "DESC" || request('order') === "ASC"){
            $order = request('order');
        }else{
            $order = "DESC";
        }
        /*
            La lista è "ordinata" anche sulla colonna "completed" in modo da avere le task completate sempre in fondo
        */ 
        $todos = User::find(Auth::id())->todos()->orderBy('completed', 'ASC')->orderBy($type, $order)->get();
        return view('home', compact('todos', 'type', 'order'));
    }

    public function store(){
        $user = Auth::user();
        if (request()->user_id != $user->id){
            return back();
        }
        $user ->addTodo(
            request()->validate(['description'=>'required', 'title'=>'required', 'user_id'=>'required', 'priority'=>['required', 'regex:/(^(1|2|3){1}$)/u']])
        );
        return back();
    }
    public function update($id){
        User::find(Auth::id())->todos()->findOrFail($id)->update(['completed'=> request()->has('completed')]);
        return back();   
    }

    public function destroy($id){
        User::find(Auth::id())->todos()->findOrFail($id)->delete();
        return back();
    }
}
