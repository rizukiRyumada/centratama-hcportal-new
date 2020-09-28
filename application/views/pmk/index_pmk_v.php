
<!-- Admin Panel -->
<?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
    <div class="row">
        <div class="col">
            <div class="card card-danger">
                <div class="card-header">
                    <h5 class="m-0"><i class="fas fa-user-shield"></i>Admin Panel</h5>
                </div>
                <div class="card-body">
                    <div class="row text justify-content-end">
                        <div class="col-lg-5 col-12 align-self-center">
                            <button class="w-100 btn btn-warning">
                                <div class="row h-100">
                                    <div class="col-auto align-self-center text-center">
                                        <img src="<?= base_url('/assets/img/illustration/contract.svg'); ?>" alt="add-document" class="img-lg">
                                    </div>
                                    <div class="col align-self-center text-center">
                                        <p class="text m-0">Check for employee who have 2 months before the End of Contract Date</p>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false"><i class="fas fa-file-alt"></i> Assessment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="true"><i class="fas fa-file-signature"></i> Summary</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <!-- Tabel assessment -->
                    <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <div class="row">
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label for="divisi">Division:</label>
                                    <select id="divisi" class="custom-select form-control form-control-sm">
                                        <option value="">All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label for="departement">Departement:</label>
                                    <select id="departement" class="custom-select form-control form-control-sm">
                                        <option value="">All</option>        
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select id="status" class="custom-select form-control form-control-sm">
                                        <option value="">All</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <table id="mainTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>Division</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Employee Name</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>CG000000</td>
                                    <td>HC</td>
                                    <td>HC</td>
                                    <td>HCIS Specialist</td>
                                    <td>Wahyudi</td>
                                    <td><div class="row h-100">
                                        <div class="col align-self-center text-center">
                                            <span class="badge badge-danger w-100">Draft</span>
                                        </div>
                                    </div></td>
                                    <td>
                                        <div class="row h-100">
                                            <div class="col align-self-center text-center">
                                                <a href="<?= base_url('pmk/assessment'); ?>" class="btn btn-primary w-100"><i class="fas fa-pencil-alt"></i></a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div> <!-- /Tabel assessment -->

                    <!-- Tabel Summary -->
                    <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                        <div class="row d-flex align-items-stretch">
                            <?php foreach($divisi as $v): ?>
                                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                                    <a href="<?= base_url('pmk/summary'); ?>?div=<?= $v['id']; ?>" class="card bg-light w-100">
                                        <!-- <div class="card-header text-muted border-bottom-0">
                                            Digital Strategist
                                        </div> -->
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-7">
                                                    <h2 class="lead"><b><?= $v['division']; ?></b></h2>
                                                    <p class="text-sm badge badge-<?= $v['color']; ?>"><b>Need Attention: </b> 4 </p>
                                                    <p class="text-muted text-sm m-0"><b>Employees: </b> <?= $v['emp_total']; ?> </p>
                                                    <p class="text-muted text-sm m-0"><b>Divhead: </b> <?= $v['emp_name']; ?> </p>
                                                </div>
                                                <div class="col-5 text-center align-self-center">
                                                    <!-- <img src="../../dist/img/user1-128x128.jpg" alt="" class="img-circle img-fluid"> -->
                                                    <i class="fas fa-users fa-5x text-<?= $v['color']; ?>"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div> <!-- /Tabel Summary -->
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>