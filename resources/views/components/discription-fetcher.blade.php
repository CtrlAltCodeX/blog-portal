<div style="margin-top: 20px;">
  <button
    type="button"
    class="btn btn-primary"
    onclick="TitleFetcher.fetchAndAppend()"
  >
    <i class="fa fa-spinner d-none fa-spin" id='spinner' aria-hidden="true" style='font-size:20px;'></i>
    Fetch AI Description
  </button>
</div>

<script>
  window.TitleFetcher = {
    fetchAndAppend: function () {
        const title = $('#title').val().trim();
        if (!title) {
          alert('❌ Please enter a title.');
          return;
        }

        const template = `Q. Write Product Description in 300 to 400 words of the Book Name - ${title}.
        Q. Write Top 20 Google Search Keywords Which is Most Searchable by Users Before Buying this Item of the Book Name - ${title}.`;

        $.ajax({
        url: '{{ route("getai.descriptionfetcher") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        data: { product_info: template },
        beforeSend: function () {
            $("#spinner").removeClass('d-none');
        },
        success: function (data) {
            if (!data.status) {
            alert('❌ Error: ' + data.message);
            return;
            }

            let content = data.content || '';

            const descStart = content.indexOf('Product Description:');
            const keywordsStart = content.indexOf('Top 20 Google Search Keywords:');

            let description = '';
            let keywordsText = '';

            if (descStart !== -1 && keywordsStart !== -1) {
            description = content.substring(descStart + 'Product Description:'.length, keywordsStart).trim();
            keywordsText = content.substring(keywordsStart + 'Top 20 Google Search Keywords:'.length).trim();
            }

            const keywordsArray = keywordsText.split(/\n+/).map(line => {
            return line.replace(/^\d+\.\s*/, '').trim();
            }).filter(Boolean);

            const updatedKeywords = keywordsArray.map(keyword => {
                return keyword.toLowerCase() === 'pdf' ? 'document' : keyword;
            });

            const keywordsComma = updatedKeywords.join(', ');

            const $desc = $('#desc');

            const finalHtml = `
                ${title}<br><br>
                <br>${description}<br><br>
                <strong>Search Keywords - </strong>${keywordsComma}
            `;

            const finalText = `Title: ${title}\n\nDescription:\n${description}\n\nSearch Keywords -\n${keywordsComma}`;

            if ($desc.siblings('.note-editor').length) {
              // ✅ Overwrite without old content
              $desc.siblings('.note-editor').find('.note-editable').html(finalHtml);
              $desc.html(finalHtml);
            } else {
              // ✅ Overwrite textarea directly
              $desc.html(finalText);
            }

            $("#spinner").addClass('d-none');
        },
        error: function (xhr) {
            alert('❌ Failed: ' + xhr.statusText);
        }
        });
    }
  };
</script>
