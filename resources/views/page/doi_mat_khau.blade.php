<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đặt Lại Mật Khẩu</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .login.spad {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }

        .reset-container {
            background-color: #212529;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            margin: 15px auto;
            max-width: 900px;
            width: calc(100% - 30px);
        }

        .site-btn {
            background-color: #ffc107;
            color: #212529;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 5px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: 100%;
            margin-top: 10px;
        }

        .site-btn:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
        }

        h3 {
            color: white;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .input__item {
            position: relative;
            margin-bottom: 20px;
        }

        .input__item input {
            height: 50px;
            width: 100%;
            font-size: 15px;
            padding: 10px 45px 10px 20px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .input__item input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 2px rgba(255, 193, 7, 0.3);
        }

        .input__item input::placeholder {
            color: #b7b7b7;
        }

        .input__item span {
            color: #b7b7b7;
            font-size: 20px;
            position: absolute;
            right: 20px;
            top: 14px;
        }

        .login__social__links {
            margin-top: 30px;
        }

        .login__social__links ul {
            padding-left: 0;
            list-style: none;
        }

        .login__social__links ul li {
            margin-bottom: 15px;
        }

        .login__social__links ul li a {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 0;
            text-align: center;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .login__social__links ul li a i {
            margin-right: 10px;
        }

        .login__social__links ul li a.facebook {
            background: #4267b2;
        }

        .login__social__links ul li a.google {
            background: #ff4343;
        }

        .login__social__links ul li a.twitter {
            background: #1da1f2;
        }

        .login__social__links ul li a:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .login__register {
            margin-top: 20px;
        }

        .login__register a {
            color: #ffc107 !important;
            transition: all 0.3s ease;
        }

        .login__register a:hover {
            color: #e0a800 !important;
            text-decoration: none;
        }

        .loading-spinner {
            color: #ffc107;
            font-size: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .reset-container {
                padding: 25px;
                margin: 15px;
                width: calc(100% - 30px);
            }

            .login__form {
                margin-bottom: 30px;
            }

            h3 {
                font-size: 20px;
                text-align: center;
            }

            .login__social__links {
                padding: 0;
            }

            .login__register {
                text-align: center;
                margin: 20px 0;
            }

            .site-btn {
                max-width: 100%;
                padding: 12px 20px;
            }
        }

        @media (max-width: 576px) {
            .reset-container {
                padding: 20px 15px;
                margin: 10px;
                width: calc(100% - 20px);
            }

            .input__item input {
                font-size: 14px;
                height: 45px;
            }

            h3 {
                font-size: 18px;
                margin-bottom: 20px;
            }

            .login__social__links ul li a {
                font-size: 14px;
                padding: 10px 0;
            }

            .site-btn {
                font-size: 14px;
                padding: 10px 15px;
            }
        }
    </style>
    <!-- Pass Laravel environment variables to JavaScript -->
    <script>
        window.appConfig = {
            frontendUrl: "{{ env('URL_FE', '/') }}"
        };
    </script>
</head>
<body>
    <div id="app">
        <!-- Login Section Begin -->
        <section class="login spad">
            <div class="container">
                <div v-if="isLoading" class="row justify-content-center">
                    <div class="col-12 text-center">
                        <div class="reset-container">
                            <div class="loading-spinner">
                                <i class="fas fa-spinner"></i>
                            </div>
                            <h3>Đang kiểm tra...</h3>
                        </div>
                    </div>
                </div>

                <div v-else-if="isValid" class="reset-container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="login__form">
                                <div class="row">
                                    <div class="col-12">
                                        <h3>Đặt Lại Mật Khẩu!</h3>
                                    </div>
                                </div>
                                <div>
                                    <div class="input__item">
                                        <input v-model="doiMatKhau.password" type="password" placeholder="Nhập mật khẩu mới!">
                                        <span class="icon_lock"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <div class="input__item">
                                        <input v-model="doiMatKhau.re_password" type="password" placeholder="Nhập lại mật khẩu!">
                                        <span class="icon_lock"><i class="fas fa-lock"></i></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button @click="xuLyDoiMatKhau()" style="width: 100%;" class="site-btn">ĐỔI MẬT KHẨU</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="login__register">
                                <a href="#" class="btn btn-sm text-info" @click.prevent="goToLogin">
                                    <i class="fa-solid fa-left-long"></i> Đăng Nhập
                                </a>
                            </div>
                            <div class="login__social__links">
                                <ul style="margin-top: 30px;">
                                    <li class="li_social"><a href="#" class="facebook"><i class="fa fa-facebook"></i> Sign in With Facebook</a></li>
                                    <li class="li_social"><a href="#" class="google"><i class="fa fa-google"></i> Sign in With Google</a></li>
                                    <li class="li_social"><a href="#" class="twitter"><i class="fa fa-twitter"></i> Sign in With Twitter</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="row justify-content-center">
                    <div class="col-12 text-center">
                        <div class="reset-container">
                            <div class="text-danger" style="font-size: 60px; margin-bottom: 20px;">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <h3>@{{ errorMessage }}</h3>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <a href="#" style="text-decoration: none; display: block;" @click.prevent="goToLogin">
                                        <button style="width: 50%; margin: 0 auto;" class="site-btn">QUAY LẠI ĐĂNG NHẬP</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.47/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const app = Vue.createApp({
            data() {
                return {
                    doiMatKhau: {
                        password: '',
                        re_password: '',
                        hash_quen_mat_khau: '{{ $hash }}'
                    },
                    isLoading: true,
                    isValid: false,
                    errorMessage: 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.'
                }
            },
            mounted() {
                this.checkHashPass();
            },
            methods: {
                goToLogin() {
                    window.location.href = window.appConfig.frontendUrl + '/login';
                },
                checkHashPass() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.post('/api/kiem-tra-quen-hash-pass', {
                        hash_quen_mat_khau: this.doiMatKhau.hash_quen_mat_khau
                    })
                    .then(response => {
                        this.isLoading = false;
                        if(response.data.status) {
                            this.isValid = true;
                            // You can show a toast notification here if needed
                        } else {
                            this.isValid = false;
                            this.errorMessage = response.data.message || 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.';
                        }
                    })
                    .catch(error => {
                        this.isLoading = false;
                        this.isValid = false;
                        this.errorMessage = 'Đã xảy ra lỗi. Vui lòng thử lại sau.';
                        console.error('Error checking hash:', error);
                    });
                },
                xuLyDoiMatKhau() {
                    if (!this.doiMatKhau.password) {
                        alert('Vui lòng nhập mật khẩu mới!');
                        return;
                    }

                    if (this.doiMatKhau.password !== this.doiMatKhau.re_password) {
                        alert('Mật khẩu nhập lại không khớp!');
                        return;
                    }

                    axios.post('/api/dat-lai-mat-khau', this.doiMatKhau)
                    .then(response => {
                        if(response.data.status) {
                            alert(response.data.message || 'Đổi mật khẩu thành công!');
                            this.goToLogin();
                        } else {
                            alert(response.data.message || 'Có lỗi xảy ra!');
                        }
                    })
                    .catch(error => {
                        alert('Đã xảy ra lỗi. Vui lòng thử lại sau.');
                        console.error('Error resetting password:', error);
                    });
                }
            }
        });

        app.mount('#app');
    </script>
</body>
</html>
