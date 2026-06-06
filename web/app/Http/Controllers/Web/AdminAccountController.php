<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Departmentsbiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminAccountController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');
        
        $query = User::with('department')->orderBy('name');
        
        if ($role && in_array($role, ['admin', 'interviewer', 'candidate'])) {
            $query->where('role', $role);
        }

        $users = $query->get();
        $departments = Departmentsbiro::orderBy('name')->get();

        return view('admin.accounts', [
            'users' => $users,
            'departments' => $departments,
            'currentRole' => $role,
            'title' => 'Kelola Akun'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,interviewer,candidate',
            'department_id' => [
                'nullable',
                Rule::requiredIf($request->role === 'interviewer'),
                'exists:departmentsbiro,id'
            ],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'department_id' => $request->role === 'interviewer' ? $request->department_id : null,
        ]);

        return redirect()->route('admin.accounts')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function update(Request $request, User $account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($account->id),
            ],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,interviewer,candidate',
            'department_id' => [
                'nullable',
                Rule::requiredIf($request->role === 'interviewer'),
                'exists:departmentsbiro,id'
            ],
        ]);

        $account->name = $request->name;
        $account->email = $request->email;
        $account->role = $request->role;
        $account->department_id = $request->role === 'interviewer' ? $request->department_id : null;

        if ($request->filled('password')) {
            $account->password = Hash::make($request->password);
        }

        $account->save();

        return redirect()->route('admin.accounts')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $account)
    {
        if (auth()->id() === $account->id) {
            return redirect()->route('admin.accounts')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $account->delete();

        return redirect()->route('admin.accounts')->with('success', 'Akun berhasil dihapus.');
    }
}
