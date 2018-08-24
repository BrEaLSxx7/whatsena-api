<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller {

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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        //
    }

    public function img(Request $request) {
        if ($request->hasFile('foto')) {
            $nameimagen = time() . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move('./../files/img/', $nameimagen);
            $user = User::where('id', $request->id)->first();
            $user->foto = 'img/' . $nameimagen;
            if ($user->save()) {
                return response()->json(['message' => 'Actualizado correctamente', 'foto' => $user->foto], 200);
            } else {
                return response()->json(['message' => 'Ocurrio un error'], 500);
            }
        } else {
            return response()->json(['message' => 'Falta la foto'], 500);
        }
    }

}
