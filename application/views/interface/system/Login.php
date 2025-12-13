<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $system_title ?> | <?= $page_title ?></title>

    <link rel="icon" type="image/png" href="<?= $system_svg ?>">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="<?= base_url() ?>dist/css/fonts.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= base_url() ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">

        <!-- Preloader -->
        <!-- /.login-logo -->
        <div class="card shadow-sm border-0">
    <div class="card-body p-4">

        <!-- Logo -->
        <div class="text-center mb-3">
            <img src="<?= $system_logo ?>" width="80" height="80" alt="School Logo">
        </div>

        <!-- Title -->
        <h5 class="text-center font-weight-bold mb-3">
            School Feedback System
        </h5>

        <!-- Error Message -->
        <?php if (
            $this->input->get("login_attempt") == md5(0) ||
            $this->input->get("login_attempt") == md5(1)
        ) : ?>
            <p class="text-danger text-center text-sm mb-3">
                <i class="fa fa-exclamation-triangle"></i> Invalid username or password
            </p>
        <?php endif; ?>

        <form action="<?= base_url() ?>requestlogin" method="post" autocomplete="off">

            <!-- Username -->
            <div class="form-group mb-3">
                <input
                    type="text"
                    name="username"
                    class="form-control form-control-sm <?php if ($this->input->get("login_attempt") == md5(0)) echo 'is-invalid'; ?>"
                    placeholder="Username"
                    autofocus
                    required
                >
            </div>

            <!-- Password -->
            <div class="form-group mb-3">
                <input
                    type="password"
                    name="password"
                    class="form-control form-control-sm <?php if ($this->input->get("login_attempt") == md5(0)) echo 'is-invalid'; ?>"
                    placeholder="Password"
                    required
                >
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn bg-<?php if ($this->input->get("login_attempt") == md5(0)) echo 'danger'; else echo'navy'; ?> btn-sm btn-block">
                Login
            </button>

        </form>

    </div>
</div>

        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= base_url() ?>plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url() ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url() ?>dist/js/adminlte.min.js"></script>

</body>

</html>