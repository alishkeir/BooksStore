<div>
    <div class="flex-fill">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body border-top">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Rendelésszám</th>
                                <th>Szállítási mód/díj</th>
                                <th>Fizetési mód/díj</th>
                                <th>Ügyfél</th>
                                <th>Dátum</th>
                                <th>Állapot</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                style="border-left:3px solid @if ($model->store == 0) #e62934; @elseif($model->store == 1) #fbc72e @elseif($model->store == 2) #4971ff;  @else  #000; @endif">

                                <td
                                    @if ($model->has_ebook) title="Tartalmaz e-bookot" class="text-success" @endif>
                                    {{ $model->order_number }}
                                </td>
                                <td>
                                    <strong style="white-space: nowrap;">{{ $model->shippingMethod?->name }}
                                    </strong>
                                    <br><small>{{ $model->shipping_fee }} Ft</small>
                                </td>
                                <td>
                                    <strong style="white-space: nowrap;">{{ $model->paymentMethod?->name }}</strong>
                                    <br><small>{{ $model->payment_fee ?? 'N/A' }} Ft</small>
                                </td>
                                <td>
                                    <strong
                                        style="white-space: nowrap;">{{ $model->billingAddress?->full_name }}</strong>
                                    <br>
                                    <small>{{ $model->billingAddress?->city }}
                                        {{ $model->billingAddress?->address }}</small>
                                </td>
                                <td>
                                    <strong>{{ substr($model->created_at, 0, 10) }}</strong>
                                    <br>{{ substr($model->created_at, 10, 9) }}
                                </td>
                                <td>
                                    {!! $model->status_html !!}
                                    <small>{!! $model->payment_status_html !!}</small>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /mail details -->

                    <!-- Order container -->
                    <div class="card-body">
                        <div class="overflow-auto mw-100">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="bg-primary-300">
                                    <tr>
                                        <th>Tételek</th>
                                        <th>Info</th>
                                        <th>Áfa</th>
                                        <th>Egységár</th>
                                        <th>db</th>
                                        <th>Összesen</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($model->orderItems as $item)
                                        @if ($item->product ?? false)
                                            <tr @if ($item->product->type == 1) class="text-success" @endif>
                                                <td class="py-2">
                                                    <a href="/gephaz/products/{{ $item->product->id }}/edit"
                                                       target="_blank" class="mb-0 h5">
                                                        {{ $item->product->title }}
                                                    </a>
                                                    <br>{{ $item->product->isbn }}
                                                </td>
                                                <td>
                                                    @if ($item->cart_price == 1)
                                                        <i class="icon icon-cart" title="Kosár ár"></i>
                                                    @endif
                                                    @if ($item->product->type == 1)
                                                        <i class="icon icon-file-empty2"
                                                           title="Elektronikus könyv"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $item->product->tax_rate }}%</td>
                                                <td>
                                                    @if ($editInProgress && ($editItem->id ?? false) == $item['id'])
                                                        <input type="number" wire:model="editItem.price">
                                                    @else
                                                        <span
                                                            class="font-weight-semibold">{{ $item->price }}</span>
                                                        Ft <s>({{ $item->original_price }} Ft)</s>
                                                    @endif

                                                </td>
                                                <td>
                                                    @if ($editInProgress && ($editItem->id ?? false) == $item['id'])
                                                        <input type="number" wire:model="editItem.quantity">
                                                    @else
                                                        {{ $item->quantity }}
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                        <span class="font-weight-semibold">{{ $item->total }}
                                                            Ft</span>
                                                </td>
                                                <td class="text-right px-0" style="width:60px;">
                                                    @if ($editInProgress ?? false)
                                                        @if (($editItem['id'] ?? false) == $item['id'])
                                                            <a wire:click="saveItem({{ $item['id'] }})"
                                                               class="btn btn-sm mx-0 px-1"><i
                                                                    class="icon text-success icon-check"></i></a>
                                                        @else
                                                            <a wire:click="openItemForEdit({{ $item['id'] }})"
                                                               class="btn btn-sm mx-0 px-1"><i
                                                                    class="icon icon-gear"></i></a>
                                                        @endif
                                                        <a wire:click="deleteItem({{ $item['id'] }})"
                                                           class="btn btn-sm mx-0 px-1"><i
                                                                class="icon icon-trash"></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    @if ($editInProgress)
                                        <tr class="alpha-orange">
                                            <td class="py-2" colspan="2">
                                                <label class="col-form-label font-weight-bold"
                                                       style="margin-bottom:20px;">Új
                                                    termék hozzáadása (ISBN, ID vagy NÉV)</label>
                                            </td>
                                            <td colspan="5">
                                                <select class="form-control select-product" data-fouc>
                                                </select>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="pt-3">
                                            @if ($editShipping)
                                                <select wire:model="model.shipping_method_id" class="form-control">
                                                    @foreach ($shippingMethods as $sm)
                                                        <option value="{{ $sm->id }}">{{ $sm->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <div class="h6"><span class="font-weight-bold">Szállítás:</span>
                                                    {{ $model->shippingMethod?->name }}</div>
                                            @endif
                                        </td>
                                        <td colspan="4"></td>
                                        <td class="text-right">
                                            @if ($editShipping)
                                                <input type="number" wire:model.lazy="model.shipping_fee"
                                                       class="form-control">
                                            @else
                                                <span class="font-weight-semibold">{{ $model->shipping_fee }}
                                                        Ft</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (($editInProgress ?? false) && $correctiveInProgress == false)
                                                @if ($editShipping ?? false)
                                                    <a wire:click="$set('editShipping', false)"
                                                       class="btn btn-sm mx-0 px-1"><i
                                                            class="icon text-success icon-check"></i></a>
                                                @else
                                                    <a wire:click="$set('editShipping', true)"
                                                       class="btn btn-sm mx-0 px-1"><i
                                                            class="icon icon-gear"></i></a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="pt-3">
                                            @if ($editPayment)
                                                <select wire:model="model.payment_method_id"
                                                        class="form-control select select2">
                                                    @foreach ($paymentMethods as $pm)
                                                        <option value="{{ $pm->id }}">{{ $pm->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <div class="h6"><span class="font-weight-bold">Fizetés:</span>
                                                    {{ $model->paymentMethod?->name }}</div>
                                            @endif
                                        </td>
                                        <td colspan="4"></td>
                                        <td class="text-right">
                                            @if ($editPayment)
                                                <input type="number" wire:model.lazy="model.payment_fee"
                                                       class="form-control">
                                            @else
                                                <span class="font-weight-semibold">{{ $model->payment_fee }}
                                                        Ft</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (($editInProgress ?? false) && $correctiveInProgress == false)
                                                @if ($editPayment ?? false)
                                                    <a wire:click="$set('editPayment', false)"
                                                       class="btn btn-sm mx-0 px-1"><i
                                                            class="icon text-success icon-check"></i></a>
                                                @else
                                                    <a wire:click="$set('editPayment', true)"
                                                       class="btn btn-sm mx-0 px-1"><i
                                                            class="icon icon-gear"></i></a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="alpha-success">
                                        <td class="pt-3">
                                            <div class="h4 font-weight-bold">Végösszeg</div>
                                        </td>
                                        <td colspan="4"></td>
                                        <td class="text-right">
                                            <span class="font-weight-bold h4">{{ $model->total_amount }} Ft</span>
                                        </td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-md-flex flex-md-wrap">
                            <div class="p-3 mb-3">
                                {{--                                <div class="card border-1 border-info-300"> --}}
                                {{--                                    <div class="card-header alpha-info border-info-300 header-elements-inline"> --}}
                                <h6 class="card-title font-weight-semibold">Vevő</h6>
                                {{--                                    </div> --}}
                                {{--                                    <div class="card-body"> --}}
                                <ul class="list-unstyled">
                                    @if ($model->customer ?? false)
                                        <li><a href="/gephaz/customers/{{ $model->customer->id ?? '' }}/edit">
                                                {{ $model->customer->email }}
                                                <br>{{ $model->customer->lastname }}
                                                {{ $model->customer->firstname }}</a></li>
                                        <li></li>

                                        <li>{{ $model->customer->phone }}</li>
                                    @else
                                        <li>Vásárló</li>
                                    @endif
                                    <li>{{ $model->email ?? '' }}</li>
                                </ul>
                                {{--                                    </div> --}}
                                {{--                                </div> --}}
                            </div>
                            <div class="p-3 mb-3 mx-4">
                                {{--                                <div class="card border-1 border-green-300"> --}}
                                {{--                                    <div class="card-header alpha-green border-green-300 header-elements-inline"> --}}
                                <h6 class="card-title font-weight-semibold">Szállítási
                                    cím @if (($editInProgress ?? false) && $correctiveInProgress == false)
                                        @if ($editDeliveryAddress ?? false)
                                            <a wire:click="$set('editDeliveryAddress', false)"
                                               class="btn btn-sm mx-0 px-1"><i
                                                    class="icon text-success icon-check"></i></a>
                                        @else
                                            <a wire:click="$set('editDeliveryAddress', true)"
                                               class="btn btn-sm mx-0 px-1"><i class="icon icon-gear"></i></a>
                                        @endif
                                    @endif
                                </h6>
                                {{--                                    </div> --}}
                                {{--                                    <div class="card-body"> --}}
                                <p class="font-weight-bold">{{ $model->shippingMethod?->name }}</p>

                                @if ($model->shippingMethod?->method_id == 'shop')
                                    @if ($editDeliveryAddress)
                                        <div class="row">
                                            <div class="col">
                                                <select name="shop_id" class="form-control" id="shop"
                                                        wire:model.lazy="SAshopID">

                                                    @foreach ($shops as $shop)
                                                        <option value="{{ $shop->id }}"
                                                                @if ($shop->id == $model->shippingMethod?->id) selected @endif>
                                                            {{ $shop->title }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                    @elseif(!empty($model->shipping_data) and !empty($model->shipping_data['shop']))
                                        {{ \Alomgyar\Shops\Shop::find($model->shipping_data['shop']['selected_shop']['id'])->title ?? '' }}
                                    @endif
                                @elseif ($model->shippingMethod?->method_id == 'box')
                                    @if ($editDeliveryAddress)
                                        <div class="row" style="width:100%;">
                                            <div class="col">
                                                <select name="box_id" class="form-control select-point"
                                                        id="box" wire:model.lazy="SAboxID">
                                                    <option
                                                        value="{{$model?->shippingObject->getProviderId()}}">{{ $model->shippingObject->getProviderName() . ' ' . $model->shippingObject->getName() }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        @if (!empty($model->shipping_details))
                                            <ul class="list-unstyled">
                                                @if ($model?->shippingObject)
                                                    <li>Átvételi hely: {{ $model?->shippingObject->getProviderName() }}
                                                    </li>
                                                    <li>Átvételi hely azonosító:
                                                        {{ $model?->shippingObject->getProviderId() }}</li>
                                                @endif
                                                <li>{{ $model->shipping_details?->name ?? null }}</li>
                                                <li>{{ $model->shipping_details?->county ?? null }}</li>
                                                <li>{{ $model->shipping_details?->zip ?? null }}
                                                    {{ $model->shipping_details?->city ?? null }}</li>
                                                <li>Cím: {{ $model->shipping_details?->address ?? null }}</li>
                                                <li>Tel: {{ $model->shipping_details?->phone ?? null }}</li>
                                            </ul>
                                        @endif
                                    @endif
                                @elseif ($model->shippingMethod?->method_id != 'none')
                                    @if ($editDeliveryAddress && $editInProgress)
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control"
                                                       wire:model.lazy="SAfirstName" placeholder="Keresztnév">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control"
                                                       wire:model.lazy="SAlastName" placeholder="Vezetéknév">
                                            </div>
                                        </div>
                                        @if ($model->billingAddress->vat_number ?? false)
                                            <div class="row">
                                                <div class="col">
                                                    <input type="number" class="form-control" placeholder="Adószám"
                                                           wire:model.lazy="SAvatNumber">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col">
                                                <select name="country_id" class="form-control" id="country"
                                                        wire:model="SAcountryID">

                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}"
                                                                @if ($country->id == ($model->billingAddress->country?->id ?? false)) selected @endif>
                                                            {{ $country->name }} ({{ $country->code }})
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="Irányítószám"
                                                       wire:model.lazy="SAzipCode">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="Város"
                                                       wire:model.lazy="SAcity">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col"><input type="text" class="form-control"
                                                                    placeholder="Cím" wire:model.lazy="SAaddress"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col"><input type="text" class="form-control"
                                                                    placeholder="Megjegyzés"
                                                                    wire:model.lazy="SAcomment"></div>
                                        </div>
                                    @else
                                        @isset($model->shippingAddress)
                                            @include('partials.order.shipping-info-details', [
                                                'lastName' => $model->shippingAddress?->last_name,
                                                'firstName' => $model->shippingAddress?->first_name,
                                                'vatNumber' => $model->shippingAddress?->vat_number,
                                                'countryName' => $model->shippingAddress?->country?->name,
                                                'zipCode' => $model->shippingAddress?->zip_code,
                                                'city' => $model->shippingAddress?->city,
                                                'address' => $model->shippingAddress?->address,
                                                'comment' => $model->shippingAddress?->comment,
                                            ])
                                        @endisset
                                    @endif
                                @endif


                                {{--                                    </div> --}}
                                {{--                                </div> --}}
                            </div>

                            @isset($model->billingAddress)
                                <div class="p-3 mb-3 mx-4">
                                    {{--                                <div class="card border-1 border-violet-300"> --}}
                                    {{--                                    <div class="card-header alpha-violet border-violet-300 header-elements-inline"> --}}
                                    <h6 class="card-title font-weight-semibold">Számlázási
                                        cím @if (($editInProgress ?? false) && $correctiveInProgress == false)
                                            @if ($editBillingAddress ?? false)
                                                <a wire:click="$set('editBillingAddress', false)"
                                                   class="btn btn-sm mx-0 px-1"><i
                                                        class="icon text-success icon-check"></i></a>
                                            @else
                                                <a wire:click="$set('editBillingAddress', true)"
                                                   class="btn btn-sm mx-0 px-1"><i class="icon icon-gear"></i></a>
                                            @endif
                                        @endif
                                    </h6>
                                    {{--                                    </div> --}}
                                    {{--                                    <div class="card-body"> --}}
                                    @if ($model->billingAddress->entity_type == 1)
                                        <p><strong>Magánszemély</strong></p>
                                    @else
                                        <p><strong>Céges</strong></p>
                                        @if ($editBillingAddress)
                                            <input type="text" wire:model.lazy="businessName" class="form-control"
                                                   placeholder="Vállalkozás neve">
                                        @else
                                            <p>{{ $model->billingAddress->business_name }}</p>
                                        @endif
                                    @endif


                                    @if ($editBillingAddress)
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control" wire:model.lazy="BAfirstName"
                                                       placeholder="Keresztnév">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" wire:model.lazy="BAlastName"
                                                       placeholder="Vezetéknév">
                                            </div>
                                        </div>
                                        @if ($model->billingAddress->vat_number ?? false)
                                            <div class="row">
                                                <div class="col">
                                                    <input type="number" class="form-control" placeholder="Adószám"
                                                           wire:model.lazy="BAvatNumber">
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row">
                                            <div class="col">
                                                <select name="country_id" class="form-control" id="country"
                                                        wire:model="BAcountryID">

                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}"
                                                                @if ($country->id == ($model->billingAddress->country?->id ?? false)) selected @endif>
                                                            {{ $country->name }} ({{ $country->code }})
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="Irányítószám"
                                                       wire:model.lazy="BAzipCode">
                                            </div>
                                            <div class="col">
                                                <input type="text" class="form-control" placeholder="Város"
                                                       wire:model.lazy="BAcity">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col"><input type="text" class="form-control"
                                                                    placeholder="Cím" wire:model.lazy="BAaddress"></div>
                                        </div>
                                    @else
                                        <ul class="list-unstyled">
                                            <li>{{ $model->billingAddress->last_name }}
                                                {{ $model->billingAddress->first_name }}</li>
                                            @if ($model->billingAddress->vat_number ?? false)
                                                <li>Adószám: {{ $model->billingAddress->vat_number }}</li>
                                            @endif
                                            <li>{{ $model->billingAddress->country?->name }}</li>
                                            <li>{{ $model->billingAddress->zip_code }}
                                                , {{ $model->billingAddress->city }}
                                            </li>
                                            <li>Cím: <br>{{ $model->billingAddress->address }}</li>
                                        </ul>
                                    @endif
                                    {{--                                    </div> --}}
                                    {{--                                </div> --}}
                                </div>
                            @endisset
                            @if (!empty($model->message))
                                <div class="p-3 mb-3">
                                    <h6 class="card-title font-weight-semibold">Vásárló megjegyzése</h6>
                                    <p>{{ $model->message }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- /mail container -->
                    <!-- Attachments -->
                    <div class="card-body border-top">
                        <h6 class="mb-0"><i class="icon-attachment2 mr-2"></i>Csatolmányok (számla, nyugta,
                            bizonylat)</h6>

                        <ul class="list-inline mb-0">
                            @if (!empty($model->attachments))
                                @foreach ($model->attachments as $attachment)
                                    <li class="list-inline-item">
                                        <div class="card bg-light py-2 px-3 mt-3 mb-0 ">
                                            <div
                                                class="media my-1 @if ($model->invoice_url == $attachment) text-success @endif">
                                                <div class="mr-3 align-self-center"><i
                                                        class="icon-file-pdf icon-2x text-danger-400 top-0"></i></div>
                                                <div class="media-body">
                                                    <div class="font-weight-semibold">{{ $attachment }}.pdf</div>

                                                    <ul class="list-inline list-inline-condensed mb-0">
                                                        <li class="list-inline-item text-muted">174 KB</li>
                                                        <li class="list-inline-item"><a
                                                                href="{{ route('orders.invoice.get', ['id' => $attachment]) }}"
                                                                target="_blank">Megtekint</a></li>
                                                        <li class="list-inline-item"><a
                                                                href="{{ route('orders.invoice.get', ['id' => $attachment, 'type' => 'download']) }}">Letölt</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                                @if ($productMovement ?? false)
                                    @foreach ($productMovement ?? [] as $pm)
                                        <li class="list-inline-item">
                                            <div class="card bg-light py-2 px-3 mt-3 mb-0 ">
                                                <div
                                                    class="media my-1 @if ($model->invoice_url == $attachment) text-success @endif">
                                                    <div class="mr-3 align-self-center"><i
                                                            class="icon-archive icon-2x text-danger-400 top-0"></i>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="font-weight-semibold">
                                                            @if ($pm->source_type === 'storno')
                                                                Storno
                                                            @endif Bizonylat:
                                                            {{ $pm->reference_nr }}
                                                        </div>

                                                        <ul class="list-inline list-inline-condensed mb-0">
                                                            <li class="list-inline-item text-muted">174 KB</li>
                                                            <li class="list-inline-item"><a
                                                                    href="/gephaz/warehouses/product_movements/{{ $pm->id }}"
                                                                    target="_blank">Megnyit</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            @endif
                        </ul>
                    </div>
                    <!-- /attachments -->
                </div>
                <!-- /single order -->
            </div>

            <div class="col-md-3 sidebar-light" style="background:transparent;">
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="card-title font-weight-semibold">Lehetőségek</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0" style="">
                        <div class="nav nav-sidebar mb-2">
                            <li class="nav-item">
                                <a href="#" class="nav-link legitRipple" wire:click="createReceipt()">
                                    <i class="icon-printer2"></i>
                                    Nyugta generálás
                                </a>
                            </li>
                            @if (!empty($model->invoice_url))
                                @if ($editInProgress)
                                    <li class="nav-item">
                                        <a href="#" class="nav-link legitRipple bg-success"
                                           wire:click="createCorrective()">
                                            <i class="icon-file-pdf"></i>
                                            HELYESBÍTŐ ELKÜLDÉSE
                                        </a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a href="#" class="nav-link legitRipple"
                                           wire:click="startCorrective()">
                                            <i class="icon-file-pdf"></i>
                                            Helyesbítő számla készítés
                                        </a>
                                    </li>
                                @endif
                            @endif
                            @if (
                                $model->status <= \App\Order::STATUS_PROCESSING ||
                                    ($model->shippingMethod?->method_id == 'shop' && \App\Order::STATUS_LANDED == $model->status))
                                @if ($editInProgress)
                                    <li class="nav-item">
                                        <a wire:click="$set('editInProgress', false)" class="nav-link legitRipple">
                                            <i class="icon text-success icon-check"></i>
                                            <span class="text-success">Szerkesztés befejezése</span>
                                        </a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a href="javascript:" wire:click="$set('editInProgress', true)"
                                           class="nav-link legitRipple">
                                            <i class="icon-gear"></i>
                                            Rendelés módosítása
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="card-title font-weight-semibold">Fizetettség</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0" style="">
                        <div class="nav nav-sidebar mb-2">

                            @if ($model->payment_status != App\Order::STATUS_PAYMENT_PAID)
                                <li class="nav-item">
                                    @if ($model->paymentMethod->method_id == 'transfer')
                                        <span class="ml-3" style="display: inline-block;">Fizetés dátuma</span>
                                        <input type="text" class="form-control pickadate"
                                               style="width: auto; margin: 0 22px; display: inline-block"
                                               name="invoice_date" id="invoice_date" value="{{ date('Y-m-d') }}"
                                               placeholder="Fizetés dátuma">
                                    @endif
                                    <a href="#" class="nav-link legitRipple" style="padding-right: 0.25rem"
                                       id="payment_set_payed">
                                        <i class="icon-check2"></i>
                                        Fizetettre állít
                                    </a>
                                </li>
                            @endif
                            @if ($model->payment_status == App\Order::STATUS_PAYMENT_PAID)
                                <li class="nav-item">
                                    <a href="#" class="nav-link legitRipple" id="payment_set_unpayed">
                                        <i class="icon-undo"></i>
                                        Fizetetlenre állít
                                    </a>
                                </li>
                            @endif

                        </div>
                    </div>
                </div>

                <input type="hidden" id="model_id" name="model_id" value="{{ $model->id }}">
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="card-title font-weight-semibold">Teljesítési folyamat</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>
                    @if (!$onlyEbook)
                        <div class="card-body p-0">
                            <div class="nav nav-sidebar mb-2">
                                <li class="nav-item-header">Lehetőségek:</li>
                                <li class="nav-item ">
                                    <a href="#" data-status="{{ App\Order::STATUS_NEW }}"
                                       class="nav-link legitRipple {{ $model->isAllowedStatus(App\Order::STATUS_NEW) }} {{ $model->status == App\Order::STATUS_NEW ? 'bg-success' : '' }} status-link">
                                        <i class="icon-file-empty"></i>
                                        Megrendelve
                                        <i title="E-mail küldés" class="icon icon-envelope float-right mr-1"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" data-status="{{ App\Order::STATUS_PROCESSING }}"
                                       class="nav-link legitRipple {{ $model->isAllowedStatus(App\Order::STATUS_PROCESSING) }} {{ $model->status == App\Order::STATUS_PROCESSING ? 'bg-success' : '' }} status-link">
                                        <i class="icon-file-plus"></i>
                                        Feldolgozás alatt
                                        <i title="E-mail küldés" class="icon icon-envelope float-right mr-1"></i>
                                        {{-- <i title="Rendeléslap generálás" class="icon icon-printer float-right mr-1"></i> --}}
                                    </a>
                                </li>
                                @if ($model->shippingMethod->method_id == 'shop')
                                    <li class="nav-item">
                                        <a href="#" data-status="{{ App\Order::STATUS_LANDED }}"
                                           class="nav-link legitRipple {{ $model->isAllowedStatus(App\Order::STATUS_LANDED) }} {{ $model->status == App\Order::STATUS_LANDED ? 'bg-success' : '' }} status-link">
                                            <i class="icon-mailbox"></i>
                                            Átvehető
                                            <i title="E-mail küldés" class="icon icon-envelope float-right mr-1"></i>
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="#"
                                               data-status="{{ App\Order::STATUS_WAITING_FOR_SHIPPING }}"
                                               data-invoice="szamla" id="order-waiting-for-shipping"
                                               class="nav-link  legitRipple {{ $model->isAllowedStatus(App\Order::STATUS_WAITING_FOR_SHIPPING) }} {{ $model->status == App\Order::STATUS_WAITING_FOR_SHIPPING ? 'bg-success' : '' }} status-link">
                                                <i class="icon-file-check "></i>
                                                Összekészítve
                                                <i title="E-mail küldés"
                                                   class="icon icon-envelope float-right mr-1"></i>
                                                <i title="Raktárkészlet módosítás, bizonylat generálás"
                                                   class="icon icon-database-insert float-right mr-1"></i>
                                                {{-- <i title="Csomagcimke generálás" class="icon icon-printer float-right mr-1"></i> --}}
                                                <i title="Számla kiállítás"
                                                   class="icon icon-newspaper2 float-right mr-1"></i>
                                            </a>
                                        </div>
                                        @if (
                                            $model->isAllowedStatus(App\Order::STATUS_WAITING_FOR_SHIPPING, true) &&
                                                $model->shippingMethod->method_id == 'shop')
                                            <div class="col-md-12">
                                                <div class="row d-flex justify-content-center skvad-switcher"
                                                     data-switch_id="order-waiting-for-shipping">
                                                    <div class="col-md-5"><input class="d-none" id="o-nyugta"
                                                                                 type="radio" value="nyugta" checked
                                                                                 name="o-type"><label for="o-nyugta"
                                                                                                      class="btn btn-sm btn-primary">Nyugtával</label>
                                                    </div>
                                                    <div class="col-md-5"><input class="d-none" id="o-szamla"
                                                                                 type="radio" value="szamla"
                                                                                 name="o-type"><label
                                                            for="o-szamla"
                                                            class="btn btn-sm btn-secondary">Számlával</label></div>
                                                </div>
                                                <hr/>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                                @if ($model->shippingMethod->method_id != 'shop')
                                    <li class="nav-item">
                                        <a href="#" data-status="{{ App\Order::STATUS_SHIPPING }}"
                                           class="nav-link legitRipple {{ $model->isAllowedStatus(App\Order::STATUS_SHIPPING) }} {{ $model->status == App\Order::STATUS_SHIPPING ? 'bg-success' : '' }} status-link">
                                            <i class="icon-truck"></i>
                                            Szállítás alatt
                                            <i title="E-mail küldés" class="icon icon-envelope float-right mr-1"></i>
                                        </a>
                                    </li>
                                @endif
                                {{-- }
                            @if ($model->shippingMethod->method_id != 'home' && $model->shippingMethod->method_id != 'shop')
                            <li class="nav-item">
                                <a href="#" data-status="{{App\Order::STATUS_LANDED}}" class="nav-link legitRipple {{$model->isAllowedStatus(App\Order::STATUS_LANDED)}} {{$model->status == App\Order::STATUS_LANDED ? 'bg-success' : ''}} status-link">
                                    <i class="icon-mailbox"></i>
                                    Átvehető
                                    <i title="E-mail küldés" class="icon icon-envelope float-right mr-1"></i>
                                </a>
                            </li>
                            @endif --}}
                                <li class="nav-item">
                                    <a href="#" data-status="{{ App\Order::STATUS_COMPLETED }}"
                                       class="nav-link legitRipple {{ $model->isAllowedStatus(App\Order::STATUS_COMPLETED) }} {{ $model->status == App\Order::STATUS_COMPLETED ? 'bg-success' : '' }} status-link">
                                        <i class="icon-file-check2"></i>
                                        Sikeres, teljesítve
                                        <i title="E-mail küldés" class="icon icon-envelope float-right mr-1"></i>
                                    </a>
                                </li>
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            Fizikai könyv hiányában nincsenek rendelés-teljesítéssel kapcsolatos állapotok
                        </div>
                    @endif
                </div>

                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="card-title font-weight-semibold">Rendelés érvénytelenítése</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0" style="">
                        <div class="nav nav-sidebar mb-2">

                            @if ($model->status != App\Order::STATUS_DELETED && Auth::user()->can('orders.delete'))
                                <li class="nav-item">
                                    <a href="#" class="nav-link legitRipple inactivate_order text-danger"
                                       data-status="8">
                                        <i class="icon-trash"></i>
                                        Rendelés törlése
                                    </a>
                                </li>
                            @endif
                            @if ($model->status > \App\Order::STATUS_NEW)
                                <li class="nav-item">
                                    <a href="javascript:" wire:click="setOrderToEdit()" class="nav-link legitRipple">
                                        <i class="icon-gear"></i>
                                        Rendelés alaphelyzetbe állítása
                                    </a>
                                </li>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
