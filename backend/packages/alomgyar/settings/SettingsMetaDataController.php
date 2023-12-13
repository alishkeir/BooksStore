<?php

namespace Alomgyar\Settings;

use App\Http\Controllers\Controller;

class SettingsMetaDataController extends Controller
{
    public function index()
    {
        $model = SettingsMetaData::all();

        return view('metadata::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('metadata::create');
    }

    public function store()
    {
        $data = request()->all();
        $this->validateRequest();
        $checks = ['status', 'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        // dd($data);
        $settings = SettingsMetaData::create(request()->all());

        session()->flash('success', 'Settings meta címkék sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $settings, 'return_url' => route('metadata.index')]);
    }

    public function edit(SettingsMetaData $metadata)
    {
        return view('metadata::edit', [
            'model' => $metadata,
        ]);
    }

    public function update(SettingsMetaData $metadata)
    {
        $data = request()->all();
        if ($data['page'] != $metadata['page']) {
            $this->validateRequest();
        }
        $metadata->update($data);

        session()->flash('success', 'Settings meta címkék sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $metadata, 'return_url' => route('metadata.index')]);
    }

    public function show(SettingsMetaData $settings)
    {
        return view('meta_data::show', [
            'model' => $settings,
        ]);
    }

    public function destroy(SettingsMetaData $metadata)
    {
        $metadata->delete();

        return redirect()->route('metadata.index')->with('success', 'Settings meta címkék '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'page' => 'required',
            'section' => 'required',
        ]);
    }
}
