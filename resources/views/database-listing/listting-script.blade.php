<script>
    $(document).ready(function() {
        $('#basic-datatable').DataTable({
            "paging": false,
            "order": [[0, "asc"]]
        });

        $("#category").on("change", function() {
            $("#form").submit();
        })
        
        $("#paging").on("change", function() {
            $("#pagingform").submit();
        });
        
        $("#status").change(function() {
            $("#formStatus").submit();
        });

        $("#user").change(function() {
            $("#form").submit();
        });

        $("#issue_dropdown").change(function() {
            var val = $(this).val();

            switch (val) {
                case "1":
                    $(".astric").hide();
                    $(".accurate").show();
                    break;

                case "2":
                    $(".accurate").show();
                    $(".astric").show();
                    break;

                case "3":
                    $(".accurate").hide();
                    $(".astric").show();
                    break;

                default:
                    // Optional: reset or hide both if none selected
                    $(".accurate").hide();
                    $(".astric").hide();
                    break;
            }
        });

        var ids = [];
        $(".checkbox-update").click(function() {
            if ($(this).prop('checked')) {
                ids.push($(this).val());
            } else {
                var index = ids.indexOf($(this).val());
                if (index !== -1) {
                    ids.splice(index, 1);
                }
            }
        });

        $('.check-all').click(function() {
            $(".checkbox-update").each(function() {
                if ($('.check-all').prop('checked') == true) {
                    if ($(this).parent().parent().is(":visible")) {
                        $(this).prop('checked', true);
                        ids.push($(this).val());
                    }
                } else {
                    $(this).prop('checked', false);
                    var index = ids.indexOf($(this).val());
                    if (index !== -1) {
                        ids.splice(index, 1);
                    }
                }
            });
        });



        $('.thumb').hover(
            function(e) {
                var full = $(this).data('full');
                if (!full) {
                    full = '/dummy.jpg';
                }
                console.log(full);
                $('#imagePreviewImg').attr('src', full);
                $('#imagePreview').show();
            },
            function(e) {
                $('#imagePreview').hide();
            }
        );

        $('.thumb').mousemove(function(e) {
            // follow mouse cursor
            $('#imagePreview').css({
                top: e.clientY - 250 + 'px',
                left: e.clientX + 20 + 'px'
            });
        });
    });
</script>