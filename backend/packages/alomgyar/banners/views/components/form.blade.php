<div>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-solid nav-justified border-0">
                <li class="nav-item"><a wire:click="setStoreId('0')" href="#"
                                        class="nav-link legitRipple @if($model->shop_id === 0) active @endif">Álomgyár</a>
                </li>
                <li class="nav-item"><a wire:click="setStoreId('1')" href="#"
                                        class="nav-link legitRipple @if($model->shop_id === 1) active @endif">Olcsókönyvek</a>
                </li>
                <li class="nav-item"><a wire:click="setStoreId('2')" href="#"
                                        class="nav-link legitRipple @if($model->shop_id === 2) active @endif">Nagyker</a>
                </li>
            </ul>

            <div class="row">

                <div class="col-md-8">
                    <h4>Main banner</h4>
                    <p>Kép cseréje:</p>
                    <form wire:submit.prevent="saveHero">

                        <div class="form-group">
                            <input type="file" wire:model="mainBanner" class="form-control"/>
                            <div wire:loading wire:target="mainBanner" class="py-3">Feltöltés...</div>
                            @error('mainBanner') <span class="error">{{ $message }}</span> @enderror

                        </div>

                        <div class="form-group">
                            <label>Cím:</label>
                            <input type="text" wire:model="model.main_banner_title" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>URL:</label>
                            <input type="text" wire:model="model.main_banner_url" class="form-control">
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Mentés</button>
                        </div>
                    </form>
                    <hr class="my-3">
                    <p class="font-weight-bold">Előlnézet: </p>
                    <img src="{{ '/storage/'. $model->main_banner }}" alt="" class="img-fluid">
                </div>

                <div class="col-md-4">
                    <h4>Main hero banner</h4>
                    <form wire:submit.prevent="saveMainHero">
                        <div class="form-group">
                            <label for="file">Kép cseréje:</label>
                            <input type="file" wire:model="mainHeroBanner" class="form-control"/>
                            <div wire:loading wire:target="mainHeroBanner" class="py-3">Feltöltés...</div>
                            @error('mainHeroBanner') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Cím:</label>
                            <input type="text" wire:model="model.main_hero_banner_title" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>URL:</label>
                            <input type="text" wire:model="model.main_hero_banner_url" class="form-control">
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Mentés</button>
                        </div>
                    </form>
                    <hr class="my-3">
                    <p class="font-weight-bold">Előlnézet: </p>
                    <img src="{{ '/storage/'. $model->main_hero_banner }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
