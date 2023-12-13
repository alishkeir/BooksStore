<div class="page-header page-header-light">


    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i></a>

                <a href="" class="breadcrumb-item"> Könyvek</a>
                <span class="breadcrumb-item active"></span>
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        <div class="header-elements d-none">
            
            <div class="breadcrumb justify-content-center">
                <a href="/gephaz/products/flash-promotion" class="breadcrumb-elements-item">
                    <i class="icon-magic-wand2 mr-2"></i>
                    Villámakció
                </a>

                <a href="/gephaz/warehouses/product_movements" class="breadcrumb-elements-item">
                    <i class="icon-link mr-2"></i>
                    Bizonylatok
                </a>

                <div class="breadcrumb-elements-item dropdown p-0">
                    <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-gear mr-2"></i>
                        Ügyvitel törzsadatok
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(96px, 40px, 0px);">
                        <a href="/gephaz/warehouses" class="dropdown-item"><i class="icon-user-lock"></i> Raktárak</a>
                        <a href="/gephaz/suppliers" class="dropdown-item"><i class="icon-statistics"></i> Beszállítók</a>
                    </div>
                </div>
                <div class="breadcrumb-elements-item dropdown p-0 mr-2">
                    <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-gear mr-2"></i>
                        Manuális futtatás
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(96px, 40px, 0px);">
                        <a href="{{ route('products.ranked_list') }}" class="dropdown-item"><i class="icon-hammer-wrench"></i> Sikerlisták generálása most</a>
                        <a href="{{ route('products.calculate_price') }}" class="dropdown-item"><i class="icon-hammer-wrench"></i> Termék ár kalkulálás most</a>
                    </div>
                </div>
                <div class="breadcrumb justify-content-center">
                    <a href="{{ $button }}" class="breadcrumb-elements-item text-{{ $buttonClass ?? 'success'}}" id="{{ str_replace('/', '-', implode('/', array_slice(Request::segments(), 1, count(Request::segments())))) }}-btn">
                        <i class="icon-plus22 mr-2"></i>
                        {{ $buttonText ?? 'Új létrehozása'}}
                    </a>
                </div>
            </div>
            
        @isset($button)

            <div class="header-elements d-none ml-auto">

            </div>
        @endisset
        </div>
       
    </div>
</div>