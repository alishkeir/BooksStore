<div>
    <div class="row">
        @foreach($model as $section => $templates)
        <div class="col-md-12">
            <h4>{!!$section!!}</h4>
        </div>
        @foreach($templates as $slug=>$template)
            <div class="col-md-4"  >
                <div class="card">

                    <div class="card-body p-3">
                        {{ $template[0]->title ?? $template[1]->title  }}
                        <span class="float-right"><small>{{ $slug }}</small></span>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        
                            @isset($template[1])
                            <a href="{{ route('templates.edit', ['template' => $template[1]]) }}" class="text-blue">
                                <i class="icon icon-gear"></i>
                                <img style="width:60px;" src="/logo-olcsokonyvek.png">
                            </a>
                            @endisset
                            @isset($template[2])
                            <a href="{{ route('templates.edit', ['template' => $template[2]]) }}" class="text-blue">
                                <i class="icon icon-gear"></i>
                                <img style="width:60px;" src="/logo-nagyker.png">
                            </a>
                            @endisset
                            @isset($template[0])
                            <a href="{{ route('templates.edit', ['template' => $template[0]]) }}" class="text-blue">
                                <i class="icon icon-gear"></i>
                                <img style="width:60px;" src="/logo-alomgyar.png">
                            </a>
                            @endisset
                        

                    </div>
                </div>
            </div>
        @endforeach
        @endforeach
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                @include('admin::partials._search')
            </div>
        </div>
    </div>
</div>
