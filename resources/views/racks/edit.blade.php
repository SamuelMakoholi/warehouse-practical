<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Rack') }}
            </h2>
            <a href="{{ route('racks.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Racks
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-400 text-red-700 rounded-md">
                <h3 class="font-semibold">Oops! Something went wrong.</h3>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('racks.update', $rack) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial
                                Number</label>
                            <input type="text" name="serial_number" id="serial_number"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('serial_number') border-red-500 @enderror"
                                value="{{ old('serial_number', $rack->serial_number) }}" required>
                            @error('serial_number')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="line_id" class="block text-sm font-medium text-gray-700">Line</label>
                            <select name="line_id" id="line_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('line_id') border-red-500 @enderror"
                                required>
                                <option value="">Select a Line</option>
                                @foreach ($lines as $line)
                                    <option value="{{ $line->id }}"
                                        {{ old('line_id', $rack->line_id) == $line->id ? 'selected' : '' }}>
                                        {{ $line->warehouse->name }} / {{ $line->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('line_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity (kg)</label>
                            <input type="number" name="capacity" id="capacity" step="0.01" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('capacity') border-red-500 @enderror"
                                value="{{ old('capacity', $rack->capacity) }}" required>
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Update Rack
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
