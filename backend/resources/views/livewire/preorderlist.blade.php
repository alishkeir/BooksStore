<div>
<div class="row">
    <div class="col-6">
    <div class="card">
        <div class="card-body">

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
                                Ügyfél
                            </a>
                        </th>
                        <th>
                            <a href="javascript:;" role="button" class="text-default">
                                Termék
                            </a>
                        </th>
                        <th>
                            <a href="javascript:;" wire:click.prevent="sortBy('created_at')" role="button" class="text-default">
                                Időpont
                                @include('admin::partials._sort-icons', ['field' => 'created_at'])
                            </a>
                        </th>
                        <th>
                            <a href="javascript:;" wire:click.prevent="sortBy('notified_at')" role="button" class="text-default">
                                Levél kiküldve
                                @include('admin::partials._sort-icons', ['field' => 'notified_at'])
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
                        <th>Levél kiküldve</th>
                    </tr>
                </tfoot>
                <tbody>
                @forelse($model as $preorder)
                <tr style="border-left:3px solid @if($preorder->customer?->store == 0) #e62934; @elseif($preorder->customer?->store==1) #fbc72e @else  #4971ff;  @endif" >
                    <td>{{$preorder->id}}</td>

                    <td>{{$preorder->customer?->full_name}}</td>
                    <td> {{$preorder->product->title}}</td>
                    <td>{{$preorder->created_at}}</td>
                    <td>{{$preorder->notified_at}}</td>

                @empty
                <tr>
                    <td colspan="10" class="p-4 text-center"><h5>Nincs megjeleníthető előrendelés</h5></td>
                </tr>
                @endforelse
                </tbody>
            </table>

            <div class="row">
                <div class="col-md-2">

                    <span class="badge badge-light">{{ $model->firstItem() }}</span> - <span class="badge badge-light">{{ $model->lastItem() }}</span> / <span class="badge badge-light">{{ $model->total() }}</span> elem
                </div>
                <div class="col-md-8 justify-content-end text-center">
                    {{ $model->links() }}
                </div>
                <div class="col-md-2">
                    <div class="float-right">
                        <select class="form-control" wire:model="perPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="300">300</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="col-6">
    <div class="card">
        <div class="card-body">

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
                                Reg. nélküli előjegyző
                            </a>
                        </th>
                        <th>
                            <a href="javascript:;" wire:click.prevent="sortBy('created_at')" role="button" class="text-default">
                                Időpont
                                @include('admin::partials._sort-icons', ['field' => 'created_at'])
                            </a>
                        </th>
                        <th>
                            <a href="javascript:;" wire:click.prevent="sortBy('notified_at')" role="button" class="text-default">
                                Levél kiküldve
                                @include('admin::partials._sort-icons', ['field' => 'notified_at'])
                            </a>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Reg. nélküli előjegyző</th>
                        <th>Időpont</th>
                        <th>Levél kiküldve</th>
                    </tr>
                </tfoot>
                <tbody>
                @forelse($publicmodel as $preorder)
                <tr style="border-left:3px solid @if($preorder->store == 0) #e62934; @elseif($preorder->store==1) #fbc72e @else  #4971ff;  @endif" >
                    <td>{{$preorder->id}}</td>

                    <td>{{$preorder->email}}</td>
                    <td>{{$preorder->created_at}}</td>
                    <td>{{$preorder->notified_at}}</td>

                @empty
                <tr>
                    <td colspan="10" class="p-4 text-center"><h5>Nincs megjeleníthető előrendelés</h5></td>
                </tr>
                @endforelse
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-2">

                    <span class="badge badge-light">{{ $publicmodel->firstItem() }}</span> - <span class="badge badge-light">{{ $publicmodel->lastItem() }}</span> / <span class="badge badge-light">{{ $publicmodel->total() }}</span> elem
                </div>
                <div class="col-md-8 justify-content-end text-center">
                    {{ $publicmodel->onEachSide(1)->links() }}
                </div>
                <div class="col-md-2">
                    <div class="float-right">
                        <select class="form-control" wire:model="perPage">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="300">300</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div></div>
</div>
