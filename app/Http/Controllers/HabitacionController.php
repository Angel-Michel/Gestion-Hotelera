<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use Illuminate\Http\Request;

class HabitacionController extends Controller
{
    public function index()
    {
        $habitaciones = Habitacion::all();
        return response()->json($habitaciones, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tipo_habitacion' => 'required|exists:tipos_habitacion,id',
            'numero_habitacion' => 'required|string|max:10|unique:habitaciones',
            'piso' => 'nullable|string|max:10',
            'estatus' => 'nullable|string|max:50',
        ]);

        $habitacion = Habitacion::create($request->all());

        return response()->json([
            'message' => 'Habitación creada exitosamente',
            'data' => $habitacion
        ], 201);
    }
}