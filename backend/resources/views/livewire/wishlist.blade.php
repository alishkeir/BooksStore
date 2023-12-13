<div>


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
                            <a href="javascript:" role="button" class="text-default">
                                Ügyfél
                            </a>
                        </th>
                        <th>
                            <a href="javascript:" role="button" class="text-default">
                                Termék
                            </a>
                        </th>
                        <th>
                            <a href="javascript:" wire:click.prevent="sortBy('created_at')" role="button" class="text-default">
                                Időpont
                                @include('admin::partials._sort-icons', ['field' => 'created_at'])
                            </a>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Ügyfél</th>
                        <th>Termék</th>
                        <th>Időpont</th>
                    </tr>
                </tfoot>
                <tbody>
                @forelse($model as $wishitem)
                <tr style="border-left:3px solid @if($wishitem->customer?->store == 0) #e62934; @elseif($wishitem->customer?->store==1) #fbc72e @else  #4971ff;  @endif" >
                    <td>{{$wishitem->id}}</td>

                    <td>{{$wishitem->customer?->full_name}}</td>
                    <td><i class="icon icon-heart5"></i> {{$wishitem->product->title}}</td>
                    <td>{{$wishitem->created_at}}</td>
                
                @empty
                <tr>
                    <td colspan="10" class="p-4 text-center"><h5>Nincs megjeleníthető elem</h5></td>
                </tr>
                @endforelse
                </tbody>
            </table>

            @include('admin::partials._pagination')
        </div>
    </div>

</div>
