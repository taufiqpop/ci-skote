let table;
$(() => {
    $('#table-data').on('click', '.btn-remove', function () {
        var tr = $(this).closest('tr');
        let data = table.row(tr).data();

        let { id, name } = data;

        Swal.fire({
            title: 'Anda yakin?',
            html: `Anda akan menghapus menu "<b>${name}</b>"!`,
            footer: 'Data yang sudah dihapus tidak bisa dikembalikan kembali!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(BASE_URL + 'menu/deleteGroupMenu/' + MENU_ID, {
                    id,
                    encrypted_menu_id: MENU_ID,
                    [TOKEN_NAME]: TOKEN_HASH
                }).done((res) => {
                    console.log(res);
                    if (res.status) {
                        showSuccessToastr('Success', 'Data berhasil dihapus');
                        table.ajax.reload();
                        loadSidebar();
                    }
                }).fail((res) => {
                    console.log(res);
                    showErrorToastr('Oops', 'Terjadi kesalahan di server');
                })
            }
        })
    })

    $('#table-data').on('click', '.btn-update', function () {
        var tr = $(this).closest('tr');
        let data = table.row(tr).data();

        $('#form-menu')[0].reset();
        clearErrorMessage();

        let { id, name } = data;

        $('#id').val(id);
        $('#name').val(name);

        $('#modal-form').modal('show');
    });

    $('#form-menu').on('submit', function (e) {
        e.preventDefault();

        let data = new FormData(this);

        data.append('encrypted_menu_id', MENU_ID);
        data.append(TOKEN_NAME, TOKEN_HASH);

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: () => {
                $(this).find('.btn-submit').fadeOut();
            },
            success: (res) => {
                $(this).find('.btn-submit').fadeIn();
                showSuccessToastr('Success', 'Data berhasil ditambahkan');
                $(this)[0].reset();
                $('#modal-form').modal('hide');
                table.ajax.reload();

                loadSidebar();
            },
            error: (res) => {
                if (res.status == 422) {
                    generateErrorMessage({ errors: res.responseJSON.data }, false);
                }
                $(this).find('.btn-submit').fadeIn();
                showErrorToastr('Oops', 'Terjadi kesalahan di server');
                table.ajax.reload();
            }
        })
    })

    $('.btn-tambah').on('click', function () {
        clearErrorMessage();
        $('#form-menu')[0].reset();
        $('#modal-form').modal('show');
    });

    table = $('#table-data').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: BASE_URL + 'menu/groupData/' + MENU_ID,
            type: 'post',
            dataType: 'json',
            data: { [TOKEN_NAME]: TOKEN_HASH }
        },
        order: [[3, 'desc']],
        language: options.dt,
        columnDefs: [{
            targets: [0, 2],
            searchable: false,
            orderable: false,
            className: 'text-center align-top w-5'
        }, {
            targets: [1],
            className: 'text-left align-top',
        }, {
            targets: [3],
            visible: false,
        }],
        columns: [{
            data: 'no',
            render: (data) => {
                return data + '.';
            }
        }, {
            data: 'name'
        }, {
            data: 'id',
            render: (id, type, row) => {
                let button_edit = '', button_delete = '';
                if (UPDATE_ACCESS) {
                    button_edit = $('<button>', {
                        html: $('<i>', {
                            class: 'bx bx-pencil'
                        }).prop('outerHTML'),
                        class: 'btn btn-outline-dark btn-update',
                        type: 'button',
                        'data-id': id,
                        'data-toggle': 'tooltip',
                        'data-placement': 'top',
                        title: 'Ubah Data'
                    })
                }

                if (DELETE_ACCESS) {
                    button_delete = $('<button>', {
                        html: $('<i>', {
                            class: 'bx bx-trash'
                        }).prop('outerHTML'),
                        class: 'btn btn-outline-danger btn-remove',
                        'data-id': id,
                        'data-toggle': 'tooltip',
                        'data-placement': 'top',
                        title: 'Hapus Data'
                    });
                }

                return $('<div>', {
                    class: 'btn-group',
                    html: [button_edit, button_delete]
                }).prop('outerHTML');
            }
        }, {
            data: 'created_at'
        }]
    });
})