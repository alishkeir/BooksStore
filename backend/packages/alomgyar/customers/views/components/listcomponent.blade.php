<div class="card">
    <div class="card-header bg-transparent">
        @include('customers::components._search')
    </div>
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
                    <a href="javascript:" wire:click.prevent="sortBy('email')" role="button" class="text-default">
                        E-mail
                        @include('admin::partials._sort-icons', ['field' => 'email'])
                    </a>
                </th>
                <th>
                    <a href="javascript:" wire:click.prevent="sortBy('lastname')" role="button" class="text-default">
                        Név
                        @include('admin::partials._sort-icons', ['field' => 'lastname'])
                    </a>
                </th>
                <th>
                    <a href="javascript:" wire:click.prevent="sortBy('created_at')" role="button" class="text-default">
                        Regisztrált
                        @include('admin::partials._sort-icons', ['field' => 'created_at'])
                    </a>
                </th>
                <th>
                    <a href="javascript:" wire:click.prevent="sortBy('status')" role="button" class="text-default">
                        Állapot
                        @include('admin::partials._sort-icons', ['field' => 'status'])
                    </a>
                </th>
                <th>Kedvezmény</th>
                <th>{{ __('general.actions') }}</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>E-mail</th>
                <th>Név</th>
                <th>Regisztrált</th>
                <th>Állapot</th>
                <th>Kedvezmény</th>
                <th>Műveletek</th>
            </tr>
            </tfoot>
            <tbody>
            @foreach($model as $customer)
                <tr style="border-left:3px solid  @if($customer->store == 0) #e62934; @elseif($customer->store == 1) #fbc72e @elseif($customer->store==2) #4971ff @endif ">
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->lastname }} {{ $customer->firstname }}</td>
                    <td>{{ $customer->created_at }}</td>
                    <td>@if($customer->status > 0)Aktív @else Inaktív @endif</td>
                    <td>{{ $customer->personal_discount_alomgyar }} / {{ $customer->personal_discount_all }}</td>
                    <td>
                        <div class="list-icons">
                            @can('customers.storing')
                                <a href="{{ route('customers.edit', ['customer' => $customer]) }}"
                                   class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                            @endcan
                            {{--}}
                            @can('customers.destroy')
                                <form action="{{route('customers.destroy', ['customer' => $customer])}}"
                                      class="d-inline" method="POST"
                                      onsubmit="return confirm({{ __('messages.delete-confirm') }});">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger btn-sm btn-link list-icons-document text-danger-600 p-0">
                                        <i class="icon-trash"></i></button>
                                </form>
                            @endcan
                            --}}
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('admin::partials._pagination')
    </div>
</div>
