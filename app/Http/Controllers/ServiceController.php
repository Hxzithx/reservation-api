<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\Middleware;

class ServiceController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
        ];
    }

    public function index(Request $request) : JsonResponse
    {
        try{
            Gate::authorize('viewAny', Service::class);

            $services = Service::where('business_id', $request->business_id)->get();

            return response()->json([
                'message' => 'Services retrieved successfully',
               'services' => $services,
            ],200);
        }catch (AuthorizationException $e){
            return response()->json([
              'message' => 'Unauthorize to view services',
              'error' => $e->getMessage(),
            ],403);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while retrieving services',
               'error' => $e->getMessage(),
            ],500);
        }
    }
}