@extends('admin::layouts.master')
@section('pageTitle')
    Csomagpont Partner
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Csomagpont Partnerek', 'subtitle' => 'Összes', 'button' => route('package-points.partners.create')])
@endsection

@section('content')
    <div class="card">

        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <td>Név</td>
                    <td>Link</td>
                    <td>Email</td>
                    <td>Telefonszám</td>
                    <td class="text-right">Műveletek</td>
                </tr>
                </thead>
                <tbody>
                @foreach($partners as $partner)
                    <tr>
                        <td>{{ $partner->name ?? '' }}</td>
                        <td>{{ $partner->link ?? '' }}</td>
                        <td>{{ $partner->email ?? '' }}</td>
                        <td>{{ $partner->phone ?? '' }}</td>
                        <td class="text-right">

                            <div class="list-icons">
                                <div class="btn-group ml-2">
                                    <button type="button" class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></button>

                                    <div class="dropdown-menu" style="">
                                        <a href="{{ route('package-points.partners.edit', $partner) }}" class="dropdown-item">Szerkesztés</a>
                                        <a href="{{ route('package-points.partners.delete', $partner) }}" data-delete="#deletePartner{{ $partner->id }}" class="delete dropdown-item">Törlés</a>

                                        <form action="{{route('package-points.partners.delete', $partner)}}" class="d-none dropdown-item" id="deletePartner{{ $partner->id }}"
                                            method="POST" onsubmit="return confirm({{ __('messages.delete-confirm') }});">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection

@push('inline-js')
    <script>
        $('.delete').on('click', function(e) {
            e.preventDefault();

            if (confirm('{{ __('messages.delete-confirm') }}')) {
                $($(this).data('delete')).submit();
            }
        });
    </script>
@endpush
