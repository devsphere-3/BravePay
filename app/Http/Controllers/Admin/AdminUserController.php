<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        return view('superadmin.users.index', [
            'users' => User::with('role')->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('superadmin.users.create', [
            'roles' => Role::orderByDesc('level')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'role_id'  => ['required', 'exists:roles,id'],
            'status'   => ['required', 'in:active,inactive,suspended'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create($validated);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(string $id): View
    {
        return view('superadmin.users.show', [
            'user' => User::with('role')->findOrFail($id),
        ]);
    }

    public function edit(string $id): View
    {
        return view('superadmin.users.edit', [
            'user'  => User::with('role')->findOrFail($id),
            'roles' => Role::orderByDesc('level')->get(),
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'    => ['nullable', 'string', 'max:20'],
            'role_id'  => ['required', 'exists:roles,id'],
            'status'   => ['required', 'in:active,inactive,suspended'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        if ($user->is(Auth::user()) && $validated['status'] !== 'active') {
            return back()->withErrors(['status' => 'Akun yang sedang digunakan tidak dapat dinonaktifkan.'])
                ->withInput();
        }

        $user->update($validated);
        $user->clearPermissionCache();

        return redirect()->route('superadmin.users.show', $user->id)
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function toggleStatus(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->is(Auth::user())) {
            return back()->with('error', 'Akun yang sedang digunakan tidak dapat dinonaktifkan.');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->is(Auth::user())) {
            return back()->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
