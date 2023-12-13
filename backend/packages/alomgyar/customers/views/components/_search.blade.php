<div class="row mt-1">
    <div class="col-md-4">
        <div class="form-group-feedback form-group-feedback-right">
            <input wire:model="s" type="text" class="form-control" placeholder="Kezdj el gépelni...">
            <div class="form-control-feedback text-muted pr-2">
                <i class="icon-search4"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
    <div class="col-md-5 text-right">
        <div class="row">
            <div class="col-md-4">
                <div class="form-check">
                    <label class="form-check-label">
                        <input wire:model="filters.store_0" type="checkbox" value="1" class="form-check-input" checked="">
                        Álomgyár
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <label class="form-check-label">
                        <input wire:model="filters.store_1" type="checkbox" value="1" class="form-check-input" checked="">
                        Olcsókönyvek
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <label class="form-check-label">
                        <input wire:model="filters.store_2" type="checkbox" value="1" class="form-check-input" checked="">
                        Nagyker
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>