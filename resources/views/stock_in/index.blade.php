<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Stock In') }}
            </h2>
            <button id="createNewStockIn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                New Entry
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div id="success-message" class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table id="t_stock_in-table" class="min-w-full divide-y divide-gray-200 table">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Stock In No.</th>
                                    <th class="text-center">Material</th>
                                    <th class="text-center">Total Qty</th>
                                    <th class="text-center">UOM</th>
                                    <th class="text-center">Department</th>
                                    <th class="text-center">Storage</th>
                                    <th class="text-center">Cell</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Created At</th>
                                    <th class="text-center">Updated At</th>
                                    <th class="actions-column text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>  
    </div>
    
    <!-- Modal Form -->
    <div id="stockInModal" class="modal hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="modal-dialog relative bg-white rounded-lg shadow-lg w-1/3 mx-auto mt-20">
            <form id="stockInForm" method="POST">
                @csrf
                <div class="modal-header bg-gray-100 p-4 rounded-t-lg">
                    <h4 id="modalTitle" class="text-lg font-semibold">New Stock In Entry</h4>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="stock_in_no" class="block text-gray-700">Stock In No.</label>
                        <input type="text" id="stock_in_no" name="stock_in_no" class="w-full border-gray-300 rounded-md" value="{{ $newStockInNo }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="material" class="block text-gray-700">Material</label>
                        <select id="material" name="material" class="w-full border-gray-300 rounded-md select2" required>
                            <option value="AL">Alabama</option>
                                ...
                            <option value="WY">Wyoming</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="qty" class="block text-gray-700">Quantity</label>
                        <input type="number" id="qty" name="qty" class="w-full border-gray-300 rounded-md" required>
                    </div>
                    <div class="mb-4">
                        <label for="uom" class="block text-gray-700">UOM</label>
                        <input type="text" id="uom" name="uom" class="w-full border-gray-300 rounded-md" readonly>
                    </div>
                </div>
                <div class="modal-footer p-4 bg-gray-100 rounded-b-lg text-right">
                    <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" id="cancelBtn">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

   
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#t_stock_in-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('stock-in.data') }}", 
                columns: [
                    { data: 'DT_RowIndex', name: 'No', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'stock_in_no', name: 'Stock In No.', className: 'text-center' },
                    { data: 'material_description', name: 'Material', className: 'text-center' },
                    { data: 'stock_in_qty', name: 'Total Qty', className: 'text-center' },
                    { data: 'uom_description', name: 'UoM', className: 'text-center' },
                    { data: 'department_description', name: 'Department', className: 'text-center' },
                    { data: 'storage_description', name: 'Storage', className: 'text-center' },
                    { data: 'cell_description', name: 'Cell', className: 'text-center' },
                    { data: 'status', name: 'Status', className: 'text-center' },
                    { data: 'created_at', name: 'created_at', className: 'text-center' },
                    { data: 'updated_at', name: 'updated_at', className: 'text-center' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' },
                ]
            });

            // Open modal on button click
            $('#createNewStockIn').on('click', function() {
                console.log("Tombol New Entry diklik");
                $.ajax({
                    url: '/stock-in/generate-number',
                    method: 'GET',
                    success: function(response) {
                        console.log(response);
                        $('#stock_in_no').val(response.newStockInNo);
                        $('#stockInModal').removeClass('hidden');
                    },
                    error: function(xhr, status, error) {
                        console.log("Error: " + error);
                    }
                });
            });

            // Close modal on cancel button click
            $('#cancelBtn').on('click', function() {
                $('#stockInModal').addClass('hidden');
            });

            $('#material').select2({
                placeholder:'Select Material'
            });

            // Menangani pengiriman formulir
            $('#stockInForm').on('submit', function(e) {
                e.preventDefault(); // Mencegah pengiriman default

                $.ajax({
                    url: '/stock-in/store', // Rute untuk menyimpan data
                    method: 'POST',
                    data: $(this).serialize(), // Mengambil data dari formulir
                    success: function(response) {
                        $('#stockInModal').addClass('hidden'); // Menutup modal
                        $('#t_stock_in-table').DataTable().ajax.reload(); // Memuat ulang DataTable
                        // Menampilkan pesan sukses
                        $('#success-message').removeClass('hidden').text(response.success);
                    },
                    error: function(xhr) {
                        // Menampilkan pesan error
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '<br>'; // Menggabungkan semua pesan error
                        });
                        $('#error-message').removeClass('hidden').html(errorMessage);
                    }
                });
            });
        });
    </script>
</x-app-layout>