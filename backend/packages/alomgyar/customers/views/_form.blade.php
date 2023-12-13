@isset($model)
<form action="{{route('customers.update', ['customer' => $model])}}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('customers.store')}}" method="POST" enctype="multipart/form-data" id="form">
@endisset
    @csrf

    <div class="d-md-flex align-items-md-start">

        <!-- Left sidebar component -->
        <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-300 border-0 shadow-0 sidebar-expand-md">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Navigation -->
                <div class="card">

                    <div class="card-body p-0">
                        <ul class="nav nav-sidebar mb-2 col-md-12">
                            <li class="nav-item-header">Lehetőségek</li>
                            <li class="nav-item">
                                <a href="#edit" class="nav-link legitRipple py-1 active show" data-toggle="tab">
                                    <i class="icon-user"></i>
                                    Szerkesztés
                                </a>
                            </li>
                            @isset($model)
                            <li class="nav-item">
                                <a href="#orders" class="nav-link legitRipple py-1" data-toggle="tab">
                                    <i class="icon-price-tag"></i>
                                    Rendelések
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#preorders" class="nav-link legitRipple py-1" data-toggle="tab">
                                    <i class="icon-cart2"></i>
                                    Előjegyzések
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#review" class="nav-link legitRipple py-1" data-toggle="tab">
                                    <i class="icon-thumbs-up2"></i>
                                    Értékelések
                                    {{--<span class="badge bg-success badge-pill ml-auto">16</span>--}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#wishlist" class="nav-link legitRipple py-1" data-toggle="tab">
                                    <i class="icon-heart6"></i>
                                    Kívánságlista
                                    {{--<span class="badge bg-success badge-pill ml-auto">16</span>--}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#billingaddresses" class="nav-link legitRipple py-1" data-toggle="tab">
                                    <i class="icon-list"></i>
                                    Számlázási címek
                                    <span class="badge bg-danger badge-pill ml-auto">{{ $countBillingAddresses }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#shippingaddresses" class="nav-link legitRipple py-1" data-toggle="tab">
                                    <i class="icon-list"></i>
                                    Szállítási címek
                                    <span class="badge bg-danger badge-pill ml-auto">{{ $countShippingAddresses }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#comments" class="nav-link legitRipple py-1" data-toggle="tab">
                                    <i class="icon-bubble-lines4"></i>
                                    Hozzászólások
                                    <span class="badge bg-danger badge-pill ml-auto">{{ $countComments }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#affiliate" class="nav-link legitRipple py-1" data-toggle="tab">
                                    <i class="icon-gift"></i>
                                    Affiliate
                                </a>
                            </li>
                            @endisset
                        </ul>
                    </div>

                </div>
                <!-- /navigation -->

            </div>
            <!-- /sidebar content -->

        </div>
        <!-- /left sidebar component -->

        <!-- Right content -->
        <div class="tab-content w-100 {{--overflow-auto--}}">
            @include('customers::partials.edit')
            @isset($model)
            @include('customers::partials.orders')
            @include('customers::partials.preorders')
            @include('customers::partials.billing_addresses')
            @include('customers::partials.shipping_addresses')
            @include('customers::partials.review')
            @include('customers::partials.wishlist')
            @include('customers::partials.comments')
            @include('customers::partials.affiliate')
            @endisset
        </div>
        <div>
            <ul class="fab-menu fab-menu-fixed fab-menu-bottom-right">
                <li>
                    <button type="submit" class="fab-menu-btn btn btn-primary btn-float rounded-round btn-icon legitRipple" title="{{ __('messages.save') }}">
                        <i class="fab-icon-open icon-paperplane"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <!-- /right content -->
</form>
