<div class="row">
    <div class="col-md-3">
        <div class="card card-outline card-primary">
            <div class="card-body box-profile">
                <div class="text-center">
                    <i class="fa fa-user-circle fa-5x"></i>

                    <!-- <img class="profile-user-img img-fluid img-circle"
                       src="../../dist/img/user4-128x128.jpg"
                       alt="User profile picture"> -->
                </div>

                <h3 class="profile-username text-center"><?= $data_karyawan['emp_name']; ?></h3>
                <p class="text-muted text-center"><?= $data_karyawan['position_name']; ?></p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Hierarchy</b>
                        <a class="float-right">
                            <span class="badge 
                                <?php switch ($data_karyawan['hirarki_org']){
                                    case "N":
                                        echo "badge-danger";
                                        break;
                                    case "N-1":
                                        echo "badge-warning";
                                        break;
                                    case "N-2":
                                        echo "badge-success";
                                        break;
                                    case "N-3":
                                        echo "badge-info";
                                        break;
                                    default:
                                        echo "badge-primary";
                                } ?>
                            ">
                                <?= $data_karyawan['hirarki_org']; ?>
                            </span>
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>Department</b> <a class="float-right"><?= $data_karyawan['departemen']; ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>Division</b> <a class="float-right"><?= $data_karyawan['divisi']; ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">My Profile</h5>
            </div>
            <div class="card-body">
                <form id="profileForm" action="<?= base_url('profile/saveprofile'); ?>" class="form-horizontal" method="POST">
                    <div class="form-group row">
                        <div class="col">
                            <?= $this->session->flashdata('message'); ?>                    
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputNik" class="col-sm-3 col-form-label">NIK</label>
                        <div class="col-sm-9">
                            <input type="text" name="nik" class="form-control" id="inputNik" disabled value="<?= $data_karyawan['nik']; ?>" placeholder="nik">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" id="inputName" value="<?php if($this->session->userdata('form_profile')){echo($this->session->userdata('form_profile')['emp_name']);}else{echo($data_karyawan['emp_name']);} ?>" placeholder="Change Name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" name="email" class="form-control" id="inputEmail" value="<?php if($this->session->userdata('form_profile')){echo($this->session->userdata('form_profile')['email']);}else{echo($data_karyawan['email']);} ?>" placeholder="Change Email" required>
                        </div>
                    </div>
                    <hr/>
                    <!-- current password -->
                    <div class="form-group row">
                        <label for="currentPassword" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" name="password_current" class="form-control" id="currentPassword" placeholder="Type your existing password" required>
                        </div>
                    </div>
                    <hr/>
                    <!-- change password -->
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-3 col-form-label">Change Password</label>
                        <div class="col-sm-9">
                            <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Type the new password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword2" class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <input type="password" name="password2" class="form-control" id="inputPassword2" placeholder="Retype your new password">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="offset-sm-3 col-sm-9">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
                            <button type="reset" class="btn btn-warning"><i class="fa fa-sync-alt"></i> Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>