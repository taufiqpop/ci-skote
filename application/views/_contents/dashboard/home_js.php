<script>
    $('.btn-test-standard-modal').on('click', async function() {
        let modal = await openStandardModal(`${BASE_URL}dashboard/testStandardModal`, {
            id: '12'
        });
    })

    $('.btn-test-large-modal').on('click', async function() {
        let modal = await openLargeModal(`${BASE_URL}dashboard/testStandardModal`, {
            id: '12'
        });
    })

    $('.btn-test-extra-large-modal').on('click', async function() {
        let modal = await openExtraLargeModal(`${BASE_URL}dashboard/testStandardModal`, {
            id: '12'
        });
    })
</script>