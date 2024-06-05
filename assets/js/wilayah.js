const getKabupaten = async (kode) => {
    return await $.get(BASE_URL + 'wilayah/getKabupaten', { kode }).fail(({ responseJSON }) => {
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            message: responseJSON?.msg || 'Terjadi Kesalahan',
            willClose: () => {
                location.reload();
            }
        })
    })
}

const getKecamatan = async (kode) => {
    return await $.get(BASE_URL + 'wilayah/getKecamatan', { kode }).fail(({ responseJSON }) => {
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            message: responseJSON?.msg || 'Terjadi Kesalahan',
            willClose: () => {
                location.reload();
            }
        })
    })
}

const getKelurahan = async (kode) => {
    return await $.get(BASE_URL + 'wilayah/getKelurahan', { kode }).fail(({ responseJSON }) => {
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            message: responseJSON?.msg || 'Terjadi Kesalahan',
            willClose: () => {
                location.reload();
            }
        })
    })
}