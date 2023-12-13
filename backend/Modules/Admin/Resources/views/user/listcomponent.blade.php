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
                                    <a href="javascript:;" wire:click.prevent="sortBy('lastname')" role="button" class="text-default">
                                        Vezetéknév
                                        @include('admin::partials._sort-icons', ['field' => 'lastname'])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:;" wire:click.prevent="sortBy('firstname')" role="button" class="text-default">
                                        Keresztnév
                                        @include('admin::partials._sort-icons', ['field' => 'firstname'])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:;" wire:click.prevent="sortBy('name')" role="button" class="text-default">
                                        Felhasználónév
                                        @include('admin::partials._sort-icons', ['field' => 'name'])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:;" wire:click.prevent="sortBy('email')" role="button" class="text-default">
                                        Email
                                        @include('admin::partials._sort-icons', ['field' => 'email'])
                                    </a>
                                </th>
                                <th>
                                    <a href="javascript:;" wire:click.prevent="sortBy('status')" role="button" class="text-default">
                                        Státusz
                                        @include('admin::partials._sort-icons', ['field' => 'status'])
                                    </a>
                                </th>
                                <th>Műveletek</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Vezetéknév</th>
                                <th>Keresztnév</th>
                                <th>Felhasználónév</th>
                                <th>Email</th>
                                <th>Státusz</th>
                                <th>Műveletek</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @foreach($model as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->lastname}}</td>
                            <td>{{$item->firstname}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->email}}</td>
                            <td><span class="badge badge-{{$item->status == 10 ? 'success' : 'secondary'}}">{{$item->status == 10 ? 'Active' : 'Inactive'}}</span></td>
                            <td>
                                <div class="list-icons">
                                    <a href="{{ route('user.edit', ['user' => $item->id]) }}" class="list-icons-item text-primary-600"><i class="icon-pencil7"></i></a>
                                    @can('delete user')
                                    <form action="{{ route('user.destroy', ['user' => $item->id, 'type' => 'delete']) }}" class="d-inline" method="POST" onsubmit="return confirm('Biztosan törölni akarod ezt a usert?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-link list-icons-item text-danger-600 p-0"> <i class="icon-trash"></i></button>
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
