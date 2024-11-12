<?php

namespace App\Jobs;

use App\Models\LuotPhim;
use App\Models\Phim;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IncreaseViewCount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phimId;
    protected $tapPhimId;
    public function __construct($phimId, $tapPhimId)
    {
        $this->phimId = $phimId;
        $this->tapPhimId = $tapPhimId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $currDate = date('Y-m-d');

        // Tăng lượt xem cho tập phim
        $luotXem = LuotPhim::firstOrCreate(
            [
                'id_phim' => $this->phimId,
                'id_tap_phim' => $this->tapPhimId,
                'ngay_xem' => $currDate,
            ],
            [
                'so_luot_xem' => 0,
            ]
        );

        $luotXem->increment('so_luot_xem');

        // Tăng tổng lượt xem cho phim
        Phim::where('id', $this->phimId)->increment('tong_luot_xem');
    }
}
