<div class="mb-4 p-4 border rounded shadow-sm">
    <h5 class="mb-2 font-bold">Fetch Product</h5>
    <hr />

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" value="" id="{{ $checkboxId ?? 'asinCheck' }}">
        <label class="form-check-label" for="{{ $checkboxId ?? 'asinCheck' }}">ASIN</label>
    </div>

    <div class="d-flex gap-3">
        <input type="text" class="form-control" placeholder="Search Using ASIN" id="{{ $inputId ?? 'asinInput' }}">
        <button type="button" class="btn btn-primary"
                onclick="AsinFetcher.fetchAndFill({
                    asinInputId: '{{ $inputId ?? 'asinInput' }}',
                    token: 'M9kd_M7-JKGWACXbVKZICxl-YA'
                })">
            Fetch
        </button>
    </div>
</div>
