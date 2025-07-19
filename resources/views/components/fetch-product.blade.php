<div class="">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0 font-bold">Fetch Product</h5>
    <div class="form-check mb-0">
      <input class="form-check-input" type="checkbox" id="asinCheck" />
      <label class="form-check-label" for="asinCheck">ASIN</label>
    </div>
  </div>

  <hr />

  <div class="d-flex gap-3 align-items-center flex-wrap">
<input
  type="text"
  class="form-control w-auto flex-grow-1"
  placeholder="Search Using ASIN"
  id="asinInput"
  style="
    min-width: 250px;
    height: 48px;      
    padding: 10px 20px; 
    font-size: 1rem;
  "
/>


    <button
      type="button"
      class="btn custom-btn btn-black"
      onclick="AsinFetcher.fetchAndStore()"
    >
      FETCH
    </button>

    <button
      type="button"
      class="btn custom-btn btn-mahrun"
      id="downloadBtn"
      onclick="AsinFetcher.downloadImage()"
      disabled
    >
      Download
    </button>

    <button
      type="button"
      class="btn custom-btn btn-secondary"
      id="download2Btn"
      onclick="AsinFetcher.downloadImageWithWhiteBG()"
      disabled
    >
      Download 2
    </button>

    <button
      type="button"
      class="btn custom-btn btn-red"
      id="autoFillBtn"
      onclick="AsinFetcher.autoFill()"
      disabled
    >
      Auto-Fill
    </button>
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

  .custom-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
</style>
