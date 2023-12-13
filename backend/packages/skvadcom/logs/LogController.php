<?php

namespace Skvadcom\Logs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        if (! auth()->user()->hasRole(['admin', 'skvadmin', 'super admin'])) {
            abort(403, 'Jogosultsági probléma');
        }

        return view('logs::index');
    }

    public function show($id)
    {
        if (! auth()->user()->hasRole(['admin', 'skvadmin', 'super admin'])) {
            abort(403, 'Jogosultsági probléma');
        }

        $model = Log::findOrFail($id);

        return view('logs::show', [
            'model' => $model,
        ]);
    }

    public function search(Request $request)
    {
        $term = trim($request->q);

        $model = Log::where('name', 'like', '%'.$term.'%')->get();

        $formatted_tags = [];

        foreach ($model as $log) {
            $formatted_tags[] = ['id' => $log->id, 'text' => $log->name];
        }

        return response()->json($formatted_tags);
    }
}
