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
                		<?php //if (empty($my['posnameatasan1'])) : ?>
                		<?php if ($posisi['id_atasan1'] < 1) : ?>
                		<!-- <form action="<?= base_url('job_profile/insatasan'); ?>" method="post">
                			<input type="hidden" value="<?= $posisi['id'] ?>" name="id">
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
                		</form> -->
						<?php else : ?>
						<div class="col-lg-3 font-weight-bold">Bertanggung Jawab Kepada</div>
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
                	<div class="row ml-1 mb-2">
                		<?php if (empty($tujuanjabatan)) : ?>
                		<div class="col-lg-11 view-tujuan d-none">

                		</div>
                		<div id="add-tujuan_jabatan" class="col-12">
                			<!-- <input type="hidden" name="id_posisi"> -->
                			<div class="form-group">
                				<textarea class="form-control" name="tujuanbaru" id="tujuanbaru"></textarea>
                			</div>
                			<button id="simpan-tujuan-baru" type="submit" class="btn btn-primary btn-sm"
                				data-id="<?= $posisi['id']; ?>">Save</button>
                		</div>
                		<div class="col justify-content-center edit-tujuan d-none">
                			<button type="button" class="btn btn-circle btn-sm btn-success" data-toggle="tooltip"
                				data-placement="top" title="Edit">
                				<i class="fas fa-1x fa-pencil-alt"></i>
                			</button>
                		</div>
                		<?php else : ?>

                		<div class="col-lg-11 view-tujuan">
                			<?= $tujuanjabatan['tujuan_jabatan']; ?>
                		</div>
                		<div class="col-10 editor-tujuan">
                			<textarea name="tujuan" id="tujuan"><?= $tujuanjabatan['tujuan_jabatan']; ?></textarea>
                			<button type="submit" class="mt-2 btn btn-primary btn-sm"
                				data-id="<?= $tujuanjabatan['id']; ?>" id="simpan-tujuan">Save</button>
                			<button class="batal-edit-tujuan mt-2 btn btn-danger btn-sm">Cancel</button>
                			<br>
                		</div>
                		<div class="col justify-content-center">
                			<button type="button" class="btn btn-circle btn-sm btn-success edit-tujuan"
                				data-toggle="tooltip" data-placement="top" title="Edit">
                				<i class="fas fa-1x fa-pencil-alt"></i>
                			</button>
                		</div>
                		<?php endif; ?>
                	</div>

                	<!-- start tanggung jawab utama -->
                	<hr>
                	<div class="row align-items-end mb-2 bg-secondary text-white">
                		<div class="col">
                			<h5 class="font-weight-bold pt-2">Tanggung Jawab Utama, Aktivitas Utama & Indikator Kinerja:</h5>
                		</div>
                	</div>

                	<div class="row">
                		<div class="table-responsive">

                			<table id="tanggung-jawab" class="table">
                				<thead>
                					<tr>
                						<!-- <td>No</td> -->
                						<th>Tanggung Jawab Utama</th>
                						<th>Aktivitas Utama</th>
                						<th>Pengukuran</th>
                						<th class="" width="8%"><a class="nTgjwb" data-toggle="modal"
                								data-target="#modalTanggungJwb" data-placement="auto" title="Add New"><i
                									class="fas fa-plus-square fa-2x text-primary"
                									style="cursor: pointer;"></i></a></th>
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
                						<td>
                							<a href="#" data-id="<?= $t['id_tgjwb']; ?>" data-toggle="modal"
                								data-target="#modalTanggungJwb"
                								class="eTgjwb btn btn-sm btn-circle btn-success" data-placement="bottom"
                								title="Edit"><i class="fas fa-pencil-alt"></i></a>
                							<!-- <button data-id="<?= $t['id_tgjwb']; ?>"  class=" btn btn-sm btn-circle btn-danger" data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></button> -->
                							<button
                								href="<?= base_url('job_profile/hapusTanggungJawab/')  .  $t['id_tgjwb']; ?>"
                								class="hapusJobs btn btn-sm btn-circle btn-danger"
                								data-placement="bottom" title="Delete"><i
                									class="fas fa-trash-alt"></i></button>
                						</td>
                					</tr>
                					<?php endforeach; ?>
                				</tbody>
                			</table>
                		</div>
                	</div>

                	<!-- start ruang lingkup -->
                	<hr>
                	<div class="row align-items-end mt-auto mb-2" id="hal6">
                		<div class="col-11">
                			<h5 class="font-weight-bold">Ruang Lingkup Jabatan</h5>
                			<h6 class="font-weight-light mt-2"><em>(Ruang lingkup dan skala kegiatan yang berhubungan
                					dengan
                					pekerjaan)</em></h6>
                		</div>
                		<?php if(empty($ruangl)): ?>
                		<div class="col d-flex justify-content-center">
                			<!-- nothing -->
                		</div>
                		<?php else: ?>
                		<div class="col d-flex justify-content-center">
                			<button type="button" class="btn btn-circle btn-sm btn-success edit-ruang"
                				data-toggle="tooltip" data-placement="top" title="Edit"><i
                					class="fas fa-1x fa-pencil-alt"></i></button>
                		</div>
                		<?php endif;?>

                	</div>
                	<?php if (empty($ruangl)) : ?>
                	<div class="col-12 mb-3">
                		<!-- <form action="<?= base_url('job_profile/addruanglingkup'); ?>" method="post"> -->
                		<div class="form-group">
                			<textarea class="form-control" name="add-ruangl" id="add-ruangl" rows="2"></textarea>
                		</div>
                		<button id="simpan-ruangl-baru" type="submit" class="btn btn-primary btn-sm"
                			data-id="<?= $posisi['id']; ?>">Save</button>
                		<!-- </form> -->
                	</div>
                	<?php else : ?>
                	<div class="row">
                		<div class="col-11 view-ruang">
                			<?= $ruangl['r_lingkup']; ?>
                		</div>
                	</div>
                	<div class="editor-ruang mb-3">
                		<textarea name="ruang" id="ruang"><?= $ruangl['r_lingkup']; ?></textarea>
                		<button type="submit" class="mt-2 btn btn-primary btn-sm" data-id="<?= $ruangl['id']; ?>"
                			id="simpan-ruang">Save</button>
                		<button class="batal-edit-ruang mt-2 btn btn-danger btn-sm">Cancel</button>
                	</div>
                	<?php endif; ?>

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
                		<table class="table" id="wewenang">
                			<thead class="font-weight-bold">
                				<tr>
                					<td>Kewenangan</td>
                					<td>Anda</td>
                					<td>Atasan 1</td>
                					<td>Atasan 2</td>
                					<td><a id="addwen" class="ml-n1"><i class="fas fa-plus-square fa-2x text-primary"
                								style="color: dodgerblue; cursor: pointer;" data-toggle="tooltip" data-placement="auto" title="Add New"></i></a></td>
                				</tr>
                			</thead>
                			<tbody>
                				<?php foreach ($wen as $w) : ?>
                				<tr>
                					<td><?= $w['id']; ?></td>
                					<td><?= $w['kewenangan']; ?></td>
                					<td><?= $w['wen_sendiri']; ?></td>
                					<td><?= $w['wen_atasan1']; ?></td>
                					<td><?= $w['wen_atasan2']; ?></td>
                				</tr>
                				<?php endforeach; ?>
                			</tbody>
                			<table id="newWen" class="mb-3">
                				<tr>
                					<!-- <form action="<?= base_url('job_profile/addwen'); ?>" method="post"> -->
                					<td class="ml-0"><input type="text" name="wewenang" class="form-control" required
                							placeholder="Masukkan Kewenangan"></td>
                					<td>
                						<select id="wen_sendiri" name="wen_sendiri" class="form-control" required>
                							<option value="">Wewenang Anda</option>
                							<option value="-">-</option>
                							<option value="R">R : Responsibility</option>
                							<option value="A">A : Accountability</option>
                							<option value="V">V : Veto</option>
                							<option value="C">C : Consult</option>
                							<option value="I">I : Informed</option>
                						</select>
                					</td>
                					<td>
                						<select id="wen_atasan1" name="wen_atasan1" class="form-control" required>
                							<option value="">Wewenang Atasan Pertama</option>
                							<option value="-">-</option>
                							<option value="R">R : Responsibility</option>
                							<option value="A">A : Accountability</option>
                							<option value="V">V : Veto</option>
                							<option value="C">C : Consult</option>
                							<option value="I">I : Informed</option>
                						</select>
                					</td>
                					<td>
                						<select id="wen_atasan2" name="wen_atasan2" class="form-control" required>
                							<option value="">Wewenang Atasan Kedua</option>
                							<option value="-">-</option>
                							<option value="R">R : Responsibility</option>
                							<option value="A">A : Accountability</option>
                							<option value="V">V : Veto</option>
                							<option value="C">C : Consult</option>
                							<option value="I">I : Informed</option>
                						</select>
                					</td>
                					<td><button id="add-wewenang-baru"
                							class="btn btn-primary btn-sm mr-n3" data-id="<?= $posisi['id']; ?>">Save</button>
                					</td>
                					<!-- </form> -->
                				</tr>
                			</table>
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

                	<?php if (empty($hub)) : ?>
                	<div class="row">
                		<div class="col-12">
                			<!-- <form method="post" action="<?= base_url('job_profile/addHubungan'); ?>"> -->
                			<div class="form-group">
                				<label for="internal">Hubungan Internal</label>
                				<textarea class="form-control" name="internal" id="internal"></textarea>
                			</div>
                			<div class="form-group">
                				<label for="eksternal">Hubungan Eksternal</label>
                				<textarea class="form-control" name="eksternal" id="eksternal"></textarea>
                			</div>
                			<button id="simpan-hubungan-baru" class="btn btn-primary btn-sm"
                				data-id="<?= $posisi['id'] ?>">Save</button>
                			<!-- </form> -->
                		</div>
                	</div>
                	<?php else : ?>

                	<div class="row ml-2">
                		<div class="col-5">
                			<h5><strong>Hubungan Internal</strong></h5>
                			<div class="hubIntData"><?= $hub['hubungan_int']; ?></div>
                			<textarea id="hubInt"><?= $hub['hubungan_int']; ?></textarea>
                			<button data-id="<?= $hub['id']; ?>"
                				class="btn btn-primary btn-sm simpanhubInt mt-1 mb-2">Save</button>
                			<button class="btn btn-danger btn-sm batalhubInt mt-1 mb-2">Cancel</button>
                		</div>
                		<div class="col-sm-1 d-flex justify-content-center">
                			<span class="edit-hubInt btn btn-sm btn-circle btn-success" data-toggle="tooltip"
                				data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></span>
                		</div>
                		<div class="col-5">
                			<h5><strong>Hubungan Ekternal</strong></h5>
                			<div class="hubEksData"> <?= $hub['hubungan_eks']; ?>
                			</div>
                			<textarea id="hubEks"><?= $hub['hubungan_eks']; ?></textarea>
                			<button data-id="<?= $hub['id']; ?>"
                				class="btn btn-primary btn-sm simpanhubEks mt-1">Save</button>
                			<button class="btn btn-danger btn-sm batalhubEks mt-1">Cancel</button>
                		</div>
                		<div class="col-sm-1 d-flex justify-content-center">
                			<span class="edit-hubEks btn btn-sm btn-circle btn-success" data-toggle="tooltip"
                				data-placement="top" title="Edit"><i class="fas fa-pencil-alt"></i></span>
                		</div>
                	</div>

                	<?php endif; ?>

					<!-- start jumlah staff -->
					<?php if(!empty($staff)): ?>
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
									<input type="text" id="totMgr" class="form-control form-control-sm"
										value="<?= $staff['manager']; ?>">
									<div class="input-group-append">
										<span class="input-group-text" id="basic-addon2">Orang</span>
									</div>
								</div>
							</dd>
							<dd class="col-8"></dd>

							<dt class="col-2">Supervisor</dt>
							<dd class="col-2">
								<div class="input-group input-group-sm mb-3">
									<input type="text" id="totSpvr" class="form-control form-control-sm"
										value="<?= $staff['supervisor']; ?>">
									<div class="input-group-append">
										<span class="input-group-text" id="basic-addon2">Orang</span>
									</div>
								</div>
							</dd>
							<dd class="col-8"></dd>


							<dt class="col-2">Staff</dt>
							<dd class="col-2">
								<div class="input-group input-group-sm mb-3">
									<input type="text" id="totStaf" class="form-control form-control-sm"
										value="<?= $staff['staff']; ?>">
									<div class="input-group-append">
										<span class="input-group-text" id="basic-addon2">Orang</span>
									</div>
								</div>
							</dd>
						</dl>
					<?php else: ?>
					<?php 
						if(empty($this->Jobpro_model->getDetail('*', 'jumlah_staff', array('id_posisi' => $posisi['id'])))){ //cek apa jumlah staff sudah ada
							$this->Jobpro_model->insert('jumlah_staff', array(
								'id_posisi' => $posisi['id'],
								'manager' => 0,
								'supervisor' => 0,
								'staff' => 0
							));
						}
					?>
					
					<div class="row align-items-end mt-5">
						<div class="col">
							<div class="alert alert-danger text-center" role="alert">Harap refresh Halaman ini, tekan <span class="badge badge-dark">F5</span> di keyboard anda.</div>
						</div>
					</div>
					<?php endif; ?>

                	<!-- start tantangan dan maslah utama -->
                	<hr>
                	<div class="row mt-2">
                		<div class="col-11">
                			<h5 class="font-weight-bold">Tantangan Dan Masalah Utama</h5>
                			<h6 class="font-weight-light mt-2"><em>Tantangan yang melekat pada jabatan dan masalah yang
                					sulit/ rumit yang dihadapi dalam kurun waktu cukup panjang :</em></h6>
                		</div>
                		<div class="col-sm-1 d-flex justify-content-center">
                			<?php if(!empty($tu_mu)): ?>
                			<button type="button" class="btn btn-circle btn-sm btn-success edit-tantangan"
                				data-toggle="tooltip" data-placement="top" title="Edit">
                				<i class="fas fa-1x fa-pencil-alt"></i>
                			</button>
                			<?php endif; ?>
                		</div>
                	</div>
                	<?php if (empty($tu_mu)) : ?>
                	<div class="row">
                		<div class="col-12">
                			<!-- <form method="post" action="<?= base_url('job_profile/addtantangan/'); ?>"> -->
                			<div class="form-group">
                				<textarea name="tantangan-baru" id="tantangan-baru"></textarea>
                			</div>
                			<button id="simpan-tantangan_baru" class="btn btn-primary btn-sm"
                				data-id="<?= $posisi['id']; ?>">Save</button>
                			<!-- </form> -->
                		</div>
                	</div>
                	<?php else : ?>
                	<div class="row">
                		<div class="col-12">
                			<div class="view-tantangan">
                				<?= $tu_mu['text']; ?>
                			</div>
                			<div class="editor-tantangan">
                				<textarea name="tantangan" id="tantangan"><?= $tu_mu['text']; ?></textarea>
                				<button id="simpan-tantangan" data-id="<?= $tu_mu['id']; ?>"
                					class="mt-2 btn btn-primary btn-sm">Save</button>
                				<button class="batal-edit-tantangan mt-2 btn btn-danger btn-sm">Batal</button>
                			</div>
                		</div>
                	</div>
                	<?php endif; ?>

                	<!-- start kualifikasi dan pengalaman -->
                	<hr>
                	<div class="row mt-4">
                		<div class="col-11">
                			<h5 class="font-weight-bold">Kualifikasi dan Pengalaman </h5>
                			<h6 class="font-weight-light mt-2"><em>Persyaratan minimum yang harus dipenuhi : pendidikan,
                					lama pengalaman kerja yang relevan, kompetensi (soft dan technical skill), atau
                					kualifikasi personal maupun profesional lainnya :</em></h6>
                		</div>
                		<div class="col-sm-1 d-flex justify-content-center">
                			<?php if(!empty($kualifikasi)): ?>
                			<button type="button" class="btn btn-circle btn-sm btn-success edit-kualifikasi"
                				data-id="<?= $posisi['id']; ?>" data-toggle="modal"
                				data-target="#modalKualifikasi" data-placement="top" title="Edit">
                				<i class="fas fa-1x fa-pencil-alt"></i>
                			</button>
                			<?php endif; ?>
                		</div>
                	</div>
                	<?php if(empty($kualifikasi)) : ?>
                	<div class="row">
                		<div class="col-6 ml-2 mt-2">
                			<!-- <form action="<?= base_url('job_profile/addkualifikasi'); ?>" method="post"> -->
                			<input type="hidden" name="id" value="<?= $posisi['id']; ?>">
                			<div class="form-group">
                				<label for="pend">Pendidikan Formal</label>
                				<textarea class="form-control" name="pend" id="pend" rows="2"></textarea>
                			</div>
                			<div class="form-group">
                				<label for="pengalmn">Pengalaman Kerja</label>
                				<textarea class="form-control" name="pengalmn" id="pengalmn" rows="2"></textarea>
                			</div>
                			<div class="form-group">
                				<label for="pengtahu">Pengetahuan</label>
                				<textarea class="form-control" name="pengtahu" id="pengtahu" rows="2"></textarea>
                			</div>
                			<div class="form-group">
                				<label for="kptnsi">Kompetensi & Keterampilan</label>
                				<textarea class="form-control" name="kptnsi" id="kptnsi" rows="2"></textarea>
                			</div>
                			<button id="simpan-kualifikasi-baru" class="btn btn-primary btn-sm">Save</button>
                			<!-- </form> -->
                		</div>
                	</div>
                	<?php else : ?>
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
                		<div class="col-11">
                			<h5 class="font-weight-bold">Jabatan Berikutnya Di Masa Depan</h5>
                			<h6 class="font-weight-light mt-2"><em>Pergerakan karir yang memungkinkan setelah memegang
                					jabatan ini? (baik yang utama/ primary maupun yang secondary):</em></h6>
                		</div>
                		<div class="col-sm-1 d-flex justify-content-center">
                			<?php if(!empty($jenk)): ?>
                			<button type="button" class="btn btn-circle btn-sm btn-success edit-jenjang"
                				data-toggle="tooltip" data-placement="top" title="Edit">
                				<i class="fas fa-1x fa-pencil-alt"></i>
                			</button>
                			<?php endif; ?>
                		</div>
                	</div>
                	<?php if (empty($jenk)) : ?>
                	<div class="row">
                		<div class="col-12">
                			<!-- <form action="<?= base_url('job_profile/addjenjangkarir'); ?>" method="post"> -->
                			<div class="form-group">
                				<label for="jenkar">Jabatan Di Masa Depan :</label>
                				<textarea class="form-control" name="jenkar" id="jenkar" rows="2"></textarea>
                			</div>
                			<button id="simpan-jenk-baru" class="btn btn-primary btn-sm"
                				data-id="<?= $posisi['id']; ?>">Save</button>
                			<!-- </form> -->
                		</div>
                	</div>
                	<?php else : ?>
                	<div class="row">
                		<div class="col-12">
                			<div class="view-jenjang">
                				<?= $jenk['text']; ?>
                			</div>
                			<div class="editor-jenkar">
                				<textarea name="jenkar" id="jenkar"><?= $jenk['text']; ?></textarea>
                				<button type="submit" class="mt-2 btn btn-primary btn-sm" data-id="<?= $jenk['id']; ?>"
                					id="simpan-jenjang">Save</button>
                				<button class="batal-edit-jenjang mt-2 btn btn-danger btn-sm">Cancel</button>
                			</div>
                		</div>
                	</div>
                	<?php endif; ?>

                	<!-- start Struktur Organisasi -->
                	<?php // if($atasan != 0 && $posisi['id_atasan1'] != 1): ?>
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

