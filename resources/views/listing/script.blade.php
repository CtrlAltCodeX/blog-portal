<script>
    $(document).ready(function() {
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

            requiredFields(inputValue, this);

            nameValidate(inputValue, this);

            emailValidate(inputValue, this);
        })

        $('textarea').on('input', function() {
            var textareaValue = $(this).val();

            requiredFields(textareaValue, this);

            nameValidate(textareaValue, this);

            emailValidate(textareaValue, this);
        })

        $('#url').on('input', function() {
            var url = $(this).val();
            if (!url.includes('https://www.instamojo.com/EXAM360/')) {
                errorHandling('url', 'Please add instamojo link', false, this);
            } else {
                errorHandling(fieldId, '', false, this);
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

        $('#form').submit(function(event) {
            // Reset previous error messages
            $('.error-message').text('');

            // Flag to check if any URL is found
            var valid = true;
            var requiredvalid = true;

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

                if (inputValue == '') {
                    var fieldId = $(this).attr('name');
                    if (fieldId != 'multipleImages[]' &&
                        fieldId != 'files' &&
                        fieldId
                    ) {
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
        })

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
                if (fieldValue == '') emptyFields++;
            });

            $("#progressBar").html(emptyFields + " Remaining Out of " + (totalFields - notDefined) + " Fields");
        }

        function requiredFields(val, currentElement) {
            var fieldId = $(currentElement).attr('name');
            if (val == '') {
                if (fieldId != 'multipleImages[]' && fieldId != 'files') {
                    errorHandling(fieldId, 'This field is required', false, currentElement);
                }
            } else {
                errorHandling(fieldId, '', true, currentElement);
            }
        }

        function nameValidate(val, currentElement) {
            var fieldId = $(currentElement).attr('name');

            var validateFields = JSON.parse(localStorage.getItem('validate'));
            for (var i = 0; i < validateFields.length; i++) {
                if (val.includes(validateFields[i].name)) {
                    errorHandling(fieldId, 'Words not allowed', false, currentElement)
                }
            }
        }

        function emailValidate(val, currentElement) {
            var fieldId = $(currentElement).attr('name');
            var validateFields = JSON.parse(localStorage.getItem('validate'));

            if (fieldId != 'url') {
                var urlRegex = /^(ftp|http|https):\/\/[^ "]+$/;
                if (urlRegex.test(val)) {
                    var data = [];
                    for (var i = 0; i < validateFields.length; i++) {
                        if (validateFields[i].links) {
                            data.push(validateFields[i].links);
                        }
                    }

                    var validDomain = data.some(function(domain) {
                        return val.startsWith(domain);
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
    })

    function copyLink() {
        // Get the text field
        var copyText = document.getElementById("url");

        $("#copylink").html('Copied');

        navigator.clipboard.writeText('https://www.instamojo.com/EXAM360/');

        setTimeout(function() {
            $('#copylink').html('Copy');
        }, 500)
    }
</script>