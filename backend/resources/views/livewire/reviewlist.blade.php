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
                            <a href="javascript:" role="button" class="text-default">
                                Értékelés
                            </a>
                        </th>
                        <th>
                            <a href="javascript:" wire:click.prevent="sortBy('created_at')" role="button" class="text-default">
                                Időpont
                                @include('admin::partials._sort-icons', ['field' => 'created_at'])
                            </a>
                        </th>
                        {{--<th>{{ __('general.actions') }}</th>--}}
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Ügyfél</th>
                        <th>Termék</th>
                        <th>Értékelés</th>
                        <th>Időpont</th>
                        {{--<th>{{ __('general.actions') }}</th>--}}
                    </tr>
                </tfoot>
                <tbody>
                @forelse($model as $review)
                <tr style="border-left:3px solid @if($review->store == 0) #e62934; @elseif($review->store==1) #fbc72e @else  #4971ff;  @endif" >
                    <td>{{$review->id}}</td>

                    <td>{{$review->customer?->full_name}}</td>
                    <td>{{$review->product->title}}</td>
                    <td>
                        <div class="mt-2 mt-sm-0">
                            <i class="@if($review->review > 0) icon-star-full2 @else icon-star-empty3 @endif font-size-base text-warning-300"></i>
                            <i class="@if($review->review > 1) icon-star-full2 @else icon-star-empty3 @endif font-size-base text-warning-300"></i>
                            <i class="@if($review->review > 2) icon-star-full2 @else icon-star-empty3 @endif font-size-base text-warning-300"></i>
                            <i class="@if($review->review > 3) icon-star-full2 @else icon-star-empty3 @endif font-size-base text-warning-300"></i>
                            <i class="@if($review->review > 4) icon-star-full2 @else icon-star-empty3 @endif font-size-base text-warning-300"></i>
                        </div>
                    </td>
                    <td>{{$review->created_at}}</td>
                    {{--}}
                    <td>
                        <div class="list-icons">
                            <a href="/gephaz/managment_templates/order" class="btn alpha-primary text-primary-800 btn-icon ml-2 legitRipple"><i class="icon-enlarge7"></i></a>                                                                   
                        </div>
                    </td>--}}
                
                @empty
                <tr>
                    <td colspan="10" class="p-4 text-center"><h5>Nincs megjeleníthető értékelés</h5></td>
                </tr>
                @endforelse
                </tbody>
            </table>

            @include('admin::partials._pagination')
        </div>
    </div>

</div>
