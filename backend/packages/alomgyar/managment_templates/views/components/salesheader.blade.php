<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
        <h4>
            {{-- <a href="javascript:;" onclick="window.history.back();" class="text-default"><i class="icon-arrow-left52 mr-2"></i></a> --}}
            <span class="font-weight-semibold">{{ $title }}</span> @if ( $subtitle) - {{ $subtitle }} @endif
        </h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        
        <div class="header-elements d-none">
            <div class="d-flex justify-content-center">
                <a href="#" class="btn btn-link btn-float text-default"><i class="icon-calendar5 text-primary"></i> <span>Új fogyásjelentés</span></a>
            </div>
        </div> 
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i></a>
                @for($i = 2; $i <= count(Request::segments()); $i++)
                    @if ( $i < count(Request::segments()) )
                        @php
                            $segment = implode('/', array_slice(Request::segments(), 0, $i));
                        @endphp
                        <a href="{{ url($segment) }}" class="breadcrumb-item">{{ Request::segment($i) }}</a>
                    @elseif($i === count(Request::segments()) )
                        <span class="breadcrumb-item active">{{ Request::segment($i) }}</span>
                    @endif
                @endfor
                {{-- <a href="" class="breadcrumb-item"> {{ $title }}</a>
                <span class="breadcrumb-item active"></span> --}}
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <div class="header-elements d-none ml-auto">
            {{--}}
            @if (str_starts_with(Route::currentRouteName(), 'managment_templates.stock'))
            <div class="breadcrumb justify-content-center">
                <a href ="" class="btn btn-outline btn-sm alpha-info text-info-800 border-info-600 legitRipple ml-2">Raktárkezelő</a>
                <a href ="" class="btn btn-outline btn-sm alpha-info text-info-800 border-info-600 legitRipple ml-2">Beszállítókezelő</a>
            </div>
            @else
            <div class="breadcrumb justify-content-center">
                <a href ="" class="btn btn-outline btn-sm alpha-success text-success-800 border-success-600 legitRipple ml-2">Teljesíthető rendelések</a>
                <a href ="" class="btn btn-outline btn-sm alpha-danger text-danger-800 border-danger-600 legitRipple ml-2">Nem teljesíthető rendelések</a>
            </div>
            @endif
            --}}
        </div>
    </div>
</div>