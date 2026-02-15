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
        cursor: pointer;
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

    .image-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        display: flex;
        gap: 5px;
    }

    .action-btn {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 4px;
        padding: 4px;
        color: #333;
        font-size: 14px;
        line-height: 1;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: #fff;
        color: #3366ff;
    }

    .select-all-container {
        padding: 10px 20px;
        background: #f1f1f1;
        border-radius: 4px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .select-all-container label {
        margin-left: 10px;
        font-weight: bold;
        cursor: pointer;
        flex-grow: 1;
    }

    .no-data {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px 0;
        color: #888;
        font-weight: 500;
    }

    /* Hover Preview Big Box */
    #image-preview-box {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        background: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 0 30px rgba(0,0,0,0.5);
        max-width: 80vw;
        max-height: 80vh;
        pointer-events: none; /* Add this to prevent flickering */
    }

    #image-preview-box img {
        max-width: 100%;
        max-height: 75vh;
        display: block;
    }

    #preview-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        z-index: 9998;
        pointer-events: none; /* Add this to prevent flickering */
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
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" id="select-all-create"
                                        onchange="toggleSelectAll(this, 'tab1')">
                                    <label for="select-all-create">Select All</label>
                                </div>
                                <button type="button" class="btn btn-sm btn-info" onclick="bulkDownload('form-create')">
                                    <i class="fe fe-download me-1"></i> Download Selected
                                </button>
                            </div>
                            @endif
                            <form id="form-create">
                                <div class="image-grid">
                                    @forelse($requestedCreate as $item)
                                    <div class="image-item">
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                            class="image-checkbox row-checkbox">
                                        
                                        <div class="image-actions">
                                            <a href="{{ route('on-demand.download', $item->id) }}" class="action-btn" title="Download">
                                                <i class="fe fe-download"></i>
                                            </a>
                                        </div>

                                        <img src="{{ asset('storage/' . $item->image) }}" alt="Request Image" 
                                            onmouseover="showPreview('{{ asset('storage/' . $item->image) }}')" 
                                            onmouseout="hidePreview()">
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
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" id="select-all-update"
                                        onchange="toggleSelectAll(this, 'tab2')">
                                    <label for="select-all-update">Select All</label>
                                </div>
                                <button type="button" class="btn btn-sm btn-info" onclick="bulkDownload('form-update')">
                                    <i class="fe fe-download me-1"></i> Download Selected
                                </button>
                            </div>
                            @endif
                            <form id="form-update">
                                <div class="image-grid">
                                    @forelse($requestedUpdate as $item)
                                    <div class="image-item">
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                            class="image-checkbox row-checkbox">
                                        
                                        <div class="image-actions">
                                            <a href="{{ route('on-demand.download', $item->id) }}" class="action-btn" title="Download">
                                                <i class="fe fe-download"></i>
                                            </a>
                                        </div>

                                        <img src="{{ asset('storage/' . $item->image) }}" alt="Request Image"
                                            onmouseover="showPreview('{{ asset('storage/' . $item->image) }}')" 
                                            onmouseout="hidePreview()">
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
                                <div class="d-flex align-items-center">
                                    <input type="checkbox" id="select-all-completed"
                                        onchange="toggleSelectAll(this, 'tab3')">
                                    <label for="select-all-completed">Select All</label>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-danger me-2" onclick="bulkAction('form-completed', '{{ route('on-demand.bulk-delete') }}', 'Are you sure you want to PERMANENTLY DELETE these records?')">
                                        <i class="fe fe-trash-2 me-1"></i> Delete Selected
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info" onclick="bulkDownload('form-completed')">
                                        <i class="fe fe-download me-1"></i> Download Selected
                                    </button>
                                </div>
                            </div>
                            @endif

                            <form id="form-completed">
                                <div class="image-grid">
                                    @forelse($completed as $item)
                                    <div class="image-item">
                                        <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                            class="image-checkbox row-checkbox">

                                        <div class="image-actions">
                                            <a href="{{ route('on-demand.download', $item->id) }}" class="action-btn" title="Download">
                                                <i class="fe fe-download"></i>
                                            </a>
                                        </div>

                                        <img src="{{ asset('storage/' . $item->image) }}" alt="Request Image"
                                            onmouseover="showPreview('{{ asset('storage/' . $item->image) }}')" 
                                            onmouseout="hidePreview()">

                                        {{-- Completed badge --}}
                                        <div class="badge bg-success position-absolute top-0 end-0 m-1" style="z-index: 5;">
                                            Completed
                                        </div>

                                        {{-- Completed info --}}
                                        <div class="position-absolute bottom-0 start-0 w-100 p-1"
                                            style="background: rgba(0,0,0,0.6); color:#fff; font-size:10px; z-index: 5;">

                                            <div>
                                                <strong>By:</strong>
                                                {{ $item->completedBy->name ?? 'N/A' }}
                                            </div>

                                            <div>
                                                <strong>At:</strong>
                                                {{ $item->completed_at
                    ? \Carbon\Carbon::parse($item->completed_at)->format('d M Y, h:i A')
                    : 'N/A' }}
                                            </div>
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

{{-- Hover Preview Box --}}
<div id="preview-overlay"></div>
<div id="image-preview-box">
    <img src="" id="preview-img">
</div>

@endsection

@push('js')
<script>
    function showPreview(src) {
        const previewBox = document.getElementById('image-preview-box');
        const previewImg = document.getElementById('preview-img');
        const overlay = document.getElementById('preview-overlay');
        
        previewImg.src = src;
        previewBox.style.display = 'block';
        overlay.style.display = 'block';
    }

    function hidePreview() {
        const previewBox = document.getElementById('image-preview-box');
        const overlay = document.getElementById('preview-overlay');
        
        previewBox.style.display = 'none';
        overlay.style.display = 'none';
    }

    function toggleSelectAll(checkbox, tabId) {
        const tab = document.getElementById(tabId);
        const checkboxes = tab.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = checkbox.checked);
    }

    function bulkAction(formId, url, customMsg = null) {
        const form = document.getElementById(formId);
        const selected = Array.from(
            form.querySelectorAll('.row-checkbox:checked')
        ).map(cb => cb.value);

        if (selected.length === 0) {
            alert('Please select at least one image.');
            return;
        }

        if (!confirm(customMsg || 'Are you sure you want to perform this action?')) {
            return;
        }

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ids: selected
                })
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

    function bulkDownload(formId) {
        const form = document.getElementById(formId);
        const selected = Array.from(
            form.querySelectorAll('.row-checkbox:checked')
        ).map(cb => cb.value);

        if (selected.length === 0) {
            alert('Please select at least one image to download.');
            return;
        }

        fetch('{{ route("on-demand.bulk-download") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                ids: selected
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.download_url) {
                const link = document.createElement('a');
                link.href = data.download_url;
                link.download = '';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                alert('Failed to generate ZIP file.');
            }
        })
        .catch(() => {
            alert('Error during bulk download.');
        });
    }
</script>
@endpush
