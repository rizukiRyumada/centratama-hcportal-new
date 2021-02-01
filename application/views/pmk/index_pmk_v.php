<!-- <div class="row">
    <div class="col">
        <div class="card card-danger">
            <div class="card-header">
                <h5 class="m-0"><i class="fas fa-user-shield"></i>Admin Panel</h5>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-md-2 d-md-inline-block d-none mt-3">
                        <img src="<?= base_url('/assets/img/illustration/contract.svg'); ?>" alt="pmk illustration" class="responsive-image">
                    </div>
                    <div class="col-md-7 col-12 align-self-center mt-3">
                        <dl class="row m-0">
                            <dt class="col-sm-4">End of Contract</dt>
                            <dd class="col-sm-8">44</dd>
                            <dt class="col-sm-4">Active</dt>
                            <dd class="col-sm-8">33</dd>
                            <dt class="col-sm-4">Completed</dt>
                            <dd class="col-sm-8">2</dd>
                        </dl>
                    </div>
                    <div class="col-lg-5 col-12 align-self-center mt-3">
                        <button id="cekKontrak" class="w-100 btn btn-warning">
                            <div class="row h-100">
                                <div class="col-auto align-self-center text-center">
                                    <img src="<?= base_url('/assets/img/illustration/contract.svg'); ?>" alt="add-document" class="img-lg">
                                </div>
                                <div class="col align-self-center text-center">
                                    <p class="text m-0">Check for employee who have 2 months before the End of Contract Date</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
    <div class="row mb-3">
        <!--<div class="col-md-2 d-md-inline-block d-none">
            <img src="http://localhost:82/assets/img/illustration/contract.svg" alt="" class="responsive-image">
        </div> -->
        <!-- <div class="col-md-2 d-md-inline-block d-none">
            <img src="http://localhost:82/assets/img/illustration/contract.svg" alt="" class="responsive-image">
        </div> -->
            <div class="col-md-3 col-12 align-self-center">
                <div class="card">
                    <div class="card-body p-2">
                        <dl class="row m-0">
                            <dt class="col-8 align-self-center">End of Contract</dt>
                            <dd id="eoc" class="col-4 align-self-center m-0 text-right"><i class="fas fa-question-circle text-danger"></i></dd>
                            <dt class="col-8 align-self-center">Active</dt>
                            <dd id="act" class="col-4 align-self-center m-0 text-right"><i class="fas fa-question-circle text-danger"></i></dd>
                            <dt class="col-8 align-self-center">Completed</dt>
                            <dd id="cpt" class="col-4 align-self-center m-0 text-right"><i class="fas fa-question-circle text-danger"></i></dd>
                        </dl>
                        <div class="row mt-1">
                            <div class="col">
                                <button id="buttonRefreshPMK" class="btn btn-danger w-100"><i id="iconRefreshPMK" class="fa fa-sync"></i> Refresh</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- <div class="col-md-7">
            <div class="row h-100">
                <div class="col align-self-center">
                    <p class="text m-0">The card on the left is used for refresh the near-to-end employee contract, please click the button at least once a month to keep on track the employee contract.</p>
                </div>
            </div>
        </div> -->
    </div>
<?php endif; ?>

