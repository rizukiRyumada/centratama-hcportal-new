<div class="row">
    <div class="col-auto my-1">
        <form action="">
            <select class="custom-select mr-sm-2" id="jenis-surat" name="jenis">
                <option value="">All</option>
                <?php 
                $role_id = $this->session->userdata('akses_surat_id');
                $querySurat = "SELECT `jenis_surat`.`id`,`jenis_surat`
                FROM `jenis_surat`
                JOIN `user_access_surat` ON `jenis_surat`.`id` = `user_access_surat`.`surat_id`
                WHERE `user_access_surat`.`role_surat_id` = $role_id";
                $jenis = $this->db->query($querySurat)->result_array();
                ?>
                <?php foreach ($jenis as $j) : ?>
                <option value="<?= $j['id']; ?>"><?= $j['jenis_surat']; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-xl col-lg">
        <div class="card mb-2 shadow-lg">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="tableNomor">
                        <thead>
                            <tr>
                                <th>No. Surat</th>
                                <th>Perihal</th>
                                <th>PIC</th>
                                <th>Tanggal</th>
                                <th>Note</th>
                                <th>Tipe Surat</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>