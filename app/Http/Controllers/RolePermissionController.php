<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{

    public function edit($id)
    {
        $role = Role::with('permissions')
            ->findOrFail($id);


        $permissions = Permission::all();


        return view(
            'roles.permissions',
            compact(
                'role',
                'permissions'
            )
        );
    }







    public function update(Request $request, $id)
    {

        $role = Role::findOrFail($id);



        $request->validate([

            'permissions' => ['nullable','array']

        ]);




        $role->permissions()->sync(
            $request->permissions ?? []
        );




        return redirect()

            ->route('roles.permissions', $id)

            ->with(
                'success',
                'Permissions updated successfully.'
            );


    }


}