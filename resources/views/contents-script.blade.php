<script>
    $(document).ready(function () {

     
        $(document).on('change', '.category_id', function () {

            let catId = $(this).val();
            let subs = @json($categories);

            let row = $(this).closest('tr');

            let subSelect = row.find('.sub_category_id');
            let subSubSelect = row.find('.sub_sub_category_id');

            subSelect.html('<option value="">-- Select Sub-Category --</option>');
            subSubSelect.html('<option value="">-- Select Sub Sub-Category --</option>');

            subs.forEach(cat => {
                if (cat.id == catId && cat.children.length > 0) {
                    cat.children.forEach(child => {
                        subSelect.append(`<option value="${child.id}">${child.name}</option>`);
                    });
                }
            });

        });

        $(document).on('change', '.sub_category_id', function () {

            let row = $(this).closest('tr');

            let catId = row.find('.category_id').val();
            let subCatId = $(this).val();
            let subs = @json($categories);

            let subSubSelect = row.find('.sub_sub_category_id');

            subSubSelect.html('<option value="">-- Select Sub Sub-Category --</option>');

            subs.forEach(cat => {
                if (cat.id == catId && cat.children.length > 0) {

                    cat.children.forEach(child => {
                        if (child.id == subCatId && child.sub_children.length > 0) {

                            child.sub_children.forEach(subChild => {
                                subSubSelect.append(`<option value="${subChild.id}">${subChild.name}</option>`);
                            });

                        }
                    });

                }
            });

        });

         $(document).on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });

    

     let rowIndex = 1;

    $('#add-row').click(function () {
        $.ajax({
            url: "{{ route('promotional.row') }}",
            data: { index: rowIndex },
            success: function (html) {
                $('#promo-body').append(html);
                rowIndex++;
            }
        });
    });

    $('#add-row').click(function () {
        $.ajax({
            url: "{{ route('content.row') }}",
            data: { index: rowIndex },
            success: function (html) {
                $('#content-body').append(html);
                rowIndex++;
            }
        });
    });

    });
</script>
