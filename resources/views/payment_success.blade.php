<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isPaymentSuccess ? 'Thanh Toán Thành Công' : 'Thanh Toán Thất Bại' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --primary-dark: #2e59d9;
            --success-color: #1cc88a;
            --danger-color: #e74a3b;
            --dark-text: #333333;
            --light-text: #666666;
            --lighter-text: #999999;
            --border-radius: 10px;
            --box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fc;
            color: var(--dark-text);
            line-height: 1.6;
        }

        .payment-result {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .result-container {
            text-align: center;
            padding: 40px;
            background: #ffffff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            max-width: 600px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .result-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: {{ $isPaymentSuccess ? 'var(--success-color)' : 'var(--danger-color)' }};
        }

        .icon-container {
            margin-bottom: 25px;
            animation: fadeInDown 1s;
        }

        .icon-container i {
            font-size: 80px;
            color: {{ $isPaymentSuccess ? 'var(--success-color)' : 'var(--danger-color)' }};
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        .title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
            color: {{ $isPaymentSuccess ? 'var(--success-color)' : 'var(--danger-color)' }};
            animation: fadeInUp 1s;
        }

        .message {
            font-size: 18px;
            color: var(--light-text);
            margin-bottom: 20px;
            animation: fadeInUp 1.2s;
        }

        .details {
            margin: 30px 0;
            text-align: left;
            background-color: #f8f9fc;
            padding: 20px;
            border-radius: var(--border-radius);
            border-left: 4px solid var(--primary-color);
            animation: fadeInUp 1.4s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .details p {
            font-size: 16px;
            margin: 12px 0;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .details p strong {
            color: var(--dark-text);
            margin-right: 10px;
            min-width: 150px;
        }

        .contact-info {
            background-color: #f8f9fc;
            padding: 20px;
            border-radius: var(--border-radius);
            margin: 25px 0;
            animation: fadeInUp 1.6s;
        }

        .contact-info .title-small {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 15px;
            color: var(--dark-text);
        }

        .contact-info p {
            font-size: 16px;
            color: var(--light-text);
            margin: 10px 0;
        }

        .contact-info a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .contact-info a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: var(--primary-color);
            color: #fff;
            border-radius: 50px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            margin-top: 20px;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(78, 115, 223, 0.25);
            animation: fadeInUp 1.8s;
        }

        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(78, 115, 223, 0.3);
        }

        .btn i {
            margin-right: 8px;
        }

        .additional-info {
            margin-top: 30px;
            font-size: 14px;
            color: var(--lighter-text);
            animation: fadeInUp 2s;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .result-container {
                padding: 30px 20px;
            }

            .title {
                font-size: 24px;
            }

            .message {
                font-size: 16px;
            }

            .icon-container i {
                font-size: 70px;
            }

            .details p {
                flex-direction: column;
            }

            .details p strong {
                margin-bottom: 5px;
            }
        }

        @media (max-width: 480px) {
            .result-container {
                padding: 25px 15px;
            }

            .title {
                font-size: 22px;
            }

            .message {
                font-size: 15px;
            }

            .icon-container i {
                font-size: 60px;
            }

            .btn {
                width: 100%;
                padding: 12px 15px;
            }
        }

        /* Print styles */
        @media print {
            .btn {
                display: none;
            }

            .result-container {
                box-shadow: none;
                max-width: 100%;
            }

            body {
                background-color: white;
            }
        }
    </style>
</head>
<body>
    <div class="payment-result">
        <div class="result-container">
            <div class="icon-container">
                <i class="{{ $isPaymentSuccess ? 'fas fa-check-circle' : 'fas fa-times-circle' }}"></i>
            </div>

            <h2 class="title">{{ $isPaymentSuccess ? 'CẢM ƠN BẠN ĐÃ THANH TOÁN' : 'THANH TOÁN KHÔNG THÀNH CÔNG' }}</h2>

            <p class="message">
                @if($isPaymentSuccess)
                    Chúng tôi đã nhận được thanh toán từ bạn. Hệ thống sẽ gửi hóa đơn qua email của bạn trong thời gian sớm nhất.
                @else
                    {{ $errorMessage }}
                @endif
            </p>

            @if($isPaymentSuccess)
                <div class="details">
                    <p>
                        <strong>Số tiền:</strong>
                        <span>{{ number_format($paymentInfo['amount'], 0, ',', '.') }}đ</span>
                    </p>
                    <p>
                        <strong>Mã giao dịch:</strong>
                        <span>{{ $paymentInfo['transactionNo'] }}</span>
                    </p>
                    @if($paymentInfo['paymentType'] === 'vnpay')
                        <p>
                            <strong>Ngân hàng:</strong>
                            <span>{{ $paymentInfo['bankCode'] }}</span>
                        </p>
                    @endif
                    <p>
                        <strong>Phương thức:</strong>
                        <span>{{ $paymentInfo['paymentType'] === 'momo' ? 'Ví MOMO' : 'VNPAY' }}</span>
                    </p>
                    <p>
                        <strong>Thông tin đơn hàng:</strong>
                        <span>{{ $paymentInfo['orderInfo'] }}</span>
                    </p>
                    <p>
                        <strong>Thời gian:</strong>
                        <span>{{ date('H:i:s d/m/Y') }}</span>
                    </p>
                </div>

                <div class="additional-info">
                    Vui lòng lưu lại thông tin này cho mục đích tham khảo trong tương lai.
                </div>
            @endif

            <div class="contact-info">
                <h3 class="title-small">Hỗ trợ khách hàng</h3>
                <p>Nếu bạn có bất kỳ câu hỏi hoặc vấn đề nào, vui lòng liên hệ với chúng tôi:</p>
                <p><i class="far fa-envelope"></i> Email: <a href="mailto:vietdev2106@gmail.com">vietdev2106@gmail.com</a></p>
                <p><i class="fas fa-phone-alt"></i> SĐT: <a href="tel:0905123456">0905.123.456</a></p>
            </div>

            <a href="{{ env('URL_FE') }}" class="btn">
                <i class="fas fa-home"></i> Trở về trang chủ
            </a>

            @if($isPaymentSuccess)
                <button onclick="window.print()" class="btn" style="margin-left: 10px; background-color: #6c757d;">
                    <i class="fas fa-print"></i> In hóa đơn
                </button>
            @endif
        </div>
    </div>

    <script>
        // Ghi log thời gian tải trang
        console.log('Trang thanh toán được tải lúc:', new Date().toLocaleString());

        // Thêm sự kiện cho nút in (nếu cần thêm logic)
        document.addEventListener('DOMContentLoaded', function() {
            const printBtn = document.querySelector('.btn[onclick="window.print()"]');
            if (printBtn) {
                printBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.print();
                });
            }
        });
    </script>
</body>
</html>