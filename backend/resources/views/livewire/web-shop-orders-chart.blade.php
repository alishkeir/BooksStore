<div class="col-sm-6 col-xl-6">
    <div class="card card-body text-center">
        <h6 class="font-weight-semibold mb-0 mt-1">Webshop megrendelések</h6>
        <div class="font-size-sm text-muted mb-3">{{ date("Y.").'0'.(date("m")-1).date(".d") }}
            - {{ date("Y.m.d") }} </div>

        <div class="svg-center" id="pie_progress_bar">
            <svg width="146" height="146">
                <g transform="translate(73,73)">
                    <path d="M4.469960816887839e-15,-73A73,73 0 1,1 -33.628646954376094,64.79285534700513L-24.415319021670314,47.04138812864756A53,53 0 1,0 3.245314017740486e-15,-53Z"
                          fill="#EF5350"
                          style="stroke: rgb(255, 255, 255); stroke-width: 2; cursor: pointer;"></path>
                    <path d="M-33.628646954376094,64.79285534700513A73,73 0 0,1 -68.50673714502378,-25.21560956516062L-49.73776806419534,-18.307223382924832A53,53 0 0,0 -24.415319021670314,47.04138812864756Z"
                          fill="#29b6f6"
                          style="stroke: rgb(255, 255, 255); stroke-width: 2; cursor: pointer;"></path>
                    <path d="M-68.50673714502378,-25.21560956516062A73,73 0 0,1 -1.3409882450663516e-14,-73L-9.735942053221457e-15,-53A53,53 0 0,0 -49.73776806419534,-18.307223382924832Z"
                          fill="#66BB6A"
                          style="stroke: rgb(255, 255, 255); stroke-width: 2; cursor: pointer;"></path>
                    <path d="M2.87791997799628e-15,-47A47,47 0 1,1 -21.651320641858582,41.71594796314029L-20.729987848588003,39.940801241304534A45,45 0 1,0 2.7554552980815448e-15,-45Z"
                          style="fill: rgb(239, 83, 80);"></path>
                    <text dy="6"
                          style="font-size: 21px; font-weight: 500; text-anchor: middle;">{{ $total }}</text>
                </g>
            </svg>
            <ul class="chart-widget-legend">
                <li data-slice="0"
                    style="border-bottom: 2px solid rgb(239, 83, 80); opacity: 1; transition: all 0.15s ease-in-out 0s;">
                    Új: <span>{{ $new }}</span></li>
                <li data-slice="1"
                    style="border-bottom: 2px solid rgb(41, 182, 246); opacity: 1; transition: all 0.15s ease-in-out 0s;">
                    Feldolgozás alatt: <span>{{ $processing }}</span></li>
                <li data-slice="2"
                    style="border-bottom: 2px solid rgb(102, 187, 106); opacity: 1; transition: all 0.15s ease-in-out 0s;">
                    Feladva: <span>{{ $waitingForShippingAndShipping }}</span></li>
            </ul>
        </div>
    </div>
</div>