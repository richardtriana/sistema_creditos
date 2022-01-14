<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::select();
        if ($request->user && ($request->user != '')) {
            $users  =     $users->where('document', 'LIKE', "%$request->user%")
                ->orWhere('name', 'LIKE', "%$request->user%")
                ->orWhere('email', 'LIKE', "%$request->user%");
        }
        $users = $users->paginate(5);

        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->headquarter_id = $request['headquarter_id'];
        $user->email = $request['email'];
        $user->password = $request['password'];
        $user->name = $request['name'];
        $user->last_name = $request['last_name'];
        $user->phone = $request['phone'];
        $user->address = $request['address'];
        $user->type_document = $request['type_document'];
        $user->document = $request['document'];
        $user->photo = 'undefined';
        $user->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = User::find($request->id);
        $user->headquarter_id = $request['headquarter_id'];
        $user->rol_id = $request['rol_id'];
        $user->email = $request['email'];
        $user->password = $request['password'];
        $user->name = $request['name'];
        $user->last_name = $request['last_name'];
        $user->phone = $request['phone'];
        $user->address = $request['address'];
        $user->type_document = $request['type_document'];
        $user->document = $request['document'];
        $user->photo = 'undefindef';
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        User::destroy($id);
        return redirect('user')->with('mensaje', 'User eliminado correctamente');
    }

    public function changeStatus(User $user)
    {
        //
        $u = User::find($user->id);
        $u->status = !$u->status;
        $u->save();
    }
}
