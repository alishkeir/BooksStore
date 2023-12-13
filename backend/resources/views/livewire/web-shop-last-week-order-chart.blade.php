<div class="card">
    <div class="card-body">
        <div class="d-flex">
            <h3 class="font-weight-semibold mb-0">{{ $total }}</h3>
            <div class="list-icons ml-auto">
                <a class="list-icons-item" data-action="reload"></a>
            </div>
        </div>

        <div>
            Elmúlt hét webshop rendelései
        </div>
    </div>

    <div id="line_chart_simple" style="overflow:hidden;">
        <svg width="378" height="50">
            <g transform="translate(0,0)" width="378">
                <defs>
                    <clipPath id="clip-line-small">
                        <rect class="clip" width="378" height="50"></rect>
                    </clipPath>
                </defs>
                <path d="M20,8.46153846153846L76.33333333333333,25.76923076923077L132.66666666666666,5L189,15.384615384615383L245.33333333333331,5L301.6666666666667,36.15384615384615L358,8.46153846153846"
                      clip-path="url(#clip-line-small)" class="d3-line d3-line-medium"
                      style="stroke: rgb(33, 150, 243);"></path>
                <g>
                    <line class="d3-line-guides" x1="20" y1="50" x2="20" y2="8.46153846153846"
                          style="stroke: rgba(33, 150, 243, 0.5); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line>
                    <line class="d3-line-guides" x1="76.33333333333333" y1="50"
                          x2="76.33333333333333" y2="25.76923076923077"
                          style="stroke: rgba(33, 150, 243, 0.5); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line>
                    <line class="d3-line-guides" x1="132.66666666666666" y1="50"
                          x2="132.66666666666666" y2="5"
                          style="stroke: rgba(33, 150, 243, 0.5); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line>
                    <line class="d3-line-guides" x1="189" y1="50" x2="189" y2="15.384615384615383"
                          style="stroke: rgba(33, 150, 243, 0.5); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line>
                    <line class="d3-line-guides" x1="245.33333333333331" y1="50"
                          x2="245.33333333333331" y2="5"
                          style="stroke: rgba(33, 150, 243, 0.5); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line>
                    <line class="d3-line-guides" x1="301.6666666666667" y1="50"
                          x2="301.6666666666667" y2="36.15384615384615"
                          style="stroke: rgba(33, 150, 243, 0.5); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line>
                    <line class="d3-line-guides" x1="358" y1="50" x2="358" y2="8.46153846153846"
                          style="stroke: rgba(33, 150, 243, 0.5); stroke-dasharray: 4, 2; shape-rendering: crispedges;"></line>
                </g>
                <g>
                    <circle class="d3-line-circle d3-line-circle-medium" cx="20"
                            cy="8.46153846153846" r="3"
                            style="stroke: rgb(33, 150, 243); fill: rgb(255, 255, 255); opacity: 1;"></circle>
                    <circle class="d3-line-circle d3-line-circle-medium" cx="76.33333333333333"
                            cy="25.76923076923077" r="3"
                            style="stroke: rgb(33, 150, 243); fill: rgb(255, 255, 255); opacity: 1;"></circle>
                    <circle class="d3-line-circle d3-line-circle-medium" cx="132.66666666666666"
                            cy="5" r="3"
                            style="stroke: rgb(33, 150, 243); fill: rgb(255, 255, 255); opacity: 1;"></circle>
                    <circle class="d3-line-circle d3-line-circle-medium" cx="189"
                            cy="15.384615384615383" r="3"
                            style="stroke: rgb(33, 150, 243); fill: rgb(255, 255, 255); opacity: 1;"></circle>
                    <circle class="d3-line-circle d3-line-circle-medium" cx="245.33333333333331"
                            cy="5" r="3"
                            style="stroke: rgb(33, 150, 243); fill: rgb(255, 255, 255); opacity: 1;"></circle>
                    <circle class="d3-line-circle d3-line-circle-medium" cx="301.6666666666667"
                            cy="36.15384615384615" r="3"
                            style="stroke: rgb(33, 150, 243); fill: rgb(255, 255, 255); opacity: 1;"></circle>
                    <circle class="d3-line-circle d3-line-circle-medium" cx="358"
                            cy="8.46153846153846" r="3"
                            style="stroke: rgb(33, 150, 243); fill: rgb(255, 255, 255); opacity: 1;"></circle>
                </g>
            </g>
        </svg>
    </div>
</div>