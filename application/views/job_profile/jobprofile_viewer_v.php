<!-- this is JP Editor, you must using template job_footer -->

<?php //this is for preparation data
	$this->load->model('Jobpro_model');

	$tujuanjabatan = $this->Jobpro_model->getProfileJabatan($posisi['id']);                                              //data tujuan jabatan
	$ruangl        = $this->Jobpro_model->getDetail('*', 'ruang_lingkup', array('id_posisi' => $posisi['id']));          //data ruang lingkup
	$tu_mu         = $this->Jobpro_model->getDetail('*', 'tantangan', array('id_posisi' => $posisi['id']));              // data tanggung jawab dan masalah utama
	$kualifikasi   = $this->Jobpro_model->getDetail('*', 'kualifikasi', array('id_posisi' => $posisi['id']));
	$jenk          = $this->Jobpro_model->getDetail('*', 'jenjang_kar', array('id_posisi' => $posisi['id']));
	$hub           = $this->Jobpro_model->getDetail('*', 'hub_kerja', array('id_posisi' => $posisi['id']));
	$tgjwb         = $this->Jobpro_model->getDetails('*', 'tanggung_jawab', array('id_posisi' => $posisi['id']));
	$wen           = $this->Jobpro_model->getDetails('*', 'wewenang', array('id_posisi' => $posisi['id']));
	$atasan        = $this->Jobpro_model->getDetail('position_name', 'position', array('id' => $posisi['id_atasan1']));
