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
                                    <a href="javascript:;" wire:click.prevent="sortBy('description')" role="button" class="text-default">
                                        Leírás
                                        @include('admin::partials._sort-icons', ['field' => 'description'])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:;" wire:click.prevent="sortBy('subject_type')" role="button" class="text-default">
                                        Entitás
                                        @include('admin::partials._sort-icons', ['field' => 'subject_type'])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:;" wire:click.prevent="sortBy('subject_id')" role="button" class="text-default">
                                        Entitás ID
                                        @include('admin::partials._sort-icons', ['field' => 'subject_id'])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:;" wire:click.prevent="sortBy('causer_id')" role="button" class="text-default">
                                        Felhasználó
                                        @include('admin::partials._sort-icons', ['field' => 'causer_id'])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:;" wire:click.prevent="sortBy('properties')" role="button" class="text-default">
                                        Adatok
                                        @include('admin::partials._sort-icons', ['field' => 'properties'])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:;" wire:click.prevent="sortBy('created_at')" role="button" class="text-default">
                                        Készült
                                        @include('admin::partials._sort-icons', ['field' => 'created_at'])
                                    </a>
                                </th>
                                <th>Részletek</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Leírás</th>
                                <th>Entitás</th>
                                <th>Entitás ID</th>
                                <th>Felhasználó</th>
                                <th>Adatok</th>
                                <th>Készült</th>
                                <th>Részletek</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @foreach($model as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->description}}</td>
                            <td>{{$item->subject_type}}</td>
                            <td>{{$item->subject_id}}</td>
                            <td>{{$item->created}}</td>
                            <td>{{\Illuminate\Support\Str::limit($item->properties, 150, $end='...')}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>
                                <div class="list-icons">
                                    <a href="{{ route('activity_logs.show', ['activity_log' => $item->id]) }}" class="list-icons-item text-primary-600"><i class="icon-eye"></i></a>
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
