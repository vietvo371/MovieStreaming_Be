<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TraiAI extends Controller
{
    private $baseUrl = 'http://127.0.0.1:5000';

    private function getRecommendations($params)
    {
        try {
            $response = Http::post("{$this->baseUrl}/recommend", $params);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['recommendations'])) {
                    $recommendations = $data['recommendations'];
                    $movie_ids = array_map(function($item) {
                        return $item['id'];
                    }, $recommendations);

                    return implode(', ', $movie_ids);
                }
                return null;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
    private function getRecommendationsUser($params)
    {
        try {
            $response = Http::post("{$this->baseUrl}/recommend/history", $params);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['recommendations'])) {
                    $recommendations = $data['recommendations'];
                    $movie_ids = array_map(function($item) {
                        return $item['id'];
                    }, $recommendations);

                    return implode(', ', $movie_ids);
                }
                return null;
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function recommendByUser($user_id)
    {
        $recommendations = $this->getRecommendationsUser(['user_id' => 1]);

        if ($recommendations === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể lấy được đề xuất'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'recommendations' => $recommendations // Sẽ trả về dạng "49, 17, 23, 21, 47"
        ]);
    }

    public function recommendByMovie($movie_id)
    {
        $recommendations = $this->getRecommendations(['movie_id' => $movie_id]);

        if ($recommendations === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể lấy được đề xuất'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'recommendations' => $recommendations // Sẽ trả về dạng "49, 17, 23, 21, 47"
        ]);
    }
}
