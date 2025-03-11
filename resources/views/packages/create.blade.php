<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Package') }}
            </h2>
            <a href="{{ route('packages.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Packages
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('packages.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
                            <input type="text" name="serial_number" id="serial_number"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                required>
                        </div>

                        <div class="mb-6">
                            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="type" id="type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                required>
                                <option value="loose">Loose</option>
                                <option value="carton" selected>Carton</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="mass" class="block text-sm font-medium text-gray-700">Mass (kg)</label>
                            <input type="number" step="0.01" name="mass" id="mass"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                required>
                        </div>

                        <div class="mb-6">
                            <label for="barcode" class="block text-sm font-medium text-gray-700">Barcode</label>
                            <input type="text" name="barcode" id="barcode"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                required>
                        </div>

                        <div class="mb-6">
                            <label for="pallet_id" class="block text-sm font-medium text-gray-700">Pallet</label>
                            <select name="pallet_id" id="pallet_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Select Pallet (Optional)</option>
                                @foreach ($pallets as $pallet)
                                    <option value="{{ $pallet->id }}">{{ $pallet->serial_number }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="quality_mark_id" class="block text-sm font-medium text-gray-700">Quality Mark</label>
                            <select name="quality_mark_id" id="quality_mark_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                required>
                                @foreach ($quality_marks as $mark)
                                    <option value="{{ $mark->id }}">{{ $mark->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Package
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
