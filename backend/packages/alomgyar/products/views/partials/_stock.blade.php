<div class="card">
    <div class="card-header bg-transparent header-elements-inline">
        <h6 class="card-title font-weight-semibold">
            <i class="icon-package mr-2"></i>
            Raktárkészlet
        </h6>
        <div class="header-elements">
            <span class="text-muted"></span>
        </div>
    </div>
    <div class="row card-body">
        <div class="col-md-5">
            <table class="table table-striped mb-4">
                <tbody>
                @foreach ($model->inventories as $inventory)
                    @if($loop->odd)
                        <tr>
                            <td>{{$inventory->warehouse?->title}}</td>
                            <td><strong>{{ $inventory->stock }}</strong> db
                                @if($inventory->warehouse?->title === 'Webshop')
                                    <span>({{ $model->webshop_orders_count }} db foglalás a webshopban)</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-5">
            <table class="table table-striped mb-4">
                <tbody>
                @foreach ($model->inventories as $inventory)
                    @if($loop->even)
                        <tr>
                            <td>{{$inventory->warehouse?->title}}</td>
                            <td><strong>{{ $inventory->stock }}</strong> db
                                @if($inventory->warehouse?->title === 'Webshop')
                                    <span>({{ $model->webshop_orders_count }} db foglalás a webshopban)</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-transparent header-elements-inline">
        <h6 class="card-title font-weight-semibold">
            <i class="icon-folder6 mr-2"></i>
            Bizonylatok
        </h6>
        <div class="header-elements">
            <span class="text-muted"></span>
        </div>
    </div>
    @livewire('product_movements::listcomponent', ['productId' => $model->id])
</div>
