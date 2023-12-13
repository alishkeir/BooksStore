<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4>
                {{-- <a href="javascript:;" onclick="window.history.back();" class="text-default"><i class="icon-arrow-left52 mr-2"></i></a> --}}
                <span class="font-weight-semibold">{{ $title }}</span>
                @if ($subtitle)
                    - {{ $subtitle }}
                @endif
            </h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        <div class="header-elements d-none domain-select">
            {{-- <div class="d-flex justify-content-center  bg-light border-top-2  border-success-300">
            <a href="#" data-domain="1" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                <img src="/logo-alomgyar.png">
            </a>
            </div> --}}
            <div class="d-flex justify-content-center  border-default-300">
                <a href="#" data-domain="0"
                    class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                    <img src="/logo-alomgyar.png">
                </a>
            </div>
            <div class="d-flex justify-content-center  border-danger-300">
                <a href="#" data-domain="1"
                    class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                    <img src="/logo-olcsokonyvek.png">
                </a>
            </div>
            <div class="d-flex justify-content-center  border-info-300">
                <a href="#" data-domain="2"
                    class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                    <img src="/logo-nagyker.png">
                </a>
            </div>
        </div>
        {{-- <div class="header-elements d-none">
            <div class="d-flex justify-content-center">
                <a href="#" class="btn btn-link btn-float text-default"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
                <a href="#" class="btn btn-link btn-float text-default"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
                <a href="#" class="btn btn-link btn-float text-default"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
            </div>
        </div> --}}
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin') }}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i></a>
                @for ($i = 2; $i <= count(Request::segments()); $i++)
                    @if ($i < count(Request::segments()))
                        @php
                            $segment = implode('/', array_slice(Request::segments(), 0, $i));
                        @endphp
                        <a href="{{ url($segment) }}" class="breadcrumb-item">{{ Request::segment($i) }}</a>
                    @elseif($i === count(Request::segments()))
                        <span class="breadcrumb-item active">{{ Request::segment($i) }}</span>
                    @endif
                @endfor
                {{-- <a href="" class="breadcrumb-item"> {{ $title }}</a>
                <span class="breadcrumb-item active"></span> --}}
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        {{-- @isset($selectedMonth)
            <div class="header-elements d-none ml-auto">
                <div class="breadcrumb justify-content-center">

                    <input type="month" id="selectedMonth" name="selectedMonth"
                        min="" max="{{\Carbon\Carbon::now()->subMonth()->format('Y-m')}}" value="{{\Carbon\Carbon::now()->subMonth()->format('Y-m')}}"
                        class="form-control"
                        onchange="updateUrl()"

                        >
                </div>
            </div>
        @endisset --}}
        @isset($button)
            <div class="header-elements d-none ml-auto">
                @isset($startMonth)
                    <div class="breadcrumb justify-content-center mr-2">
                        <input type="month" id="startMonth" name="startMonth" min=""
                            max="{{ \Carbon\Carbon::now()->subMonth()->format('Y-m') }}"
                            value="{{ \Carbon\Carbon::now()->subMonth()->format('Y-m') }}" class="form-control"
                            onchange="updateUrl()">
                    </div>
                @endisset
                @isset($selectedMonth)
                    <div class="breadcrumb justify-content-center mr-2">
                        <input type="month" id="selectedMonth" name="selectedMonth" min=""
                            max="{{ \Carbon\Carbon::now()->subMonth()->format('Y-m') }}"
                            value="{{ \Carbon\Carbon::now()->subMonth()->format('Y-m') }}" class="form-control"
                            onchange="updateUrl()">
                    </div>
                @endisset
                <div class="breadcrumb justify-content-center">
                    <a href="{{ $button }}" class="breadcrumb-elements-item text-{{ $buttonClass ?? 'success' }}"
                        id="{{ str_replace('/', '-', implode('/', array_slice(Request::segments(), 1, count(Request::segments())))) }}-btn">
                        <i class="icon-plus22 mr-2"></i>
                        {{ $buttonText ?? 'Új létrehozása' }}
                    </a>
                </div>
            </div>
        @endisset
        @isset($button2)
            <div class="header-elements d-none ml-3">
                <div class="breadcrumb justify-content-center">
                    <a href="{{ $button2 }}" class="breadcrumb-elements-item text-{{ $buttonClass2 ?? 'primary' }}"
                        id="{{ str_replace('/', '-', implode('/', array_slice(Request::segments(), 1, count(Request::segments())))) }}2-btn">
                        <i class="icon-plus22 mr-2"></i>
                        {{ $buttonText2 ?? 'Új létrehozása' }}
                    </a>
                </div>
            </div>
        @endisset
        @isset($button3)
            <div class="header-elements d-none ml-3">
                <div class="breadcrumb justify-content-center">
                    <a href="{{ $button3 }}" class="breadcrumb-elements-item text-{{ $buttonClass3 ?? 'secondary' }}"
                        id="{{ str_replace('/', '-', implode('/', array_slice(Request::segments(), 1, count(Request::segments())))) }}3-btn">
                        <i class="icon-plus22 mr-2"></i>
                        {{ $buttonText3 ?? 'Új létrehozása' }}
                    </a>
                </div>
            </div>
        @endisset
    </div>
</div>

@push('inline-js')
    <script>
        function updateUrl() {
            var selectedMonth = document.getElementById("selectedMonth");
            var startMonth = document.getElementById("startMonth");
            @isset($button)
                var button1 = document.getElementById(
                    "{{ str_replace('/', '-', implode('/', array_slice(Request::segments(), 1, count(Request::segments())))) }}-btn"
                );
                setButtonUrl(button1);
            @endisset

            @isset($button2)
                var button2 = document.getElementById(
                    "{{ str_replace('/', '-', implode('/', array_slice(Request::segments(), 1, count(Request::segments())))) }}2-btn"
                );
                setButtonUrl(button2);
            @endisset

            @isset($button3)
                var button3 = document.getElementById(
                    "{{ str_replace('/', '-', implode('/', array_slice(Request::segments(), 1, count(Request::segments())))) }}3-btn"
                );
                setButtonUrl(button3);
            @endisset
        }

        function setButtonUrl(button) {
            buttonUrl = button.href;
            if (buttonUrl.substring(0, buttonUrl.indexOf('?selectedMonth='))) {
                buttonClearedUrl = buttonUrl.substring(0, buttonUrl.indexOf('?selectedMonth='));
            } else {
                buttonClearedUrl = buttonUrl;
            }
            if (buttonUrl.substring(0, buttonUrl.indexOf('?startMonth='))) {
                buttonClearedUrl = buttonUrl.substring(0, buttonUrl.indexOf('?startMonth='));
            } else {
                buttonClearedUrl = buttonUrl;
            }

            button.href = buttonClearedUrl + '?startMonth=' + startMonth.value + '&selectedMonth=' + selectedMonth.value;
        }
    </script>
@endpush
