<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class HoaDonExport implements FromArray, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithProperties
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function properties(): array
    {
        return [
            'creator'        => 'Movie Streaming',
            'title'         => 'Danh sách thanh toán',
            'description'   => 'Danh sách hóa đơn thanh toán',
            'subject'       => 'Hóa đơn',
            'company'       => 'Movie Streaming',
        ];
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã Hóa Đơn',
            'Tên Khách Hàng',
            'Email',
            'Số Điện Thoại',
            'Tên Gói',
            'Tổng Tiền',
            'Đã Thanh Toán',
            'Loại Thanh Toán',
            'Trạng Thái',
            'Mã Giao Dịch',
            'Ngày Tạo',
            'Ngày Bắt Đầu',
            'Ngày Kết Thúc'
        ];
    }

    public function map($row): array
    {
        static $stt = 0;
        $stt++;

        return [
            $stt,
            $row['ma_hoa_don'] ?? '',
            $row['ho_va_ten'] ?? '',
            $row['email'] ?? '',
            $row['so_dien_thoai'] ?? '',
            $row['ten_goi'] ?? '',
            (float) ($row['tong_tien'] ?? 0),
            (float) ($row['so_tien_da_thanh_toan'] ?? 0),
            $row['loai_thanh_toan'] ?? 'MB Bank',
            isset($row['tinh_trang']) ? ($row['tinh_trang'] == 0 ? 'Chưa thanh toán' : 'Đã thanh toán') : '',
            $row['ma_giao_dich'] ?? 'N/A',
            isset($row['created_at']) ? Carbon::parse($row['created_at'])->format('d/m/Y H:i:s') : '',
            isset($row['ngay_bat_dau']) ? Carbon::parse($row['ngay_bat_dau'])->format('d/m/Y') : '',
            isset($row['ngay_ket_thuc']) ? Carbon::parse($row['ngay_ket_thuc'])->format('d/m/Y') : ''
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Style cho header
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'name' => 'Arial',
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E2EFDA',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Style cho toàn bộ dữ liệu
        $sheet->getStyle('A2:N'.$lastRow)->applyFromArray([
            'font' => [
                'size' => 11,
                'name' => 'Arial',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Căn giữa cho một số cột
        $sheet->getStyle('A1:A'.$lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D1:D'.$lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('E1:E'.$lastRow)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('J1:J'.$lastRow)->getAlignment()->setHorizontal('center');

        // Format cho cột tiền tệ
        $sheet->getStyle('G2:H'.$lastRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('G2:H'.$lastRow)->getAlignment()->setHorizontal('right');

        // Tự động xuống dòng và fix chiều cao tối thiểu
        $sheet->getStyle('A1:N'.$lastRow)->getAlignment()->setWrapText(true);
        $sheet->getDefaultRowDimension()->setRowHeight(20);

        // Set độ rộng cột
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(15);
    }
}
