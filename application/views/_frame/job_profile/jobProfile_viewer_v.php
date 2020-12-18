<div class="card-body">
	<div class="row pt-2" style="background-color: #e6e6e6;">
		<div class="col-8">
			<div class="row">
				<div class="col">
					<div class="row">
					<div class="col-md-3 font-weight-semibold"><b>Divisi</b></div>
					<div class="col-md-9">: <?= $mydiv['division']; ?></div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3 font-weight-semibold"><b>Departemen</b></div>
					<div class="col-md-9">: <?= $mydept['nama_departemen']; ?></div>
				</div>
				</div>
			</div>
		</div>
		<div class="col-4">
			<div class="row">
				<div class="col text-right"><?= date("d  M  Y") ?></div>
			</div>		
		</div>
	</div>
	
	<hr />
	<!-- start identifikasi jabatan -->
	<div class="row align-items-end mt-3 mb-2">
		<div class="col">
			<h5 class="font-weight-bold">Identifikasi Jabatan</h5>
		</div>
	</div>
	<div class="row mb-2">
		<div class="col-md-3 font-weight-semibold">Nama Jabatan</div>
		<div class="col-md-9">: <?= $posisi['position_name']; ?></div>
	</div>
	<div class="row mb-2">
		<div class="col-md-3 font-weight-semibold">Bertanggung Jawab Kepada</div>
		<?php if (empty($posisi['id_atasan1'])) : ?>
			<div class="col-md-9"><span class="badge badge-danger">: Data Kosong</span></div>
		<?php else : ?>
			<div class="col-md-9": >: <?= $atasan['position_name']; ?></div>
		<?php endif; ?>
	</div>
	<!-- start tujuan jabatan -->
	<hr>
	<div class="row mt-3 mb-2">
		<div class="col">
			<h5 class="font-weight-bold">Tujuan Jabatan</h5>
		</div>
	</div>
	<?php if(empty($tujuanjabatan)): ?>
		<div class="row ml-0 mb-2">
			<div class="col-lg-12 view-tujuan alert alert-danger">
				<i>Karyawan belum mengisi Tujuan Jabatan</i>
			</div>
		</div>
	<?php else: ?>
		<div class="row ml-1 mb-2">
			<div class="col-lg-12 view-tujuan">
				<?= $tujuanjabatan['tujuan_jabatan']; ?>
			</div>
		</div>
	<?php endif; ?>
	<!-- start tanggung jawab utama -->
	<hr>
	<div class="row align-items-end my-3 bg-gray py-2">
		<div class="col">
			<h5 class="font-weight-bold">Tanggung Jawab Utama, Aktivitas Utama & Indikator Kinerja:</h5>
			<h6 class="font-weight-light mt-2"><em>Uraian Tanggung Jawab Utama dilengkapi dengan penjelasan aktivitas utama serta indikator pengukuran keberhasilan :</em></h6>
		</div>
	</div>
	<div class="row">
		<div class="table-responsive">
			<?php if(empty($tgjwb)): ?>
				<div class="col-12 alert alert-danger">
					<i>Karyawan belum mengisi Tanggung Jawab Utama, Aktivitas Utama & Indikator Kinerja</i>
				</div>
			<?php else: ?>
				<table id="tanggung-jawab" class="table table-bordered table-hover">
					<thead class="font-weight-semibold">
						<tr>
							<!-- <td>No</td> -->
							<th>Tanggung Jawab Utama</th>
							<th class="text-center">Aktivitas Utama</th>
							<th class="text-center">Pengukuran</th>
						</tr>
					</thead>
					<tbody id="table-body">
						<?php foreach ($tgjwb as $t) : ?>
						<tr id="<?= $t['id_tgjwb']; ?>">
							<td><?= $t['keterangan']; ?></td>
							<td>
								<?= $t['list_aktivitas']; ?>
							</td>
							<td>
								<?= $t['list_pengukuran']; ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</div>
	<!-- start ruang lingkup -->
	<hr>
	<div class="row my-3 bg-gray py-2" id="hal6">
		<div class="col-12">
			<h5 class="font-weight-bold">Ruang Lingkup Jabatan</h5>
			<h6 class="font-weight-light mt-2"><em>Ruang lingkup dan skala kegiatan yang berhubungan dengan pekerjaan :</em></h6>
		</div>
	</div>
	<div class="row">
		<?php if(empty($ruangl)): ?>
			<div class="col-12 alert alert-danger">
				<i>Karyawan belum mengisi Ruang Lingkup Jabatan</i>
			</div>
		<?php else: ?>
			<div class="col-12 view-ruang">
				<?= $ruangl['r_lingkup']; ?>
			</div>
		<?php endif; ?>
	</div>
	
	<!-- start wewenang -->
	<hr>
	<div class="row my-3 bg-gray py-2">
		<div class="col">
			<h5 class="font-weight-bold">Wewenang Pengambilan Keputusan Dan Pengawasan</h5>
			<h6 class="font-weight-light mt-2"><em>Uraian jenis wewenang yang diperlukan dalam menjalankan aktivitas pekerjaan :</em></h6>
		</div>
	</div>
	<div class="col-lg table-responsive">
		<?php if(empty($wen)): ?>
			<div class="row">
				<div class="col-12 alert alert-danger m-0">
					<i>Karyawan belum mengisi Wewenang Pengambilan Keputusan Dan Pengawasan</i>
				</div>
			</div>
		<?php else: ?>
			<table class="table">
				<thead class="font-weight-semibold">
					<tr>
						<td>Kewenangan</td>
						<td class="text-center">Anda</td>
						<td class="text-center">Atasan 1</td>
						<td class="text-center">Atasan 2</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($wen as $w) : ?>
					<tr>
						<td><?= $w['kewenangan']; ?></td>
						<td class="text-center"><?= $w['wen_sendiri']; ?></td>
						<td class="text-center"><?= $w['wen_atasan1']; ?></td>
						<td class="text-center"><?= $w['wen_atasan2']; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<div class="py-4 bg-gray-light border-radius-3 px-3">
				<ul class="ml-2 mb-0">
					<li><b class="font-weight-semibold">R</b> : Responsibility = Memiliki tanggung jawab dan wewenang untuk mengambil keputusan
					</li>
					<li><b class="font-weight-semibold">A</b> : Accountability = tidak dapat mengambil keputusan tetapi bertanggung jawab dalam
						pelaksanaan dan hasilnya</li>
					<li><b class="font-weight-semibold">V</b> : Veto = dapat meng-anulir atau mem-blok suatu keputusan</li>
					<li><b class="font-weight-semibold">C</b> : Consult= sebelum mengambil keputusan harus memberi masukan dan mengkonsultasikan
						lebih
						dahulu dengan atasan</li>
					<li><b class="font-weight-semibold">I</b> : Informed = harus diberi informasi setelah keputusan diambil</li>
				</ul>
			</div>
		<?php endif; ?>
	</div>
	<!-- start hubungan kerja -->
	<hr>
	<div class="row my-3 bg-gray py-2" id="hal5">
		<div class="col">
			<h5 class="font-weight-bold">Hubungan Kerja</h5>
			<h6 class="font-weight-light mt-2"><em>Uraian tujuan dan hubungan jabatan dengan pihak luar
					dan
					pihak dalam perusahaan selain dengan atasan langsung maupun bawahan, yang diperlukan
					dalam melakukan pekerjaan :</em></h6>
		</div>
	</div>
	<div class="row ml-2">
		<?php if(empty($hub)): ?>
			<div class="col-12 alert alert-danger m-0">
				<i>Karyawan belum mengisi Hubungan Kerja</i>
			</div>
		<?php else: ?>
			<div class="col-sm-6">
				<p class="font-weight-semibold"><strong>Hubungan Internal</strong></p>
				<div class="hubIntData"><?= $hub['hubungan_int']; ?></div>
			</div>
			<div class="col-sm-6">
				<p class="font-weight-semibold"><strong>Hubungan Ekternal</strong></p>
				<div class="hubEksData"> <?= $hub['hubungan_eks']; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<!-- start jumlah staff -->
	<?php
	$dataStaff = [$staff['manager'], $staff['supervisor'], $staff['staff']];
	?>
	<hr>
	<div class="row align-items-end my-2 bg-gray py-2">
		<div class="col">
			<h5 class="font-weight-bold">Jumlah Dan Level Staf Yang Dibawahi</h5>
			<h6 class="font-weight-light mt-2"><em>Jumlah dan level staf yang memiliki garis
					pertanggungjawaban ke jabatan :</em></h6>
		</div>
	</div>
	<dl class="row justify-content-start mb-0">
		<dt class="col-sm-2">Jumlah Staff</dt>
		<dd class="col-sm-3 mb-0">
			<div class="input-group input-group-sm mb-3">
				<div type="text" id="totMgr" class="jumTotStaff form-control form-control-sm">
					<?= array_sum($dataStaff); ?>
				</div>
				<div class="input-group-append">
					<span class="input-group-text" id="basic-addon2">Orang</span>
				</div>
			</div>
		</dd>
	</dl>
	<dl class="row justify-content-start mb-0">
		<dt class="col-sm-2">Manager</dt>
		<dd class="col-sm-3 mb-0">
			<div class="input-group input-group-sm mb-3">
				<div type="text" id="totMgr" class="form-control form-control-sm">
					<?= $staff['manager']; ?>
				</div>
				<div class="input-group-append">
					<span class="input-group-text" id="basic-addon2">Orang</span>
				</div>
			</div>
		</dd>
	</dl>
	<dl class="row justify-content-start mb-0">
		<dt class="col-sm-2">Supervisor</dt>
		<dd class="col-sm-3 mb-0">
			<div class="input-group input-group-sm mb-3">
				<div type="text" id="totSpvr" class="form-control form-control-sm">
					<?= $staff['supervisor']; ?>
				</div>
				<div class="input-group-append">
					<span class="input-group-text" id="basic-addon2">Orang</span>
				</div>
			</div>
		</dd>
	</dl>
	<dl class="row justify-content-start mb-0">
		<dt class="col-sm-2">Staff</dt>
		<dd class="col-sm-3 mb-0">
			<div class="input-group input-group-sm mb-3">
				<div type="text" id="totStaf" class="form-control form-control-sm">
					<?= $staff['staff']; ?>
				</div>
				<div class="input-group-append">
					<span class="input-group-text" id="basic-addon2">Orang</span>
				</div>
			</div>
		</dd>
	</dl>
	<!-- start tantangan dan masalah utama -->
	<hr>
	<div class="row my-2 bg-gray py-2">
		<div class="col-12">
			<h5 class="font-weight-bold">Tantangan Dan Masalah Utama</h5>
			<h6 class="font-weight-light mt-2"><em>Tantangan yang melekat pada jabatan dan masalah yang
					sulit/ rumit yang dihadapi dalam kurun waktu cukup panjang :</em></h6>
		</div>
	</div>
	<div class="row">
		<?php if(empty($tu_mu)): ?>
			<div class="col-12 alert alert-danger m-0">
				<i>Karyawan belum mengisi Tantangan Dan Masalah Utama</i>
			</div>
		<?php else: ?>
			<div class="col-12">
				<div class="view-tantangan">
					<?= $tu_mu['text']; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<!-- start kualifikasi dan pengalaman -->
	<hr>
	<div class="row my-3 bg-gray py-2">
		<div class="col-12">
			<h5 class="font-weight-bold">Kualifikasi dan Pengalaman </h5>
			<h6 class="font-weight-light mt-2"><em>Persyaratan minimum yang harus dipenuhi : pendidikan,
					lama pengalaman kerja yang relevan, kompetensi (soft dan technical skill), atau
					kualifikasi personal maupun profesional lainnya :</em></h6>
		</div>
	</div>
	<?php if(empty($kualifikasi)): ?>
		<div class="col-12 alert alert-danger m-0">
			<i>Karyawan belum mengisi Tantangan Dan Masalah Utama</i>
		</div>
	<?php else: ?>
		<div class="table-responsive">
			<table id="tableK" class="table table-borderless tableK" width="25%">
				<tbody>
					<tr>
						<th class="font-weight-semibold head-kualifikasi">Pendidikan Formal</th>
						<td id="pendidikan">: <?= $kualifikasi['pendidikan']; ?></td>
					</tr>
					<tr>
						<th class="font-weight-semibold head-kualifikasi">Pengalaman Kerja</th>
						<td id="pengalaman">: <?= $kualifikasi['pengalaman']; ?></td>
					</tr>
					<tr>
						<th class="font-weight-semibold head-kualifikasi">Pengetahuan</th>
						<td id="pengetahuan">: <?= $kualifikasi['pengetahuan']; ?></td>
					</tr>
					<tr>
						<th class="font-weight-semibold head-kualifikasi">Kompetensi & Keterampilan</th>
						<td id="kompetensi">: <?= $kualifikasi['kompetensi']; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
	<!-- start jenjang karir / karir berikutnya di masa depan-->
	<hr>
	<div class="row my-3 bg-gray py-2">
		<div class="col-12">
			<h5 class="font-weight-bold">Jabatan Berikutnya Di Masa Depan</h5>
			<h6 class="font-weight-light mt-2"><em>Pergerakan karir yang memungkinkan setelah memegang
					jabatan ini? (baik yang utama/ primary maupun yang secondary) :</em></h6>
		</div>
	</div>
	<div class="row">
		<?php if(empty($jenk)): ?>
			<div class="col-12 alert alert-danger">
				<i>Karyawan belum mengisi Jabatan Berikutnya Di Masa Depan</i>
			</div>
		<?php else: ?>
			<div class="col-12">
				<div class="view-jenjang">
					<?= $jenk['text']; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>