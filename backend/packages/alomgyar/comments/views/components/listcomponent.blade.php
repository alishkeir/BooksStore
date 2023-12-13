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
                                <a href="javascript:;" role="button" class="text-default">
                                    Hozzászóló
                                </a>
                            </th>
                            <th>
                                <a href="javascript:;" role="button" class="text-default">
                                    Termék
                                    
                                </a>
                            </th>
                            <th>
                                <a href="javascript:;" role="button" class="text-default">
                                    Hozzászólás
                                </a>
                            </th>
                            <th>
                                <a href="javascript:;" wire:click.prevent="sortBy('created_at')" role="button" class="text-default">
                                    Dátum
                                    @include('admin::partials._sort-icons', ['field' => 'created_at'])
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
                            <th>Hozzászóló</th>
                            <th>Termék</th>
                            <th>Hozzászólás</th>
                            <th>Dátum</th>
                            <th>Műveletek</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($model as $comment)
                        <tr style="border-left:3px solid  @if($comment->store == 0) #e62934; @elseif($comment->store==1) #fbc72e @else  #4971ff;  @endif ">
                            <td>{{ $comment->id }}</td>
                            <td>
                                <a href="{{ route('customers.edit', ['customer' => $comment->customer]) }}">{{$comment->customer->username}}</a>
                                <br><small>{{$comment->customer->full_name}}</small>
                            </td>
                            <td>
                                <a href="{{ route('products.edit', ['product' => $comment->product]) }}">{{$comment->product->title}}</a>
                            </td>
                            <td><small>{{ $comment->comment }}</small></td>
                            <td>{{ $comment->created_at }}</td>
                            <td>{!! $comment->status_html !!}</td>
                            <td>
                                <div class="list-icons">
                                    @can('comments.storing')
                                    <a href="{{ route('comments.edit', ['comment' => $comment]) }}" class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
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
