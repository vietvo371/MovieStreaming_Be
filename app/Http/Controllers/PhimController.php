<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapNhatPhimRequest;
use App\Http\Requests\TaoPhimRequest;
use App\Http\Requests\ThayDoiTrangThaiPhimRequest;
use App\Models\ChiTietTheLoai;
use App\Models\HoaDon;
use App\Models\LoaiPhim;
use App\Models\PhanQuyen;
use App\Models\Phim;
use App\Models\TacGia;
use App\Models\TapPhim;
use App\Models\TheLoai;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PhimController extends Controller
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
                    $movie_ids = array_map(function ($item) {
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
                    $movie_ids = array_map(function ($item) {
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
    public function getData()
    {
        $id_chuc_nang = 5;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $the_loai_admin   = TheLoai::where('tinh_trang', 1)->select('the_loais.*')
            ->get();
        $loai_phim_admin   = LoaiPhim::where('tinh_trang', 1)->select('loai_phims.*')
            ->get();
        $dataAdmin   = Phim::join('loai_phims', 'id_loai_phim', 'loai_phims.id')
            ->select('phims.*',  'loai_phims.ten_loai_phim')
            ->orderBy('created_at', 'DESC')
            ->paginate(6); // get là ra 1  sách
        $theloais = ChiTietTheLoai::join('the_loais', 'chi_tiet_the_loais.id_the_loai', 'the_loais.id')
            ->select('chi_tiet_the_loais.*', 'the_loais.id', 'the_loais.ten_the_loai', 'chi_tiet_the_loais.id_phim')
            ->get();
        $pagination = [
            'total' => $dataAdmin->total(),
            'per_page' => $dataAdmin->perPage(),
            'current_page' => $dataAdmin->currentPage(),
            'last_page' => $dataAdmin->lastPage(),
            'from' => $dataAdmin->firstItem(),
            'to' => $dataAdmin->lastItem()
        ];

        $phimsArray = $dataAdmin->toArray();

        foreach ($phimsArray['data'] as &$phim) {
            $the_loais = [];
            foreach ($theloais as $theLoai) {
                if ($theLoai['id_phim'] == $phim['id']) {
                    array_push($the_loais, $theLoai->toArray());
                }
            }
            $phim['the_loais'] = $the_loais;
        }
        unset($phim);

        $dataAdmin = $phimsArray;

        $response = [
            'dataAdmin' => $dataAdmin,
            'pagination' => $pagination
        ];

        return response()->json([
            'phim_admin'  =>  $response,
            'the_loai_admin'  =>  $the_loai_admin,
            'loai_phim_admin'  =>  $loai_phim_admin,
        ]);
    }
    public function getDataTheoTap()
    {
        $phims = Phim::leftJoin('tap_phims', 'tap_phims.id_phim', 'phims.id')
            ->select('phims.id', 'phims.ten_phim', 'phims.hinh_anh', 'phims.thoi_gian_chieu', 'phims.nam_san_xuat', 'phims.so_tap_phim', 'phims.tinh_trang', DB::raw('COUNT(tap_phims.id) as tong_tap'))
            ->groupBy('phims.id', 'phims.ten_phim', 'phims.hinh_anh', 'phims.thoi_gian_chieu', 'phims.nam_san_xuat', 'phims.so_tap_phim', 'phims.tinh_trang')
            ->orderBy('phims.created_at', 'DESC')
            ->paginate(6);
        $response = [
            'pagination' => [
                'total' => $phims->total(),
                'per_page' => $phims->perPage(),
                'current_page' => $phims->currentPage(),
                'last_page' => $phims->lastPage(),
                'from' => $phims->firstItem(),
                'to' => $phims->lastItem()
            ],
            'dataAdmin' => $phims
        ];
        return response()->json([
            'phim_admin'  =>  $response,
        ]);
    }
    public function timPhimTheoTap(Request $request)
    {
        $id_chuc_nang = 5;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $key    = '%' . $request->key . '%';
        $phims = Phim::leftJoin('tap_phims', 'tap_phims.id_phim', 'phims.id')
            ->where('phims.ten_phim', 'like', $key)
            ->select('phims.id', 'phims.ten_phim', 'phims.hinh_anh', 'phims.thoi_gian_chieu', 'phims.nam_san_xuat', 'phims.so_tap_phim', 'phims.tinh_trang', DB::raw('COUNT(tap_phims.id) as tong_tap'))
            ->groupBy('phims.id', 'phims.ten_phim', 'phims.hinh_anh', 'phims.thoi_gian_chieu', 'phims.nam_san_xuat', 'phims.so_tap_phim', 'phims.tinh_trang')
            ->orderBy('phims.created_at', 'DESC')
            ->paginate(6);
        $response = [
            'pagination' => [
                'total' => $phims->total(),
                'per_page' => $phims->perPage(),
                'current_page' => $phims->currentPage(),
                'last_page' => $phims->lastPage(),
                'from' => $phims->firstItem(),
                'to' => $phims->lastItem()
            ],
            'dataAdmin' => $phims
        ];
        return response()->json([
            'phim_admin'  =>  $response,
        ]);
    }
    public function getDataXemPhim(Request $request)
    {
        $the_loai = TheLoai::select('ten_the_loai')
            ->where('tinh_trang', 1)
            ->get();

        $phim = Phim::where('slug_phim', $request->slugMovie)->first();
        $tap_phims = TapPhim::where('id_phim', $phim->id)->orderBy('so_tap', 'ASC')->get();
        $tap = TapPhim::where('slug_tap_phim', $request->slugEpisode)->first();
        return response()->json([
            'the_loai'  =>  $the_loai,
            'phim'      =>  $phim,
            'tap_phims' =>  $tap_phims,
            'tap'       =>  $tap,
        ]);
    }
    public function dataTheoTL(Request $request)
    {
        $id_tl    = $request->id_tl;
        $data = Phim::join('the_loais', 'id_the_loai', 'the_loais.id')
            ->join('loai_phims', 'id_loai_phim', 'loai_phims.id')
            ->where('id_the_loai', $id_tl)
            ->select('phims.*', 'the_loais.ten_the_loai', 'loai_phims.ten_loai_phim')
            ->get();
        return response()->json([
            'phim_theo_tl'  =>  $data,
        ]);
    }
    public function getAllPhim()
    {
        $data = DB::table(DB::raw('
        (
            SELECT
                phims.id,
                phims.ten_phim,
                phims.hinh_anh,
                loai_phims.ten_loai_phim,
                phims.slug_phim,
                phims.mo_ta,
                phims.tong_luot_xem,
                phims.so_tap_phim,
                GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
                (
                    SELECT COUNT(tap_phims.id)
                    FROM tap_phims
                    WHERE tap_phims.id_phim = phims.id
                ) as tong_tap
            FROM
                phims
            JOIN
                chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
            JOIN
                loai_phims ON loai_phims.id = phims.id_loai_phim
            JOIN
                the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
            WHERE
                phims.tinh_trang = 1
            AND
                loai_phims.tinh_trang = 1
            AND
                the_loais.tinh_trang = 1
            GROUP BY
                phims.id,loai_phims.ten_loai_phim, phims.ten_phim, phims.hinh_anh, phims.slug_phim, phims.mo_ta, phims.tong_luot_xem, phims.so_tap_phim
            HAVING
                tong_tap > 0
        ) as subquery
    '))
            ->paginate(9);

        $data_9 = DB::table(DB::raw('
        (
            SELECT
                phims.id,
                phims.ten_phim,
                phims.hinh_anh,
                loai_phims.ten_loai_phim,
                phims.slug_phim,
                phims.mo_ta,
                phims.tong_luot_xem,
                phims.so_tap_phim,
                GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
                (
                    SELECT COUNT(tap_phims.id)
                    FROM tap_phims
                    WHERE tap_phims.id_phim = phims.id
                ) as tong_tap
            FROM
                phims
            JOIN
                chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
            JOIN
                loai_phims ON loai_phims.id = phims.id_loai_phim
            JOIN
                the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
            WHERE
                phims.tinh_trang = 1
            AND
                loai_phims.tinh_trang = 1
            AND
                the_loais.tinh_trang = 1
            GROUP BY
                phims.id,loai_phims.ten_loai_phim, phims.ten_phim, phims.hinh_anh, phims.slug_phim, phims.mo_ta, phims.tong_luot_xem, phims.so_tap_phim
            HAVING
                tong_tap > 0
        ) as subquery
    '))
            ->take(8)->inRandomOrder()->get();
        $response = [
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ],
            'dataPhim' => $data
        ];
        return response()->json([
            'phim'             =>  $response,
            'phim_9_obj'       =>  $data_9,
        ]);
    }
    public function getDataHome()
    {
        try {
            $top_view_thang = DB::table(DB::raw('
            (
              SELECT
            p.id,
            p.ten_phim,
            p.hinh_anh,
            p.slug_phim,
            p.mo_ta,
            p.tong_luot_xem,
            p.so_tap_phim,
            lp.ten_loai_phim,
            (
                SELECT GROUP_CONCAT(DISTINCT tl.ten_the_loai SEPARATOR ", ")
                FROM chi_tiet_the_loais ctdl
                JOIN the_loais tl ON tl.id = ctdl.id_the_loai
                WHERE ctdl.id_phim = p.id
            ) AS ten_the_loais,
            (
                SELECT SUM(lx.so_luot_xem)
                FROM luot_xems lx
                WHERE lx.id_phim = p.id
                AND DATE_FORMAT(lx.ngay_xem, "%Y-%m") = DATE_FORMAT(CURRENT_DATE, "%Y-%m")
            ) AS tong_luot_xem_thang
        FROM phims p
        JOIN loai_phims lp ON lp.id = p.id_loai_phim
        WHERE p.tinh_trang = 1
    ) AS ranked_movies'))
                ->select('*')
                ->orderBy('tong_luot_xem_thang', 'desc')
                ->limit(6)
                ->get();
            $phim_moi_cap_nhat = DB::table(DB::raw('
                (
                    SELECT
                        phims.id,
                        phims.ten_phim,
                        loai_phims.ten_loai_phim,
                        phims.hinh_anh,
                        phims.slug_phim,
                        phims.tong_luot_xem,
                        phims.mo_ta,
                        phims.so_tap_phim,
                        MAX(tap_phims.updated_at) as tap_moi_nhat,  -- Lấy thời gian cập nhật mới nhất của tập phim
                        GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
                        (
                            SELECT COUNT(tap_phims.id)
                            FROM tap_phims
                            WHERE tap_phims.id_phim = phims.id
                        ) as tong_tap
                    FROM
                        phims
                    JOIN
                        chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
                    JOIN
                        loai_phims ON loai_phims.id = phims.id_loai_phim
                    JOIN
                        the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
                    LEFT JOIN
                        tap_phims ON tap_phims.id_phim = phims.id
                    WHERE
                        phims.tinh_trang = 1
                    AND
                        the_loais.tinh_trang = 1
                    AND
                        loai_phims.tinh_trang = 1
                    GROUP BY
                        phims.id, phims.ten_phim, loai_phims.ten_loai_phim, phims.hinh_anh, phims.slug_phim, phims.tong_luot_xem, phims.mo_ta, phims.so_tap_phim
                    HAVING
                        tong_tap > 0
                ) as subquery
            '))
                ->orderBy('tap_moi_nhat', 'DESC') // Sắp xếp theo tập mới cập nhật
                ->take(6)
                ->get();


            $tat_ca_phim = DB::table(DB::raw('
                (
                    SELECT
                        phims.id,
                        phims.ten_phim,
                        phims.hinh_anh,
                        loai_phims.ten_loai_phim,
                        phims.slug_phim,
                        phims.mo_ta,
                        phims.tong_luot_xem,
                        phims.so_tap_phim,
                        GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
                        (
                            SELECT COUNT(tap_phims.id)
                            FROM tap_phims
                            WHERE tap_phims.id_phim = phims.id
                        ) as tong_tap
                    FROM
                        phims
                    JOIN
                        chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
                    JOIN
                        loai_phims ON loai_phims.id = phims.id_loai_phim
                    JOIN
                        the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
                    WHERE
                        phims.tinh_trang = 1
                    AND
                        loai_phims.tinh_trang = 1
                    AND
                        the_loais.tinh_trang = 1
                    GROUP BY
                        phims.id,loai_phims.ten_loai_phim, phims.ten_phim, phims.hinh_anh, phims.slug_phim, phims.mo_ta, phims.tong_luot_xem, phims.so_tap_phim
                    HAVING
                        tong_tap > 0
                ) as subquery
            '))
                ->take(6)
                ->get();



            $tat_ca_phim_hoan_thanh = DB::table(DB::raw('
        (
            SELECT
                phims.id,
                phims.ten_phim,
                phims.hinh_anh,
                loai_phims.ten_loai_phim,
                phims.slug_phim,
                phims.mo_ta,
                phims.tong_luot_xem,
                phims.so_tap_phim,
                GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
                (
                    SELECT COUNT(tap_phims.id)
                    FROM tap_phims
                    WHERE tap_phims.id_phim = phims.id
                ) as tong_tap
            FROM
                phims
            JOIN
                chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
            JOIN
                loai_phims ON loai_phims.id = phims.id_loai_phim
            JOIN
                the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
            WHERE
                phims.tinh_trang = 1
            AND
                loai_phims.tinh_trang = 1
            AND
                the_loais.tinh_trang = 1
            GROUP BY
                phims.id, loai_phims.ten_loai_phim, phims.ten_phim, phims.hinh_anh, phims.slug_phim, phims.mo_ta, phims.tong_luot_xem, phims.so_tap_phim
            HAVING
                tong_tap > 0 AND phims.so_tap_phim = tong_tap
        ) as subquery
    '))
                ->take(6)
                ->get();

            $phim_xem_nhieu_nhat = DB::table(DB::raw('
                (
                    SELECT
                        phims.id,
                        phims.ten_phim,
                        loai_phims.ten_loai_phim,
                        phims.hinh_anh,
                        phims.poster_img,
                        phims.slug_phim,
                        phims.tong_luot_xem,
                        phims.mo_ta,
                        phims.so_tap_phim,
                        GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
                        (
                            SELECT COUNT(tap_phims.id)
                            FROM tap_phims
                            WHERE tap_phims.id_phim = phims.id
                        ) as tong_tap
                    FROM
                        phims
                    JOIN
                        chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
                    JOIN
                        loai_phims ON loai_phims.id = phims.id_loai_phim
                    JOIN
                        the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
                    WHERE
                        phims.tinh_trang = 1
                    AND
                        loai_phims.tinh_trang = 1
                    AND
                        the_loais.tinh_trang = 1
                    GROUP BY
                        phims.id, phims.ten_phim, loai_phims.ten_loai_phim, phims.hinh_anh, phims.slug_phim, phims.tong_luot_xem, phims.mo_ta, phims.so_tap_phim,phims.poster_img
                    HAVING
                        tong_tap > 0
                ) as subquery
            '))
                ->orderBy('tong_luot_xem', 'DESC') // Sắp xếp theo tổng lượt xem giảm dần
                ->take(3) // Lấy 6 phim có lượt xem cao nhất
                ->get();
            // $recommendations = [];
            // $user = $this->isUser();
            // if ($user) {
            //     $recommendations = $this->getRecommendationsUser(['user_id' => $user->id]);
            // }
            // // Lấy danh sách ID từ $recommendations
            // $list_id = collect($recommendations)->toArray();
            // if (empty($list_id)) {
            //     $list_id = [1, 2, 3, 4, 5];
            // }
            $phim_hot = DB::table(DB::raw("
                    (
                        SELECT
                            phims.id,
                            phims.ten_phim,
                            loai_phims.ten_loai_phim,
                            phims.hinh_anh,
                            phims.slug_phim,
                            phims.tong_luot_xem,
                            phims.mo_ta,
                            phims.poster_img,
                            phims.so_tap_phim,
                            GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ', ') as ten_the_loais,
                            (
                                SELECT COUNT(tap_phims.id)
                                FROM tap_phims
                                WHERE tap_phims.id_phim = phims.id
                            ) as tong_tap
                        FROM
                            phims
                        JOIN
                            chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
                        JOIN
                            loai_phims ON loai_phims.id = phims.id_loai_phim
                        JOIN
                            the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
                        WHERE
                            phims.tinh_trang = 1
                        AND
                            loai_phims.tinh_trang = 1
                        AND
                            the_loais.tinh_trang = 1
                        GROUP BY
                            phims.id, phims.ten_phim, loai_phims.ten_loai_phim, phims.hinh_anh, phims.slug_phim, phims.tong_luot_xem, phims.mo_ta, phims.so_tap_phim,phims.poster_img
                        HAVING
                            tong_tap > 0
                    ) as subquery
                "))
                ->get();
            // ->setBindings(['slug_phim' => $request->slug])->first();
            // :slug_phim
            return response()->json([
                'phim_hot'                   =>  $phim_hot,
                'phim_moi_cap_nhats'         =>  $phim_moi_cap_nhat,
                'tat_ca_phim'                =>  $tat_ca_phim,
                'top_view_thang'             =>  $top_view_thang,
                'phim_xem_nhieu_nhat'        => $phim_xem_nhieu_nhat,
                'tat_ca_phim_hoan_thanh'     => $tat_ca_phim_hoan_thanh
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getDataDelist(Request $request)
    {
        $isUserTermed = false;

        $user = Auth::guard('sanctum')->user();
        $id_khach_hang = $user ? $user->id : null;

        $phim = DB::table(DB::raw('
                    (
                     SELECT
                        phims.id,
                        phims.ten_phim,
                        phims.slug_phim,
                        phims.hinh_anh,
                        phims.mo_ta,
                        phims.thoi_gian_chieu,
                        phims.nam_san_xuat,
                        phims.quoc_gia,
                        phims.id_loai_phim,
                        phims.id_the_loai,
                        phims.dao_dien,
                        phims.so_tap_phim,
                        phims.tong_luot_xem,
                        phims.tinh_trang,
                        phims.chat_luong,
                        phims.ngon_ngu,
                        phims.trailer_url,
                        loai_phims.ten_loai_phim,
                        GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
                        COUNT(tap_phims.id) as tong_tap
                    FROM phims
                    JOIN chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
                    JOIN the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
                    JOIN loai_phims ON phims.id_loai_phim = loai_phims.id
                    LEFT JOIN tap_phims ON tap_phims.id_phim = phims.id
                    WHERE phims.slug_phim = :slug_phim
                    AND phims.tinh_trang = 1
                    GROUP BY
                        phims.id,
                        phims.ten_phim,
                        phims.slug_phim,
                        phims.hinh_anh,
                        phims.mo_ta,
                        phims.thoi_gian_chieu,
                        phims.nam_san_xuat,
                        phims.quoc_gia,
                        phims.id_loai_phim,
                        phims.id_the_loai,
                        phims.dao_dien,
                        phims.so_tap_phim,
                        phims.tong_luot_xem,
                        phims.tinh_trang,
                        phims.chat_luong,
                        phims.ngon_ngu,
                        phims.trailer_url,
                        loai_phims.ten_loai_phim
                    HAVING tong_tap > 0
                    LIMIT 1
                )
                    as phim
                '))->setBindings(['slug_phim' => $request->slug])->first();

        if (!$phim) {
            return response()->json([
                'status' => 404,
                'message' => 'Không tìm thấy phim'
            ], 404);
        }

        if ($user instanceof \App\Models\KhachHang) {
            $goihientai = HoaDon::where('id_khach_hang', $id_khach_hang)
                ->where('tinh_trang', 1)
                ->where('ngay_bat_dau', '<=', now())
                ->where('ngay_ket_thuc', '>=', now())
                ->latest()
                ->first();

            $isUserTermed = (bool) $goihientai;
        }

        try {
            $tap = TapPhim::where('id_phim', $phim->id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'Không tìm thấy tập phim'
            ], 404);
        }
            // $recommendations = $this->getRecommendations(['movie_id' => $phim->id]);
            // $list_id = collect($recommendations)->toArray();
            // if (empty($list_id)) {
            //     $list_id = [1, 2, 3, 4, 5];
            // }
        $select5film = DB::table(DB::raw("
                (
                    SELECT
                        phims.id,
                        phims.ten_phim,
                        loai_phims.ten_loai_phim,
                        phims.hinh_anh,
                        phims.slug_phim,
                        phims.tong_luot_xem,
                        phims.mo_ta,
                        phims.so_tap_phim,
                        GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ', ') as ten_the_loais,
                        (
                            SELECT COUNT(tap_phims.id)
                            FROM tap_phims
                            WHERE tap_phims.id_phim = phims.id
                        ) as tong_tap
                    FROM
                        phims
                    JOIN
                        chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
                    JOIN
                        loai_phims ON loai_phims.id = phims.id_loai_phim
                    JOIN
                        the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
                    WHERE
                        phims.tinh_trang = 1
                    AND
                        loai_phims.tinh_trang = 1
                    AND
                        the_loais.tinh_trang = 1
                    GROUP BY
                        phims.id, phims.ten_phim, loai_phims.ten_loai_phim, phims.hinh_anh, phims.slug_phim, phims.tong_luot_xem, phims.mo_ta, phims.so_tap_phim
                    HAVING
                        tong_tap > 0
                ) as subquery
            "))
            ->get();

        return response()->json([
            'phim'          => $phim,
            'phim_5_obj'    => $select5film,
            'tap'           => $tap,
            'isUserTermed'  => $isUserTermed,
        ]);
    }
    public function sapxepHome(Request $request)
    {
        $catagory = $request->catagory;
        if ($catagory === 'az') {
            $data = Phim::join('the_loais', 'id_the_loai', 'the_loais.id')
                ->join('loai_phims', 'id_loai_phim', 'loai_phims.id')
                ->select('phims.*', 'the_loais.ten_the_loai', 'loai_phims.ten_loai_phim')
                ->orderBy('ten_phim', 'ASC')  // tăng dần
                ->get();
        } else if ($catagory === 'za') {
            $data = Phim::join('the_loais', 'id_the_loai', 'the_loais.id')
                ->join('loai_phims', 'id_loai_phim', 'loai_phims.id')
                ->select('phims.*', 'the_loais.ten_the_loai', 'loai_phims.ten_loai_phim')
                ->orderBy('ten_phim', 'DESC')  // giảm dần
                ->get();
        }
        return response()->json([
            'phim'  =>  $data,
        ]);
    }
    public function sapxepAllPhim($catagory)
    {
        $data = DB::table(DB::raw('
        (
            SELECT
                phims.id,
                phims.ten_phim,
                loai_phims.ten_loai_phim,
                phims.hinh_anh,
                phims.slug_phim,
                phims.tong_luot_xem,
                phims.mo_ta,
                phims.so_tap_phim,
                GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
                (
                    SELECT COUNT(tap_phims.id)
                    FROM tap_phims
                    WHERE tap_phims.id_phim = phims.id
                ) as tong_tap
            FROM
                phims
            JOIN
                chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
            JOIN
                loai_phims ON loai_phims.id = phims.id_loai_phim
            JOIN
                the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
            WHERE
                phims.tinh_trang = 1
            AND
                loai_phims.tinh_trang = 1
            AND
                the_loais.tinh_trang = 1
            GROUP BY
                phims.id, phims.ten_phim, loai_phims.ten_loai_phim, phims.hinh_anh, phims.slug_phim, phims.tong_luot_xem, phims.mo_ta, phims.so_tap_phim
            HAVING
                tong_tap > 0
        ) as subquery
    '))
            ->orderBy('ten_phim', $catagory) // Sắp xếp theo tổng lượt xem giảm dần
            ->paginate(9);
        $response = [
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ],
            'dataPhim' => $data
        ];
        return response()->json([
            'phim'  =>  $response,
            'response'  =>  $response,
        ]);
    }
    public function watchingFilm($slugMovie, $slugEpisode)
    {
        $the_loai = TheLoai::select('ten_the_loai')
            ->where('tinh_trang', 1)
            ->get();

        $phim = Phim::where('slug_phim', $slugMovie)->firstOrFail();
        $tap_phims = TapPhim::where('id_phim', $phim->id)->orderBy('so_tap', 'ASC')->get();
        $tap = TapPhim::where('slug_tap_phim', $slugEpisode)->firstOrFail();
        return response()->json([
            'the_loai'  =>  $the_loai,
            'phim'      =>  $phim,
            'tap_phims' =>  $tap_phims,
            'tap'       =>  $tap,
        ]);
    }

    public function taoPhim(TaoPhimRequest $request)
    {
        try {
            $id_chuc_nang = 5;
            $check = $this->checkQuyen($id_chuc_nang);
            if ($check == false) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            if ($request->is_add_url && filter_var($request->hinh_anh, FILTER_VALIDATE_URL)) {
                $filePath = $request->hinh_anh;
            } else {
                if ($request->hasFile('hinh_anh')) {
                    $file = $request->file('hinh_anh');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/admin/phim/thumbail'), $fileName);
                    $filePath = asset('uploads/admin/phim/thumbail/' . $fileName);
                }
            }

            if ($request->is_add_url && filter_var($request->poster_img, FILTER_VALIDATE_URL)) {
                $filePathPoster = $request->poster_img;
            } else {
                if ($request->hasFile('poster_img')) {
                    $file = $request->file('poster_img');
                    $fileNamePoster = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/admin/phim/poster'), $fileNamePoster);
                    $filePathPoster = asset('uploads/admin/phim/poster/' . $fileNamePoster);
                }
            }

            $theloaisArray = explode(',', $request->the_loais);

            $phim = Phim::create([
                'ten_phim'                  => $request->ten_phim,
                'slug_phim'                 => $request->slug_phim,
                'hinh_anh'                  => $filePath,
                'poster_img'                => $filePathPoster,
                'mo_ta'                     => $request->mo_ta,
                'thoi_gian_chieu'           => $request->thoi_gian_chieu,
                'nam_san_xuat'              => $request->nam_san_xuat,
                'quoc_gia'                  => $request->quoc_gia,
                'id_loai_phim'              => $request->id_loai_phim,
                'dao_dien'                  => $request->dao_dien,
                'so_tap_phim'               => $request->so_tap_phim,
                'tinh_trang'                => $request->tinh_trang,
                'ngon_ngu'                  => $request->ngon_ngu,
                'trailer_url'               => $request->trailer_url,
                'chat_luong'                => $request->chat_luong,
            ]);

            if ($phim) {
                foreach ($theloaisArray as $value) {
                    ChiTietTheLoai::create([
                        'id_phim' => $phim->id,
                        'id_the_loai' => (int) $value,
                    ]);
                }
            }
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn thêm Phim thành công!',
            ]);
        } catch (ExceptionEvent $e) {
            return response()->json([
                'status'     => false,
                'message'    => 'thêm Phim không thành công!!'
            ]);
        }
    }

    public function timPhim(Request $request)
    {
        $id_chuc_nang = 5;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $key    = '%' . $request->key . '%';
        $the_loai_admin   = TheLoai::where('tinh_trang', 1)->select('the_loais.*')
            ->get();
        $loai_phim_admin   = LoaiPhim::where('tinh_trang', 1)->select('loai_phims.*')
            ->get();
        $dataAdmin   = Phim::join('loai_phims', 'id_loai_phim', 'loai_phims.id')
            ->where('phims.ten_phim', 'like', $key)
            ->select('phims.*',  'loai_phims.ten_loai_phim')
            ->orderBy('created_at', 'DESC')
            ->paginate(6); // get là ra 1  sách
        $theloais = ChiTietTheLoai::join('the_loais', 'chi_tiet_the_loais.id_the_loai', 'the_loais.id')
            ->select('chi_tiet_the_loais.*', 'the_loais.id', 'the_loais.ten_the_loai', 'chi_tiet_the_loais.id_phim')
            ->get();
        $pagination = [
            'total' => $dataAdmin->total(),
            'per_page' => $dataAdmin->perPage(),
            'current_page' => $dataAdmin->currentPage(),
            'last_page' => $dataAdmin->lastPage(),
            'from' => $dataAdmin->firstItem(),
            'to' => $dataAdmin->lastItem()
        ];

        $phimsArray = $dataAdmin->toArray();

        foreach ($phimsArray['data'] as &$phim) {
            $the_loais = [];
            foreach ($theloais as $theLoai) {
                if ($theLoai['id_phim'] == $phim['id']) {
                    array_push($the_loais, $theLoai->toArray());
                }
            }
            $phim['the_loais'] = $the_loais;
        }
        unset($phim);

        $dataAdmin = $phimsArray;

        $response = [
            'dataAdmin' => $dataAdmin,
            'pagination' => $pagination
        ];

        return response()->json([
            'phim_admin'  =>  $response,
            'the_loai_admin'  =>  $the_loai_admin,
            'loai_phim_admin'  =>  $loai_phim_admin,
        ]);
    }
    public function xoaPhim($id)
    {
        try {
            $id_chuc_nang = 5;
            $check = $this->checkQuyen($id_chuc_nang);
            if ($check == false) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            $phim = Phim::where('id', $id)->first();
            if ($phim->hinh_anh && file_exists(public_path('uploads/admin/phim/' . basename($phim->hinh_anh)))) {
                unlink(public_path('uploads/admin/phim/' . basename($phim->hinh_anh)));
            }
            ChiTietTheLoai::where('id_phim', $id)->delete();
            TapPhim::where('id_phim', $id)->delete();
            Phim::where('id', $id)->delete();
            return response()->json([
                'status'     => true,
                'message'    => 'Đã xoá Phim thành công!!'
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Xoá Phim không thành công!!'
            ]);
        }
    }

    public function capnhatPhim(CapNhatPhimRequest $request)
    {
        try {
            $id_chuc_nang = 5;
            $check = $this->checkQuyen($id_chuc_nang);
            if ($check == false) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            $phim = Phim::find($request->id);
            if (!$phim) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy Phim',
                ]);
            }

            if ($request->is_update_url && filter_var($request->hinh_anh, FILTER_VALIDATE_URL)) {
                $filePath = $request->hinh_anh;
            } else {
                $filePath = $phim->hinh_anh; // Giữ nguyên ảnh cũ nếu không có ảnh mới
                if ($request->hasFile('hinh_anh')) {
                    $file = $request->file('hinh_anh');
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/admin/phim/thumbnail'), $fileName);
                    $filePath = asset('uploads/admin/phim/thumbnail/' . $fileName);

                    // Xóa ảnh thumbnail cũ nếu tồn tại
                    if ($phim->hinh_anh && file_exists(public_path('uploads/admin/phim/thumbnail/' . basename($phim->hinh_anh)))) {
                        unlink(public_path('uploads/admin/phim/thumbnail/' . basename($phim->hinh_anh)));
                    }
                }
            }
            if ($request->is_update_url && filter_var($request->poster_img, FILTER_VALIDATE_URL)) {
                $filePathPoster = $request->poster_img;
            } else {
                // Xử lý ảnh Poster
                $filePathPoster = $phim->poster_img; // Giữ nguyên ảnh cũ nếu không có ảnh mới
                if ($request->hasFile('poster_img')) {
                    $filePoster = $request->file('poster_img');
                    $fileNamePoster = time() . '_' . $filePoster->getClientOriginalName();
                    $filePoster->move(public_path('uploads/admin/phim/poster'), $fileNamePoster);
                    $filePathPoster = asset('uploads/admin/phim/poster/' . $fileNamePoster);

                    // Xóa ảnh poster cũ nếu tồn tại
                    if ($phim->poster_img && file_exists(public_path('uploads/admin/phim/poster/' . basename($phim->poster_img)))) {
                        unlink(public_path('uploads/admin/phim/poster/' . basename($phim->poster_img)));
                    }
                }
            }


            // Cập nhật thông tin phim
            $phim->update([
                'ten_phim'           => $request->ten_phim,
                'slug_phim'          => $request->slug_phim,
                'hinh_anh'           => $filePath,
                'poster_img'         => $filePathPoster,
                'mo_ta'              => $request->mo_ta,
                'thoi_gian_chieu'    => $request->thoi_gian_chieu,
                'nam_san_xuat'       => $request->nam_san_xuat,
                'quoc_gia'           => $request->quoc_gia,
                'id_loai_phim'       => $request->id_loai_phim,
                'dao_dien'           => $request->dao_dien,
                'so_tap_phim'        => $request->so_tap_phim,
                'tinh_trang'         => $request->tinh_trang,
                'ngon_ngu'   => $request->ngon_ngu,
                'trailer_url'        => $request->trailer_url,
                'chat_luong'         => $request->chat_luong,
            ]);

            // Cập nhật thể loại
            ChiTietTheLoai::where('id_phim', $phim->id)->delete(); // Xóa các thể loại hiện tại
            $theloaisArray = explode(',', $request->the_loais);
            foreach ($theloaisArray as $value) {
                ChiTietTheLoai::create([
                    'id_phim' => $phim->id,
                    'id_the_loai' => (int) $value,
                ]);
            }


            return response()->json([
                'status'     => true,
                'message'    => 'Đã Cập Nhật thành công ' . $request->ten_phim,
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Cập Nhật Phim không thành công!!'
            ]);
        }
    }

    public function thaydoiTrangThaiPhim(ThayDoiTrangThaiPhimRequest  $request)
    {

        try {
            $id_chuc_nang = 5;
            $check = $this->checkQuyen($id_chuc_nang);
            if ($check == false) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            $tinh_trang_moi = !$request->tinh_trang;
            //   $tinh_trang_moi là trái ngược của $request->tinh_trangs
            Phim::where('id', $request->id)
                ->update([
                    'tinh_trang'    => $tinh_trang_moi
                ]);

            return response()->json([
                'status'     => true,
                'message'    => 'Cập Nhật Trạng Thái thành công!! '
            ]);
        } catch (Exception $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Cập Nhật Trạng Thái không thành công!!'
            ]);
        }
    }
    public function timPhimHome(Request $request)
    {
        $key    = '%' . $request->key . '%';
        if ($request->key == "") {
            $data = [];
        } else {
            $data = DB::table(DB::raw('
    (
        SELECT
            phims.id,
            phims.ten_phim,
            phims.hinh_anh,
            loai_phims.ten_loai_phim,
            phims.slug_phim,
            phims.mo_ta,
            phims.tong_luot_xem,
            phims.so_tap_phim,
            GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
            (
                SELECT COUNT(tap_phims.id)
                FROM tap_phims
                WHERE tap_phims.id_phim = phims.id
            ) as tong_tap
        FROM
            phims
        JOIN
            chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
        JOIN
            loai_phims ON loai_phims.id = phims.id_loai_phim
        JOIN
            the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
        WHERE
            phims.tinh_trang = 1
        AND
            loai_phims.tinh_trang = 1
        AND
            the_loais.tinh_trang = 1
        AND
            phims.ten_phim LIKE ?
        GROUP BY
            phims.id, loai_phims.ten_loai_phim, phims.ten_phim, phims.hinh_anh, phims.slug_phim, phims.mo_ta, phims.tong_luot_xem, phims.so_tap_phim
        HAVING
            tong_tap > 0
    ) as subquery
'))
                ->setBindings([$key]) // Bind the key parameter
                ->get();
        }
        return response()->json([
            'phim'  =>  $data,
        ]);
    }
    public function loadTimPhimHome(Request $request)
    {
        $key    = '%' . $request->key . '%';
        if ($request->key == "") {
            $data = [];
        } else {
            $data = DB::table(DB::raw('
    (
        SELECT
            phims.id,
            phims.ten_phim,
            phims.hinh_anh,
            loai_phims.ten_loai_phim,
            phims.slug_phim,
            phims.mo_ta,
            phims.tong_luot_xem,
            phims.so_tap_phim,
            GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
            (
                SELECT COUNT(tap_phims.id)
                FROM tap_phims
                WHERE tap_phims.id_phim = phims.id
            ) as tong_tap
        FROM
            phims
        JOIN
            chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
        JOIN
            loai_phims ON loai_phims.id = phims.id_loai_phim
        JOIN
            the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
        WHERE
            phims.tinh_trang = 1
        AND
            loai_phims.tinh_trang = 1
        AND
            the_loais.tinh_trang = 1
        AND
            phims.ten_phim LIKE ?
        GROUP BY
            phims.id, loai_phims.ten_loai_phim, phims.ten_phim, phims.hinh_anh, phims.slug_phim, phims.mo_ta, phims.tong_luot_xem, phims.so_tap_phim
        HAVING
            tong_tap > 0
    ) as subquery
'))
                ->setBindings([$key]) // Bind the key parameter
                ->paginate(12);
        }
        $response = [
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem()
            ],
            'dataPhim' => $data
        ];
        return response()->json([
            'phim'  =>  $response,
        ]);
    }
    public function kiemTraSlugPhim(Request $request)
    {
        $id_chuc_nang = 5;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $phim = Phim::where('slug_phim', $request->slug)->first();

        if (!$phim) {
            return response()->json([
                'status'            =>   true,
                'message'           =>   'Tên Phim phù hợp!',
            ]);
        } else {
            return response()->json([
                'status'            =>   false,
                'message'           =>   'Tên Phim Đã Tồn Tại!',
            ]);
        }
    }
    public function kiemTraSlugPhimUpdate(Request $request)
    {
        $id_chuc_nang = 5;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $mon_an = Phim::where('slug_phim', $request->slug)
            ->where('id', '<>', $request->id)
            ->first();

        if (!$mon_an) {
            return response()->json([
                'status'            =>   true,
                'message'           =>   'Tên Phim phù hợp!',
            ]);
        } else {
            return response()->json([
                'status'            =>   false,
                'message'           =>   'Tên Phim Đã Tồn Tại!',
            ]);
        }
    }
    public function getdataAI()
    {
        try {
            $top_view_thang = DB::table(DB::raw('
            (
                SELECT
                    phims.ten_phim,
                    phims.hinh_anh,
                    phims.slug_phim,
                    phims.mo_ta,
                    phims.tong_luot_xem,
                    phims.so_tap_phim,
                    loai_phims.ten_loai_phim,
                    GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") AS ten_the_loais,  -- Lấy thể loại phim
                    COUNT(DISTINCT tap_phims.id) AS tong_tap,  -- Đếm số tập phim
                    SUM(luot_xems.so_luot_xem) AS tong_luot_xem_thang,  -- Tổng lượt xem trong tháng
                    DATE_FORMAT(luot_xems.ngay_xem, "%Y-%m") AS thang,
                    RANK() OVER (PARTITION BY DATE_FORMAT(luot_xems.ngay_xem, "%Y-%m") ORDER BY SUM(luot_xems.so_luot_xem) DESC) AS `rank`
                FROM
                    phims
                INNER JOIN
                    luot_xems ON luot_xems.id_phim = phims.id
                INNER JOIN
                    chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id  -- Kết nối bảng chi tiết thể loại
                INNER JOIN
                    the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id  -- Kết nối bảng thể loại
                LEFT JOIN
                    tap_phims ON tap_phims.id_phim = phims.id  -- Kết nối với bảng tập phim
                LEFT JOIN
                    loai_phims ON loai_phims.id = phims.id_loai_phim  -- Kết nối với loại phim
                WHERE
                    phims.tinh_trang = 1 AND
                    the_loais.tinh_trang = 1 AND
                    loai_phims.tinh_trang = 1
                GROUP BY
                    phims.id, phims.ten_phim, phims.hinh_anh, phims.slug_phim, phims.mo_ta, phims.tong_luot_xem, phims.so_tap_phim, loai_phims.ten_loai_phim, thang
            ) AS ranked_movies
        '))
                ->where('rank', 1)  // Chỉ lấy phim đứng đầu mỗi tháng
                ->groupBy(
                    'ten_phim',
                    'hinh_anh',
                    'slug_phim',
                    'mo_ta',
                    'tong_luot_xem',
                    'so_tap_phim',
                    'ten_loai_phim',
                    'thang'
                )  // Nhóm theo các thông tin cần thiết
                ->orderBy('tong_luot_xem_thang', 'desc')  // Sắp xếp theo tổng lượt xem trong tháng giảm dần
                ->take(1)  // Giới hạn kết quả trả về là 6 phim
                ->get();
            $phim_moi_cap_nhat = DB::table(DB::raw('
                (
                    SELECT
                        phims.id,
                        phims.ten_phim,
                        loai_phims.ten_loai_phim,
                        phims.hinh_anh,
                        phims.slug_phim,
                        phims.tong_luot_xem,
                        phims.mo_ta,
                        phims.so_tap_phim,
                        MAX(tap_phims.updated_at) as tap_moi_nhat,  -- Lấy thời gian cập nhật mới nhất của tập phim
                        GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
                        (
                            SELECT COUNT(tap_phims.id)
                            FROM tap_phims
                            WHERE tap_phims.id_phim = phims.id
                        ) as tong_tap
                    FROM
                        phims
                    JOIN
                        chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
                    JOIN
                        loai_phims ON loai_phims.id = phims.id_loai_phim
                    JOIN
                        the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
                    LEFT JOIN
                        tap_phims ON tap_phims.id_phim = phims.id
                    WHERE
                        phims.tinh_trang = 1
                    AND
                        the_loais.tinh_trang = 1
                    AND
                        loai_phims.tinh_trang = 1
                    GROUP BY
                        phims.id, phims.ten_phim, loai_phims.ten_loai_phim, phims.hinh_anh, phims.slug_phim, phims.tong_luot_xem, phims.mo_ta, phims.so_tap_phim
                    HAVING
                        tong_tap > 0
                ) as subquery
            '))
                ->orderBy('tap_moi_nhat', 'DESC') // Sắp xếp theo tập mới cập nhật
                ->take(2)
                ->get();


            $tat_ca_phim = DB::table(DB::raw('
                (
                    SELECT
                        phims.id,
                        phims.ten_phim,
                        phims.hinh_anh,
                        loai_phims.ten_loai_phim,
                        phims.slug_phim,
                        phims.mo_ta,
                        phims.tong_luot_xem,
                        phims.so_tap_phim,
                        GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
                        (
                            SELECT COUNT(tap_phims.id)
                            FROM tap_phims
                            WHERE tap_phims.id_phim = phims.id
                        ) as tong_tap
                    FROM
                        phims
                    JOIN
                        chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
                    JOIN
                        loai_phims ON loai_phims.id = phims.id_loai_phim
                    JOIN
                        the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
                    WHERE
                        phims.tinh_trang = 1
                    AND
                        loai_phims.tinh_trang = 1
                    AND
                        the_loais.tinh_trang = 1
                    GROUP BY
                        phims.id,loai_phims.ten_loai_phim, phims.ten_phim, phims.hinh_anh, phims.slug_phim, phims.mo_ta, phims.tong_luot_xem, phims.so_tap_phim
                    HAVING
                        tong_tap > 0
                ) as subquery
            '))
                ->get();
            $phim_hot = DB::table(DB::raw('
                (
                    SELECT
                        phims.id,
                        phims.ten_phim,
                        loai_phims.ten_loai_phim,
                        phims.hinh_anh,
                        phims.slug_phim,
                        phims.tong_luot_xem,
                        phims.mo_ta,
                        phims.so_tap_phim,
                        GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais,
                        (
                            SELECT COUNT(tap_phims.id)
                            FROM tap_phims
                            WHERE tap_phims.id_phim = phims.id
                        ) as tong_tap
                    FROM
                        phims
                    JOIN
                        chi_tiet_the_loais ON chi_tiet_the_loais.id_phim = phims.id
                    JOIN
                        loai_phims ON loai_phims.id = phims.id_loai_phim
                    JOIN
                        the_loais ON chi_tiet_the_loais.id_the_loai = the_loais.id
                    WHERE
                        phims.tinh_trang = 1
                    AND
                        loai_phims.tinh_trang = 1
                    AND
                        the_loais.tinh_trang = 1
                    GROUP BY
                        phims.id, phims.ten_phim, loai_phims.ten_loai_phim, phims.hinh_anh, phims.slug_phim, phims.tong_luot_xem, phims.mo_ta, phims.so_tap_phim
                    HAVING
                        tong_tap > 0
                ) as subquery
            '))
                ->orderBy('tong_luot_xem', 'DESC')
                ->inRandomOrder() // Sắp xếp theo tổng lượt xem giảm dần
                ->take(3) // Lấy 6 phim có lượt xem cao nhất
                ->get();

            return response()->json([
                'phim_hot'                   =>  $phim_hot,
                'phim_moi_cap_nhats'         =>  $phim_moi_cap_nhat,
                'top_view_thang'             =>  $top_view_thang,
                'tat_ca_phim'                =>  $tat_ca_phim,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function  getdataUserAI()
    {
        $result = DB::table('yeu_thichs as yt')
            ->join('khach_hangs as kh', 'yt.id_khach_hang', '=', 'kh.id')
            ->join('phims as p', 'yt.id_phim', '=', 'p.id')
            ->join('chi_tiet_the_loais as ctl', 'p.id', '=', 'ctl.id_phim')
            ->join('the_loais as tl', 'ctl.id_the_loai', '=', 'tl.id')
            ->where('kh.id', 11)
            ->select(
                'kh.id as id_khach_hang',
                'kh.ho_va_ten as ten_khach_hang',
                'p.ten_phim',
                'tl.ten_the_loai'
            )
            ->get();

        return response()->json([
            'result' => $result
        ]);
    }
    public function getLichSuXem()
    {
        try {
            $user = $this->isUser();
            if (!$user) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Bạn cần đăng nhập để xem lịch sử xem phim'
                ], 401);
            }

            // Sử dụng subquery để lấy lần xem gần nhất của mỗi phim
            $lichSuXem = DB::table('luot_xems')
                ->join('phims', 'luot_xems.id_phim', '=', 'phims.id')
                ->where('luot_xems.id_khach_hang', $user->id)
                ->select(
                    'phims.id',
                    'phims.ten_phim as tenPhim',
                    'phims.slug_phim as slug',
                    'phims.hinh_anh as poster',
                    'luot_xems.so_luot_xem as thoiLuongXem',
                    'luot_xems.created_at as ngayXem'
                )
                ->whereIn('luot_xems.created_at', function ($query) use ($user) {
                    $query->select(DB::raw('MAX(created_at)'))
                        ->from('luot_xems')
                        ->where('id_khach_hang', $user->id)
                        ->groupBy('id_phim');
                })
                ->orderBy('luot_xems.created_at', 'desc')
                ->get();

            return response()->json([
                'status'    => true,
                'data'      => $lichSuXem
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Không thể lấy lịch sử xem: ' . $e->getMessage()
            ], 500);
        }
    }
    public function locPhimHomePage(Request $request)
    {
        try {
            $query = DB::table('phims')
                ->select(
                    'phims.id',
                    'phims.ten_phim',
                    'phims.hinh_anh',
                    'phims.slug_phim',
                    'phims.mo_ta',
                    'phims.tong_luot_xem',
                    'phims.so_tap_phim',
                    'loai_phims.ten_loai_phim',
                    DB::raw('GROUP_CONCAT(DISTINCT the_loais.ten_the_loai SEPARATOR ", ") as ten_the_loais'),
                    DB::raw('COUNT(DISTINCT tap_phims.id) as tong_tap')
                )
                ->join('loai_phims', 'phims.id_loai_phim', '=', 'loai_phims.id')
                ->join('chi_tiet_the_loais', 'phims.id', '=', 'chi_tiet_the_loais.id_phim')
                ->join('the_loais', 'chi_tiet_the_loais.id_the_loai', '=', 'the_loais.id')
                ->leftJoin('tap_phims', 'phims.id', '=', 'tap_phims.id_phim')
                ->where('phims.tinh_trang', 1)
                ->where('loai_phims.tinh_trang', 1)
                ->where('the_loais.tinh_trang', 1);

            // Tìm kiếm
            if ($request->search && $request->search !== 'null') {
                $query->where(function ($q) use ($request) {
                    $q->where('phims.ten_phim', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('phims.mo_ta', 'LIKE', '%' . $request->search . '%');
                });
            }

            // Lọc theo thể loại
            if ($request->the_loai && !in_array('null', $request->the_loai)) {
                $query->whereIn('the_loais.id', $request->the_loai);
            }

            // Lọc theo loại phim
            if ($request->loai_phim && $request->loai_phim !== 'null') {
                $query->where('phims.id_loai_phim', $request->loai_phim);
            }

            // Lọc theo trạng thái
            if ($request->has('tinh_trang') && $request->tinh_trang !== 'null') {
                $request->tinh_trang == 2 ? $query->where('phims.is_hoan_thanh', 1) : $query->where('phims.is_hoan_thanh', 0);
            }

            // Group by
            $query->groupBy(
                'phims.id',
                'phims.ten_phim',
                'phims.hinh_anh',
                'phims.slug_phim',
                'phims.mo_ta',
                'phims.tong_luot_xem',
                'phims.so_tap_phim',
                'loai_phims.ten_loai_phim'
            );

            // Thêm having để lọc tong_tap > 0
            $query->having('tong_tap', '>', 0);

            // Sắp xếp
            switch ($request->sap_xep) {
                case 'moi-nhat':
                    $query->orderBy('phims.created_at', 'desc');
                    break;
                case 'cu-nhat':
                    $query->orderBy('phims.created_at', 'asc');
                    break;
                case 'luot-xem':
                    $query->orderBy('phims.tong_luot_xem', 'desc');
                    break;
                default:
                    $query->orderBy('phims.created_at', 'desc');
            }

            // Phân trang
            $phims = $query->paginate(env('PAGINATION_LIMIT'));

            return response()->json([
                'status' => true,
                'message' => 'Lấy danh sách phim thành công',
                'data' => $phims->items(),
                'meta' => [
                    'current_page' => $phims->currentPage(),
                    'from' => $phims->firstItem(),
                    'last_page' => $phims->lastPage(),
                    'per_page' => $phims->perPage(),
                    'to' => $phims->lastItem(),
                    'total' => $phims->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
