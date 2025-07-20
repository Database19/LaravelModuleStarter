document.body.addEventListener('click', function (event) {
            // Cek apakah elemen yang diklik memiliki class 'confirm-delete-button'
            if (event.target.matches('.confirm-delete-button')) {
                event.preventDefault();

                const form = event.target.closest('form'); // Cari form terdekat

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Jika dikonfirmasi, submit form
                    }
                });
            }
        });
