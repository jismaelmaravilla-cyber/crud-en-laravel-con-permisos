<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
   
    public function index()
    {
        // with('role') carga el rol junto con cada usuario en una sola consulta (evita lentitud)
        // User::all() ya excluye automáticamente los que tienen 'deleted_at' lleno
        $usuarios = User::with('role')->get();

        return view('usuarios.index', compact('usuarios'));
    }

  //mostrar formulario para crear un nuevo usuario
    public function create()
    {
        $roles = Role::all();

        return view('usuarios.create', compact('roles'));
    }

    //guardar un nuevo usuario en la base de datos
    public function store(Request $request)
    {
        $esAdministrador = Auth::user()->role?->nombre === 'Administrador';

        // Validamos los campos que vienen del formulario
        $datos = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:3|confirmed',
            'role_id' => $esAdministrador
                ? 'required|exists:roles,id'
                : 'nullable',
        ]);

        // Si el usuario autenticado NO es Administrador, forzamos que el nuevo usuario sea Empleado
        if (!$esAdministrador) {
            $datos['role_id'] = Role::where('nombre', 'Empleado')->firstOrFail()->id;
        }

        User::create($datos);

        return redirect()->route('usuarios.index')
            ->with('mensaje', 'Usuario registrado correctamente.');
    }

    //mosatrar detalles 
    public function show($id)
    {
        $usuario = User::with('role')->findOrFail($id);

        return view('usuarios.show', compact('usuario'));
    }

  //formulario para editar
    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

   //actualiza
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        $esAdministrador = Auth::user()->role?->nombre === 'Administrador';

        $datos = $request->validate([
            'name' => 'required|string|max:255',
            // unique:users,email,ID ignora el correo del propio usuario que estamos editando
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:3|confirmed',
            'role_id' => $esAdministrador
                ? 'required|exists:roles,id'
                : 'nullable',
        ]);

        // Quitamos password y role_id del arreglo general para tratarlos con cuidado
        unset($datos['password'], $datos['role_id']);

        // Solo actualizamos la contraseña si el usuario escribió algo nuevo
        if ($request->filled('password')) {
            $usuario->password = $request->password;
        }

        // Solo el Administrador puede cambiar el rol de un usuario
        if ($esAdministrador && $request->filled('role_id')) {
            $usuario->role_id = $request->role_id;
        }

        $usuario->fill($datos);
        $usuario->save();

        return redirect()->route('usuarios.index')
            ->with('mensaje', 'Usuario actualizado correctamente.');
    }

   
     //Eliminación lógica (Soft Delete): llena 'deleted_at' y deshabilita el registro.
    
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        // Evitar que el usuario autenticado se elimine a sí mismo
        if (Auth::id() === $usuario->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('mensaje', 'Usuario deshabilitado correctamente.');
    }

    // eliminacion de adeveras
    public function forceDestroy($id)
    {
        // withTrashed() permite encontrarlo aunque ya esté deshabilitado (Soft Deleted)
        $usuario = User::withTrashed()->findOrFail($id);

        if (Auth::id() === $usuario->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->forceDelete();

        return redirect()->route('usuarios.index')
            ->with('mensaje', 'Usuario eliminado permanentemente.');
    }

   // ver lista de los deleteados
    public function trashed()
    {
        // onlyTrashed() trae SOLO los registros que tienen 'deleted_at' lleno
        $usuarios = User::onlyTrashed()->with('role')->get();

        return view('usuarios.trashed', compact('usuarios'));
    }

  // restaura el mono dehabilitado
    public function restore($id)
    {
        $usuario = User::onlyTrashed()->findOrFail($id);
        $usuario->restore();

        return redirect()->route('usuarios.trashed')
            ->with('mensaje', 'Usuario recuperado correctamente.');
    }
}