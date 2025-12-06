<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Muestra el listado de usuarios con paginación.
     */
    public function index()
    {
        $users = User::paginate(10); 
        return view('admin.users.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Almacena un usuario recién creado.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], 
        ]);

        User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Cifrado
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', __('user.created_success')); 
    }

    /**
     * Muestra la información detallada del usuario.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Muestra el formulario para editar el usuario.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Actualiza el usuario. Maneja la lógica de la contraseña vacía.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 
                        Rule::unique('users')->ignore($user->id)], // Ignora el email propio
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
        
        $validatedData = $request->validate($rules);

        $data = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ];

        // Lógica de Contraseña: Si el campo viene lleno, se cifra. Si no, se ignora.
        if (!empty($request->password)) {
            $data['password'] = Hash::make($validatedData['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
                         ->with('success', __('user.updated_success'));
    }

    /**
     * Elimina el usuario. Impide la auto-eliminación.
     */
    public function destroy(User $user)
    {
        // Restricción: No se puede eliminar a sí mismo
        if (auth()->id() == $user->id) {
            return redirect()->route('admin.users.index')
                             ->with('error', __('user.cannot_delete_self'));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', __('user.deleted_success'));
    }
}