<div class="row">
    <div class="col">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="overlay"><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div>
            <?php if(!empty($summary)): ?>
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <!-- TODO tambahkan if jika dia atasan HC atau bukan buat aktifin mana dulu -->
                        <li class="nav-item">
                            <a class="nav-link 
                                <?php if(!empty($redirect_summary)): ?>
                                    <?php // biarkan kosong ?>
                                <?php else: ?>
                                    active
                                <?php endif; ?>
                            " id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false"><i class="fas fa-file-alt"></i> Assessment</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link 
                                <?php if(!empty($redirect_summary)): ?>
                                    active
                                <?php else: ?>
                                    <?php // biarkan kosong ?>
                                <?php endif; ?>
                            " id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="true"><i class="fas fa-file-signature"></i> Approval</a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <!-- Tabel assessment -->
                    <?php $flag_filter = 0; // buat penanda apa ada item tool buat filter ?>
                    <div class="tab-pane fade 
                            <?php if(!empty($redirect_summary)): ?>
                                <?php // biarkan kosong ?>
                            <?php else: ?>
                                active show
                            <?php endif; ?>
                        " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <?php if(($position_my['hirarki_org'] == "N" || $position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2")): ?>    
                            <!-- data view chooser -->
                            <div class="row mb-2">
                                <div class="col bg-light py-2">
                                    <div class="row">
                                        <div class="col">
                                            <label>Choose data to view:</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <ul class="nav nav-pills ml-auto p-2">
                                                <li class="nav-item"><a id="chooserData1" class="nav-link active" href="javascript:void(0)" data-choosewhat="0"><i class="fas fa-clipboard-list"></i> My Task</a></li>
                                                <li class="nav-item"><a id="chooserData2" class="nav-link" href="javascript:void(0)" data-choosewhat="1"><i class="fas fa-history"></i> History</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- filter table -->
                        <div id="filterTools" class="row
                            <?php if($this->session->userdata('role_id') == 1): ?>
                               <?php $flag_filter++; // tandai filter flag buat munculin tombol apa dia ada filter toolsnya ?>
                            <?php endif; ?>
                            ">
                            <?php if($position_my['id'] == "1" || $position_my['id'] == "196" || $this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
                                <?php $flag_filter++; // tandai filter flag buat munculin tombol apa dia ada filter toolsnya ?>
                                <div id="division_wrapper" class="col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="divisi">Division:</label>
                                        <select id="divisi" class="custom-select form-control form-control-sm">
                                            <option value="">All</option>
                                            <?php foreach($filter_divisi as $v): ?>
                                                <option value="div-<?= $v['id']; ?>"><?= $v['division']; ?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($position_my['hirarki_org'] == "N" || $this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
                                <?php $flag_filter++; // tandai filter flag buat munculin tombol apa dia ada filter toolsnya ?>
                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="departemen">Department:</label>
                                        <select id="departemen" class="custom-select form-control form-control-sm">
                                            <?php if($position_my['hirarki_org'] == "N" && $position_my['id'] != 1 && $position_my['id'] != 196): ?>
                                                <option value="">All Department</option>
                                                <?php foreach($department as $vya): ?>
                                                    <option value="dept-<?= $vya['id']; ?>"><?= $vya['nama_departemen']; ?></option>
                                                <?php endforeach;?>
                                            <?php else: ?>
                                            <option value="">Please choose division first</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div id="statusChooser" class="col-lg-4 col-sm-6" 
                                <?php if(($position_my['hirarki_org'] == "N" || $position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2")): ?>
                                    style="display: none;"
                                <?php endif; ?>
                                >
                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select id="status" class="custom-select form-control form-control-sm">
                                        <option value="">All</option>
                                        <?php foreach($pmk_status as $v): ?>
                                            <option value="<?= $v['id_status']; ?>"><?= $v['name_text']; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                            <div id="daterangeChooser" class="col-lg-4 col-sm-6" 
                                <?php if(($position_my['hirarki_org'] == "N" || $position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2")): ?>
                                    style="display: none;"
                                <?php endif; ?>
                                >
                                <div class="form-group">
                                    <label for="daterange">Pick a daterange:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input id="daterange" class="form-control daterange-chooser" type="text" name="dateChooser" value="<?= date('m/01/Y', strtotime("-2 month", time())) ?> - <?= date('m/t/Y', strtotime("+2 month", time())); ?>">
                                    </div>
                                </div>
                            </div>
                            <!-- <div id="daterangeChooser" class="col-lg-4 col-sm-6" 
                                <?php if(($position_my['hirarki_org'] == "N" || $position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2") && $position_my['id'] != 1): ?>
                                <?php endif; ?>
                                >
                                <div class="form-group">
                                    <label for="daterange">Pick a daterange:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input id="superdatepicker" class="form-control " type="text" name="sdvvd" value="<?= date('m/01/Y', strtotime("-2 month", time())) ?> - <?= date('m/t/Y', strtotime("+2 month", time())); ?>">
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <!-- <div id="buttonResetFilter" class="row">
                            <div class="col-sm-2">
                                <button id="resetFilterAsses" class="btn btn-danger w-100"><i class="fa fa-filter fa-rotate-180"></i> Reset</button>
                            </div>
                        </div> -->
                        <!-- /filter table -->

                        <hr/>

                        <div class="table-responsive">
                            <!-- tabel index pmk -->
                            <table id="table_indexPMK" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Division</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Employee Name</th>
                                        <th>End of Contract</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table><!-- /tabel index pmk -->
                        </div>
                    </div> <!-- /Tabel assessment -->

                    <?php if(!empty($summary)): ?>
                        <!-- Tabel Summary -->
                        <div class="tab-pane fade
                                <?php if(!empty($redirect_summary)): ?>
                                    active show
                                <?php else: ?>
                                    <?php // biarkan kosong ?>
                                <?php endif; ?>
                            " id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                            <div class="row mb-2">
                                <div class="col bg-light py-2">
                                    <div class="row">
                                        <div class="col">
                                            <label>Choose data to view:</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <ul class="nav nav-pills ml-auto p-2">
                                                <li class="nav-item"><a id="summary_switchData1" class="switch-data nav-link active" href="javascript:void(0)" data-choosewhat="0"><i class="fas fa-clipboard-list"></i> My Task</a></li>
                                                <li class="nav-item"><a id="summary_switchData2" class="switch-data nav-link" href="javascript:void(0)" data-choosewhat="1"><i class="fas fa-history"></i> History</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- filter table -->
                            <div class="row" >
                                <?php $flag_filterSummary = 0; ?>
                                    <?php if($position_my['id'] == "1" || $position_my['id'] == "196" || $this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
                                        <?php $flag_filter++; $flag_filterSummary++; // tandai filter flag buat munculin tombol apa dia ada filter toolsnya ?>
                                        <div id="division_wrapper" class="col-lg-4 col-sm-6">
                                            <div class="form-group">
                                                <label for="divisiSummary">Division:</label>
                                                <select id="divisiSummary" class="custom-select form-control form-control-sm">
                                                    <option value="">All</option>
                                                    <?php foreach($filter_divisi as $v): ?>
                                                        <option value="div-<?= $v['id']; ?>"><?= $v['division']; ?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <!-- <div class="col-lg-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="departemenSummary">Department:</label>
                                            <select id="departemenSummary" class="custom-select form-control form-control-sm">
                                                <option value="">Please choose division first</option>        
                                            </select>
                                        </div>
                                    </div> -->
                                    <div id="summary_status" class="col-lg-4 col-sm-6" style="display: none;">
                                        <div class="form-group">
                                            <label for="status">Status:</label>
                                            <select id="status" class="custom-select form-control form-control-sm">
                                                <option value="">All</option>
                                                <?php foreach($status_summary as $v): ?>
                                                    <option value="<?= $v['id']; ?>"><?= $v['name_text']; ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="summaryDateChooser" class="col-lg-4 col-sm-6" style="display: none;" >
                                        <div class="form-group">
                                            <label for="daterange">Pick a daterange:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                </div>
                                                <input id="daterange_summary" class="form-control daterange-chooser" type="text" name="dateChooser_summary" value="<?= date('m/01/Y', strtotime("-2 month", time())) ?> - <?= date('m/t/Y', strtotime("+2 month", time())); ?>">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <!-- <div id="summaryButton_resetFilter" class="row" 
                            <?php if($flag_filterSummary == 0): ?>
                                style="display: none;"
                            <?php endif; ?>
                            >
                                <div class="col-sm-2">
                                    <button id="resetFilterAsses" class="btn btn-danger w-100"><i class="fa fa-filter fa-rotate-180"></i> Reset</button>
                                </div>
                            </div> -->
                            <!-- /filter table -->
                            <hr/>
                            <div class="table-responsive">
                                <table id="table_indexSummary" class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Division</th>
                                            <th>Month (Year)</th>
                                            <th>Employee Total</th>
                                            <th>Status</th>
                                            <!-- <th>Created</th>
                                            <th>Modified</th> -->
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div> <!-- /Tabel Summary -->
                    <?php endif; ?>
                </div>
            </div><!-- /.card -->
        </div>
    </div>
</div>

<!-- load tampilan viewer status -->
<?php $this->load->view('pmk/viewer_status') ?>

<script>
    var flag_filter = <?= $flag_filter; ?>;
</script>