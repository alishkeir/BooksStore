<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
            <li class="nav-item"><a wire:click="setFilter('altalanos')" href="#solid-justified-tab1" class="nav-link legitRipple @if($section == 'altalanos') active show @endif" data-toggle="tab">Általános</a></li>
            <li class="nav-item"><a wire:click="setFilter('alomgyar')" href="#solid-justified-tab2" class="nav-link legitRipple @if($section == 'alomgyar') active show @endif" data-toggle="tab">Álomgyár</a></li>
            <li class="nav-item"><a wire:click="setFilter('olcsokonyvek')" href="#solid-justified-tab2" class="nav-link legitRipple @if($section == 'olcsokonyvek') active show @endif" data-toggle="tab">Olcsókönyvek</a></li>
            <li class="nav-item"><a wire:click="setFilter('nagyker')" href="#solid-justified-tab2" class="nav-link legitRipple @if($section == 'nagyker') active show @endif" data-toggle="tab">Nagyker</a></li>
        </ul>
        <table class="table table-striped">
            <tbody>
                @foreach ($model as $metadata)
                    <tr>
                        <td>{{ $metadata->page }}
                        <td>{{ $metadata->title }}
                        <td>{{ $metadata->description }}
                            <br><small>{{ $metadata->key }}</small>
                        </td>
                        <td>
                            <input wire:model.lazy="input.{{ $metadata->id }}" name="input{{ $metadata->id }}"
                                @if ($metadata->secondary == 'checkbox') type="checkbox" value="1"
                                     @if ($metadata->primary == '1') checked @endif
                            @else type="text" value="{{ $input[$metadata->id] }}" class="form-control" @endif >
                        </td>
                        <td class="text-right">
                            <div class="list-icons">
                                @can('metadata.storing')
                                    <form action="{{ route('metadata.destroy', ['metadata' => $metadata]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="list-icons-document text-danger-600"><i class="icon-trash"></i></a>
                                    </form>
                                    <a href="{{ route('metadata.edit', ['metadata' => $metadata]) }}"
                                        class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <style>
        .nav-tabs-solid .nav-item {
            margin-bottom: -6px;
        }
    </style>
</div>
