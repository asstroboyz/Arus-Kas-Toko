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
                                        <?= lang('Auth.register') ?>
                                    </h1>
                                </div>
                                <?= view('Myth\Auth\Views\_message_block') ?>
                                <form class="user"
                                    action="<?=base_url() ?>/auth/reset_password"
                                    method="post">
                                    <?= csrf_field() ?>
                                    <div class="form-group">
                                        <input type="text"
                                            class="form-control form-control-user <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>"
                                            name="username"
                                            placeholder="<?= lang('Auth.username') ?>"
                                            value="<?= old('username') ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="text"
                                            class="form-control form-control-user <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>"
                                            name="password"
                                            placeholder="<?= lang('Auth.password') ?>"
                                            value="<?= old('password') ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="text"
                                            class="form-control form-control-user <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>"
                                            name="confirm"
                                            placeholder="<?= lang('Auth.password') ?>"
                                            value="<?= old('confirm') ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
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

</div>

<?= $this->endSection(); ?>