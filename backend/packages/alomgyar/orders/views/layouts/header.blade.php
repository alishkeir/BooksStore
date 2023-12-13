<div class="page-header page-header-light">
    {{--
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
        <h4>
            
            <span class="font-weight-semibold">{{ $title }}</span> @if ( $subtitle) - {{ $subtitle }} @endif
        </h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        
        <div class="header-elements d-none domain-select">
            <div class="d-flex justify-content-center  border-default-300">
            <a href="#" data-domain="0" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                <img src="/logo-alomgyar.png">
            </a>
            </div>
            <div class="d-flex justify-content-center  border-danger-300">
            <a href="#" data-domain="1" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                <img src="/logo-olcsokonyvek.png">
            </a>
            </div>
            <div class="d-flex justify-content-center  border-info-300">
            <a href="#" data-domain="2" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                <img src="/logo-nagyker.png">
            </a>
            </div>
        </div>
    </div> --}}

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i></a>
                <span href="" class="breadcrumb-item">{{ $title }}</span>
                <span class="breadcrumb-item active">{{ $subtitle }}</span>

                {{-- <a href="" class="breadcrumb-item"> {{ $title }}</a>
                <span class="breadcrumb-item active"></span> --}}
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        <div class="header-elements d-none">
            
            <div class="breadcrumb justify-content-center">
                <a href="/gephaz/orders" class="breadcrumb-elements-item">
                    <i class="icon-list mr-2"></i>
                    Megrendelések
                </a>
                <a href="/gephaz/orders/items" class="breadcrumb-elements-item">
                    <i class="icon-list mr-2"></i>
                    Tételek
                </a>
                <a title="Teljesíthető" href="/gephaz/orders/ok" class="breadcrumb-elements-item">
                    <i class="icon-link mr-2"></i>
                </a>
                <a title="Majdnem Teljesíthető" href="/gephaz/orders/almost" class="breadcrumb-elements-item">
                    <i class="icon-link mr-2"></i>
                </a>
                <a title="Nem Teljesíthető" href="/gephaz/orders/no" class="breadcrumb-elements-item">
                    <i class="icon-link mr-2"></i>
                </a>
                <a href="{{ route('orders.create') }}" class="breadcrumb-elements-item text-success" id="{{ str_replace('/', '-', implode('/', array_slice(Request::segments(), 1, count(Request::segments())))) }}-btn">
                    <i class="icon-plus22 mr-2"></i>
                    Új létrehozása
                </a>

            </div>
            
        
            <div class="header-elements d-none ml-auto">

            </div>
                </div>
    </div>
</div>