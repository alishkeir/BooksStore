<div class="row">
    <div class="col-md-4 d-inline-flex align-items-center">
        <h4
            data-id="{{$option->id}}"
            data-key="{{$option->key}}"
            data-source={{$option->source}}
            data-name={{$option->name}}
            data-type={{$option->type}}
            data-extra={{$option->extra}}
            >
            <label>{{$option->name}}</label><code>settings('{{$option->key}}')</code>
        </h4>
        <a href="javascript:;" class="edit-settings-name" data-settings={{$option->key}}><i class="icon-pencil4 ml-1 mb-3"></i></a>
        <button class="btn btn-danger btn-sm btn-link list-icons-item text-danger-600 p-0 ml-1 mb-3 trash" data-id={{$option->id}}><i class="icon-trash"></i></button>
    </div>

    <div class="col-md-8">
        @if($option->type == 'text')
        <input type="text" class="form-control" name="data[{{$option->key}}]" value="{{$option->value}}">
        @elseif($option->type == 'textbox')
        <textarea class="form-control" class="form-control" name="data[{{$option->key}}]">{{$option->value}}</textarea>
        @elseif($option->type == 'richtext')
        <textarea class="rich-text-editor" class="form-control" name="data[{{$option->key}}]">{{$option->value}}</textarea>
        @elseif($option->type == 'select')
        <select name="data[{{$option->key}}]" class="form-control">
        @foreach(json_decode($option->extra) as $key => $extra)
        <option value="{{$key}}" @if($key == $option->value) selected="" @endif >{{$extra}}</option>
        @endforeach
        </select>
        @endif

    </div>

</div>
<hr />
@if($option->type == 'richtext')
<script>

</script>
@endif