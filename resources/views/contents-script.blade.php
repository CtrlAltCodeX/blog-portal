<script>
    $(document).ready(function() {
        $(document).on('change', '.category_id', function() {

            let catId = $(this).val();
            let allCategories = @json($categories);

            let row = $(this).closest('tr');

            let subSelect = row.find('.sub_category_id');
            let subSubSelect = row.find('.sub_sub_category_id');

            // Reset dropdowns
            subSelect.html('<option value="">-- Select Sub-Category --</option>');
            subSubSelect.html('<option value="">-- Select Sub Sub-Category --</option>');

            // Selected category
            let selectedCategory = allCategories.find(c => c.id == catId);

            if (selectedCategory && selectedCategory.children?.length > 0) {

                selectedCategory.children.forEach(child => {

                    // SHOW ONLY REAL SUB-CATEGORY (sub_parent_id MUST be null)
                    if (child.sub_parent_id === null) {
                        subSelect.append(
                            `<option value="${child.id}">${child.name}</option>`
                        );
                    }

                });
            }

        });



        // -----------------------------
        // 2. On Sub Category Change
        // -----------------------------
        $(document).on('change', '.sub_category_id', function() {

            let row = $(this).closest('tr');

            let catId = row.find('.category_id').val();
            let subCatId = $(this).val();
            let allCategories = @json($categories);

            let subSubSelect = row.find('.sub_sub_category_id');

            subSubSelect.html('<option value="">-- Select Sub Sub-Category --</option>');

            // Find selected category
            let selectedCategory = allCategories.find(c => c.id == catId);

            if (!selectedCategory) return;

            // Find selected sub category inside selected category
            let selectedSub = selectedCategory.children.find(ch => ch.id == subCatId);
            if (!selectedSub) return;

            // Load sub-sub categories
            if (selectedSub.sub_children && selectedSub.sub_children.length > 0) {

                selectedSub.sub_children.forEach(subChild => {
                    subSubSelect.append(`<option value="${subChild.id}">${subChild.name}</option>`);
                });

            }

        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });



        let rowIndex = 1;

        $('#add-promotional-row').click(function() {
            $.ajax({
                url: "{{ route('promotional.row') }}",
                data: {
                    index: rowIndex
                },
                success: function(html) {
                    $('#promotional-body').append(html);
                    rowIndex++;
                }
            });
        });

        $('#add-row').click(function() {
            $.ajax({
                url: "{{ route('content.row') }}",
                data: {
                    index: rowIndex
                },
                success: function(html) {
                    $('#content-body').append(html);
                    rowIndex++;
                }
            });
        });

    });
</script>