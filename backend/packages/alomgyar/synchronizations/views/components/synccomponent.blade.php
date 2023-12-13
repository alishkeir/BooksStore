<div>
    <p>
        1.)
    <a wire:click="migrateAuthor" class="btn btn-info text-white ml-2" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Szerző migráció</a>
    </p>
    <p>
        2.)
    <a wire:click="migrateCategory" class="btn btn-info text-white ml-2" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Kategória migráció</a>
    </p>
    <p>
        3.)
    <a wire:click="migrateProducts" class="btn btn-info text-white ml-2" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Termék migráció</a>
    <a wire:click="fixProducts" class="btn btn-info text-white ml-2" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Termék pre</a>

    <a wire:click="migratePublishers" class="btn btn-info text-white" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Kiadó migráció </a>

    <a wire:click="migrateProductMeta" class="btn btn-info text-white ml-2" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Meta migráció</a>
    <a wire:click="migrateSubcatToProduct" class="btn btn-info text-white" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Kateg-Prod </a>
    <a wire:click="migrateProductImage" class="btn btn-info text-white ml-2" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Termék kép migráció</a>
    <a wire:click="migrateProductImage2" class="btn btn-info text-white ml-2" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Termék kép migráció2</a>

    </p>
    <p>
       4.)
       <a wire:click="migrateUsers" class="btn btn-info text-white ml-2" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Ügyfél migráció</a>
       <a wire:click="migrateSocial" class="btn btn-info text-white ml-2" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Social migráció</a>

    </p>


    <p>
    6.)

    <a wire:click="migrateOrders" class="btn btn-info text-white ml-2" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Megrendelések migráció</a>
    <a wire:click="migrateEbookOrders" class="btn btn-info text-white ml-2" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Megrendelések EBOOK migráció</a>
    <a wire:click="migrateOaddress" class="btn btn-info text-white ml-2"  onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i>Megrendelés cím migráció</a>
    <a wire:click="migrateOrdersCustomers" class="btn btn-info text-white ml-2"  onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i>Megrendelés - Vevő kapcsolat</a>
</p>


    <a wire:click="runProductSync" class="btn btn-danger text-white" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Szinkronizáció indítása</a>
    <a wire:click="downloadXml" class="btn btn-danger text-white" onClick="$(this).find('i').addClass('icon-spinner4 spinner')"><i></i> Book24 XML frissítése</a>


    <br><br>

    @if($running ?? false)
    <div class="card card-body border-top-1 border-top-primary">
        <div class="text-center">
            <h6 class="mb-0 font-weight-semibold">Migráció</h6>
            <p class="mb-3 text-muted">{{$page * $take}}/{{$all*$take}}</p>
        </div>

        <div class="progress mb-3" style="height: 1.375rem;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-dark" style="width: {{ $page / ($all / 100) }}%">
                <span class="sr-only">{{ $page / ($all / 100) }}% Complete</span>
            </div>
        </div>
    </div>
    @endif
    <br>
    @foreach ($resp ?? [] as $message)
        {{$message}}<br>
    @endforeach

</div>
@section('js')

<script>
    window.addEventListener('continueOrderAddress', event => { Livewire.emit('migrateOaddress'); })
    window.addEventListener('continue', event => { Livewire.emit('migrateProducts'); })
    window.addEventListener('continueImage', event => { Livewire.emit('migrateProductImage'); })
    window.addEventListener('continueImage2', event => { Livewire.emit('migrateProductImage2'); })
    window.addEventListener('continueOrder', event => { Livewire.emit('migrateOrders'); })
    window.addEventListener('continueEbookOrder', event => { Livewire.emit('migrateEbookOrders'); })
    window.addEventListener('continueSubcatToProduct', event => { Livewire.emit('migrateSubcatToProduct'); })
    window.addEventListener('continueAuthor', event => { Livewire.emit('migrateAuthor'); })
    window.addEventListener('continueCategory', event => { Livewire.emit('migrateCategory'); })
    window.addEventListener('continueMeta', event => { Livewire.emit('migrateProductMeta'); })
    window.addEventListener('continueUser', event => { Livewire.emit('migrateUsers'); })
    window.addEventListener('continueSocial', event => { Livewire.emit('migrateSocial'); })
    window.addEventListener('continueOrdersCustomers', event => { Livewire.emit('migrateOrdersCustomers'); })
    window.addEventListener('continueXXX', event => { Livewire.emit('fixProducts'); })
</script>

@endsection
