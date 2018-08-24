<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\User;
use App\DocumentType;
use App\Authentication;
use Illuminate\Support\Facades\Hash;
use App\Role;
use App\TeacherGroups;

class GroupController extends Controller {

    private $excel;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return response()->json(Group::all(), 200);
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
        try {
            if ($request->hasFile('foto')) {
                if ($request->hasFile('aprendices')) {
                    $nameimagen = time() . $request->file('foto')->getClientOriginalName();
                    $request->file('foto')->move('./../files/img/', $nameimagen);
                    $group = new Group();
                    $group->numero_ficha = $request->numero_ficha;
                    $group->nombre_ficha = $request->nombre_ficha;
                    $group->descripcion = $request->descripcion;
                    $group->foto = 'img/' . $nameimagen;
                    if ($group->save()) {
                        $path = $request->file('aprendices')->store('hola');
                        $name = './../storage/app/' . $path;
                        $this->leer($name);
                        foreach ($this->excel as $row) {
                            $user = $this->addUser($row);
                            $this->addAuth($row, $group, $user);
                        }
                        return response()->json(['message' => 'Agregado correctamente']);
                    } else {
                        return response()->json(['mensaje' => 'ocurrio un error'], 500);
                    }
                }
            } else {
                return response()->json(['mensaje' => 'falta la foto'], 401);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return response()->json(['mensaje' => 'ocurrio un error', $ex->getMessage()], 500);
        }
    }

    private function leer($name) {
        Excel::load($name, function ($reader) {
            $excel = $reader->get();
            $this->agregar($excel);
        });
    }

    private function addAuth($row, $group, $user) {
        $auth = new Authentication();
        $auth->correo = $row->correo_electronico;
        $auth->contrasena = Hash::make($row->numero_documento);
        $auth->id_usuario = $user->id;
        if ($row->rol !== 'Instructor') {
            $auth->id_grupo = $group->id;
        }
        $role = Role::where('nombre', $row->rol)->firstOrFail();
        $auth->id_rol = $role->id;
        $auth->save();
        if ($row->rol == 'Instructor') {
            $tech = new TeacherGroups();
            $tech->id_autorizacion = $auth->id;
            $tech->id_grupo = $group->id;
            $tech->save();
        }
    }

    private function addUser($row) {
        $user = new User();
        $user->numero_documento = $row->numero_documento;
        $user->primer_nombre = $row->primer_nombre;
        $user->primer_apellido = $row->primer_apellido;
        $user->segundo_Apellido = $row->segundo_apellido;
        $user->segundo_nombre = $row->segundo_nombre;
        $tpd = DocumentType::where('nombre', $row->tipo_documento)->firstOrFail();
        $user->id_tipo_documento = $tpd->id;
        $user->save();
        return $user;
    }

    private function agregar($group) {
        $this->excel = $group;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group) {
//
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group) {
//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group) {
//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group) {
//
    }

}
