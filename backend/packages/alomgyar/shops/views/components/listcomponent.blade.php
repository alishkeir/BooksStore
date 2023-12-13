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
                                <a href="javascript:" wire:click.prevent="sortBy('status')" role="button"
                                    class="text-default">
                                    Állapot
                                    @include('admin::partials._sort-icons', ['field' => 'status'])
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" wire:click.prevent="sortBy('show_shipping')" role="button"
                                    class="text-default">
                                    Szállításnál látható
                                    @include('admin::partials._sort-icons', ['field' => 'show_shipping'])
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" role="button" class="text-default">
                                    Megjelenik
                                </a>
                            </th>
                            <th width="10%">{{ __('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Név</th>
                            <th>Állapot</th>
                            <th>Megjelenik</th>
                            <th>Műveletek</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($model as $shop)
                            <tr>
                                <td>{{ $shop->id }}</td>
                                <td>{{ $shop->title }}</td>
                                <td>
                                    @if ($shop->status ?? false)
                                        <span class="badge badge-success">Látható</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($shop->show_shipping ?? false)
                                        <span class="badge badge-success">Szállításnál Látható</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($shop->store_0 == 1)
                                        <i class="icon icon-check text-danger"></i>
                                    @endif
                                    @if ($shop->store_1 == 1)
                                        <i class="icon icon-check" style="color:#fbc72e;"></i>
                                    @endif
                                    @if ($shop->store_2 == 1)
                                        <i class="icon icon-check" style="color:#4971ff;"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="list-icons">
                                        @can('shops.show')
                                            <a href="{{ route('shops.show', ['shop' => $shop]) }}"
                                                class="list-icons-document text-primary-600"><i class="icon-eye"></i></a>
                                        @endcan
                                        @can('shops.storing')
                                            <a href="{{ route('shops.edit', ['shop' => $shop]) }}"
                                                class="list-icons-document text-primary-600"><i
                                                    class="icon-pencil7"></i></a>
                                        @endcan
                                        @can('shops.destroy')
                                            <form action="{{ route('shops.destroy', ['shop' => $shop]) }}" class="d-inline"
                                                method="POST"
                                                onsubmit="return confirm({{ __('messages.delete-confirm') }});">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger btn-sm btn-link list-icons-document text-danger-600 p-0">
                                                    <i class="icon-trash"></i></button>
                                            </form>
                                        @endcan
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
