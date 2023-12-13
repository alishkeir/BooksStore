<div class="card">
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="actual">
                <div><b>AFFILIATE PARTNEREK</b></div>
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
                                    Adószám
                                </a>
                            </th>
                            <th>
                                <a role="button" class="text-default">
                                    Egyedi affiliate kód
                                </a>
                            </th>
                            <th>
                                <a role="button" class="text-default">
                                    Aktuális affiliate egyenleg
                                </a>
                            </th>
                            <th>
                                <a role="button" class="text-default">
                                    Kifizetett jóváírások
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Partner neve</th>
                            <th>Adószám</th>
                            <th>Egyedi affiliate kód</th>
                            <th>Aktuális affiliate egyenleg</th>
                            <th>Kifizetett jóváírások</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($model as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->affiliate->vat }}</td>
                                <td>{{ $customer->affiliate->code }}</td>
                                <td>{{ $customer->balance }}</td>
                                <td>{{ $customer->totalRedeems }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @include('admin::partials._pagination')
            </div>
        </div>
    </div>
</div>
