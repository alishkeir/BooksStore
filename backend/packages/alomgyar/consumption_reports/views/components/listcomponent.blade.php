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
                                <a href="javascript:" wire:click.prevent="sortBy('period')" role="button" class="text-default">
                                    Időszak
                                    @include('admin::partials._sort-icons', ['field' => 'period'])
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" role="button" class="text-default">
                                    Termékek száma
                                </a>
                            </th>
                            <th>
                                <a href="javascript:" role="button" class="text-default">
                                    Beszállítók száma
                                </a>
                            </th>
                            <th class="text-center">
                                <a href="" role="button" class="text-default">
                                    Beszállítói jelentések letöltése
                                </a>
                            </th>
                            <th class="text-center">
                                <a href="" role="button" class="text-default">
                                    Szerzői jelentések letöltése
                                </a>
                            </th>
                            <th class="text-center">
                                <a href="" role="button" class="text-default">
                                    Jogtulaj jelentések letöltése
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Időszak</th>
                            <th>Termékek száma</th>
                            <th>Beszállítók száma</th>
                            <th class="text-center">Beszállítói jelentések letöltése</th>
                            <th class="text-center">Szerzői jelentések letöltése</th>
                            <th class="text-center">Jogtulaj jelentések letöltése</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($model as $consumption_report)
                        <tr>
                            <td>{{ $consumption_report->id }}</td>
                            <td>{{ $consumption_report->period }}</td>
                            <td>{{ $consumption_report->number_of_books }}</td>
                            <td>{{ $consumption_report->number_of_suppliers }}</td>
                            <td class="text-center" style="max-width:40px;">
                                <div class="list-icons">
                                    <div class="btn-group ml-2">
                                        <button type="button"
                                                class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple"
                                                data-toggle="dropdown" aria-expanded="false"><i
                                                class="icon-menu7"></i></button>

                                        <div class="dropdown-menu" x-placement="top-start"
                                             style="position: absolute; transform: translate3d(0px, -165px, 0px); top: 0px; left: 0px; will-change: transform;overflow:auto;max-height:500px">
                                            @foreach($consumption_report->link_to_report as $link)
                                                 <a href="/gephaz{{ \Illuminate\Support\Facades\Storage::disk('local')->url('consumption-reports/' . $link) }}" class="dropdown-item"
                                                    download
                                                    >
                                                    <i class="icon-file-download"></i>
                                                    <span>{{ $link }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center" style="max-width:40px;">
                                <div class="list-icons">
                                    <div class="btn-group ml-2">
                                        <button type="button"
                                                class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple"
                                                data-toggle="dropdown" aria-expanded="false"><i
                                                class="icon-menu7"></i></button>

                                        <div class="dropdown-menu" x-placement="top-start"
                                             style="position: absolute; transform: translate3d(0px, -165px, 0px); top: 0px; left: 0px; will-change: transform;overflow:auto;max-height:500px">
                                            @foreach($consumption_report->link_to_author_report ?? [] as $link)
                                                 <a href="/gephaz{{ \Illuminate\Support\Facades\Storage::disk('local')->url('author-consumption-reports/' . $link) }}" class="dropdown-item"
                                                    download
                                                    >
                                                    <i class="icon-file-download"></i>
                                                    <span>{{ $link }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center" style="max-width:40px;">
                                <div class="list-icons">
                                    <div class="btn-group ml-2">
                                        <button type="button"
                                                class="btn alpha-primary btn-primary-800 text-primary-800 btn-icon dropdown-toggle legitRipple"
                                                data-toggle="dropdown" aria-expanded="false"><i
                                                class="icon-menu7"></i></button>

                                        <div class="dropdown-menu" x-placement="top-start"
                                             style="position: absolute; transform: translate3d(0px, -165px, 0px); top: 0px; left: 0px; will-change: transform;overflow:auto;max-height:500px">
                                            @foreach($consumption_report->link_to_copyright_report ?? [] as $link)
                                                 <a href="/gephaz{{ \Illuminate\Support\Facades\Storage::disk('local')->url('legal-owner-consumption-reports/' . $link) }}" class="dropdown-item"
                                                    download
                                                    >
                                                    <i class="icon-file-download"></i>
                                                    <span>{{ $link }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
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
