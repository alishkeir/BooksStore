@extends('admin::layouts.master')
@section('pageTitle')
    Ügyvitel sablonok
@endsection

@section('header')
    @include('managment_templates::components.stockheader', ['title' => 'Ügyvitel sablonok', 'subtitle' => 'Raktárkezelő'])
@endsection

@section('content')
<div class="card">
<div class="card-body">
    <div class="tab-content">
        <div class="tab-pane fade show active" id="actual">
            <div class="row mt-1">
<div class="col-md-5">
    <div class="form-group-feedback form-group-feedback-right">
        <input wire:model="s" type="text" class="form-control" placeholder="Kezdj el gépelni...">
        <div class="form-control-feedback text-muted pr-2">
            <i class="icon-search4"></i>
        </div>
    </div>
</div>
<div class="col-md-2 text-right" style="white-space:nowrap">
   
</div>
<div class="col-md-5">
    <div class="row float-right">
        <div class="col-4">
            <select class="form-control" wire:model="perPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <label for="" class="col-form-label col-lg-8">sor oldalanként</label>
    </div>
</div>
</div>                <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            <a href="javascript:;" wire:click.prevent="sortBy('id')" role="button" class="text-default">
                                #
                                <i class="icon-arrow-down5"></i>
                            </a>
                        </th>
                        <th>
                            <a href="javascript:;" wire:click.prevent="sortBy('title')" role="button" class="text-default">
                                Név
                                <i class="icon-menu-open"></i>
                            </a>
                        </th>
                        <th>Könyv típusok</th>
                        <th>Összes darabszám</th>
                        <th>
                            <a href="javascript:;" wire:click.prevent="sortBy('status')" role="button" class="text-default">
                                Állapot
                                <i class="icon-menu-open"></i>
                            </a>
                        </th>
                        <th width="10%">műveletek</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Név</th>
                        <th>Könyv típusok</th>
                        <th>Összes darabszám</th>
                        <th>Állapot</th>
                        <th>Műveletek</th>
                    </tr>
                </tfoot>
                <tbody>
                                        <tr>
                        <td>10</td>
                        <td>Központi raktár</td>
                        <td>{{rand(20, 80)}}</td>
                        <td>{{rand(1000, 3000)}}</td>
                        <td></td>
                        <td>
                            <div class="list-icons">                                   
                                <a href="http://alomgyar.yo:22200/gephaz/managment_templates/warehouse" class="list-icons-document text-primary-600"><i class="icon-eye"></i></a>
                                <a href="http://alomgyar.yo:22200/gephaz/publishers/10/edit" class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                            </div>
                        </td>
                    </tr>
                                        <tr>
                        <td>9</td>
                        <td>Webshop</td>
                        <td>{{rand(20, 80)}}</td>
                        <td>{{rand(1000, 3000)}}</td>
                        <td><span class="badge badge-success">Aktív</span></td>
                        <td>
                            <div class="list-icons">                                                                     
                                <a href="http://alomgyar.yo:22200/gephaz/managment_templates/warehouse" class="list-icons-document text-primary-600"><i class="icon-eye"></i></a>
                                <a href="http://alomgyar.yo:22200/gephaz/publishers/9/edit" class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                            </div>
                        </td>
                    </tr>
                                        <tr>
                        <td>8</td>
                        <td>Deák Téri Bolt</td>
                        <td>{{rand(20, 80)}}</td>
                        <td>{{rand(1000, 3000)}}</td>
                        <td></td>
                        <td>
                            <div class="list-icons">                                                                     
                                <a href="http://alomgyar.yo:22200/gephaz/managment_templates/warehouse" class="list-icons-document text-primary-600"><i class="icon-eye"></i></a>
                                <a href="http://alomgyar.yo:22200/gephaz/publishers/8/edit" class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                            </div>
                        </td>
                    </tr>
                                        <tr>
                        <td>7</td>
                        <td>Raktár X</td>
                        <td>{{rand(20, 80)}}</td>
                        <td>{{rand(1000, 3000)}}</td>
                        <td><span class="badge badge-success">Aktív</span></td>
                        <td>
                            <div class="list-icons">                                                                     
                                <a href="http://alomgyar.yo:22200/gephaz/managment_templates/warehouse" class="list-icons-document text-primary-600"><i class="icon-eye"></i></a>
                                <a href="http://alomgyar.yo:22200/gephaz/publishers/7/edit" class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                            </div>
                        </td>
                    </tr>
                                        <tr>
                        <td>6</td>
                        <td>Raktár Y</td>
                        <td>{{rand(20, 80)}}</td>
                        <td>{{rand(1000, 3000)}}</td>
                        <td></td>
                        <td>
                            <div class="list-icons">                                                                     
                                <a href="http://alomgyar.yo:22200/gephaz/managment_templates/warehouse" class="list-icons-document text-primary-600"><i class="icon-eye"></i></a>
                                <a href="http://alomgyar.yo:22200/gephaz/publishers/6/edit" class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                            </div>
                        </td>
                    </tr>
                                        <tr>
                        <td>5</td>
                        <td>Raktár Z</td>
                        <td>{{rand(20, 80)}}</td>
                        <td>{{rand(1000, 3000)}}</td>
                        <td></td>
                        <td>
                            <div class="list-icons">                                                                     
                                <a href="http://alomgyar.yo:22200/gephaz/managment_templates/warehouse" class="list-icons-document text-primary-600"><i class="icon-eye"></i></a>
                                <a href="http://alomgyar.yo:22200/gephaz/publishers/5/edit" class="list-icons-document text-primary-600"><i class="icon-pencil7"></i></a>
                            </div>
                        </td>
                    </tr>
                     
                                    </tbody>
            </table>

            <div class="row">
<div class="col-md-2">
    
    <span class="badge badge-light">1</span> - <span class="badge badge-light">10</span> / <span class="badge badge-light">10</span> elem
</div>
<div class="col-md-8 justify-content-end text-center">
    
</div>
<div class="col-md-2">
    
        <select class="form-control" wire:model="perPage">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="300">300</option>
        </select>
</div>
</div>            </div>
    </div>
</div>
</div>
@endsection
