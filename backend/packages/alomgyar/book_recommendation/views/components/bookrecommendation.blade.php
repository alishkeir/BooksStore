<div>
    <style>
        .grid-recommendation {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;

            width: 100%;

            padding: 0 10rem;
        }
    </style>
    <div class="d-md-flex align-items-md-start">

        <div class="flex-fill">

            <div>
                @if (strlen($topMessage) > 0)
                    <div class="alert alert-success">
                        {{ $topMessage }}
                    </div>
                @endif
            </div>

            <div class="card">
                <form class="d-flex flex-column h-100 text-center justify-content-center" style="align-items: center"
                    wire:submit.prevent="save">
                    @csrf
                    <div class="w-50 mt-2 mb-2 text-center">
                        <input wire:model="isbn" name="isbn" type="number" min="1" class="form-control"
                            placeholder="ISBN">
                        <span class="helper-teyt">Az ISBN-nek 13 karakternek kell lennie, még {{ 13 - strlen($isbn) }}
                            szükséges </span>

                    </div>

                    @if (isset($isbn) && strlen($isbn) == 13)
                        @if ($book)
                            <div class="w-100 mt-2">
                                <h2 style="color: darkred">Ez a könyv már megtalálható az oldalon</h2>
                            </div>
                        @else
                            <div>
                                Még nem található meg
                            </div>

                            <div class="grid-recommendation">
                                <div>
                                    <h1 class="mt-4">Típus*</h1>
                                    <select class="form-control w-100" required name="type"
                                        wire:model.debounce.500ms="type">
                                        <option value="{{ Alomgyar\Products\Product::BOOK }}">Papír könyv</option>
                                        <option value="{{ Alomgyar\Products\Product::EBOOK }}">e-book</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div>
                                    <h1 class="mt-4">Szerzők*</h1>
                                    <label>
                                        <input wire:model.debounce.500ms="newAuthor" type="checkbox" id="setNewAuthor">
                                        Új szerző</label>
                                    @if ($newAuthor)
                                        <div class="w-100">
                                            <input type="text" wire:model.debounce.500ms="newAuthorName"
                                                class="form-control" placeholder="Szerző neve" required>

                                        </div>
                                    @endif
                                    @if (!$newAuthor)
                                        <div class="w-100">
                                            <ul class="media-list author" wire:ignore>
                                                <div>
                                                    <li class="media">
                                                        <div class="mr-3 align-self-center">
                                                            <i class="icon-quill4 text-warning-300 top-0"></i>
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="font-weight-semibold">
                                                                <select
                                                                    class="form-control author newselect author-search"
                                                                    required data-fouc name="author[]"
                                                                    wire:model.live="authors" multiple>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </div>
                                            </ul>
                                        </div>
                                        @error('authors')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    @endif
                                </div>

                                <div>
                                    <h1 class="mt-4">Könyv címe*</h1>
                                    <div class="w-100">
                                        <input type="text" minlength="1" required class="form-control"
                                            wire:model.debounce.500ms="title" placeholder="Könyv címe">
                                    </div>
                                </div>

                                <div>
                                    <h1 class="mt-4">Státusz*</h1>
                                    <select class="form-control w-100" required wire:model.debounce.500ms="book_state">
                                        <option value="{{ Alomgyar\Products\Product::STATE_NORMAL }}">Normál</option>
                                        <option value="{{ Alomgyar\Products\Product::STATE_PRE }}">Előjegyezhető
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <h1 class="mt-4">Várható megjelenés</h1>
                                    <div class="w-100">
                                        <input type="date" wire:model.debounce.500ms="published_at" id="published_at"
                                            class="col-md-7 form-control text-right" placeholder="Várható megjelenés"
                                            min="{{ date('Y-m-d') }}" @if ($book_state == Alomgyar\Products\Product::STATE_PRE) required @endif>
                                    </div>
                                    @error('published_at')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div>
                                    <h1 class="mt-4">Fülszöveg*</h1>
                                    <div class="w-100">
                                        <textarea wire:model.debounce.500ms="description" id="description" required
                                            class="summernote form-control @error('description') border-danger @enderror"></textarea>
                                    </div>
                                </div>

                                <div>
                                    <h1 class="mt-4">Kiadó*</h1>
                                    <div class="w-100">
                                        <input wire:model.debounce.500ms="newPublisher" type="checkbox"
                                            id="setNewPublisher">
                                        <label>
                                            Új kiadó</label>
                                        @if ($newPublisher)
                                            <input type="text" wire:model.debounce.500ms="newPublisherName"
                                                class="form-control" placeholder="Kiadó neve">
                                            <input type="text" wire:model.debounce.500ms="newPublisherEmail"
                                                class="form-control" placeholder="Kapcsolattartó email cím">
                                        @else
                                            {{-- <select name="publisher_id" class="form-control" required>
                                                @foreach ($publishers as $item)
                                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                                @endforeach
                                            </select> --}}
                                            <div class="w-100">
                                                <ul class="media-list publisherId" wire:ignore>
                                                    <div>
                                                        <li class="media">
                                                            <div class="mr-3 align-self-center">
                                                                <i class="icon-quill4 text-warning-300 top-0"></i>
                                                            </div>
                                                            <div class="media-body">
                                                                <div class="font-weight-semibold">
                                                                    <select
                                                                        class="form-control publisherId newselect publisher-search"
                                                                        required data-fouc name="publisher_id"
                                                                        wire:model.live="publisherId">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </div>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <h1 class="mt-4">Megjelenés éve*</h1>
                                    <div class="w-100">
                                        <input type="number" min="0" wire:model.debounce.500ms="release_year"
                                            class="form-control" required placeholder="Megjelenés éve">
                                    </div>
                                </div>

                                <div>
                                    <h1 class="mt-4">Nyelv*</h1>
                                    <div class="w-100">
                                        <input wire:model.debounce.500ms="language" type="text" required
                                            id="language" class="form-control "
                                            placeholder="magyar, angol, magyar-angol, koreai">
                                    </div>
                                </div>

                                <div>
                                    <h1 class="mt-4">Kötésmód*</h1>
                                    <div class="w-100">
                                        <input wire:model.debounce.500ms="book_binding_method" type="text" required
                                            id="book_binding_method" class="form-control "
                                            placeholder="puha, kartonált, füles, írkafűzött, lapozó">
                                    </div>
                                </div>

                                <div>
                                    <h1 class="mt-4">Oldalszám*</h1>
                                    <div class="w-100">
                                        <input type="number" min="0"
                                            wire:model.debounce.500ms="number_of_pages" class="form-control" required
                                            placeholder="Oldalszám">
                                    </div>
                                </div>

                                <div>
                                    <h1 class="mt-4">Borító Ár*</h1>
                                    <div class="w-100">
                                        <input type="number" min="0" wire:model.debounce.500ms="price"
                                            class="form-control" required placeholder="Ár">
                                    </div>
                                </div>

                                <div>
                                    <h1 class="mt-4">Áfa*</h1>
                                    <select class="form-control w-100" wire:model.debounce.500ms="tax_rate">
                                        <option value="5">5%</option>
                                        <option value="27">27%</option>
                                    </select>
                                </div>


                                <div class="w-100">
                                    <h1 class="mt-4">Kategória</h1>
                                    <ul class="media-list categories" wire:ignore>
                                        <div>
                                            <li class="media">
                                                <div class="mr-3 align-self-center">
                                                    <i class="icon-quill4 text-warning-300 top-0"></i>
                                                </div>
                                                <div class="media-body">
                                                    <div class="font-weight-semibold">
                                                        <select
                                                            class="form-control categories newselect category-search"
                                                            required data-fouc name="categories[]" multiple
                                                            wire:model.live="categories">
                                                        </select>
                                                    </div>
                                                </div>
                                            </li>
                                        </div>
                                    </ul>
                                </div>
                                {{-- <div>
                                    <h1 class="mt-4">Kategória</h1>
                                    <div class="w-100">
                                        <ul class="media-list subcategory">
                                        </ul>

                                        <a href="javascript:" title="Alkategória hozzáadása"
                                            onClick="$('.media-list.subcategory').append($('#li-tpl').html()); $('.subcategory .newselect').select2(); $('.subcategory .newselect').removeClass('newselect');"
                                            class="btn btn-outline bg-success btn-icon text-success border-success border-2 rounded-round legitRipple mt-3">
                                            <i class="icon-plus3"></i></a>
                                    </div>
                                </div> --}}

                                <div>
                                    <h1 class="mt-4">Borító kép</h1>
                                    <div wire:ignore>
                                        <div class="dropzone" data-type="products" id="dropzone_image"
                                            data-url="{{ url(route('fileupload')) }}" style="min-height:200px;">
                                            <div class="dz-message" data-dz-message
                                                style="position: absolute; top: 0; text-align: center; left: 0; bottom: 0; color: #aaa; width: 100%; display: flex; align-items: flex-end; justify-content: center;">
                                                <span>Húzd ide a képet, vagy kattints a feltöltéshez</span>
                                            </div>
                                            <img width="100%" class="preview" style="width:100%;" />
                                        </div>
                                        <span id="invalid-feedback-file-upload" class="" role="alert"
                                            style="display: inline;"></span>

                                        <input type="text" class="form-control coverInput" hidden name="cover"
                                            id="cover" value="{{ old('cover') ?? ($model->cover ?? '') }}">
                                        <p>Ajánlott méret: 640 × 970 px</p>
                                    </div>
                                    <script>
                                        $("div.dropzone").each(function() {
                                            var element = $(this);
                                            $(this).dropzone({
                                                paramName: "file",
                                                url: $(this).data('url'),
                                                acceptedFiles: 'image/jpeg, image/png',
                                                maxFilesize: 10,
                                                thumbnailWidth: 350,
                                                thumbnailHeight: 350,
                                                uploadMultiple: false,
                                                previewTemplate: document.querySelector("#tpl").innerHTML,
                                                dictInvalidFileType: 'Csak jpg vagy png képet tölthet fel',
                                                dictFileTooBig: 'A kép mérete nem lehet több, mint 10 Mb',
                                                params: {
                                                    _token: $('input[name="_token"]').val(),
                                                    type: element.data('type')
                                                },
                                                thumbnail: function(file, dataURL) {
                                                    element.find('img.preview').data('preview', dataURL);
                                                },
                                                error: function(file, response) {
                                                    $('.invalid-feedback').remove();
                                                    $('#invalid-feedback-file-upload').html(
                                                        '<strong>' + response.message + '</strong>');
                                                },
                                                sending: function() {
                                                    // loading($("div#entry_image_con"));
                                                },
                                                uploadprogress: function() {
                                                    //  $("#entry_image_con .fa-spinner").show();
                                                },
                                                success: function(file, response) {
                                                    // loadfinished($("div#entry_image_con"));
                                                    element.next('input').val(response.url);
                                                    @this.set('cover', response.url)
                                                    element.find('img.preview').attr('src', element.find('img.preview').data(
                                                        'preview'));
                                                    $('#invalid-feedback-file-upload').html(
                                                        '<strong>' + response.message + '</strong>');

                                                }
                                            });
                                        });
                                    </script>
                                </div>
                            </div>


                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <button type="submit" class="form-control btn btn-primary mt-5 mb-5 w-25">Mentés</button>
                        @endif
                    @else
                        <h1>ISBN megadása kötelező</h1>
                    @endif
                </form>
            </div>
        </div>

        {{-- <div style="display:none;" id="li-tpl-author">
            <li class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-quill4 text-warning-300 top-0"></i>
                </div>
                <div class="media-body">
                    <div class="font-weight-semibold">
                        <select class="form-control author newselect" data-fouc name="author[]">
                        </select>
                    </div>
                </div>
                <div class="ml-2 align-self-center">
                    <a href="javascript:" onclick="$(this).parent().parent().remove()"
                        class="icon icon-trash text-dark"></a>
                </div>
            </li>
        </div>

        <div style="display:none;" id="li-tpl">
            <li class="media">
                <div class="mr-3 align-self-center">
                    <i class="icon-price-tags text-warning-300 top-0"></i>
                </div>
                <div class="media-body">
                    <div class="font-weight-semibold">
                        <select name="category[]"
                            class="form-control newselect @error('category') border-danger @enderror">
                            @foreach ($categories ?? [] as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </li>
        </div> --}}
    </div>
</div>


@push('inline-js')
    <script>
        function registerAuthorSelect() {
            $('.author-search').select2({
                ajax: {
                    url: '{{ route('recommendation.authorSearch') }}',
                    dataType: 'json',
                    data: function(params) {
                        let query = {
                            q: params.term,
                            page: params.page || 1
                        }

                        return query;
                    },
                    delay: 250, // wait 250 milliseconds before triggering the request
                    cache: true
                },
                placeholder: 'Összes szerző',
                allowClear: true
            });
            $('.author-search').on('change', function(e) {
                var data = $('.author-search').select2("val");
                @this.set('authors', data);
            });
        }

        function registerPublisherSelect() {
            $('.publisher-search').select2({
                ajax: {
                    url: '{{ route('recommendation.publisherSearch') }}',
                    dataType: 'json',
                    data: function(params) {
                        let query = {
                            q: params.term,
                            page: params.page || 1
                        }

                        return query;
                    },
                    delay: 250, // wait 250 milliseconds before triggering the request
                    cache: true
                },
                placeholder: 'Összes kiadó',
                allowClear: true
            });
            $('.publisher-search').on('change', function(e) {
                var data = $('.publisher-search').select2("val");
                @this.set('publisher_id', data);
            });
        }

        function registerCategorySelect() {
            $('.category-search').select2({
                ajax: {
                    url: '{{ route('recommendation.categorySearch') }}',
                    dataType: 'json',
                    data: function(params) {
                        let query = {
                            q: params.term,
                            page: params.page || 1
                        }

                        return query;
                    },
                    delay: 250, // wait 250 milliseconds before triggering the request
                    cache: true
                },
                placeholder: 'Összes kategória',
                allowClear: true
            });
            $('.category-search').on('change', function(e) {
                var data = $('.category-search').select2("val");
                @this.set('categories', data);
            });
        }

        document.addEventListener('registerSelect2', () => {
            registerAuthorSelect();
            registerPublisherSelect();
            registerCategorySelect();
        });
    </script>
@endpush
