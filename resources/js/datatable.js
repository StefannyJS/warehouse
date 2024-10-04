import Swal from 'sweetalert2';
import 'datatables.net';
import 'datatables.net-dt/css/jquery.dataTables.css';

// Fungsi inisialisasi DataTable
export function initializeDataTable(selector, url, columns) {
    console.log('Inisialisasi DataTable:', selector, url, columns);
    $(selector).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: 'GET',
        },
        responsive: true,
        columns: columns,
        autoWidth: false,
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                if (column.index() === 0) {
                    $(column.header()).find('.filter').remove();
                }
            });
        },
    });
}

// Fungsi untuk menonaktifkan record
export function disableRecord(deleteUrl, tableSelector) {
    console.log('Delete URL:', deleteUrl);
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dinonaktifkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, nonaktifkan!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Response:', response);
                    Swal.fire('Berhasil!', response.success, 'success');
                    $(tableSelector).DataTable().ajax.reload();
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    Swal.fire(
                        'Gagal!', xhr.responseJSON.error, 'error'
                    );
                }
            });
        }
    });
}

// Fungsi untuk menghapus record
export function deleteRecord(deleteUrl, tableSelector) {
    console.log('Delete URL:', deleteUrl);
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                success: function(response) {
                    console.log('Response:', response);
                    Swal.fire(
                        'Berhasil!',
                        'Data berhasil dihapus.',
                        'success'
                    );
                    $(tableSelector).DataTable().ajax.reload(null, false);
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    Swal.fire(
                        'Gagal!',
                        xhr.responseJSON ? xhr.responseJSON.error : 'Terjadi kesalahan saat menghapus data.',
                        'error'
                    );
                }
            });
        }
    });
}

function renderStatus(data) {
    return data == 1 ? 
        '<div class="flex justify-center items-center"><i class="fas fa-check-circle text-green-500"></i></div>' : 
        '<div class="flex justify-center items-center"><i class="fas fa-times-circle text-red-500"></i></div>';
}