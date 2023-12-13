
<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
            @forelse ($sections as $key => $value)
                <li class="nav-item"><a wire:click="setFilter('{{$key}}')" href="#solid-justified-tab1" class="nav-link legitRipple @if($section == $key) active show @endif" data-toggle="tab">{{$value}}</a></li>
            @empty
            @endforelse
            {{-- <li class="nav-item"><a wire:click="setFilter('alomgyar')" href="#solid-justified-tab2" class="nav-link legitRipple @if($section == 'alomgyar') active show @endif" data-toggle="tab">Álomgyár</a></li>
            <li class="nav-item"><a wire:click="setFilter('olcsokonyvek')" href="#solid-justified-tab2" class="nav-link legitRipple @if($section == 'olcsokonyvek') active show @endif" data-toggle="tab">Olcsókönyvek</a></li>
            <li class="nav-item"><a wire:click="setFilter('nagyker')" href="#solid-justified-tab2" class="nav-link legitRipple @if($section == 'nagyker') active show @endif" data-toggle="tab">Nagyker</a></li>
            <li class="nav-item"><a wire:click="setFilter('affiliate')" href="#solid-justified-tab2" class="nav-link legitRipple @if($section == 'affiliate') active show @endif" data-toggle="tab">Affiliate</a></li> --}}
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active">
                <table class="table table-striped">
                    <tbody>
                    @foreach($model as $settings)
                        <tr>
                            <td>{{ $settings->title }}
                            <br><small>{{ $settings->key }}</small></td>
                            <td>
                                <input wire:model.lazy="input.{{$settings->id}}" name="input{{$settings->id}}"
                                @if($settings->secondary == 'checkbox')
                                     type="checkbox" value="1"
                                     @if($settings->primary == '1') checked @endif
                                @else
                                    type="text" value="{{ $input[$settings->id] }}" class="form-control"
                                @endif >
                            </td>
                            <td class="text-right">
                                <div class="list-icons">
                                    <a wire:click="saveSetting({{$settings->id}})" class="btn btn-info text-white">Mentés</a>
                                    {{--}}
                                    @can('settings.storing')
                                    <a href="{{ route('settings.edit', ['setting' => $settings]) }}" class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                                    @endcan --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <style>
        .nav-tabs-solid .nav-item {
            margin-bottom: -6px;
        }
    </style>
</div>
