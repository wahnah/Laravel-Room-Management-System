@extends('template.master')
@section('title', 'Dashboard')
@section('content')

    @if (auth()->user()->role == 'Super' || auth()->user()->role == 'Admin')
        <div id="dashboard">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="card shadow-sm border" style="border-radius: 0.5rem">
                                <div class="card-body text-center">
                                    <h5>{{ count($transactions) }} Guests this day</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card shadow-sm border" style="border-radius: 0.5rem">
                                <div class="card-body text-center">
                                    <h5>Dashboard</h5>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box border -->
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="card shadow-sm border">
                                <div class="card-header">
                                    <div class="row ">
                                        <div class="col-lg-12 d-flex justify-content-between">
                                            <h3>Today Guests</h3>
                                            <div>
                                                <a href="#" class="btn btn-tool btn-sm">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="#" class="btn btn-tool btn-sm">
                                                    <i class="fas fa-bars"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Room</th>
                                                <th class="text-center">Stay</th>
                                                <th>Day Left</th>
                                                <th>Debt</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($transactions as $transaction)
                                                <tr>
                                                    <td>
                                                        <img src="{{ $transaction->customer->user->getAvatar() }}"
                                                            class="rounded-circle img-thumbnail" width="40"
                                                            height="40" alt="">
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="{{ route('customer.show', ['customer' => $transaction->customer->id]) }}">
                                                            {{ $transaction->customer->name }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="{{ route('room.show', ['room' => $transaction->room->id]) }}">
                                                            {{ $transaction->room->number }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ Helper::dateFormat($transaction->check_in) }} ~
                                                        {{ Helper::dateFormat($transaction->check_out) }}
                                                    </td>
                                                    <td>{{ Helper::getDateDifference(now(), $transaction->check_out) == 0 ? 'Last Day' : Helper::getDateDifference(now(), $transaction->check_out) . ' ' . Helper::plural('Day', Helper::getDateDifference(now(), $transaction->check_out)) }}
                                                    </td>
                                                    <td>
                                                        {{ $transaction->getTotalPrice() - $transaction->getTotalPayment() <= 0 ? '-' : Helper::convertToRupiah($transaction->getTotalPrice() - $transaction->getTotalPayment()) }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="justify-content-center badge {{ $transaction->getTotalPrice() - $transaction->getTotalPayment() == 0 ? 'bg-success' : 'bg-warning' }}">
                                                            {{ $transaction->getTotalPrice() - $transaction->getTotalPayment() == 0 ? 'Success' : 'Progress' }}
                                                        </span>
                                                        @if (Helper::getDateDifference(now(), $transaction->check_out) < 1)
                                                            <span class="justify-content-center badge bg-danger">
                                                                must finish payment
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        There's no data in this table
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{-- <div class="row justify-content-md-center mt-3">
                                    <div class="col-sm-10 d-flex mx-auto justify-content-md-center">
                                        <div class="pagination-block">
                                            {{ $transactions->onEachSide(1)->links('template.paginationlinks') }}
                                        </div>
                                    </div>
                                </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">

                        </div>
                    </div>
                </div>

                <div id="guests-chart-section" class="col-lg-6">
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="card shadow-sm border">
                                <div class="card-header border-0">
                                    <div class="d-flex justify-content-between">
                                        <h3 class="card-title">Monthly Guests Chart</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="d-flex flex-column">
                                            {{-- <span class="text-bold text-lg">Belum</span> --}}
                                            {{-- <span>Total Guests at {{ Helper::thisMonth() . '/' . Helper::thisYear() }}</span> --}}
                                        </p>
                                        {{-- <p class="ml-auto d-flex flex-column text-right">
                                                <span class="text-success">
                                                    <i class="fas fa-arrow-up"></i> Belum
                                                </span>
                                                <span class="text-muted">Since last month</span>
                                            </p> --}}
                                    </div>
                                    <div class="position-relative mb-4">
                                        <canvas id="visitors-chart" height="400" width="100%"
                                            class="chartjs-render-monitor"
                                            style="display: block; width: 249px; height: 200px;"></canvas>
                                    </div>

                                    <div class="d-flex flex-row justify-content-between">
                                        <input type="month" id="datePicker"
                                            value="{{ Helper::thisYear() . '-' . Helper::thisMonth() }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>
                <script>
                    $(document).ready(function() {
                        const datePicker = $('#datePicker');
                        let myVisitorChart = null; // Declare chart variable

                        // Get the initial default selected date
                        const initialSelectedDate = datePicker.val();

                        // Function to create the chart
                        function createChartWithData(selectedDate) {
                            console.log('Fetching data for selected date:', selectedDate);

                            if (myVisitorChart) {
                                myVisitorChart.destroy(); // Destroy existing chart
                            }

                            // Make an initial AJAX request to your controller with the default selected date
                            $.ajax({
                                method: 'GET',
                                url: '/get-dialy-guest-chart-data',
                                data: {
                                    selected_date: selectedDate,
                                },
                                success: function(response) {
                                    // Create the chart with the response data
                                    createGuestsChart(response.data, response.month, response.year);
                                },
                                error: function(error) {
                                    console.error('Error fetching data:', error);
                                }
                            });
                        }

                        // Create the chart with the initial default selected date
                        createChartWithData(initialSelectedDate);

                        // Date picker change event handler
                        datePicker.on('change', function() {
                            const selectedDate = datePicker.val();
                            createChartWithData(selectedDate);
                        });

                        function createGuestsChart(data, month, year) {
                            const ticksStyle = {
                                color: "#495057",
                                font: {
                                    weight: "bold"
                                }
                            };

                            const mode = "index";
                            const intersect = true;

                            let visitorsChart = $("#visitors-chart");
                            myVisitorChart = new Chart(visitorsChart, {
                                type: "line",
                                data: {
                                    labels: data.day,
                                    datasets: [{
                                        data: data.guest_count_data,
                                        backgroundColor: "transparent",
                                        borderColor: "#007bff",
                                        pointBorderColor: "#007bff",
                                        pointBackgroundColor: "#007bff",
                                        fill: false
                                    }]
                                },
                                options: {
                                    maintainAspectRatio: false,
                                    plugins: {
                                        tooltip: {
                                            mode: mode,
                                            intersect: intersect
                                        },
                                        title: {
                                            display: false
                                        }
                                    },
                                    interaction: {
                                        mode: mode,
                                        intersect: intersect,
                                        onHover: function(evt, item) {
                                            const point = item[0];
                                            if (point) {
                                                evt.target.style.cursor = "pointer";
                                            } else {
                                                evt.target.style.cursor = "default";
                                            }
                                        }
                                    },
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        y: {
                                            display: true,
                                            grid: {
                                                display: true,
                                                lineWidth: "4px",
                                                color: "rgba(0, 0, 0, .2)",
                                                drawBorder: false,
                                                zeroLineColor: "transparent"
                                            },
                                            ticks: {
                                                beginAtZero: true,
                                                suggestedMax: data.max,
                                                ...ticksStyle
                                            }
                                        },
                                        x: {
                                            display: true,
                                            grid: {
                                                display: true,
                                                drawBorder: false
                                            },
                                            ticks: ticksStyle
                                        }
                                    }
                                }
                            });

                            visitorsChart.on("click", function(e) {
                                const slice = myVisitorChart.getElementsAtEventForMode(e, "nearest", {
                                    intersect: true
                                });
                                if (slice.length) {
                                    const label = slice[0].index + 1;
                                    window.location.href = `/get-dialy-guest/${year}/${month}/${label}`;
                                }
                            });
                        }
                    });
                </script>

                <div id="income-chart-section">
                    <div class="col-lg-12">
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="card shadow-sm border">
                                    <div class="card-header border-0">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title">Monthly Income Chart</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <p class="d-flex flex-column">
                                                {{-- <span class="text-bold text-lg">Belum</span> --}}
                                                {{-- <span>Total Guests at {{ Helper::thisMonth() . '/' . Helper::thisYear() }}</span> --}}
                                            </p>
                                            {{-- <p class="ml-auto d-flex flex-column text-right">
                        <span class="text-success">
                            <i class="fas fa-arrow-up"></i> Belum
                        </span>
                        <span class="text-muted">Since last month</span>
                    </p> --}}
                                        </div>
                                        <div class="position-relative mb-4">
                                            <canvas id="income-chart" height="400" width="100%"
                                                class="chartjs-render-monitor"
                                                style="display: block; width: 249px; height: 200px;"></canvas>
                                        </div>
                                        <div class="d-flex flex-row justify-content-between">
                                            <input type="month" id="datePickerrr"
                                                value="{{ Helper::thisYear() . '-' . Helper::thisMonth() }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    $(document).ready(function() {
                        const datePickerrr = $('#datePickerrr');
                        let myIncomeChart = null; // Declare chart variable

                        // Get the initial default selected date
                        const initialSelectedDategg = datePickerrr.val();

                        // Function to create the chart
                        function createChartWithData(selectedDatez) {
                            console.log('Fetching data for selected date:', selectedDatez);

                            if (myIncomeChart) {
                                myIncomeChart.destroy(); // Destroy existing chart
                            }

                            // Make an initial AJAX request to your controller with the default selected date
                            $.ajax({
                                method: 'GET',
                                url: '/get-income-chart-data',
                                data: {
                                    selected_datee: selectedDatez,
                                },
                                success: function(response) {
                                    // Create the chart with the response data
                                    createIncomeChart(response.data, response.month, response.year, response
                                        .total_income);
                                },
                                error: function(error) {
                                    console.error('Error fetching data:', error);
                                }
                            });
                        }

                        // Create the chart with the initial default selected date
                        createChartWithData(initialSelectedDategg);

                        // Date picker change event handler
                        datePickerrr.on('change', function() {
                            const selectedDatez = datePickerrr.val();
                            createChartWithData(selectedDatez);
                        });



                        function createIncomeChart(data, month, year, total_income) {
                            const ticksStyle = {
                                color: "#495057",
                                font: {
                                    weight: "bold"
                                }
                            };

                            const mode = "index";
                            const intersect = true;

                            let incomeChart = $("#income-chart");
                            myIncomeChart = new Chart(incomeChart, {
                                type: "line",
                                data: {
                                    labels: data.day,
                                    datasets: [{
                                        data: data.income_data,
                                        backgroundColor: "transparent",
                                        borderColor: "#007bff",
                                        pointBorderColor: "#007bff",
                                        pointBackgroundColor: "#007bff",
                                        fill: false
                                    }]
                                },
                                options: {
                                    maintainAspectRatio: false,
                                    plugins: {
                                        tooltip: {
                                            mode: mode,
                                            intersect: intersect
                                        },
                                        title: {
                                            display: true,
                                            text: `Income in ${year}-${month} (Total: ZMK ${total_income.toLocaleString()})`
                                        },
                                    },
                                    interaction: {
                                        mode: mode,
                                        intersect: intersect,
                                        onHover: function(evt, item) {
                                            const point = item[0];
                                            if (point) {
                                                evt.target.style.cursor = "pointer";
                                            } else {
                                                evt.target.style.cursor = "default";
                                            }
                                        }
                                    },
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        y: {
                                            display: true,
                                            grid: {
                                                display: true,
                                                lineWidth: "4px",
                                                color: "rgba(0, 0, 0, .2)",
                                                drawBorder: false,
                                                zeroLineColor: "transparent"
                                            },
                                            ticks: {
                                                beginAtZero: true,
                                                suggestedMax: data.max,
                                                ...ticksStyle
                                            }
                                        },
                                        x: {
                                            display: true,
                                            grid: {
                                                display: true,
                                                drawBorder: false
                                            },
                                            ticks: ticksStyle
                                        }
                                    }
                                }
                            });


                            incomeChart.on("click", function(e) {
                                const slice = myIncomeChart.getElementsAtEventForMode(e, "nearest", {
                                    intersect: true
                                });
                                if (slice.length) {
                                    const label = slice[0].index + 1;
                                    window.location.href = `/get-daily-income/${year}/${month}/${label}`;
                                }
                            });
                        }
                    });
                </script>
            </div>
        </div>
    @else
        <div id="dashboard">
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="card shadow-sm border">
                                <div class="card-header">
                                    <div class="row ">
                                        <div class="col-lg-12 d-flex justify-content-between">
                                            <h3>Your Current Reservations</h3>
                                            <div>
                                                <a href="#" class="btn btn-tool btn-sm">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="#" class="btn btn-tool btn-sm">
                                                    <i class="fas fa-bars"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Room</th>
                                                <th class="text-center">Stay</th>
                                                <th>Day Left</th>
                                                <th>Debt</th>
                                                <th class="text-center">Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($userReservations as $transaction)
                                                <tr>

                                                    <td>
                                                        <a
                                                            href="{{ route('room.show', ['room' => $transaction->room->id]) }}">
                                                            {{ $transaction->room->number }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ Helper::dateFormat($transaction->check_in) }} ~
                                                        {{ Helper::dateFormat($transaction->check_out) }}
                                                    </td>
                                                    <td>{{ Helper::getDateDifference(now(), $transaction->check_out) == 0 ? 'Last Day' : Helper::getDateDifference(now(), $transaction->check_out) . ' ' . Helper::plural('Day', Helper::getDateDifference(now(), $transaction->check_out)) }}
                                                    </td>
                                                    <td>
                                                        {{ $transaction->getTotalPrice() - $transaction->getTotalPayment() <= 0 ? '-' : Helper::convertToRupiah($transaction->getTotalPrice() - $transaction->getTotalPayment()) }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="justify-content-center badge {{ $transaction->getTotalPrice() - $transaction->getTotalPayment() == 0 ? 'bg-success' : 'bg-warning' }}">
                                                            {{ $transaction->getTotalPrice() - $transaction->getTotalPayment() == 0 ? 'Success' : 'Progress' }}
                                                        </span>
                                                        @if (Helper::getDateDifference(now(), $transaction->check_out) < 1)
                                                            <span class="justify-content-center badge bg-danger">
                                                                must finish payment
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-light btn-sm rounded shadow-sm border p-1 m-0"
                                                            href="{{ route('roomservice.index', ['room' => $transaction->room->id, 'customer' => $transaction->customer->id]) }}">
                                                            Room Service
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        There's no data in this table
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{-- <div class="row justify-content-md-center mt-3">
                                    <div class="col-sm-10 d-flex mx-auto justify-content-md-center">
                                        <div class="pagination-block">
                                            {{ $transactions->onEachSide(1)->links('template.paginationlinks') }}
                                        </div>
                                    </div>
                                </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    @endif

@endsection
{{-- @section('footer')
    <script src="{{ asset('style/js/chart.min.js') }}"></script>
    <script src="{{ asset('style/js/guestsChart.js') }}"></script>
    <script>
        function reloadJs(src) {
            src = $('script[src$="' + src + '"]').attr("src");
            $('script[src$="' + src + '"]').remove();
            $('<script/>').attr('src', src).appendTo('head');
        }

        Echo.channel('dashboard')
            .listen('.dashboard.event', (e) => {
                $("#dashboard").hide()
                $("#dashboard").load(window.location.href + " #dashboard");
                $("#dashboard").show(150)
                reloadJs('style/js/guestsChart.js');
                toastr.warning(e.message, "Hello, {{ auth()->user()->name }}");
            })
    </script>
@endsection --}}
