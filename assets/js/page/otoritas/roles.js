let table;

$(() => {
    $('#table-data').on('click', '.btn-set-default', function () {
        var tr = $(this).closest('tr');
        let { id, name } = table.row(tr).data();

        var value = $(this).data('val');

        let alert_msg = `Anda akan mengatur otoritas <b>${name}</b> sebagai otoritas default sebagai user baru`;
        if (value == 0) {
            alert_msg = 'Anda akan menghapus set default otoritas ini';
        }

        Swal.fire({
            title: 'Anda yakin?',
            html: alert_msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Atur!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(BASE_URL + 'otoritas/setDefault/' + MENU_ID, {
                    id,
                    value,
                    [TOKEN_NAME]: TOKEN_HASH
                }).done((res) => {
                    if (res.status) {
                        showSuccessToastr('Success', res.msg);
                        table.ajax.reload();
                        loadSidebar();
                    }
                }).fail(({ responseJSON: res }) => {
                    showErrorToastr('Oops', res.msg);
                })
            }
        })
    })
    $('#table-data').on('click', '.btn-remove', function () {
        var tr = $(this).closest('tr');
        let data = table.row(tr).data();

        let { id, name } = data;

        Swal.fire({
            title: 'Anda yakin?',
            html: `Anda akan menghapus otoritas "<b>${name}</b>"!`,
            footer: 'Data yang sudah dihapus tidak bisa dikembalikan kembali!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(BASE_URL + 'otoritas/delete/' + MENU_ID, { id, encrypted_menu_id: MENU_ID, [TOKEN_NAME]: TOKEN_HASH }).done((res) => {
                    if (res.status) {
                        showSuccessToastr('Success', res.msg);
                        table.ajax.reload();
                        loadSidebar();
                    }
                }).fail(({ responseJSON: res }) => {
                    showErrorToastr('Oops', res.msg);
                })
            }
        })
    })

    $('#table-data').on('click', '.btn-update', function () {
        var tr = $(this).closest('tr');
        let data = table.row(tr).data();

        $("#form-menu")[0].reset();
        clearErrorMessage();

        let { id, name } = data;

        $('#id').val(id);
        $('#name').val(name);

        $('#modal-form').modal('show');
    })

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
            },
            error: (res) => {
                console.log(res);
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
        language: options.dt,
        ajax: {
            url: BASE_URL + 'otoritas/data/' + MENU_ID,
            type: 'post',
            dataType: 'json',
            data: { [TOKEN_NAME]: TOKEN_HASH }
        },
        order: [[3, 'desc']],
        columnDefs: [{
            targets: [0, 2],
            searchable: false,
            orderable: false,
            className: 'text-center align-top w-5'
        }, {
            targets: [1],
            className: 'text-left align-top'
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
            data: 'name',
            render: (data, type, { is_default }) => {
                if (is_default) {
                    return `${data} <span class="badge badge-secondary">Otoritas Default</span>`
                }

                return data;
            }
        }, {
            data: 'id',
            render: (id, type, { is_default }) => {
                let button_edit = '', button_delete = '', button_access = '', button_set_default = '';
                if (UPDATE_ACCESS) {
                    button_set_default = $('<button>', {
                        html: $('<i>', {
                            class: () => {
                                if (is_default) {
                                    return 'bx bx-x'
                                }

                                return 'bx bx-check'
                            }
                        }).prop('outerHTML'),
                        class: `btn ${is_default == 1 ? 'btn-outline-danger' : 'btn-outline-secondary'} btn-set-default`,
                        type: 'button',
                        'data-id': id,
                        'data-val': (is_default ? 0 : 1),
                        'data-toggle': 'tooltip',
                        'data-placement': 'top',
                        title: () => {
                            if (is_default == 1) {
                                return 'Hilangkan status default dari otoritas ini'
                            }

                            return 'Set default otoritas ini'
                        }
                    })

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

                    button_access = $('<a>', {
                        html: $('<i>', {
                            class: 'bx bx-check-shield'
                        }).prop('outerHTML'),
                        class: 'btn btn-outline-success btn-access',
                        'data-toggle': 'tooltip',
                        'data-placement': 'top',
                        title: 'Ubah hak akses otoritas',
                        href: BASE_URL + 'otoritas/hak_akses/' + MENU_ID + '/' + id
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
                    html: [button_set_default, button_access, button_edit, button_delete]
                }).prop('outerHTML');
            }
        }, {
            data: 'created_at'
        }]
    })
})