@isset($model)
<form action="{{route('products.update', ['product' => $model])}}" method="POST" enctype="multipart/form-data" id="form">
    @method('PUT')
@else
<form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data" id="form">
@endisset
    @csrf

    <div class="d-md-flex align-items-md-start" x-data="{ tab: window.location.hash ? window.location.hash.substring(1) : 'edit' }">

        <!-- Left sidebar component -->
        <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left wmin-300 border-0 shadow-0 sidebar-expand-md">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Navigation -->
                <div class="card">
                    <div class="card-body @isset($model) @if($model->type == 1) bg-success-400 @else bg-indigo-400 @endif @endisset text-center card-img-top">
                        <div>
                            <div class="dropzone" data-type="products" data-url="{{url(route('fileupload'))}}" style="min-height:200px;">
                                <div class="dz-message" data-dz-message style="position: absolute; top: 0; text-align: center; left: 0; bottom: 0; color: #aaa; width: 100%; display: flex; align-items: flex-end; justify-content: center;"><span>Húzd ide a képet, vagy kattints a feltöltéshez</span></div>
                                <img src="{{ old('cover') ?? substr(($model->cover ?? ''), 0, 4)=='http' ? ($model->cover ?? '') : '/storage/'.($model->cover ?? '') }}" width="100%" class="preview" style="width:100%;"/>
                            </div>
                            <input type="text" class="form-control" name="cover" value="{{ old('cover') ?? $model->cover ?? ''}}">
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <ul class="nav nav-sidebar mb-2 col-md-12">
                            <li class="nav-item-header">Lehetőségek</li>
                            <li class="nav-item">
{{--                                <a href="#edit" class="nav-link legitRipple py-1 active show" data-toggle="tab">--}}
                                <a class="nav-link legitRipple py-1" :class="{ 'active show': tab === 'edit' }" @click.prevent="tab = 'edit'; window.location.hash = 'edit'; console.log(tab)" href="#">
                                    <i class="icon-user"></i>
                                    Szerkesztés
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link legitRipple py-1" :class="{ 'active show': tab === 'price' }" @click.prevent="tab = 'price'; window.location.hash = 'price'" href="#">
{{--                                <a href="#price" class="nav-link legitRipple py-1" data-toggle="tab">--}}
                                    <i class="icon-price-tag"></i>
                                    Ár modul
                                </a>
                            </li>
                            @isset($model)
                            <li class="nav-item">
{{--                                <a href="#orders" class="nav-link legitRipple py-1" data-toggle="tab">--}}
                                <a class="nav-link legitRipple py-1" :class="{ 'active show': tab === 'orders' }" @click.prevent="tab = 'orders'; window.location.hash = 'orders'" href="#">
                                    <i class="icon-cart2"></i>
                                    Megrendelések
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link legitRipple py-1" :class="{ 'active show': tab === 'preorders' }" @click.prevent="tab = 'preorders'; window.location.hash = 'preorders'" href="#">
{{--                                <a href="#preorders" class="nav-link legitRipple py-1" data-toggle="tab">--}}
                                    <i class="icon-cart4"></i>
                                    Előjegyzések
                                </a>
                            </li>
                            <li class="nav-item">
{{--                                <a href="#reviews" class="nav-link legitRipple py-1" data-toggle="tab">--}}
                                <a class="nav-link legitRipple py-1" :class="{ 'active show': tab === 'reviews' }" @click.prevent="tab = 'reviews'; window.location.hash = 'reviews'" href="#">
                                <i class="icon-stars"></i>
                                    Értékelések
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link legitRipple py-1" :class="{ 'active show': tab === 'wishlist' }" @click.prevent="tab = 'wishlist'; window.location.hash = 'wishlist'" href="#">
{{--                                <a href="#wishlist" class="nav-link legitRipple py-1" data-toggle="tab">--}}
                                    <i class="icon-heart6"></i>
                                    Kívánságlistán
                                    {{--<span class="badge bg-danger badge-pill ml-auto">29</span>--}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link legitRipple py-1" :class="{ 'active show': tab === 'stock' }" @click.prevent="tab = 'stock'; window.location.hash = 'stock'" href="#">
{{--                                <a href="#stock" class="nav-link legitRipple py-1" data-toggle="tab">--}}
                                    <i class="icon-store"></i>
                                    Raktárkészlet
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link legitRipple py-1" :class="{ 'active show': tab === 'comments' }" @click.prevent="tab = 'comments'; window.location.hash = 'comments'" href="#">
{{--                                <a href="#comments" class="nav-link legitRipple py-1" data-toggle="tab">--}}
                                    <i class="icon-bubble-lines4"></i>
                                    Hozzászólások
                                </a>
                            </li>
                            <li class="nav-item" style="opacity:0.7;">
                                <a class="nav-link legitRipple py-1" :class="{ 'active show': tab === 'stat' }" @click.prevent="tab = 'stat'; window.location.hash = 'stat'" href="#">
{{--                                <a href="#stat" class="nav-link legitRipple py-1" data-toggle="tab">--}}
                                    <i class="icon-chart"></i>
                                    Statisztika
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
            <div class="tab-pane fade" x-show="tab === 'edit'" id="edit" :class="{ 'active show': tab === 'edit' }">
                @include('products::partials._edit')
            </div>
            <div class="tab-pane fade" id="price" x-show="tab === 'price'" :class="{ 'active show': tab === 'price' }">
                @include('products::partials._price')
            </div>
            @isset($model)
            <div class="tab-pane fade" id="comments" x-show="tab === 'comments'" :class="{ 'active show': tab === 'comments' }">
                @include('products::partials._comments')
            </div>
            <div class="tab-pane fade" id="orders" x-show="tab === 'orders'" :class="{ 'active show': tab === 'orders' }">
                @include('products::partials._orders')
            </div>
            <div class="tab-pane fade" id="preorders" x-show="tab === 'preorders'" :class="{ 'active show': tab === 'preorders' }">
                @include('products::partials._preorders')
            </div>
            <div class="tab-pane fade" id="reviews" x-show="tab === 'reviews'" :class="{ 'active show': tab === 'reviews' }">
                @include('products::partials._reviews')
            </div>
            <div class="tab-pane fade" id="wishlist"x-show="tab === 'wishlist'" :class="{ 'active show': tab === 'wishlist' }">
                @include('products::partials._wishlist')
            </div>
            <div class="tab-pane fade" id="stock" x-show="tab === 'stock'" :class="{ 'active show': tab === 'stock' }">
                @include('products::partials._stock')
            </div>
            @endisset
        </div>
        <!-- /right content -->

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
</form>



<div style="display:none;" id="li-tpl">
    <li class="media">
        <div class="mr-3 align-self-center">
            <i class="icon-cube3"></i>
        </div>
        <div class="media-body">
            <div class="font-weight-semibold">
                <select name="subcategory[]" class="form-control newselect @error('subcategory') border-danger @enderror">

                    @foreach ($subcategories ?? [] as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="ml-2 align-self-center">
            <a href="javascript:" onclick="$(this).parent().parent().remove()" class="icon icon-trash text-dark"></a>
        </div>
    </li>
</div>
<div style="display:none;" id="li-tpl-author">
    <li class="media">
        <div class="mr-3 align-self-center">
            <i class="icon-quill4 text-warning-300 top-0"></i>
        </div>
        <div class="media-body">
            <div class="font-weight-semibold">
                <select class="form-control author newselect" data-fouc name="author[]">
                </select>
            </div>
        </div>
        <div class="ml-2 align-self-center">
            <a href="javascript:" onclick="$(this).parent().parent().remove()" class="icon icon-trash text-dark"></a>
        </div>
    </li>
</div>
