<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function getEscorts(Request $request)
    {
        // Ensure the request is an AJAX request
        if ($request->ajax()) {
            try {
                // get data
                $escorts = User::with(['primaryImage', 'activeSubscription.plan'])
                    ->where('relationship_status', 'single')
                    ->whereNull('deleted_at')
                    // ->orderBy('created_at')
                    ->get();

                // Return success response with data
                return response()->json([
                    'status'  => 'success',
                    'message' => count($escorts) . ' escorts found near you.',
                    'data'    => $escorts,
                ]);
            } catch (\Exception $e) {
                // Log the error for debugging purposes
                Log::error('Error fetching escorts near me: ', [
                    'error' => $e->getMessage(),
                ]);

                // Return error response with proper error message
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to fetch escorts. Please try again later.',
                ], 500);
            }
        }
    }

    public function singlesNearMe(Request $request)
    {
        // Ensure the request is an AJAX request
        if ($request->ajax()) {
            try {
                // Validate the incoming latitude and longitude
                $validated = $request->validate([
                    'latitude'  => 'required|numeric',
                    'longitude' => 'required|numeric',
                ]);

                $latitude = $validated['latitude'];
                $longitude = $validated['longitude'];
                $radius = 10; // Search radius in kilometers

                // Using Haversine formula to calculate distance between coordinates
                $singles = User::with(['primaryImage', 'activeSubscription.plan'])
                    ->selectRaw("users.*, (6371 * acos(cos(radians(?)) 
                        * cos(radians(latitude)) 
                        * cos(radians(longitude) - radians(?)) 
                        + sin(radians(?)) 
                        * sin(radians(latitude)))) AS distance", [$latitude, $longitude, $latitude])
                    ->where('relationship_status', 'single')
                    ->whereNull('deleted_at')
                    ->having('distance', '<', $radius)
                    ->orderBy('distance')
                    ->get();

                // Check if any singles were found
                if ($singles->isEmpty()) {
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'No singles found near you.',
                        'data'    => [],
                    ]);
                }

                // Return success response with data
                return response()->json([
                    'status'  => 'success',
                    'message' => count($singles) . ' singles found near you.',
                    'data'    => $singles,
                ]);
            } catch (\Exception $e) {
                // Log the error for debugging purposes
                Log::error('Error fetching singles near me: ', [
                    'error' => $e->getMessage(),
                ]);

                // Return error response with proper error message
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Failed to fetch singles. Please try again later.',
                ], 500);
            }
        }

        // view
        return view('pages.singles-near-me');
    }
}
