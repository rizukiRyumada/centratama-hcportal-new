<div class="row">
	<div class="col-lg">
		<?= $this->session->flashdata('message'); ?>
	</div>
</div>

<div class="card shadow mb-2" id="print"> <!-- Profil Jabatan anda -->
	<!-- Card Header - Accordion -->
	<div class="card-header">
        <h3 class="my-1 card-title"><?= $posisi['position_name']?></h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <?php if($statusApproval['status_approval'] == 0): ?>
                    <i class="fa fa-exclamation-circle fa-2x" style="color: red"></i>
                <?php elseif($statusApproval['status_approval'] == 1): ?>
                    <i class="fa fa-ellipsis-h fa-2x" style="color: gold"></i>
                <?php elseif($statusApproval['status_approval'] == 2): ?>
                    <i class="fa fa-ellipsis-h fa-2x" style="color: gold"></i>
                <?php elseif($statusApproval['status_approval'] == 3): ?>
                    <i class="fa fa-exclamation-triangle fa-2x" style="color: red"></i>
                <?php elseif($statusApproval['status_approval'] == 4): ?>
                    <i class="fa fa-check-circle fa-2x" style="color: green"></i>
                <?php endif; ?>
            </button>
        </div>
	</div>
	<!-- Card Content - Collapse -->
	
	<div class="card-body py-0">
        <div class="row mb-3">
            <div class="col-md-10 status-text mt-3"> <!-- status text -->
                <div class="row">
                    <div class="col-xl-7">
                        <div class="row">
                            <?php if(!empty($approver[0]['position_name'])): //cek jika tidak punya atasan1?>
                                <div class="col-4">Approver 1</div><div class="col-1">:</div><div class="col-7"><?= $approver[0]['position_name']; ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <?php if(!empty($approver[1]['position_name'])): //cek jika tidak punya atasan2?> 
                                <div class="col-4">Approver 2</div><div class="col-1">:</div><div class="col-7"><?= $approver[1]['position_name']; ?></div>
                            <?php endif; ?>
                        </div>
                        <!-- <div class="row">
                            <div class="col-12"></div>
                        </div> -->
                    </div>
                    <div class="col-xl-5">
                            <!-- Status Approval Infomation
                            0 = Belum diisi
                            1 = Direview Atasan 1
                            2 = Direview Atasan 2
                            3 = Revisi
                            4 = Selesai -->
                        <div class="row">
                        <!-- card status -->
                            <div class="col-4">Status</div><div class="col-1">:</div><div class="col-7">
                                <?php if($statusApproval['status_approval'] == 0): ?>
                                    <span class="badge badge-danger">Not Yet Submitted</span>
                                <?php elseif($statusApproval['status_approval'] == 1): ?>
                                    <span class="badge badge-warning">Submitted</span>
                                <?php elseif($statusApproval['status_approval'] == 2): ?>
                                    <span class="badge badge-warning">First Approval</span>
                                <?php elseif($statusApproval['status_approval'] == 3): ?>
                                    <span class="badge badge-danger">Need Revised</span>
                                <?php elseif($statusApproval['status_approval'] == 4): ?>
                                    <span class="badge badge-success">Final Approval</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row">
                            <?php if(!$statusApproval['status_approval'] == 0): ?>
                                <div class="col-4">Updated</div><div class="col-1">:</div><div class="col-7"><?= date('d F Y, H:i', $statusApproval['diperbarui']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <?php if($statusApproval['pesan_revisi'] !== "null" && $statusApproval['status_approval'] == 3): ?>
                                <div class="col-4">Notes</div><div class="col-1">:</div>
                                <div class="col-7">
                                    <a tabindex="0" class="btn badge" role="button" data-toggle="popover" data-trigger="focus" data-placement="bottom" title="Pesan" data-content="<?= $statusApproval['pesan_revisi']; ?>"><i class="fas fa-comment-dots text-info"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 status-action mt-3"> <!-- status action -->
                <div class="row h-100">
                    <div class="col align-self-center text-center">
                        <?php if($statusApproval['status_approval'] == 0 || $statusApproval['status_approval'] == 3): ?>
                            <a class="btn btn-danger w-100" href="<?= base_url('job_profile/myjp')?>"><i class="align-middle fa fa-pencil-alt fa-2x"></i></a>
                        <?php else: ?>
                            <a class="btn 
                            <?php if($statusApproval['status_approval'] == 1 || $statusApproval['status_approval'] == 2): ?>
                                btn-warning
                            <?php elseif($statusApproval['status_approval'] == 4): ?>
                                btn-success
                            <?php endif; ?>
                            w-100" href="<?= base_url('job_profile/myjp')?>"><i class="align-middle fa fa-search fa-2x"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
	</div>
        
    <!-- footer message -->
    <?php if($statusApproval['status_approval'] == 0): ?>
        <div class="card-footer badge-danger">
            Kindly complete your job profile and submit for approvals.
        </div>
    <?php elseif($statusApproval['status_approval'] == 1): ?>
        <div class="card-footer badge-warning">
            Your job profile is awaiting for approvals.
        </div>
    <?php elseif($statusApproval['status_approval'] == 2): ?>
        <div class="card-footer badge-warning">
            Your job profile is awaiting for final approval.
        </div>
    <?php elseif($statusApproval['status_approval'] == 3): ?>
        <div class="card-footer badge-danger">
            Your job profile need to be revised. Kindly click notes button for comments and re-submit.
        </div>
    <?php elseif($statusApproval['status_approval'] == 4): ?>
        <div class="card-footer badge-success">
            Your job profile is approved.
        </div>
    <?php endif; ?>
</div> <!-- /Profil Jabatan anda -->

<div class="card shadow mb-2" id=""> <!-- My Task -->
    <div class="card-header">
        <h3 class="my-1 card-title">My Task</h3>
    </div>
	<div class="card-body table-responsive p-0">
        <table id="basicTable" class="table table-striped table-hover"  style="display: table;width:100%">
            <thead class="text-center">
                <th class="align-middle" >Division</th>
                <th class="align-middle" >Departement</th>
                <th class="align-middle" >Position</th>
                <th class="align-middle" >Employee Name</th>
                <th class="align-middle" >Date</th>
                <!-- <th class="align-middle"  style="min-width: 60px;">View Details</th> -->
                <th class="align-middle" >View Details</th>
            </thead>
            <tbody>
                <?php foreach($my_task as $v): ?>
                    <tr id="myTask-list">
                        <td><?= $v['divisi'] ?></td>
                        <td><?= $v['departement'] ?></td>
                        <td><?= $v['posisi'] ?></td>
                        <td><?= $v['emp_name'] ?></td>
                        <td><?= date('d F Y, H:i', $v['diperbarui']); ?></td>
                        <td class="align-middle">
                            <div class="container h-100 m-0 px-auto"> <!-- this container make the element to vertically and horizontally centered -->
                                <div class="row justify-content-center align-self-center w-100 m-0">
                                    <a class="btn btn-primary w-100" id="myTask-button" href="<?= base_url('job_profile/taskJp'); ?>?task=<?= $v['nik']; ?>&id=<?= $v['id_posisi']; ?>&status=<?= $v['status_approval'] ?>"><i class="fa fa-search mx-auto"></i></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
	</div>
</div> <!-- /My Task -->