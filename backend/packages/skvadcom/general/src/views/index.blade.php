@extends('admin::layouts.master')
@section('header')
    @include('admin::layouts.header', ['title' => 'Settings', 'subtitle' => ''])
@endsection
@section('css')
    <style>
        .edit-settings-name, .trash {
            opacity: 0;
        }
        h4:hover ~ .edit-settings-name,
        .edit-settings-name:hover,
        h4:hover ~ .trash,
        .trash:hover {
            opacity: 0.8;
        }
    </style>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jshint/2.9.5/jshint.min.js"></script>

<script>

    $(function(){
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $('body').on({
            submit: function(e){
                e.preventDefault();
                const groupForm = $(this);
                let outString = $(this).find('#setting-group-name').val().replace(/[`~!áéőúűóüöí@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
                let valid = true;
                $('#settings-tab').children().each(function(index, item){
                    if (item.innerText == outString) {
                        valid = false;
                    }
                });
                if (valid) {
                    $('#settings-tab').append(
                            '<li class="nav-item"><a href="#highlight-tab'+($('#settings-tab li').length+1)+'" class="nav-link" data-toggle="tab">'
                            +$(this).find('#setting-group-name').val()
                            +'</a></li>');
                    const newTab = $('#tpl-tab > div').clone();
                    newTab.find('.new-param').attr('data-tab', groupForm.find('#setting-group-name').val());
                    newTab.attr({'data-id': outString, 'id': 'highlight-tab'+($('#settings-tab li').length)}).appendTo('#settings-content');
                    //$('#settings-content').clone('#tpl-tab > div').append('<div class="tab-pane fade" data-id="'+outString+'" id="highlight-tab'+($('#settings-tab li').length)+'">asd</div>')
                    $('#setting-group-name').val('');
                } else {
                    new PNotify({
                        title: 'Ez a csoport név már létezik',
                        icon: 'icon-blocked',
                        type: 'error'
                    });
                }
            }
        }, '#setting-group-form');

        $('body').on({
            click: function(e){
                e.preventDefault();

                const elem = $(this);
                let cloned = $('#tpl-row > form').clone();
                cloned.find('.new-source').val(elem.data('tab'));
                cloned.appendTo(elem.closest('.tab-pane').find('.new-element'));
                let textarea = cloned.find('textarea')[0];
            }
        }, '.new-param');

        $('body').on({
            submit: function(e){
                e.preventDefault();
                const form = $(this);

                if (form.hasClass('update')) {
                    $.post('{{route('updaterow')}}', $(this).serialize(), function(response){
                        new PNotify({
                            title: 'Új beállítás paraméter létrehozva',
                            icon: 'icon-checkmark3',
                            type: 'success'
                        });

                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    })
                } else {
                    $.post('{{route('createrow')}}', $(this).serialize(), function(response){
                        form.closest('.tab-pane').find('.list').append(response);
                        form.remove();
                        new PNotify({
                            title: 'Új beállítás paraméter létrehozva',
                            icon: 'icon-checkmark3',
                            type: 'success'
                        });

                        tinymce.init({
                            selector: '.rich-text-editor'
                        });
                    })
                }
            }
        }, '.new-row-form');

        $('body').on({
            click: function(e){
                e.preventDefault();
                var generals = [];
                $('#settings-content form.settings-form').each(function(){
                    generals.push($(this).serialize());
                })
                $.post('{{route('general.store')}}', {'data': generals}, function(response){
                    response = $.parseJSON(response);
                    if (response.success) {
                         new PNotify({
                            title: 'A beállítások sikeresen elmentve',
                            icon: 'icon-checkmark3',
                            type: 'success'
                        });
                    } else {
                        new PNotify({
                            title: 'Előbb fel kell venni paramétereket és nem lehet üres',
                            icon: 'icon-blocked',
                            type: 'error'
                        });
                    }
                })
                .fail(err => console.log(err))
            }
        }, '#save-generals');

        $('.trash').click(function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            $.ajaxSetup({
                url: "{{route('deleterow')}}",
                global: false,
                type: "DELETE"
            });
            $.ajax({
                data: {id},
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                success: function() {
                    new PNotify({
                        title: 'Paraméter törölve',
                        icon: 'icon-checkmark3',
                        type: 'success'
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 500);
                }
             });
        });

        $('.edit-settings-name').click(function(){
            let sID = $(this).data('settings');
            let editable = $(this).prev();
            // let cloned = $('#tpl-row > form').clone();
            let modal = $('#modal_form_inline');
            modal.find('input[name="id"]').val(editable.data('id'));
            modal.find('input[name="name"]').val(editable.data('name'));
            modal.find('input[name="key"]').val(editable.data('key'));
            modal.find('input[name="source"]').val(editable.data('source'));
            modal.find('select[name="type"]').val(editable.data('type')).attr('selected', true);
            modal.find('textarea[name="extra"]').val(editable.data('extra'));

            modal.modal();
        });

        tinymce.init({
            selector: '.rich-text-editor'
        });

        @if(Session::has('success'))
            new PNotify({
                title: "{{ Session::get('success') }}",
                icon: 'icon-checkmark3',
                type: 'success'
            });
            @php
                Session::forget('success');
            @endphp
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                new PNotify({
                    title: "{{ $error }}",
                    icon: 'icon-blocked',
                    type: 'error'
                });
            @endforeach
        @endif
    })
</script>

@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Beállítás csoportok</h6>
            </div>
            <div class="card-body">
                <ul id="settings-tab" class="nav nav-tabs nav-tabs-highlight">
                    @php $count = 1; @endphp
                    @foreach($content as $key => $tab)
                    <li class="nav-item d-inline-flex">
                        <a href="#highlight-tab-{{$count}}" class="nav-link @if ($count == 1) active @endif" data-toggle="tab">{{$key}}</a>
                    @php $count++; @endphp
                    @endforeach
                </ul>
                <div id="settings-content" class="tab-content">
                    @php $count2 = 1; @endphp
                    @foreach($content as $key => $settings)
                        <div class="tab-pane fade @if ($count2 == 1) active show @endif" data-id="{{$key}}" id="highlight-tab-{{$count2}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="settings-form">
                                        <div class="list">
                                            @foreach($settings as $setting)
                                            {!! $setting !!}
                                            @endforeach
                                        </div>

                                    </form>
                                    <div class="new-element"></div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="" class="btn bg-slate new-param" data-tab="{{$key}}">Új paraméter</a>
                                </div>
                            </div>
                        </div>
                    @php $count2++; @endphp
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header header-elements-inline">
                <h6 class="card-title">Új csoport <small>(új tab)</small></h6>
            </div>
            <div class="card-body">
                <form id="setting-group-form">
                    <div class="form-group row">
                        <label class="col-md-2">Csoport neve</label>
                        <input type="text" name="name" id="setting-group-name" class="form-control col-md-7">
                        <input type="submit" value="Csoport mentése" class="btn btn-outline-primary ml-4">
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <a href="" id="save-generals" class="btn btn-primary float-right">Beállítások mentése <i class="icon-paperplane ml-2"></i></a>
            </div>
        </div>
    </div>

    <div id="templates" style="display: none;">
        <div id="tpl-tab">
            <div class="tab-pane fade" data-id="" id="">
                <div class="row">
                    <div class="col-md-12">
                        <form class="settings-form">
                            <div class="list"></div>
                        </form>
                        <div class="new-element"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href="" class="btn bg-slate new-param">Új paraméter</a>
                    </div>
                </div>
            </div>
        </div>
        <div id="tpl-row">
            <form class="new-row-form">
                <input type="hidden" name="source" class="new-source" value="">
                <div class="row">

                    <div class="col-md-2 form-group">
                        <label class="form-label">Név</label>
                        <input class="form-control" name="name" type="text" placeholder="Ez lesz a paraméter neve">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Egyedi ID</label>
                        <input class="form-control" name="key" type="text" placeholder="Ezzel hivatkozunk a paraméterre">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="form-label">Típus</label>
                        <select class="form-control" name="type">
                            <option value="text">Egyszerű szöveg</option>
                            <option value="textbox">Szövegdoboz</option>
                            <option value="richtext">HTML szerkesztő</option>
                            <option value="select">Lenyíló</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Extra kód</label>
                        <textarea name="extra" style="height: 50px;" class="form-control"></textarea>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-success" value="Mentés">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="alert alert-info bg-white alert-styled-left alert-arrow-left alert-dismissible">
    <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
    <h6 class="alert-heading font-weight-semibold mb-1">Információ</h6>
    <ol><li>Hozz létre egy csoportot</li><li>Kattints az újonnan létrehozott csoportra</li><li>Hozz létre egy új paramétert és mentsd el</li><li>Adj értéket a paraméternek és mentsd el a beállításokat a legalsó gombbal</li></ol>
</div>

<div id="modal_form_inline" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Szerkesztés</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form class="new-row-form update modal-body form-inline justify-content-center" action="{{route('updaterow')}}">
                @method('PUT')
                <input type="hidden" name="source" class="new-source" value="">
                <input type="hidden" name="id" value="">
                <div class="row">

                    <div class="col-md-2 form-group">
                        <label class="form-label">Név</label>
                        <input class="form-control" name="name" type="text" placeholder="Ez lesz a paraméter neve">
                    </div>
                    <div class="col-md-2 form-group">
                        <label class="form-label">Egyedi ID</label>
                        <input class="form-control" name="key" type="text" placeholder="Ezzel hivatkozunk a paraméterre">
                    </div>
                    <div class="col-md-3 form-group">
                        <label class="form-label">Típus</label>
                        <select class="form-control" name="type">
                            <option value="text">Egyszerű szöveg</option>
                            <option value="textbox">Szövegdoboz</option>
                            <option value="richtext">HTML szerkesztő</option>
                            <option value="select">Lenyíló</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Extra kód</label>
                        <textarea name="extra" style="height: 50px;" class="form-control"></textarea>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-success" value="Mentés">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection