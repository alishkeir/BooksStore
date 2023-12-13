@extends('admin::layouts.master')
@section('pageTitle')
Jogosultságkezelő
@endsection

@section('header')
    @include('admin::layouts.header', ['title' => 'Jogosultságkezelő', 'subtitle' => 'All items', 'button' => route('role.create'), 'buttonText' => __('permissions::trans.new_permission')])
@endsection

@section('content')

{{__('permissions::trans.permissions.index')}}
<form action="{{route('permissions.store') }}" method="POST" id="permission-form">
@csrf
<div class="card">
    <div class="card-body">
        <table style="width: 100%;">
        <thead>
            <tr>
                <td style="width: 150px"><b>Modulok</b></td>
                <td style="width: 200px"><b>Jogosultságok</b></td>
                @foreach($role_all as $role)
                @if(auth()->user()->can($role->name))
                <td><b>{{$role->name}}@if($role->name != 'skvadmin')<a href="{{route('role.edit', ['role' => $role->id])}}"><i class="icon-pencil5"></i></a>@endif</b></td>
                @endif
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($permissions as $key => $permission)
            <tr>
                <td>{{__($key.'::trans.'.$key)}}</td>
                <td>
                    <ul>
                    @foreach($permission as $key2 => $p)
                        <li style="border-bottom: 1px solid #eee;">
                        <label for="role[{{$role->id}}][{{$p}}]">{{__($key.'::trans.'.$p)}}</label>
                        </li>
                    @endforeach
                    </ul>
                </td>
                @foreach($role_all as $role)
                <td style="border-left: 1px solid #eee;">
                    <ul>
                        @foreach($permission as $key2 => $p)
                        <li class="role_input" style="border-bottom: 1px solid #eee;">
                           <label> <input type="checkbox" id="role[{{$role->id}}][{{$p}}]"  name="role[{{$role->id}}][{{$p}}]" @if(in_array($p, $rhp_checked[$role->id])) checked  @endif></label>
                            
                        </li>
                        @endforeach
                    </ul>
                </td>
                @endforeach
            </tr>

            @endforeach
            </tbody>

        </table>
        <button type="submit" class="btn btn-primary mt-3">Mentés</button>
    </div>
    
</div>

</form>
@endsection

@section('js')
<script>
$('#permission-form').submit(function(e){
    e.preventDefault();
    $.post($(this).attr('action'), $(this).serialize(), function(response){
        if (response.success) {
            new PNotify({
                text: 'A jogosultságok sikeresen beállítva',
                addclass: 'bg-success border-success'
            });
        }
    })
})
</script>
@endsection

@section('css')
<style>

.card-body table h5 {
    margin: 0;
    text-align: center;
}

.card-body table b i[class^="icon-"], table b i[class*=" icon-"] {
    margin-left: 5px;
    position: relative;
    top: -2px;
    font-size: 12px;
}

.card-body thead {
    border-bottom: 1px solid #bbb;
}

.card-body table tr {
    border-bottom: 1px solid #ddd;
}

.card-body table tr label {
    margin: 5px 0;
}

.card-body table ul {
    margin: 0;
    padding: 0;
}

.card-body table li {
    list-style-type: none;
}

.card-body table li.role_input {
    text-align: center;
}

.card-body table li.role_input label{
    display: block;
}

</style>
@endsection