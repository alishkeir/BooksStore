<div class="card">
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="actual">
                @include('admin::partials._search')
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
                                <a href="javascript:" wire:click.prevent="sortBy('title')" role="button"
                                    class="text-default">
                                    Név
                                    @include('admin::partials._sort-icons', ['field' => 'title'])
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" wire:click.prevent="sortBy('percent')" role="button"
                                    class="text-default">
                                    Jutalék
                                    @include('admin::partials._sort-icons', ['field' => 'percent'])
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" wire:click.prevent="sortBy('status')" role="button"
                                    class="text-default">
                                    Állapot
                                    @include('admin::partials._sort-icons', ['field' => 'status'])
                                </a>
                            </th>
                            <th>Beszállítóhoz tartozó készlet</th>
                            <th width="10%">{{ __('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Név</th>
                            <th>Jutalék</th>
                            <th>Állapot</th>
                            <th>Beszállítóhoz tartozó készlet</th>
                            <th>Műveletek</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($model as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td>
                                    @if (!is_null($supplier->deleted_at))
                                        <span class="text-muted">{{ $supplier->title }}</span>
                                    @else
                                        {{ $supplier->title }}
                                    @endif
                                </td>
                                <td>
                                    @if (!is_null($supplier->deleted_at))
                                        <span class="text-muted">{{ $supplier->percent }}%</span>
                                    @else
                                        {{ $supplier->percent }}%
                                    @endif
                                </td>
                                <td>
                                    @if ($supplier->status && is_null($supplier->deleted_at))
                                        <span class="badge badge-success">Látható</span>
                                    @elseif(!is_null($supplier->deleted_at))
                                        <span class="badge badge-danger">Törölt</span>
                                    @else
                                        <span class="badge badge-info">Inaktív</span>
                                    @endif
                                </td>
                                <td>
                                    <form
                                        action="{{ route('suppliers.download.inventory', ['supplier' => $supplier]) }}"
                                        class="" method="POST"
                                        onsubmit="return confirm('Egy kis türelemere lesz szükség, amíg az adatokat összeállítjuk!');">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-link  list-icons-document text-primary-600"><i
                                                class="icon-download"></i></button>

                                    </form>
                                </td>
                                <td>
                                    <div class="list-icons">
                                        @if (!is_null($supplier->deleted_at))
                                            @can('suppliers.restore')
                                                {{--                                            <form action="{{route('suppliers.restore', ['supplier' => $supplier])}}" class="d-inline" method="POST" onsubmit="return confirm('{{ __('messages.restore-confirm') }}');"> --}}
                                                {{--                                                @csrf --}}
                                                <button
                                                    class="btn btn-danger btn-sm btn-link list-icons-document text-danger-600 p-0"
                                                    onclick="return confirm('{{ __('messages.restore-confirm') }}')  || event.stopImmediatePropagation()"
                                                    wire:click="restore({{ $supplier->id }})"> <i
                                                        class="icon-reset"></i></button>
                                                {{--                                            </form> --}}
                                            @endcan
                                        @else
                                            @can('suppliers.show')
                                                <a href="{{ route('suppliers.show', ['supplier' => $supplier]) }}"
                                                    class="list-icons-document text-primary-600"><i
                                                        class="icon-eye"></i></a>
                                            @endcan
                                            @can('suppliers.storing')
                                                <a href="{{ route('suppliers.edit', ['supplier' => $supplier]) }}"
                                                    class="list-icons-document text-primary-600"><i
                                                        class="icon-pencil7"></i></a>
                                            @endcan
                                            @can('suppliers.destroy')
                                                <form action="{{ route('suppliers.destroy', ['supplier' => $supplier]) }}"
                                                    class="d-inline" method="POST"
                                                    onsubmit="return confirm('{{ __('messages.delete-confirm') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm btn-link list-icons-document text-danger-600 p-0">
                                                        <i class="icon-trash"></i></button>
                                                </form>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @include('admin::partials._pagination')
            </div>
        </div>
    </div>
</div>
