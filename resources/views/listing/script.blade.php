<script>
    $(document).ready(function() {
        $(".searchable_dropdown").select2();

        // Counter to keep track of the number of file input fields
        var fileInputCounter = 2; // Start from 2 since the first one is already visible

        // Function to add a new file input field
        function addFileInput() {
            var fileInputHtml = '<div class="form-group col-md-4"><label for="url" class="form-label">Image URL</label>' +
                '<div class="input-group align-items-center">' +
                '<input placeholder="Additional Image URL" type="text" required class="form-control" id="fileInput' + fileInputCounter + '" name="multipleImages[]">' +
                '<div class="input-group-append">' +
                '<img src="/assets/images/cross.png" class="removeFileInput" style="width:25px;margin-left:5px;"/>' +
                '</div>' +
                '</div>' +
                '</div>';

            // Append the new file input field to the container
            $("#addUrls").append(fileInputHtml);

            // Enable the remove button for subsequent file input fields
            // $("#fileInputContainer .form-group:not(:first-child) .removeFileInput").prop('disabled', false);

            // Increment the counter for the next file input field
            fileInputCounter++;
        }

        // Attach a click event to the "Add File Input" button
        $("#addFileInput").click(function() {
            addFileInput();
            calculateFields();
        });

        // // Attach a click event to dynamically added "Remove" buttons
        $("#addUrls").on("click", ".removeFileInput", function() {
            // Check if there's more than one file input field before removing
            if ($("#addUrls .form-group").length > 0) {
                $(this).closest('.form-group').remove();
            }
        });

        $("#draft").click(function(e) {
            e.preventDefault();
            $("#form").append("<input type='hidden' name='isDraft' value=1 />");

            $("#form").submit();
        });

        $('input').on('input', function() {
            var inputValue = $(this).val();
            var inputName = $(this).attr('name');

            if (inputName == 'author_name' ||
                inputName == 'publication' ||
                inputName == 'edition') {
                var value = inputValue.replace(/,/g, '');
                $(this).val(value);
            }

            if (inputName == 'author_name' ||
                inputName == 'title' ||
                inputName == 'publication' ||
                inputName == 'edition' ||
                inputName == 'sku'
            ) {
                limit(this);
            }

            if (inputName == 'isbn_10' ||
                inputName == 'isbn_13'
            ) {
                const validPattern = /^[a-zA-Z0-9\-]*$/; // Regex for allowed characters
                const inputValue = $(this).val();

                if (!validPattern.test(inputValue)) {
                    // If the value doesn't match the pattern, remove invalid characters
                    errorHandling(inputName, 'Only alphabets, numbers, and hyphens are allowed', false, $(this))
                    $(this).val(inputValue.replace(/[^a-zA-Z0-9\-]/g, ''));
                }
            }

            if (inputName != 'images[]') {
                requiredFields(inputValue, this);
            }

            nameValidate(inputValue, this);

            domainValidation(inputValue, this);

            if (inputName == 'title') {
                minLimit(this);
            }

            if (inputName == 'discount' ||
                inputName == 'mrp'
            ) {
                setSellingPrice();
            }

            if (inputName == 'selling_price') {
                var sellingPrice = $(this).val();
                var mrp = parseInt($("#mrp").val());

                $('#discount').val(Math.round(((mrp - sellingPrice) / mrp) * 100));
            }
        })

        $('textarea').on('input', function() {
            var textareaValue = $(this).val();

            requiredFields(textareaValue, this);

            nameValidate(textareaValue, this);

            domainValidation(textareaValue, this);
        })

        $('#url').on('input', function() {
            var url = $(this).val();
            if (!url.includes('https://www.instamojo.com/EXAM360/')) {
                errorHandling('url', 'Please add instamojo link', false, this);
            } else {
                errorHandling('url', '', true, this);
            }
        });

        $.ajax({
            type: "GET",
            url: "{{ route('settings.keywords.validate') }}",
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            success: (result) => {
                localStorage.setItem('validate', JSON.stringify(result));
            },
        });

        $('#form, #formTest').submit(function(event) {
            var valid = true;
            var requiredvalid = true;

            var valuesToCheck = ['Stk_o', 'stock__out', 'stock__onDemand', 'Stk_d']; // Replace with your target values
            var selectedValues = $('.select2').val(); // Get selected values

            if (selectedValues) { // Ensure selectedValues is not null
                valuesToCheck.forEach(function(value) {
                    if (selectedValues.includes(value)) {
                        alert('This Labels are not accepted while update any listing. Value found: ' + value);
                    }
                });
            } else {
                console.log('No values selected.');
            }

            let totalLength = 0;
            let selectedOptions = $('.select2').find('option:selected');

            selectedOptions.each(function() {
                totalLength += $(this).text().trim().length;
            });

            @if(!auth()->user()->hasRole('Super Admin') && !auth()->user()->hasRole('Super Management'))
            if (totalLength < 170) {
                event.preventDefault();
                alert('You must select at least 170 characters in Category.');
            }
            @endif

            // Reset previous error messages
            $('.error-message').text('');

            // Iterate over each input field with the class 'no-url-validation'
            $('input').each(function() {
                var inputValue = $(this).val();
                var urlRegex = /^(http|https):\/\/[^\s\[\]]*$/i;

                if ((urlRegex.test(inputValue) &&
                        inputValue != 'http://' &&
                        inputValue != 'url') ||
                    (inputValue.includes('[') ||
                        inputValue.includes(']'))
                ) {
                    var fieldId = $(this).attr('name');
                    if (fieldId != 'images[]' && fieldId != 'multipleImages[]' && fieldId != 'url' && fieldId != 'images') {
                        $(this).css('border', '1px red solid');

                        $('.' + fieldId).text('Please do not enter URLs.');
                        valid = false;
                    }
                }

                var notRequiredFields = ['multipleImages[]', 'files', 'isbn_10', 'isbn_13', 'isbn_10', 'reading_age', 'country_origin', 'genre', 'manufacturer', 'importer', 'discount'];

                if (inputValue == '') {
                    var fieldId = $(this).attr('name');
                    if (fieldId && !notRequiredFields.includes(fieldId)) {
                        console.log(fieldId);
                        $(this).css('border', '1px red solid');
                        $('.' + fieldId).text('This field is required');
                        requiredvalid = false;
                    }
                }
            });

            $('textarea').each(function() {
                var textareaValue = $(this).val();
                var urlRegex = /^(http|https):\/\/[^\s]*$/i;

                if (urlRegex.test(textareaValue)) {
                    // Display error message
                    var fieldId = $(this).attr('name');
                    $('.' + fieldId).text('Please do not enter URLs.');
                    $(this).css('border', '1px red solid');

                    valid = false;
                }

                if (textareaValue == '') {
                    var fieldId = $(this).attr('name');
                    if (fieldId) {
                        $(this).css('border', '1px red solid');
                        $('.' + fieldId).text('This field is required');
                        requiredvalid = false;
                    }
                }
            });

            $('select').each(function() {
                var textareaValue = $(this).val();
                var notRequiredFields = ['book_name', 'pub_name'];

                if (textareaValue == '') {
                    var fieldId = $(this).attr('name');
                    if (fieldId && !notRequiredFields.includes(fieldId)) {
                        $(this).css('border', '1px red solid');
                        $('.' + fieldId).text('This field is required');
                        requiredvalid = false;
                    }
                }
            });

            var url = $('#url').val();
            if (!url.includes('https://www.instamojo.com/EXAM360/')) {
                $('#url').css('border', '1px red solid');
                $('.url').text('Please add instamojo link');
                valid = false;
            } else {
                $(this).css('border', '1px solid #e9edf4');
                $('.url').text('');
                valid = true;
            }

            // Prevent form submission if a URL is found
            if (!valid || !requiredvalid) {
                event.preventDefault();
            }
        });

        setTimeout(function() {
            calculateFields();
        }, 1000);

        $(".fields select.form-control").on('change', function() {
            calculateFields();
            checkFieldsAreChanged();
        });

        $("textarea").on('input', function() {
            calculateFields();
        });

        $("input").on('input', function() {
            calculateFields();
            checkFieldsAreChanged();
        });

        $("#publication").on('input', function() {
            $('#manufacturer').val($(this).val());
            $('#importer').val($(this).val());
        });

        function calculateFields() {
            var totalFields = $(".fields input.form-control").length + $(".fields select.form-control").length + $("textarea").length;
            var filledFields = 0;
            var emptyFields = 0;
            var notDefined = 0;

            $(".fields input.form-control").each(function(index) {
                var fieldValue = $(this).val();

                if (fieldValue != '' && fieldValue != 'http://') {
                    filledFields++;
                } else if ($(this).attr('name') !== undefined) {
                    emptyFields++;
                } else if ($(this).attr('name') === undefined) {
                    notDefined++;
                }
            });

            $(".fields select.form-control").each(function() {
                var fieldValue = $(this).val();
                if (fieldValue == '') emptyFields++;
            });

            $("textarea").each(function() {
                var fieldValue = $(this).val();
                if (fieldValue == '' && $(this).attr('class') != 'note-codable') emptyFields++;
            });

            $("#progressBar").html(emptyFields + " Remaining Out of " + (totalFields - notDefined) + " Fields");
        }

        function requiredFields(val, currentElement) {
            var notRequiredFields = ['multipleImages[]', 'images', 'files', 'pub_name', 'book_name','discount']; // Add more fields as needed

            var fieldId = $(currentElement).attr('name');
            if (fieldId && !notRequiredFields.includes(fieldId) && !val) {
                errorHandling(fieldId, 'This field is required', false, currentElement);
            } else {
                errorHandling(fieldId, '', true, currentElement);
            }
        }

        function nameValidate(val, currentElement) {
            var fieldId = $(currentElement).attr('name');

            function extractInnerText(html) {
                var div = document.createElement('div');
                div.innerHTML = html;
                return div.textContent || div.innerText || "";
            }

            // Extract inner text from the input value
            var innerText = extractInnerText(val);
            var errors = [];
            var normalString = '';
            var validateFields = JSON.parse(localStorage.getItem('validate'));
            validateFields.forEach(function(value) {
                if (value.name) {
                    var nameToCheck = value.name.toLowerCase();
                    // Split innerText into words and check for exact match
                    var words = innerText.toLowerCase().split(/\b/); // Split by word boundaries
                    if (words.includes(nameToCheck)) {
                        errors.push(value.name);
                        normalString = errors.join(", ");
                        errorHandling(fieldId, 'Words not allowed - ' + errors, false, currentElement);
                    }
                }
            });
        }

        function domainValidation(val, currentElement) {
            var fieldId = $(currentElement).attr('name');
            var validateFields = JSON.parse(localStorage.getItem('validate'));

            if (fieldId != 'url') {
                const urlRegex = /^([a-zA-Z0-9-]+)(?:\.[a-zA-Z]{2,})+(?:\/[^\s]*)?$/;

                // var urlRegex = /^(ftp):\/\/[^ "]+$/;
                if (urlRegex.test(val.replace(/(<([^>]+)>)/ig, ''))) {
                    var data = [];
                    for (var i = 0; i < validateFields.length; i++) {
                        if (validateFields[i].links) {
                            data.push(validateFields[i].links);
                        }
                    }

                    var validDomain = data.some(function(domain) {
                        var stringWithoutHTML = val.replace(/(<([^>]+)>)/ig, '');
                        return stringWithoutHTML.startsWith(domain);
                    });
                    // var domainPattern = new RegExp("^(" + data.join('|').replace(/\./g, "\\.") + ")(\/.*)?$", "i");
                    // var allowedURLsRegex = new RegExp("^(https?:\\/\\/)?((" + data.join('|') + ")(\\/|$))", "i");
                    if (!validDomain) {
                        errorHandling(fieldId, 'This URL not allowed', false, currentElement)
                    } else {
                        errorHandling(fieldId, '', true, currentElement)
                    }
                }
            }
        }

        function limit(currentElement) {
            var fieldId = $(currentElement).attr('name');
            var maxLength = $(currentElement).attr('maxlength'); // Get the maximum length allowed
            var currentLength = $(currentElement).val().length;

            $($(currentElement).parent().children().children()[1]).text(currentLength + '/' + maxLength);

            if (currentLength > maxLength) {
                $(currentElement).val($(currentElement).val().substring(0, 50));
            }
        }

        function minLimit(currentElement) {
            var fieldId = $(currentElement).attr('name');
            var minLength = Number($(currentElement).attr('minlength')); // Get the maximum length allowed
            var currentLength = $(currentElement).val().length;
            if (currentLength < minLength) {
                errorHandling(fieldId, 'Minmum 75 Character requried', false, currentElement);
            }
        }

        function errorHandling(element, msg, valid, currentElement) {
            if (!valid) {
                $(currentElement).css('border', '1px red solid');
                $('.' + element).text(msg);
                $(".fields .btn").attr('disabled', true);
            } else {
                $(currentElement).css('border', '1px solid #e9edf4');
                $('.' + element).text(msg);
                $(".fields .btn").attr('disabled', false);
            }
        }

        function setSellingPrice() {
            var discount = parseInt($('#discount').val());
            var mrp = parseInt($("#mrp").val());

            if (discount <= 100) {
                var discountedPrice = (mrp * discount) / 100;

                $('#selling_price').val(Math.round(mrp - discountedPrice));
            } else {
                $('#discount').val(0);
            }
        }

        $('#desc').summernote({
            toolbar: [
                ['font', ['bold', 'italic', 'underline']],
                ['para', ['paragraph']],
            ],
            callbacks: {
                onInit: function() {
                    $('.note-editable').attr('name', 'desc');
                },
                onChange: function(contents, $editable) {
                    var tempDiv = document.createElement('div');
                    tempDiv.innerHTML = contents;
                    var innerHTML = contents;
                    if (tempDiv.querySelector('h1')) {
                        var innerHTML = tempDiv.querySelector('h1').innerHTML;
                    }

                    requiredFields(innerHTML, $editable[0]);

                    nameValidate(innerHTML, $editable[0]);

                    domainValidation(innerHTML, $editable[0]);

                    calculateFields();
                }
            }
        });

        let totalLength = 0;
        let selectedOptions = $(".select2").find('option:selected');
        selectedOptions.each(function() {
            let optionText = $(this).text().trim();
            let textLength = optionText.length;
            totalLength += textLength;
        });

        $('#textLength').html('<strong>Total Length:</strong> ' + totalLength + ' (Min:170, Max.:184)');

        let maxLength = 184;
        $('.select2').on('change', function(event) {
            let totalLength = 0; // Reset total length before recalculating
            let selectedOptions = $(this).find('option:selected');

            // Calculate the total length of the selected options
            selectedOptions.each(function() {
                let optionText = $(this).text().trim();
                let textLength = optionText.length;
                totalLength += textLength;
            });

            // Check if the total length exceeds the limit
            if (totalLength > maxLength) {
                let lastSelected = $(this).val()[$(this).val().length - 1];

                $(this).find(`option[value='${lastSelected}']`).prop('selected', false); // Deselect only the last one
                $(this).trigger('change.select2'); // Trigger the select2 change event to update the UI

                alert('You have reached the maximum limit of 184 characters.');
                totalLength -= $(this).find(`option[value='${lastSelected}']`).text().trim().length; // Adjust total length after deselecting
            }

            // Update the total length in the UI
            $('#textLength').html('<strong>Total Length:</strong> ' + totalLength + ' (Min:170, Max.:184)');
        });

        $('#base_url').on('change', function() {
            previewImage('base_url');
        });

        previewImage('base_url');

        function previewImage(element) {
            const imageUrl = $("#" + element).val();
            const $image = $('#previewImage');
            const $status = $('.image-status');

            // Clear previous content
            $status.empty();
            $image.hide();

            // Validate URL and load image
            if (imageUrl) {
                $image.attr('src', imageUrl).on('load', function() {
                    const width = this.naturalWidth;
                    const height = this.naturalHeight;

                    // Show the image
                    $image.show();

                    // Check dimensions
                    if (width === 555 && height === 555 || width === 320 && height === 320) {
                        $('.image-status').show();
                        $('.image-status').css('background-color', 'green');
                        $status.html('<span style="color: white;">✔ Image Size is Perfect</span>');
                    } else {
                        $('.image-status').show();
                        $('.image-status').css('background-color', 'red');
                        $status.html('<span style="color: white;">✖ Image Pixels Issues Found</span>');
                    }
                }).on('error', function() {
                    $('.image-status').show();
                    $('.image-status').css('background-color', 'red');
                    $status.html('<span style="color: white;">✖ Failed to load image. Check the URL.</span>');
                });
            } else {
                $('.image-status').show();
                $('.image-status').css('background-color', 'red');
                $status.html('<span style="color: white;">✖ Please enter a valid image URL.</span>');
            }
        }

        $('#count').html("<strong>Label Selected : </strong>" + $('.select2').val().length);

        $('.select2').change(function() {
            $('#count').html("<strong>Label Selected : </strong>" + $(this).val().length);
        });

        const publications = @json($publications);
        const $pubDropdown = $("#pub_name");
        const $bookDropdown = $("#book_name");
        const $mrpInput = $("#mrp");
        let discount = 0,
            locationDis = 0,
            enteredMRP = 0;

        // Jab Publication select ho
        $pubDropdown.on("change", function() {
            const selectedPub = publications.find(pub => pub.id == $(this).val());
            $bookDropdown.html('<option value="">-- Select Book --</option>');

            if (selectedPub) {
                for (let i = 1; i <= 6; i++) {
                    const bookType = selectedPub[`book_type_${i}`];
                    if (bookType) {
                        $bookDropdown.append(`<option value="book_discount_${i}">${bookType}</option>`);
                    }
                }
            }

            calculatePrice();
        });

        // Jab Book select ho
        $bookDropdown.on("change", function() {
            const selectedPub = publications.find(pub => pub.id == $pubDropdown.val());
            if (selectedPub && $(this).val()) {
                discount = parseInt(selectedPub[$(this).val()]) || 0;
                locationDis = parseInt(selectedPub.location_dis) || 0;
            }
            calculatePrice();
        });

        // Jab MRP change ho to naye value store karo aur calculation karo
        $mrpInput.on("input", function() {
            enteredMRP = parseInt($(this).val()) || 0;
            calculatePrice();
        });

        function calculatePrice() {
            const mrp = parseInt($mrpInput.val()) || 0;
            // const locationDiscount = (locationDis / 100) * mrp;
            const locationDiscount = locationDis;
            const netDis = discount - locationDiscount;
            const finalPrice = mrp - ((netDis*mrp)/100);
            // console.log("mrp", mrp)
            // console.log("finalPrice", finalPrice)

            if (mrp && finalPrice > 0) {
                $.get("{{ route('listing.getPriceRecords') }}", {
                        price: finalPrice
                    })
                    .done(function(data) {
                        if (!data.length) {
                            // console.error("No data received.");
                            alert("No data according to" + finalPrice)
                            return;
                        }

                        const record = data[0];
                        const courier_rate = parseInt(record.courier_rate) || 0;
                        const packing_charge = parseInt(record.packing_charge) || 0;
                        const our_min_profit_value = parseInt(record.our_min_profit) || 0;
                        const max_profit_value = parseInt(record.max_profit) || 0;

                        // Calculate transaction cost
                        const transactionCost = (finalPrice + courier_rate + packing_charge) * (3 / 100);
                        const selling_price = finalPrice + courier_rate + packing_charge + transactionCost;

                        const selling_price1 = selling_price + our_min_profit_value;
                        const selling_price2 = selling_price + max_profit_value;

                        $("#selling_price1").text(selling_price1.toFixed(2));
                        $("#selling_price2").text(selling_price2.toFixed(2));

                        $("#selling_price_minus1").text(selling_price1.toFixed(2) - 45);
                        $("#selling_price_minus2").text(selling_price2.toFixed(2) - 45);
                        $("#weight").text(record.weight);
                    })
                    .fail(function(error) {
                        console.error("Error fetching records:", error);
                    });
            }
        }
        
        function checkFieldsAreChanged()
        {
            @if(isset($listing->product_id))
            $.ajax({
                type: "GET",
                url: "{{ route('database.fields.changed', $listing->product_id) }}",
                data:{
                    'title':$('#title').val(),
                    'base_url': $('#base_url').val(),
                    'selling_price': $('#selling_price').val()
                },
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    if (result[0].length != 3 && result[1]) {
                        $('#update').attr('disabled', true);
                    } else {
                        $('#update').attr('disabled', false);
                    }
                },
            });
            @endif
        }

        @if(isset($listing->product_id))
        checkFieldsAreChanged();
        @endif
    });
    
    function copyLink() {
        // Get the text field
        var copyText = document.getElementById("url");

        $("#copylink").html('Copied');

        navigator.clipboard.writeText('https://www.instamojo.com/EXAM360/');

        setTimeout(function() {
            $('#copylink').html('Copy');
        }, 500)
    }

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
            success: function (data) {
                const result = data[0] || {};
                window.AsinFetcher.fetchedResult = result;

                $('#downloadBtn, #download2Btn, #autoFillBtn').prop('disabled', false);

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
                language: 'Language',
                pages: 'No of Pages',
                reading_age: 'Reading_age',
                sku: 'SKU',
                selling_price: 'Selling Price',
                weight: 'Weight',
                country_origin: 'country_of_origin',
                importer: 'importer',
                packer: 'packer'
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
                // alert('✅ Image downloaded successfully.');
            })
            .catch(err => {
                alert('❌ Failed to download image: ' + err.message);
            });
        }
    };


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

        const keywordsComma = keywordsArray.join(', ');

        const $desc = $('#desc');

        const finalHtml = `
 ${title}<br><br>
<br>${description}<br><br>
<strong>Search Keywords:</strong><br>${keywordsComma}
`;

        const finalText = `Title: ${title}\n\nDescription:\n${description}\n\nSearch Keywords:\n${keywordsComma}`;

        if ($desc.siblings('.note-editor').length) {
          // ✅ Overwrite without old content
          $desc.siblings('.note-editor').find('.note-editable').html(finalHtml);
        } else {
          // ✅ Overwrite textarea directly
          $desc.val(finalText);
        }
      },
      error: function (xhr) {
        alert('❌ Failed: ' + xhr.statusText);
      }
    });
  }
};


</script>