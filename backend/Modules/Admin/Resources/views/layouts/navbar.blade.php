<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark navbar-static">
    <div class="navbar-brand">
        <a href="{{ url('/gephaz') }}" class="d-inline-block">
            <img src="{{ config('pam.logo_url') }}" alt="Skvadmin">
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-md-auto">

            <li class="nav-item">
                <a class="navbar-nav-link" href="#"
                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="icon-switch2"></i>
                    <span class="d-md-none ml-2">Logout</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->

<style>
    table.table td {
        padding-top: 4px;
        padding-bottom: 4px;
    }

    .nav-item {
        line-height: 11px !important;
    }
</style>
