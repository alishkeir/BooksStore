<div class="flex-fill overflow-auto">
<div class="card">
        <div class="card-body p-3">
            <h3 class="text-center p-3">Fogyásjelentés - Publish and More Kft (álomgyár könyvesbolt)</h3>
            <table class="table">
                <tr>
                    {{--                        <td><span class="font-weight-semibold">Időszak</span></td>--}}
                    {{--                        <td class="text-right">{{ now()->locale(config('app.locale'),config('app.fallback_locale'))->format('Y. F') }}</td>--}}
                    {{--                        <td>&nbsp;</td>--}}
                    {{--                        <td colspan="2" class="text-center"><span class="font-weight-semibold">Számla</span></td>--}}
                </tr>
                <tr>
                    <td><span class="font-weight-semibold">Partner</span></td>
                    <td class="text-right">{{ $details['legal_owner_name'] }}</td>
                    <td>&nbsp;</td>
                    <td colspan="2" class="text-center"><span class="font-weight-semibold">Számla</span></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-right"></td>
                    <td>&nbsp;</td>
                    <td>Email</td>
                    <td>penzugy@alomgyar.hu</td>
                </tr>
                <tr>
                    <td><span class="font-weight-semibold">Összes értékesített mennyiség</span></td>
                    <td class="text-right">{{ $model->sum('total_sales') }}</td>
                    <td>&nbsp;</td>
                    <td>Posta</td>
                    <td>1137 Budapest, Pozsonyi út 10.</td>
                </tr>
                <tr>
                    <td><span class="font-weight-semibold">Összesen bruttó számlázandó</span></td>
                    <td class="text-right">@huf($model->sum('total_amount'))</td>
                    <td colspan="3">&nbsp;</td>
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
                            <th class="text-center">Számlázandó (bruttó) egységár</th>
                            <th class="text-center">Lista ár</th>
                            <th class="text-center">Átlagos eladási ár</th>
                            <th class="text-center">Összesen bruttó számlázandó</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($model as $item)
                            <tr>
                                    <td>{{ $item['product_title'] }}</td>
                                    <td class="text-center">{{ $item['isbn'] }}</td>
                                    <td class="text-center">{{ $item['total_sales'] }}</td>
                                    <td class="text-right">@huf($item['commission'])</td>
                                    <td class="text-right">@huf($item['price_list'])</td>
                                    <td class="text-right">@huf($item['avg_price'])</td>
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
