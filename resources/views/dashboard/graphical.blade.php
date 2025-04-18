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
                <div class="form-group mb-4">
                    <label>Select User (for Cards)</label>
                    <select name="card_user_id" class="form-control" onchange="this.form.submit()">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $cardUserId == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                        <input type="hidden" name="graph_user_id" value="{{ $graphUserId }}">
                    </select>
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
                <div class="form-group mb-4">
                    <label>Select User (for Graph)</label>
                    <select name="graph_user_id" class="form-control" onchange="this.form.submit()">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $graphUserId == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                        <input type="hidden" name="card_user_id" value="{{ $cardUserId }}">
                    </select>
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
