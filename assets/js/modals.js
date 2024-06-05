const openStandardModal = async (url = null, payloads = null) => {
    let modal = $('#standard-modal');
    let container = modal.find('.standard-modal-dialog');

    let data = await getModal(url, payloads);

    Swal.close();

    container.html(data.modal);
    modal.modal('show');

    return { data, payloads };
}

const openLargeModal = async (url = null, payloads = null) => {
    let modal = $('#large-modal');
    let container = modal.find('.large-modal-dialog');

    let data = await getModal(url, payloads);

    Swal.close();

    container.html(data.modal);
    modal.modal('show');

    return { data, payloads };
}

const openExtraLargeModal = async (url = null, payloads = null) => {
    let modal = $('#extra-large-modal');
    let container = modal.find('.extra-large-modal-dialog');

    let data = await getModal(url, payloads);

    Swal.close();

    container.html(data.modal);
    modal.modal('show');

    return { data, payloads };
}

const getModal = async (url, payloads) => {
    swalProcessing('Memuat data');

    return await $.get(url, payloads).fail(({ status, responseJSON: res }) => {
        switch (status) {
            case 400:
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan',
                    text: res.msg || 'Terjadi kesalahan diserver saat memuat data'
                })
                break;

            case 404:
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan',
                    text: 'Halaman tidak ditemukan'
                })
                break;

            default:
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi kesalahan',
                    text: 'Terjadi kesalahan diserver saat memuat data'
                })
                break;
        }
    })
}