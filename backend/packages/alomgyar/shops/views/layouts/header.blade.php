<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title">
            <h4 class="d-block"><strong>{{ \Alomgyar\Shops\Shop::find( Auth::user()->shop_id )->title ?? 'Nincs kiválasztva bolt!'}}</strong></h4>
            
            <h5 class="d-block">Bejelentkezett eladó : {{Auth::user()->name ?? ''}}</h5>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        
        <div class="header-elements d-none domain-select">
            <div class="d-flex justify-content-center  border-default-300">
            <a href="#" data-domain="0" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                <img src="/logo-alomgyar.png">
            </a>
            </div>
        </div>
    </div>

</div>