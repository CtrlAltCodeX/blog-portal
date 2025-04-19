

@extends('layouts.master')

@section('title', __("Graphical Dashboard"))

@section('content')
<div class="main-container container-fluid">

    {{-- Stats Card --}}
    <div class="card mb-5">
        <div class="card-header">
            <h4 class="card-title">Creations Details</h4>
        </div>
        <div class="card-body">
        @if (auth()->user()->id == 1)
        <form method="GET" action="{{ route('graphical.dashboard') }}">
    <div class="form-group mb-4 d-flex justify-content-end" style="width: 100%;">
        <div style="width: 30%;">
            <label class="mb-2">Select User (for Cards)</label>
            <select name="card_user_id" class="form-control" style="height: 45px;" onchange="this.form.submit()">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $cardUserId == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
                <input type="hidden" name="graph_user_id" value="{{ $graphUserId }}">
            </select>
        </div>
    </div>
</form>

            @endif

            @if ($cardTotals)
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5>Approved</h5>
                                <h2>{{ $cardTotals['approved'] }}</h2>
                            </div>
                          
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5>Rejected</h5>
                                <h2>{{ $cardTotals['rejected'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-dark">
                            <div class="card-body">
                                <h5>Deleted</h5>
                                <h2>{{ $cardTotals['deleted'] }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5>Created</h5>
                                <h2>{{ $cardTotals['created'] }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
    <div class="card text-white" style="position: relative; background: linear-gradient(135deg, #007bff, #0056b3); box-shadow: 0 4px 10px rgba(0,0,0,0.2); border-radius: 10px;">
        <div class="card-body d-flex flex-column align-items-start justify-content-between" style="position: relative;">
            <!-- Badge at Top Right -->
            <div style="position: absolute; top: 10px; right: 10px;">
                @if($currentWeekDataCreated <= 120)
                    <span class="badge-status" style="background-color: red; color: white; padding: 5px 10px; border-radius: 5px;">AT RISK</span>
                @elseif($currentWeekDataCreated >= 121 && $currentWeekDataCreated <= 149)
                    <span class="badge-status" style="background-color: orange; color: black; padding: 5px 10px; border-radius: 5px;">REVIEW</span>
                @elseif($currentWeekDataCreated >= 150 && $currentWeekDataCreated <= 199)
                    <span class="badge-status" style="background-color: yellow; color: black; padding: 5px 10px; border-radius: 5px;">AVERAGE</span>
                @elseif($currentWeekDataCreated >= 200 && $currentWeekDataCreated <= 349)
                    <span class="badge-status" style="background-color: lightgreen; color: black; padding: 5px 10px; border-radius: 5px;">GOOD</span>
                @elseif($currentWeekDataCreated >= 350)
                    <span class="badge-status" style="background-color: green; color: white; padding: 5px 10px; border-radius: 5px;">EXCELLENT</span>
                @endif
            </div>

            <!-- Card content -->
            <div>
                <h5 class="mb-2">Created This Week:</h5>
                <h3><strong>{{ $currentWeekDataCreated }}</strong></h3>
            </div>
            <div class="w-100 mt-auto text-center">
                <a href="/account_health.jpeg" target="_blank" class="text-white text-decoration-underline">View Health Chart</a>
            </div>
        </div>
    </div>
</div>


                </div>
            @endif

        </div>
    </div>

    {{-- Graphical Representation --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Graphical Representations</h4>
        </div>
        <div class="card-body">
        @if (auth()->user()->id == 1)
        <form method="GET" action="{{ route('graphical.dashboard') }}">
    <div class="form-group mb-4 d-flex justify-content-end" style="width: 100%;">
        <div style="width: 20%;">
            <label class="mb-2">Select User (for Graph)</label>
            <select name="graph_user_id" class="form-control" style="height: 45px;" onchange="this.form.submit()">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $graphUserId == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
                <input type="hidden" name="card_user_id" value="{{ $cardUserId }}">
            </select>
        </div>
    </div>
</form>

            @endif

            @if ($graphTotals)
            <canvas id="barChart" style="max-width: 7000px; height: 400px; margin: auto;"></canvas>
            @else
                <p class="text-muted">Please select a user to see graphical data.</p>
            @endif

        </div>
    </div>

</div>
@endsection

@push('js')
    @if ($graphTotals)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('barChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Approved', 'Rejected', 'Deleted', 'Created',"",'','',''],
                datasets: [{
                    label: 'Count',
                    data: [
                        {{ $graphTotals['approved'] }},
                        {{ $graphTotals['rejected'] }},
                        {{ $graphTotals['deleted'] }},
                        {{ $graphTotals['created'] }}
                    ],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)',
                        'rgba(220, 53, 69, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(0, 123, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(0, 123, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
    @endif
@endpush
