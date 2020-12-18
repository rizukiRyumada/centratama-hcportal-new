<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header bg-gray-light">
                <?php if(!empty($this->session->userdata('msgapproval'))): ?>
                    <div class="row">
                        <div class="alert alert-success alert-dismissible fade show col" role="alert">
                            <strong>Status Approval has been refreshed!.</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
                <?php $this->session->unset_userdata('msgapproval'); ?>
                <div class="row">
                    <div class="col-2 mx-0 px-0">
                        <div class="container d-flex h-100 m-0 px-0"> <!-- this container make the element to vertically and horizontally centered -->
                            <div class="row justify-content-center align-self-center w-100 m-0">
                                <div class="d-inline-block">
                                    <a href="<?= base_url('appSettings/') ?>jobProfile" type="button" class="btn btn-primary" data-placement="left" title="Back to Report Page"><i class="fa fa-chevron-left text-white"></i></a>
                                    <a href="<?= base_url('job_profile/') ?>startJobApprovalSystem" class="btn btn-danger btn-icon-split" title="Refresh Approval - If there is a Employe not listed down here.">
                                        <span class="icon text-white">
                                            <i class="fa fa-sync"></i>
                                        </span>
                                        <span class="text">Refesh</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="divisi">Division:</label>
                            <select id="divisi" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <?php foreach($divisi as $v): ?>
                                    <option value="div-<?= $v['id'] ?>"><?= $v['division'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="departement">Departement :</label>
                            <select id="departement" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <?php foreach($dept as $v): ?>
                                    <option value="dept-<?= $v['id'] ?>"><?= $v['nama_departemen'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="status">Status :</label>
                            <select id="status" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <option value="0">Not Yet Submitted</option>
                                <option value="1">Submitted</option>
                                <option value="2">First Approval</option>
                                <option value="3">Need Revised</option>
                                <option value="4">Final Approval</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="mainTable" width="100%">
                        <thead>
                            <tr class="text-center">
                                <th class="align-middle" >Division</th>
                                <th class="align-middle" >Department</th>
                                <th class="align-middle" >Position</th>
                                <th class="align-middle" >Employee Name</th>
                                <th class="align-middle" style="min-width: 170px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($approval_data as$v): ?>
                            <tr id="myTask-list">
                                <td data-filter="div-<?= $v['id_div'] ?>"><?= $v['divisi'] ?></td>
                                <td data-filter="dept-<?= $v['id_dept'] ?>"><?= $v['departement'] ?></td>
                                <td><?= $v['posisi'] ?></td>
                                <td><?= $v['emp_name'] ?></td>
                                <td data-filter="<?= $v['status_approval'] ?>">
                                    <div class="row w-100">
                                        <div class="col my-1">
                                            <!-- <label class="mr-sm-2" for="inlineFormCustomSelect">Preference</label> -->
                                            <select class="custom-select mr-sm-2 status_approval" id="inlineFormCustomSelect" data-id="<?= $v['id_posisi'] ?>" style="
                                                <?php if($v['status_approval'] == 0): //styling buat warna select
                                                    echo("background-color: red; color: white;");
                                                elseif($v['status_approval'] == 1): 
                                                    echo("background-color: yellow; color: black;");
                                                elseif($v['status_approval'] == 2): 
                                                    echo("background-color: yellow; color: black;");
                                                elseif($v['status_approval'] == 3): 
                                                    echo("background-color: orange; color: white;");
                                                elseif($v['status_approval'] == 4): 
                                                    echo("background-color: green; color: white;");
                                                endif; ?>
                                            ">
                                                <option <?php if($v['status_approval'] == 0){echo ('selected');} ?> value="0">Not Yet Submitted</option>
                                                <option <?php if($v['status_approval'] == 1){echo ('selected');} ?> value="1">Submitted</option>
                                                <option <?php if($v['status_approval'] == 2){echo ('selected');} ?> value="2">First Approval</option>
                                                <option <?php if($v['status_approval'] == 3){echo ('selected');} ?> value="3">Need Revised</option>
                                                <option <?php if($v['status_approval'] == 4){echo ('selected');} ?> value="4">Final Approval</option>
                                            </select>
                                        </div>
                                        <div class="col-1 my-1 mr-3">
                                            <?php if($v['nik'] != " "): ?>
                                                <button class="btn status_approval sendNotification" data-nik="<?= $v['nik']; ?>" data-id="<?= $v['id_posisi'] ?>" style="<?php if($v['status_approval'] == 0): //styling buat warna select
                                                        echo("background-color: red; color: white;");
                                                    elseif($v['status_approval'] == 1): 
                                                        echo("background-color: yellow; color: black;");
                                                    elseif($v['status_approval'] == 2): 
                                                        echo("background-color: yellow; color: black;");
                                                    elseif($v['status_approval'] == 3): 
                                                        echo("background-color: orange; color: white;");
                                                    elseif($v['status_approval'] == 4): 
                                                        echo("background-color: green; color: white;");
                                                    endif; ?>
                                                ">
                                                    <i class="fa fa-envelope"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal send notification -->
<div class="modal fade" id="kirimNotifikasi" tabindex="-1" role="dialog" aria-labelledby="kirimNotifikasiLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kirimNotifikasiLabel">Apa Anda yakin?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Kirim notifikasi lewat email ke karyawan ini?
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary">Kirim</button>

                <button type="button" class="btn btn-primary" data-dismiss="modal">Tidak</button>
            </div>
        </div>
    </div>
</div>