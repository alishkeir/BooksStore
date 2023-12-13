@extends('admin::layouts.master')
@section('pageTitle')
    Ügyvitel sablonok
@endsection

@section('header')
    @include('managment_templates::components.header', ['title' => 'Ügyvitel sablonok', 'subtitle' => 'Raktár végoldal'])
@endsection

@section('content')
<div class="flex-fill overflow-auto">
    <div class="card border-left-3 border-left-success rounded-left-0">
        <div class="card-body">
            <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                <div>
                    <h6 class="font-weight-semibold">Központi raktár</h6>
                    <ul class="list list-unstyled mb-0">
                        <li>Cím: 1122 Fő utca 34</li>
                    </ul>
                </div>

                <div class="text-sm-right mb-0 mt-3 mt-sm-0 ml-auto">
                    <h6 class="font-weight-semibold">Termékek: 23 000 db</h6>
                    <ul class="list list-unstyled mb-0">
                        <li class="dropdown">
                            Állapot: &nbsp;
                            <a href="#" class="badge bg-success-400 align-top dropdown-toggle" data-toggle="dropdown">Aktív</a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item"><i class="icon-alert"></i> Overdue</a>
                                <a href="#" class="dropdown-item"><i class="icon-alarm"></i> Pending</a>
                                <a href="#" class="dropdown-item active"><i class="icon-checkmark3"></i> Paid</a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item"><i class="icon-spinner2 spinner"></i> On hold</a>
                                <a href="#" class="dropdown-item"><i class="icon-cross2"></i> Canceled</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">
            <span>
                <span class="badge badge-mark border-success mr-2"></span>
                Létrejött:
                <span class="font-weight-semibold">2015.03.24</span>
            </span>

            <ul class="list-inline list-inline-condensed mb-0 mt-2 mt-sm-0">
                <li class="list-inline-item">
                    <a href="#" class="text-default"><i class="icon-eye8"></i></a>
                </li>
                <li class="list-inline-item dropdown">
                    <a href="#" class="text-default dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item"><i class="icon-printer"></i> Print invoice</a>
                        <a href="#" class="dropdown-item"><i class="icon-file-download"></i> Download invoice</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item"><i class="icon-file-plus"></i> Edit invoice</a>
                        <a href="#" class="dropdown-item"><i class="icon-cross2"></i> Remove invoice</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
   
    <div class="card">
        <div class="card-body p-0">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="actual">
    
                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th>Termék</th>
                                <th class="text-right">Beszerzési ár</th>
                                <th class="text-right">Eladási ár</th>
                                <th class="text-right">Készlet</th>
                                {{--<th class="text-right">Beszerzés alatt</th>--}}
                                <th class="text-right">Rendelve</th>
                                <th class="text-right">{{ __('general.actions') }}</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Termék</th>
                                <th class="text-right">Beszerzési ár</th>
                                <th class="text-right">Eladási ár</th>
                                <th class="text-right">Készlet</th>
                                {{--<th class="text-right">Beszerzés alatt</th>--}}
                                <th class="text-right">Rendelve</th>
                                <th class="text-right">{{ __('general.actions') }}</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach(range(1, 20) as $p)
                            <tr>
                                <td> {{rand(1000, 4000)}}</td>
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
                                </td>
                                {{--}}
                                <td class="text-right">
                                    @if(rand(0, 1) == 1)
                                    {{ rand(0, 200) }} db
                                    @else
                                    0
                                    @endif
                                </td>--}}
                                <td class="text-right">
                                    
                                    @if(rand(0, 1) == 1)
                                    {{ rand(0, 200) }} db
                                    @else
                                    0
                                    @endif
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

</div>

@endsection
