@extends('layouts.master')

@section('title', __("Graphical Dashboard"))

@section('content')
<div class="main-container container-fluid">

    {{-- Unified Filter Form --}}
    @if (auth()->user()->id == 1)
        <form method="GET" action="{{ route('graphical.dashboard') }}">
            <!-- <div class="card mb-4"> -->
                <div class="card-body d-flex gap-3 flex-wrap align-items-end justify-content-end">
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
                <!-- </div> -->
            </div>
        </form>
    @endif
    <div class="card mb-5">
    <div class="card-header">
        <h4 class="card-title">Creations Details</h4>
    </div>
    <div class="card-body">
        @if ($cardTotals)
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body text-center">
                            <h5>Approved</h5>
                            <h2>{{ $cardTotals['approved'] }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-danger text-white h-100">
                        <div class="card-body text-center">
                            <h5>Rejected</h5>
                            <h2>{{ $cardTotals['rejected'] }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-warning text-dark h-100">
                        <div class="card-body text-center">
                            <h5>Deleted</h5>
                            <h2>{{ $cardTotals['deleted'] }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body text-center">
                            <h5>Created</h5>
                            <h2>{{ $cardTotals['created'] }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #007bff, #0056b3); box-shadow: 0 4px 10px rgba(0,0,0,0.2); border-radius: 10px;">
                        <div class="card-body d-flex flex-column justify-content-between text-center position-relative">
                            <div style="position: absolute; top: 10px; right: 10px;">
                                @if($currentWeekDataCreated <= 120)
                                    <span class="badge-status" style="background-color: red;">AT RISK</span>
                                @elseif($currentWeekDataCreated <= 149)
                                    <span class="badge-status" style="background-color: orange;">REVIEW</span>
                                @elseif($currentWeekDataCreated <= 199)
                                    <span class="badge-status" style="background-color: yellow;">AVERAGE</span>
                                @elseif($currentWeekDataCreated <= 349)
                                    <span class="badge-status" style="background-color: lightgreen;">GOOD</span>
                                @else
                                    <span class="badge-status" style="background-color: green;">EXCELLENT</span>
                                @endif
                            </div>
                            <div>
                                <h5 class="mb-2">Created This Week:</h5>
                                <h3><strong>{{ $currentWeekDataCreated }}</strong></h3>
                            </div>
                            <div>
                                <a href="/account_health.jpeg" target="_blank" class="text-white text-decoration-underline">View Health Chart</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body text-center">
                            <h5> Total Created</h5>
                            <h2>{{ $createdCount }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-secondary text-white h-100">
                        <div class="card-body text-center">
                            <h5> Total Updated</h5>
                            <h2>{{ $editedCount }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-dark text-white h-100">
                        <div class="card-body text-center">
                            <h5>Created + Updated</h5>
                            <h2>{{ $sumOfBoth }}</h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-4">
                    <div class="card bg-light text-dark border border-primary h-100">
                        <div class="card-body text-center">
                            <h5>Account Age (Days)</h5>
                            <h2>{{ $daysSinceCreation }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>


{{-- Graph Section --}}
<div class="card">
  <div class="card-header">
    <h4 class="card-title">Welcome {{ auth()->user()->name }}, your performance charts:</h4>
  </div>
  <div class="card-body p-4">
    <div class="row g-4">
      {{-- Left side: Bar Chart --}}
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

      {{-- Right side: Created & Edited pies --}}
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

          {{-- Total pie full width --}}
          <div class="col-12">
            <div class="card h-100 shadow-sm">
              <div class="card-body ">
                <h6>Total (Created + Edited)</h6>
                <div style="position: relative; height:250px;">
                  <canvas id="totalPie"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</div>

{{-- Session List Card --}}
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

    // Bar Chart example
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
</script>

    
@endpush
