<div class="row">
        <div class="col-lg-4">
            <?php if(validation_errors()): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= validation_errors(); ?>
                    </div>
                    <?php endif; ?>

                    <?= $this->session->flashdata('message'); ?>
                    <?php if($this->session->flashdata('flash')) : ?>
                    <div class="col-md-6 mt-2">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Nomor Surat <strong>Success</strong> <?= $this->session->flashdata('flash'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>

            <div class="card mb-2">
                <div class="card-header py-3 d-flex flex-row justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Form Pembuatan Nomor Surat</h6>
                </div>
                <div class="card-body">
                    <form id="suratForm" action="<?= base_url('document'); ?>" method="POST">
                        <div class="form-group">
                            <label for="jenis">Pilih Jenis Surat</label>
                            <select value="<?= set_value('jenis'); ?>" class="custom-select form-control" id="jenis" name="jenis">
                                <option value="">- Jenis Surat -</option>
                                <?php 
                                    $role_id = $this->session->userdata('akses_surat_id');
                                    $querySurat = "SELECT `document_jenis`.`id`,`jenis_surat`
                                                    FROM `document_jenis`
                                                    JOIN `document_access` ON `document_jenis`.`id` = `document_access`.`surat_id`
                                                    WHERE `document_access`.`role_surat_id` = $role_id";
                                    $jenis_surat = $this->db->query($querySurat)->result_array();
                                ?>
                                <?php foreach ($jenis_surat as $j) : ?>
                                <option value="<?= $j['id']; ?>"><?= $j['jenis_surat']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="text" value="<?= $user['emp_name']; ?>" name="pic" hidden>
                        <div class="form-group">
                            <label for="tipe">Pilih Subjenis Surat</label>
                            <select class="custom-select form-control tipe-sub" id="tipe" name="tipe">
                                <option>- Subjenis Surat -</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="entity">Pilih Entity</label>
                            <select class="custom-select form-control" id="entity" name="entity">
                                <option>- Entity Perusahaan -</option>
                                <?php foreach ($entity as $en) : ?>
                                <option value="<?= $en['nama_entity']; ?>"><?= $en['nama_entity']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Nomor Surat</label>
                            <input type="text" name="no"  class="form-control hasil" readonly>
                        </div>
                        <div class="form-group">
                            <label for="perihal">Perihal:</label>
                            <textarea class="form-control" id="perihal" name="perihal"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="perihal">Note: (Optional):</label>
                            <textarea class="form-control" rows="5" id="note" name="note"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                        <button type="reset" class="btn btn-warning"><i class="fa fa-sync-alt"></i> Reset Nomor</button>
                    </form>
                </div>
            </div>
        </div>


        <!-- DASHBOARD -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="basicTable">
                            <thead>
                                <tr>
                                    <th>Nomor Surat</th>
                                    <th>Perihal</th>
                                    <th>Tanggal</th>
                                    <th>Note</th>
                                    <th>Tipe Surat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($no as $n) : ?>
                                    <tr>
                                        <td><?= $n['no_surat']; ?></td>
                                        <td><?= $n['perihal']; ?></td>
                                        <td><?= date("d  F  Y", strtotime($n['tanggal'])); ?></td>
                                        <td><?= $n['note']; ?></td>
                                        <td><?= $n['jenis_surat']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>