?>

                <div class="card-body">
					<div class="row" style="background-color: #e6e6e6;">
						<div class="col-12 pt-2">
							<div class="row mb-2">
								<div class="col-lg-3 font-weight-bold">Divisi</div>
								<div class="col-lg-7"> : <?= $mydiv['division']; ?></div>
								<div class="col-lg-2"><?= date("d  M  Y") ?></div>
							</div>
							<div class="row mb-2">
								<div class="col-lg-3 font-weight-bold">Departemen</div>
								<div class="col-lg-8"> : <?= $mydept['nama_departemen']; ?></div>
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
                		<div class="col-lg-3 font-weight-bold">Nama Jabatan</div>
                		<div class="col-lg-8"> : <?= $posisi['position_name']; ?></div>
                	</div>
                	<div class="row mb-2">
                		<div class="col-lg-3 font-weight-bold">Bertanggung Jawab Kepada</div>
                		<?php if (empty($posisi['id_atasan1'])) : ?>
                		<form action="<?= base_url('job_profile/insatasan'); ?>" method="post">
                			<input type="hidden" value="<?= $posisi['position_id'] ?>" name="id">
                			<div class="col mb-1">
                				<select name="position" class="form-control form-control-sm  border border-danger">
                					<?php foreach ($pos as $p) : ?>
                					<option value="<?= $p['id']; ?>"><?= $p['position_name']; ?></option>
                					<?php endforeach; ?>
                				</select>
                			</div>
                			<div class="col mb-1"><span class="badge badge-danger font-weight-bold">Pilih Posisi Atasan
                					Anda</span></div>
                			<div class="col mb-1">
                				<button type="submit" class="btn btn-sm btn-primary">Save</button>
                			</div>
                		</form>
                		<?php else : ?>
                		<div class="col-lg-8"> : <?= $atasan['position_name']; ?></div>
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
                	<div class="row align-items-end mb-2 bg-secondary text-white">
                		<div class="col">
                			<h5 class="font-weight-bold pt-2">Tanggung Jawab Utama, Aktivitas Utama & Indikator Kinerja:</h5>
                		</div>
                	</div>

                	<div class="row">
                		<div class="table-responsive">
							<?php if(empty($tgjwb)): ?>
								<div class="col-12 alert alert-danger">
									<i>Karyawan belum mengisi Tanggung Jawab Utama, Aktivitas Utama & Indikator Kinerja</i>
								</div>
							<?php else: ?>
                				<table id="tanggung-jawab" class="table">
									<thead>
										<tr>
											<!-- <td>No</td> -->
											<th>Tanggung Jawab Utama</th>
											<th>Aktivitas Utama</th>
											<th>Pengukuran</th>
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
                	<div class="row align-items-end mt-auto mb-2" id="hal6">
                		<div class="col-12">
                			<h5 class="font-weight-bold">Ruang Lingkup Jabatan</h5>
                			<h6 class="font-weight-light mt-2"><em>(Ruang lingkup dan skala kegiatan yang berhubungan
                					dengan
                					pekerjaan)</em></h6>
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
                	<div class="row mt-auto mb-2 bg-secondary text-white">
                		<div class="col pt-2">
                			<h5 class="font-weight-bold">Wewenang Pengambilan Keputusan Dan Pengawasan</h5>
                			<h6 class="font-weight-light mt-2"><em>Uraian jenis wewenang yang diperlukan dalam
                					menjalankan
                					aktivitas pekerjaan :</em></h6>
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
								<thead class="font-weight-bold">
									<tr>
										<td>Kewenangan</td>
										<td>Anda</td>
										<td>Atasan 1</td>
										<td>Atasan 2</td>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($wen as $w) : ?>
									<tr>
										<td><?= $w['kewenangan']; ?></td>
										<td><?= $w['wen_sendiri']; ?></td>
										<td><?= $w['wen_atasan1']; ?></td>
										<td><?= $w['wen_atasan2']; ?></td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
							<div class="note py-2">
								<ul class="ml-2 mb-0">
									<li>R : Responsibility = Memiliki tanggung jawab dan wewenang untuk mengambil keputusan
									</li>
									<li>A : Accountability = tidak dapat mengambil keputusan tetapi bertanggung jawab dalam
										pelaksanaan dan hasilnya</li>
									<li>V : Veto = dapat meng-anulir atau mem-blok suatu keputusan</li>
									<li>C : Consult= sebelum mengambil keputusan harus memberi masukan dan mengkonsultasikan
										lebih
										dahulu dengan atasan</li>
									<li>I : Informed = harus diberi informasi setelah keputusan diambil</li>
								</ul>
							</div>
						<?php endif; ?>
                	</div>

                	<!-- start hubungan kerja -->
                	<hr>
                	<div class="row mt-4" id="hal5">
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
							<div class="col-6">
								<h5><strong>Hubungan Internal</strong></h5>
								<div class="hubIntData"><?= $hub['hubungan_int']; ?></div>
							</div>
							<div class="col-6">
								<h5><strong>Hubungan Ekternal</strong></h5>
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
                	<div class="row align-items-end mt-2">
                		<div class="col">
                			<h5 class="font-weight-bold">Jumlah Dan Level Staf Yang Dibawahi</h5>
                			<h6 class="font-weight-light mt-2"><em>Jumlah dan level staf yang memiliki garis
                					pertanggungjawaban ke jabatan :</em></h6>
                		</div>
                	</div>
                	<dl class="row mt-2">
                		<dt class="col-2">Jumlah Staff</dt>
                		<dd class="col-1">
                			<p class="jumTotStaff"><?= array_sum($dataStaff); ?></p>
                		</dd>
                		<dd class="col-9">Orang</dd>

                		<dt class="col-2">Manager</dt>
                		<dd class="col-2">
                			<div class="input-group input-group-sm mb-3">
                				<div type="text" id="totMgr" class="form-control form-control-sm">
									<?= $staff['manager']; ?>
								</div>
                				<div class="input-group-append">
                					<span class="input-group-text" id="basic-addon2">Orang</span>
                				</div>
                			</div>
                		</dd>
                		<dd class="col-8"></dd>

                		<dt class="col-2">Supervisor</dt>
                		<dd class="col-2">
                			<div class="input-group input-group-sm mb-3">
                				<div type="text" id="totSpvr" class="form-control form-control-sm">
									<?= $staff['supervisor']; ?>
								</div>
                				<div class="input-group-append">
                					<span class="input-group-text" id="basic-addon2">Orang</span>
                				</div>
                			</div>
                		</dd>
                		<dd class="col-8"></dd>


                		<dt class="col-2">Staff</dt>
                		<dd class="col-2">
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

                	<!-- start tantangan dan maslah utama -->
                	<hr>
                	<div class="row mt-2">
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
                	<div class="row mt-4">
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
										<th class="head-kualifikasi">Pendidikan Formal</th>
										<td id="pendidikan">: <?= $kualifikasi['pendidikan']; ?></td>
									</tr>
									<tr>
										<th class="head-kualifikasi">Pengalaman Kerja</th>
										<td id="pengalaman">: <?= $kualifikasi['pengalaman']; ?></td>
									</tr>
									<tr>
										<th class="head-kualifikasi">Pengetahuan</th>
										<td id="pengetahuan">: <?= $kualifikasi['pengetahuan']; ?></td>
									</tr>
									<tr>
										<th class="head-kualifikasi">Kompetensi & Keterampilan</th>
										<td id="kompetensi">: <?= $kualifikasi['kompetensi']; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					<?php endif; ?>

                	<!-- start jenjang karir / karir berikutnya di masa depan-->
                	<hr>
                	<div class="row mt-3">
                		<div class="col-12">
                			<h5 class="font-weight-bold">Jabatan Berikutnya Di Masa Depan</h5>
                			<h6 class="font-weight-light mt-2"><em>Pergerakan karir yang memungkinkan setelah memegang
                					jabatan ini? (baik yang utama/ primary maupun yang secondary):</em></h6>
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

                	<!-- start Struktur Organisasi -->
                	<?php if($atasan != 0): ?>
                	<hr />
                	<div class="row mt-3">
                		<div class="col-12">
                			<h5 class="font-weight-bold mb-3">Struktur Organisasi</h5>
                		</div>
                	</div>
                	<div class="row">
                		<div class="col-12">
                			<div id="chart-container"></div>
                		</div>
                	</div>
                	<?php endif; ?>

                </div>