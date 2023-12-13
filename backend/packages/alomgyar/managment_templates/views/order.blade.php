@extends('admin::layouts.master')
@section('pageTitle')
    Ügyvitel sablonok
@endsection

@section('header')
    @include('managment_templates::components.header', ['title' => 'Ügyvitel sablonok', 'subtitle' => 'Megrendelés'])
@endsection

@section('content')
<div class="flex-fill overflow-auto">

    <!-- Single mail -->
    <div class="card">

        <!-- Mail details -->
        <div class="card-body border-top">
            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ügyfél</th>
                        <th>Ár</th>
                        <th>Szállítási mód</th>
                        <th>Fizetési mód</th>
                        <th>Dátum</th>
                        <th>Státusz</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-left:3px solid   #4971ff;  ">
                        <td>43</td>
                        <td>
                            <strong style="white-space: nowrap;">Somogyi ZRT</strong>
                            <br>  
                            <small>Ózd Kristóf lejáró 74.</small>
                        </td>
                        <td>4570 Ft
                        <br><small>2 db</small></td>
                        <td>
                            <strong style="white-space: nowrap;">Helyszíni átvétel</strong>
                            <br><small>6000 Ft</small>
                        </td>
                        <td>
                            Bankkártyával
                            <br><small><span class="d-block badge bg-orange-600" title="Fizetésre vár">Fizetésre vár</span></small>
                        </td>
                        <td>
                            <strong>2021-06-30</strong>
                            <br> 13:33:12
                        </td>
                        <td>
                            
                            <span class="d-block badge bg-info-600" title="Feldolgozás alatt">Feldolgozás alatt</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /mail details -->
        <!-- Action toolbar -->
        <div class="navbar navbar-light navbar-expand-lg shadow-0 py-lg-2 rounded-top">
            <div class="text-center d-lg-none w-100">
                <button type="button" class="navbar-toggler w-100 h-100" data-toggle="collapse" data-target="#inbox-toolbar-toggle-read">
                    <i class="icon-circle-down2"></i>
                </button>
            </div>

            <div class="navbar-collapse text-center text-lg-left flex-wrap collapse" id="inbox-toolbar-toggle-read">
                <div class="mt-3 mt-lg-0 mr-lg-3">
                    <div class="btn-group">
                        {{-- <button type="button" class="btn btn-light legitRipple">
                            <i class="icon-file-check"></i>
                            <span class="d-none d-lg-inline-block ml-2">Fizetett</span>
                        </button>
                        <button type="button" class="btn btn-light legitRipple">
                            <i class="icon-file-play"></i>
                            <span class="d-none d-lg-inline-block ml-2">Feladott</span>
                        </button>
                        <button type="button" class="btn btn-light legitRipple">
                            <i class="icon-file-minus"></i>
                            <span class="d-none d-lg-inline-block ml-2">Érvénytelenít</span>
                        </button>--}}
                        <div class="btn-group ml-2">
                            <button type="button" class=" mr-3 btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i> Státusz állítás </button>

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
                        <button type="button" class="btn btn-primary btn-labeled btn-labeled-left legitRipple"><b><i class="icon-paperplane"></i></b> Számla újraküldése</button>
                    </div>
                </div>

                <div class="navbar-text ml-lg-auto"></div>

                <div class="ml-lg-3 mb-3 mb-lg-0">
                    <div class="btn-group">
                        <button type="button" class="btn btn-light legitRipple">
                            <i class="icon-printer"></i>
                            <span class="d-none d-lg-inline-block ml-2">Rendeléslap generálása</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /action toolbar -->




        <!-- Mail container -->
        <div class="card-body">
            <div class="overflow-auto mw-100">


                <div class="table-responsive">
                    <table class="table table-lg">
                        <thead>
                            <tr>
                                <th>Terméknév</th>
                                <th>Áfa</th>
                                <th>db</th>
                                <th>Egységár</th>
                                <th>Összesen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <a href="/gephaz/products/2/edit" target="_blank" class="mb-0 h5">Kisherceg</a>
                                </td>
                                <td>5%</td>
                                <td>2</td>
                                <td><span class="font-weight-semibold">1 500 Ft</span></td>
                                <td><span class="font-weight-semibold">3 000 Ft</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="/gephaz/products/2/edit" target="_blank" class="mb-0 h5">Nagyherceg</a>
                                </td>
                                <td>5%</td>
                                <td>1</td>
                                <td><span class="font-weight-semibold">4 500 Ft</span></td>
                                <td><span class="font-weight-semibold">4 500 Ft</span></td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
        <div class="card-body">
            <div class="d-md-flex flex-md-wrap">
                <div class="pt-2 mb-3">
                    <h6 class="mb-3">Vevő</h6>

                    <ul class="list-unstyled text-muted">
                        <li>Somogyi ZRT</li>
                        <li>Ózd</li>
                        <li>Kristóf lejáró 74.</li>
                        <li>Magyarország</li>
                    </ul>
                </div>

                <div class="pt-2 mb-3 wmin-md-400 ml-auto">
                    <h6 class="mb-3">Összesítő</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Részösszeg:</th>
                                    <td class="text-right">7 125 Ft</td>
                                </tr>
                                <tr>
                                    <th>Adó: <span class="font-weight-normal"></span></th>
                                    <td class="text-right">375 Ft</td>
                                </tr>
                                <tr>
                                    <th>Összesen:</th>
                                    <td class="text-right text-primary"><h5 class="font-weight-semibold">7 500 Ft</h5></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right mt-3">
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- /mail container -->


        <!-- Attachments -->
        <div class="card-body border-top">
            <h6 class="mb-0">Csatolt dokumentumok</h6>

            <ul class="list-inline mb-0">
                <li class="list-inline-item">
                    <div class="card bg-light py-2 px-3 mt-3 mb-0">
                        <div class="media my-1">
                            <div class="mr-3 align-self-center"><i class="icon-file-pdf icon-2x text-danger-400 top-0"></i></div>
                            <div class="media-body">
                                <div class="font-weight-semibold">szamla-2020-98346.pdf</div>

                                <ul class="list-inline list-inline-condensed mb-0">
                                    <li class="list-inline-item text-muted">174 KB</li>
                                    <li class="list-inline-item"><a href="#">Megtekint</a></li>
                                    <li class="list-inline-item"><a href="#">Letölt</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                {{--
                <li class="list-inline-item">
                    <div class="card bg-light py-2 px-3 mt-3 mb-0">
                        <div class="media my-1">
                            <div class="mr-3 align-self-center"><i class="icon-file-pdf icon-2x text-danger-400 top-0"></i></div>
                            <div class="media-body">
                                <div class="font-weight-semibold">szallitolevel-876876876.pdf</div>

                                <ul class="list-inline list-inline-condensed mb-0">
                                    <li class="list-inline-item text-muted">736 KB</li>
                                    <li class="list-inline-item"><a href="#">Megtekint</a></li>
                                    <li class="list-inline-item"><a href="#">Letölt</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>--}}
                <li class="list-inline-item">
                    <div class="card bg-light py-2 px-3 mt-3 mb-0">
                        <div class="media my-1">
                            <div class="mr-3 align-self-center"><i class="icon-file-pdf icon-2x text-danger-400 top-0"></i></div>
                            <div class="media-body">
                                <div class="font-weight-semibold">rendeleslap-876876876.pdf</div>

                                <ul class="list-inline list-inline-condensed mb-0">
                                    <li class="list-inline-item text-muted">736 KB</li>
                                    <li class="list-inline-item"><a href="#">Megtekint</a></li>
                                    <li class="list-inline-item"><a href="#">Letölt</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- /attachments -->

    </div>
    <!-- /single mail -->

</div>

@endsection
