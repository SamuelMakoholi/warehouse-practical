<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Rack;
use App\Models\Line;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Handle search functionality
        $search = $request->input('search');
        $searchResults = null;
        
        if ($search) {
            // Search for package
            $package = Package::with(['pallet.rack.line.warehouse', 'qualityMark'])
                ->where('serial_number', 'like', "%{$search}%")
                ->where('is_discarded', false)
                ->first();
                
            if ($package) {
                $searchResults = [
                    'type' => 'package',
                    'data' => $package
                ];
            } else {
                // Search for rack
                $rack = Rack::with(['line.warehouse', 'pallets.packages' => function($query) {
                    $query->where('is_discarded', false);
                }])
                    ->where('serial_number', 'like', "%{$search}%")
                    ->first();
                    
                if ($rack) {
                    $searchResults = [
                        'type' => 'rack',
                        'data' => $rack
                    ];
                }
            }
        }

        // Get warehouse snapshots
        $warehouses = Warehouse::with([
                'lines.racks.pallets.packages' => function($query) {
                    $query->where('is_discarded', false);
                }
            ])
            ->withCount(['lines'])
            ->get()
            ->map(function ($warehouse) {
                // Calculate total capacity from all racks
                $totalCapacity = $warehouse->lines->sum(function ($line) {
                    return $line->racks->sum('capacity');
                });
                
                // Calculate current mass from non-discarded packages
                $currentMass = $warehouse->lines->sum(function ($line) {
                    return $line->racks->sum(function ($rack) {
                        return $rack->pallets->sum(function ($pallet) {
                            return $pallet->packages->sum('mass');
                        });
                    });
                });

                // Count total racks across all lines
                $rackCount = $warehouse->lines->sum(function ($line) {
                    return $line->racks->count();
                });

                // Count active packages (non-discarded)
                $activePackageCount = $warehouse->lines->sum(function ($line) {
                    return $line->racks->sum(function ($rack) {
                        return $rack->pallets->sum(function ($pallet) {
                            return $pallet->packages->count();
                        });
                    });
                });

                return [
                    'warehouse' => $warehouse,
                    'utilization' => $totalCapacity > 0 ? ($currentMass / $totalCapacity) * 100 : 0,
                    'current_mass' => $currentMass,
                    'total_capacity' => $totalCapacity,
                    'rack_count' => $rackCount,
                    'active_package_count' => $activePackageCount
                ];
            });

        // Get packages ordered by creation date
        $sortOrder = $request->input('sort_order', 'desc');
        $packages = Package::with(['pallet.rack.line.warehouse', 'qualityMark'])
            ->where('is_discarded', false)
            ->orderBy('created_at', $sortOrder)
            ->paginate(10);

        // Get location data for dropdowns
        $locationData = null;
        $locationType = $request->input('location_type');
        
        if ($locationType === 'line') {
            $locationData = Line::with('warehouse')
                ->get()
                ->map(function ($line) {
                    return [
                        'id' => $line->id,
                        'name' => "{$line->warehouse->name} / {$line->name}"
                    ];
                });
        } elseif ($locationType === 'rack') {
            $locationData = Rack::with('line.warehouse')
                ->get()
                ->map(function ($rack) {
                    return [
                        'id' => $rack->id,
                        'name' => "{$rack->line->warehouse->name} / {$rack->line->name} / {$rack->serial_number}"
                    ];
                });
        }

        // Get historical records if location is selected
        $locationHistory = null;
        $locationId = $request->input('location_id');

        if ($locationType && $locationId) {
            switch ($locationType) {
                case 'warehouse':
                    $location = Warehouse::with([
                        'lines.racks.pallets.packages' => function ($query) {
                            $query->withTrashed();
                        }
                    ])->findOrFail($locationId);
                    break;
                case 'line':
                    $location = Line::with([
                        'racks.pallets.packages' => function ($query) {
                            $query->withTrashed();
                        }
                    ])->findOrFail($locationId);
                    break;
                case 'rack':
                    $location = Rack::with([
                        'pallets.packages' => function ($query) {
                            $query->withTrashed();
                        }
                    ])->findOrFail($locationId);
                    break;
            }
            
            $locationHistory = [
                'type' => $locationType,
                'location' => $location
            ];
        }

        return view('dashboard', compact(
            'warehouses',
            'packages',
            'searchResults',
            'locationHistory',
            'locationData',
            'sortOrder'
        ));
    }
}
