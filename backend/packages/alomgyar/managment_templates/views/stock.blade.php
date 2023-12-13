@extends('admin::layouts.master')
@section('pageTitle')
    Ügyvitel sablonok
@endsection

@section('header')
    @include('managment_templates::components.stockheader', ['title' => 'Ügyvitel sablonok', 'subtitle' => 'Raktárkészlet'])
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
                                <option selected value="">Raktár</option>
                            </select>
                            <div class="form-control-feedback">
                                <i class="icon-bag text-muted"></i>
                            </div>
                        </div>
                        <div class="form-group form-group-feedback form-group-feedback-left">
                            <select class="form-control  select select2" name="category" onChange="handleSelect(this)">
                                <option selected value="">Beszállító</option>
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
                                <option selected value="">Kiadó</option>
                            </select>
                            <div class="form-control-feedback">
                                <i class="icon-database-check text-muted"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label">
                                <div ><span><input wire:model="filters.cart_price" type="checkbox" ></span> Csak kosár árral</div>
                            </label>
                        </div>
    
                    </form>
                </div>
                    
                <div class="card-body">
                    Raktárkészlet
                    <br>max: több mint 1000
                    <input type="range" min="0" max="1000" value="1000">
        
                </div>
            </div>
            <!-- /filter -->
    
            <div class="card card-body border-top-1 border-top-primary">
                <div class="text-center">
                    <h6 class="mb-1 font-weight-semibold">Leltár</h6>
                </div>
                <a href="" class="btn btn-outline-warning legitRipple my-2"><i class="icon icon-upload"></i> Leltár export</a>
                <a href="" class="btn btn-outline-success legitRipple my-2"><i class="icon icon-download"></i> Leltár import</a>
                
            </div>
            <div class="card card-body border-top-1 border-top-primary">
                <div class="text-center">
                    <h6 class="mb-1 font-weight-semibold">Bizonylat generálás</h6>
                </div>
                <a href="" class="btn btn-outline-success legitRipple my-2"><i class="icon icon-download"></i> Import</a>
                
            </div>
    
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
                                <th>Termék</th>
                                <th class="text-right">Beszerzési ár</th>
                                <th class="text-right">Eladási ár</th>
                                <th class="text-right">Készlet</th>
                                {{--<th class="text-right">Beszerzés alatt</th>
                                <th class="text-right">Rendelve</th>--}}
                                <th>{{ __('general.actions') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Termék</th>
                                <th class="text-right">Beszerzési ár</th>
                                <th class="text-right">Eladási ár</th>
                                <th class="text-right">Készlet</th>
                                {{--<th class="text-right">Beszerzés alatt</th>
                                <th class="text-right">Rendelve</th>--}}
                                <th class="text-right">{{ __('general.actions') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach(range(1, 20) as $p)
                            <tr>
                                <td><input type="checkbox"> {{rand(1000, 4000)}}</td>
                                <td>
                                    
                                    
                                    @if(rand(0, 1) == 1)
                                    <strong>Kis Herceg</strong>
                                    @else
                                    <strong>A lakótárs - Társas játék sorozat</strong>
                                    @endif
                                    <br><small>9780480188849</small>
                                </td>
                                <td class="text-right">
                                    {{rand(1, 5)}} {{rand(100, 500)}} Ft
                                    <br><small>{{rand(3, 5)}} {{rand(100, 500)}} Ft</small>
                                </td>
                                <td class="text-right" style="white-space:nowrap;">
                                                            
                                    <div style="color:#e62934; display:inline-block">3249
                                    <br> <small>20%</small>
                                    </div>
                                    <div style="color:#fbc72e; display:inline-block">3346
                                    <br> <small>17%</small>
                                    </div>
                                    <div style="color:#4971ff; display:inline-block">3607
                                    <br> <small>7%</small>
                                    </div>
                            </td>
                                <td class="text-right">
                                    <strong title="Összesen">{{ rand(0, 200) }} db</strong>
                                    <br><small title="Fő raktár">{{ rand(0, 200) }}</small>/<small title="Boltokban">{{ rand(0, 200) }}</small>
                                </td>
                                {{--}}
                                <td class="text-right">
                                    @if(rand(0, 1) == 1)
                                    {{ rand(0, 200) }} db
                                    @else
                                    0
                                    @endif
                                </td>
                                <td class="text-right">
                                    
                                    @if(rand(0, 1) == 1)
                                    {{ rand(0, 200) }} db
                                    @else
                                    0
                                    @endif
                                </td>--}}
                                <td class="text-right">
                                    <div class="list-icons">
                                        <div class="btn-group ml-2">
                                            <button type="button" class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></button>

                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -165px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a href="#" class="dropdown-item">Bevételezés</a>
                                                <a href="#" class="dropdown-item">Kivételezés</a>
                                                <a href="#" class="dropdown-item">Raktárkészlet történet</a>
                                                <a href="#" class="dropdown-item">Raktárkészlet állapot</a>
                                            </div>
                                        </div>
                                        {{--<a href="/gephaz/products/2/edit" class="btn alpha-primary text-primary-800 btn-icon ml-2 legitRipple"><i class="icon-enlarge7"></i></a>    --}}                                                               
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
