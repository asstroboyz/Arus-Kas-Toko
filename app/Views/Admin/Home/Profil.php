<?= $this->extend('Admin/Templates/Index') ?>

<?= $this->section('page-content') ?>
<div class="container-fluid">
    <?php if (session()->getFlashdata('error-msg')) : ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible show fade" role="alert">

                <div class="alert-body">
                    <button class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <b><i class="fa fa-check"></i></b>
                    <?= session()->getFlashdata('error-msg'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('msg')) : ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible show fade" role="alert">

                <div class="alert-body">
                    <button class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <b><i class="fa fa-check"></i></b>
                    <?= session()->getFlashdata('msg'); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-900"></h1>

            <div class="card shadow px-5 py-4">
                <div class="row">
                    <div class="col-12">
                        <div style="background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); padding: 20px;">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-12">
                                    <img style="width: 100%; border-radius: 8px; object-fit: cover;"
                                        src="<?= empty(user()->foto) ? '/sbassets/img/undraw_profile_1.svg' : '/uploads/profile/' . user()->foto; ?>"
                                        alt="Image profile" height="290">
                                </div>
                                <div class="col-lg-10 col-md-8 col-sm-12">
                                    <ul style="list-style: none; padding: 0; margin: 0; line-height: 1.6;">
                                        <li style="display: flex; align-items: center; padding: 10px; background-color: #fff; border-radius: 5px; margin-bottom: 10px;">
                                            <i class="fa fa-user" style="color: #007bff; font-size: 1.2em; margin-right: 15px;"></i>
                                            <span style="width: 150px;"><strong>Username:</strong></span>
                                            <span><?= user()->username; ?></span>
                                        </li>
                                        <li style="display: flex; align-items: center; padding: 10px; background-color: #fff; border-radius: 5px; margin-bottom: 10px;">
                                            <i class="fa fa-address-card" style="color: #007bff; font-size: 1.2em; margin-right: 15px;"></i>
                                            <span style="width: 150px;"><strong>Nama Lengkap:</strong></span>
                                            <span><?= $user->fullname ?></span>
                                        </li>
                                        <li style="display: flex; align-items: center; padding: 10px; background-color: #fff; border-radius: 5px; margin-bottom: 10px;">
                                            <i class="fa fa-envelope" style="color: #007bff; font-size: 1.2em; margin-right: 15px;"></i>
                                            <span style="width: 150px;"><strong>Email:</strong></span>
                                            <span><?= $user->email ?></span>
                                        </li>
                                    </ul>
                                    <div class="row mt-4">
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <button data-toggle="modal" data-target="#edit-profile" type="button"
                                                style="background-color: #28a745; color: white; padding: 10px; border: none; border-radius: 5px; width: 100%; cursor: pointer;">
                                                <i class="fas fa-user-edit"></i> Ubah Profile
                                            </button>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <button data-toggle="modal" data-target="#edit-password" type="button"
                                                style="background-color: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; width: 100%; cursor: pointer;">
                                                <i class="fas fa-lock"></i> Ubah Password
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - Edit Profile -->
    <div class="modal fade" id="edit-profile" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Update Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/Admin/simpanProfile/<?= $user->id; ?>"
                    method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="id" id="userid">
                        <div class="form-group">
                            <label for="foto">Foto Profil</label>
                            <input type="file" name="foto" id="foto" class="form-control p-1">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control"
                                value="<?= $user->username ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                    
                        <div class="form-group">
                            <label for="fullname">fullname</label>
                            <input type="text" name="fullname" id="fullname" class="form-control"
                                value="<?= $user->fullname ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="<?= $user->email ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                        <!-- <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password untuk konfirmasi perubahan" autocomplete="false">
                            <div class="invalid-feedback"></div>
                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-simpan">Simpan data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-password" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Update Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form
                    action="/Admin/updatePassword/<?= user()->id ?>"
                    method="post">
                    <div class="modal-body">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="id" id="user_id">
                        <div class="form-group">
                            <label for="passwordLama">Password Lama no</label>
                            <input type="password" name="passwordLama" id="passwordLama" class="form-control "
                                placeholder="Masukkan password saat ini" autocomplete="false">
                            <div class="invalid-feedback">

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="passwordBaru">Password Baru</label>
                            <input type="password" name="passwordBaru" id="passwordBaru" class="form-control"
                                placeholder="Masukkan password baru" autocomplete="false">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="konfirm">Konfirmasi Password</label>
                            <input type="password" name="konfirm" id="konfirm" class="form-control"
                                placeholder="Konfirmasi password baru" autocomplete="false">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-simpan">Simpan data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script>
    $(document).ready(function() {
        $.validator.addMethod("metodku", function(value, element) {
            return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
        }, "Username must contain only letters, numbers, or dashes.");

        $.validator.addMethod("valueNotEquals", function(value, element, arg) {
            return arg !== value;
        }, "This field is required.");

        $("#formUser").validate({
            rules: {
                nama: {
                    required: true,
                    minlength: 3,
                    metodku: true
                },
                username: {
                    required: true,
                    minlength: 3,
                    metodku: true
                },
                role: {
                    required: true,
                    valueNotEquals: "default"
                },
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 8
                }
            },
        });

        // Set timeout to hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 3000);
    });
</script>

<?= $this->endSection() ?>