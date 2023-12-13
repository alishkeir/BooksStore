<div wire:ignore.self class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cím szerkesztése</h5>
                <button type="button" class="close" data-dismiss="modal" wire:click.prevent="cancel()">&times;</button>
            </div>

            <form action="#">
                <input type="hidden" wire:model="address.id">
                <input type="hidden" wire:model="address.type">
                <div class="modal-body">
                    @if(!empty($address) && $address->type === 'billing')
                    <div class="form-group col-6">
                        <div class="row">
                            <div class="form-group mb-3 mb-md-2">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <div class="uniform-choice">
                                            <span class="{{ $address->entity_type == 1 ? 'checked' : '' }}">
                                                <input type="radio" class="form-check-input-styled" {{ $address->entity_type == 1 ? 'checked=""' : '' }} data-fouc="" value="1" wire:model="address.entity_type">
                                            </span>
                                        </div>
                                        Magán
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <div class="uniform-choice">
                                            <span class="{{ $address->entity_type == 2 ? 'checked' : '' }}">
                                                <input type="radio" class="form-check-input-styled" data-fouc="" {{ $address->entity_type == 2 ? 'checked=""' : '' }} value="2" wire:model="address.entity_type">
                                            </span>
                                        </div>
                                        Céges
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="font-weight-bold" for="last_name">Vezetéknév</label>
                                    <input type="text" class="form-control" wire:model.defer="address.last_name" id="last_name">
                                    @error('last_name') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-sm-6">
                                    <label class="font-weight-bold" for="first_name">Keresztnév</label>
                                    <input type="text" class="form-control" wire:model.defer="address.first_name" id="first_name">
                                    @error('last_name') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        @if(!empty($address) && $address->entity_type == 2)
                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <label class="font-weight-bold" for="business_name">Cégnév</label>
                                    <input type="text" class="form-control" wire:model.defer="address.business_name" id="business_name">
                                    @error('last_name') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-sm-6">
                                    <label class="font-weight-bold" for="vat_number">Adószám</label>
                                    <input type="text" class="form-control" wire:model.defer="address.vat_number" id="vat_number">
                                    @error('last_name') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="font-weight-bold" for="address">Cím</label>
                                <input type="text" class="form-control" wire:model="address.address" id="address">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="font-weight-bold" for="zip_code">Irányítószám</label>
                                <input type="text" class="form-control" wire:model="address.zip_code" id="zip_code">
                            </div>

                            <div class="col-sm-4">
                                <label class="font-weight-bold" for="city">Város</label>
                                <input type="text" class="form-control" wire:model="address.city" id="zip_code">
                            </div>

                            <div class="col-sm-4">
                                <label class="font-weight-bold" for="country">Ország</label>
                                <select name="country_id" class="form-control" id="country" wire:model="address.country_id">
                                    @if(!empty($address))
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" @if($country->id == $address->country_id) selected @endif>{{ $country->name }} ({{ $country->code }})</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="font-weight-bold" for="zip_code">Megjegyzés</label>
                                <textarea class="form-control" wire:model="address.comment" id="comment"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" wire:click.prevent="cancel()" class="btn btn-link" data-dismiss="modal">Mégsem</button>
                    <button type="button" wire:click.prevent="update()" class="btn bg-primary" data-dismiss="modal">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>
