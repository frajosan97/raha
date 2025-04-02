<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('portal.dashboard');
    }

    public function updateUserLocation(Request $request)
    {
        try {
            // get information
            $latitude = $request->query('latitude');
            $longitude = $request->query('longitude');

            // Get the authenticated user
            $user = Auth::user();

            if ($user) {
                $user->latitude = $latitude;
                $user->longitude = $longitude;
                $user->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Location updated successfully',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating lovcation: ', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'Error updating your location.',
            ], 500);
        }
    }
}
