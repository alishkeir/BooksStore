<div>
    <div class="d-md-flex align-items-md-start">

        <div
            class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left border-0 shadow-0 sidebar-expand-md"
            style="width:13rem">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Filter -->
                <div class="card">
                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
                        <li class="nav-item"><a href="#tab-spec" class="nav-link legitRipple active show"  data-toggle="tab">Szűrő</a></li>
                    </ul>

                    <div class="tab-content border-top-0 rounded-top-0 mb-0">

                        <div class="tab-pane fade active show" id="tab-spec">

                            <div class="card-body">
                                <form action="#">
                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <input wire:model="s" type="search" class="form-control" placeholder="Keresés">
                                        <div class="form-control-feedback">
                                            <i class="icon-search4 text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <select wire:model="filters.shipping_method"
                                                class="form-control  select select2" name="shipping_method"
                                                onChange="handleSelect(this)">
                                            <option selected value="">Szállítási mód</option>
                                            @foreach ($shipping as $method)
                                                <option value="{{ $method->id }}">{{ $method->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-control-feedback">
                                            <i class="icon-bag text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <select wire:model="filters.payment_method" class="form-control  select select2"
                                                name="payment_method" onChange="handleSelect(this)">
                                            <option selected value="">Fizetési mód</option>
                                            @foreach ($payment as $method)
                                                <option value="{{ $method->id }}">{{ $method->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-control-feedback">
                                            <i class="icon-cash2 text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-feedback form-group-feedback-left">
                                        <select class="form-control select select2" name="subcategory"
                                                onChange="handleSelect(this)">
                                            <option selected value="">Boltok</option>
                                        </select>
                                        <div class="form-control-feedback">
                                            <i class="icon-location4 text-muted"></i>
                                        </div>
                                    </div>
                                    <h6>Időintervallum:</h6>
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <input type="datetime-local" wire:model="filters.from" class="form-control" value="{{ $filters->from ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <input type="datetime-local" wire:model="filters.to" class="form-control" value="{{ $filters->to ?? '' }}">
                                        </div>
                                    </div>

                                </form>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <!-- /sidebar content -->

        </div>

        <div class="flex-fill">
            <div class="row">
                <div class="col-md-2">
                    <div class="card card-body py-3" style="min-height:92px;">
                            <div class="media">
                                <div class="mr-3 align-self-center">
                                    <i class="icon-check icon-2x opacity-75"></i>
                                </div>

                                <div class="media-body text-right">
                                    <h3 class="mb-0">{{ count($selection) }}</h3>
                                    <span class="text-uppercase font-size-xs">Kijelölés </span>
                                </div>
                            </div>
                    </div>
                </div>
                @if($selection ?? false)
                <div class="col-md-3">
                    <div class="card p-2" style="min-height:92px;">
                        <p>Excel lista</p>
                        <a wire:click="generateXmlFromSelection()" class="btn btn-success btn-sm text-white d-block"><i class="icon-file-excel mr-3"></i> Letöltés</a>
                    </div>
                </div>
                
                
                @endif
            </div>
            
            <div class="card border-top-2 @if($type == 'ok') border-top-success @elseif($type == 'almost') border-top-warning @else border-top-danger @endif">
                <div class="card-body">
                    <table class="table table-striped mb-2">
                        <thead>
                        <tr>
                            <th>
                                <a href="javascript:" role="button" class="text-default">
                                   <input type="checkbox" wire:click="reverseAll">
                                </a>
                            </th>
                            <th>Tétel / Rendelésszám</th>
                            <th>
                                <a href="javascript:" wire:click.prevent="sortBy('price')" role="button" class="text-default">
                                    Ár
                                    @include('admin::partials._sort-icons', ['field' => 'price'])
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" wire:click.prevent="sortBy('shipping_fee')" role="button" class="text-default">
                                    Szállítási mód/díj
                                    @include('admin::partials._sort-icons', ['field' => 'shipping_fee'])
                                </a>
                            </th>
                            <th>Fizetési mód/díj</th>
                            
                            <th>Isbn / Kiadó</th>
                            <th>
                                <a href="javascript:" wire:click.prevent="sortBy('created_at')" role="button"
                                   class="text-default">
                                    Dátum
                                    @include('admin::partials._sort-icons', ['field' => 'created_at'])
                                </a>
                            </th>
                            <th>Készlet</th>
                            <th>Állapot</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Tétel / Rendelésszám</th>
                            <th>Ár</th>
                            <th>Szállítási mód/díj</th>
                            <th>Fizetési mód/díj</th>
                            <th>Isbn / Kiadó</th>
                            <th>Dátum</th>
                            <th>Készlet</th>
                            <th>Állapot</th>

                        </tr>
                        </tfoot>
                        <tbody>
                        @forelse($model as $orderitem)
                            <tr style="border-left:3px solid @if($orderitem->store == 0) #e62934; @elseif($orderitem->store==1) #fbc72e @elseif($orderitem->store==2) #4971ff;  @else  #000;  @endif">
                                <td><input value="1" wire:model="selection.{{$orderitem->id}}" type="checkbox"></td>

                                <td @if($orderitem->type == 1) title="Tartalmaz e-bookot" class="text-success" @endif>
                                    <strong><a class="text-dark"
                                               href="/gephaz/products/{{ $orderitem->product->id }}/edit">{{$orderitem->product->title}}</a></strong>
                                    <br> <a
                                        href="/gephaz/orders/{{ $orderitem->order->id }}">{{$orderitem->order->order_number}}</a>
                                </td>
                                <td style="white-space:nowrap">{{$orderitem->price}} Ft
                                    <br><small>{{$orderitem->quantity}} db</small></td>
                                <td>
                                    <strong
                                        style="white-space: nowrap;">{{ $orderitem->order->shippingMethod?->name }} </strong>
                                    <br><small>{{$orderitem->order->shipping_fee}} Ft</small>
                                </td>
                                <td>
                                    <strong
                                        style="white-space: nowrap;">{{ $orderitem->order->paymentMethod?->name }}</strong>
                                    <br><small>{{$orderitem->order->payment_fee ?? 'N/A'}} Ft</small>
                                </td>
                                <td>
                                    <strong>{{$orderitem->isbn}}</strong>
                                    <br>{{$orderitem->product?->publisher?->title}}
                                </td>
                                <td style="white-space:nowrap" class="px-0">
                                    <strong>{{ substr($orderitem->order->created_at, 0, 10)}}</strong>
                                    <br>{{ substr($orderitem->order->created_at, 10, 9)}}
                                </td>
                                <td class="text-right px-1" style="white-space:nowrap">
                                    <strong title="Összesen">Össz: {{$orderitem->product->stock}} </strong>
                                    <br>Fő R.: <span title="GPS">{{$orderitem->product->stockGPS}}</span>
                                </td>
                                <td>
                                    {!! $orderitem->order->status_html !!}
                                    <small>{!! $orderitem->order->payment_status_html !!}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="p-4 text-center"><h5>Nincs megjeleníthető rendelés</h5></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    @include('admin::partials._pagination')
                </div>
            </div>
        </div>

        <style>
            table.table td {
                padding-top: 4px;
                padding-bottom: 4px;
            }

            .nav-item {
                line-height: 11px !important;
            }
        </style>
    </div>
