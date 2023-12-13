<div class="dropzone" wire:sortable="updateImageOrder" id="{{ $fieldName }}" x-data="{ files: null }"
     x-on:drag.prevent=""
     x-on:dragstart.prevent=""
     x-on:dragend.prevent=""
     x-on:dragover.prevent="$el.classList.add('is-dragover');"
     x-on:dragenter.prevent=""
     x-on:dragleave.prevent="$el.classList.remove('is-dragover');"
     x-on:drop.prevent="$el.classList.remove('is-dragover'); files = [...$event.dataTransfer.files]
        files.forEach((file) => {
            @this.upload('images', file, (uploadedFilename) => {
            /*Success callback.*/
            }, (err) => {console.log(err)}, (event) => {
                /* Progress callback.
                event.detail.progress contains a number between 1 and 100 as the upload progresses. */})
            })
        "
>
    @if ($images)
        @foreach($images as $key => $image)
            <div class="dz-image-preview" wire:sortable.author="{{ $image->getClientOriginalName() }}" wire:key="image-{{ $key }}" draggable="true">
                <div class="dz-preview">
                    <div class="handler-container" wire:sortable.handle.defer>
                        <span class="handle"><i class="icon-move-alt1"></i></span>
                    </div>
                    <div class="dz-image">
                        <img src="{{ $image->temporaryUrl() }}">
                    </div>
                    <div class="dz-details">
                        <div class="dz-filename"><span>{{ $image->getClientOriginalName() }}</span></div>
                        <div class="dz-size"><span><strong>{{ HumanReadable::bytesToHuman($image->getSize()) }}</strong></span></div>
                    </div>
                    <div class="dz-remove" wire:click="remove('{{ $key }}')">
                        <span><i class="icon-x"></i></span>
                    </div>
                </div>
                <div class="dz-progress">
                    <span class="dz-upload"></span>
                </div>
                <div class="dz-input-fields">
                    <input type="hidden" name="{{ $fieldName }}[]" value="{{ $image->getClientOriginalName() }}">
                </div>
            </div>
        @endforeach
    @endif
    <div class="dz-message">
        <label class="col-form-label">
            <i class="icon-add mr-3"></i>Húzd ide a fájlokat vagy KATTINTS IDE a feltöltéshez! | PNG, JPG, GIF
            <input type="file" wire:model.defer="images" accept="image/*" multiple
                   class="position-absolute top-0 bottom-0 left-0 right-0 z-50 m-0 p-0 w-100 h-100 outline-none invisible"
                   data-fouc>
        </label>
    </div>
    @if ($errors->any())
        <div style="order:2;text-align: center">
            <span class="text-danger" x-on:onload="console.log('error')">{{ $errors->all()[0] }}</span>
        </div>
    @endif
</div>
