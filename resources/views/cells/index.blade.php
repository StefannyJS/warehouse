<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cells for Storage: ') . $storage->code }}
            </h2>
            <div class="flex space-x-4">
                <button id="createNewCell" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Cell
                </button>
                <a href="{{ route('storages.index') }}" 
                    class="btn-cancel text-white font-bold py-2 px-4 rounded">
                    {{ __('Back') }}
                </a>
            </div>
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
                        <div id="error-message" class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Table for Cells -->
                    <table id="cells-table" class="min-w-full divide-y divide-gray-200" data-url="{{ route('cells.data', $storage->id_storage) }}">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="text-center">No</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Status</th>
                                <th class="actions-column text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi oleh DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="cellModal" class="modal hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="modal-dialog relative bg-white rounded-lg shadow-lg w-1/3 mx-auto mt-20">
            <form id="cellForm" method="POST">
                @csrf
                <div class="modal-header bg-gray-100 p-4 rounded-t-lg">
                    <h4 id="modalTitle" class="text-lg font-semibold">Create New Cell</h4>
                </div>
                <div class="modal-body p-6">
                    <input type="hidden" name="id_cell" id="id_cell">
                    <input type="hidden" name="id_storage" id="id_storage" value="{{ $storage->id_storage }}">
                    
                    <div class="mb-4">
                        <label for="code" class="block text-gray-700">Code</label>
                        <input type="text" id="code" name="code" class="w-full border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">Description</label>
                        <textarea id="description" name="description" class="w-full border-gray-300 rounded-md" required></textarea>
                    </div>

                    <div class="mb-4">
                    <label for="status" class="block text-gray-700">Status</label>
                    <select id="status" name="status" class="w-full border-gray-300 rounded-md" required>
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                </div>
                <div class="modal-footer p-4 bg-gray-100 rounded-b-lg text-right">
                    <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" id="cancelBtn">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
    $(document).ready(function() {
        var cellsDataUrl = $('#cells-table').data('url');
        var table = $('#cells-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: cellsDataUrl,
                type: 'GET',
            },
            columns: [
                { data: 'DT_RowIndex', title: 'No', orderable: false, searchable: false },
                { data: 'code', title: 'Code' },
                { data: 'description', title: 'Description' },
                { data: 'status', title: 'Status', className: 'text-center' },
                { data: 'actions', title: 'Actions', orderable: false, searchable: false, className: 'text-center' }
            ]
        });

        // Show Modal for Creating New Cell
        $('#createNewCell').click(function() {
            $('#cellModal').removeClass('hidden'); // Show modal
            $('#modalTitle').text('Create New Cell'); // Change modal title
            $('#cellForm')[0].reset(); // Clear the form
            $('#id_cell').val(''); // Ensure id_cell is empty
        });

        // Show Modal for Editing Cell
        $('#cells-table').on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: `/storages/{{ $storage->id_storage }}/cells/${id}/edit`,
                success: function(data) {
                    $('#cellModal').removeClass('hidden');
                    $('#modalTitle').text('Edit Cell');
                    $('#id_cell').val(data.id_cell);
                    $('#code').val(data.code);
                    $('#description').val(data.description);
                    $('#status').val(data.status);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Show Modal for deleting Cell
    $('#cells-table').on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        var deleteUrl = `/storages/{{ $storage->id_storage }}/cells/${id}`;
        
        disableRecord(deleteUrl, '#cells-table');
    });

        // Close Modal on Cancel Button
        $('#cancelBtn').click(function() {
            $('#cellModal').addClass('hidden'); // Hide modal
        });

        // Submit Form using AJAX
        $('#cellForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var idCell = $('#id_cell').val();
            var actionUrl = idCell ? `/storages/{{ $storage->id_storage }}/cells/${idCell}` : "{{ route('cells.store', $storage->id_storage) }}";

            $.ajax({
                type: idCell ? "PUT" : "POST",
                url: actionUrl,
                data: formData,
                success: function(response) {
                    $('#cellModal').addClass('hidden');
                    $('#success-message').text(response.success).removeClass('hidden');
                    table.ajax.reload(); // Reload DataTables
                },
                error: function(xhr) {
                    $('#error-message').removeClass('hidden').text('Error processing your request.');
                }
            });
        });
    });
</script>
</x-app-layout>
