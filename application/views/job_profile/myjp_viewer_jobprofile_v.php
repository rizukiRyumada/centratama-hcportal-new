<!-- card start -->
<div class="card mb-2" id="print">
	<!-- Card Header - Accordion -->
	<!-- <div class="d-block card-header py-3">
		<h5 class="m-0 font-weight-bold text-black-50">Profil Jabatan Anda</h5>
	</div> -->
	<!-- Card Content - Collapse -->
	<div class="collapse show">
		<!-- JP Viewer -->
		<?php $this->load->view('job_profile/jobprofile_viewer_v') ?>
		<!-- JP Viewer -->
		
		<?php if($approval['status_approval'] == 0): ?>
			<div class="card-footer badge-danger d-print-none">
				<p class="text-center m-0 p-0">Kindly complete your job profile and submit for approvals.</p>
			</div>
		<?php elseif($approval['status_approval'] == 1): ?>
			<div class="card-footer badge-warning d-print-none">
				<p class="text-center m-0 p-0">Your job profile is awaiting for approvals.</p>
			</div>
		<?php elseif($approval['status_approval'] == 2): ?>
			<div class="card-footer badge-warning d-print-none">
				<p class="text-center m-0 p-0">Your job profile is awaiting for final approval.</p>
			</div>
		<?php elseif($approval['status_approval'] == 3): ?>
			<div class="card-footer badge-danger d-print-none">
				<p class="text-center m-0 p-0">Your job profile need to be revised. Kindly click notes button for comments and re-submit. </p>
			</div>
		<?php elseif($approval['status_approval'] == 4): ?>
			<div class="card-footer badge-success d-print-none">
				<p class="text-center m-0 p-0">Your job profile is approved.</p>
			</div>
		<?php endif; ?>
	</div>
</div>

<div id="testdiv">
</div>