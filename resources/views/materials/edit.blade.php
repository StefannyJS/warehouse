<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Master Material') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8"> <!-- Mengatur lebar max -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('materials.update', $material->id_material) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-barcode text-gray-400"></i> <!-- Ikon barcode -->
                                </div>
                                <input type="text" name="code" id="code" class="mt-1 block w-full px-10 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200" value="{{ old('code', $material->code) }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-pencil-alt text-gray-400"></i> <!-- Ikon pencil -->
                                </div>
                                <input type="text" name="description" id="description" class="mt-1 block w-full px-10 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200" value="{{ old('description', $material->description) }}" required>
                            </div>
                        </div>

                        <!-- Dropdown untuk UOM -->
                        <div class="mb-4">
                            <label for="unit_of_measure" class="block text-sm font-medium text-gray-700">Unit of Measure</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-cogs text-gray-400"></i> <!-- Ikon cogs -->
                                </div>
                                <select name="unit_of_measure" id="unit_of_measure" class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200" required>
                                    <option value="">-- Select UOM --</option>
                                    @foreach($uoms as $uom)
                                        <option value="{{ $uom->id_uom }}" {{ $material->id_uom == $uom->id_uom ? 'selected' : '' }}>{{ $uom->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-toggle-on text-gray-400"></i> <!-- Ikon toggle -->
                                </div>
                                <select name="status" id="status" class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200" required>
                                    <option value="1" {{ old('status', $material->status) == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $material->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('materials.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
