@extends('admin::layouts.master')
@section('pageTitle')
    Ügyvitel sablonok
@endsection

@section('header')
    @include('managment_templates::components.header', ['title' => 'Ügyvitel sablonok', 'subtitle' => 'Megrendelések'])
@endsection

@section('content')
<div class="d-md-flex align-items-md-start">

    <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left border-0 shadow-0 sidebar-expand-md" style="width:13rem">
    
        <!-- Sidebar content -->
        <div class="sidebar-content">
    
            <!-- Filter -->
            <div class="card border-top-1 border-top-info">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="text-uppercase font-size-sm font-weight-semibold">Szűrő</span>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
    
                <div class="card-body">
                    <form action="#">
                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <input wire:model="s" type="search" class="form-control" placeholder="Keresés">
                            <div class="form-control-feedback">
                                <i class="icon-search4 text-muted"></i>
                            </div>
                        </div>
                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <select class="form-control  select select2" name="category" onChange="handleSelect(this)">
                                <option selected value="">Szállítási mód</option>
                            </select>
                            <div class="form-control-feedback">
                                <i class="icon-bag text-muted"></i>
                            </div>
                        </div>
                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <select class="form-control  select select2" name="category" onChange="handleSelect(this)">
                                <option selected value="">Fizetési mód</option>
                                <option value="">Utánvét</option>
                                <option value="">Fizetés átvételkor</option>
                                <option value="">Kártyás</option>
                                <option value="">Előreutalás</option>
                            </select>
                            <div class="form-control-feedback">
                                <i class="icon-cash2 text-muted"></i>
                            </div>
                        </div>
                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <select class="form-control select select2" name="subcategory" onChange="handleSelect(this)">
                                <option selected value="">Státusz</option>
                            </select>
                            <div class="form-control-feedback">
                                <i class="icon-database-check text-muted"></i>
                            </div>
                        </div>
                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <select class="form-control select select2" name="subcategory" onChange="handleSelect(this)">
                                <option selected value="">Csomagpont</option>
                            </select>
                            <div class="form-control-feedback">
                                <i class="icon-location4 text-muted"></i>
                            </div>
                        </div>
                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <select class="form-control select select2" name="subcategory" onChange="handleSelect(this)">
                                <option selected value="">Boltok</option>
                            </select>
                            <div class="form-control-feedback">
                                <i class="icon-location4 text-muted"></i>
                            </div>
                        </div>
    
    
                        <div class="form-group">
                            <label class="form-check-label">
                                <div ><span><input wire:model="filters.only_ebook" type="checkbox" ></span> Csak e-könyvek</div>
                            </label><br>
                            <label class="form-check-label">
                                <div ><span><input wire:model="filters.only_book" type="checkbox" ></span> Csak könyvek</div>
                            </label><br>
                            <label class="form-check-label">
                                <div ><span><input wire:model="filters.cart_price" type="checkbox" ></span> Csak kosár árral</div>
                            </label><br>
                            <label class="form-check-label">
                                <div ><span><input wire:model="filters.cart_price" type="checkbox" ></span> Csak boltok</div>
                            </label><br>
                            <label class="form-check-label">
                                <div ><span><input wire:model="filters.cart_price" type="checkbox" ></span> Csak webshop</div>
                            </label><br><br>
                            <label class="form-check-label">
                                <div ><span><input wire:model="filters.cart_price" type="checkbox" ></span> Teljesíthető</div>
                            </label><br>
                            <label class="form-check-label">
                                <div ><span><input wire:model="filters.cart_price" type="checkbox" ></span> Nem teljesíthető</div>
                            </label>
                        </div>
    
                    </form>
                </div>
                    
            </div>
            <!-- /filter -->
    
        </div>
        <!-- /sidebar content -->
    
    </div>
    
    
    
    
    <div class="flex-fill overflow-auto">
    
    
    
    
    
    
    
    
    
    <div class="card">
        <div class="card-body p-0">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="actual">
    
                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox"> # </th>
                                <th>Ügyfél</th>
                                <th>Ár</th>
                                <th>Szállítási mód/díj</th>
                                <th>Fizetési mód</th>
                                <th>Dátum</th>
                                <th>Státusz</th>
                                <th>{{ __('general.actions') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Ügyfél</th>
                                <th>Ár</th>
                                <th>Szállítási mód/díj</th>
                                <th>Fizetési mód</th>
                                <th>Dátum</th>
                                <th>Státusz</th>
                                <th>{{ __('general.actions') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach(range(1, 20) as $p)
                            <tr @if(rand(1, 2) == 2) style="border-left:3px solid #e62934;" @else style="border-left:3px solid #fbc72e;" @endif >
                                <td><input type="checkbox"> 1000</td>
                                <td>
                                    <strong style="white-space: nowrap;">Kovács József</strong>
                                    <small>+36 20 1233456</small>
                                    <br>  
                                    <small>Göd, Fő utca 34.</small>
                                </td>
                                <td>{{rand(1, 5)}} {{rand(100, 500)}} Ft
                                <br><small>2 db</small></td>
                                <td>
                                    <strong style="white-space: nowrap;">Fárszolgálat</strong>
                                    <br><small>999 Ft</small>
                                </td>
                                <td>
                                    
                                    @if(rand(1, 2) == 1)
                                        <i class="icon icon-mailbox"></i> Utánvét
                                    @else
                                        <i class="icon icon-next"></i> Előreutalás
                                    @endif
                                    
                                    <br><small><span class="d-block badge bg-success-600" title="Fizetésre vár">Fizetett</span></small>
                                </td>
                                <td>
                                    2020.07.02<br><strong style="white-space: nowrap;">10:20:50</strong>
                                </td>
                                <td>
                                    <span class="badge badge-danger d-block">Sikertelen</span>
                                </td>
                                <td>
                                    <div class="list-icons">
                                        <div class="btn-group ml-2">
                                            <button type="button" class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></button>

                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -165px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a href="#" class="dropdown-item"><strong>Fizetési állapot:</strong></a>
                                                <a href="#" class="dropdown-item">Kifizetettre állít</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="#" class="dropdown-item"><strong>Rendelés állapot állítás:</strong></a>
                                                <a href="#" class="dropdown-item">Várakozó/Utalásra vár</a>
                                                <a href="#" class="dropdown-item">Feldolgozás alatt/Utalása megérkezett</a>
                                                <a href="#" class="dropdown-item">Szállítás alatt</a>
                                                <a href="#" class="dropdown-item">Átvehető</a>
                                                <a href="#" class="dropdown-item">Teljesítve</a>
                                                <a href="#" class="dropdown-item">Törölve</a>
                                                <a href="#" class="dropdown-item">Nem véglegesített</a>
                                            </div>
                                        </div>                                                          

                                        <a href="/gephaz/managment_templates/order" class="btn alpha-primary text-primary-800 btn-icon ml-2 legitRipple"><i class="icon-enlarge7"></i></a>                                                                   
                                    </div>
                                </td>
                            </tr>
                            <tr @if(rand(1, 2) == 2) style="border-left:3px solid #e62934;" @else style="border-left:3px solid #fbc72e;" @endif>
                                <td><input type="checkbox"> 109965</td>
                                <td>
                                    <strong style="white-space: nowrap;">Tóth Ferenc</strong>
                                    <small>+36 20 1233456</small>
                                    <br>  
                                    <small>Budapest, 1223, Halom utca 18.</small>
                                </td>
                                <td>{{rand(1, 5)}} {{rand(100, 500)}} Ft
                                <br><small>{{rand(1, 5)}} db</small></td>
                                <td>
                                    @if(rand(1, 2) == 1)
                                    <strong style="white-space: nowrap;">Csomagpont(POSTA-10_POSTA)</strong>
                                    @else
                                    <strong style="white-space: nowrap;">Álomgyár boltban (Budapest)</strong>
                                    <br><small>Blaha - 1072 Budapest, Rákóczi út 42.</small>
                                    @endif
                                </td>
                                <td>
                                    
                                    @if(rand(1, 2) == 1)
                                        <i class="icon icon-credit-card2"></i> Kártyás
                                    @else
                                        <i class="icon icon-mailbox"></i> Fizetés átvételkor
                                    @endif
                                    <br><small><span class="d-block badge bg-orange-600" title="Fizetésre vár">Fizetésre vár</span></small>
                                </td>
                                <td>
                                    2020.07.02<br><strong style="white-space: nowrap;">10:20:50</strong>
                                </td>
                                <td>
                                    @if(rand(1, 2) == 2)
                                    <span class="badge badge-success d-block">Sikeres</span>
                                    @else
                                        @if(rand(3, 4) == 4)
                                        <span class="badge badge-danger d-block">Sikertelen</span>
                                        @else
                                        <span class="badge badge-warning d-block">Szállítás alatt</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="list-icons">
                                        <div class="btn-group ml-2">
                                            <button type="button" class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></button>

                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -165px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a href="#" class="dropdown-item"><strong>Fizetési állapot:</strong></a>
                                                <a href="#" class="dropdown-item">Kifizetettre állít</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="#" class="dropdown-item"><strong>Rendelés állapot állítás:</strong></a>
                                                <a href="#" class="dropdown-item">Várakozó/Utalásra vár</a>
                                                <a href="#" class="dropdown-item">Feldolgozás alatt/Utalása megérkezett</a>
                                                <a href="#" class="dropdown-item">Szállítás alatt</a>
                                                <a href="#" class="dropdown-item">Átvehető</a>
                                                <a href="#" class="dropdown-item">Teljesítve</a>
                                                <a href="#" class="dropdown-item">Törölve</a>
                                                <a href="#" class="dropdown-item">Nem véglegesített</a>
                                            </div>
                                        </div> 
                                        <a href="/gephaz/managment_templates/order" class="btn alpha-primary text-primary-800 btn-icon ml-2 legitRipple"><i class="icon-enlarge7"></i></a>                                                                   
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
    
                   
                    
                </div>
            </div>
        </div>
        <div class="card-footer">
            @include('admin::partials._pagination')
        </div>
    </div>
    <style>
        table.table td{
            padding-top:4px;
            padding-bottom:4px;
        }
    </style>
    
    </div>

@endsection
