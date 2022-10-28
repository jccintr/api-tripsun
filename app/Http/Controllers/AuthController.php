<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth:api', ['except' => ['login','register','list','getById','update']]);
  }

//=============================================================
//=============================================================
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);
    $credentials = $request->only('email', 'password');

    $token = Auth::attempt($credentials);
    if (!$token) {
        return response()->json([
            'status' => 'error',
            'message' => 'Usuário e ou senha inválidos',
        ], 401);
    }

    $user = Auth::user();
    return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ],200);

}
//=============================================================
//=============================================================
public function register(Request $request){

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'password' => 'required',
    ]);

    if(!$validator->fails()) {

        $name = $request->input('name');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $password = $request->input('password');
        $emailExists = User::where('email', $email)->count();
        if($emailExists === 0) {

            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ],201);


        } else {

            return response()->json([
                'status' => 'error',
                'message' => 'Email já cadastrado',
                 ],309);

        }
    } else {

        return response()->json([
            'status' => 'error',
            'message' => 'Dados inválidos',
             ],400);
    }


}
//=============================================================
//=============================================================
    public function registerX(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
          //  'password' => 'required|string|min:6',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ],201);
    }
//=============================================================
//=============================================================
public function logout()
{
    Auth::logout();
    return response()->json([
        'status' => 'success',
        'message' => 'Successfully logged out',
    ],200);
}
//=============================================================
//=============================================================
public function refresh()
{
    return response()->json([
        'status' => 'success',
        'user' => Auth::user(),
        'authorisation' => [
            'token' => Auth::refresh(),
            'type' => 'bearer',
        ]
    ],200);
}

//=============================================================
//=============================================================
public function list()
{
  $usuarios = User::all();
  return response()->json($usuarios,200);
}

//=============================================================
//=============================================================
public function getById($id){

  $usuario = User::find($id);

 if ($usuario === null){
    return response()->json(['erro'=>'Usuario não encontrada'],404);
 } else {
    return response()->json($usuario,200);
 }

}
//=============================================================
//=============================================================
public function update($id,Request $request){


  $name = $request->name;
  $phone = $request->phone;

  if($name  && $phone) {
      $usuario = User::find($id);
      $usuario->name = $name;
      $usuario->phone = $phone;
      $usuario->save();
      return response()->json($usuario,200);
  } else {
    $array['erro'] = "Campos obrigatórios não informados.";
    return response()->json($array,400);
  }


}

}
