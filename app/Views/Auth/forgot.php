<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-md-8">

                <div class="card shadow">
                    <div class="row g-0">

                        <!-- Foto kiri -->
                        <div class="col-md-5 bg-primary d-flex align-items-center justify-content-center">
                            <img src="/assets/img/forgot.png" class="img-fluid" style="max-width:100%;">
                        </div>

                        <!-- Form kanan -->
                        <div class="col-md-7 p-4">

                            <h2 class="text-center mb-4">RESET PASSWORD</h2>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger">
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" action="<?= site_url('auth/resetPassword') ?>">
                                <?= csrf_field(); ?>

                                <div class="mb-3">
                                    <input type="email"
                                        name="email"
                                        class="form-control form-control-lg"
                                        placeholder="Email"
                                        required>
                                </div>

                                <div class="mb-3 position-relative">
                                    <input type="password"
                                        id="password"
                                        name="password"
                                        class="form-control form-control-lg"
                                        placeholder="New Password"
                                        required>

                                    <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3"
                                        id="togglePassword"
                                        style="cursor:pointer; font-size:22px;"></i>
                                </div>

                                <div class="mb-3">
                                    <input type="password"
                                        name="confirm_password"
                                        class="form-control form-control-lg"
                                        placeholder="Confirm New Password"
                                        required>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        Reset Password
                                    </button>
                                </div>
                                <p class="mb-0 text-center">
                                    No change password?
                                    <a href="<?= site_url('auth/login') ?>">Login</a>
                                </p>
                            </form>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById("togglePassword");
        const password = document.getElementById("password");

        togglePassword.addEventListener("click", function() {

            const type = password.type === "password" ? "text" : "password";
            password.type = type;

            this.classList.toggle("bi-eye");
            this.classList.toggle("bi-eye-slash");

        });
    </script>

</body>

</html>