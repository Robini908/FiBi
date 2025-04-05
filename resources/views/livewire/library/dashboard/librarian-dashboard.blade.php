<div>
    <div class="row">
        <!-- Statistics cards -->
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-blue-400 has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-books icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ number_format($totalBooks) }}</h3>
                        <span class="text-uppercase font-size-xs">Total Books</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-success-400 has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-copy4 icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ number_format($availableCopies) }} / {{ number_format($totalCopies) }}</h3>
                        <span class="text-uppercase font-size-xs">Copies Available</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-indigo-400 has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-bag icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ number_format($activeLoans) }}</h3>
                        <span class="text-uppercase font-size-xs">Active Loans</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-danger-400 has-bg-image">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <i class="icon-alarm icon-3x opacity-75"></i>
                    </div>
                    <div class="media-body text-right">
                        <h3 class="mb-0">{{ number_format($overdueLoans) }}</h3>
                        <span class="text-uppercase font-size-xs">Overdue Items</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick actions -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Quick Actions</h6>
                </div>

                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <a href="{{ route('library.books.create') }}" class="btn bg-teal-400 btn-icon btn-lg mb-3 rounded-round">
                                <i class="icon-plus3"></i>
                            </a>
                            <h5 class="mb-0">Add Book</h5>
                            <div class="text-muted">New title</div>
                        </div>

                        <div class="col-4">
                            <a href="{{ route('library.loans.index') }}" class="btn bg-primary-400 btn-icon btn-lg mb-3 rounded-round">
                                <i class="icon-bag"></i>
                            </a>
                            <h5 class="mb-0">Issue Loan</h5>
                            <div class="text-muted">Checkout</div>
                        </div>

                        <div class="col-4">
                            <a href="{{ route('library.reservations.index') }}" class="btn bg-warning-400 btn-icon btn-lg mb-3 rounded-round">
                                <i class="icon-alarm-check"></i>
                            </a>
                            <h5 class="mb-0">Reservations</h5>
                            <div class="text-muted">Pending {{ number_format($pendingReservations) }}</div>
                        </div>
                    </div>

                    <div class="row text-center mt-3">
                        <div class="col-4">
                            <a href="{{ route('library.reports') }}" class="btn bg-violet-400 btn-icon btn-lg mb-3 rounded-round">
                                <i class="icon-stats-dots"></i>
                            </a>
                            <h5 class="mb-0">Reports</h5>
                            <div class="text-muted">Analytics</div>
                        </div>

                        <div class="col-4">
                            <a href="{{ route('library.inventory') }}" class="btn bg-pink-400 btn-icon btn-lg mb-3 rounded-round">
                                <i class="icon-list"></i>
                            </a>
                            <h5 class="mb-0">Inventory</h5>
                            <div class="text-muted">Manage stock</div>
                        </div>

                        <div class="col-4">
                            <a href="{{ route('library.circulation.overdue') }}" class="btn bg-danger-400 btn-icon btn-lg mb-3 rounded-round">
                                <i class="icon-exclamation"></i>
                            </a>
                            <h5 class="mb-0">Overdue</h5>
                            <div class="text-muted">{{ number_format($overdueLoans) }} items</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent reservations -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Recent Reservations</h6>
                    <div class="header-elements">
                        <a href="{{ route('library.reservations.index') }}" class="text-primary">View All</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>User</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReservations ?? [] as $reservation)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <a href="{{ route('library.books.show', $reservation->book_id) }}" class="text-body font-weight-semibold">
                                                    {{ Str::limit($reservation->book->title, 30) }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $reservation->user->name }}</td>
                                    <td>{{ $reservation->reservation_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($reservation->status == 'Pending') badge-secondary
                                            @elseif($reservation->status == 'Approved') badge-info
                                            @elseif($reservation->status == 'Fulfilled') badge-success
                                            @elseif($reservation->status == 'Cancelled') badge-danger
                                            @else badge-secondary
                                            @endif">
                                            {{ $reservation->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent reservations</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Monthly loan chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Monthly Loans</h6>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="loans-chart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Books by category chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h6 class="card-title">Books by Category</h6>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="categories-chart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('page_scripts')
    <script src="{{ asset('global_assets/js/plugins/visualization/d3/d3.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/visualization/c3/c3.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/visualization/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/visualization/d3/d3_tooltip.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/charts/chart.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly loans chart
            var loansChartData = @json($loansChartData ?? '[]');
            if (loansChartData) {
                var loansData = JSON.parse(loansChartData);
                var ctx = document.getElementById('loans-chart').getContext('2d');
                
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: loansData.map(function(d) { return d.month; }),
                        datasets: [{
                            label: 'Number of Loans',
                            data: loansData.map(function(d) { return d.count; }),
                            backgroundColor: '#26a69a',
                            borderColor: '#26a69a',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 5
                                }
                            }]
                        }
                    }
                });
            }
            
            // Books by category chart
            var categoriesChartData = @json($categoriesChartData ?? '[]');
            if (categoriesChartData) {
                var categoryData = JSON.parse(categoriesChartData);
                var ctx2 = document.getElementById('categories-chart').getContext('2d');
                
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: categoryData.map(function(d) { return d.name; }),
                        datasets: [{
                            data: categoryData.map(function(d) { return d.count; }),
                            backgroundColor: [
                                '#EF5350', '#42A5F5', '#66BB6A', '#FFA726', '#26C6DA',
                                '#7E57C2', '#EC407A', '#5C6BC0', '#29B6F6', '#26A69A'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position: 'right'
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</div> 