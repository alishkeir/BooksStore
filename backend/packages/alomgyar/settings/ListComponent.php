<?php

namespace Alomgyar\Settings;

use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class ListComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $s;

    public $perPage = 300;

    public $sortField = 'id';

    public $sortAsc = true;

    public $section = 'altalanos';

    public $input = [];

    protected $listeners = ['saveSetting'];

    public function mount()
    {
        $model = Settings::all();
        foreach ($model as $settings) {
            if ($settings->secondary == 'checkbox' && $settings->primary == '0') {
            } else {
                $this->input[$settings->id] = $settings->primary;
            }
        }
        $this->sections = SettingsHelper::SETTING_SECTIONS;
    }

    public function render()
    {
        $model = Settings::query();
        $model->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

        if ($this->section) {
            $model = $model->where('section', $this->section);
        }
        $model = $model->paginate($this->perPage);

        return view('settings::components.listcomponent', [
            'model' => $model,
        ]);
    }

    public function sortBy($column)
    {
        if ($this->sortField === $column) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }
        $this->sortField = $column;
    }

    public function setFilter($filter)
    {
        $this->section = $filter;
    }

    public function updatingS()
    {
        $this->resetPage();
    }

    public function saveSetting($id)
    {
        $setting = Settings::find($id);
        if ($setting->secondary == 'checkbox' && $this->input[$id] != '1') {
            $setting->primary = 0;
        } else {
            $setting->primary = $this->input[$id];
        }
        $setting->save();
        Cache::forget('settings_options');
        Cache::rememberForever('settings_options', function () {
            return Settings::all();
        });
    }
}
