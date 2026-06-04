<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class SystemSettingController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return[
          new Middleware('auth:sanctum'),
        ];
    }

    public function index()
    {
        try{
            Gate::authorize('viewAny', SystemSetting::class);

            $systemSetting = SystemSetting::all();

            return response()->json([
               'message' => 'System retrieved successfully',
               'systemSetting' => $systemSetting,
            ], 200);
        }catch (AuthorizationException $e){
            return response()->json([
               'message' => 'Unauthorize to view System setting',
               'error' => $e->getMessage(),
            ], 403);
        }catch (Exception $e){
            return response()->json([
               'message' => 'An error occured while retrieving system setting',
               'error' => $e->getMessage(),
            ], 500);
        }
    }
}