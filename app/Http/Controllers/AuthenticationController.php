<?php

namespace App\Http\Controllers;

use App\Authentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Group;
use App\User;
use Illuminate\Support\Facades\DB;
use App\TeacherGroups;
use App\Message;

class AuthenticationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Authentication  $authentication
     * @return \Illuminate\Http\Response
     */
    public function show(Authentication $authentication) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Authentication  $authentication
     * @return \Illuminate\Http\Response
     */
    public function edit(Authentication $authentication) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Authentication  $authentication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Authentication $authentication) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Authentication  $authentication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Authentication $authentication) {
        //
    }

    public function auth(Request $request) {
        try {
            /**
             * traer aythenticacion
             */
            $auth = Authentication::where('correo', $request->correo)->firstOrFail();

            /**
             * verificar
             */
            if (Hash::check($request->contrasena, $auth->contrasena)) {
                switch ((int) $auth->id_rol) {
                    case 1:
                        return response()->json(['mensaje' => 'hola'], 200);
                        break;
                    case 2:
                        $grupos = TeacherGroups::all()->where('id_autorizacion', $auth->id);
                        $groups = [];
                        foreach ($grupos as $value) {
                            $tmp = Group::where('id', $value->id_grupo)->first();
                            array_push($groups, $tmp);
                        }
                        $part = [];
                        foreach ($groups as $value) {
                            $temp = Authentication::all()->where('id_grupo', $value->id);
                            $a = [];
                            foreach ($temp as $value2) {
                                $t = User::where('id', $value2->id_usuario)->first();
                                $t['id_grupo'] = $value->id;
                                array_push($a, $t);
                            }
                            $g = TeacherGroups::all()->where('id_grupo', $value->id);
                            foreach ($g as $value3) {
                                $tea = Authentication::where('id', $value3->id_autorizacion)->first();
                                $te = User::where('id', $tea->id_usuario)->first();
                                $te['id_grupo'] = $value->id;
                                array_push($a, $te);
                            }
                            $part = $a;
                        }
                        return response()->json(['grupos' => $groups, 'participantes' => $part, 'message' => 'success', 'token' => $auth->id_rol, 'usuario' => User::where('id', $auth->id_usuario)->first()], 200);
                        break;
                    case 3:
                        $group = Group::where('id', $auth->id_grupo)->first();
                        $us = DB::table('authentications')->select('id_usuario')->where('id_grupo', $group->id)->get();
                        $users = [];
                        foreach ($us as $value) {
                            if ($value->id_usuario !== $auth->id_usuario) {
                                $tmp = User::where('id', (int) $value->id_usuario)->first();
                                array_push($users, $tmp);
                            }
                        }
                        $th = TeacherGroups::all()->where('id_grupo', $group->id);
                        $temp = [];
                        foreach ($th as $value) {
                            $us = DB::table('authentications')->select('id_usuario')->where('id', $value->id_autorizacion)->get();
                            array_push($temp, $us[0]);
                        }
                        foreach ($temp as $value) {
                            $tmp = User::where('id', (int) $value->id_usuario)->first();
                            array_push($users, $tmp);
                        }

                        return response()->json(['message' => 'Success', 'grupo' => $group, 'participantes' => $users, 'usuario' => User::where('id', $auth->id_usuario)->first(), 'mensajes' => Message::all()->where('id_grupo', $group->id), 'token' => $auth->id_rol], 200);
                        break;
                }
            } else {
                return response()->json(['mensaje' => 'Datos incorrectos'], 401);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exc) {
            return response()->json(['mensaje' => $exc->getMessage()], 401);
        }
    }

    public function forgout(Request $request) {
        $auth = Authentication::where('id_usuario', $request->id)->first();
        if (Hash::check($request->contrasena_actual, $auth->contrasena)) {
            $auth->contrasena = Hash::make($request->contrasena_nueva);
            if ($auth->save()) {
                return response()->json(['message' => 'Actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Ocurrio un problema'], 500);
            }
        } else {
            return response()->json(['message' => 'Contraseña incorrecta'], 500);
        }
    }

}
