<div class="row"></div>
    <div class="col">
        <div class="card shadow">
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <p class="text-lg text-bold mb-0">Current Data</p>
                    </div>
                    <hr class="mt-0 mb-1">
                    <p>
                        Table Name: position_2021_01
                        <br />
                        Updated at: 22/11/2021
                    </p>
                    <hr class="mt-0">
                </div>
                <!-- filter position -->
                <div class="row">
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label for="divisi">Divisi</label>
                            <select id="divisi" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <?php foreach ($divisi as $v) : ?>
                                    <option value="div-<?= $v['id'] ?>"><?= $v['division'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label for="departement">Departement</label>
                            <select id="departement" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <?php foreach ($dept as $v) : ?>
                                    <option value="dept-<?= $v['id'] ?>"><?= $v['nama_departemen'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md">
                        <div class="form-group">
                            <label for="dataHistory">Data History</label>
                            <select id="dataHistory" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 text-center mb-3 mb-sm-0">
                        <div class="container d-flex h-100 m-0 px-auto">
                            <!-- this container make the element to vertically and horizontally centered -->
                            <div class="row justify-content-center align-self-center w-100 m-0">
                                <div class="col">
                                    <a type="button" class="btn btn-warning w-100" data-placement="left" title="Update Position Data" data-toggle="modal" data-target="#modalUpdatePosition"><i class="fas fa-sync-alt"></i> Update</a>
                                </div>
                                <div class="col-auto">
                                    <a type="button" class="btn btn-success" data-placement="left" title="Add New Position" data-toggle="modal" data-target="#modal_tambah_posisi"><i class="fas fa-user-plus text-white"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- tampilan tabel -->
                <div class="row">
                    <div class="col">
                        <table id="positionTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Division</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Hirarki</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modals -->
<?php
foreach ([
    '_komponen/settings/modals/position_modal_add.php',
    '_komponen/settings/modals/position_modal_edit.php',
    '_komponen/settings/modals/position_modal_update.php'
] as $v) {
    $this->load->view($v);
}
?>

<!-- hidden inputs -->
<input type="hidden" name="url_table" value="<?= base_url('settings/ ajax_getDataPosition'); ?>">
<input type="hidden" name="url_ajaxDepartment" value="<?php echo base_url('settings/ajax_getDepartment'); ?>">
<input type="hidden" name="url_ajaxPositionDetail" value=" <?= base_url('settings/ajax_getDetailPosition'); ?>">