<?php

namespace Skvadcom\Permissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Skvadcom\Items\Item;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $routes = Route::getRoutes();
        $permissions_all = Permission::pluck('name', 'id');
        $role_all = Role::get();
        $role_has_permission = Role::with('permissions')->get()->pluck('permissions', 'id')->toArray();
        $rhp_checked = [];
        foreach ($role_has_permission as $role_id => $permission_array) {
            //var_dump(Auth::user()->can($permission_array['name']));
            $rhp_checked[$role_id] = [];
            foreach ($permission_array as $permission) {
                array_push($rhp_checked[$role_id], $permission['name']);
            }
        }
        //dd($permissions_all);
        $permissions_list = [];
        foreach ($permissions_all as $key => $permission) {
            if (Auth::user()->can($permission)) {
                $permissions_list[$key] = $permission;
            }
        }

        $permissions = $this->getPermissions();
        //dd($permissions);
        return view('permissions::index', [
            'permissions' => $permissions,
            'permissions_list' => $permissions_list,
            'role_all' => $role_all,
            'rhp_checked' => $rhp_checked,
        ]);
    }

    public function create()
    {
        return view('permissions::create');
    }

    public function store(Request $request)
    {
        $input_roles = $request->role;

        //dd($input_roles);

        $permissions = $this->getPermissions(true);

        $permissions_all = Permission::pluck('name', 'id')->toArray();
        $roles = Role::all();
        //dd($roles);
        foreach ($permissions as $perm) {
            if (! in_array($perm, $permissions_all)) {
                $permission = Permission::create(['name' => $perm]);
            }
        }
        // lekérdezem újra, megfordítom a key - value-t
        $permissions_all = Permission::pluck('id', 'name')->toArray();
        //dd($input_roles);
        foreach ($roles as $role) {
            //dd(array_keys($input_roles[$role->id]));
            $pIds = [];
            if (isset($input_roles[$role->id])) {
                foreach (array_keys($input_roles[$role->id]) as $name) {
                    $pIds[] = $permissions_all[$name];
                }
            }

            //dd($pIds);
            $role->permissions()->sync($pIds);
        }
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return ['success' => true];
    }

    public function edit(Item $item)
    {
    }

    public function update(Item $item)
    {
    }

    public function show(Item $item)
    {
    }

    public function destroy(Item $item)
    {
    }

    protected function validateRequest()
    {
    }

    private function getPermissions($raw = false)
    {
        $routes = Route::getRoutes();
        $permissions = [];
        $permissions_raw = [];
        foreach ($routes as $route) {
            if (strpos($route->uri(), 'gephaz') !== false) {
                foreach ($route->getAction('middleware') as $middleware) {
                    if (strpos($middleware, 'can:') !== false) {
                        $middlewareName = substr($middleware, 4);
                        $group = strpos($middlewareName, '.') !== false ? substr($middlewareName, 0, strpos($middlewareName, '.')) : $middlewareName;
                        if (! isset($permissions[$group]) || ! in_array($middlewareName, $permissions[$group])) {
                            $permissions[$group][] = $middlewareName;
                            $permissions_raw[] = $middlewareName;
                        }
                    }
                }
            }
        }

        if ($raw) {
            return $permissions_raw;
        }

        return $permissions;
    }
}
