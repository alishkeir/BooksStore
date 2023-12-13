<?php

use Illuminate\Http\Request;

?>
<!-- Main sidebar -->
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        <span class="font-weight-semibold">Navigation</span>
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->


    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user-material">
            <div class="sidebar-user-material-body">
                <div class="card-body text-center">
                    <h6 class="mb-0 text-white text-shadow-dark">{{ Auth::user()->name ?? '' }}</h6>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                @if (Auth::user() && Auth::user()->hasRole('szerző'))
                    <li class="nav-item-header">
                        <div class="text-uppercase font-size-xs line-height-xs">Szerző</div>
                        <i class="icon-menu" title="Main"></i>
                    </li>
                    <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'writers')) active @endif">
                        <a href="/gephaz/writers/{{ Auth::user()->writer_id }}" class="nav-link">
                            <i class="icon-list"></i>
                            <span>Fogyásjelentések</span>
                        </a>
                    </li>
                @else
                    <!-- Products -->
                    <li class="nav-item-header">
                        <div class="text-uppercase font-size-xs line-height-xs">Webshop</div>
                        <i class="icon-menu" title="Main"></i>
                    </li>

                    @can('orders.index')
                        <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'orders')) active @endif">
                            <a href="{{ route('orders.index') }}" class="nav-link">
                                <i class="icon-list"></i>
                                <span>Megrendelések</span>
                            </a>
                        </li>
                    @endcan
                    @can('products.index')
                        <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'products')) active @endif">
                            <a href="{{ route('products.index') }}" class="nav-link">
                                <i class="icon-books"></i>
                                <span>Könyvek</span>
                            </a>
                        </li>
                    @endcan
                    @can('shop')
                        <li class="nav-item d-flex  @if (Route::currentRouteName() == 'shop') active @endif">
                            <a href="{{ route('shop') }}" class="nav-link">
                                <i class="icon-inbox"></i>
                                <span>Bolti eladás</span>
                            </a>
                        </li>
                    @endcan
                    @if (Auth::user()->can('authors') ||
                            Auth::user()->can('publishers.index') ||
                            Auth::user()->can('methods.index') ||
                            Auth::user()->can('categories') ||
                            Auth::user()->can('subcategories') ||
                            Auth::user()->can('countries.index') ||
                            Auth::user()->can('comments.index') ||
                            Auth::user()->can('writers.index') ||
                            Auth::user()->can('legal_owners.index'))
                        <li class="nav-item nav-item-submenu  @if (str_starts_with(Route::currentRouteName(), 'publishers') ||
                                str_starts_with(Route::currentRouteName(), 'authors') ||
                                str_starts_with(Route::currentRouteName(), 'categories') ||
                                str_starts_with(Route::currentRouteName(), 'writers') ||
                                str_starts_with(Route::currentRouteName(), 'legal_owners') ||
                                str_starts_with(Route::currentRouteName(), 'comments') ||
                                str_starts_with(Route::currentRouteName(), 'methods') ||
                                str_starts_with(Route::currentRouteName(), 'subcategories')) nav-item-open @endif ">
                            <a href="#" class="nav-link legitRipple"><i class="icon-sphere"></i>
                                <span>Törzsadatok</span></a>
                            <ul class="nav nav-group-sub" data-submenu-title="Törzsadatok"
                                @if (str_starts_with(Route::currentRouteName(), 'publishers') ||
                                        str_starts_with(Route::currentRouteName(), 'authors') ||
                                        str_starts_with(Route::currentRouteName(), 'categories') ||
                                        str_starts_with(Route::currentRouteName(), 'writers') ||
                                        str_starts_with(Route::currentRouteName(), 'legal_owners') ||
                                        str_starts_with(Route::currentRouteName(), 'countries') ||
                                        str_starts_with(Route::currentRouteName(), 'comments') ||
                                        str_starts_with(Route::currentRouteName(), 'methods') ||
                                        str_starts_with(Route::currentRouteName(), 'subcategories')) style="display:block;" @endif>
                                @can('authors')
                                    <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'authors')) active @endif">
                                        <a href="{{ route('authors.index') }}" class="nav-link">
                                            <i class="icon-quill4"></i>
                                            <span>Szerzők</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('writers.index')
                                    <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'writers')) active @endif">
                                        <a href="{{ route('writers.index') }}" class="nav-link">
                                            <i class="icon-quill4"></i>
                                            <span>Szerzőcsoportok (Írók)</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('legal_owners.index')
                                    <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'legal_owners')) active @endif">
                                        <a href="{{ route('legal_owners.index') }}" class="nav-link">
                                            <i class="icon-quill4"></i>
                                            <span>Jogtulajok</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('publishers.index')
                                    <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'publishers')) active @endif">
                                        <a href="{{ route('publishers.index') }}" class="nav-link">
                                            <i class="icon-library2"></i>
                                            <span>Kiadók</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('methods.index')
                                    <li class="nav-item d-flex  @if (str_starts_with(Route::currentRouteName(), 'methods')) active @endif">
                                        <a href="{{ route('methods.index') }}" class="nav-link">
                                            <i class="icon-hat"></i>
                                            <span>Fizetési/szállítási módok</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('categories')
                                    <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'categories')) active @endif">
                                        <a href="{{ route('categories.index') }}" class="nav-link">
                                            <i class="icon-cube4"></i>
                                            <span>Kategóriák</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('subcategories')
                                    <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'subcategories')) active @endif">
                                        <a href="{{ route('subcategories.index') }}" class="nav-link">
                                            <i class="icon-cube3"></i>
                                            <span>Alkategóriák</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('countries.index')
                                    <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'countries')) active @endif">
                                        <a href="{{ route('countries.index') }}" class="nav-link">
                                            <i class="icon-map5"></i>
                                            <span>Országok</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('comments.index')
                                    <li class="nav-item d-flex  @if (str_starts_with(Route::currentRouteName(), 'comments')) active @endif">
                                        <a href="{{ route('comments.index') }}" class="nav-link">
                                            <i class="icon-bubble-lines4"></i>
                                            <span>Hozzászólások</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                    @if (Auth::user()->can('posts.index') ||
                            Auth::user()->can('pages.index') ||
                            Auth::user()->can('templates.index') ||
                            Auth::user()->can('carousels.index') ||
                            Auth::user()->can('banners.index'))
                        <li
                            class="nav-item nav-item-submenu @if (str_starts_with(Route::currentRouteName(), 'posts') ||
                                    str_starts_with(Route::currentRouteName(), 'pages') ||
                                    str_starts_with(Route::currentRouteName(), 'banners') ||
                                    str_starts_with(Route::currentRouteName(), 'carousels') ||
                                    str_starts_with(Route::currentRouteName(), 'templates')) nav-item-open @endif  ">
                            <a href="#" class="nav-link legitRipple"><i class="icon-magazine"></i>
                                <span>Tartalom</span></a>
                            <ul class="nav nav-group-sub" data-submenu-title="Tartalom"
                                @if (str_starts_with(Route::currentRouteName(), 'posts') ||
                                        str_starts_with(Route::currentRouteName(), 'pages') ||
                                        str_starts_with(Route::currentRouteName(), 'banners') ||
                                        str_starts_with(Route::currentRouteName(), 'carousels') ||
                                        str_starts_with(Route::currentRouteName(), 'templates')) style="display:block;" @endif>
                                @can('posts.index')
                                    <li class="nav-item d-flex @if (Route::currentRouteName() == 'posts.index') active @endif">
                                        <a href="{{ route('posts.index') }}" class="nav-link">
                                            <i class="icon-blog"></i>
                                            <span>Magazin</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('pages.index')
                                    <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'pages')) active @endif">
                                        <a href="{{ route('pages.index') }}" class="nav-link">
                                            <i class="icon-pagebreak"></i>
                                            <span>Általános oldalak</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('templates.index')
                                    <li class="nav-item d-flex @if (str_starts_with(Route::currentRouteName(), 'templates')) active @endif">
                                        <a href="{{ route('templates.index') }}" class="nav-link">
                                            <i class=" icon-envelop5"></i>
                                            <span>E-mail sablonok</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('carousels.index')
                                    <li class="nav-item d-flex @if (Route::currentRouteName() === 'carousels.index') active @endif">
                                        <a href="{{ route('carousels.index') }}"
                                            class="nav-link @if (Route::currentRouteName() == 'carousel.index') active @endif">
                                            <i class="icon-image-compare"></i>
                                            <span>Főoldali slider</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('banners.index')
                                    <li class="nav-item d-flex @if (Route::currentRouteName() == 'banners') active @endif">
                                        <a href="{{ route('banners') }}" class="nav-link">
                                            <i class="icon-images3"></i>
                                            <span>Főoldali bannerek</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                    @can('promotions.index')
                        <li class="nav-item d-flex  @if (str_starts_with(Route::currentRouteName(), 'promotions')) active @endif">
                            <a href="{{ route('promotions.index') }}" class="nav-link">
                                <i class="icon-percent"></i>
                                <span>Akciók</span>
                            </a>
                        </li>
                    @endcan
                    @can('customers.index')
                        <li class="nav-item d-flex  @if (str_starts_with(Route::currentRouteName(), 'customers')) active @endif">
                            <a href="{{ route('customers.index') }}" class="nav-link">
                                <i class="icon-reading"></i>
                                <span>Ügyfelek</span>
                            </a>
                        </li>
                    @endcan
                    @can('settings.index')
                        <li class="nav-item nav-item-submenu @if (str_starts_with(Route::currentRouteName(), 'settings') || str_starts_with(Route::currentRouteName(), 'metadata')) nav-item-open @endif ">
                            <a href="#" class="nav-link legitRipple"><i class="icon-gear"></i>
                                <span>Beállítások</span></a>
                            <ul class="nav nav-group-sub" data-submenu-title="Aktuális fogyás állapotok"
                                @if (str_starts_with(Route::currentRouteName(), 'settings') || str_starts_with(Route::currentRouteName(), 'metadata')) style="display:block;" @endif>
                                <li class="nav-item d-flex  @if (str_starts_with(Route::currentRouteName(), 'settings')) active @endif">
                                    <a href="{{ route('settings.index') }}" class="nav-link">
                                        <span>Beállítások</span>
                                    </a>
                                </li>
                                <li class="nav-item d-flex  @if (str_starts_with(Route::currentRouteName(), 'metadata')) active @endif">
                                    <a href="{{ route('metadata.index') }}" class="nav-link">
                                        <span>Meta címkék</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @if (Auth::user()->can('package-points.package.list') ||
                            Auth::user()->can('package-points.shops.list') ||
                            Auth::user()->can('package-points.partners.list'))
                        <li
                            class="nav-item nav-item-submenu @if (str_starts_with(Route::currentRouteName(), 'package-points')) nav-item-open @endif  ">
                            <a href="#" class="nav-link legitRipple"><i class="icon-box"></i> <span>Álomgyár
                                    átvételi pont</span></a>
                            <ul class="nav nav-group-sub" data-submenu-title="Csomagpont"
                                @if (str_starts_with(Route::currentRouteName(), 'package-points')) style="display:block;" @endif>
                                @can('package-points.package.list')
                                    <li class="nav-item d-flex @if (request()->is('gephaz/package-points', 'gephaz/package-points/create')) active @endif">
                                        <a href="{{ route('package-points.package.list') }}" class="nav-link">
                                            <i class="icon-gift"></i>
                                            <span>Csomagok</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('package-points.shops.list')
                                    <li class="nav-item d-flex @if (request()->is('gephaz/package-points/shops*')) active @endif">
                                        <a href="{{ route('package-points.shops.list') }}" class="nav-link">
                                            <i class="icon-home"></i>
                                            <span>Boltok</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('package-points.partners.list')
                                    <li class="nav-item d-flex @if (request()->is('gephaz/package-points/partners*')) active @endif">
                                        <a href="{{ route('package-points.partners.list') }}" class="nav-link">
                                            <i class="icon-users2"></i>
                                            <span>Partnerek</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif

                    @can('recommenders.index')
                        <li class="nav-item d-flex  @if (str_starts_with(Route::currentRouteName(), 'recommenders')) active @endif">
                            <a href="{{ route('recommenders.index') }}" class="nav-link">
                                <i class="icon-accessibility"></i>
                                <span>Ajánló</span>
                            </a>
                        </li>
                    @endcan

                    @can('statistics.index')
                        <li class="nav-item d-flex  @if (str_starts_with(Route::currentRouteName(), 'statistics')) active @endif">
                            <a href="{{ route('statistics.index') }}" class="nav-link">
                                <i class="icon-stats-dots"></i>
                                <span>Statisztikák</span>
                            </a>
                        </li>
                    @endcan

                    {{-- }}
                <li class="nav-item nav-item-submenu @if (str_starts_with(Route::currentRouteName(), 'carousel') || Route::currentRouteName() == 'banners') nav-item-open @endif  ">
                    <a href="#" class="nav-link legitRipple"><i class="icon-page-break"></i> <span>Főoldal</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Főoldal"
                        @if (str_starts_with(Route::currentRouteName(), 'carousel') || Route::currentRouteName() == 'banners') style="display:block;" @endif>
                        <li class="nav-item d-flex @if (Route::currentRouteName() === 'carousels.index') active @endif">
                            <a href="{{ route('carousels.index') }}" class="nav-link @if (Route::currentRouteName() == 'carousel.index') active @endif">
                                <i class="icon-image-compare"></i>
                                <span>Carousel</span>
                            </a>
                        </li>
                        <li class="nav-item d-flex @if (Route::currentRouteName() == 'banners') active @endif">
                            <a href="{{ route('banners') }}" class="nav-link">
                                <i class="icon-image-compare"></i>
                                <span>Banner</span>
                            </a>
                        </li>
                    </ul>
                </li>
