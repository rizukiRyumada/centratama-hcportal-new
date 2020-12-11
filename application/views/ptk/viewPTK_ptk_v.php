<!-- banner -->
<!-- <div class="row mb-3 pl-2 px-3">
    <div class="col-md-2 d-md-inline-block d-none">
        <div class="row h-100">
            <div class="col align-self-center p-0">
                <img src="<?= base_url('/assets/img/illustration/writing.svg'); ?>" alt="" class="responsive-image">
            </div>
        </div>
    </div>
    <div class="col-md-10">
        <div class="row h-100">
            <div class="col align-self-center p-lg-4 p-md-3 p-sm-2 p-1"> -->
                <!-- <p class="text m-0"></p> -->
                <!-- <ul>
                    <li>Employee Requisition Form should be received by Human Capital minimum 45 days before the required date</li>
                    <li></li>
                </ul>
            </div>
        </div>
    </div>
</div> -->

<div class="row">
    <div class="col">
        <?php echo validation_errors(); ?>
    </div>
</div>

<!-- Main View -->
<div class="row">
    <div class="col">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="overlay"><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div>
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item" id="tab_timeline">
                        <a class="nav-link" href="<?= base_url('ptk'); ?>" aria-selected="false"><i class="fas fa-arrow-left"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-ptkForm-tab" data-toggle="pill" href="#custom-tabs-ptkForm" role="tab" aria-controls="custom-tabs-ptkForm" aria-selected="true"><i class="fa fa-file-alt"></i> Form</a>
                    </li>
                    <li class="nav-item" id="tab_jobProfile" style="display: none;">
                        <a class="nav-link" id="custom-tabs-jobProfile-tab" data-toggle="pill" href="#custom-tabs-jobProfile" role="tab" aria-controls="custom-tabs-jobProfile" aria-selected="false"><i class="fa fa-briefcase"></i> Job Profile</a>
                    </li>
                    <li class="nav-item" id="tab_orgChart" style="display: none;">
                        <a class="nav-link" id="custom-tabs-orgchart-tab" data-toggle="pill" href="#custom-tabs-orgchart" role="tab" aria-controls="custom-tabs-orgchart" aria-selected="false"><i class="fa fa-sitemap"></i> Organization Chart</a>
                    </li>
                    <li class="nav-item" id="tab_attachment">
                        <a class="nav-link" id="custom-tabs-attachment-tab" data-toggle="pill" href="#custom-tabs-attachment" role="tab" aria-controls="custom-tabs-attachment" aria-selected="false"><i class="fa fa-paperclip"></i> Attachment</a>
                    </li>
                    <li class="nav-item" id="tab_timeline">
                        <a class="nav-link" id="custom-tabs-timeline-tab" data-toggle="pill" href="#custom-tabs-timeline" role="tab" aria-controls="custom-tabs-timeline" aria-selected="false"><i class="fa fa-history"></i> Approval Logs</a>
                    </li>
                    <li class="nav-item" id="tab_timeline">
                        <a class="nav-link" href="<?= base_url('ptk/printPTK'); ?>?id_entity=<?= $id_entity; ?>&id_div=<?= $id_div; ?>&id_dept=<?= $id_dept; ?>&id_pos=<?= $id_pos; ?>&id_time=<?= $id_time; ?>" aria-selected="false"><i class="fa fa-print"></i> Print</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <!-- Tab Form PTK -->
                    <div class="tab-pane fade active show" id="custom-tabs-ptkForm" role="tabpanel" aria-labelledby="custom-tabs-ptkForm-tab">
                        <!-- jika statusnya draft dan revised tampilkan editor -->
                        <?php if($is_edit == 1): ?>
                            <?php $this->load->view('ptk/ptk_editor_v'); ?>
                        <?php else: ?>
                            <?php $this->load->view('ptk/ptk_viewer_v'); ?>
                        <?php endif; ?>

                        <!-- buttons -->
                        <div class="row justify-content-end">
                            <!-- buat CEO -->
                            <?php if($position_my['id'] == 1): ?>
                                <?php if($is_edit == 1): ?>
                                    <div class="col-md-6">
                                        <div class="btn-group w-100">
                                            <button class="submitPTK btn btn-lg btn-success w-100" data-status="<?= $status_form; ?>" data-id="1">
                                                <i class="fa fa-check"></i> Approve
                                            </button>
                                            <button class="submitPTK btn btn-lg btn-warning w-100" data-status="<?= $status_form; ?>" data-id="2">
                                                <i class="fas fa-edit"></i> Revise
                                            </button>
                                            <button class="submitPTK btn btn-lg btn-danger w-100" data-status="<?= $status_form; ?>" data-id="0">
                                                <i class="fa fa-times"></i> Reject
                                            </button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col">
                                        <div class="alert alert-<?= $status_detail['css_color']; ?>">
                                            <h5><i class="icon fas fa-exclamation-triangle"></i><?= $status_detail['status_text']; ?></h5>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <!-- buat Divisi HC -->
                            <?php elseif($position_my['id'] == 196): ?>
                                <?php if($is_edit == 1): ?>
                                    <div class="col-md-6">
                                        <div class="btn-group w-100">
                                            <button class="submitPTK btn btn-lg btn-success w-100" data-status="<?= $status_form; ?>" data-id="1">
                                                <i class="fa fa-check"></i> Approve
                                            </button>
                                            <button class="submitPTK btn btn-lg btn-warning w-100" data-status="<?= $status_form; ?>" data-id="2">
                                                <i class="fas fa-edit"></i> Revise
                                            </button>
                                            <button class="submitPTK btn btn-lg btn-danger w-100" data-status="<?= $status_form; ?>" data-id="0">
                                                <i class="fa fa-times"></i> Reject
                                            </button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col">
                                        <div class="alert alert-<?= $status_detail['css_color']; ?>">
                                            <h5><i class="icon fas fa-exclamation-triangle"></i><?= $status_detail['status_text']; ?></h5>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <!-- buat Admin -->
                            <?php elseif($userApp_admin == 1 || $this->session->userdata('role_id') == 1): ?>
                                <?php if($status_form == "ptk_stats-3"): ?>
                                    <div class="col-md-6">
                                        <div class="btn-group w-100">
                                            <button class="submitPTK btn btn-lg btn-success w-100" data-status="<?= $status_form; ?>" data-id="1">
                                                <i class="fa fa-check"></i> Approve
                                            </button>
                                            <button class="submitPTK btn btn-lg btn-warning w-100" data-status="<?= $status_form; ?>" data-id="2">
                                                <i class="fas fa-edit"></i> Revise
                                            </button>
                                            <button class="submitPTK btn btn-lg btn-danger w-100" data-status="<?= $status_form; ?>" data-id="0">
                                                <i class="fa fa-times"></i> Reject
                                            </button>
                                        </div>
                                    </div>
                                <?php elseif($status_form == "ptk_stats-1" || $status_form == "ptk_stats-C" || $status_form == "ptk_stats-D" || $status_form == "ptk_stats-E" || $status_form == "ptk_stats-F"): ?>
                                    <div class="col-md-6">
                                        <div class="btn-group w-100">
                                            <button class="submitPTK btn btn-lg btn-success w-100" data-status="<?= $status_form; ?>" data-id="1">
                                                <i class="fa fa-paper-plane"></i> Submit
                                            </button>
                                            <button class="submitPTK btn btn-lg btn-warning w-100" data-status="<?= $status_form; ?>" data-id="3">
                                                <i class="fas fa-save"></i> Save
                                            </button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col">
                                        <div class="alert alert-<?= $status_detail['css_color']; ?>">
                                            <h5><i class="icon fas fa-exclamation-triangle"></i><?= $status_detail['status_text']; ?></h5>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <!-- buat hirarki N -->
                            <?php elseif($position_my['hirarki_org'] == "N"): ?>
                                <?php if($is_edit == 1): ?>
                                    <div class="col-md-6">
                                        <div class="btn-group w-100">
                                            <button class="submitPTK btn btn-lg btn-success w-100" data-status="<?= $status_form; ?>" data-id="1">
                                                <i class="fa fa-check"></i> Approve
                                            </button>
                                            <button class="submitPTK btn btn-lg btn-warning w-100" data-status="<?= $status_form; ?>" data-id="2">
                                                <i class="fas fa-edit"></i> Revise
                                            </button>
                                            <button class="submitPTK btn btn-lg btn-danger w-100" data-status="<?= $status_form; ?>" data-id="0">
                                                <i class="fa fa-times"></i> Reject
                                            </button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col">
                                        <div class="alert alert-<?= $status_detail['css_color']; ?>">
                                            <h5><i class="icon fas fa-exclamation-triangle"></i><?= $status_detail['status_text']; ?></h5>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <!-- buat hirarki N-1 -->
                            <?php elseif($position_my['hirarki_org'] == "N-1"): ?>
                                <?php if($is_edit == 1): ?>
                                    <div class="col-md-6">
                                        <div class="btn-group w-100">
                                            <button class="submitPTK btn btn-lg btn-success w-100" data-status="<?= $status_form; ?>" data-id="1">
                                                <i class="fa fa-paper-plane"></i> Submit
                                            </button>
                                            <button class="submitPTK btn btn-lg btn-warning w-100" data-status="<?= $status_form; ?>" data-id="3">
                                                <i class="fas fa-save"></i> Save
                                            </button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col">
                                        <div class="alert alert-<?= $status_detail['css_color']; ?>">
                                            <h5><i class="icon fas fa-exclamation-triangle"></i><?= $status_detail['status_text']; ?></h5>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <!-- buat hirarki N-2 -->
                            <?php else: ?>
                                <?php if($is_edit == 1): ?>
                                    <div class="col-md-6">
                                        <div class="btn-group w-100">
                                            <button class="submitPTK btn btn-lg btn-warning w-100" data-status="<?= $status_form; ?>" data-id="3">
                                                <i class="fas fa-save"></i> Save
                                            </button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col">
                                        <div class="alert alert-<?= $status_detail['css_color']; ?>">
                                            <h5><i class="icon fas fa-exclamation-triangle"></i><?= $status_detail['status_text']; ?></h5>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div><!-- /Tab form PTK -->
                    
                    <!-- /* -------------------------------------------------------------------------- */
                    /*                                Tab form Job Profile                             */
                    /* ------------------------------------------------------------------------------- */ -->
                    <!-- Tab form Job Profile -->
                    <div class="tab-pane fade" id="custom-tabs-jobProfile" role="tabpanel" aria-labelledby="custom-tabs-jobProfile-tab">
                        <?php $this->load->view('ptk/ptk_jobprofile_viewer_v'); ?>
                    </div><!-- /Tab form Job Profile -->

                    <!-- Tab form Organization Chart -->
                    <div class="tab-pane fade" id="custom-tabs-orgchart" role="tabpanel" aria-labelledby="custom-tabs-orgchart-tab">
                        <?php $this->load->view('ptk/ptk_jobprofile_orgchart_v'); ?>
                    </div><!-- /Tab form Organization Chart -->

                    <!-- Tab form Organization Chart -->
                    <?php $this->load->view('ptk/attachment_tab_ptk_v') ?>

                    <!-- Tab form Status History -->
                    <div class="tab-pane fade" id="custom-tabs-timeline" role="tabpanel" aria-labelledby="custom-tabs-timeline-tab">
                        <div class="row">
                            <div class="col-12">
                                <div id="overlay_statusHistory" class="overlay" ></div>
                                <div class="timeline">
                                    <!-- timeline data -->
                                </div>
                            </div>
                        </div>
                    </div><!-- /Tab form Status History -->
                </div>
            </div><!-- /.card -->
        </div>
    </div>
</div>

<script>
    var id_entity = "<?= $id_entity; ?>";
    var id_div    = "<?= $id_div; ?>";
    var id_dept   = "<?= $id_dept; ?>";
    var id_pos    = "<?= $id_pos; ?>";
    var id_time   = "<?= $id_time; ?>";
</script>

<!-- modal pesan revisi -->
<?php $this->load->view('ptk/modalPesan_ptk_v') ?>