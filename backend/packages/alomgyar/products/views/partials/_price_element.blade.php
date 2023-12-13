{{--
    storeNumber = 0
    logo = alomgyar / olcsokonyvek / nagyker
    attribute = store_0
    --}}

<div class="col-md-4">
    <div class="card-header bg-transparent header-elements-inline">
        <img style="width:80px;" src="/logo-{{$logo}}.png">
        <div class="header-elements">
            <div class="form-check form-check-right">
                <label class="form-check-label">
                    Megjelenik
                    <input type="checkbox" name="{{$attribute}}"
                        @if ($model->$attribute ?? false) checked="" @endif value="1"
                        class="form-check-input">
                </label>
            </div>
        </div>
    </div>
    <div class="card-body">


        <div class="form-group row mb-0">
            <label class="col-lg-5 col-form-label font-weight-bold">Listaár</label>
            <input type="number" name="store[{{$storeNumber}}][price_list]"
                class="original form-control col-lg-7 @error('price_list') border-danger @enderror"
                value="@if($model ?? false){{ $model?->price($storeNumber)?->price_list ?? false }}@endif">
            @error('price_list')
                <span class="form-text text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group row mb-0">
            <label class="col-lg-5 col-form-label font-weight-bold">Akciós ár</label>
            <input type="number" name="store[{{$storeNumber}}][price_sale]"
                class="form-control col-lg-7 @error('price_sale') border-danger @enderror"
                value="@if($model ?? false){{ $model?->price($storeNumber)?->price_sale ?? false }}@endif"/>
            <input onkeyup="handlePercent(this)" placeholder="%"
                style="text-align:center;max-width: 30px; position: absolute; right: 19px; background-color: #eeeded;"
                class="form-control">
            @error('price_sale')
                <span class="form-text text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group row mb-0">
            <label class="col-lg-5 col-form-label font-weight-bold">Kosár ár</label>
            <input type="number" name="store[{{$storeNumber}}][price_cart]"
                class="form-control col-lg-7 @error('price_cart') border-danger @enderror"
                value="@if($model ?? false){{ $model?->price($storeNumber)?->price_cart ?? false }}@endif">
            @error('price_cart')
                <span class="form-text text-danger">{{ $message }}</span>
            @enderror
        </div>
        <hr>
        
        <div class="form-group row mb-0">
            <label class="col-lg-5 col-form-label font-weight-bold">Beserzési ár</label>
            <input type="number" name="store[{{$storeNumber}}][price_list_original]"
                class="original form-control col-lg-7 @error('price_list_original') border-danger @enderror"
                value="@if($model ?? false){{ $model?->price($storeNumber)?->price_list_original ?? false }}@endif">
            @error('price_list')
                <span class="form-text text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group row mb-0">
            <label class="col-lg-5 col-form-label font-weight-bold">Beserzési akciós ár</label>
            <input type="number" name="store[{{$storeNumber}}][price_sale_original]"
                class="original form-control col-lg-7 @error('price_sale_original') border-danger @enderror"
                value="@if($model ?? false){{ $model?->price($storeNumber)?->price_sale_original ?? false }}@endif">
            @error('price_list')
                <span class="form-text text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

</div>
