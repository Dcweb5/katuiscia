<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::orderBy('created_at', 'desc');

        // Filtre recherche
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($request->query('role') === 'admin') {
            $query->where('is_admin', true);
        } elseif ($request->query('role') === 'client') {
            $query->where('is_admin', false);
        }

        // Filtre par statut
        if ($request->query('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->query('status') === 'inactive') {
            $query->where('is_active', false);
        }

        // Filtre par pays
        if ($country = $request->query('country')) {
            $query->where('country', $country);
        }

        $users = $query->paginate(20)->appends($request->query());

        $stats = [
            'total' => User::count(),
            'admins' => User::where('is_admin', true)->count(),
            'active' => User::where('is_active', true)->count(),
            'newsletter' => User::where('newsletter', true)->count(),
            'countries' => User::whereNotNull('country')->distinct()->pluck('country'),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->loadCount('tokens');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'firstname'   => 'nullable|string|max:255',
            'lastname'    => 'nullable|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'city'        => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country'     => 'nullable|string|max:100',
            'is_active'   => 'nullable|boolean',
            'is_admin'    => 'nullable|boolean',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour.');
    }

    public function toggleActive(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'Compte ' . ($user->is_active ? 'activé' : 'désactivé') . '.');
    }

    public function toggleAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }
        $user->update(['is_admin' => !$user->is_admin]);
        return back()->with('success', 'Rôle admin ' . ($user->is_admin ? 'attribué' : 'retiré') . '.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->tokens()->delete();
        $user->update(['is_active' => false]);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}
