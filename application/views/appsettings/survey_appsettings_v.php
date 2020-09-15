<!-- Survey Statuses -->
<div class="row mb-1">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Survey Status</h5>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-lg-6 col-12 mt-2">
                        <!-- <small class="card-title">Special title treatment</small> -->
                        
                        <p class="card-text">Survey Status List on Employee and Departement.</p>
                        <a href="<?= base_url('survey/settings_status'); ?>" class="btn btn-primary"><i class="fa fa-file-signature"></i> Survey Statuses</a>
                    </div>
                    <div class="col-lg-6 col-12 mt-2">
                        <!-- <small class="card-title">Special title treatment</small> -->
                        
                        <!-- <p class="card-text">See survey status on Division and Department.</p>
                        <a href="<?= base_url('survey/settings_statusDepartemen'); ?>" class="btn btn-primary"><i class="fa fa-sitemap"></i> Survey Statuses on Department</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Surveys Periods</h5>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="bg-blue p-3 col-lg-4 col-md-6 col-12 mt-2">
                        <h5 class="font-weight-bold">Employee Enggagement Survey</h5>
                        
                        <ul class="text-decoration-none">
                            <li>Periods: 2x/year (Semester)</li>
                            <li>Status: </li>
                        </ul>
                        <a href="javascript:changePeriods('eng')" class="btn btn-light text-dark"><i class="fa fa-file-signature"></i> Survey Statuses</a>
                    </div>
                    <div class="bg-orange p-3 col-lg-4 col-md-6 col-12 mt-2">
                        <h5 class="font-weight-bold">Service Excellence Survey</h5>
                        
                        <ul class="text-decoration-none">
                            <li>Periods: 4x/year (Quartal)</li>
                            <li>Status: </li>
                        </ul>
                        <a href="javascript:changePeriods('exc')" class="btn btn-light text-dark"><i class="fa fa-file-signature"></i> Survey Statuses</a>
                    </div>
                    <div class="bg-yellow p-3 col-lg-4 col-md-6 col-12 mt-2">
                        <h5 class="font-weight-bold">360° Feedback</h5>
                        
                        <ul class="text-decoration-none">
                            <li>Periods: 2x/year (Semester)</li>
                            <li>Status: </li>
                        </ul>
                        <a href="javascript:changePeriods('360')" class="btn btn-light text-dark"><i class="fa fa-file-signature"></i> Survey Statuses</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>