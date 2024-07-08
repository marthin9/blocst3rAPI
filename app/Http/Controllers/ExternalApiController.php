<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\tokens;
use App\Models\checkpoints;
use Carbon\Carbon;

class ExternalApiController extends Controller
{
    /**
     * Fetch token from external Url
     */
    public function getTokens() {
        try {
            $response = Http::post('https://tpcc.police.go.th/2021/api/auth', [
                "username" => "command2",
                "password" => "1234command"
            ]);

            if ($response->status() !== 200) {
                Log::error('Failed to fetch token', ['response' => $response->body()]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to get token',
                    'data' => null
                ], $response->status());
            }

            $tokens = $response->json();

            Tokens::updateOrCreate([], [
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'expires_in' => $tokens['expires_in'],
                'token_type' => $tokens['token_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Successfully stored token',
                'data' => $tokens
            ]);
        } catch (\Exception $e) {
            Log::error('Exception occurred while fetching token', ['exception' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching token',
                'data' => null
            ], 500);
        }
    }

    public function getCheckpoints()
    {
        try {
            // Fetch the latest token from the database
            $tokenRecord = Tokens::latest()->first();

            if (!$tokenRecord || !$tokenRecord->access_token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access token not found',
                    'data' => null
                ], 401);
            }

            // Make the API request to fetch today's checkpoints
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenRecord->access_token
            ])->get('https://tpcc.police.go.th/2021/api/v2/checkpointToday', [
                'page' => 1,
                'limit' => 200,
                'showActive' => 'true',
                'showInActive' => 'false',
                'bureau' => '10008'
            ]);

            if ($response->status() !== 200) {
                Log::error('Failed to fetch checkpoints', ['response' => $response->body()]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to fetch checkpoints',
                    'data' => null
                ], $response->status());
            }

            $data = $response->json();

            if (isset($data['data'])) {
                $today = Carbon::today();

                foreach ($data['data'] as $checkpoints) {
                    $existingCheckpoints = Checkpoints::where('checkpoint_id', $checkpoints['id'])
                        ->whereDate('created_at', $today)
                        ->first();

                    if ($existingCheckpoints) {
                        $existingCheckpoints->update([
                            'name' => $checkpoints['name'],
                            'chief' => $checkpoints['chief'],
                            'chief_phone' => $checkpoints['chief_phone'],
                            'station' => $checkpoints['station'],
                            'division' => $checkpoints['division'],
                            'bureau' => $checkpoints['bureau'],
                            'type' => $checkpoints['type'],
                            'start_at' => $checkpoints['start_at'],
                            'end_at' => $checkpoints['end_at'],
                            'address' => $checkpoints['address'],
                            'is_active' => $checkpoints['is_active'],
                            'approval' => $checkpoints['approval'],
                        ]);
                    } else {
                        Checkpoints::create([
                            'checkpoint_id' => $checkpoints['id'],
                            'name' => $checkpoints['name'],
                            'chief' => $checkpoints['chief'],
                            'chief_phone' => $checkpoints['chief_phone'],
                            'station' => $checkpoints['station'],
                            'division' => $checkpoints['division'],
                            'bureau' => $checkpoints['bureau'],
                            'type' => $checkpoints['type'],
                            'start_at' => $checkpoints['start_at'],
                            'end_at' => $checkpoints['end_at'],
                            'address' => $checkpoints['address'],
                            'is_active' => $checkpoints['is_active'],
                            'approval' => $checkpoints['approval'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Checkpoints fetched and stored successfully',
                    'data' => $data['data']
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to fetch checkpoints',
                    'data' => $data
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Exception occurred while fetching checkpoints', ['exception' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching checkpoints',
                'data' => null
            ], 500);
        }
    }
}
