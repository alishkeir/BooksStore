<div class="row mt-1">
    <div class="col-md-6">
        <div class="form-group-feedback form-group-feedback-right">
            <input wire:model="s" type="text" class="form-control" placeholder="Kezdj el gépelni...">
            <div class="form-control-feedback text-muted pr-2">
                <i class="icon-search4"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row float-right">
            <div class="col-4">
                <select class="form-control" wire:model="perPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <label for="" class="col-form-label col-lg-8">sor oldalanként</label>
        </div>
    </div>
</div>