<div class="row">
    <div class="col">
        <div class="card <?php if($this->session->userdata('role_id') == 1): ?>
            card-gray
        <?php endif; ?>">
            <div class="card-header">
                <?php if($this->session->userdata('role_id') == 1): ?>
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="divisi">Division:</label>
                                <select id="divisi" class="custom-select form-control form-control-sm">
                                    <option value="">All</option>
                                    <?php foreach($divisi as $v): ?>
                                        <option value="div-<?= $v['id'] ?>"><?= $v['division'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="departement">Departement:</label>
                                <select id="departement" class="custom-select form-control form-control-sm">
                                    <option value="">All</option>
                                    <?php foreach($dept as $v): ?>
                                        <option value="dept-<?= $v['id'] ?>"><?= $v['nama_departemen'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="status">Employee:</label>
                                <select id="status" class="custom-select form-control form-control-sm">
                                    <option value="">All</option>
                                    <option value="1">Not Yet Submitted</option>
                                    <option value="2">Submitted</option>
                                    <option value="3">First Approval</option>
                                    <option value="4">Need Revised</option>
                                    <option value="5">Final Approval</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <h3 class="card-title">Your Health Checked In</h3>
                <?php endif; ?>
            </div>
            <div class="card-body">
                ajhbekjwhqfew
            </div>
        </div>
    </div>
</div>