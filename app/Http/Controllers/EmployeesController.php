<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;

class EmployeesController extends Controller
{
    public function index()
    {   
        $errorFound = false;
        $error = ['error' => 'No Results Found'];
        $users = User::whereHas('role', function ($query) {
            $query->where('role_name', 'employee');
        });
        if (request()->has('q')) {
            $keyword = '%'.request()->get('q').'%';
            $builder = $users;
            $builder = $builder->where('name', 'like', $keyword);
            $builder->count() ? $users = $builder : $errorFound = true;
        }
       return $errorFound === false ? UserResource::collection($users->latest()->paginate(5)) : $error;
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = 2;
        $user->password = Hash::make($request->input('password'));
        if($user->save()){
            return new UserResource($user);
        }
    }
    public function show($id)
    {
        $user = User::findOrFail($id); 
        if($user->role->role_name == 'employee') {
            return new UserResource($user);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => 'min:6',
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = 2;
        if($request->input('password')){
            $user->password = Hash::make($request->input('password'));
        }
        if($user->save()){
            return new UserResource($user);
        }
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return new UserResource($user);
    }
}
