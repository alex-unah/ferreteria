<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\EmpleadoResource;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Paginamos de 10 en 10 para no colapsar la memoria en catálogos gigantes
        $empleados = Empleado::orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'status' => 'success',
            'data'   => EmpleadoResource::collection($empleados),
            'pagination' => [
                'total'        => $empleados->total(),
                'per_page'     => $empleados->perPage(),
                'current_page' => $empleados->currentPage(),
                'last_page'    => $empleados->lastPage(),
            ]
        ], 200); // 200 OK
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validamos los datos entrantes del payload JSON
        $validados = $request->validate([
            
            'identidad'         => 'string|min:13|max:13',
            'nombre'         => 'string|min:150',
            'apellido'         => 'string|min:150|nullable',
            'cargo'         => 'string|min:150',
            'salario'         => 'integer|min:0.01',
            'codEmp'         => 'integer|nullable'

            //A continuacion//
        ]);
            
        // 2. Insertamos el producto utilizando Eloquent ORM
        $empleado = Empleado::create($validados);

        // 3. Respondemos con código semántico 201 Created y el recurso transformado
        return response()->json([
            'status'  => 'created',
            'message' => 'Empleado ingresado exitosamente',
            'data'    => new EmpleadoResource($empleado)
        ], 201); // 201 Created
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Buscamos el producto en MySQL
        $empleado = Empleado::find($id);

        // Si no existe, devolvemos error semántico 404
        if (!$empleado) {
            return response()->json([
                'status'  => 'error',
                'message' => "El empleado con ID #{$id} no existe en Ferretería La Bendición"
            ], 404); // 404 Not Found
        }

        return response()->json([
            'status' => 'success',
            'data'   => new EmpleadoResource($empleado)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Empleado no encontrado para actualizar'
            ], 404);
        }

        $validados = $request->validate([
            'identidad'         => 'string|min:13|max:13',
            'nombre'         => 'string|min:150',
            'apellido'         => 'string|min:150|nullable',
            'cargo'         => 'string|min:150',
            'salario'         => 'integer|min:0.01',
            'codEmp'         => 'integer|nullable'
        ]);

        $empleado->update($validados);

        return response()->json([
            'status'  => 'updated',
            'message' => 'Empleado actualizado correctamente',
            'data'    => new EmpleadoResource($empleado)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $empleado = Empleado::find($id);

        if (!$empleado) {
            return response()->json([
                'status'  => 'error',
                'message' => 'El empleado a eliminar no existe en bodega'
            ], 404);
        }

        $empleado->delete();

        return response()->json([
            'status'  => 'deleted',
            'message' => 'Empleado dado de baja exitosamente'
        ], 200);
    }
}
