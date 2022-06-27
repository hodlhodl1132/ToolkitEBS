<script>
    $(document).ready(function() {
        // Inialize the sliders
        $( "#poll_duration_slider" ).slider({
            range: "min",
            min: 1,
            max: 5,
            value: $('input[name="duration"]').val(),
            slide: function( event, ui ) {
                $("#poll_duration_label").text(ui.value)
                $('input[name="duration"]').val(ui.value)
            }
        });
        $( "#poll_interval_slider" ).slider({
            range: "min",
            min: 1,
            max: 30,
            value: $('input[name="interval"]').val(),
            slide: function( event, ui ) {
                $("#poll_interval_label").text(ui.value)
                $('input[name="interval"]').val(ui.value)
            }
        });
        $("#poll_interval_label").text($('input[name="interval"]').val())
        $("#poll_duration_label").text($('input[name="duration"]').val())

        $('#open-polls-button').on('click', function() {
            $('.ui.modal').modal('show')
        })
    })
</script>