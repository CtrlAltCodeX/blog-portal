<div class="card mb-4 shadow-sm">
  <div class="card-body bg-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0 fw-bold">Fetch by Book Name</h5>
    </div>

    <hr />

    <div class="d-flex flex-nowrap align-items-center gap-2">
      <input
        type="text"
        class="form-control flex-grow-1"
        placeholder="Enter Book Name"
        id="titleInput"
        style="min-width: 250px; height: 48px; padding: 10px 20px; font-size: 1rem; flex: 1 1 auto;"
      />

      <button
        type="button"
        class="btn custom-btn btn-black"
        onclick="TitleFetcher.fetchAndAppend()"
      >
        Find & Give Result
      </button>
    </div>
  </div>
</div>

<style>
  .custom-btn {
    min-width: 120px;
    min-height: 44px;
    padding: 10px 20px;
    font-weight: 600;
    border-radius: 4px;
    color: #fff;
  }

  .btn-black {
    background-color: #000;
    border: none;
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
