<div class="d-md-flex align-items-md-start">
    @section('pageTitle')
        Jogtulajdonos fogyásjelentés
    @endsection

    @section('header')
        @include('admin::layouts.header', ['title' => 'Jogtulajdonos fogyásjelentés', 'subtitle' => ''])
    @endsection
    <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-left border-0 shadow-0 sidebar-expand-md"
        style="width:13rem">
        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- Filter -->
            <div class="card border-top-1 border-top-info">
                <div class="card-header bg-transparent header-elements-inline">
                    <span class="text-uppercase font-size-sm font-weight-semibold">Szűrők</span>
                </div>

                <div class="card-body">
                    <div wire:ignore class="form-group form-group-feedback form-group-feedback-left">
                        <select class="form-control legalOwnerId" wire:model="legalOwnerId">
                            <option value="0">Válassz jogtulajdonost...</option>
                            @foreach ($legalOwners as $lo)
                                <option value="{{ $lo->id }}">
                                    {{ $lo->title }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-control-feedback">
                            <i class="icon-database-check text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /sidebar content -->
    </div>
    @if ($legalOwnerId)
        <div class="flex-fill overflow-auto">
            <div class="card">
                <div class="card-body p-3">
                    <h3 class="text-center p-3">Fogyásjelentés - Publish and More Kft (álomgyár könyvesbolt)</h3>
                    <table class="table">
                        <tr>
                            <td><span class="font-weight-semibold">Időszak</span></td>
                            <td class="text-right">{{ $period }}</td>
                            <td>&nbsp;
                            </td>
                            <td colspan="2" class="text-center"><span class="font-weight-semibold">Számla</span></td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-semibold">Partner</span></td>
                            <td class="text-right">{{ $details['legal_owner_name'] }}</td>
                            <td>&nbsp;</td>
                            <td colspan="2" class="text-center">&nbsp;</td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-semibold">Összes értékesített mennyiség</span></td>
                            <td class="text-right">{{ $model->sum('total_sales') }}</td>
                            <td>&nbsp;</td>
                            <td>Email</td>
                            <td>penzugy@alomgyar.hu</td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-semibold">Összesen nettó számlázandó</span></td>
                            <td class="text-right">@huf($model->sum('total_amount'))</td>
                            <td>&nbsp;</td>
                            <td>Posta</td>
                            <td>1137 Budapest, Pozsonyi út 10.</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="actual">

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Könyv címe</th>
                                        <th class="text-center">ISBN</th>
                                        <th class="text-center">Értékesített mennyiség</th>
                                        <th class="text-center">Számlázandó (nettó) egységár</th>
                                        <th class="text-center">Összesen nettó számlázandó</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Könyv címe</th>
                                        <th class="text-center">ISBN</th>
                                        <th class="text-center">Értékesített mennyiség</th>
                                        <th class="text-center">Számlázandó (nettó) egységár</th>
                                        <th class="text-center">Összesen nettó számlázandó</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($model as $item)
                                        <tr>
                                            <td>{{ $item['product_title'] }}</td>
                                            <td class="text-center">{{ $item['isbn'] }} / {{ $item['product_id'] }}</td>
                                            <td class="text-center">{{ $item['total_sales'] }}</td>
                                            <td class="text-right">@huf($item['commission'])</td>
                                            <td class="text-right">@huf($item['total_amount'])</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($legalOwnerId && empty($model))
        <div class="flex-fill overflow-auto">
            <div class="card">
                <div class="card-body p-3">
                    <div class="alert bg-info text-white alert-styled-left">
                        <span class="font-weight-semibold">Ehhez a jogtulajdonoshoz nem létezik fogyásjelentés!</span>
                        Kérlek válassz jogtulajdonost a bal oldali listából.
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="flex-fill overflow-auto">
            <div class="card">
                <div class="card-body p-3">
                    <div class="alert bg-info text-white alert-styled-left">
                        <span class="font-weight-semibold">Nincs jogtulajdonos kiválasztva!</span> Kérlek válassz egy
                        jogtulajdonost a bal oldali listából.
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<style>
    table.table td {
        padding-top: 4px;
        padding-bottom: 4px;
    }
</style>
@push('inline-js')
    <script>
        $(document).ready(function() {
            $('.legalOwnerId').select2();
            $('.legalOwnerId').on('change', function(e) {
                @this.set('legalOwnerId', e.target.value);
            });
        });
    </script>
@endpush
