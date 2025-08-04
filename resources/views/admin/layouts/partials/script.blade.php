<script>
$(document).ready(function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Confirm delete
    $('#deleteModal form').on('submit', function(e) {
        e.preventDefault();

        if (confirm('Are you absolutely sure you want to delete this product?')) {
            this.submit();
        }
    });
});
</script>
<script>
$(document).ready(function() {
    // Filter functionality
    function applyFilters() {
        let category = $("input[name='category']:checked").val();
        let minPrice = $("#minCost").val();
        let maxPrice = $("#maxCost").val();
        let rating = $("input[name='rating']:checked").val();
        let status = $("input[name='status']:checked").val();

        let params = new URLSearchParams();
        if (category) params.append('category', category);
        if (minPrice) params.append('min_price', minPrice);
        if (maxPrice) params.append('max_price', maxPrice);
        if (rating) params.append('rating', rating);
        if (status) params.append('status', status);

        window.location.href = "{{ route('product-list') }}?" + params.toString();
    }

    // Apply filters button
    $("#applyFilters").click(function() {
        applyFilters();
    });

    // Clear filters button
    $("#clearFilters").click(function() {
        $("#filterForm")[0].reset();
        window.location.href = "{{ route('product-list') }}";
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

























































<!-- JAVASCRIPT -->
        <script src="{{asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('admin/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('admin/js/plugins.js')}}"></script>

        <!-- apexcharts -->
        <script src="{{asset('admin/libs/apexcharts/apexcharts.min.js')}}"></script>

        <!-- Vector map-->
        <script src="{{asset('admin/libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
        <script src="{{asset('admin/libs/jsvectormap/maps/world-merc.js')}}"></script>


        <!--Swiper slider js-->
        <script src="{{asset('admin/libs/swiper/swiper-bundle.min.js')}}"></script>

        <!-- Dashboard init -->
        <script src="{{asset('admin/js/pages/dashboard-ecommerce.init.js')}}"></>

        <!-- App js -->
        <script src="{{asset('admin/js/app.js')}}"></script>


        <script>
            //  Line chart datalabel
                var linechartDatalabelColors = getChartColorsArray("line_chart_datalabel");
                if (linechartDatalabelColors) {
                    var options = {
                        chart: {
                            height: 405,
                            zoom: {
                                enabled: true
                            },
                            toolbar: {
                                show: false
                            }
                        },
                        colors: linechartDatalabelColors,
                        markers: {
                            size: 0,
                            colors: "#ffffff",
                            strokeColors: linechartDatalabelColors,
                            strokeWidth: 1,
                            strokeOpacity: 0.9,
                            fillOpacity: 1,
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            width: [2, 2, 2],
                            curve: 'smooth'
                        },
                        series: [{
                            name: "Orders",
                            type: 'line',
                            data: [180, 274, 346, 290, 370, 420, 490, 542, 510, 580, 636, 745]
                        },
                        {
                            name: "Refunds",
                            type: 'area',
                            data: [100, 154, 302, 411, 300, 284, 273, 232, 187, 174, 152, 122]
                        },
                        {
                            name: "Earnings",
                            type: 'line',
                            data: [260, 360, 320, 345, 436, 527, 641, 715, 832, 794, 865, 933]
                        }
                        ],
                        fill: {
                            type: ['solid', 'gradient', 'solid'],
                            gradient: {
                                shadeIntensity: 1,
                                type: "vertical",
                                inverseColors: false,
                                opacityFrom: 0.3,
                                opacityTo: 0.0,
                                stops: [20, 80, 100, 100]
                            },
                        },
                        grid: {
                            row: {
                                colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                                opacity: 0.2
                            },
                            borderColor: '#f1f1f1'
                        },
                        xaxis: {
                            categories: [
                                "Jan",
                                "Feb",
                                "Mar",
                                "Apr",
                                "May",
                                "Jun",
                                "Jul",
                                "Aug",
                                "Sep",
                                "Oct",
                                "Nov",
                                "Dec",
                            ],
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            floating: true,
                            offsetY: -25,
                            offsetX: -5
                        },
                        responsive: [{
                            breakpoint: 600,
                            options: {
                                chart: {
                                    toolbar: {
                                        show: false
                                    }
                                },
                                legend: {
                                    show: false
                                },
                            }
                        }]
                    }

                    var chart = new ApexCharts(
                        document.querySelector("#line_chart_datalabel"),
                        options
                    );
                    chart.render();
                }

                // world map with line & markers
                var vectorMapWorldLineColors = getChartColorsArray("world-map-line-markers");
                if (vectorMapWorldLineColors)
                    var worldlinemap = new jsVectorMap({
                        map: "world_merc",
                        selector: "#world-map-line-markers",
                        zoomOnScroll: false,
                        zoomButtons: false,
                        markers: [{
                            name: "Greenland",
                            coords: [71.7069, 42.6043],
                            style: {
                                image: "../assets/images/flags/gl.svg",
                            }
                        },
                        {
                            name: "Canada",
                            coords: [56.1304, -106.3468],
                            style: {
                                image: "../assets/images/flags/ca.svg",
                            }
                        },
                        {
                            name: "Brazil",
                            coords: [-14.2350, -51.9253],
                            style: {
                                image: "../assets/images/flags/br.svg",
                            }
                        },
                        {
                            name: "Serbia",
                            coords: [44.0165, 21.0059],
                            style: {
                                image: "../assets/images/flags/rs.svg",
                            }
                        },
                        {
                            name: "Russia",
                            coords: [61, 105],
                            style: {
                                image: "../assets/images/flags/ru.svg",
                            }
                        },
                        {
                            name: "US",
                            coords: [37.0902, -95.7129],
                            style: {
                                image: "../assets/images/flags/us.svg",
                            }
                        },
                        {
                            name: "Australia",
                            coords: [25.2744, 133.7751],
                            style: {
                                image: "../assets/images/flags/au.svg",
                            }
                        },
                        ],
                        lines: [{
                            from: "Canada",
                            to: "Serbia",
                        },
                        {
                            from: "Russia",
                            to: "Serbia"
                        },
                        {
                            from: "Greenland",
                            to: "Serbia"
                        },
                        {
                            from: "Brazil",
                            to: "Serbia"
                        },
                        {
                            from: "US",
                            to: "Serbia"
                        },
                        {
                            from: "Australia",
                            to: "Serbia"
                        },
                        ],
                        regionStyle: {
                            initial: {
                                stroke: "#9599ad",
                                strokeWidth: 0.25,
                                fill: vectorMapWorldLineColors,
                                fillOpacity: 1,
                            },
                        },
                        labels: {
                            markers: {
                                render(marker, index) {
                                    return marker.name || marker.labelName || 'Not available'
                                }
                            }
                        },
                        lineStyle: {
                            animation: true,
                            strokeDasharray: "6 3 6",
                        },
                    });


                    // Multi-Radial Bar
var chartRadialbarMultipleColors = getChartColorsArray("multiple_radialbar");
if (chartRadialbarMultipleColors) {
    var options = {
        series: [85, 69, 45, 78],
        chart: {
            height: 300,
            type: 'radialBar',
        },
        sparkline: {
            enabled: true
        },
        plotOptions: {
            radialBar: {
                startAngle: -90,
                endAngle: 90,
                dataLabels: {
                    name: {
                        fontSize: '22px',
                    },
                    value: {
                        fontSize: '16px',
                    },
                    total: {
                        show: true,
                        label: 'Sales',
                        formatter: function (w) {
                            return 2922
                        }
                    }
                }
            }
        },
        labels: ['Fashion', 'Electronics', 'Groceries', 'Others'],
        colors: chartRadialbarMultipleColors,
        legend: {
            show: false,
            fontSize: '16px',
            position: 'bottom',
            labels: {
                useSeriesColors: true,
            },
            markers: {
                size: 0
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#multiple_radialbar"), options);
    chart.render();
}


    //  Spline Area Charts
    var areachartSplineColors = getChartColorsArray("area_chart_spline");
    if (areachartSplineColors) {
        var options = {
            series: [{
                name: 'This Month',
                data: [49, 54, 48, 54, 67, 88, 96]
            }, {
                name: 'Last Month',
                data: [57, 66, 74, 63, 55, 70, 85]
            }],
            chart: {
                height: 250,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            fill: {
                type: ['gradient', 'gradient'],
                gradient: {
                    shadeIntensity: 1,
                    type: "vertical",
                    inverseColors: false,
                    opacityFrom: 0.3,
                    opacityTo: 0.0,
                    stops: [50, 70, 100, 100]
                },
            },
            markers: {
                size: 4,
                colors: "#ffffff",
                strokeColors: areachartSplineColors,
                strokeWidth: 1,
                strokeOpacity: 0.9,
                fillOpacity: 1,
                hover: {
                    size: 6,
                }
            },
            grid: {
                show: false,
                padding: {
                    top: -35,
                    right: 0,
                    bottom: 0,
                    left: -6,
                },
            },
             legend: {
                show: false,
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: [2, 2],
                curve: 'smooth'
            },
            colors: areachartSplineColors,
            xaxis: {
                 labels: {
                    show: false,
                 }
            },
            yaxis: {
                labels: {
                    show: false,
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#area_chart_spline"), options);
        chart.render();
    }

        </script>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- Mirrored from themesbrand.com/toner/html/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 16 Mar 2025 17:50:20 GMT -->
</html>
