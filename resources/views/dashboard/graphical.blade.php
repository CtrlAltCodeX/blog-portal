@extends('layouts.master')

@section('title', __("Graphical Dashboard"))

@section('content')

<style>
    .custom-card {
        border-radius: 0.4rem;
        position: relative;
        margin-block-end: 1.5rem;
        width: 100%;
        max-height: 145px !important;
    }

    .max-145-height {
        max-height: 145px !important;
    }
</style>

<div class="card mb-5">
    <div class="card-header">
        <h1 class="card-title">Welcome {{ auth()->user()->name }}, your performance charts:</h1>
    </div>
    <div class="card-body">
        @if (auth()->user()->id == 1)
            <form method="GET" action="{{ route('graphical.dashboard') }}">
                    <div class="d-flex gap-3 flex-wrap align-items-end justify-content-end mb-4">
                        <div style="width: 20%;">
                            <label>Select User</label>
                            <select name="user_id" class="form-control">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ request()->get('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div style="width: 20%;">
                            <label>Select Range</label>
                            <select name="range" id="rangeSelect" class="form-control">
                                <option value="today" {{$range == 'today' ? 'selected' : '' }}>Today's</option>
                                <option value="3" {{$range == '3' ? 'selected' : '' }}>Last 3 Days</option>
                                <option value="7" {{$range == '7' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="15" {{$range == '15' ? 'selected' : '' }}>Last 15 Days</option>
                                <option value="30" {{$range == '30' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="60" {{$range == '60' ? 'selected' : '' }}>Last 60 Days</option>
                                <option value="90" {{$range == '90' ? 'selected' : '' }}>Last 90 Days</option>
                                <option value="all" {{$range == 'all' ? 'selected' : '' }}>All Time</option>
                                <option value="custom" {{$range == 'custom' ? 'selected' : '' }}>Custom Date</option>
                            </select>
                        </div>
                        <div style="width: 20%;" id="fromDateDiv" class="{{ request()->get('range') == 'custom' ? '' : 'd-none' }}">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request()->get('from_date') }}">
                        </div>
                        <div style="width: 20%;" id="toDateDiv" class="{{ request()->get('range') == 'custom' ? '' : 'd-none' }}">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request()->get('to_date') }}">
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                </div>
            </form>
        @endif

        @if ($cardTotals)
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card text-white shadow-sm rounded-4 h-100 hover-shadow custom-card" style="background: linear-gradient(135deg, #09ad95, #0056b3);box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);border-radius: 10px;">
                        <div class="card-body text-center">
                            <i class="fa fa-check-circle" style="font-size: 40px;margin-bottom: 10px;"></i>
                            <h6 class="text-uppercase">Approved</h6>
                            <h2 class="fw-bold">{{ $cardTotals['approved'] }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card text-white shadow-sm rounded-4 h-100 hover-shadow custom-card" style="background: linear-gradient(135deg, #e82646, #0056b3);box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);border-radius: 10px;">
                        <div class="card-body text-center">
                            <i class="fa fa-times-circle" style="font-size: 40px;margin-bottom: 10px;"></i>
                            <h6 class="text-uppercase">Rejected</h6>
                            <h2 class="fw-bold">{{ $cardTotals['rejected'] }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card text-white shadow-sm rounded-4 h-100 hover-shadow custom-card" style="background: linear-gradient(135deg, #f7b731, #0056b3);box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);border-radius: 10px;">
                        <div class="card-body text-center">
                            <i class="fa fa-trash" style="font-size: 40px;margin-bottom: 10px;"></i>
                            <h6 class="text-uppercase">Deleted</h6>
                            <h2 class="fw-bold">{{ $cardTotals['deleted'] }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card text-white shadow-sm rounded-4 h-100 hover-shadow custom-card" style="background: linear-gradient(135deg, #6c5ffc, #0056b3);box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);border-radius: 10px;">
                        <div class="card-body text-center">
                            <i class="fa fa-plus-circle" style="font-size: 40px;margin-bottom: 10px;"></i>
                            <h6 class="text-uppercase">Created</h6>
                            <h2 class="fw-bold">{{ $cardTotals['created'] }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 mb-4 max-145-height">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #007bff, #0056b3); box-shadow: 0 4px 10px rgba(0,0,0,0.2); border-radius: 10px;">
                        <div class="card-body d-flex flex-column justify-content-between text-center position-relative">
                            <div>
                                <h5 class="mb-2">Created This Week:</h5>
                                <h3><strong>{{ $currentWeekDataCreated }}</strong></h3>
                            </div>
                        </div>
                        @if($currentWeekDataCreated <= 120)
                            <span class="badge-status text-center" style="background-color: red;">AT RISK</span>
                        @elseif($currentWeekDataCreated <= 149)
                            <span class="badge-status text-center" style="background-color: orange;">REVIEW</span>
                        @elseif($currentWeekDataCreated <= 199)
                            <span class="badge-status text-center" style="background-color: yellow;">AVERAGE</span>
                        @elseif($currentWeekDataCreated <= 349)
                            <span class="badge-status text-center" style="background-color: lightgreen;">GOOD</span>
                        @else
                            <span class="badge-status text-center" style="background-color: green;">EXCELLENT</span>
                        @endif
                    </div>
                </div>

                <div class="col-md-2 mb-4 max-145-height">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #1170e4, #0056b3);box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);border-radius: 10px;">
                        <div class="card-body text-center">
                            <h5> Total Created</h5>
                            <h2>{{ $createdCount }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 mb-4 max-145-height">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #05c3fb, #0056b3);box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);border-radius: 10px;">
                        <div class="card-body text-center">
                            <h5> Total Updated</h5>
                            <h2>{{ $editedCount }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 mb-4 max-145-height">
                    <div class="card bg-dark text-white h-100" style="background: linear-gradient(135deg, #343a40, #0056b3);box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);border-radius: 10px;">
                        <div class="card-body text-center">
                            <h5>All (Created + Updated)</h5>
                            <h2>{{ $sumOfBoth }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 mb-4 max-145-height">
                    <div class="card bg-light h-100 text-dark">
                        <div class="card-body text-center">
                            <i class="fa fa-calendar fa-2x text-primary mb-2"></i>
                            <h5>Account Age (Days)</h5>
                            <h2>{{ $daysSinceCreation }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 mb-4 max-145-height">
                    <div class="card bg-light h-100 text-dark">
                        <div class="card-body text-center">
                            <i class="fa fa-money fa-2x text-primary mb-2"></i>
                            <h5>Expected Earning</h5>
                            <h2>₹ {{ $expectedEarning }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Graphical Representations</h2>
    </div>
  <div class="card-body p-4">
    <div class="row g-4">
      <div class="col-lg-6">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title text-center">Bar Chart Overview</h5>
            @if($graphTotals)
              <div style="position: relative;">
                <canvas id="barChart"  style="max-width: 7000px; height: 700px"></canvas>
              </div>
            @else
              <p class="text-muted text-center">Please select a user to see data.</p>
            @endif
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="row g-4">
          <div class="col-md-6">
            <div class="card h-100 shadow-sm">
              <div class="card-body ">
                <h6>Total Created</h6>
                <div style="position: relative; height:250px;">
                  <canvas id="createdPie"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card h-100 shadow-sm">
              <div class="card-body ">
                <h6>Total Updated</h6>
                <div style="position: relative; height:250px;">
                  <canvas id="editedPie"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card h-100 shadow-sm">
              <div class="card-body ">
                <h6>Total (Created + Edited)</h6>
                <div style="position: relative; height:250px;">
                  <canvas id="totalPie"></canvas>
                </div>
              </div>
            </div>
          </div>

          
          <div class="col-md-6">
            <div class="card h-100 shadow-sm">
              <div class="card-body ">
                <h6>Expected Earning</h6>
                <div style="position: relative; height:250px;">
                  <canvas id="ExpectedEarning"></canvas>
                </div>
              </div>
            </div>
          </div>

          
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card mb-5">
  <div class="card-header">
    <h4 class="card-title">Active Sessions</h4>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="basic-datatable" class="table table-bordered text-nowrap border-bottom mb-0">
        <thead>
          <tr>
            <th>{{ __('Sl') }}</th>
            <th>{{ __('Session Id') }}</th>
            <th>{{ __('Session Start') }}</th>
            <th>{{ __('Session Expire') }}</th>
            <th>{{ __('Action') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($userSessionsCount as $key => $session)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $session->session_id }}</td>
            <td>{{ \Carbon\Carbon::parse($session->created_at)->format('d-m-Y h:i A') }}</td>
            <td>{{ \Carbon\Carbon::parse($session->expire_at)->format('d-m-Y h:i A') }}</td>
            <td>
              @if(session('sessionId') !== $session->session_id)
                <a href="{{ route('user.session.delete', $session->session_id) }}"
                   class="btn btn-danger btn-sm">Delete</a>
              @else
                <span class="btn btn-success btn-sm disabled">Current Session</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rangeSelect = document.getElementById('rangeSelect');
        const fromDiv = document.getElementById('fromDateDiv');
        const toDiv = document.getElementById('toDateDiv');

        function toggleCustomDate() {
            if (rangeSelect.value === 'custom') {
                fromDiv.classList.remove('d-none');
                toDiv.classList.remove('d-none');
            } else {
                fromDiv.classList.add('d-none');
                toDiv.classList.add('d-none');
            }
        }

        rangeSelect.addEventListener('change', toggleCustomDate);
        toggleCustomDate(); // Initialize on page load
    });
</script>

@if ($graphTotals)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('barChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Approved', 'Rejected', 'Deleted', 'Created', 'Total Created', 'Total Updated'],
            datasets: [{
                label: 'Count',
                data: [
                    {{ $graphTotals['approved'] }},
                    {{ $graphTotals['rejected'] }},
                    {{ $graphTotals['deleted'] }},
                    {{ $graphTotals['created'] }},
                    {{ $createdCount }},
                    {{ $editedCount }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(220, 53, 69, 0.7)',
                    'rgba(255, 193, 7, 0.7)',
                    'rgba(0, 123, 255, 0.7)',
                    'rgba(23, 162, 184, 0.7)',   
                    'rgba(108, 117, 125, 0.7)' 
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(0, 123, 255, 1)'
                ],
                borderWidth: 0.5
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>

@endif

<script>
    const createdData = [{{ $createdLastMonth }}, {{ $createdThisMonth }}];
    const editedData = [{{ $editedLastMonth }}, {{ $editedThisMonth }}];
    const totalData = [{{ $totalLastMonth }}, {{ $totalThisMonth }}];


    const expectedEarningData = [{{ $lastMonthExpectedEarning ?? 0 }}, {{ $thisMonthExpectedEarning ?? 0 }}];

    const pieOptions = {
        type: 'pie',
        options: {
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    };

    new Chart(document.getElementById('createdPie'), {
        ...pieOptions,
        data: {
            labels: ['Last Month', 'This Month'],
            datasets: [{
                data: createdData,
                backgroundColor: ['#36A2EB', '#4BC0C0']
            }]
        }
    });

    new Chart(document.getElementById('editedPie'), {
        ...pieOptions,
        data: {
            labels: ['Last Month', 'This Month'],
            datasets: [{
                data: editedData,
                backgroundColor: ['#FF6384', '#FF9F40']
            }]
        }
    });

    new Chart(document.getElementById('totalPie'), {
        ...pieOptions,
        data: {
            labels: ['Last Month', 'This Month'],
            datasets: [{
                data: totalData,
                backgroundColor: ['#9966FF', '#FFCD56']
            }]
        }
    });

    new Chart(document.getElementById('ExpectedEarning'), {
        ...pieOptions,
        data: {
            labels: ['Last Month', 'This Month'],
            datasets: [{
                data: expectedEarningData,
               backgroundColor: ['#28a745', '#ffc107'],
                borderColor: ['#218838', '#e0a800'],
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ₹ ' + context.parsed.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: ['Created', 'Edited'],
            datasets: [{
                label: 'Status Count',
                data: [{{ $createdCount }}, {{ $editedCount }}],
                backgroundColor: ['#007bff', '#6c757d']
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });


    // new Chart(document.getElementById('ExpectedEarning'), {
    //     ...pieOptions,
    //     data: {
    //         labels: ['Last Month', 'This Month'],
    //         datasets: [{
    //             data: expectedEarningData,
    //             backgroundColor: ['#28a745', '#ffc107'],
    //             borderColor: ['#218838', '#e0a800'],
    //             borderWidth: 1
    //         }]
    //     },
    //     options: {
    //         plugins: {
    //             legend: {
    //                 position: 'bottom'
    //             },
    //             tooltip: {
    //                 callbacks: {
    //                     label: function(context) {
    //                         return context.label + ': $' + context.parsed.toLocaleString();
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // });
</script>
    
@endpush
