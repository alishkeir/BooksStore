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
                                <a href="javascript:;" wire:click.prevent="sortBy('section')" role="button" class="text-default">
                                    Szekció
                                    @include('admin::partials._sort-icons', ['field' => 'section'])
                                </a>
                            </th>
                            <th width="10%">{{ __('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Név</th>
                            <th>Szekció</th>
                            <th>műveletek</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($model as $synchronization)
                        <tr>
                            <td>{{ $synchronization->id }}</td>
                            <td>{{ $synchronization->title }}</td>
                            <td>{{ $synchronization->section }}</td>
                            <td>
                                <div class="list-icons">
                                    @can('synchronizations.show')
                                    <a href="{{ route('synchronizations.show', ['synchronization' => $synchronization]) }}" class="list-icons-document text-primary-600"><i class="icon-eye"></i></a>
                                    @endcan
                                    @can('synchronizations.destroy')
                                        <form action="{{route('synchronizations.destroy', ['synchronization' => $synchronization])}}" class="d-inline" method="POST" onsubmit="return confirm({{ __('messages.delete-confirm') }});">
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
