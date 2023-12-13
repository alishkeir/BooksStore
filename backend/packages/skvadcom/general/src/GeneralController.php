<?php

namespace Skvadcom\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Description of GeneralController
 *
 * @author arany
 */
class GeneralController extends Controller
{
    public function index()
    {
        $settings = General::all();
        $tabs = [];

        foreach ($settings as $setting) {
            $tabs[$setting->source][] = ''.$this->makeRow($setting);
        }

        return view('general::index', [
            'content' => $tabs,
        ]);
    }

    public function store(Request $request)
    {
        $params = $request->all();

        $validatedData = $request->validate([
            'data.*' => 'required',
        ]);

        foreach ($params['data'] as $data) {
            parse_str($data, $section);
            foreach ($section['data'] as $key => $options) {
                $general = General::where('key', $key)->first();
                $general->value = $options;
                $general->save();
            }
        }
        Cache::forget('general_settings');
        Cache::rememberForever('general_settings', function () {
            return General::all();
        });

        return json_encode(['success' => true]);
    }

    public function delete(Request $request)
    {
        $params = $request->all();
        $setting = General::where('key', $params['key'])->first();
        $setting->delete();

        return json_encode(['success' => true]);
    }

    public function createrow(Request $request)
    {
        $params = $request->all();
        $setting = new General();
        $setting->fill($params);
        $setting->key = Str::slug($setting->source, '_').'.'.$setting->key;
        $setting->save();

        return $this->makeRow($setting);
    }

    public function updaterow(Request $request)
    {
        $setting = General::findOrFail($request->id);
        $setting->fill($request->all());
        $setting->key = Str::slug($setting->source, '_').'.'.$setting->key;
        $setting->save();

        return $this->makeRow($setting);
    }

    public function deleterow(Request $request)
    {
        $setting = General::findOrFail($request->id);
        $setting->delete();

        return $setting;
    }

    private function makeRow(General $setting)
    {
        return view('general::partials.row', [
            'option' => $setting,
        ]);
    }
}
