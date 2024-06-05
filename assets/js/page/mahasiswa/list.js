let table;
$(() => {
	$("#table-data").on("click", ".btn-remove", function () {
		var tr = $(this).closest("tr");
		let data = table.row(tr).data();

		let { id, nama } = data;

		Swal.fire({
			title: "Anda yakin?",
			html: `Anda akan menghapus mahasiswa "<b>${nama}</b>"!`,
			footer: "Data yang sudah dihapus tidak bisa dikembalikan kembali!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#d33",
			cancelButtonColor: "#3085d6",
			confirmButtonText: "Ya, Hapus!",
			cancelButtonText: "Batal",
		}).then((result) => {
			if (result.isConfirmed) {
				$.post(BASE_URL + "mahasiswa/deleteMahasiswa/" + MENU_ID, {
					id,
					[TOKEN_NAME]: TOKEN_HASH,
				})
					.done((res) => {
						console.log(res);
						if (res.status) {
							showSuccessToastr("Success", "Data berhasil dihapus");
							table.ajax.reload();
							loadSidebar();
						}
					})
					.fail((res) => {
						console.log(res);
						showErrorToastr("Oops", "Terjadi kesalahan di server");
					});
			}
		});
	});

	$("#form-ubah-mahasiswa").on("submit", function (e) {
		e.preventDefault();

		let data = new FormData(this);
		data.append(TOKEN_NAME, TOKEN_HASH);

		$.ajax({
			url: $(this).attr("action"),
			type: $(this).attr("method"),
			data: data,
			dataType: "json",
			processData: false,
			contentType: false,
			beforeSend: () => {
				swalProcessing();
				$(this).find(".btn-submit").fadeOut();
			},
			success: (res) => {
				$(this).find(".btn-submit").fadeIn();

				showSuccessToastr("Success", "Data berhasil diperbarui");
				$(this)[0].reset();
				$("#modal-ubah-mahasiswa").modal("hide");
				table.ajax.reload();
			},
			error: (res) => {
				if (res.status == 422) {
					generateErrorMessage({ errors: res.responseJSON.data }, true);
				}
				$(this).find(".btn-submit").fadeIn();
				showErrorToastr("Oops", "Terjadi kesalahan di server");
				table.ajax.reload();
			},
		}).always(() => {
			Swal.close();
		});
	});

	$("#table-data").on("click", ".btn-update", function () {
		var tr = $(this).closest("tr");
		var data = table.row(tr).data();

		$("#form-ubah-mahasiswa")[0].reset();
		clearErrorMessage();

		let { id, nim, nama } = data;

		$("input[name=id]").val(id);
		$("#ubah-nim").val(nim);
		$("#ubah-nama").val(nama);

		$("#modal-ubah-mahasiswa").modal("show");
	});

	$("#form-tambah-mahasiswa").on("submit", function (e) {
		e.preventDefault();

		let data = new FormData(this);
		data.append(TOKEN_NAME, TOKEN_HASH);

		$.ajax({
			url: $(this).attr("action"),
			type: $(this).attr("method"),
			data: data,
			dataType: "json",
			processData: false,
			contentType: false,
			beforeSend: () => {
				swalProcessing();
				$(this).find(".btn-submit").fadeOut();
			},
			success: (res) => {
				$(this).find(".btn-submit").fadeIn();
				showSuccessToastr("Success", "Data berhasil ditambahkan");
				$(this)[0].reset();
				$("#modal-tambah-mahasiswa").modal("hide");
				table.ajax.reload();
			},
			error: (res) => {
				if (res.status == 422) {
					generateErrorMessage({ errors: res.responseJSON.data }, false);
				}
				$(this).find(".btn-submit").fadeIn();
				showErrorToastr("Oops", "Terjadi kesalahan di server");
				table.ajax.reload();
			},
		}).always(() => {
			Swal.close();
		});
	});

	$(".btn-tambah").on("click", function () {
		clearErrorMessage();
		$("#form-tambah-mahasiswa")[0].reset();
		$("#modal-tambah-mahasiswa").modal("show");
	});

	table = $("#table-data").DataTable({
		serverSide: true,
		processing: true,
		language: options.dt,
		ajax: {
			url: BASE_URL + "mahasiswa/data/" + MENU_ID,
			type: "post",
			dataType: "json",
			data: { [TOKEN_NAME]: TOKEN_HASH },
		},
		order: [[3, "desc"]],
		columnDefs: [
			{
				targets: [0, 3],
				searchable: false,
				orderable: false,
				className: "text-center align-top w-5",
			},
			{
				targets: [1, 2, 3],
				className: "text-left align-top",
			},
			{
				targets: [4],
				visible: false,
			},
		],
		columns: [
			{
				data: "no",
				render: (data) => {
					return data + ".";
				},
			},
			{
				data: "nim",
			},
			{
				data: "nama",
			},
			{
				data: "id",
				render: (id, type) => {
					const button_edit = $("<button>", {
						html: $("<i>", {
							class: "bx bx-pencil",
						}).prop("outerHTML"),
						class: "btn btn-outline-dark btn-update",
						type: "button",
						"data-id": id,
						"data-toggle": "tooltip",
						"data-placement": "top",
						title: "Ubah Data",
					});

					const button_delete = $("<button>", {
						html: $("<i>", {
							class: "bx bx-trash",
						}).prop("outerHTML"),
						class: "btn btn-outline-danger btn-remove",
						"data-id": id,
						"data-toggle": "tooltip",
						"data-placement": "top",
						title: "Hapus Data",
					});

					return $("<div>", {
						class: "btn-group",
						html: () => {
							let arr = [];

							if (UPDATE_ACCESS) {
								arr.push(button_edit);
							}

							if (DELETE_ACCESS) {
								arr.push(button_delete);
							}

							return arr;
						},
					}).prop("outerHTML");
				},
			},
			{
				data: "created_at",
			},
		],
	});
});
