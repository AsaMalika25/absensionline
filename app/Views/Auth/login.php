<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Halaman Login</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-md-8">

                <div class="card shadow">
                    <div class="row g-0">

                        <!-- Foto kiri full background -->
                        <div class="col-md-5 bg-primary d-flex align-items-center justify-content-center">
                            <img src="/assets/img/login.jpg" class="img-fluid" style="max-width:132%;">
                        </div>

                        <!-- Form kanan -->
                        <div class="col-md-7 p-4">

                            <h2 class="text-center mb-4">LOGIN</h2>
                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger">
                                    <?= session()->getFlashdata('error') ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" action="<?= site_url('auth/process') ?>">
                                <?= csrf_field(); ?>

                                <div class="mb-3">
                                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required>
                                </div>

                                <div class="mb-3 position-relative">

                                    <input type="password"
                                        id="password"
                                        name="password"
                                        class="form-control form-control-lg"
                                        placeholder="Password"
                                        required>

                                    <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3"
                                        id="togglePassword"
                                        style="cursor:pointer; font-size:22px;"></i>

                                </div>

                                <div class="text-end mb-2">
                                    <a href="<?= site_url('auth/forgot') ?>">Forgot the password</a>
                                </div>
                                <div class="d-grid mt-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        Login
                                    </button>
                                </div>
                            </form>
                            <div class="text-center mt-4">

                                <p class="mb-0">
                                    Don't have an account yet?
                                    <a href="<?= site_url('auth/register') ?>">Register</a>
                                </p>

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