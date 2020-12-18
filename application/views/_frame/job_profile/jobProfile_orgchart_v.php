<!-- start Struktur Organisasi -->
<?php if($atasan != 0): ?>
	<!-- <div class="row my-3 bg-gray py-2">
		<div class="col-12">
			<h5 class="font-weight-bold">Struktur Organisasi</h5>
			<h6 class="font-weight-light mt-2"><em>Menggambarkan posisi anda di struktur organisasi :</em></h6>
		</div>
	</div> -->
	<div class="row justify-content-end my-2 d-print-none">
		<div class="col-2 text-center">
			<span>Zoom :</span>
			<div class="btn-group">
				<button id="zoomIn" class="btn btn-primary"><i class="fa fa-plus"></i></button>
				<button id="zoomOut" class="btn btn-primary"><i class="fa fa-minus"></i></button>
			</div>
		</div>
	</div>
	<div class="row d-print-block">
		<div class="col-12">
			<div id="chart-container"></div>
		</div>
	</div>
<?php endif; ?>