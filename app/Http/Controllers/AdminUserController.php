<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display listing of all users.
     */
    public function index(Request $request): View
    {
        $query = User::with('kecamatan')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($kecamatanId = $request->integer('kecamatan_id')) {
            $query->where('kecamatan_id', $kecamatanId);
        }

        $users = $query->paginate(15)->withQueryString();
        $kecamatans = Kecamatan::orderBy('urutan')->get();

        return view('admin.users.index', compact('users', 'kecamatans'));
    }

    /**
     * Show creation form.
     */
    public function create(): View
    {
        $kecamatans = Kecamatan::orderBy('urutan')->get();

        return view('admin.users.create', compact('kecamatans'));
    }

    /**
     * Store new user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'user_area'])],
            'kecamatan_id' => [
                'nullable',
                Rule::requiredIf($request->input('role') === 'user_area'),
                'exists:kecamatans,id',
            ],
        ]);

        if ($validated['role'] === 'admin') {
            $validated['kecamatan_id'] = null;
        }

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dibuat!');
    }

    /**
     * Show edit form.
     */
    public function edit(User $user): View
    {
        $kecamatans = Kecamatan::orderBy('urutan')->get();

        return view('admin.users.edit', compact('user', 'kecamatans'));
    }

    /**
     * Update user details.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'user_area'])],
            'kecamatan_id' => [
                'nullable',
                Rule::requiredIf($request->input('role') === 'user_area'),
                'exists:kecamatans,id',
            ],
        ]);

        if ($validated['role'] === 'admin') {
            $validated['kecamatan_id'] = null;
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil diperbarui!');
    }

    /**
     * Delete user account.
     */
    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
