

<div    
@if($store==0) style="color:#e62934;"@endif
@if($store==1) style="color:#fbc72e;"@endif
@if($store==2) style="color:#4971ff;"@endif >
    {{--<a wire:click="calculate(0)" class="btn btn-light legitRipple"><i class="icon-make-group mr-2"></i> Újraszámol</a>--}}
    <label class="mx-3 col-form-label font-weight-bold" >{{  $prices['price_sale'] ?? '' }} / <s>{{  $prices['price_list'] ?? '' }}</s> ({{ $prices['discount_percent']}}%)</label>
</div>