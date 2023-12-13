<div class="tab-pane fade show" id="preorders">
    <div class="row">
        <div class="col-lg-12">

            <div class="card-group-control card-group-control-right">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $model->full_name }} előjegyzései
                        </h3>
                    </div>
                </div>
                @foreach($model->preorders as $product)
                    <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="p-2">
                                        <img src="{{ $product->cover }}" alt="" class="img-thumbnail" style="max-width: 70px;/* grid-column:1; */grid-column: 1;grid-row: 1 / 3;">
                                    </div>
                                    <div class="p-2">
                                        <p class="font-weight-bold">{{ $product->title }}</p>
                                        <p class="text-muted">
                                                {{ $product->primaryAuthor->first()?->title }}
                                        </p>
                                        <strike class="text-muted">{{ $product->price($model->store)->price_list }} Ft</strike>
                                        <h6 class="font-weight-bold">{{ $product->price($model->store)->price_sale }} Ft</h6>
                                    </div>
                                    {{--}}
                                    <div class="p-2 d-flex justify-content-center align-items-center">
                                        <a href="javascript:;"><i class="icon-trash-alt mr-3 text-danger-600"></i></a>
                                    </div>--}}
                                </div>
                            </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
