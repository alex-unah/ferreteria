<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empleados = Empleado::latest()->paginate(10);
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('empleados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'identidad' => 'required|string|min:13|max:13',
            'nombre' => 'required|max:255|string',
            'apellido' => 'max:255|string',
            'cargo' => 'required',
            'salario' => 'required|integer|min:0',
            'codEmp' => 'integer',
        ]);

        Empleado::create($request->all());

        return redirect()->route('empleados.index')
                         ->with('success', '¡Empleado registrado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {
        return redirect()->route('empleados.edit',$empleado);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleado $empleado)
    {
        return view('empleados.edit', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'identidad' => 'required|string|min:13|max:13',
            'nombre' => 'required|max:255|string',
            'apellido' => 'max:255|string',
            'cargo' => 'required',
            'salario' => 'required|integer|min:0',
            'codEmp' => 'integer',
        ]);

        $empleado->update($request->all());

        return redirect()->route('empleados.index')
                         ->with('success', '¡Empleado actualizado correctamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return redirect()->route('empleados.index')
                         ->with('success', '¡Empleado eliminado del inventario!');
    }
}
