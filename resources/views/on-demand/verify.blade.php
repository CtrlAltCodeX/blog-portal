@extends('layouts.master')

@section('title', __('Verify On Demand Listings'))

@push('css')
<style>
    .image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 20px;
        padding: 20px;
    }
    .image-item {
        position: relative;
        border: 2px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        aspect-ratio: 1/1;
        transition: transform 0.2s;
        background: #f9f9f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .image-item:hover {
        transform: scale(1.02);
        border-color: #3366ff;
    }
    .image-item img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .image-checkbox {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
        width: 22px;
        height: 22px;
        cursor: pointer;
    }
    .select-all-container {
        padding: 10px 20px;
        background: #f1f1f1;
        border-radius: 4px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    .select-all-container label {
        margin-left: 10px;
        font-weight: bold;
        cursor: pointer;
    }
    .no-data {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px 0;
        color: #888;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="main-container container-fluid">
    <div class="page-header">
        <h1 class="page-title">{{ __('Verify On Demand Listings') }}</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                {{-- Tabs --}}
                <div class="card-header border-bottom-0">
                    <div class="tabs-menu1">
                        <ul class="nav panel-tabs">
                            <li>
                                <a href="#tab1" class="active" data-bs-toggle="tab">
                                    Request To Create ({{ $requestedCreate->count() }})
                                </a>
                            </li>
                            <li>
                                <a href="#tab2" data-bs-toggle="tab">
                                    Request To Update ({{ $requestedUpdate->count() }})
                                </a>
                            </li>
                            <li>
                                <a href="#tab3" data-bs-toggle="tab">
                                    All Completed ({{ $completed->count() }})
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card-body">
                    <div class="tab-content">

                        {{-- TAB 1 : Request To Create --}}
                        <div class="tab-pane active" id="tab1">
                             @if($requestedCreate->count() > 0)
                            <div class="select-all-container">
                                <input type="checkbox" id="select-all-create"
                                       onchange="toggleSelectAll(this, 'tab1')">
                                <label for="select-all-create">Select All</label>
                            </div>
  @endif
                            <form id="form-create">
                                <div class="image-grid">
                                    @forelse($requestedCreate as $item)
                                        <div class="image-item">
                                            <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                   class="image-checkbox row-checkbox">
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="Request Image">
                                        </div>
                                    @empty
                                        <div class="no-data">
                                            No data found
                                        </div>
                                    @endforelse
                                </div>

                                @if($requestedCreate->count() > 0)
                                <div class="text-center mt-4">
                                    <button type="button"
                                            onclick="bulkAction('form-create', '{{ route('on-demand.complete') }}')"
                                            class="btn btn-warning border-dark text-dark fw-bold">
                                        Mark It as "Completed"
                                    </button>
                                </div>
                                @endif
                            </form>
                        </div>

                        {{-- TAB 2 : Request To Update --}}
                        <div class="tab-pane" id="tab2">
                             @if($requestedUpdate->count() > 0)
                            <div class="select-all-container">

                                <input type="checkbox" id="select-all-update"
                                       onchange="toggleSelectAll(this, 'tab2')">
                                <label for="select-all-update">Select All</label>
                            </div>
                             @endif
                            <form id="form-update">
                                <div class="image-grid">
                                    @forelse($requestedUpdate as $item)
                                        <div class="image-item">
                                            <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                   class="image-checkbox row-checkbox">
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="Request Image">
                                        </div>
                                    @empty
                                        <div class="no-data">
                                            No data found
                                        </div>
                                    @endforelse
                                </div>

                                @if($requestedUpdate->count() > 0)
                                <div class="text-center mt-4">
                                    <button type="button"
                                            onclick="bulkAction('form-update', '{{ route('on-demand.complete') }}')"
                                            class="btn btn-warning border-dark text-dark fw-bold">
                                        Mark It as "Completed"
                                    </button>
                                </div>
                                @endif
                            </form>
                        </div>

                        {{-- TAB 3 : All Completed --}}
                        <div class="tab-pane" id="tab3">
                             @if($completed->count() > 0)
                            <div class="select-all-container">
                                <input type="checkbox" id="select-all-completed"
                                       onchange="toggleSelectAll(this, 'tab3')">
                                <label for="select-all-completed">Select All</label>
                            </div>
                             @endif

                            <form id="form-completed">
                                <div class="image-grid">
                                    @forelse($completed as $item)
                                        <div class="image-item">
                                            <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                                   class="image-checkbox row-checkbox">
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="Request Image">
                                            <div class="badge bg-success position-absolute bottom-0 end-0 p-1">
                                                Completed
                                            </div>
                                        </div>
                                    @empty
                                        <div class="no-data">
                                            No data found
                                        </div>
                                    @endforelse
                                </div>

                                @if($completed->count() > 0)
                                <div class="text-center mt-4">
                                    <button type="button"
                                            onclick="bulkAction('form-completed', '{{ route('on-demand.uncomplete') }}')"
                                            class="btn btn-info border-dark text-dark fw-bold">
                                        Mark It as "Un Completed"
                                    </button>
                                </div>
                                @endif
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function toggleSelectAll(checkbox, tabId) {
        const tab = document.getElementById(tabId);
        const checkboxes = tab.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = checkbox.checked);
    }

    function bulkAction(formId, url) {
        const form = document.getElementById(formId);
        const selected = Array.from(
            form.querySelectorAll('.row-checkbox:checked')
        ).map(cb => cb.value);

        if (selected.length === 0) {
            alert('Please select at least one image.');
            return;
        }

        if (!confirm('Are you sure you want to perform this action?')) {
            return;
        }

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ids: selected })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Something went wrong.');
            }
        })
        .catch(() => {
            alert('Error updating status.');
        });
    }
</script>
@endpush
