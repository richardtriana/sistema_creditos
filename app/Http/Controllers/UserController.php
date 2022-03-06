<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:user.index', ['only' => ['index']]);
        $this->middleware('permission:user.store', ['only' => ['store','register']]);
        $this->middleware('permission:user.update', ['only' => ['update']]);
        $this->middleware('permission:user.delete', ['only' => ['destroy']]);
        $this->middleware('permission:user.status', ['only' => ['changeStatus']]);
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'code' =>  400,
                'message' => 'Validación de datos incorrecta',
                'errors' =>  $validate->errors()
            ], 400);
        }

        if(strpos($request->username, "@")){
            $condition = [
                'validate' => 'string|exists:users,email',
                'where' => 'email'
            ];
        }else{
            $condition = [
                'validate' => 'alpha_num|exists:users',
                'where' => 'username'
            ];
        }

        $validateAccess = Validator::make($request->all(), [
            'username' => $condition['validate'],
        ]);

        if ($validateAccess->fails()) {
            return response()->json([
                'status' => 'error',
                'code' =>  400,
                'message' => 'Validación de datos incorrecta',
                'errors' =>  $validateAccess->errors()
            ], 400);
        }

        $user = User::where($condition['where'], $request->input('username'))->first();

        if (is_object($user) && $user->status) {
            $validatePassword = Hash::check($request->input('password'), $user->password);

            if ($validatePassword) {
                $token = Str::random(80);
                $user->api_token = hash('sha256', $token);
                $user->update();

                $data = [
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Login correcto',
                    'user' => [
                        'sub' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'username' => $user->username,
                        'permissions' => $user->getAllPermissions(),
                        'iat' => time(),
                        'exp' => time() + (7 * 60),
                        'api_token' =>  $token
                    ]
                ];
            } else {
                $data = [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Contraseña incorrecta'
                ];
            }

            
        }else{
            $data = [
                'status' => 'error',
                'code' => 400,
                'message' => 'Usuario desactivado'
            ];
        }

        return response()->json($data, $data['code']);
    }
    
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
        $users = $users->paginate(10);

        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'headquarter_id' => 'required|integer|exists:headquarters,id',
            'name' => 'required|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            'email' => 'required|email:rfc,dns|unique:users|max:255',
            'username' => 'required|alpha_num|unique:users|min:4|max:25',
            'password' => 'required|confirmed|min:8',
            'phone' => 'nullable|numeric|between:9999,999999999999',
            'address' => 'nullable|string|min:1|max:255',
            'type_document' => 'nullable|in:CC,CE,NIT,PP,TI',
            'document' => 'nullable|numeric|between:9999,999999999999',
            'rol' => 'required|integer|exists:roles,id'
        ]);


        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'code' =>  500,
                'message' => 'Validación de datos incorrecta',
                'errors' =>  $validate->errors()
            ], 500);
        }

        $user = new User();
        $user->headquarter_id = $request['headquarter_id'];
        $user->email = $request['email'];
        $user->username = $request['username'];
        $user->password = Hash::make($request['password']);
        $user->name = $request['name'];
        $user->last_name = $request['last_name'];
        $user->phone = $request['phone']; 
        $user->address = $request['address'];
        $user->type_document = $request['type_document'];
        $user->document = $request['document'];
        $user->photo = 'undefined';
        $user->save();

        $user->syncRoles($request->input('rol'));

        return response()->json([
            'status' => 'success',
            'code' =>  200,
            'message' => 'Registro exitoso',
            'user' => $user
        ], 200);
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

        $validate = Validator::make($request->all(), [
            'headquarter_id' => 'required|integer|exists:headquarters,id',
            'name' => 'required|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            'email' => 'required|email:rfc,dns|max:255', Rule::unique('users')->ignore($user->email),
            'username' => 'required|alpha_num|min:4|max:25', Rule::unique('users')->ignore($user->username),
            'password' => 'nullable|confirmed|min:8',
            'phone' => 'nullable|numeric|between:9999,999999999999',
            'address' => 'nullable|string|min:1|max:255',
            'type_document' => 'nullable|in:CC,CE,NIT,PP,TI',
            'document' => 'nullable|numeric|between:9999,999999999999',
            'rol' => 'required|integer|exists:roles,id'
        ]);


        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'code' =>  500,
                'message' => 'Validación de datos incorrecta',
                'errors' =>  $validate->errors()
            ], 500);
        }

        $user->headquarter_id = $request['headquarter_id'];
        $user->email = $request['email'];
        $user->username = $request['username'];
        $user->name = $request['name'];
        $user->last_name = $request['last_name'];
        $user->phone = $request['phone']; 
        $user->address = $request['address'];
        $user->type_document = $request['type_document'];
        $user->document = $request['document'];
        $user->photo = 'undefined';

        if(!is_null($request['password'])){
            $user->password = Hash::make($request['password']);
        }
        
        $user->update();


        $user->syncRoles($request->input('rol'));

        return response()->json([
            'status' => 'success',
            'code' =>  200,
            'message' => 'Actualización  exitoso',
            'user' => $user
        ], 200);
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
