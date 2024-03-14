<script>
    $(document).ready(function() {
        $("#multipleFiles").change(function() {
            $('#multiImagesDownload').show();
        });

        $("#downloadMultipleImage").click(function() {
            $("#multipleImagesform").submit();
        });

        $("#method").change(function() {
            $("#form").submit();
        })

        $('input[name="maker"]').change(function() {
            $("#form").submit();
        })

        $(".copy").click(function() {
            navigator.clipboard.writeText($(this).attr('id'));
            alert('Copied');
        });
    })
</script>