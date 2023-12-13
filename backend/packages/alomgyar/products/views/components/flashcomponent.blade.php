<div>
    @if ($done == true)
        <div class="card bg-success-400 text-white text-center p-3"
            style="background-image: url(http://demo.interface.club/limitless/assets/images/bg.png); background-size: contain;">
            <div>

                <i class="icon-check"></i>
            </div>

            <blockquote class="blockquote mb-0">
                <p>Az árazás sikeresen megtörtént</p>
            </blockquote>
        </div>
    @endif
    <div class="card">
        <div class="card-header">

            <h5>Villám árazó</h5>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-lg-8">
                    <label class="col-form-label font-weight-bold">Milyen kedvezményű könyveket érintsen (%):</label>
                    <input wire:model="source" name="source" type="number"
                        class="form-control @error('source') border-danger @enderror" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-8">
                    <label class="col-form-label font-weight-bold">Új, beállítandó kedvezmény (%):</label>
                    <input wire:model="target" name="target" type="number"
                        class="form-control @error('target') border-danger @enderror" value="">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-8">
                    <label class="col-form-label font-weight-bold">Melyik site árai módosuljanak:</label>
                    <select wire:model="store" class="form-control" name="store">
                        <option>Válassz</option>
                        <option value="alom">Álomgyár</option>
                        <option value="olcso">Olcsókönyvek</option>
                        <option value="nagyker">Nagyker</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-8">
                    <label class="col-form-label font-weight-bold">Mikortól él a VillámAkció:</label>
                    <input wire:model="fromDate" name="fromDate" type="datetime-local"
                        class="form-control @error('fromDate') border-danger @enderror" value="">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-8">
                    <label class="col-form-label font-weight-bold">Meddig él a VillámAkció:</label>
                    <input wire:model="toDate" name="toDate" type="datetime-local"
                        class="form-control @error('toDate') border-danger @enderror" value="">
                </div>
            </div>
            <div class="row mt-4 text-center">
                <div class="col-lg-12">
                    <a wire:click="runFlashPromotion" class="btn btn-success text-white">{{ $count ?? 0 }} db termék
                        villámárazása</a>
                </div>
            </div>


            <div class="form-group row">
                <div class="col-lg-12">
                    <p class="col-form-label font-weight-bold">Élő VillámAkció(k):</p>
                    @if ($dateUpdated)
                        <div class="alert alert-success show p-1" role="alert">
                            <span>Dátum sikeresen frissítve <i class="icon-check"></i></span>
                            <button wire:click="$set('dateUpdated', false)" type="button" class="close px-1">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if ($cantUpdate)
                        <div class="alert alert-danger show p-1" role="alert">
                            <span>{{ $cantUpdateText }}</span>
                            <button wire:click="$set('cantUpdate', false)" type="button" class="close px-1">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @forelse ($currentFlashDeals as $currentFlashDeal)
                        <hr>
                        <div class="row mt-2">
                            <div class="col-md-6">{{ $currentFlashDeal->title }} <i
                                    wire:click="setUpdateId({{ $currentFlashDeal->id }})" data-toggle="modal"
                                    data-target="#toDateModal" class="icon-pencil5 p-1 pb-2"></i></div>
                            <div class="col-md-4">Készítette: {{ $currentFlashDeal->createdBy?->email }} </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <img src="{{ $currentFlashDeal->store_logo_url }}">
                            </div>
                            <div class="col-md-2">
                                <div wire:click="deleteFlashPromotion({{ $currentFlashDeal->id }})"
                                    class="btn btn-sm btn-danger">
                                    Leállítás
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Jelenleg nem fut VillámAkció</p>
                    @endforelse
                </div>
            </div>


        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="toDateModal" tabindex="-1" role="dialog"
        aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Új befejező dátum beállítása</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <input wire:model="newToDate" name="newToDate" type="datetime-local"
                        class="form-control @error('newToDate') border-danger @enderror" value="">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Mégsem</button>
                    <button type="button" wire:click.prevent="updateFlashPromotionToDate()" class="btn bg-primary"
                        data-dismiss="modal">Mentés</button>
                </div>
            </div>
        </div>
    </div>
</div>
