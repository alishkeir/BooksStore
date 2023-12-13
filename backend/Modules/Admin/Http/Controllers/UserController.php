<?php

namespace Modules\Admin\Http\Controllers;

use Alomgyar\Shops\Shop;
use Alomgyar\Writers\Writer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $model = DB::table('users');
        $params = $request->all();
        if (isset($params['search'])) {
            $model = $model->where('lastname', 'like', '%'.$params['search'].'%')->orWhere('firstname', 'like', '%'.$params['search'].'%');
        }

        if (isset($params['sort'])) {
            $model = $model->orderBy($params['sort'], $params['order'] ?? 'desc');
        }

        $model = $model->paginate($params['show'] ?? 20);

        return view('admin::user.index', [
            'users' => $model,
        ]);
    }

    public function create()
    {
        $permissions = Permission::all();
        $roles = Role::all();

        return view('admin::user.create', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return redirect(route('user.create'))
                ->withErrors($validator)
                ->withInput();
        } else {
            $params = $request->all();
            $user = User::factory()->create([
                'name' => $params['name'],
                'email' => $params['email'],
                'lastname' => $params['lastname'],
                'firstname' => $params['firstname'],
                // factory default password is 'secret'
            ]);
            $userRoles = [];
            foreach ($params['role'] as $key => $role) {
                if ($role == 1) {
                    $userRoles[] = $key;
                }
            }
            $user->assignRole([$userRoles]);

            $user = User::where('email', $params['email'])->first();
            $token = Password::getRepository()->create($user);
            $user->sendPasswordResetNotification($token);

            return redirect()->route('user.edit', ['user' => $user])->with('success', 'Felhasználó létrehozása sikeres volt!');
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin::user.view', [
            'user' => $user,
        ]);
    }

    public function edit($name)
    {
        $user = User::findOrFail($name);
        $roles = Role::all();
        $permissions = Permission::all();
        $shops = Shop::active()->get();
        $writers = Writer::active()->get();

        return view('admin::user.edit', [
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'shops' => $shops,
            'writers' => $writers,
        ]);
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);
        $params = $request->all();
        $validator = $this->validator($request, $user);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $user->fill($validator->validated());
            $user->save();
            $userRoles = [];
            foreach ($params['role'] as $key => $role) {
                if ($role == 1) {
                    $userRoles[] = $key;
                }
            }
            $user->syncRoles([$userRoles]);

            if (isset($params['permission'])) {
                $userPermissions = [];
                foreach ($params['permission'] as $key => $role) {
                    if ($role == 1) {
                        $userPermissions[] = $key;
                    }
                }
                $user->syncPermissions([$userPermissions]);
            }

            return redirect()->back()->with('success', 'Felhasználó mentése sikeres volt!');
        }
    }

    public function destroy($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->syncRoles();
        if ($request->input('type') == 'inactivate') {
            Session::flash('success', 'Felhasználó inaktiválva');
        } else {
            $user->delete();
            Session::flash('success', 'Felhasználó törölve');
        }

        return redirect()->route('user.index');
    }

    private function validator($request, $user = false)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min: 3',
            'email' => [
                'required',
                'email:rfc,dns,filter',
                Rule::unique('users')->ignore($user)
            ],
            'firstname'=> 'string|nullable',
            'lastname'=> 'string|nullable',
            'shop_id' => 'nullable|exists:shops,id',
            'writer_id' => 'nullable|exists:writers,id',
        ]);
        return $validator;
    }
}
