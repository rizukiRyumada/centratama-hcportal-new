<!-- banner -->
<div class="row mb-3">
    <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1 || $my_hirarki == "N-1" || $my_hirarki == "N-2"): ?>
        <div class="col-md-4 py-2">
            <div class="row h-100">
                <div class="col align-self-center text-center">
                    <a href="<?= base_url('ptk/createNewForm'); ?>" class="w-100 btn btn-success">
                        <div class="row h-100">
                            <div class="col-auto align-self-center text-center">
                                <img src="<?= base_url('/assets/img/illustration/ptk/thingking-new-employee.svg'); ?>" alt="add-document" class="img-md">
                                <!-- <img src="<?= base_url('/assets/img/illustration/add-document.svg'); ?>" alt="add-document" class="img-lg"> -->
                            </div>
                            <div class="col align-self-center text-center">
                                <p class="text m-0">Create New Employee Requisition Form</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="
    <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1 || $my_hirarki == "N-1" || $my_hirarki == "N-2"): ?>
        col-md-6
    <?php else: ?>
        col-md-10
    <?php endif; ?>
    ">
        <div class="row h-100">
            <div class="col align-self-center">
                <!-- <p class="text m-0">Employee Requisition Form digunakan untuk mengajukan tenaga kerja baru, dengan melalui beberapa tahap approval.</p> -->
            </div>
        </div>
    </div>
    <!-- <div class="col-md-2 d-md-inline-block d-none">
        <img src="<?= base_url('/assets/img/illustration/ptk/thingking-new-employee.svg'); ?>" alt="" class="responsive-image">
    </div> -->
</div>

<!-- main tabs -->
<div class="row">
    <div class="col">
        <div class="card card-primary card-tabs">
            <div class="overlay"><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div>
            <div class="card-header px-0 pb-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                  <?php if(!empty($mytask)): ?>
                    <li class="nav-item">
                        <a class="nav-link ptk_tableTrigger active" id="custom-tabs-four-myTask-tab" data-toggle="pill" data-status="4" href="#custom-tabs-four-myTask" role="tab" aria-controls="custom-tabs-four-myTask" aria-selected="true">My Task</a>
                    </li>
                  <?php endif; ?>
                  <li class="nav-item">
                    <a class="nav-link ptk_tableTrigger 
                    <?php if(empty($mytask)): ?>
                        active
                    <?php endif; ?>
                    " id="custom-tabs-four-home-tab" data-toggle="pill" data-status="1" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false">History</a>
                  </li>
                  <!-- <li class="nav-item">
                    <a class="nav-link ptk_tableTrigger" id="custom-tabs-four-profile-tab" data-toggle="pill" data-status="0" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Inactive</a>
                  </li> -->
                  <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link exportPTK" id="custom-tabs-four-messages-tab" href="<?= base_url('ptk/exportHistory'); ?>" aria-selected="false"><i class="fas fa-file-excel"></i> Export Data</a>
                    </li>
                  <?php endif; ?>
                </ul>
            </div>
            <div class="card-body table-responsive">
                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="statusPtk">Status :</label>
                            <select id="statusPtk" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                            </select>
                        </div>
                    </div>
                </div>
                <table id="table_indexPTK" class="table table-striped">
                    <thead class="text-center">
                        <tr>
                            <th>Date</th>
                            <th>Division</th>
                            <th>Department</th>
                            <!-- <th>Subject</th> -->
                            <th>Status</th>
                            <th style="width: 98px;">View Details</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
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