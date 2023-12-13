<?php

namespace Alomgyar\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $model = Settings::latest()->paginate(25);

        return view('settings::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('settings::create');
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
        $settings = Settings::create(request()->all());

        Cache::forget('settings_options');
        Cache::rememberForever('settings_options', function () {
            return Settings::all();
        });

        session()->flash('success', 'Settings sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $settings, 'return_url' => route('settings.index')]);
//        return redirect()->route('settings.index')->with('success', 'Settings sikeresen létrehozva!');
    }

    public function edit(Settings $setting)
    {
        return view('settings::edit', [
            'model' => $setting,
        ]);
    }

    public function update(Settings $setting)
    {
        $data = request()->all();
        $this->validateRequest();
        $checks = ['status', 'store_0', 'store_1', 'store_2'];
        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $setting->update($data);
        Cache::forget('settings_options');
        Cache::rememberForever('settings_options', function () {
            return Settings::all();
        });

        session()->flash('success', 'Settings sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $settings, 'return_url' => route('settings.index')]);
//        return redirect()->route('settings.index', ['settings' => $settings->id]);
    }

    public function show(Settings $settings)
    {
        return view('settings::show', [
            'model' => $settings,
        ]);
    }

    public function destroy(Settings $settings)
    {
        $settings->delete();

        return redirect()->route('settings.index')->with('success', 'Settings '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
        ]);
    }
}
