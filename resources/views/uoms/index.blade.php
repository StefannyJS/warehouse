<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Master UOM') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('uoms.create') }}" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('New UOM') }}
                </a>
                <a href="{{ route('materials.index') }}" 
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
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table id="uoms-table" class="min-w-full divide-y divide-gray-200 table">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="text-center">No</th>
                                    <th class="text-center description-column-uom">Description</th>
                                    <th class="text-center">Status</th>
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

    <script>
        $(document).ready(function() {
            initializeDataTable('#uoms-table', '/uoms/data', [
                { data: 'DT_RowIndex', title: 'No', orderable: false, searchable: false },
                { data: 'description', title: 'Description' },
                {
                    data: 'status',
                    title: 'Status',
                    render: function(data) {
                        return data == 1 ? 
                            '<div class="flex justify-center items-center"><i class="fas fa-check-circle text-green-500"></i></div>' : 
                            '<div class="flex justify-center items-center"><i class="fas fa-times-circle text-red-500"></i></div>';
                    },
                    className: 'text-center'
                },
                {
                    data: 'actions',
                    title: 'Actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center ',
                    render: function(data, type, row) {
                        return `
                            <a href="/uoms/${row.id_uom}/edit" class="btn btn-edit flex items-center space-x-2">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </a>
                            <button onclick="disableRecord('/uoms/${row.id_uom}', '#uoms-table')" class="btn btn-delete flex items-center space-x-2">
                                <i class="fas fa-trash-alt"></i>
                                <span>Delete</span>
                            </button>
                        `;
                    }
                }
            ]);
        });
    </script>
</x-app-layout>