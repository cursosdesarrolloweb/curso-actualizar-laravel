<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRoleRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::with('roles')->orderBy('name')->paginate(20),
        ]);
    }

    public function edit(User $user): View
    {
        return view('admin.users.roles', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(AssignRoleRequest $request, User $user): RedirectResponse
    {
        $user->syncRoles($request->roleIds());

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Roles actualizados.');
    }
}
