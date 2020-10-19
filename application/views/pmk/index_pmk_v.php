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
<div class="row mb-3">
    <!-- <div class="col-md-2 d-md-inline-block d-none">
        <img src="http://localhost:82/assets/img/illustration/contract.svg" alt="" class="responsive-image">
    </div> -->
    <div class="col-md-2 d-md-inline-block d-none">
        <img src="http://localhost:82/assets/img/illustration/contract.svg" alt="" class="responsive-image">
    </div>
    <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
        <div class="col-md-3 col-12 align-self-center">
            <div class="card">
                <div class="card-body p-2">
                    <dl class="row m-0">
                        <dt class="col-10 align-self-center">End of Contract</dt>
                        <dd id="eoc" class="col-2 align-self-center m-0 text-center"><i class="fas fa-question-circle text-danger"></i></dd>
                        <dt class="col-10 align-self-center">Active</dt>
                        <dd id="act" class="col-2 align-self-center m-0 text-center"><i class="fas fa-question-circle text-danger"></i></dd>
                        <dt class="col-10 align-self-center">Completed</dt>
                        <dd id="cpt" class="col-2 align-self-center m-0 text-center"><i class="fas fa-question-circle text-danger"></i></dd>
                    </dl>
                    <div class="row mt-1">
                        <div class="col">
                            <button id="buttonRefreshPMK" class="btn btn-danger w-100"><i id="iconRefreshPMK" class="fa fa-sync"></i> Refresh</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="
    <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
        col-md-7
    <?php else: ?>
        col-md-10    
    <?php endif; ?>
    ">
        <div class="row h-100">
            <div class="col align-self-center">
                <p class="text m-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit, rem amet, ut quia necessitatibus vel, obcaecati maiores natus doloribus aliquid rerum voluptates saepe. Enim commodi, nesciunt laudantium deserunt veniam quod?</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card card-primary card-outline card-outline-tabs">
                        <div id="overlay_statusHistory" class="overlay" ></div>
            <div class="overlay"><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div>
            <?php if(!empty($summary)): ?>
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <!-- TODO tambahkan if jika dia atasan HC atau bukan buat aktifin mana dulu -->
                        <li class="nav-item">
                            <a class="nav-link 
                                <?php if($position_my['id'] != 1): ?>
                                    active
                                <?php endif; ?>
                            " id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false"><i class="fas fa-file-alt"></i> Assessment</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link 
                                <?php if($position_my['id'] == 1): ?>
                                    active
                                <?php endif; ?>
                            " id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="true"><i class="fas fa-file-signature"></i> Summary</a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <!-- Tabel assessment -->
                    <?php $flag_filter = 0; // buat penanda apa ada item tool buat filter ?>
                    <div class="tab-pane fade 
                            <?php if($position_my['id'] != 1): ?>
                                active show
                            <?php endif; ?>
                        " id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <?php if(($position_my['hirarki_org'] == "N" || $position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2") && $position_my['id'] != 1): ?>    
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
                        <div id="filterTools" class="row justify-content-end
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
                                            <option value="">Please choose division first</option>        
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div id="statusChooser" class="col-lg-4 col-sm-6" 
                                <?php if(($position_my['hirarki_org'] == "N" || $position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2") && $position_my['id'] != 1): ?>
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
                                <?php if(($position_my['hirarki_org'] == "N" || $position_my['hirarki_org'] == "N-1" || $position_my['hirarki_org'] == "N-2") && $position_my['id'] != 1): ?>
                                    style="display: none;"
                                <?php endif; ?>
                                >
                                <div class="form-group">
                                    <label for="daterange">Pick a daterange:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <input class="form-control" type="text" name="dateChooser" id="daterange">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="buttonResetFilter" class="row justify-content-end">
                            <div class="col-sm-2">
                                <button id="resetFilterAsses" class="btn btn-danger w-100"><i class="fa fa-filter fa-rotate-180"></i> Reset</button>
                            </div>
                        </div><!-- /filter table -->

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
                                <?php if($position_my['id'] == 1): ?>
                                    active show
                                <?php endif; ?>
                            " id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                            <div class="row d-flex align-items-stretch">
                                <?php foreach($divisi as $v): ?>
                                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                                        <a href="<?= base_url('pmk/summary'); ?>?div=<?= $v['id']; ?>" class="card bg-light w-100">
                                            <!-- <div class="card-header text-muted border-bottom-0">
                                                Digital Strategist
                                            </div> -->
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="lead"><b><?= $v['division']; ?></b></h2>
                                                        <p class="text-sm badge badge-<?= $v['color']; ?>"><b>Need Attention: </b> <?= $v['count_summary']; ?> </p>
                                                        <p class="text-muted text-sm m-0"><b>Employees: </b> <?= $v['emp_total']; ?> </p>
                                                        <p class="text-muted text-sm m-0"><b>Divhead: </b> <?= $v['emp_name']; ?> </p>
                                                    </div>
                                                    <div class="col-5 text-center align-self-center">
                                                        <!-- <img src="../../dist/img/user1-128x128.jpg" alt="" class="img-circle img-fluid"> -->
                                                        <i class="fas fa-users fa-5x text-<?= $v['color']; ?>"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </div> <!-- /Tabel Summary -->
                    <?php endif; ?>
                </div>
            </div><!-- /.card -->
        </div>
    </div>
</div>

<!-- Modal Status History Viewer -->
<div class="modal fade" id="statusViewer" tabindex="-1" aria-labelledby="statusViewerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusViewerLabel">Status Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="overlay_statusHistory" class="overlay" ></div>
                        <div class="timeline">
                            <!-- timeline data -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- /Modal Status History Viewer -->

<script>
    var flag_filter = <?= $flag_filter; ?>;
</script>