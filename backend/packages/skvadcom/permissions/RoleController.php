<?php

namespace Skvadcom\Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function create()
    {
        return view('permissions::create');
    }

    public function store(Request $request)
    {
        $params = $request->all();

        $existRole = Role::where([['name', $params['name']]])->first();

        if (empty($existRole)) {
            $newRole = Role::create(['name' => $params['name'], 'guard_name' => 'web']);

            return redirect(route('permissions.index'));
        } else {
            return redirect()->back()->withErrors(['Ezzel a névvel nem tudsz szerepkört létrehozni']);
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('permissions::edit', [
            'model' => $role,
        ]);
    }

    public function update($id, Request $request)
    {
        $params = $request->all();
        $role = Role::findOrFail($id);

        $existRole = Role::where([['name', $params['name']], ['id', '<>', $id]])->first();

        if (! empty($existRole)) {
            return redirect()->back()->withErrors(['Ezzel a névvel nem tudsz szerepkört létrehozni']);
        }

        $role->fill($params);

        if ($role->save()) {
            return redirect(route('permissions.index'));
        }
    }
}
