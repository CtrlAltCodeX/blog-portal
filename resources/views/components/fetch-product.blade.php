@can('Listing -> Product Fetcher')
<div class="card mb-4 shadow-sm">
  <div class="card-body bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0 fw-bold">Fetch Product</h5>
    </div>

    <hr />

    <div class="d-flex flex-nowrap align-items-center gap-2">
      <input
        type="text"
        class="form-control flex-grow-1"
        placeholder="Search Using ASIN"
        id="asinInput"
        style="min-width: 250px; height: 48px; padding: 10px 20px; font-size: 1rem; flex: 1 1 auto;"
      />

      <button
        type="button"
        class="btn custom-btn btn-black"
        onclick="AsinFetcher.fetchAndStore(
          '{{ config('services.asin.url') }}',
          '{{ config('services.asin.token') }}'
        )"
      >
        FETCH
      </button>

      <button
        type="button"
        class="download-btn btn-mahrun"
        id="downloadBtn"
        onclick="AsinFetcher.downloadImage()"
        disabled
      >
        <i class="fa fa-download"></i>
      </button>

      <button
        type="button"
        class="download-btn btn-secondary"
        id="download2Btn"
        onclick="AsinFetcher.downloadImageWithWhiteBG()"
        disabled
      >
        <i class="fa fa-download"></i>
      </button>

      <button
        type="button"
        class="download-btn btn-red"
        id="autoFillBtn"
        onclick="AsinFetcher.autoFill()"
        disabled
      >
        Auto-Fill
      </button>
    </div>
  </div>
</div>

<style>
  .custom-btn,
  .download-btn {
    min-width: 50px;
    min-height: 44px;
    padding: 10px 20px;
    font-weight: 600;
    border-radius: 4px;
    color: #fff;
    white-space: nowrap; /* ✅ stops text wrap */
  }

  .btn-black {
    background-color: #000;
    border: none;
  }

  .btn-mahrun {
    background-color: #800000;
    border: none;
  }

  .btn-red {
    background-color: #c0392b;
    border: none;
  }

  .btn-secondary {
    background-color: #6c757d;
    border: none;
  }

  .download-btn i {
    font-size: 18px;
  }

  .card {
    border-radius: 6px;
    border: 1px solid #ddd;
  }

  .card-body {
    background-color: #fff;
    border-radius: 6px;
  }
</style>
@endcan
