<div class="card">
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="actual">
                @include('admin::partials._search')
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                <a href="javascript:;" wire:click.prevent="sortBy('id')" role="button" class="text-default">
                                    #
                                    @include('admin::partials._sort-icons', ['field' => 'id'])
                                </a>
                            </th>
                            <th>
                                <a href="javascript:;" wire:click.prevent="sortBy('title')" role="button" class="text-default">
                                    Név
                                    @include('admin::partials._sort-icons', ['field' => 'title'])
                                </a>
                            </th>
                            <th>
                                <a href="javascript:;" wire:click.prevent="sortBy('status')" role="button" class="text-default">
                                    Állapot
                                    @include('admin::partials._sort-icons', ['field' => 'status'])
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
                            <th>Műveletek</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($model as $legal_owner)
                        <tr>
                            <td>{{ $legal_owner->id }}</td>
                            <td>{{ $legal_owner->title }}</td>
                            <td>@if( $legal_owner->status ?? false)<span class="badge badge-success">Látható</span>@endif</td>
                            <td>
                                <div class="list-icons">
                                    @can('legal_owners.storing')
                                    <a href="{{ route('legal_owners.edit', ['legal_owner' => $legal_owner]) }}" class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                                    @endcan
                                    @can('legal_owners.destroy')
                                        <form action="{{route('legal_owners.destroy', ['legal_owner' => $legal_owner])}}" class="d-inline" method="POST" onsubmit="return confirm({{ __('messages.delete-confirm') }});">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-link list-icons-document text-danger-600 p-0"> <i class="icon-trash"></i></button>
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
