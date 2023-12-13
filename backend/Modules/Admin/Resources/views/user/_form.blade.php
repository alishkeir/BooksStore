@csrf
<fieldset class="mb-3">
    <legend class="text-uppercase font-size-sm font-weight-bold">Profil adatok</legend>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">Felhasználónév</label>
        <div class="col-lg-10">
            <input type="text" name="name" value="{{ $user->name ?? old('name') }}" class="form-control pl-2 @error('name') border-danger @enderror">
            @error('name')
            <span class="form-text text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">Vezetéknév</label>
        <div class="col-lg-4">
            <input type="text" name="lastname" value="{{ $user->lastname ?? old('lastname') }}" class="form-control pl-2 @error('lastname') border-danger @enderror">
            @error('lastname')
            <span class="form-text text-danger">{{ $message }}</span>
            @enderror
        </div>
        <label class="col-form-label col-lg-2">Keresztnév</label>
        <div class="col-lg-4">
            <input type="text" name="firstname" value="{{ $user->firstname ?? old('firstname') }}" class="form-control pl-2 @error('firstname') border-danger @enderror">
            @error('firstname')
            <span class="form-text text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label class="col-form-label col-lg-2">Email</label>
        <div class="col-lg-10">
            <input type="text" name="email" value="{{ $user->email ?? old('email') }}" class="form-control pl-2 @error('email') border-danger @enderror">
            @error('email')
            <span class="form-text text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <legend class="text-uppercase font-size-sm font-weight-bold">Jogosultság</legend>
    <div class="row">
        @if(!empty($roles))
            <div class="col-md-12">
                <div class="form-group">
                    <label class="d-block font-weight-semibold">Szerepkör</label>
                    <div class="form-check form-check-inline form-check-right">
                        @foreach($roles as $role)
                            <div class="offset-1 ">
                                <label class="form-check-label">{{ $role->name }}
                                    <input type="hidden" name="role[{{ $role->name }}]" value="0">
                                    <input type="checkbox" name="role[{{ $role->name }}]" value="1" class="form-check-input" @if(isset($user) && $user->hasRole($role->name)) checked="" @endif>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        @if(!empty($permissions->items))
            <div class="offset-2 col-md-5">
                <div class="row">
                    <label class="col-form-label col-md-4">Permissions</label>
                    <div class="col-md-8">

                        <div class="form-group">
                            @foreach($permissions as $role)
                            <div class="form-check form-check-right">
                                <label class="form-check-label">{{$role->name}}
                                    <input type="hidden" name="role[{{$role->name}}]" value="0">
                                    <input type="checkbox" name="role[{{$role->name}}]" value="1" class="form-check-input">
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>



    <legend class="text-uppercase font-size-sm font-weight-bold">Hozzárendelések</legend>
    <div class="row">
        @if(isset($user) && $user->hasRole('shop eladó'))
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Hozzárendelt bolt</label>
                    <select class="form-control" name="shop_id">
                        <option></option>
                        @foreach($shops as $shop)
                            <option value="{{ $shop->id }}" @if(isset($user) && $shop->id == $user->shop_id) selected @endif>{{ $shop->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        @if(isset($user) && $user->hasRole('szerző'))
            <div class="col-md-4">
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Hozzárendelt Író</label>
                    <select class="form-control" name="writer_id">
                        <option></option>
                        @foreach($writers as $writer)
                            <option value="{{ $writer->id }}" @if(isset($user) && $writer->id == $user->writer_id) selected @endif>{{ $writer->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
    </div>


</fieldset>
<div class="text-right">
    <button type="submit" class="btn btn-primary legitRipple" onclick="return confirm('Biztosan menteni akarod?')">Mentés <i class="icon-paperplane ml-2"></i></button>
</div>

