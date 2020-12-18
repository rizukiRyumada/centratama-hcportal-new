<!-- TODO tampilkan persen di diagram pie -->
<!-- TODO sick categories tampilkan nama si sakitnya, tapi bisa difilter others -->
<div class="row">
    <div class="col-lg-9">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-gray">
                        <div class="overlay"><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div>
                        <div class="card-header">
                            <div class="row justify-content-start">
                                <div class="col-lg-4 col-sm-6">
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
                            <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
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
                            <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- hidden form to download data -->
                            <form id="downloadForm" action="<?= base_url('healthReport/export2Excel'); ?>" method="post">
                                <input type="hidden" value="" name="daterangeSelected">
                                <input type="hidden" value="" name="divisiSelected">
                                <input type="hidden" value="" name="departemenSelected">
                            </form>
                            <!-- Table Toolbox -->
                            <div><label for="">Table Filter:</label></div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button id="reset_filter" class="btn btn-danger"><i class="fa fa-sync"></i> Reset</button>
                                    </div>
                                    <!-- /btn-group -->
                                    <select id="healthStatus_filter" class="custom-select">
                                        <option value="">Health Status</option>
                                        <option value="Healthy">Healthy</option>
                                        <option value="Unwell">Unwell</option>
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
                        <div class="card-body table-responsive">
                            <!-- Tabel -->
                            <table id="report_healthCheckIn" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Division</th>
                                        <th>Health Status</th>
                                        <th>Sick Category</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                            </table><!-- /Tabel -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1 || $is_divhead == true || $is_depthead == true): ?>
            <!-- Diagram Batang -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card card-primary">
                            <div class="overlay"><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div>

                            <div class="card-header">
                                <p class="card-title">Periods Data</p>
                                <!-- card tools -->
                                <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#periodeModel"><i class="fas fa-expand"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="periodeChart" width="400" height="250">Your browser does not support the canvas element.</canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-lg-3">
        <div class="card card-primary">
            <div id="overlayPie" class="overlay"><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div>

            <div class="card-header">
                <!-- <div class="form-group">
                    <label for="divisi">Periods:</label>
                    <select id="divisi" class="custom-select form-control form-control-sm">
                        <option value="">All</option>
                    </select>
                </div> -->
                <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
                    <div class="form-group">
                        <label for="showOn">Show on:</label>
                        <select id="showOn" class="custom-select form-control form-control-sm">
                            <option value="">Select a Date...</option>
                        </select>
                    </div>
                <?php else: ?>
                    <p class="card-title">Your Health Status (daterange)</p>    
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div><label for="">Health Status Ratio:</label></div>
                        <canvas id='healthRasio' width="400" height="400">Your browser does not support the canvas element.</canvas>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div><label for="">Health Category Ratio:</label></div>
                        <canvas id='healthcategoryRasio' width="400" height="575">Your browser does not support the canvas element.</canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="periodeModel" tabindex="-1" role="dialog" aria-labelledby="periodeModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="periodeModelLabel">Periods Chart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <canvas id="periodeChart_more" width="400" height="185">Your browser does not support the canvas element.</canvas>
            </div>
        </div>
    </div>
</div>