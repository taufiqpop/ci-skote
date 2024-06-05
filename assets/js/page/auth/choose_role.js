$(() => {
    $('#role_id').on('change', function () {
        var val = $(this).val();

        $('#form-choose-role').submit();
    })
})