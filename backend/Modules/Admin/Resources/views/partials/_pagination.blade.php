<div class="row">
    <div class="col-md-2">

        <span class="badge badge-light">{{ $model->firstItem() }}</span> - <span class="badge badge-light">{{ $model->lastItem() }}</span> / <span class="badge badge-light">{{ $model->total() }}</span> elem
    </div>
    <div class="col-md-8 justify-content-end text-center">
        {{ $model->links('admin::partials._custom-pagination') }}
    </div>
    <div class="col-md-2">
        <div class="float-right">
            <select class="form-control" wire:model="perPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="300">300</option>
            </select>
        </div>
    </div>
</div>
