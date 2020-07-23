<div class="row">
    <div class="col">
        <div class="card <?php if($this->session->userdata('role_id') == 1): ?>
            card-gray
        <?php endif; ?>">
            <div class="card-header">
                <?php if($this->session->userdata('role_id') == 1): ?>
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label for="daterange">Dates:</label>
                                <div class="input-group" id="daterange">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input type="text" name="daterange" class="form-control" value="" />
                                    <!-- <input type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask=""> -->
                                </div><!-- /.input group -->
                             </div>
                        </div>
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
                                <label for="departement">Department:</label>
                                <select id="departement" class="custom-select form-control form-control-sm">
                                    <option value="">All</option>
                                    <?php foreach($dept as $v): ?>
                                        <option value="dept-<?= $v['id'] ?>"><?= $v['nama_departemen'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-6">
                            <div class="row h-100">
                                <div class="col align-self-center text-right pt-3">
                                    <button id="apply_table" class="btn btn-primary w-100"><i class="fa fa-filter"></i> Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <h3 class="card-title">Your Health Checked In</h3>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-lg-12 col-md-6 col-12">
                                <div><label for="">Health Status Ratio:</label></div>
                                <canvas id='healthRasio' width="400" height="400"></canvas>
                            </div>
                            <div class="col-lg-12 col-md-6 col-12">
                                <div><label for="">Health Category Ratio:</label></div>
                                <canvas id='healthcategoryRasio' width="400" height="575"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <!-- Table Toolbox -->
                        <div class="row mb-3">
                            <div class="col">
                                <div><label for="">Table Filter:</label></div>
                                <div class="btn-group w-100">
                                </div>
                                <div class="form-group">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button id="reset_filter" class="btn btn-danger"><i class="fa fa-sync"></i> Reset</button>
                                        </div>
                                        <!-- /btn-group -->
                                        <select id="healthStatus_filter" class="custom-select">
                                            <option value="">Health Status</option>
                                            <option value="Sick">Sick</option>
                                            <option value="Healthy">Healthy</option>
                                        </select>
                                        <select id="sickCategory_filter" class="custom-select">
                                            <option value="">Sick Categories</option>
                                            <?php foreach($sick_categories as $v): ?>
                                                <option value="<?= $v['name']; ?>"><?= $v['name']; ?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /Table Toolbox -->
                        <div class="row">
                            <div class="col table-responsive">
                                <!-- Tabel -->
                                <table id="report_healthCheckIn" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Division</th>
                                            <th>Health Status</th>
                                            <th>Sick Type</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                </table><!-- /Tabel -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>