<div class="row">
    <div class="col-md-12">
        @if ($model ?? false)
            <div class="card">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="card-title font-weight-semibold">Kalkulált aktuális ár (10 percenként frissül, ha
                        változott)</span>
                </div>
                <div class="card-footer row">
                    <div class="col-md-4">
                        <div class="card-body p-0">
                            <div class="form-group row mb-0">
                                <livewire:products::price productid="{{ $model->id }}" store="0" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-body p-0">
                            <div class="form-group row mb-0">
                                <livewire:products::price productid="{{ $model->id }}" store="1" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-body p-0">
                            <div class="form-group row mb-0">
                                <livewire:products::price productid="{{ $model->id }}" store="2" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Prices -->
        <div class="card">

            <div class="card-header">
                <span class="card-title font-weight-semibold">Termék ára</span>
            </div>
            <div class="row">

                @include('products::partials._price_element', [
                    'storeNumber' => 0,
                    'logo' => 'alomgyar',
                    'attribute' => 'store_0',
                ])

                @include('products::partials._price_element', [
                    'storeNumber' => 1,
                    'logo' => 'olcsokonyvek',
                    'attribute' => 'store_1',
                ])
                @include('products::partials._price_element', [
                    'storeNumber' => 2,
                    'logo' => 'nagyker',
                    'attribute' => 'store_2',
                ])

            </div>

        </div>
        <!-- /Prices -->

        @if ($model ?? false)
            <!-- Promotions -->
            @foreach ($model->promotionsWithFlashSales as $promotion)
                {{-- @foreach ($model->promotions as $promotion) --}}
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="card-title font-weight-semibold">{{ $promotion->title }}</span>
                        <div class="header-elements">
                            <div class="form-check form-check-right">
                                <label class="form-check-label">
                                    Érvényes: <strong>{{ $promotion->active_from }}</strong> -
                                    <strong>{{ $promotion->active_to }}</strong>
                                </label>
                            </div>
                            <img src="/akcio.png"
                                style="max-height:40px; border-radius:5px; margin-top:-10px; margin-bottom:-10px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card-body">
                                <div class="form-group row mb-0">
                                    <label class="col-lg-5 col-form-label font-weight-bold"
                                        style="color:#e62934;">Akciós ár</label>
                                    @if ($promotion->store_0)
                                        <input type="number"
                                            name="promotion_price[{{ $promotion->price($model->id)->id }}][0]"
                                            class="form-control col-lg-7 "
                                            value="{{ $promotion->price($model->id)->price_sale_0 }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-body">
                                <div class="form-group row mb-0">
                                    <label class="col-lg-5 col-form-label font-weight-bold"
                                        style="color:#fbc72e;">Akciós ár</label>
                                    @if ($promotion->store_1)
                                        <input type="number"
                                            name="promotion_price[{{ $promotion->price($model->id)->id }}][1]"
                                            class="form-control col-lg-7 "
                                            value="{{ $promotion->price($model->id)->price_sale_1 }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-body">
                                <div class="form-group row mb-0">
                                    <label class="col-lg-5 col-form-label font-weight-bold"
                                        style="color:#4971ff;">Akciós ár</label>
                                    @if ($promotion->store_2)
                                        <input type="number"
                                            name="promotion_price[{{ $promotion->price($model->id)->id }}][2]"
                                            class="form-control col-lg-7 "
                                            value="{{ $promotion->price($model->id)->price_sale_2 }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
            <!-- /Promotions -->
        @endif
    </div>
</div>
