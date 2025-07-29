<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Alert

        if ($request->ajax()) {
            $data = User::with('roles')->select('users.*');

            return DataTables::of($data)
                ->addColumn('roles', function ($user) {
                    return $user->roles->pluck('name')->map(function ($role) {
                        return '<span class="bg-blue-200 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">'.$role.'</span>';
                    })->implode(' ');
                })
                ->addColumn('action', function ($user) {
                    $editUrl = route('admin.users.edit', $user->id);
                    $deleteUrl = route('admin.users.destroy', $user->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    // Menggunakan flex untuk alignment dan gap untuk jarak
                    return <<<HTML
                        <div class="flex items-center gap-2">
                            <a href="{$editUrl}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="Edit">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                </svg>
                            </a>

                            <form action="{$deleteUrl}" method="POST" class="delete-form">
                                {$csrf}
                                {$method}
                                <button type="submit"
                                    title="Hapus"
                                    class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    HTML;
                })
                ->rawColumns(['roles', 'action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name')->all();
        $userRoles = $user->roles->pluck('name', 'name')->all();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function editModal()
    {
        $user = auth()->user();

        // Mengembalikan data sebagai JSON
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    /**
     * Memperbarui data profil dari modal.
     */
    public function updateModal(Request $request)
    {
        $user = auth()->user();

       // SESUDAH
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ];

        // Hanya tambahkan aturan validasi password JIKA kolomnya diisi
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $validated = $request->validate($rules);

        // Update nama dan email
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Jika user mengisi password baru, update passwordnya
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Mengembalikan respon JSON
        return response()->json(['message' => 'Profil Anda telah berhasil diperbarui.']);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => ['required', 'array']
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Mencegah user menghapus dirinya sendiri
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
