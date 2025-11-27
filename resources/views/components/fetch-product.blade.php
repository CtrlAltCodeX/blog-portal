@can('Listing -> Product Fetcher (All Area)')
<div class="card mb-4 shadow-sm">
  <div class="card-body" style="background-color: #ffff72;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0 fw-bold">Fetch Product</h5>
    </div>

    <hr />

    <div class="d-flex flex-nowrap align-items-center gap-2">
      <input
        type="text"
        class="form-control flex-grow-1"
        placeholder="Enter Correct ASIN number"
        id="asinInput"
        style="min-width: 250px; height: 48px; padding: 10px 20px; font-size: 1rem; flex: 1 1 auto;"
      />

      <button
        type="button"
        class="btn btn-primary d-flex align-items-center"
        onclick="AsinFetcher.fetchAndStore(
          '{{ config('services.asin.url') }}',
          '{{ config('services.asin.token') }}'
        )"
        style='grid-gap:4px;padding-right:30px;'
      >
        <i class="fa fa-spinner d-none fa-spin" id='spinner' aria-hidden="true" style='font-size:20px;'></i>
        FETCH
      </button>
      
      <button
        type="button"
        class="btn btn-danger"
        id="autoFillBtn"
        onclick="AsinFetcher.autoFill()"
        disabled
        style="padding-right: 20px;"
      > Auto Fill </button>

      <button
        type="button"
        class="btn"
        id="downloadBtn"
        onclick="AsinFetcher.downloadImage()"
        disabled
        style="background-color: #704a4a !important;color: white !important;"
      >
        <i class="fa fa-download"></i>
      </button>

      <button
        type="button"
        class="btn-success btn"
        id="download2Btn"
        onclick="AsinFetcher.downloadImageWithWhiteBG()"
        disabled
      >
        <i class="fa fa-download"></i>
      </button>
    </div>
      <br>
      <label style='font-size:16px; text-align:center;' class='w-100'><strong>Note- </strong>You Can Use this Feature To <u>"Create New Listing"</u> & <u>"Update Old Listings"</u>. It will Save Your Time! <br /> <span class='text-danger'>Error Alert: <i>If Not Working,Then Please Report to our Developer Team  <a href='https://wa.me/917004940179' target='_blank'><i class='fa fa-whatsapp' style='margin-left:10px;'></i> Click Here</a> .</span></label>
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

<script>
  window.AsinFetcher = {
      fetchedResult: null,

      fetchAndStore: function (apiUrl, apiToken) {
          const asinInput = $('#asinInput').val().trim();
          if (!asinInput) return alert('❌ Please enter ASIN number(s).');
      
          $('#downloadBtn, #download2Btn, #autoFillBtn').prop('disabled', true);

          const asins = asinInput.split(',').map(a => a.trim()).filter(a => a);
          $.ajax({
          url: apiUrl,
          method: 'POST',
          headers: {
          'Authorization': 'Bearer ' + apiToken,
              'Content-Type': 'application/json'
          },
          data: JSON.stringify({ asins: asins }),
          beforeSend: function () {
              $("#spinner").removeClass('d-none');
          },
          success: function (data) {
              const result = data[0] || {};
              window.AsinFetcher.fetchedResult = result;

              $('#downloadBtn, #download2Btn, #autoFillBtn').prop('disabled', false);
              $("#spinner").addClass('d-none');

          },
          error: function (xhr) {
              alert('❌ Error: ' + xhr.statusText);

              $('#downloadBtn, #download2Btn, #autoFillBtn').prop('disabled', true);
          }
          });
      },

      downloadImageWithWhiteBG: function () {
          const result = this.fetchedResult || {};
          const imgUrl = result["Image Link"] || '';

          $.ajax({
              url: "{{ route('image.watermark.store') }}",
              method: 'POST',
              data: { image_url: imgUrl, type: 'json' },
              headers: {
                  // 'Content-Type': 'application/json',
                  'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
              },
              success: function (data) {
                  // img.src = data.url;
                  const a = document.createElement('a');
                  a.href = data.url;
                  a.download = data.filename || 'watermarked-image.jpg';
                  document.body.appendChild(a);
                  a.click();
                  document.body.removeChild(a);
              },
          });
      },

      autoFill: function () {
        const result = this.fetchedResult || {};
        const clean = val => {
          if (!val) return '';
          const trimmed = val.trim().toLowerCase();
          return (trimmed === 'n/a' || trimmed === 'unknown') ? '' : val;
        };

        if (result.Discription && !result.Description) {
          result.Description = result.Discription;
        }

        const fields = {
          title: 'Title',
          desc: 'Description',
          publication: 'Publisher',
          mrp: 'MRP',
          author_name: 'Author',
          edition: 'Edition',
          isbn_10: 'ISBN-10',
          isbn_13: 'ISBN-13',
          pages: 'No of Pages',
          reading_age: 'Reading_age',
          sku: 'SKU',
          selling_price: 'Selling Price',
          weight: 'Weight',
          country_origin: 'country_of_origin'
        };

        $.each(fields, (id, key) => {
          const $el = $('#' + id);
          if ($el.length) {
            switch ($el.prop('tagName')) {
              case 'INPUT':
              case 'SELECT':
                $el.val(clean(result[key]));
                break;
              case 'TEXTAREA':
                if (id === 'desc') {
                  if ($el.siblings('.note-editor').length) {
                    $el.siblings('.note-editor').find('.note-editable').html(clean(result[key]));
                    $el.val(clean(result[key]));
                  } else {
                    $el.val(clean(result[key]));
                  }
                } else {
                  $el.val(clean(result[key]));
                }
                break;
            }
          }
        });

        if ($('#language').length) {
          const lang = clean(result.Language);
          $('#language').val(lang ? `${lang} Medium` : 'Medium');
        }

        const publisherValue = clean(result.Publisher) || 'As Per Publisher';
        $('#manufacturer').val(publisherValue);
        $('#importer').val(publisherValue);
        $('#packer').val(publisherValue);
        $('select[name="condition"]').val('New');
      },

      downloadImage: function () {
          const imgUrl = (this.fetchedResult || {})["Image Link"] || '';
          if (!imgUrl) return alert('❌ Image Link not found.');

          fetch(imgUrl, { mode: 'cors' })
          .then(res => {
              if (!res.ok) throw new Error('Network response was not ok.');
              return res.blob();
          })
          .then(blob => {
              const url = URL.createObjectURL(blob);
              $('<a>')
              .attr({ href: url, download: 'product-image.jpg' })
              .appendTo('body')[0].click();
              URL.revokeObjectURL(url);
          })
          .catch(err => {
              alert('❌ Failed to download image: ' + err.message);
          });
      }
  };
</script>

@endcan
