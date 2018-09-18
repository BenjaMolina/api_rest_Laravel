<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'data' => $users
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'name' => 'required',
            'email' =>'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request,$reglas);

        $campos = $request->all();

        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;

        $user = User::create($campos);

        return response()->json([
            'data' => $user
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json([
            'data'=> $user
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $reglas = [
            'email' =>'email|unique:users,email,'.$user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR. ',' . User::USUARIO_REGULAR,
        ];

        $this->validate($request,$reglas);

        if($request->has('name')){
            $user->name = $request->name;
        }

        //Si cambia de email, se debe volver a verificar
        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }

        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        //Si es admin, pero no esta verificado
        if($request->has('admin')){
            if(!$user->esVerficado()){
                return response()->json([
                    'error'=>'Unicamente los usuarios verificados pueden cambiar su valor de administrador',
                    'code' => 409
                ],409);
            }
        }

        //Si no ha hecho cambios para actualizar
        if(!$user->isDirty()){
            return response()->json([
                'error'=>'Se debe especificar almenos un valor diferente para actualizar',
                'code' => 422
            ],422);
        }

        $user->save();

        return response()->json([
            'data' => $user,
        ],200);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'data' => $user,
        ],200);
    }
}
