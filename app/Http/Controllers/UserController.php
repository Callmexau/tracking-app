<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\AuditLog;

class UserController extends Controller implements HasMiddleware
{
    // Définition du middleware d'authentification pour toutes les méthodes de ce contrôleur
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    // Affichage de la liste des utilisateurs
    public function index()
    {
        /** @var \App\Models\User $currentUser */
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        $query = User::with('roles')->latest();

        // Si c'est le Contrôle Interne, il ne liste que les Ops et CCB
        if ($currentUser->hasRole('Controle Interne')) {
            $query->role(['OPS', 'CCB']);
        }
        // Si ce n'est pas un Super Admin, on bloque
        elseif (!$currentUser->hasRole('Super Admin')) {
            abort(403);
        }

        $users = $query->paginate(15);

        return view('users.index', compact('users'));
    }

    // Formulaire de création d'un nouvel utilisateur
    public function create()
    {
        /** @var \App\Models\User $currentUser */
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        // 1. Le Super Admin peut attribuer TOUS les rôles
        if ($currentUser->hasRole('Super Admin')) {
            $roles = Role::all();
        }
        // 2. Le Contrôle Interne ne voit et ne crée que des Ops et des CCB
        elseif ($currentUser->hasRole('Controle Interne')) {
            $roles = Role::whereIn('name', ['OPS', 'CCB'])->get();
        }
        else {
            abort(403, 'Vous n\'êtes pas autorisé à créer un utilisateur.');
        }

        return view('users.create', compact('roles'));
    }

    // Méthode de stockage du nouvel utilisateur
    public function store(Request $request)
    {
        /** @var \App\Models\User $currentUser */
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        if ($currentUser->hasRole('Super Admin')) {
            $allowedRoles = ['Super Admin', 'Controle Interne', 'OPS', 'CCB'];
        } elseif ($currentUser->hasRole('Controle Interne')) {
            $allowedRoles = ['OPS', 'CCB'];
        } else {
            abort(403);
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'confirmed', 'min:8'],
            'role'       => ['required', 'string', 'in:' . implode(',', $allowedRoles)],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'is_active'  => true,
            'must_change_password' => true,
        ]);

        $user->assignRole($request->role);

        AuditLog::log(
            'user.created',
            "L'agent {$currentUser->first_name} {$currentUser->last_name} a créé le compte de {$user->first_name} {$user->last_name} avec le rôle de {$request->role}."
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    // Formulaire d'édition d'un utilisateur existant
    public function edit(User $user)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        if ($currentUser->hasRole('Controle Interne') && !$user->hasAnyRole(['OPS', 'CCB'])) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cet utilisateur.');
        }
        elseif (!$currentUser->hasRole('Super Admin') && !$currentUser->hasRole('Controle Interne')) {
            abort(403);
        }

        if ($currentUser->hasRole('Super Admin')) {
            $roles = Role::all();
        } else {
            $roles = Role::whereIn('name', ['OPS', 'CCB'])->get();
        }

        return view('users.edit', compact('user', 'roles'));
    }

    // Méthode de mise à jour de l'utilisateur
    public function update(Request $request, User $user)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        // 1. Contrôle strict de l'utilisateur cible (Empêche CI de modifier un Super Admin ou un autre CI)
        if ($currentUser->hasRole('Controle Interne') && !$user->hasAnyRole(['OPS', 'CCB'])) {
            abort(403, 'Action non autorisée.');
        }

        // 2. Définition des rôles que l'utilisateur connecté a le droit d'ATTRIBUER
        if ($currentUser->hasRole('Super Admin')) {
            $allowedRoles = ['Super Admin', 'Controle Interne', 'OPS', 'CCB'];
        } elseif ($currentUser->hasRole('Controle Interne')) {
            $allowedRoles = ['OPS', 'CCB'];
            // SÉCURITÉ SUPPLÉMENTAIRE : S'assurer que le rôle soumis dans le formulaire n'est pas interdit
            if (in_array($request->role, ['Super Admin', 'Controle Interne'])) {
                abort(403, 'Action non autorisée sur ce rôle.');
            }
        } else {
            abort(403);
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email,' . $user->id],
            'password'   => ['nullable', 'confirmed', 'min:8'],
            'role'       => ['required', 'string', 'in:' . implode(',', $allowedRoles)],
            'is_active'  => ['required', 'boolean'],
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->is_active = $request->is_active;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Synchronisation du rôle
        $user->syncRoles($request->role);

        AuditLog::log(
            'user.updated',
            "L'agent {$currentUser->first_name} {$currentUser->last_name} a mis à jour les informations du compte de {$user->first_name} {$user->last_name}."
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'Compte utilisateur mis à jour avec succès.');
    }

    // Méthode de suppression d'un utilisateur
    public function destroy(User $user)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            abort(403, 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Le Contrôle Interne ne peut en aucun cas supprimer un Super Admin ou un autre Contrôle Interne
        if ($currentUser->hasRole('Controle Interne') && $user->hasAnyRole(['Super Admin', 'Controle Interne'])) {
            abort(403, 'Suppression non autorisée pour ce type de profil.');
        }

        $user->delete();

        AuditLog::log(
            'user.deleted',
            "L'agent {$currentUser->first_name} {$currentUser->last_name} a supprimé l'utilisateur {$user->first_name} {$user->last_name}."
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
