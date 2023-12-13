@extends('admin::layouts.master')
@section('header')
    @include('admin::layouts.header', ['title' => 'Package', 'subtitle' => 'Create new', 'button' => ''])
@endsection
@section('content')
<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">Package létrehozása</h5>
    </div>
    <div class="card-body">
        <form action="{{route('packages.store')}}" method="POST">
            @csrf
            <fieldset class="mb-3">
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Package neve</label>
                    <div class="col-lg-8">
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control pl-2 @error('name') border-danger @enderror" required placeholder="A package neve">
                        @error('name')
                            <span class="form-text text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Mappa</label>
                    <div class="col-lg-8">
                        <input type="text" name="folder" value="{{ old('folder') }}" class="form-control pl-2 @error('folder') border-danger @enderror" placeholder="Vagy skvadcom vagy a projekt mappa neve">
                        @error('folder')
                            <span class="form-text text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Resource típusú <br><small>Egyelőre csak resource típust állít elő. Meglátjuk van-e szükség továbbfejleszteni</small></label>

                    <div class="col-lg-8">
                        <select name="resource" id="" class="form-control pl-2 @error('resource') border-danger @enderror">
                            <option value="true">Igen</option>
                            <option value="false">Nem</option>
                        </select>
                        @error('resource')
                            <span class="form-text text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-4">Fields <br><small>Adatbázis tábla mezői (ami nem id és timestamp)</small></label>
                    <div class="col-lg-8">
                        <input type="text" name="fields" value="{{ old('fields') }}" class="form-control pl-2 @error('fields') border-danger @enderror" placeholder="Vesszővel tagolva pl. field1,field2">
                        @error('fields')
                            <span class="form-text text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>

            </fieldset>
            <div class="text-right">
                <button type="submit" class="btn btn-primary legitRipple">Létrehoz <i class="icon-paperplane ml-2"></i></button>
            </div>
        </form>
    </div>
</div>

@endsection