<!-- modal tanggung jawab -->
<div class="modal fade" id="modalTanggungJwb" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalTanggungJwbTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
					<input type="hidden" name="idtgjwb" id="idtgjwb">
					<div class="form-group">
						<label for="message-text" class="col-form-label">Tanggung Jawab Utama:</label>
						<textarea class="form-control" id="tJwb-text" name="tanggung_jawab" required="true"></textarea>
					</div>
					<div class="form-group">
						<label for="message-text" class="col-form-label">Aktivitas Utama:</label>
						<textarea class="form-control" id="aUtm-text" name="aktivitas" required="true"></textarea>
					</div>
					<div class="form-group">
						<label for="message-text" class="col-form-label">Pengukuran:</label>
						<textarea class="form-control" id="pgkrn-text" name="pengukuran" required="true"></textarea>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button id="submit-tgjwb" class="btn btn-primary" data-id_posisi="<?= $posisi['id']; ?>">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- modal kualifikasi -->
<div class="modal fade" id="modalKualifikasi">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Ubah Data Kualifikasi dan Pengalaman</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- <form action="<?= base_url('job_profile/updateKualifikasi'); ?>" method="post"> -->
				<input type="hidden" name="id" id="id" value="<?= $posisi['id']; ?>">
				<div class="form-group">
					<label for="pend">Pendidikan Formal</label>
					<textarea class="form-control" name="pend" id="pend" rows="2"></textarea>
				</div>
				<div class="form-group">
					<label for="pengalmn">Pengalaman Kerja</label>
					<textarea class="form-control" name="pengalmn" id="pengalmn" rows="2"></textarea>
				</div>
				<div class="form-group">
					<label for="pengtahu">Pengetahuan</label>
					<textarea class="form-control" name="pengtahu" id="pengtahu" rows="2"></textarea>
				</div>
				<div class="form-group">
					<label for="kptnsi">Kompetensi & Keterampilan</label>
					<textarea class="form-control" name="kptnsi" id="kptnsi" rows="2"></textarea>
				</div>
				<button type="submit" class="btn btn-primary btn-sm" id="save-kualifikasi">Save</button>
				<!-- </form> -->
			</div>
		</div>
	</div>
</div>

<script>
	//variabel untuk validasi form Job Profile
	var valid_tujuanjabatan = '<?php if(empty($tujuanjabatan)){echo("null");}else{echo("fill");} ?>';
	var valid_ruangl = '<?php if(empty($ruangl)){echo("null");}else{echo("fill");} ?>';
	var valid_tu_mu = '<?php if(empty($tu_mu)){echo("null");}else{echo("fill");} ?>';
	var valid_kualifikasi = '<?php if(empty($kualifikasi)){echo("null");}else{echo("fill");} ?>';
	var valid_jenk = '<?php if(empty($jenk)){echo("null");}else{echo("fill");} ?>';
	var valid_hub = '<?php if(empty($hub)){echo("null");}else{echo("fill");} ?>';
	var valid_tgjwb = '<?php if(empty($tgjwb)){echo("null");}else{echo("fill");} ?>';
	var valid_wen = '<?php if(empty($wen)){echo("null");}else{echo("fill");} ?>';
	var valid_atasan = '<?php if(empty($atasan)){echo("null");}else{echo("fill");} ?>';
</script>