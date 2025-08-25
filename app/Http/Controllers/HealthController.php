<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Exception;

class HealthController extends Controller
{
    /**
     * Health check endpoint to verify database connectivity
     */
    public function check(Request $request)
    {
        $healthData = [
            'status' => 'healthy',
            'timestamp' => now()->toDateTimeString(),
            'app_name' => config('app.name'),
            'app_env' => config('app.env'),
            'app_url' => config('app.url'),
            'database' => [
                'connection' => config('database.default'),
                'host' => config('database.connections.' . config('database.default') . '.host'),
                'port' => config('database.connections.' . config('database.default') . '.port'),
                'database' => config('database.connections.' . config('database.default') . '.database'),
                'status' => 'unknown'
            ],
            'request_info' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => $request->method(),
                'url' => $request->fullUrl()
            ]
        ];

        // Test database connection
        try {
            DB::connection()->getPdo();
            $healthData['database']['status'] = 'connected';
            $healthData['database']['connection_time'] = now()->toDateTimeString();
            
            // Test a simple query
            $result = DB::select('SELECT 1 as test');
            $healthData['database']['query_test'] = 'success';
            
            Log::info('Health check passed', $healthData);
            
        } catch (Exception $e) {
            $healthData['status'] = 'unhealthy';
            $healthData['database']['status'] = 'failed';
            $healthData['database']['error'] = $e->getMessage();
            $healthData['database']['connection_time'] = now()->toDateTimeString();
            
            Log::error('Health check failed', $healthData);
        }

        return response()->json($healthData, $healthData['status'] === 'healthy' ? 200 : 503);
    }

    /**
     * Detailed system status endpoint
     */
    public function status(Request $request)
    {
        $statusData = [
            'system' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_time' => now()->toDateTimeString(),
                'timezone' => config('app.timezone'),
            ],
            'database' => [
                'driver' => config('database.default'),
                'host' => config('database.connections.' . config('database.default') . '.host'),
                'port' => config('database.connections.' . config('database.default') . '.port'),
                'database' => config('database.connections.' . config('database.default') . '.database'),
            ],
            'cache' => [
                'driver' => config('cache.default'),
            ],
            'session' => [
                'driver' => config('session.driver'),
                'lifetime' => config('session.lifetime'),
            ],
            'mail' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
            ]
        ];

        // Test database connectivity
        try {
            DB::connection()->getPdo();
            $statusData['database']['status'] = 'connected';
            $statusData['database']['test_query'] = 'SELECT 1 as test';
            $statusData['database']['test_result'] = DB::select('SELECT 1 as test')[0]->test;
        } catch (Exception $e) {
            $statusData['database']['status'] = 'failed';
            $statusData['database']['error'] = $e->getMessage();
        }

        Log::info('System status check', [
            'request_ip' => $request->ip(),
            'request_url' => $request->fullUrl(),
            'database_status' => $statusData['database']['status'] ?? 'unknown'
        ]);

        return response()->json($statusData);
    }

    /**
     * Debug user details endpoint (for troubleshooting login issues)
     */
    public function debugUser(Request $request)
    {
        $email = $request->query('email');
        
        if (!$email) {
            return response()->json(['error' => 'Email parameter is required'], 400);
        }

        try {
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                Log::warning('Debug user - User not found', [
                    'email' => $email,
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString()
                ]);
                
                return response()->json([
                    'found' => false,
                    'message' => 'User not found',
                    'email' => $email
                ]);
            }

            $userData = [
                'found' => true,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'password_hash_exists' => !empty($user->password),
                'password_hash_length' => strlen($user->password),
                'has_roles' => $user->roles->count(),
                'roles' => $user->roles->pluck('name')->toArray()
            ];

            Log::info('Debug user - User details retrieved', [
                'user_id' => $user->id,
                'email' => $email,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json($userData);

        } catch (Exception $e) {
            Log::error('Debug user - Error occurred', [
                'email' => $email,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'error' => 'Error retrieving user details',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
