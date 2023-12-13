@extends('admin::layouts.master')
@section('pageTitle')
    Ügyvitel sablonok
@endsection

@section('header')
    @include('managment_templates::components.stockheader', ['title' => 'Ügyvitel sablonok', 'subtitle' => 'Bizonylatok'])
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
                            <select class="form-control select select2" name="subcategory" onChange="handleSelect(this)">
                                <option selected value="">Típus</option>
                                <option value="">Bevételezés</option>
                                <option value="">Kivételezés</option>
                                <option value="">Eladás</option>
                                <option value="">Beszerzés</option>
                            </select>
                            <div class="form-control-feedback">
                                <i class="icon-database-check text-muted"></i>
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
                                <div ><span><input wire:model="filters.cart_price" type="checkbox" ></span> Csak kivételezések</div>
                                <div ><span><input wire:model="filters.cart_price" type="checkbox" ></span> Csak bevételezések</div>
                            </label>
                        </div>
    
                    </form>
                </div>
                    
                <div class="card-body">
                    Mennyiség
                    <br>max: 10000
                    <input type="range" min="0" max="1000" value="1000">
                    <br>min: 0
                    <input type="range" min="0" max="1000" value="0">
        
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
                                <th>Termék</th>
                                <th>Típus</th>
                                <th class="text-right">Mennyiség</th>
                                <th class="text-right">Beszerzési</th>
                                <th>Létrehozás</th>
                                <th>Állapot</th>
                                <th>{{ __('general.actions') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><input type="checkbox"> # </th>
                                <th>Termék</th>
                                <th>Típus</th>
                                <th class="text-right">Mennyiség</th>
                                <th class="text-right">Beszerzési</th>
                                <th>Létrehozás</th>
                                <th>Állapot</th>
                                <th>{{ __('general.actions') }}</th>
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
                                
                                    @if(rand(0, 1) == 1)
                                    <td>
                                    Bevételezés
                                    <td class="text-right"> <strong class="text-success">+{{rand(10, 50)}} db</strong> </td>
                                    @else
                                    <td>
                                        @if(rand(0, 1) == 1)
                                            Kivételezés
                                        @else
                                            Eladás
                                        @endif
                                    <td class="text-right"> <strong class="text-danger">-{{rand(10, 50)}} db</strong> </td>
                                    @endif
                                </td>
                                <td class="text-right">
                                    {{rand(1, 5)}} {{rand(100, 500)}} Ft
                                    <br><small>Libri</small>
                                </td>
                                <td>
                                    2020.09.01 12:23
                                    <br><small>Kovács Béla</small>
                                </td>
                                <td>
                                    <span class="badge badge-success d-block">Lekönyvelt</span>
                                </td>
                                
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
