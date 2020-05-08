<?php

namespace App\Http\Controllers\Admin\v1;

use App\Enums\AccessType;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\QueryBuilder;

class UsersAdminController extends Controller
{
    public function index()
    {
        $users = QueryBuilder::for(User::class)
            ->select('id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at')
            ->with('roles')
            ->where(['access_type' => AccessType::ADMIN])
            ->allowedFilters(['first_name', 'last_name'])
            ->defaultSort('-updated_at')
            ->allowedSorts(['id', 'first_name', 'last_name', 'created_at', 'updated_at'])
            ->paginate(request('per_page', 15))
            ->appends(request()->query());

        return response(['success' => true, 'data' => $users]);

    }



    public function store()
    {

        $data = request()->validate([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'role' => ['required', 'exists:roles,id'],
            'email' => ['required', 'unique:users', 'email', 'max:255'],
            'password' => ['required', 'min:6'],
        ]);

        $data['password'] = bcrypt($data['password']);

        $role = $data['role'];
        unset($data['role']);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'email_verified_at' => now(),
            'password' => $data['password'],
            'access_type' => AccessType::ADMIN,
        ]);
        $user->assignRole(Role::find($role)->name);

        return response(['success' => true, 'data' => 'admin created']);
    }

    public function update($user_id)
    {
        $data = request()->validate([
            'email' => ['required', 'email', 'unique:users,email,' . $user_id],
            'role' => ['required'],
            'password' => ['same:confirm-password'],
            'first_name' => ['required'],
            'last_name' => ['required'],
        ]);

        if (\request()->has('profile_image'))
            $data['profile_image'] = copy_file_storage_s3_admin($data['profile_image'], 'usersProfile');

        if (request()->has('password'))
            $data['password'] = bcrypt($data['password']);

        unset($data['role']);
        $user = User::find($user_id);
        $user->update($data);

        $roles = $user->getRoleNames();
        foreach ($roles as $role)
            $user->removeRole($role);

        $user->assignRole(request()->input('role'));
        return response(['success' => true, 'data' => 'admin updated']);

    }

    public function delete($user_id)
    {
        $user = User::where(['id' => $user_id, 'access_type' => AccessType::ADMIN])->firstOrFail();
        $user->delete();

        return response(['success' => true, 'data' => 'admin deleted']);
    }
}
