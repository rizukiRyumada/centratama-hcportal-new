    <!-- card start -->
    <div class="card shadow mb-2" id="print">
		<!-- Card Header - Accordion -->
		<div h class=" row card-header py-3">
			<div class="col-sm-1 h-100 my-0 d-print-none">
				<div class="h-100">
					<div class="my-0">
						<a class="w-100 btn btn-primary" href="<?= base_url('job_profile/'); ?>report" style="width: 50px;"><strong><i class="fa fa-chevron-left"></i></strong></a>
					</div>
				</div>
			</div>
			<div class="col-sm-11 align-self-center">
				<?php if(!empty($emp_name['emp_name'])): ?>
					<h5 class="m-0 font-weight-bold text-black-50"><?= $emp_name['emp_name']; ?></h5>
				<?php else: ?>
					<h5 class="m-0 font-weight-bold text-black-50">No Employe</h5>
				<?php endif; ?>
			</div>
		</div>
		
		<!-- Card Content - Collapse -->
		<div class="collapse show" id="collapseCardExample">
			<!-- JP Editor -->
			<?php $this->load->view('job_profile/jobprofile_editor_v'); ?>
			<!-- /JP Editor -->

			<div class="card-card-body mb-3">
				<div class="row justify-content-end mx-3">
					<div class="col-md-2 text-center align-self-center">
						<div class="form-group m-0">
							<div class="icheck-success d-inline">
								<input type="checkbox" id="verify_od" data-id_posisi='<?= $this->input->get('id'); ?>' <?php if(!empty($statusApproval['verify']) || $statusApproval['verify'] != 0){echo"checked";} ?>>
								<label for="verify_od"><b>Verify OD</b></label>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php if($statusApproval['status_approval'] == 0): ?>
				<div class="card-footer badge-danger">
					<p class="text-center m-0 p-0">Kindly complete your job profile and submit for approvals.</p>
				</div>
			<?php elseif($statusApproval['status_approval'] == 1): ?>
				<div class="card-footer badge-warning">
					<p class="text-center m-0 p-0">Your job profile is awaiting for approvals.</p>
				</div>
			<?php elseif($statusApproval['status_approval'] == 2): ?>
				<div class="card-footer badge-warning">
					<p class="text-center m-0 p-0">Your job profile is awaiting for final approval.</p>
				</div>
			<?php elseif($statusApproval['status_approval'] == 3): ?>
				<div class="card-footer badge-danger">
					<p class="text-center m-0 p-0">Your job profile need to be revised. Kindly click notes button for comments and re-submit. </p>
				</div>
			<?php elseif($statusApproval['status_approval'] == 4): ?>
				<div class="card-footer badge-success">
					<p class="text-center m-0 p-0">Your job profile is approved.</p>
				</div>
			<?php endif; ?>

		</div>
	</div> 