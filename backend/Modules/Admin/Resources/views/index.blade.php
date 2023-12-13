@extends('admin::layouts.master')

@section('content')
    @if (Auth::user() && Auth::user()->hasRole('skvadmin'))
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-success-400 has-bg-image">
                    <div class="media">

                        <div class="media-body">
                            <h3 class="mb-0">{{ $okListCount }}</h3>
                            <a href="/gephaz/orders/ok" target="_blank" class="text-white"><span
                                    class="text-uppercase font-size-xs">
                                    Teljesíthető rendelések
                                </span></a>
                        </div>
                        <div class="mr-3 align-self-center">
                            <i class="icon-folder-check icon-3x opacity-75"></i>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-blue-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $almostListCount }}</h3>
                            <a href="/gephaz/orders/almost" target="_blank" class="text-white"><span
                                    class="text-uppercase font-size-xs">
                                    Majdnem teljesíthető
                                </span></a>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-folder-upload icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-danger-400 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $noListCount }}</h3>
                            <a href="/gephaz/orders/no" target="_blank" class="text-white"><span
                                    class="text-uppercase font-size-xs">
                                    Nem teljesíthető
                                </span></a>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-folder-minus icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-indigo-400 has-bg-image">
                    <div class="media">
                        <div class="mr-3 align-self-center">
                            <i class="icon-alert icon-3x opacity-75"></i>
                        </div>

                        <div class="media-body text-right">
                            <h3 class="mb-0">{{ $lowStockProductsCount ?? 0 }}</h3>
                            <a href="{{ route('products.index', ['lowstock' => 1]) }}" target="_blank"
                                class="text-white"><span class="text-uppercase font-size-xs">
                                    Alacsony raktárkészlettel
                                </span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">

                <div class="row">
                    <livewire:web-shop-orders-chart />

                    <div class="col-sm-6 col-xl-6">
                        <livewire:web-shop-last-week-order-chart />
                        <livewire:physical-shop-last-week-order-chart />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header header-elements-inline bg-teal-400 text-white">
                                <h6 class="card-title">Admin aktivitás</h6>
                            </div>

                            <div class="card-body">
                                <div class="list-feed">
                                    @foreach ($activity ?? [] as $entry)
                                        <div class="list-feed-item border-warning-400">
                                            <div class="text-muted font-size-sm mb-1">{{ $entry->created_at }}</div>
                                            {{ $entry->created }} <strong>{{ $entry->description }}</strong> <a
                                                href="/gephaz/activity_logs/{{ $entry->id }}">{{ $entry->subject_type }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div
                                class="card-footer bg-white d-sm-flex justify-content-sm-between align-items-sm-center text-center">
                                <div>
                                </div>
                                <div class="mt-2 mt-sm-0">
                                    <a href="{{ route('activity_logs.index') }}">Összes megtekintése <i
                                            class="icon-arrow-right14 ml-2"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header header-elements-inline bg-teal-400 text-white">
                                <h6 class="card-title">Utolsó új hozzászólások</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            @foreach ($latestComments as $comment)
                                                <tr>
                                                    <td>
                                                        {{ $comment->comment }}
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-3">
                                                                <a href="/gephaz/comments/{{ $comment->id }}/edit"
                                                                    class="btn bg-transparent border-success text-success rounded-round border-2 btn-icon legitRipple">
                                                                    <i class="icon-link"></i>
                                                                </a>
                                                                <a href="/gephaz/products/{{ $comment->product_id }}/edit#comments"
                                                                    class="btn bg-transparent border-success text-success rounded-round border-2 btn-icon legitRipple">
                                                                    <i class="icon-book"></i>
                                                                </a>
                                                                <span
                                                                    class="text-muted font-size-sm">{{ $comment->created_at }}</span>
                                                            </div>
                                                        </div>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                {{--                                 <td colspan="2"> {{ $latestComments->links() }} </td> --}}
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div
                                class="card-footer bg-white d-sm-flex justify-content-sm-between align-items-sm-center text-center">
                                <div>
                                </div>
                                <div class="mt-2 mt-sm-0">
                                    <a href="/gephaz/comments">Összes megtekintése <i
                                            class="icon-arrow-right14 ml-2"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header header-elements-inline bg-teal-400 text-white">
                        <h6 class="card-title">Utolsó 10 bejelentkezés</h6>
                    </div>
                    <div class="card-body">
                        @foreach ($lastLogins as $item)
                            <button type="button"
                                class="mb-1 py-0 btn btn-light text-dark btn-labeled btn-labeled-left rounded-round legitRipple">
                                <b><i class="icon-user"></i></b>
                                <small>{{ $users->where('id', $item->causer_id)->first()?->name ?? 'N/A' }}
                                    <br>{{ $item->created_at }}</small></button>
                        @endforeach
                    </div>
                    <div
                        class="card-footer bg-white d-sm-flex justify-content-sm-between align-items-sm-center text-center">
                        <div>
                        </div>
                        <div class="mt-2 mt-sm-0">
                            <a href="{{ route('activity_logs.index') }}">Összes megtekintése <i
                                    class="icon-arrow-right14 ml-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">

                <div class="card card-body bg-indigo-400 has-bg-image">
                    <div class="media">
                        <div class="mr-3 align-self-center">
                            <i class="icon-alert icon-3x opacity-75"></i>
                        </div>

                        <div class="media-body text-right">
                            <h3 class="mb-0">{{ $book24ImportCount ?? 0 }}</h3>
                            <a href="{{ route('products.index', ['b24_import' => 1]) }}" target="_blank"
                                class="text-white"><span class="text-uppercase font-size-xs">Book24 nem kezelt
                                    importok</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-danger-400 has-bg-image">
                    <div class="media">

                        <div class="media-body">
                            <a href="/gephaz/orders/ok" target="_blank" class="text-white">
                                <span class="text-uppercase font-size-xs">
                                    Hiányzó jogosultság
                                </span>
                            </a>
                        </div>
                        <div class="mr-3 align-self-center">
                            <i class="icon-warning22 icon-3x opacity-75"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
