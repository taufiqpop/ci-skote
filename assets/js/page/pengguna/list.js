let table;
$(() => {
    $('#form-ubah-role').on('submit', function (e) {
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
                $(this).find('.btn-submit').fadeOut();
            },
            success: (res) => {
                $(this).find('.btn-submit').fadeIn();
                table.ajax.reload();
                showSuccessToastr('Success', 'Data berhasil ditambahkan');
                $('.checkbox-role').prop('checked', false);
                $('#modal-ubah-role').modal('hide');
            },
            error: (res) => {
                $(this).find('.btn-submit').fadeIn();
                showErrorToastr('Oops', 'Terjadi kesalahan di server');
                table.ajax.reload();
            }
        }).always(() => {
            Swal.close();
        })
    })

    $('#table-data').on('click', '.btn-roles', function () {
        var tr = $(this).closest('tr');
        let data = table.row(tr).data();

        let { id, roles, full_name } = data;

        $('.checkbox-role').prop('checked', false);
        for (const role of roles) {
            $('#roles-' + role).prop('checked', true);
        }
        $('input[name=id]').val(id);
        $('.full_name').text(full_name);

        $('#modal-ubah-role').modal('show');
    })

    $('#table-data').on('click', '.btn-remove', function () {
        var tr = $(this).closest('tr');
        let data = table.row(tr).data();

        let { id, full_name } = data;

        Swal.fire({
            title: 'Anda yakin?',
            html: `Anda akan menghapus user "<b>${full_name}</b>"!`,
            footer: 'Data yang sudah dihapus tidak bisa dikembalikan kembali!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(BASE_URL + 'users/deleteUser/' + MENU_ID, { id, [TOKEN_NAME]: TOKEN_HASH }).done((res) => {
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

    $('#table-data').on('click', '.btn-reset', function () {
        var id = $(this).data('id');
        var tr = $(this).closest('tr');
        var data = table.row(tr).data();

        let { username, full_name } = data;

        Swal.fire({
            title: 'Anda yakin?',
            html: `Anda akan mereset password untuk pengguna "<b>${full_name}</b>"!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(BASE_URL + 'users/resetPassword/' + MENU_ID, { id, [TOKEN_NAME]: TOKEN_HASH }).done((res) => {
                    if (res.status) {
                        showSuccessToastr('Success', 'Password berhasil direset');
                        table.ajax.reload();
                        loadSidebar();
                    }
                }).fail((res) => {
                    showErrorToastr('Oops', 'Terjadi kesalahan di server');
                })
            }
        })
    })

    $('#table-data').on('change', '.change-active', function () {
        var id = $(this).data('user_id');
        var checked = $(this).prop('checked');

        $.post(BASE_URL + 'users/changeUserActive/' + MENU_ID, { id, val: (checked ? 1 : 0), [TOKEN_NAME]: TOKEN_HASH }).done((res) => {
            table.ajax.reload();
        }).fail((res) => {
            showErrorToastr('Oops', 'Terjadi kesalahan');
            table.ajax.reload();
        })
    })

    $('#form-ubah-user').on('submit', function (e) {
        e.preventDefault();

        let data = new FormData(this);
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
                $(this).find('.btn-submit').fadeOut();
            },
            success: (res) => {
                $(this).find('.btn-submit').fadeIn();

                showSuccessToastr('Success', 'Data berhasil diperbarui');
                $(this)[0].reset();
                $('#modal-ubah-user').modal('hide');
                table.ajax.reload();
            },
            error: (res) => {
                if (res.status == 422) {
                    generateErrorMessage({ errors: res.responseJSON.data }, true);
                }
                $(this).find('.btn-submit').fadeIn();
                showErrorToastr('Oops', 'Terjadi kesalahan di server');
                table.ajax.reload();
            }
        }).always(() => {
            Swal.close();
        })
    })

    $('#table-data').on('click', '.btn-update', function () {
        var tr = $(this).closest('tr');
        var data = table.row(tr).data();

        $('#form-ubah-user')[0].reset();
        clearErrorMessage();

        let { id, username, full_name } = data;

        $('input[name=id]').val(id);
        $('#ubah-username').val(username);
        $('#ubah-full_name').val(full_name);

        $('#modal-ubah-user').modal('show');
    })

    $('#form-tambah-user').on('submit', function (e) {
        e.preventDefault();

        let data = new FormData(this);
        data.append(TOKEN_NAME, TOKEN_HASH)

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: () => {
                swalProcessing();
                $(this).find('.btn-submit').fadeOut();
            },
            success: (res) => {
                $(this).find('.btn-submit').fadeIn();
                showSuccessToastr('Success', 'Data berhasil ditambahkan');
                $(this)[0].reset();
                $('#modal-tambah-user').modal('hide');
                table.ajax.reload();
            },
            error: (res) => {
                if (res.status == 422) {
                    generateErrorMessage({ errors: res.responseJSON.data }, false);
                }
                $(this).find('.btn-submit').fadeIn();
                showErrorToastr('Oops', 'Terjadi kesalahan di server');
                table.ajax.reload();
            }
        }).always(() => {
            Swal.close();
        })
    })

    $('.btn-tambah').on('click', function () {
        clearErrorMessage();
        $('#form-tambah-user')[0].reset();
        $('#modal-tambah-user').modal('show');
    })

    table = $('#table-data').DataTable({
        serverSide: true,
        processing: true,
        language: options.dt,
        ajax: {
            url: BASE_URL + 'users/data/' + MENU_ID,
            type: 'post',
            dataType: 'json',
            data: { [TOKEN_NAME]: TOKEN_HASH }
        },
        order: [[6, 'desc']],
        columnDefs: [{
            targets: [0, 4, 5],
            searchable: false,
            orderable: false,
            className: 'text-center align-top w-5'
        }, {
            targets: [1, 2, 3],
            className: 'text-left align-top'
        }, {
            targets: [6],
            visible: false,
        }],
        columns: [{
            data: 'no',
            render: (data) => {
                return data + '.'
            }
        }, {
            data: 'username',
        }, {
            data: 'full_name',
            render: (data, type, { its_you }) => {
                if (its_you == true) {
                    return `${data} <br> <span class="badge badge-warning">Ini anda</span>`
                }
                return data;
            }
        }, {
            data: 'role_names',
            render: (data, type, row) => {
                if (data.length) {
                    let ul = $('<ul>');
                    for (const item of data) {
                        ul.append($('<li>', {
                            text: item
                        }))
                    }

                    return $('<div>', {
                        html: ul,
                        style: 'margin-left: -29px;'
                    }).prop('outerHTML');
                }
                return '-';
            }
        }, {
            data: 'active',
            render: (data, type, row) => {
                let { id } = row;
                return `
                <div class="custom-control custom-switch mb-3" dir="ltr">
                    <input type="checkbox" class="custom-control-input change-active" name="active-${id}" id="active-${id}" data-user_id="${id}" ${data == '1' ? 'checked' : ''}>
                    <label class="custom-control-label" for="active-${id}">${data == '1' ? 'Aktif' : 'Nonaktif'}</label>
                </div>
                `;
            }
        }, {
            data: 'id',
            render: (id, type, { its_you }) => {
                const button_reset_pass = $('<button>', {
                    html: $('<i>', {
                        class: 'bx bx-key'
                    }),
                    class: 'btn btn-outline-warning btn-reset',
                    type: 'button',
                    'data-id': id,
                    'data-toggle': 'tooltip',
                    'data-placement': 'top',
                    title: 'Reset Password',
                });

                const button_roles = $('<button>', {
                    html: $('<i>', {
                        class: 'bx bx-shield-quarter'
                    }),
                    class: 'btn btn-outline-success btn-roles',
                    type: 'button',
                    'data-id': id,
                    'data-toggle': 'tooltip',
                    'data-placement': 'top',
                    title: 'Ubah otoritas pengguna'
                });

                const button_edit = $('<button>', {
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

                const button_delete = $('<button>', {
                    html: $('<i>', {
                        class: 'bx bx-trash'
                    }).prop('outerHTML'),
                    class: 'btn btn-outline-danger btn-remove',
                    'data-id': id,
                    'data-toggle': 'tooltip',
                    'data-placement': 'top',
                    title: 'Hapus Data'
                });

                return $('<div>', {
                    class: 'btn-group',
                    html: () => {

                        let arr = [];

                        if (UPDATE_ACCESS) {
                            arr.push(button_reset_pass);
                            arr.push(button_roles);
                            arr.push(button_edit);
                        }

                        if (DELETE_ACCESS) {
                            arr.push(button_delete);
                        }

                        return arr;
                    }
                }).prop('outerHTML');
            }
        }, {
            data: 'created_at'
        }]

    })
})