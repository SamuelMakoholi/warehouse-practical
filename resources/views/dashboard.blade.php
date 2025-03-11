<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Warehouse Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Search Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Search</h3>
                    <form method="GET" action="{{ route('dashboard') }}" class="flex gap-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Enter package or rack serial number"
                            class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Search
                        </button>
                    </form>

                    @if(isset($searchResults))
                    <div class="mt-4 p-4 bg-gray-50 rounded-md">
                        @if($searchResults['type'] === 'package')
                            <h4 class="font-medium mb-2">Package Details</h4>
                            <dl class="grid grid-cols-2 gap-2">
                                <dt class="text-gray-600">Serial Number:</dt>
                                <dd>{{ $searchResults['data']->serial_number }}</dd>
                                
                                <dt class="text-gray-600">Type:</dt>
                                <dd>{{ ucfirst($searchResults['data']->type) }}</dd>
                                
                                <dt class="text-gray-600">Mass:</dt>
                                <dd>{{ number_format($searchResults['data']->mass, 2) }} kg</dd>
                                
                                <dt class="text-gray-600">Quality Mark:</dt>
                                <dd>{{ $searchResults['data']->qualityMark->name ?? 'N/A' }}</dd>
                                
                                <dt class="text-gray-600">Status:</dt>
                                <dd>
                                    @if($searchResults['data']->deleted_at)
                                        <span class="text-red-600">Deleted</span>
                                    @elseif($searchResults['data']->is_discarded)
                                        <span class="text-yellow-600">Discarded</span>
                                    @else
                                        <span class="text-green-600">Active</span>
                                    @endif
                                </dd>
                                
                                <dt class="text-gray-600">Location:</dt>
                                <dd>
                                    @if($searchResults['data']->pallet)
                                        Warehouse: {{ $searchResults['data']->pallet->rack->line->warehouse->name }}<br>
                                        Line: {{ $searchResults['data']->pallet->rack->line->name }}<br>
                                        Rack: {{ $searchResults['data']->pallet->rack->serial_number }}<br>
                                        Pallet: {{ $searchResults['data']->pallet->serial_number }}
                                    @else
                                        Not assigned to any pallet
                                    @endif
                                </dd>
                            </dl>
                        @else
                            <h4 class="font-medium mb-2">Rack Details</h4>
                            <dl class="grid grid-cols-2 gap-2">
                                <dt class="text-gray-600">Serial Number:</dt>
                                <dd>{{ $searchResults['data']->serial_number }}</dd>
                                
                                <dt class="text-gray-600">Capacity:</dt>
                                <dd>{{ number_format($searchResults['data']->capacity, 2) }} kg</dd>
                                
                                <dt class="text-gray-600">Location:</dt>
                                <dd>
                                    Warehouse: {{ $searchResults['data']->line->warehouse->name }}<br>
                                    Line: {{ $searchResults['data']->line->name }}
                                </dd>
                                
                                <dt class="text-gray-600">Current Packages:</dt>
                                <dd>
                                    @if($searchResults['data']->pallets->flatMap->packages->isNotEmpty())
                                        <div class="space-y-2">
                                        @foreach($searchResults['data']->pallets as $pallet)
                                            @foreach($pallet->packages as $package)
                                                <div class="text-sm">
                                                    {{ $package->serial_number }} 
                                                    ({{ number_format($package->mass, 2) }} kg)
                                                    <span class="ml-2">
                                                        @if($package->is_discarded)
                                                            <span class="text-yellow-600">Discarded</span>
                                                        @else
                                                            <span class="text-green-600">Active</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            @endforeach
                                        @endforeach
                                        </div>
                                    @else
                                        No packages currently assigned
                                    @endif
                                </dd>
                            </dl>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Warehouse Snapshot Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Warehouse Snapshots</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($warehouses as $warehouseData)
                        <div class="border rounded-lg p-4">
                            <h4 class="font-medium mb-2">{{ $warehouseData['warehouse']->name }}</h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-600">Current Mass:</dt>
                                    <dd class="font-medium">{{ number_format($warehouseData['current_mass'], 2) }} kg</dd>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <dt class="text-gray-600">Total Capacity:</dt>
                                    <dd class="font-medium">{{ number_format($warehouseData['total_capacity'], 2) }} kg</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-600 text-sm mb-1">Utilization:</dt>
                                    <dd>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $warehouseData['utilization'] > 90 ? 'bg-red-500' : ($warehouseData['utilization'] > 70 ? 'bg-yellow-500' : 'bg-green-500') }}"
                                                style="width: {{ min($warehouseData['utilization'], 100) }}%">
                                            </div>
                                        </div>
                                        <p class="text-sm mt-1 {{ $warehouseData['utilization'] > 90 ? 'text-red-600' : ($warehouseData['utilization'] > 70 ? 'text-yellow-600' : 'text-green-600') }}">
                                            {{ number_format($warehouseData['utilization'], 1) }}% utilized
                                        </p>
                                    </dd>
                                </div>
                                <div class="pt-2 border-t">
                                    <dt class="text-gray-600 text-sm mb-2">Structure:</dt>
                                    <dd class="text-sm space-y-1">
                                        <div class="flex justify-between">
                                            <span>Lines:</span>
                                            <span class="font-medium">{{ $warehouseData['warehouse']->lines->count() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Racks:</span>
                                            <span class="font-medium">{{ $warehouseData['warehouse']->lines->sum(function($line) {
                                                return $line->racks->count();
                                            }) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Active Packages:</span>
                                            <span class="font-medium">{{ $warehouseData['warehouse']->lines->sum(function($line) {
                                                return $line->racks->sum(function($rack) {
                                                    return $rack->pallets->sum(function($pallet) {
                                                        return $pallet->packages->where('is_discarded', false)->count();
                                                    });
                                                });
                                            }) }}</span>
                                        </div>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Package Order Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Package Order</h3>
                        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="sort_order" class="rounded-md border-gray-300 text-sm"
                                onchange="this.form.submit()">
                                <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>
                                    Newest First
                                </option>
                                <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>
                                    Oldest First
                                </option>
                            </select>
                        </form>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Serial Number
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Type
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Mass
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Quality Mark
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Barcode
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Location
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Created At
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($packages as $package)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center">
                                            <span class="mr-2">{{ $package->serial_number }}</span>
                                            @if($package->is_discarded)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Discarded
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ ucfirst($package->type) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ number_format($package->mass, 2) }} kg
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ $package->qualityMark->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono">
                                        {{ $package->barcode }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($package->pallet)
                                            <div class="space-y-1">
                                                <div class="text-gray-900">
                                                    {{ $package->pallet->rack->line->warehouse->name }}
                                                </div>
                                                <div class="text-gray-500 text-xs">
                                                    Line: {{ $package->pallet->rack->line->name }}<br>
                                                    Rack: {{ $package->pallet->rack->serial_number }}<br>
                                                    Pallet: {{ $package->pallet->serial_number }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-500">Not assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $package->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $packages->withQueryString()->links() }}
                    </div>
                </div>
            </div>

            <!-- Location History Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Location History</h3>
                    <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="sort_order" value="{{ $sortOrder }}">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location Type</label>
                            <select name="location_type" class="w-full rounded-md border-gray-300"
                                onchange="this.form.submit()">
                                <option value="">Select Type</option>
                                <option value="warehouse" {{ request('location_type') === 'warehouse' ? 'selected' : '' }}>
                                    Warehouse
                                </option>
                                <option value="line" {{ request('location_type') === 'line' ? 'selected' : '' }}>
                                    Line
                                </option>
                                <option value="rack" {{ request('location_type') === 'rack' ? 'selected' : '' }}>
                                    Rack
                                </option>
                            </select>
                        </div>

                        @if(request('location_type') === 'warehouse')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Warehouse</label>
                            <select name="location_id" class="w-full rounded-md border-gray-300"
                                onchange="this.form.submit()">
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses as $warehouseData)
                                <option value="{{ $warehouseData['warehouse']->id }}"
                                    {{ request('location_id') == $warehouseData['warehouse']->id ? 'selected' : '' }}>
                                    {{ $warehouseData['warehouse']->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @elseif(request('location_type') === 'line' && $locationData)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Line</label>
                            <select name="location_id" class="w-full rounded-md border-gray-300"
                                onchange="this.form.submit()">
                                <option value="">Select Line</option>
                                @foreach($locationData as $line)
                                <option value="{{ $line['id'] }}"
                                    {{ request('location_id') == $line['id'] ? 'selected' : '' }}>
                                    {{ $line['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @elseif(request('location_type') === 'rack' && $locationData)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rack</label>
                            <select name="location_id" class="w-full rounded-md border-gray-300"
                                onchange="this.form.submit()">
                                <option value="">Select Rack</option>
                                @foreach($locationData as $rack)
                                <option value="{{ $rack['id'] }}"
                                    {{ request('location_id') == $rack['id'] ? 'selected' : '' }}>
                                    {{ $rack['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </form>

                    @if($locationHistory)
                    <div class="mt-4">
                        <h4 class="font-medium mb-2">Package History</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Serial Number
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Type
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Mass
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Quality Mark
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Created At
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Deleted At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($locationHistory['type'] === 'warehouse')
                                        @foreach($locationHistory['location']->lines as $line)
                                            @foreach($line->racks as $rack)
                                                @foreach($rack->pallets as $pallet)
                                                    @foreach($pallet->packages as $package)
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                            {{ $package->serial_number }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                            {{ ucfirst($package->type) }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                            {{ number_format($package->mass, 2) }} kg
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                            {{ $package->qualityMark->name ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                            @if($package->deleted_at)
                                                                <span class="text-red-600">Deleted</span>
                                                            @elseif($package->is_discarded)
                                                                <span class="text-yellow-600">Discarded</span>
                                                            @else
                                                                <span class="text-green-600">Active</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                            {{ $package->created_at->format('Y-m-d H:i:s') }}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                            {{ $package->deleted_at ? $package->deleted_at->format('Y-m-d H:i:s') : '-' }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @elseif($locationHistory['type'] === 'line')
                                        @foreach($locationHistory['location']->racks as $rack)
                                            @foreach($rack->pallets as $pallet)
                                                @foreach($pallet->packages as $package)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        {{ $package->serial_number }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        {{ ucfirst($package->type) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        {{ number_format($package->mass, 2) }} kg
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        {{ $package->qualityMark->name ?? 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        @if($package->deleted_at)
                                                            <span class="text-red-600">Deleted</span>
                                                        @elseif($package->is_discarded)
                                                            <span class="text-yellow-600">Discarded</span>
                                                        @else
                                                            <span class="text-green-600">Active</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        {{ $package->created_at->format('Y-m-d H:i:s') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        {{ $package->deleted_at ? $package->deleted_at->format('Y-m-d H:i:s') : '-' }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @else
                                        @foreach($locationHistory['location']->pallets as $pallet)
                                            @foreach($pallet->packages as $package)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ $package->serial_number }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ ucfirst($package->type) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ number_format($package->mass, 2) }} kg
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ $package->qualityMark->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    @if($package->deleted_at)
                                                        <span class="text-red-600">Deleted</span>
                                                    @elseif($package->is_discarded)
                                                        <span class="text-yellow-600">Discarded</span>
                                                    @else
                                                        <span class="text-green-600">Active</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ $package->created_at->format('Y-m-d H:i:s') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    {{ $package->deleted_at ? $package->deleted_at->format('Y-m-d H:i:s') : '-' }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
