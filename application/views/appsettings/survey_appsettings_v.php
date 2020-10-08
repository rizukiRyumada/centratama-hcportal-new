<!-- /* -------------------------------------------------------------------------- */
     /*                                  MAIN VIEW                                 */
     /* -------------------------------------------------------------------------- */ -->
<div class="row mb-1">
    <div class="col">
        <div class="card">
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-12 mt-3 mb-0">
                        <h5 class="mb-0 font-weight-bold">Survey Period</h5>
                        <p class="font-weight-light mb-0">Set new survey period based on survey type.</p>
                    </div>
                </div>
                <div class="row">
                    <div id="surveyCards_eng" class="bg-blue p-3 col-lg-4 col-md-6 col-12 mt-2 border border-dark">
                        <h5 class="font-weight-bold">Employee Enggagement</h5>

                        <!-- toggle to start or stop period -->
                        <hr/>
                            <p class="toggle-loading text-center"><i class="fa fa-spinner fa-2x fa-spin"></i></p>
                            <div class="toggle-area form-group text-center" style="display: none;">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="toggle_eng" data-survey="1">
                                    <label class="custom-control-label" for="toggle_eng">Start or Stop filling out the Survey</label>
                                </div>
                            </div>
                        <hr/><!-- /toggle to start or stop period -->
                        
                        <ul class="text-decoration-none">
                            <li>Periods: 2x/year (Semester)</li>
                            <li id="statusEng">Status: <i class="fa fa-spinner fa-spin"></i></li>
                        </ul>
                    </div>
                    <div id="surveyCards_exc" class="bg-orange p-3 col-lg-4 col-md-6 col-12 mt-2 border border-dark">
                        <h5 class="font-weight-bold">Service Excellence</h5>
                        
                        <!-- toggle to start or stop period -->
                        <hr/>
                            <p class="toggle-loading text-center"><i class="fa fa-spinner fa-2x fa-spin"></i></p>
                            <div class="toggle-area form-group text-center" style="display: none;">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="toggle_exc" data-survey="0">
                                    <label class="custom-control-label" for="toggle_exc">Start or Stop filling out the Survey</label>
                                </div>
                            </div>
                        <hr/><!-- /toggle to start or stop period -->

                        <ul class="text-decoration-none">
                            <li>Periods: 4x/year (Quartal)</li>
                            <li id="statusExc">Status: <i class="fa fa-spinner fa-spin"></i></li>
                        </ul>
                    </div>
                    <div id="surveyCards_360" class="bg-yellow p-3 col-lg-4 col-md-6 col-12 mt-2 border border-dark">
                        <h5 class="font-weight-bold">360° Feedback</h5>
                        
                        <!-- toggle to start or stop period -->
                        <hr/>
                            <p class="toggle-loading text-center"><i class="fa fa-spinner fa-2x fa-spin"></i></p>
                            <div class="toggle-area form-group text-center" style="display: none;">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="toggle_360" data-survey="2">
                                    <label class="custom-control-label" for="toggle_360">Start or Stop filling out the Survey</label>
                                </div>
                            </div>
                        <hr/><!-- /toggle to start or stop period -->

                        <ul class="text-decoration-none">
                            <li>Periods: 2x/year (Semester)</li>
                            <li id="status360">Status: <i class="fa fa-spinner fa-spin"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-6 col-12">
                        <h5 class="mb-0 font-weight-bold">Survey Status</h5>
                        <p class="font-weight-light">Survey Status List on Employee and Departement.</p>
                        <a href="<?= base_url('survey/settings_status'); ?>" class="btn btn-primary"><i class="fa fa-file-signature"></i> Survey Statuses</a>
                    </div>
                    <!-- <div class="col-lg-6 col-12 mt-2">
                        <small class="card-title">Special title treatment</small>
                        
                        <p class="card-text">See survey status on Division and Department.</p>
                        <a href="<?= base_url('survey/settings_statusDepartemen'); ?>" class="btn btn-primary"><i class="fa fa-sitemap"></i> Survey Statuses on Department</a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /* -------------------------------------------------------------------------- */
     /*                                   MODALS                                   */
     /* -------------------------------------------------------------------------- */ -->

<!-- Modal Typeit for checking the user certainty -->
<div class="modal fade" id="typeItModal" tabindex="-1" role="dialog" aria-labelledby="typeItModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typeItModalLabel">Type the right phrase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col">
                        <p class="text">Please type the phrase below.</p>
                        <p class="text-primary font-weight-bold text-center">saya yakin untuk memulai periode baru</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <input type="text" name="typeit" id="typeit" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="checkInput">Ok</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>