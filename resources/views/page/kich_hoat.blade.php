<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kích Hoạt Tài Khoản</title>
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

        .activation-container {
            background-color: #212529;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: calc(100% - 30px);
            margin: 15px auto;
            transition: all 0.3s ease;
        }

        .site-btn {
            background-color: #ffc107;
            color: #212529;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: 100%;
            max-width: 300px;
            margin: 15px auto;
        }

        .site-btn:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
        }

        .text-warning {
            color: #ffc107 !important;
        }

        h3 {
            color: white;
            margin-bottom: 25px;
            font-weight: 500;
            line-height: 1.4;
        }

        .success-icon {
            color: #28a745;
            font-size: 70px;
            margin-bottom: 25px;
            animation: scaleIn 0.5s ease;
        }

        .error-icon {
            color: #dc3545;
            font-size: 70px;
            margin-bottom: 25px;
            animation: shake 0.5s ease;
        }

        .loading-spinner {
            color: #ffc107;
            font-size: 50px;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .support-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .activation-container {
                padding: 30px 20px;
                margin: 15px;
                width: calc(100% - 30px);
            }

            h3 {
                font-size: 18px;
                line-height: 1.5;
            }

            .success-icon,
            .error-icon {
                font-size: 60px;
            }

            .loading-spinner {
                font-size: 40px;
            }

            .site-btn {
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .activation-container {
                padding: 20px 15px;
                margin: 10px;
                width: calc(100% - 20px);
            }

            h3 {
                font-size: 16px;
                margin-bottom: 15px;
            }

            .success-icon,
            .error-icon {
                font-size: 50px;
                margin-bottom: 20px;
            }

            .site-btn {
                padding: 10px 15px;
                font-size: 14px;
            }

            .support-text {
                font-size: 12px;
                margin-top: 20px;
                padding-top: 10px;
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
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="activation-container text-center">
                            <div v-if="isLoading">
                                <div class="loading-spinner">
                                    <i class="fas fa-spinner"></i>
                                </div>
                                <h3>Đang kiểm tra tài khoản...</h3>
                            </div>

                            <div v-else-if="activationSuccess">
                                <div class="success-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h3>Tài khoản <b class="text-warning">@{{ userEmail }}</b> đã kích hoạt thành công!</h3>
                                        <h3>VUI LÒNG ĐĂNG NHẬP!</h3>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <a href="#" style="text-decoration: none; display: block;" @click.prevent="changeLogin">
                                            <button style="width: 100%;" class="site-btn">ĐĂNG NHẬP</button>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div v-else>
                                <div class="error-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <h3>@{{ errorMessage }}</h3>
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <a href="#" style="text-decoration: none; display: block;" @click.prevent="changeLogin">
                                            <button style="width: 100%;" class="site-btn">QUAY LẠI ĐĂNG NHẬP</button>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-white-50">
                                <small>Nếu bạn gặp vấn đề, vui lòng liên hệ hỗ trợ</small>
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
                    userEmail: '',
                    hashActive: '{{ $hash }}',
                    isLoading: true,
                    activationSuccess: false,
                    errorMessage: 'Không thể kích hoạt tài khoản. Vui lòng thử lại sau.'
                }
            },
            created() {
                this.checkHashLogin();
            },
            methods: {
                changeLogin() {
                    // Use the frontend URL from Laravel config
                    window.location.href = window.appConfig.frontendUrl;
                },
                checkHashLogin() {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    axios.post('/api/kiem-tra-hash-kich-hoat', {
                        hash_active: this.hashActive
                    })
                    .then(response => {
                        this.isLoading = false;
                        if(response.data.status) {
                            this.activationSuccess = true;
                            this.userEmail = response.data.email;
                        } else {
                            this.activationSuccess = false;
                            this.errorMessage = response.data.message || 'Không thể kích hoạt tài khoản. Mã kích hoạt không hợp lệ.';
                        }
                    })
                    .catch(error => {
                        this.isLoading = false;
                        this.activationSuccess = false;
                        this.errorMessage = 'Đã xảy ra lỗi. Vui lòng thử lại sau.';
                        console.error('Error checking hash:', error);
                    });
                }
            }
        });

        app.mount('#app');
    </script>
</body>
</html>
