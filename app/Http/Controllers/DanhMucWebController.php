<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapNhatDanhMucRequest;
use App\Http\Requests\TaoDanhMucRequest;
use App\Http\Requests\ThayDoiTrangThaiDanhMucRequest;
use App\Models\DanhMucWeb;
use App\Models\PhanQuyen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Quản Lý Danh Mục API Documentation",
 *     description="API documentation cho quản lý danh mục web"
 * )
 */
class DanhMucWebController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/du-lieu-train-ai",
     *     summary="Lấy danh sách danh mục",
     *     tags={"Danh Mục"},
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="danh_muc_admin", type="object",
     *                 @OA\Property(property="pagination", type="object",
     *                     @OA\Property(property="total", type="integer"),
     *                     @OA\Property(property="per_page", type="integer"),
     *                     @OA\Property(property="current_page", type="integer"),
     *                     @OA\Property(property="last_page", type="integer"),
     *                     @OA\Property(property="from", type="integer"),
     *                     @OA\Property(property="to", type="integer")
     *                 ),
     *                 @OA\Property(property="dataAdmin", type="array",
     *                     @OA\Items(type="object")
     *                 )
     *             ),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Không có quyền truy cập"
     *     )
     * )
     */
    public function getData()
    {
        $id_chuc_nang = 14;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $dataAdmin   = DanhMucWeb::leftjoin('danh_muc_webs as cha', 'danh_muc_webs.id_danh_muc_cha', 'cha.id')->select('danh_muc_webs.*', 'cha.ten_danh_muc as ten_danh_muc_cha')
            ->paginate(6); // get là ra 1  sách
        $data = DanhMucWeb::leftjoin('danh_muc_webs as cha', 'danh_muc_webs.id_danh_muc_cha', 'cha.id')->where('danh_muc_webs.tinh_trang', 1)->select('danh_muc_webs.*', 'cha.ten_danh_muc as ten_danh_muc_cha')->get();
        $response = [
            'pagination' => [
                'total' => $dataAdmin->total(),
                'per_page' => $dataAdmin->perPage(),
                'current_page' => $dataAdmin->currentPage(),
                'last_page' => $dataAdmin->lastPage(),
                'from' => $dataAdmin->firstItem(),
                'to' => $dataAdmin->lastItem()
            ],
            'dataAdmin' => $dataAdmin,

        ];
        return response()->json([
            'danh_muc_admin'  =>  $response,
            'data'      => $data
        ]);
    }
    /**
     * @OA\Post(
     *     path="/api/danh-muc/tao-danh-muc",
     *     summary="Tạo danh mục mới",
     *     tags={"Danh Mục"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"ten_danh_muc","slug_danh_muc"},
     *             @OA\Property(property="ten_danh_muc", type="string", example="Danh mục mới"),
     *             @OA\Property(property="slug_danh_muc", type="string", example="danh-muc-moi"),
     *             @OA\Property(property="id_danh_muc_cha", type="integer", nullable=true),
     *             @OA\Property(property="link", type="string", example="/danh-muc-moi")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Bạn thêm Danh Mục thành công!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Không có quyền truy cập"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu không hợp lệ"
     *     )
     * )
     */
    public function taoDanhMuc(TaoDanhMucRequest $request)
    {
        try {
            $id_chuc_nang = 14;
            $check = $this->checkQuyen($id_chuc_nang);
            if ($check == false) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            DanhMucWeb::create([
                'ten_danh_muc'          => $request->ten_danh_muc,
                'slug_danh_muc'         => $request->slug_danh_muc,
                'id_danh_muc_cha'       => $request->id_danh_muc_cha,
                'link'                  => $request->link,
            ]);
            return response()->json([
                'status'   => true,
                'message'  => 'Bạn thêm Danh Mục thành công!',
            ]);
        } catch (ExceptionEvent $e) {
            return response()->json([
                'status'     => false,
                'message'    => 'Xoá Danh Mục không thành công!!'
            ]);
        }
    }
    public function timDanhMuc(Request $request)
    {
        $id_chuc_nang = 14;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $key    = '%' . $request->key . '%';
        $dataAdmin   = DanhMucWeb::select('danh_muc_webs.*')
            ->where('ten_danh_muc', 'like', $key)
            ->paginate(6); // get là ra 1  sách
        $response = [
            'pagination' => [
                'total' => $dataAdmin->total(),
                'per_page' => $dataAdmin->perPage(),
                'current_page' => $dataAdmin->currentPage(),
                'last_page' => $dataAdmin->lastPage(),
                'from' => $dataAdmin->firstItem(),
                'to' => $dataAdmin->lastItem()
            ],
            'dataAdmin' => $dataAdmin
        ];
        return response()->json([
            'danh_muc_admin'  =>  $response,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/danh-muc/cap-nhat",
     *     summary="Cập nhật danh mục",
     *     tags={"Danh Mục"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id","ten_danh_muc","slug_danh_muc"},
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="ten_danh_muc", type="string"),
     *             @OA\Property(property="slug_danh_muc", type="string"),
     *             @OA\Property(property="id_danh_muc_cha", type="integer", nullable=true),
     *             @OA\Property(property="tinh_trang", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function capnhatDanhMuc(CapNhatDanhMucRequest $request)
    {
        try {
            $id_chuc_nang = 14;
            $check = $this->checkQuyen($id_chuc_nang);
            if ($check == false) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            DanhMucWeb::where('id', $request->id)
                ->update([
                    'ten_danh_muc'          => $request->ten_danh_muc,
                    'slug_danh_muc'         => $request->slug_danh_muc,
                    'id_danh_muc_cha'       => $request->id_danh_muc_cha,
                    'tinh_trang'            => $request->tinh_trang,

                ]);
            return response()->json([
                'status'     => true,
                'message'    => 'Đã Cập Nhật thành ' . $request->ten_danh_muc,
            ]);
        } catch (Exception $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Cập Nhật  Danh Mục không thành công!!'
            ]);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/danh-muc/xoa/{id}",
     *     summary="Xóa danh mục",
     *     tags={"Danh Mục"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID của danh mục cần xóa",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function xoaDanhMuc($id)
    {
        try {
            $id_chuc_nang = 14;
            $check = $this->checkQuyen($id_chuc_nang);
            if ($check == false) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            DanhMucWeb::where('id', $id)->delete();

            return response()->json([
                'status'     => true,
                'message'    => 'Đã xoá Danh Mục thành công!!'
            ]);
        } catch (ExceptionEvent $e) {
            //throw $th;
            return response()->json([
                'status'     => false,
                'message'    => 'Xoá  Danh Mục không thành công!!'
            ]);
        }
    }
    public function thaydoiTrangThaiDanhMuc(ThayDoiTrangThaiDanhMucRequest $request)
    {

        try {
            $id_chuc_nang = 14;
            $check = $this->checkQuyen($id_chuc_nang);
            if ($check == false) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            $tinh_trang_moi = !$request->tinh_trang;
            //   $tinh_trang_moi là trái ngược của $request->tinh_trangs
            DanhMucWeb::where('id', $request->id)
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
    public function kiemTraSlugDanhMuc(Request $request)
    {
        $tac_gia = DanhMucWeb::where('slug_danh_muc', $request->slug)->first();

        if (!$tac_gia) {
            return response()->json([
                'status'            =>   true,
                'message'           =>   'Tên Danh Mục phù hợp!',
            ]);
        } else {
            return response()->json([
                'status'            =>   false,
                'message'           =>   'Tên Danh Mục Đã Tồn Tại!',
            ]);
        }
    }
    public function kiemTraSlugDanhMucUpdate(Request $request)
    {
        $danh_muc = DanhMucWeb::where('slug_danh_muc', $request->slug)
            ->where('id', '<>', $request->id)
            ->first();

        if (!$danh_muc) {
            return response()->json([
                'status'            =>   true,
                'message'           =>   'Tên Danh Mục phù hợp!',
            ]);
        } else {
            return response()->json([
                'status'            =>   false,
                'message'           =>   'Tên Danh Mục Đã Tồn Tại!',
            ]);
        }
    }
    public function autoConfigMenu()
    {
        try {
            $id_chuc_nang = 14;
            $check = $this->checkQuyen($id_chuc_nang);
            if ($check == false) {
                return response()->json([
                    'status'  =>  false,
                    'message' =>  'Bạn không có quyền chức năng này'
                ]);
            }
            // Chạy seeder
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\MenuSeeder']);
            return response()->json([
                'status'     => true,
                'message'    => 'Thiết lập lại menu thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'     => false,
                'message'    => 'Xảy ra lỗi!!'
            ]);
        }
    }

    public function sapXepMenu(Request $request)
    {
        $this->processCategories($request->json()->all());
        return response()->json([
            'status'     => true,
            'message'    => 'Sắp xếp menu thanh cong'
        ]);
    }
    private function processCategories($categories, $parentId = null, $parentSlug = '')
    {
        foreach ($categories as $category) {
            // Generate slug and link
            $slug = Str::slug($category['title']);
            $link = ($slug === 'trang-chu') ? '/' : ('/' .  $parentSlug ? $parentSlug . '/' . $slug : $slug);

            // Check if slug already exists
            $danhMuc = DanhMucWeb::where('slug_danh_muc', $slug)->first();
            if ($danhMuc) {
                // Update if slug already exists
                $danhMuc->update([
                    'ten_danh_muc' => $category['title'],
                    'link' => $link,
                    'id_danh_muc_cha' => $parentId,
                ]);
            } else {
                // Insert category into the database
                $danhMuc = DanhMucWeb::create([
                    'ten_danh_muc' => $category['title'],
                    'slug_danh_muc' => $slug,
                    'link' => $link,
                    'tinh_trang' => 1, // Adjust as needed
                    'id_danh_muc_cha' => $parentId,
                ]);
            }

            // Recur for children
            if (!empty($category['children'])) {
                $this->processCategories($category['children'], $danhMuc->id, $link);
            }
        }
    }
}

