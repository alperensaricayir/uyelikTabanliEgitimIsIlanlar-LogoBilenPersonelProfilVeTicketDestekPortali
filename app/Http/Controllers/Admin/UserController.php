<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('manage-users');

        $query = User::query()->latest();

        // Search by name or email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Filter by active status
        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where('is_active', $request->input('is_active'));
        }

        $users = $query->paginate(15)->withQueryString();

        return view('cms.users.index', compact('users'));
    }

    public function create()
    {
        Gate::authorize('manage-users');
        return view('cms.users.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'agent', 'member'])],
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_premium'] = $request->has('is_premium');

        User::create($validated);

        return redirect()->route('cms.users.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function edit(User $user)
    {
        Gate::authorize('manage-users');
        return view('cms.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('manage-users');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in(['admin', 'agent', 'member'])],
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_premium'] = $request->has('is_premium');

        $user->update($validated);

        return redirect()->route('cms.users.index')
            ->with('success', 'Kullanıcı bilgileri güncellendi.');
    }

    public function destroy(User $user)
    {
        Gate::authorize('manage-users');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendinizi silemezsiniz.');
        }

        $user->delete();

        return redirect()->route('cms.users.index')
            ->with('success', 'Kullanıcı başarıyla silindi.');
    }

    public function toggleActive(User $user)
    {
        Gate::authorize('manage-users');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendi hesabınızı pasife alamazsınız.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'aktif edildi' : 'pasife alındı';
        return back()->with('success', "Kullanıcı başarıyla {$status}.");
    }

    public function toggleAdmin(User $user)
    {
        Gate::authorize('manage-users');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kendi yetkinizi değiştiremezsiniz.');
        }

        $newRole = $user->role === 'admin' ? 'member' : 'admin';
        $user->update(['role' => $newRole]);

        return back()->with('success', "Kullanıcı rolü '{$newRole}' olarak değiştirildi.");
    }
}
