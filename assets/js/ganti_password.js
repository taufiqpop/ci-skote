var modalGantiPassword = $('#modal-ganti-password');
var formGantiPassword = $('#form-ganti-password');
$(() => {
    // checkPluginExist();
})

$('#modal-ganti-password').on('hidden.bs.modal', function () {
    $('#form-ganti-password')[0].reset();
});

formGantiPassword.on('submit', function (e) {
    Swal.fire({
        title: '',
        html: "Pastikan isian Anda sudah benar. <br>Klik Simpan untuk melanjutkan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            e.preventDefault();

            var data = new FormData(this);
            data.append(TOKEN_NAME, TOKEN_HASH);

            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: () => {
                    swalProcessing();
                    formGantiPassword.find('.btn-save-password').slideUp();
                },
                success: (res) => {
                    Swal.fire({
                        icon: 'success',
                        title: res.msg,
                        footer: 'Sistem akan logout.',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        timer: 1500,
                    }).then(() => {
                        location.reload();
                    })
                },
                error: (res) => {
                    if (res.status == 422) {
                        generateErrorMessage({ errors: res.responseJSON.data }, false);
                    }
                    formGantiPassword.find('.btn-save-password').slideDown();
                    Swal.fire({
                        icon: 'error',
                        title: res.responseJSON.msg,
                    });
                }
            })
        }
    })
})