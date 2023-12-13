<div>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>
                        <a href="javascript:" wire:click.prevent="sortBy('id')" role="button" class="text-default">
                            #
                            @include('admin::partials._sort-icons', ['field' => 'id'])
                        </a>
                    </th>
                    <th>
                        <a href="javascript:" role="button" class="text-default">
                            Név
                        </a>
                    </th>
                    <th>
                        <a href="javascript:" wire:click.prevent="sortBy('city')" role="button" class="text-default">
                            Város
                            @include('admin::partials._sort-icons', ['field' => 'city'])
                        </a>
                    </th>
                    <th>
                        <a href="javascript:" wire:click.prevent="sortBy('zip_code')" role="button" class="text-default">
                            Irányítószám
                            @include('admin::partials._sort-icons', ['field' => 'zip_code'])
                        </a>
                    </th>
                    <th>
                        <a href="javascript:" role="button" class="text-default">
                            Cím
                        </a>
                    </th>
                    <th>
                        <a href="javascript:" wire:click.prevent="sortBy('country_id')" role="button" class="text-default">
                            Ország
                            @include('admin::partials._sort-icons', ['field' => 'country_id'])
                        </a>
                    </th>
                    @if($type === 'billing')
                    <th>
                        <a href="javascript:" wire:click.prevent="sortBy('entity_type')" role="button" class="text-default">
                            Típus
                            @include('admin::partials._sort-icons', ['field' => 'entity_type'])
                        </a>
                    </th>
                    @endif
                    <th>{{ __('general.actions') }}</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Név</th>
                    <th>Város</th>
                    <th>Irányítószám</th>
                    <th>Cím</th>
                    <th>Ország</th>
                    @if($type === 'billing')<th>Típus</th>@endif
                    <th>{{ __('general.actions') }}</th>
                </tr>
                </tfoot>
                <tbody>
                @forelse($addresses as $address)
                    <tr>
                        <td>{{ $address->id }}</td>
                        <td>
                            <strong style="white-space: nowrap;">{{ $address->full_name }}</strong>
                            @isset($address->vat_number)
                                <br>
                                <small>{{ $address->vat_number }}</small>
                            @endisset
                        </td>
                        <td>
                            {{ $address->city }}
                        <td>
                            {{ $address->zip_code }}
                        </td>
                        <td>
                            {{ $address->address }}
                        </td>
                        <td>
                            {{ $address->country->name }}
                        </td>
                        @if($type === 'billing')
                        <td>
                            {{ $address->entity_type === 1 ? 'Magán' : 'Cég' }}
                        </td>
                        @endif
                        <td>
                            <div class="list-icons">
                                @can('address.edit')
                                    <a href="javascript:" data-toggle="modal" data-target="#{{ $type }}" wire:click="edit({{ $address->id }})" class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                                @endcan
                                @can('address.destroy')
                                    <button onclick="return confirm(` {{ __('messages.delete-confirm') }} `) || event.preventDefault() || event.stopImmediatePropagation()" wire:click="destroy({{ $address->id }})" class="btn btn-danger btn-sm btn-link list-icons-document text-danger-600 p-0"> <i class="icon-trash"></i></button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="p-4 text-center"><h5>Nincs megjeleníthető cím</h5></td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('customers::partials.addressmodal', ['id' => $type])
</div>
