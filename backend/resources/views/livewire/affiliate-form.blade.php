<div>
    <div class="card">
        <div class="card-header">
            @if ($isAuthor)
                <div class="alert alert-info show p-1" role="alert">
                    <span>Ez egy szerző.</span>
                </div>
            @endif
            <div class="col-12">
                <div class="form-check form-check-inline form-check-right">
                    <label class="form-check-label">Affiliate partner
                        <input type="hidden" name="affiliate_status" value="0">
                        <input id='affiliate_checkbox' type="checkbox" name="affiliate_status" value="1" class="form-check-input"
                        @if ($model?->status ?? false) checked="" @endif
                        @if (!Auth::user()->hasRole('skvadmin') ?? false) disabled="" @endif
                        >
                    </label>
                </div>
            </div>
        </div>
        @if (Auth::user()->hasRole('skvadmin'))
            <div class="card-body">
                <div id='affiliate_section' class="col-12 @if(!$model?->status ?? false) d-none @endif">
                    <label class="d-block font-weight-semibold">Számlázási adatok</label>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Számlázási név (affiliate):</label>
                        <div class="col-lg-10">
                            <input type="text" name="affiliate_name" value="{{ $model->name ?? old('affiliate_name') }}" class="form-control pl-2 @error('affiliate_name') border-danger @enderror">
                            @error('affiliate_name')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Számlázási ország (affiliate):</label>
                        <div class="col-lg-10">
                            <input type="text" name="affiliate_country" value="{{ $model->country ?? old('affiliate_country') }}" class="form-control pl-2 @error('affiliate_country') border-danger @enderror">
                            @error('affiliate_country')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Számlázási irányítószám (affiliate):</label>
                        <div class="col-lg-10">
                            <input type="text" name="affiliate_zip" value="{{ $model->zip ?? old('affiliate_zip') }}" class="form-control pl-2 @error('affiliate_zip') border-danger @enderror">
                            @error('affiliate_zip')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Számlázási város (affiliate):</label>
                        <div class="col-lg-10">
                            <input type="text" name="affiliate_city" value="{{ $model->city ?? old('affiliate_city') }}" class="form-control pl-2 @error('affiliate_city') border-danger @enderror">
                            @error('affiliate_city')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Számlázási cím (affiliate):</label>
                        <div class="col-lg-10">
                            <input type="text" name="affiliate_address" value="{{ $model->address ?? old('affiliate_address') }}" class="form-control pl-2 @error('affiliate_address') border-danger @enderror">
                            @error('affiliate_address')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Adószám (affiliate):</label>
                        <div class="col-lg-10">
                            <input type="text" name="affiliate_vat" value="{{ $model->vat ?? old('affiliate_vat') }}" class="form-control pl-2 @error('affiliate_vat') border-danger @enderror">
                            @error('affiliate_vat')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-2">Egyedi affiliate kód:</label>
                        <div class="col-lg-10">
                            <input type="text" name="affiliate_code" value="{{ $model->code ?? old('affiliate_code') }}" class="form-control pl-2
                                @error('affiliate_code') border-danger @enderror"
                                @if ($model?->code) disabled="" @endif
                            >
                            @error('affiliate_code')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="d-block font-weight-semibold">Igényelt jóváírások:</label>
                        <div class="d-flex text-center">
                            @forelse ($affiliateRedeems as $redeem)
                                @if ($redeem->redeem_file_url)
                                    <a class="mx-2" href="{{$redeem->redeem_file_url}}" target="_blank">
                                        <img class="mb-1" style="max-width: 70px" src="{{asset('pdf_attachment.png')}}" alt="">
                                        <div>{{$redeem->redeem_file_name}}</div>
                                    </a>
                                @endif
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
                <script>
                    const affiliateSection = document.getElementById('affiliate_section');
                    document.getElementById('affiliate_checkbox').addEventListener('change', function(event){
                        if (event.target.checked){
                            affiliateSection.classList.remove('d-none');
                        } else {
                            affiliateSection.classList.add('d-none');
                        }
                    })
                </script>
            </div>
        @endif
    </div>
</div>
