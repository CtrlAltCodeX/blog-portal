<script>
    $(document).ready(function() {
        $(".genre").select2();
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
            // var url = $(this).val();
            // if (!url.includes('https://www.instamojo.com/EXAM360/')) {
            //     errorHandling('url', 'Please add instamojo link', false, this);
            // } else {
            //     errorHandling(fieldId, '', true, this);
            // }
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
                    if (selectedValues.includes(value)) { // Use modern `includes` method
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

            if (totalLength < 0) {
                event.preventDefault();
                alert('You must select at least 170 characters in Category.');
            }

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
                    // Display error message
                    var fieldId = $(this).attr('name');
                    if (fieldId != 'images[]' && fieldId != 'multipleImages[]' && fieldId != 'url' && fieldId != 'images') {
                        $(this).css('border', '1px red solid');

                        $('.' + fieldId).text('Please do not enter URLs.');
                        valid = false;
                    }
                }

                var notRequiredFields = ['multipleImages[]', 'files', 'discount'];

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

                if (textareaValue == '') {
                    var fieldId = $(this).attr('name');
                    if (fieldId) {
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
        });

        $("textarea").on('input', function() {
            calculateFields();
        });

        $("input").on('input', function() {
            calculateFields();
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
            var notRequiredFields = ['multipleImages[]', 'files', 'isbn_10', 'isbn_13', 'isbn_10', 'reading_age', 'country_origin', 'genre', 'manufacturer', 'importer', 'discount']; // Add more fields as needed

            var fieldId = $(currentElement).attr('name');
            if (fieldId && !notRequiredFields.includes(fieldId) && !val) {
                errorHandling(fieldId, 'This field is required', false, currentElement);
            } else {
                errorHandling(fieldId, '', true, currentElement);
            }
        }

        function nameValidate(val, currentElement) {
            var fieldId = $(currentElement).attr('name');

            var errors = [];
            var normalString = '';
            var validateFields = JSON.parse(localStorage.getItem('validate'));
            validateFields.forEach(function(value) {
                if (value.name) {
                    var nameToCheck = value.name.toLowerCase();
                    if (val.toLowerCase().includes(nameToCheck)) {
                        // console.log(val);
                        errors.push(value.name);
                        normalString = errors.join(", ");
                        errorHandling(fieldId, 'Words not allowed - ' + errors, false, currentElement)
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
    })

    $('#count').html("<strong>Label Selected : </strong>" + $('.select2').val().length);

    $('.select2').change(function() {
        $('#count').html("<strong>Label Selected : </strong>" + $(this).val().length);
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



    document.addEventListener("DOMContentLoaded", function() {
    const publications = @json($publications);
    const pubDropdown = document.getElementById("pub_name");
    const bookDropdown = document.getElementById("book_name");
    const mrpInput = document.getElementById("mrp"); // MRP input
    let discount = 0, locationDis = 0;

    // Jab Publication select ho
    pubDropdown.addEventListener("change", function() {
        const selectedPub = publications.find(pub => pub.id == this.value);
        bookDropdown.innerHTML = '<option value="">-- Select Book --</option>'; // Reset Books

        if (selectedPub) {
            for (let i = 1; i <= 6; i++) {
                const bookType = selectedPub[`book_type_${i}`];
                if (bookType) {
                    const option = document.createElement("option");
                    option.value = `book_discount_${i}`;
                    option.textContent = bookType;
                    bookDropdown.appendChild(option);
                }
            }
        }
    });

    // Jab Book select ho
    bookDropdown.addEventListener("change", function() {
        const selectedPub = publications.find(pub => pub.id == pubDropdown.value);
        if (selectedPub && this.value) {
            discount = parseInt(selectedPub[this.value]) || 0;
            locationDis = parseInt(selectedPub.location_dis) || 0;
            calculatePrice();
        }
    });

    // Jab MRP change ho to calculation update ho
    mrpInput.addEventListener("input", calculatePrice);

    function calculatePrice() {
        const mrp = parseInt(mrpInput.value) || 0;
        const locationDiscount = (locationDis / 100) * mrp;
        const finalPrice = mrp - (discount - locationDiscount);

        console.log(`MRP: ${mrp}, Discount: ${discount}, Location Discount: ${locationDiscount}, Final Price: ${finalPrice}`);

        if (mrp && locationDiscount && finalPrice>0) {
    console.log("Fetching records for price:", finalPrice);

    fetch("{{ route('listing.getPriceRecords') }}?price=" + encodeURIComponent(finalPrice))
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.length === 0) {
            console.error("No data received.");
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

            console.log("selling_price1",selling_price1)
            console.log("selling_price2",selling_price2)

            document.getElementById("selling_price1").textContent = selling_price1.toFixed(2);
            document.getElementById("selling_price2").textContent = selling_price2.toFixed(2);

        })
        .catch(error => console.error("Error fetching records:", error));
}


    }
});
</script>