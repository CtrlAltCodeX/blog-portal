<script>
    $(document).ready(function () {
        // Show / Hide Date Field Based on "Preferred Date" Select
        $('#preferredDateSelect').on('change', function () {
            if ($(this).val() === 'Yes') {
                $('#dateField').show();
            } else {
                $('#dateField').hide();
            }
        });

        $('#category_id').on('change', function () {
            let catId = $(this).val();
            let subs = @json($categories);

            let subSelect = $('#sub_category_id');
            subSelect.html('<option value="">-- Select Sub-Category --</option>');

            subs.forEach(cat => {
                if (cat.id == catId && cat.children.length > 0) {
                    cat.children.forEach(child => {
                        subSelect.append(`<option value="${child.id}">${child.name}</option>`);
                    });
                }
            });
        });

        $('#sub_category_id').on('change', function () {
            let catId = $("#category_id").val();
            let subCatId = $(this).val();
            let subs = @json($categories);

            let subSelect = $('#sub_sub_category_id');
            subSelect.html('<option value="">-- Select Sub-Sub-Category --</option>');

            subs.forEach(cat => {
                if (cat.id == catId && cat.children.length > 0) {
                    cat.children.forEach(child => {
                        if (child.id == subCatId && child.sub_children.length > 0) {
                            child.sub_children.forEach(subChild => {
                                subSelect.append(`<option value="${subChild.id}">${subChild.name}</option>`);
                            });
                        }
                    });
                }
            });
        });
    });
</script>