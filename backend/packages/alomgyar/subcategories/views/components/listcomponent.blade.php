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
                            <th> Szülő kategóriák </th>
                            <th width="10%">{{ __('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Név</th>
                            <th>Állapot</th>
                            <th>Szülő kategóriák</th>
                            <th>Műveletek</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($model as $subcategory)
                        <tr>
                            <td>{{ $subcategory->id }}</td>
                            <td>{{ $subcategory->title }}</td>
                            <td>@if( $subcategory->status ?? false)<span class="badge badge-success">Látható</span>@endif</td>
                            
                            <td><small>@forelse($subcategory->categories ?? [] as $item) <span class="badge badge-primary">{{$item->title}}</span> @empty <span class="badge badge-danger">N/A</span> @endforelse</small></td>
                            <td>
                                <div class="list-icons">
                                    @can('subcategories.show')
                                    <a href="{{ route('subcategories.show', ['subcategory' => $subcategory]) }}" class="list-icons-document text-primary-600"><i class="icon-eye"></i></a>
                                    @endcan
                                    @can('subcategories.storing')
                                    <a href="{{ route('subcategories.edit', ['subcategory' => $subcategory]) }}" class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                                    @endcan
                                    @can('subcategories.destroy')
                                        <form action="{{route('subcategories.destroy', ['subcategory' => $subcategory])}}" class="d-inline" method="POST" onsubmit="return confirm({{ __('messages.delete-confirm') }});">
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
