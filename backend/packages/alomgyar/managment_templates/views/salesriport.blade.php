@extends('admin::layouts.master')
@section('pageTitle')
    Fogysjelentések
@endsection

@section('header')
    @include('managment_templates::components.salesheader', ['title' => 'Ügyvitel sablonok', 'subtitle' => 'Fogyásjelentések'])
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
                                <option selected value="">beszállító</option>
                            </select>
                            <div class="form-control-feedback">
                                <i class="icon-bag text-muted"></i>
                            </div>
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
                                <th> # </th>
                                <th>Feldolgozott rendelések</th>
                                <th>Futtatás időpontja</th>
                                <th>Időszak</th>
                                <th>Érintett beszállítók</th>
                                <th>{{ __('general.actions') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Feldolgozott rendelések</th>
                                <th>Futtatás időpontja</th>
                                <th>Időszak</th>
                                <th>Érintett beszállítók</th>
                                <th>{{ __('general.actions') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach(range(1, 20) as $p)
                            <tr>
                                <td><input type="checkbox"> {{$p}}</td>
                                <td>{{rand(3000, 5000)}} db</td>
                                <td>
                                    <strong style="white-space: nowrap;">2020.07.02</strong> 10:20:50
                                </td>
                                <td>2020.08.01 - 2020.09.04</td>
                                <td>{{rand(4, 12)}}</td>
                                <td>
                                    <div class="list-icons">
                                        {{--}
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
                                        --}}
                                        <a href="/gephaz/managment_templates/order" class="btn alpha-primary text-primary-800 btn-icon ml-2 legitRipple"><i class="icon-file-download"></i> Letöltés</a>                                                                   
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
