<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Entities\Package;

class PackageController extends Controller
{
    protected $perPage = 25;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $model = DB::table('packages')->whereNull('deleted_at');
        $params = $request->all();
        if (isset($params['search'])) {
            $model = $model->where('name', 'like', '%'.$params['search'].'%')
                ->orWhere('folder', 'like', '%'.$params['search'].'%');
        }

        if (isset($params['sort'])) {
            $model = $model->orderBy($params['sort'], $params['order'] ?? 'desc');
        }

        $model = $model->paginate($params['show'] ?? $this->perPage);

        return view('admin::package.index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('admin::package.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return redirect(route('packages.create'))
                ->withErrors($validator)
                ->withInput();
        } else {
            $exitCode = Artisan::call('package:create', [
                'name' => $request->name,
                '--folder' => $request->folder,
                '--resource' => $request->resource,
                '--fields' => [$request->fields],
            ]);

            try {
                $res = Artisan::call('dump-autoload');

                return redirect(route('packages.index'))->with('flash_message', 'Package létrehozása sikeres volt!');
            } catch (\Throwable $th) {
                return redirect(route('packages.create'))->with('flash_message', 'Ejnye! Valami félrement... '.$th->getMessage());
            }
        }
    }

    private function validator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min: 3',
            'fields' => 'required',
        ]);

        return $validator;
    }

    public function destroy($id, Request $request)
    {
        $model = Package::findOrFail($id);
        $model->delete();

        return redirect()->route('packages.index')->with('flash_message', 'Package törölve lett bátyja');
    }

    public function show()
    {
        return redirect()->route('packages.index');
    }
}