--}}
                    <li class="nav-item-header">
                        <div class="text-uppercase font-size-xs line-height-xs">Raktárkezelő</div>
                        <i class="icon-menu" title="Raktárkezelő"></i>
                    </li>
                    @can('warehouses.index')
                        <li class="nav-item d-flex  @if (Route::currentRouteName() == 'warehouses.index') active @endif">
                            <a href="{{ route('warehouses.index') }}" class="nav-link">
                                <i class="icon-office"></i>
                                <span>Raktárkezelő</span>
                            </a>
                        </li>
                    @endcan
                    @can('warehouses.stock-in')
                        <li class="nav-item d-flex  @if (Route::currentRouteName() == 'warehouses.stock-in') active @endif">
                            <a href="{{ route('warehouses.stock-in') }}" class="nav-link">
                                <i class="icon-database-insert"></i>
                                <span>Be/kivételezés</span>
                            </a>
                        </li>
                    @endcan
                    @can('product_movements.index')
                        <li class="nav-item d-flex  @if (Route::currentRouteName() == 'product_movements.index') active @endif">
                            <a href="{{ route('product_movements.index') }}" class="nav-link">
                                <i class="icon-archive"></i>
                                <span>Bizonylatok</span>
                            </a>
                        </li>
                    @endcan
                    @can('suppliers.index')
                        <li class="nav-item d-flex  @if (Route::currentRouteName() == 'suppliers.index') active @endif">
                            <a href="{{ route('suppliers.index') }}" class="nav-link">
                                <i class="icon-truck"></i>
                                <span>Beszállítók</span>
                            </a>
                        </li>
                    @endcan
                    @can('shops.index')
                        <li class="nav-item d-flex  @if (str_starts_with(Route::currentRouteName(), 'shops')) active @endif">
                            <a href="{{ route('shops.index') }}" class="nav-link">
                                <i class="icon-store2"></i>
                                <span>Könyvesboltok</span>
                            </a>
                        </li>
                    @endcan
                    @can('consumption_report.index')
                        <li class="nav-item d-flex  @if (Route::currentRouteName() == 'consumption_report.index') active @endif">
                            <a href="{{ route('consumption_report.index') }}" class="nav-link">
                                <i class="icon-statistics"></i>
                                <span>Fogyásjelentések</span>
                            </a>
                        </li>
                    @endcan

                    @if (Auth::user()->can('consumption_reports.show') ||
                            Auth::user()->can('consumption_reports.show-author') ||
                            Auth::user()->can('consumption_reports.show-legal'))
                        <li
                            class="nav-item nav-item-submenu @if (str_starts_with(Route::currentRouteName(), 'actual_consumption_report')) nav-item-open @endif  ">
                            <a href="#" class="nav-link legitRipple"><i class="icon-calculator"></i>
                                <span>Aktuális fogyás állapotok</span></a>
                            <ul class="nav nav-group-sub" data-submenu-title="Aktuális fogyás állapotok"
                                @if (str_starts_with(Route::currentRouteName(), 'consumption_report')) style="display:block;" @endif>
                                @can('consumption_reports.show')
                                    <li class="nav-item d-flex  @if (Route::currentRouteName() == 'actual_consumption_report.show') active @endif">
                                        <a href="{{ route('consumption_report.show') }}" class="nav-link">
                                            <i class="icon-calculator"></i>
                                            <span>Beszállítói fogyás állapot</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('consumption_reports.show-author')
                                    <li class="nav-item d-flex  @if (Route::currentRouteName() == 'actual_consumption_report.show-author') active @endif">
                                        <a href="{{ route('consumption_report.show-author') }}" class="nav-link">
                                            <i class="icon-quill4"></i>
                                            <span>Szerzői fogyás állapot</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('consumption_reports.show-legal')
                                    <li class="nav-item d-flex  @if (Route::currentRouteName() == 'actual_consumption_report.show-legal') active @endif">
                                        <a href="{{ route('consumption_report.show-legal') }}" class="nav-link">
                                            <i class="icon-law"></i>
                                            <span>Jogtulajdonos fogyás állapot</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                    @can('consumption_report.merchant')
                        <li class="nav-item d-flex  @if (Route::currentRouteName() == 'consumption_report.merchant') active @endif">
                            <a href="{{ route('consumption_report.merchant') }}" class="nav-link">
                                <i class="icon-stats-growth"></i>
                                <span>Kereskedői fogyásjelentések</span>
                            </a>
                        </li>
                    @endcan

                    @hasanyrole('skvadmin|shop eladó')
                        <li class="nav-item nav-item-submenu @if (str_starts_with(Route::currentRouteName(), 'inventory')) nav-item-open @endif  ">
                            <a href="#" class="nav-link legitRipple"><i class="icon-list"></i>
                                <span>Leltár</span></a>
                            <ul class="nav nav-group-sub" data-submenu-title="Leltár"
                                @if (str_starts_with(Route::currentRouteName(), 'inventory')) style="display:block;" @endif>
                                <li class="nav-item d-flex  @if (Route::currentRouteName() == 'inventory_export.count') active @endif">
                                    <a href="{{ route('inventory_export.count') }}" class="nav-link">
                                        <i class="icon-list-numbered"></i>
                                        <span>Készletszám</span>
                                    </a>
                                </li>
                                @role('skvadmin')
                                    <li class="nav-item d-flex  @if (Route::currentRouteName() == 'inventory_export.inventory') active @endif">
                                        <a href="{{ route('inventory_export.inventory') }}" class="nav-link">
                                            <i class="icon-list"></i>
                                            <span>Leltár</span>
                                        </a>
                                    </li>
                                    <li class="nav-item d-flex  @if (Route::currentRouteName() == 'inventory_export.index') active @endif">
                                        <a href="{{ route('inventory_export.index') }}" class="nav-link">
                                            <i class="icon-cloud-download"></i>
                                            <span>Leltárív Export</span>
                                        </a>
                                    </li>
                                @endrole
                            </ul>
                        </li>
                    @endhasanyrole

                    @hasanyrole('skvadmin|admin|super admin')
                        <li class="nav-item-header">
                            <div class="text-uppercase font-size-xs line-height-xs">Adminisztráció</div>
                            <i class="icon-menu" title="Adminisztráció"></i>
                        </li>
                        {{-- }}
                <li class="nav-item d-flex  @if (str_starts_with(Route::currentRouteName(), 'synchronizations')) active @endif">
                    <a href="{{ route('synchronizations.index') }}" class="nav-link">
                        <i class="icon-spinner10"></i>
                        <span>Szinkronizáció</span>
                    </a>
                </li> --}}
                        @hasanyrole('skvadmin|super admin')
                            <li class="nav-item d-flex @if (Route::currentRouteName() == 'user.index') active @endif">
                                <a href="{{ route('user.index') }}" class="nav-link">
                                    <i class="icon-user"></i>
                                    <span>Felhasználók</span>
                                </a>
                                <a href="{{ route('user.create') }}" class="nav-link ml-auto nav-link-add">
                                    <i class="icon-add"></i>
                                </a>
                            </li>
                        @endhasanyrole

                        @role('skvadmin')
                            <li class="nav-item d-flex @if (Route::currentRouteName() == 'affiliate_program.index') active @endif">
                                <a href="{{ route('affiliates.index') }}" class="nav-link">
                                    <i class="icon-gear"></i>
                                    <span>Affiliate program</span>
                                </a>
                            </li>
                        @endrole
                        @can('permissions')
                            <li class="nav-item d-flex @if (Route::currentRouteName() == 'permissions.index') active @endif">
                                <a href="{{ route('permissions.index') }}" class="nav-link">
                                    <i class="icon-unlocked2"></i>
                                    <span>Jogosultság kezelés</span>
                                </a>
                            </li>
                        @endcan
                        @role('skvadmin')
                            {{-- <li class="nav-item d-flex @if (Route::currentRouteName() == 'general.index') active @endif">
                    <a href="{{route('general.index')}}" class="nav-link">
                        <i class="icon-gear"></i>
                        <span>Beállítások</span>
                    </a>
                </li> --}}
                        @endrole
                        {{-- <li class="nav-item d-flex @if (Route::currentRouteName() == 'activity_logs.index') active @endif">
                    <a href="{{route('activity_logs.index')}}" class="nav-link">
                        <i class="icon-database-time2"></i>
                        <span>Aktivitás log</span>
                    </a>
                </li> --}}
                    @endhasanyrole
                    @role('skvadmin')
                        {{-- }}
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">DEV</div>
                    <i class="icon-menu" title="DEV"></i>
                </li>
                <li class="nav-item d-flex @if (Route::currentRouteName() == 'packages.index') active @endif">
                    <a href="{{ route('packages.index') }}" class="nav-link">
                        <i class="icon-package"></i>
                        <span>Packages</span>
                    </a>
                    <a href="{{ route('packages.create') }}" class="nav-link ml-auto nav-link-add">
                        <i class="icon-add"></i>
                    </a>
                </li>
                <li class="nav-item d-flex @if (Route::currentRouteName() == 'logs.index') active @endif">
                    <a href="{{route('logs.index')}}" class="nav-link">
                        <i class="icon-stack2"></i>
                        <span>Rendszer log</span>
                    </a>
                </li> --}}
                    @endrole
                @endif {{-- writer --}}
            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
<!-- /main sidebar -->
