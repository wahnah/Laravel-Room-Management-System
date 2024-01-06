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
                            <canvas  id="income-chart" height="400"
                                width="100%" class="chartjs-render-monitor"
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.6.0/dist/chart.min.js"></script>
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
            createIncomeChart(response.data, response.month, response.year);
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

function createIncomeChart(data, month, year) {
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
    datasets: [
        {
            data: data.guest_count_data,
            backgroundColor: "transparent",
            borderColor: "#007bff",
            pointBorderColor: "#007bff",
            pointBackgroundColor: "#007bff",
            fill: false
        }
    ]
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

                incomeChart.on("click", function(e) {
                    const slice = myIncomeChart.getElementsAtEventForMode(e, "nearest", { intersect: true });
                    if (slice.length) {
                        const label = slice[0].index + 1;
                        window.location.href = `/get-daily-income/${year}/${month}/${label}`;
                    }
                });
            }
        });
    </script>
