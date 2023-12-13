<div class="dropzone" id="{{ $fieldName }}" x-data="{ files: null }"
     x-on:drag.prevent=""
     x-on:dragstart.prevent=""
     x-on:dragend.prevent=""
     x-on:dragover.prevent="$el.classList.add('is-dragover');"
     x-on:dragenter.prevent=""
     x-on:dragleave.prevent="$el.classList.remove('is-dragover');"
     x-on:drop.prevent="$el.classList.remove('is-dragover');@this.upload('image', $event.dataTransfer.files[0], (uploadedFilename) => {
            /*Success callback.*/
        }, (err) => {console.log(err)}, (event) => {
            /* Progress callback.
            event.detail.progress contains a number between 1 and 100 as the upload progresses. */})"
>
    @if ($image && empty($errors->first('image')))
        <div class="dz-image-preview">
            <div class="dz-preview">
                <div class="dz-image">
                    <img src="{{ $image->temporaryUrl() }}">
                </div>
                <div class="dz-details">
                    <div class="dz-filename"><span>{{ $image->getClientOriginalName() }}</span></div>
                    <div class="dz-size">
                        <span><strong>{{ HumanReadable::bytesToHuman($image->getSize()) }}</strong></span></div>
                </div>
                <div class="dz-remove" wire:click="remove(0)">
                    <span><i class="icon-x"></i></span>
                </div>
            </div>
            <div class="dz-progress">
                <span class="dz-upload"></span>
            </div>
            <div class="dz-input-fields">
                <input type="hidden" name="{{ $fieldName }}" value="{{ $storedImage }}">
            </div>
        </div>
    @else
        <div class="dz-message">
            <label class="col-form-label">
                <i class="icon-add mr-3"></i>Húzd ide a fájlt vagy KATTINTS IDE a feltöltéshez! | PNG, JPG, GIF
                <input type="file" wire:model="image" accept="image/*"
                       class="position-absolute top-0 bottom-0 left-0 right-0 z-50 m-0 p-0 w-100 h-100 outline-none invisible"
                       data-fouc
                >
            </label>
        </div>
    @endif
    @error('image')
    <div style="order:2;text-align: center">
        <span class="text-danger" x-on:onload="alert('error')">{{ $message }}</span>
    </div>
    @enderror

</div>
