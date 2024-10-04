<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Master Department') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('departments.create') }}" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('New Department') }}
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
                        <table id="departments-table" class="min-w-full divide-y divide-gray-200 table">
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
    initializeDataTable('#departments-table', '/departments/data', [
        { data: 'DT_RowIndex', title: 'No', orderable: false, searchable: false },
        { data: 'code', title: 'Code' },
        { data: 'description', title: 'Description' },
        { data: 'status', title: 'Status', className: 'text-center' },
        { data: 'actions', title: 'Actions', orderable: false, searchable: false, className: 'text-center' }
    ]);
});
    </script>
</x-app-layout>
