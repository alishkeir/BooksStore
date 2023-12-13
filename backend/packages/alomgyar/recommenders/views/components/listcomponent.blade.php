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
                        <th>Megvásárolt termék</th>
                        <th>Ajánlott termék</th>
                        <th>Kiküldés ideje</th>
                        <th width="10%">{{ __('general.actions') }}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Megvásárolt termék</th>
                        <th>Ajánlott termék</th>
                        <th>Kiküldés ideje</th>
                        <th>Műveletek</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($model as $recommender)
                        <tr>
                            <td>{{ $recommender->id }}</td>
                            <td>{{ $recommender->originalProduct->title ?? '' }}</td>
                            <td>{{ $recommender->promotedProduct->title ?? ''  }}</td>
                            <td>{{ $recommender->release_date }}</td>
                            <td>
                                <div class="list-icons">
                                    @can('recommenders.show')
                                        <a href="{{ route('recommenders.show', $recommender) }}"
                                           class="list-icons-document text-primary-600"><i class="icon-eye"></i></a>
                                    @endcan
                                    @can('recommenders.storing')
                                        <a href="{{ route('recommenders.edit', $recommender) }}"
                                           class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                                    @endcan
                                    @can('recommenders.destroy')
                                        <form action="{{route('recommenders.destroy', $recommender)}}" class="d-inline"
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
