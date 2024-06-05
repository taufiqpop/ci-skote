(function ($) {
    $.fn.inputFilter = function (inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = "";
            }
        });
    };
}(jQuery));

const getMeta = (meta_name) => {
    var meta = $(`meta[name=${meta_name}]`);

    return meta.attr('content');
}

const options = {
    /** Config datepicker */
    dt: {
        processing: 'Memuat data',
        paginate: {
            first: '<<',
            previous: '<',
            next: '>',
            last: '>>'
        },
        lengthMenu: 'Menampilkan _MENU_ data',
        search: 'Pencarian: ',
        info: 'Menampilkan _START_ ke _END_ dari _TOTAL_ data',
        infoEmpty: 'Kosong',
        infoFiltered: '(Tersaring dari _MAX_ data)',
        emptyTable: 'Data kosong'
    },
    /** Config datepicker */
    date: {
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true,
        orientation: 'bottom'
    }
};

const makeId = (length) => {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() *
            charactersLength));
    }
    return result;
}

const number_format = (val) => {
    if (val != null) {
        val = val.toString().replace(/,/g, ''); //remove existing commas first
        var valSplit = val.split('.'); //then separate decimals

        while (/(\d+)(\d{3})/.test(valSplit[0].toString())) {
            valSplit[0] = valSplit[0].toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
        }

        if (valSplit.length == 2) { //if there were decimals
            val = valSplit[0] + "." + valSplit[1]; //add decimals back
        } else {
            val = valSplit[0];
        }

        return val;
    } else {
        return '-';
    }
}

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": 300,
    "hideDuration": 1000,
    "timeOut": 5000,
    "extendedTimeOut": 1000,
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

const showSuccessToastr = (title, mssg = null) => {
    toastr.success(mssg, title);
}

const showErrorToastr = (title, mssg = null) => {
    toastr.error(mssg, title);
}

const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

const checkAccess = (access_name) => {
    let value = parseInt(getMeta(`${access_name}_access`));

    if (value) return true;

    return false;
}

const swalProcessing = (msg = null) => {
    Swal.fire({
        title: '',
        text: msg || 'Sedang memproses....',
        allowOutsideClick: false,
        showCancelButton: false,
        showConfirmButton: false,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

const generateErrorMessage = (res, is_update = false) => {
    for (const key in res.errors) {

        if (Object.hasOwnProperty.call(res.errors, key)) {
            const element = res.errors[key];

            let error_container = $('.error-' + key);
            if (is_update) {
                error_container = $('.error-update-' + key);
            }

            if (element.length > 0) {
                $('#' + key).removeClass('is-valid').addClass('is-invalid');

                error_container.empty().append(element);
            } else {
                error_container.empty();
                $('#' + key).removeClass('is-invalid').addClass('is-valid');
            }
        }
    }
}

const clearErrorMessage = () => {
    $('.is-invalid').removeClass('is-invalid');
    $('.is-valid').removeClass('is-valid');
    $('.error').empty();
}

window.BASE_URL = getMeta('base_url');
window.ROLE_NAME = getMeta('role_name');
window.MENU_ID = getMeta('menu_id');
window.MENU_ACTIVE = getMeta('menu_active').toLowerCase();
window.MENU_OPEN = getMeta('menu_open');

window.CREATE_ACCESS = checkAccess('tambah') || false;
window.UPDATE_ACCESS = checkAccess('edit') || false;
window.DELETE_ACCESS = checkAccess('delete') || false;

window.TOKEN_NAME = getMeta('token_name');
window.TOKEN_HASH = getMeta('token_hash');

$(() => {
    $('[data-toggle="tooltip"]').tooltip()

    $('[data-toggle="tooltip"]').tooltip({
        trigger: 'hover'
    })

    $('[rel="tooltip"]').on('click', function () {
        $(this).tooltip('hide')
    })

    $('#pilih-tahun').datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        defaultViewDate: {
            year: $('input[name=current_year]').val()
        }
    }).on('changeDate', function (e) {
        let currYear = String(e.date).split(" ")[3];

        $.post(BASE_URL + 'dashboard/changeYear', { year: currYear }).done((res) => {
            location.reload();
        }).fail(({ responseJSON }) => {
            showErrorToastr('oops', responseJSON);
        })
    });

    $('.link-logout').on('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Anda akan keluar dari sistem?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                sessionStorage.removeItem('menu_data');

                location.href = $(this).attr('href');
            }
        })
    })
})
