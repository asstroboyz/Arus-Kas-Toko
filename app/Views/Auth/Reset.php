<?= $this->extend('auth/templates/index'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-6">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">
                                        Reset Password
                                    </h1>
                                </div>
                                <?= view('Myth\Auth\Views\_message_block') ?>
                                <form class="user" id="resetPasswordForm"
                                    action="<?=base_url() ?>/auth/reset_password"
                                    method="post">
                                    <?= csrf_field() ?>
                                    <div class="form-group">
                                        <input type="text"
                                            class="form-control form-control-user <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>"
                                            name="username" id="username"
                                            placeholder="<?= lang('Auth.username') ?>"
                                            value="<?= old('username') ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password"
                                            class="form-control form-control-user <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>"
                                            name="password" id="password"
                                            placeholder="<?= lang('Auth.password') ?>"
                                            value="<?= old('password') ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password"
                                            class="form-control form-control-user <?php if (session('errors.confirm')) : ?>is-invalid<?php endif ?>"
                                            name="confirm" id="confirm"
                                            placeholder="<?= lang('Auth.repeatPassword') ?>"
                                            value="<?= old('confirm') ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" onclick="togglePassword()"> Show Password
                                    </div>
                                    <button type="button" class="btn btn-primary btn-user btn-block" data-toggle="modal"
                                        data-target="#confirmModal" onclick="setUsername()">
                                        Reset Password
                                    </button>
                                    <hr>
                                </form>
                                <div class="text-center">
                                    <p> <a class="small"
                                            href="<?= url_to('login') ?>"><?= lang('Auth.alreadyRegistered') ?>
                                            <?= lang('Auth.signIn') ?></a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Password Reset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to reset the password for <strong><span id="confirmUsername"></span></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmReset">Confirm</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function setUsername() {
        var username = document.getElementById('username').value;
        document.getElementById('confirmUsername').innerText = username;
    }

    document.getElementById('confirmReset').addEventListener('click', function () {
        document.getElementById('resetPasswordForm').submit();
    });

    function togglePassword() {
        var password = document.getElementById("password");
        var confirm = document.getElementById("confirm");
        if (password.type === "password") {
            password.type = "text";
            confirm.type = "text";
        } else {
            password.type = "password";
            confirm.type = "password";
        }
    }
</script>

<?= $this->endSection(); ?>
