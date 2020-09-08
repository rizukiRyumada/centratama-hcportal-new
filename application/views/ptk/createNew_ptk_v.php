<!-- banner -->
<div class="row mb-3 pl-2 px-3">
    <div class="col-md-2 d-md-inline-block d-none">
        <div class="row h-100">
            <div class="col align-self-center p-0">
                <img src="<?= base_url('/assets/img/illustration/writing.svg'); ?>" alt="" class="responsive-image">
            </div>
        </div>
    </div>
    <div class="col-md-10">
        <div class="row h-100">
            <div class="col align-self-center p-lg-4 p-md-3 p-sm-2 p-1">
                <!-- <p class="text m-0"></p> -->
                <ul>
                    <li>Employee Requisition Form should be received by Human Capital minimum 45 days before the required date</li>
                    <li></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <?php echo validation_errors(); ?>
    </div>
</div>

<!-- Main View -->
<div class="row">
    <div class="col">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-ptkForm-tab" data-toggle="pill" href="#custom-tabs-ptkForm" role="tab" aria-controls="custom-tabs-ptkForm" aria-selected="true">Form</a>
                    </li>
                    <li class="nav-item" id="tab_jobProfile" style="display: none;">
                        <a class="nav-link" id="custom-tabs-jobProfile-tab" data-toggle="pill" href="#custom-tabs-jobProfile" role="tab" aria-controls="custom-tabs-jobProfile" aria-selected="false">Job Profile</a>
                    </li>
                    <li class="nav-item" id="tab_orgChart" style="display: none;">
                        <a class="nav-link" id="custom-tabs-orgchart-tab" data-toggle="pill" href="#custom-tabs-orgchart" role="tab" aria-controls="custom-tabs-orgchart" aria-selected="false">Organization Chart</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <!-- Tab Form PTK -->
                    <div class="tab-pane fade active show" id="custom-tabs-ptkForm" role="tabpanel" aria-labelledby="custom-tabs-ptkForm-tab">
                        <?php $this->load->view('ptk/ptk_editor_v'); ?>

                        <!-- Buttons -->
                        <div class="row px-3 justify-content-end">
                            <div class="col-md-6 text-right">
                                <div class="btn-group w-100">
                                    <button class="submitPTK btn btn-lg btn-warning w-100" data-id="save">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                    <?php if($position_my['hirarki_org'] == "N-1"): ?>
                                        <button class="submitPTK btn btn-lg btn-success w-100" data-id="submit">
                                            <i class="fa fa-paper-plane"></i> Submit
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->

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
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>