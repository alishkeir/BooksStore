<div class="card">
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="actual">
                <div><b>IGÉNYELT JÓVÁÍRÁSOK - PDF</b></div>
                <hr />
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                <a role="button" class="text-default">
                                    Partner neve
                                </a>
                            </th>
                            <th>
                                <a role="button" class="text-default">
                                    Affiliate kód
                                </a>
                            </th>
                            <th>
                                <a role="button" class="text-default">
                                    Adószám
                                </a>
                            </th>
                            <th>
                                <a role="button" class="text-default">
                                    Igénylés dátuma
                                </a>
                            </th>
                            <th>
                                <a role="button" class="text-default">
                                    Igényelt összeg
                                </a>
                            </th>
                            <th>
                                <a role="button" class="text-default">
                                    Igénylés azonosítója
                                </a>
                            </th>
                            <th>
                                <a role="button" class="text-default">
                                    PDF letöltése
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Partner neve</th>
                            <th>Affiliate kód</th>
                            <th>Adószám</th>
                            <th>Igénylés dátuma</th>
                            <th>Igényelt összeg</th>
                            <th>Igénylés azonosítója</th>
                            <th>PDF letöltése</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($model as $redeem)
                            <tr>
                                <td>{{ $redeem->customer->firstname }}</td>
                                <td>{{ $redeem->customer->affiliate->code }}</td>
                                <td>{{ $redeem->customer->affiliate->vat }}</td>
                                <td>{{ $redeem->created_at }}</td>
                                <td>{{ \App\helpers\HumanReadable::formatHUF($redeem->amount) }}</td>
                                <td>{{ $redeem->redeem_file_name }}</td>
                                <td>
                                    @if ($redeem->redeem_file_url)
                                        <a href="{{ $redeem->redeem_file_url }}" target="_blank">letöltés</a>
                                    @else
                                        <div wire:click="regeneratePdf({{ $redeem->id }})"
                                            class="btn btn-sm btn-primary">
                                            Újra generálás
                                        </div>
                                    @endif
                                </td>
                                <td>

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
