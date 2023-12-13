<form wire:submit.prevent="submit">
    @csrf
    <div class="row flex-fill ">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5>Ajánlás szerkesztése</h5>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-lg-12" wire:ignore>
                            <label class="col-form-label font-weight-bold" style="margin-bottom:20px;">Megvásárolt termék kiválasztása (ISBN, ID vagy NÉV)</label>
                            <select class="form-control select-search" data-fouc id="originalProductId"
                                    data-placeholder="Válassz egyet...">
                                @if($originalProductId)
                                    <option value="{{ $originalProductId }}"
                                            selected="selected">{{ $originalProduct->title }}
                                        ({{ $originalProduct->isbn }})
                                    </option>@endif
                            </select>
                        </div>
                        @error('originalProductId')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12" wire:ignore>
                            <label class="col-form-label font-weight-bold" style="margin-bottom:20px;">Shop</label>
                            <select class="form-control select-search" data-fouc id="store">
                                <option value="0" @if($storeId === 0) selected @endif>Álomgyár</option>
                                <option value="1" @if($storeId === 1) selected @endif>Olcsókönyvek</option>
                                <option value="2" @if($storeId === 2) selected @endif>Nagyker</option>
                            </select>
                        </div>
                        @error('originalProductId')
                        <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12" wire:ignore>
                            <label class="col-form-label font-weight-bold" style="margin-bottom:20px;">Ajánlott termék kiválasztása (ISBN, ID vagy NÉV)</label>
                            <select class="form-control select-search" data-fouc id="promotedProductId"
                                    data-placeholder="Válassz egyet...">
                                @if($promotedProductId)
                                    <option value="{{ $promotedProductId }}"
                                            selected="selected">{{ $promotedProduct->title }}
                                        ({{ $promotedProduct->isbn }})
                                    </option>@endif
                            </select>
                        </div>
                        @error('promotedProductId')
                            <span class="form-text text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Kiküldés időpontja</label>
                            <input type="datetime-local" wire:model="releaseDate"
                                   class="form-control @error('releaseDate') border-bottom-danger @enderror"
                                   value="{{ $releaseDate }}">
                            @error('releaseDate')
                                <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="col-form-label font-weight-bold">Promóció tárgya</label>
                            <input wire:model.lazy="subject" id="subject" value="subject"
                                   class="form-control @error('subject') border-bottom-danger @enderror">
                            @error('subject') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12"  wire:ignore>
                            <label class="col-form-label font-weight-bold">Promóció szövege</label>
                            <textarea id="message_body" class="form-control @error('message_body') border-danger @enderror">{{ $messageBody }}</textarea>
                            @error('messageBody') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 row">

            <div class="card w-100">
                <div class="card-header bg-transparent header-elements-inline">
                    <h6 class="card-title font-weight-semibold">
                        <i class="icon-help mr-2"></i>
                        Dinamikus változók
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">Ezek a változókkal szabhatod személyre a levelet</p>
                    <table class="">
                        <thead>
                        <tr>
                            <td>Megnevezés</td>
                            <td>Változó</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Keresztnév</td>
                            <td><code>%LAST_NAME%</code></td>
                        </tr>
                        <tr>
                            <td>Eredeti könyv</td>
                            <td><code>%ORIGINAL_BOOK%</code></td>
                        </tr>
                        <tr>
                            <td>Promótált könyv</td>
                            <td><code>%PROMOTED_BOOK%</code></td>
                        </tr>
                        <tr>
                            <td>Promótált könyv leírása</td>
                            <td><code>%PROMOTED_BOOK_DESCRIPTION%</code></td>
                        </tr>
                        <tr>
                            <td>Promótált könyv fotója</td>
                            <td><code>%PROMOTED_BOOK_COVER%</code></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card w-100">
                <div class="card-header bg-transparent header-elements-inline">
                    <h6 class="card-title font-weight-semibold">
                        <i class="icon-info3 mr-2"></i>
                        Hasznos információk
                    </h6>
                </div>
                <div class="card-body">
                    Ez a levél <strong>{{ $customerNum }}</strong> embernek fog elküldődni.
                </div>
            </div>

            <div class="card w-100">
                <div class="card-header bg-transparent header-elements-inline">
                    <h6 class="card-title font-weight-semibold">
                        <i class="icon-atom mr-2"></i>
                        Ajánló tesztelése
                    </h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="toEmail" class="form-label">Email cím</label>
                        <input wire:model="toEmail" id="toEmail"
                               placeholder="Email cím ahová a teszt üzenet érkezik"
                               class="form-control">
                        @error('toEmail') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary btn-sm" wire:click.prevent="sendTestEmail">Teszt
                            Email
                            küldése
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <ul class="fab-menu fab-menu-fixed fab-menu-bottom-right">
                <li>
                    <button type="submit"
                            class="fab-menu-btn btn btn-primary btn-float rounded-round btn-icon legitRipple"
                            title="{{ __('messages.save') }}">
                        <i class="fab-icon-open icon-paperplane"></i>
                    </button>
                </li>
            </ul>
        </div>
</form>

@push('inline-js')
    <script>
        $(document).ready(function () {
            $('#originalProductId').on('change', function (e) {
                var data = $(this).select2("val");
            @this.set('originalProductId', data);
            });

            $('#promotedProductId').on('change', function (e) {
                var data = $(this).select2("val");
            @this.set('promotedProductId', data);
            });

            $('#store').on('change', function (e) {
                var data = $(this).select2("val");
                @this.set('storeId', data);
            });
        });

        tinymce.init({
            selector: '#message_body',
            plugins: 'code table wordcount lists advlist',
            toolbar: 'undo redo styleselect bold italic alignleft aligncenter alignright bullist numlist outdent indent code',
            menubar: false,
            height: 400,
            setup: function (editor) {
                editor.on('change', function (e) {
                    let content = tinymce.activeEditor.getContent();
                    tinymce.activeEditor.targetElm.value = content;
                    @this.set('messageBody', content);
                });
            }
        });
    </script>
@endpush
