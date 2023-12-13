<div>
    @section('pageTitle')
        Kereskedői fogyásjelentés lista
    @endsection
    @section('header')
        @include('admin::layouts.header', ['title' => 'Kereskedői fogyásjelentés lista', 'subtitle' => '', 'button' => route('consumption_report.merchant-import'), 'buttonText' => 'Új fogyásjelentés feltöltése'])
    @endsection

<div class="card">
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="actual">
                @include('admin::partials._search')
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                <a href="javascript:" role="button" class="text-default">
                                    #
                                </a>
                            </th>
                            <th>
                                    <a wire:click="yo"> Kereskedő</a>
                            </th>
                            <th>
                                <a href="javascript:" role="button" class="text-default">
                                    Db
                                    
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" role="button" class="text-default">
                                    Bruttó össz
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" role="button" class="text-default">
                                    Létrehozva
                                </a>
                            </th>
                            <th>
                                Számla
                            </th>
                            <th class="text-right">
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Kereskedő</th>
                            <th>Db</th>
                            <th>Bruttó össz</th>
                            <th>Létrehozva</th>
                            <th>Számla</th>
                            <th class="text-right">Lehetőségek</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($model as $merchant_report)
                        <tr>
                            <td>{{ $merchant_report->id }}</td>
                            <td>{{ $merchant_report->merchant_name }}</td>
                            <td>{{ $merchant_report->quantity }}</td>
                            <td>{{ $merchant_report->total_amount }}</td>
                            <td>{{ $merchant_report->created_at }}</td>
                            <td>{{$merchant_report->invoice_url}}</td>
                            <td class="text-right" >
                                @if($merchant_report->invoice_url ?? false)
                                <a title="Számla megtekintése" href="{{route('orders.invoice.get', ['id' => $merchant_report->invoice_url])}}"  target="_blank" class="btn-sm bg-info mr-2"><i class="icon icon-file-check"></i> <strong></strong></a>
{{--                                <a title="Számla küldése" wire:click="sendInvoiceToEmail({{$merchant_report->id}})" class="btn-sm bg-success">--}}
{{--                                    <i class="icon icon-paperplane"></i> <strong>{{ $merchant_report->merchant_email }}</strong>--}}
{{--                                </a>--}}
                                @endif
                                <a title="Számla küldése" wire:click="sendInvoiceToEmail({{$merchant_report->id}})" class="btn-sm bg-success">
                                    <i class="icon icon-paperplane"></i> <strong>{{ $merchant_report->merchant_email }}</strong>
                                </a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                @include('admin::partials._pagination')
            </div>
        </div>
    </div>
</div>
</div>