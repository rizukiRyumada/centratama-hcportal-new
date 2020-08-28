<!-- TODO Create Manual Validation -->
<!-- TODO Pasang Tippy.js -->
<!-- TODO Submition form -->
<!-- NOW replacement input form -->

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
                        <form id="ptkForm" action="<?= base_url('ptk/createNewForm'); ?>" method="POST" novalidate>
                            <div class="row bg-gray mb-3">
                                <div class="col py-2">
                                    <h5 class="font-weight-bold m-0">Identity</h5>
                                    <small class="font-weight-light">Enter the identity that needed for new Manpower</small>
                                </div>
                            </div>

                            <!-- Identity -->
                            <div class="row">
                                <div class="col-lg-6 border-gray-light border p-3">
                                    <div class="form-group row">
                                        <label for="entityInput" class="col-sm-4 col-form-label">Entity</label>
                                        <div class="col-sm-8">
                                            <select id="entityInput" name="entity" class="custom-select" required>
                                                <option value="" >Select an Entity...</option>
                                                <?php foreach($entity as $v): ?>
                                                    <option value="<?= $v['id']; ?>" data-nama="<?= $v['nama_entity']; ?>" ><?= $v['keterangan']; ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>                                        
                                    </div>
                                    <div class="form-group row">
                                        <label for="jobTitleInput" class="col-sm-4 col-form-label">Job Position</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="budgetAlert" class="form-control border border-danger" value="Choose budgeted or unbudgeted first" title="Please Choose budgeted or unbudgeted first" disabled>
                                            <input name="job_position_text" type="text" class="form-control" id="jobTitleInput" placeholder="Enter Job Title..." style="display: none;" required>
                                            <select id="positionInput" name="job_position_choose" class="custom-select" style="display: none;" required>
                                                <option value="" >Select an Job Position...</option>
                                                <?php foreach($position as $v): ?>
                                                    <option value="<?= $v['id']; ?>" ><?= $v['position_name']; ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="jobLevelForm" class="col-sm-4 col-form-label">Job Level</label>
                                        <div class="col-sm-8">
                                            <select id="jobLevelForm" name="job_level" class="custom-select" required>
                                                <option value="" >Select Job Level...</option>
                                                <option value="job_level-0" >Staff</option>
                                                <option value="job_level-1" >Supervisor</option>
                                                <option value="job_level-2" >Manager</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="divisionForm" class="col-sm-4 col-form-label">Division</label>
                                        <div class="col-sm-8">
                                            <select id="divisionForm" name="division" class="custom-select" disabled>
                                                <option selected value="<?= $division['id']; ?>"><?= $division['division']; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="departementForm" class="col-sm-4 col-form-label">Department</label>
                                        <div class="col-sm-8">
                                            <select id="departementForm" name="department" class="custom-select" disabled>
                                                <option selected value="<?= $department['id']; ?>"><?= $department['nama_departemen']; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <label for="workLocationForm" class="col-sm-4 col-form-label">Work Location</label>
                                        <div class="col-sm-8">
                                            <!-- <input id="workLocation_text" type="text" name="work_location_text" class="form-control" id="workLocationForm" placeholder="Where to be placed at?" value="<?php echo set_value('work_location'); ?>" required> -->
                                            <div class="row h-100">
                                                <div class="col-9">
                                                    <select name="work_location_choose" class="custom-select" >
                                                        <option selected value="">Select Work Location...</option>
                                                        <?php foreach($work_location as $v): ?>
                                                            <option value="<?= $v['id']; ?>"><?= $v['location']; ?></option>
                                                        <?php endforeach;?>
                                                    </select>
                                                    <input type="text" name="work_location_text" placeholder="Where to be placed at?" value="<?php echo set_value('work_location'); ?>" class="form-control" style="display: none;" value="-" required>
                                                </div>
                                                <div class="col-3 align-self-center">
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" name="work_location_otherTrigger" id="work_location_otherTrigger" >
                                                        <label for="work_location_otherTrigger">Other</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Budget -->
                                <div class="col-lg-6 border-gray-light border p-3">
                                    <div class="form-group clearfix border border-gray-light">
                                        <div class="row text-sm-center px-3">
                                            <div class="col-sm-6">
                                                <div class="icheck-success">
                                                    <input type="radio" id="budgetRadio" name="budget" value="1">
                                                    <label for="budgetRadio">Budgetted</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="icheck-danger">
                                                    <input type="radio" id="unbudgettedRadio" name="budget" value="0">
                                                    <label for="unbudgettedRadio">Unbudgetted</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Replacement -->
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <div class="icheck-primary">
                                                <input type="checkbox" id="replacementForm" name="replacement">
                                                <label for="replacementForm">Replacement</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="replacement_who" class="form-control" placeholder="Replacement who?" value="<?php echo set_value('replacement_who'); ?>" disabled>
                                        </div>
                                    </div>
                                    <!-- Resources -->
                                    <div class="form-group bg-gray-light p-2">
                                        <p class="font-weight-bold m-0">Resources</p>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-sm-6 order-sm-0 order-1">
                                            <div class="icheck-success">
                                                <input type="radio" id="internalForm" name="resources" value="int" required>
                                                <label for="internalForm">Internal</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 order-sm-1 order-0">
                                            <div class="icheck-danger">
                                                <input type="radio" id="eksternalForm" name="resources" value="ext" required>
                                                <label for="eksternalForm">External</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="internal_who" class="form-control" placeholder="Who is it?" style="display: none;">
                                    </div>
                                </div>
                            </div>
                            <!-- /Identity -->

                            <!-- Man of power dan Number of Incumbent -->
                            <div class="row pt-3 px-3 pb-0">
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="mppReq" class="col-sm-5 col-form-label">Manpower required</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <!-- TODO tambah fungsi max mengikuti di database -->
                                                <input type="number" class="form-control" id="mppReq" name="mpp_req" min="1" max="5">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">person(s)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="noiReq" class="col-sm-5 col-form-label">Number of Incumbent</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                            <input type="text" class="form-control" id="noiReq" value="-" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">persons</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /Man of power dan Number of Incumbent -->

                            <!-- Status Employement dan Number of Incumbent -->
                            <div class="row py-0 px-3">
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="emp_stats" class="col-sm-5 col-form-label">Status of Employement</label>
                                        <div class="col-sm-7">
                                            <select id="emp_stats" name="emp_stats" class="custom-select" required>
                                                <option value="" >Select One...</option>
                                                <?php foreach($emp_status as $v): ?>
                                                    <option value="<?= $v['id']; ?>" data-nama="<?= $v['status_name']; ?>" ><?= $v['status_name']; ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-5 col-form-label">Date Required</label>
                                        <div class="col-sm-7 input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            </div>
                                            <input type="text" name="date_required" class="pickadate form-control" placeholder="Click or Tap to choose date" value="<?php echo set_value('date_required'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /Status Employement dan Number of Incumbent -->

                            <div class="row bg-gray mb-3">
                                <div class="col py-2">
                                    <h5 class="font-weight-bold m-0">Qualifications</h5>
                                    <small class="font-weight-light">What is the qualification needed to qualify this Job Title?</small>
                                </div>
                            </div>

                            <!-- Education and Majoring -->
                            <div class="row px-3">
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="education" class="col-sm-5 col-form-label">Education</label>
                                        <div class="col-sm-7">
                                            <select id="education" name="education" class="custom-select" required>
                                                <option value="" >Select One...</option>
                                                <?php foreach($education as $v): ?>
                                                    <option value="<?= $v['id']; ?>" data-nama="<?= $v['name']; ?>" ><?= $v['name']; ?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="majoring" class="col-sm-5 col-form-label">Majoring</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="majoring" class="form-control" id="majoring" placeholder="Enter Majoring" value="<?php echo set_value('majoring'); ?>" required>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /Education and Majoring -->

                            <!-- Preferred Age and Sex -->
                            <div class="row px-3">
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="age" class="col-sm-5 col-form-label">Preferred Age</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <input type="number" name="preferred_age" class="form-control" id="age" placeholder="Enter Prefered Age" value="<?php echo set_value('preferred_age'); ?>" required min="15" max="70">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">year</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label for="sexForm" class="col-sm-5 col-form-label">Sex</label>
                                        <div class="col-sm-7">
                                            <select id="sexForm" name="sex" class="custom-select" required>
                                                <option value="" >Select One...</option>
                                                <option value="1">Male</option>
                                                <option value="0">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /Preferred Age and Sex -->

                            <!-- Working Experience -->
                            <div class="row px-3 mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group row mb-0">
                                        <label for="inputEmail3" class="col-lg-5 col-form-label">Working Experience</label>
                                        <div class="col-lg-7">
                                            <div class="icheck-warning">
                                                <input type="radio" id="freshGradRadio" name="work_exp" value="0">
                                                <label for="freshGradRadio">Fresh Graduate</label>
                                            </div>
                                        </div>
                                        <!-- <div class="col-lg-6 align-self-center">
                                            <p class="text m-0">Fresh Graduate</p>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row mb-0">
                                        <!-- <label for="inputEmail3" class="col-lg-5 col-form-label">Sex</label> -->
                                        <div class="col-lg-5">
                                            <div class="icheck-success">
                                                <input type="radio" id="experiencedRadio" name="work_exp" value="1">
                                                <label for="experiencedRadio">Experience</label>
                                            </div>
                                        </div>
                                        <!-- <div class="col-lg-4 align-self-center">
                                            <p class="text m-0">Experience</p>
                                        </div> -->
                                        <div class="col-lg-7">
                                            <div id="we_years" class="input-group" style="display: none;" >
                                                <input type="number" name="work_exp_years" class="form-control" placeholder="Enter Year of Experience" min="1" max="45" >
                                                <div class="input-group-append">
                                                    <span class="input-group-text">year(s)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /Working Experience -->

                            <!-- Skill, Knowledge, and Abilities (SKA) -->
                            <div class="row px-3">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Skill, Knowledge, and Abilities</label>
                                        <textarea name="ska" class="form-control ckeditor" rows="3" placeholder="Enter ..." required ><?php echo set_value('ska'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- /Skill, Knowledge, and Abilities (SKA) -->

                            <!-- Special Requirement -->
                            <div class="row px-3">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Special Requirement</label>
                                        <textarea name="req_special" class="form-control ckeditor" rows="3" placeholder="Enter ..." required ><?php echo set_value('req_special'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- /Special Requirement -->

                            <!-- Outline Why This Position is necessary -->
                            <div class="row px-3 py-2 mb-3">
                                <div class="col">
                                    <div class="form-group mb-0">
                                        <label>Outline Why This Position is necessary</label>
                                        <textarea name="outline" class="form-control ckeditor" rows="3" placeholder="Enter ..." required><?php echo set_value('outline'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- /Outline Why This Position is necessary -->

                            <!-- Interviewer -->
                            <!-- <div class="row px-3 border border-gray-light py-2 mb-3">
                                <div class="col">
                                    <div class="form-group mb-0">
                                        <label>Interviewer</label>
                                        <table class="table table-striped">
                                            <thead class="text-center font-weight-bold" >
                                                <tr>
                                                    <th style="width: 10px;" >No.</th>
                                                    <th>Name</th>
                                                    <th>Job Position</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-center" >1.</td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center" >2.</td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle text-center" >3.</td>
                                                    <td>
                                                        <div class="form-group m-0">
                                                            <input name="interviewer_name" type="text" class="form-control" id="inputEnterName" placeholder="Enter Name...">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group m-0">
                                                            <input name="interviewer_job_position" type="text" class="form-control" id="inputEnterJobTitle" placeholder="Enter Job Title...">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> -->

                            <div class="row px-3 border border-gray-light py-2 mb-3">
                                <div class="col">
                                    <?php $x = 1; ?>
                                    <div class="form-group mb-0 table-responsive">
                                        <label>Interviewer</label>
                                        <table class="table table-striped" style="min-width: 250px;">
                                            <tr>
                                                <th style="width: 10px;">No.</th>
                                                <th>Name</th>
                                                <th>Position</th>
                                            </tr>
                                            <?php foreach($data_atasan['atasan2'] as $v): ?>
                                                <tr>
                                                    <td><?= $x; ?></td>
                                                    <td><?= $v['emp_name']; ?></td>
                                                    <td><?= $v['position_name']; ?></td>
                                                </tr>
                                                <?php $x++; ?>
                                            <?php endforeach;?>
                                            <?php foreach($data_atasan['atasan1'] as $v): ?>
                                                <tr>
                                                    <td><?= $x; ?></td>
                                                    <td><?= $v['emp_name']; ?></td>
                                                    <td><?= $v['position_name']; ?></td>
                                                </tr>
                                                <?php $x++; ?>
                                            <?php endforeach;?>
                                            <tr>
                                                <td><?= $x; ?></td>
                                                <td><input type="text" name="interviewer_name3" class="form-control" id="interviewer_name3" placeholder="Enter Name..."></td>
                                                <td><input type="text" name="interviewer_position3" class="form-control" id="interviewer_position3" placeholder="Enter Position..."></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /Interviewer -->

                            <!-- Main Responsibilities -->
                            <div class="row px-3 py-2 mb-3">
                                <div class="col">
                                    <div class="form-group mb-0">
                                        <label>Main Responsibilities</label>
                                        <textarea name="main_responsibilities" class="form-control ckeditor" rows="5" placeholder="Enter ..." required ><?php echo set_value('main_responsibilities'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- /Main Responsibilities -->

                            <!-- Tasks -->
                            <div class="row px-3 py-2 mb-3">
                                <div class="col">
                                    <div class="form-group mb-0">
                                        <label>Tasks</label>
                                        <textarea name="tasks" class="form-control ckeditor" rows="5" placeholder="Enter ..." required ><?php echo set_value('tasks'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- /Tasks -->

                            <!-- Submit Button -->
                            <div class="row px-3 justify-content-end">
                                <div class="col-md-3 text-right">
                                    <button class="btn btn-lg btn-success w-100"><i class="fa fa-paper-plane"></i> Submit</button>
                                </div>
                            </div>

                        </form>
                    </div><!-- /Tab form PTK -->
                    
<!-- /* -------------------------------------------------------------------------- */
/*                                Tab form Job Profile                             */
/* ------------------------------------------------------------------------------- */ -->
                    <!-- Tab form Job Profile -->
                    <div class="tab-pane fade" id="custom-tabs-jobProfile" role="tabpanel" aria-labelledby="custom-tabs-jobProfile-tab">
                        <div class="card card-gray box-shadow-none border-0">
                            <div class="card-header p-0 pb-1">
                                <h3 class="card-title"></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                                </div><!-- /.card-tools -->
                            </div><!-- /.card-header -->
                            <div class="card-body p-0" style="height: 1000px">
                                <iframe id="viewer_jobprofile" frameborder="0" scrolling="yes" style="width: 100%; height:100%; overflow: visible"></iframe>
                            </div><!-- /.card-body -->
                        </div>
                        <!-- <iframe id="ifrm" src="<?= base_url(); ?>" onload="setIframeHeight(this.id)"></iframe> -->
                        <!-- <iframe id="ifrm" src="pages/height1.html" onload="setIframeHeight(this.id)"></iframe> -->
                        <!-- <iframe id="viewer_jobprofile" src="..." frameborder="0" scrolling="no" style="width: 100%; display: block; overflow: auto;" onload="resizeIframe(this)" ></iframe> -->
                    </div><!-- /Tab form Job Profile -->

                    <!-- Tab form Organization Chart -->
                    <div class="tab-pane fade" id="custom-tabs-orgchart" role="tabpanel" aria-labelledby="custom-tabs-orgchart-tab">
                        <div class="card card-gray">
                            <div class="card-header p-0 pb-1">
                                <h3 class="card-title"></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                                </div><!-- /.card-tools -->
                            </div><!-- /.card-header -->
                            <div class="card-body p-0" style="height: 600px">
                                <iframe id="viewer_jobprofile_orgchart" frameborder="0" scrolling="yes" style="width: 100%; height:100%; overflow: visible"></iframe>
                            </div><!-- /.card-body -->
                        </div>
                    </div><!-- /Tab form Organization Chart -->
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>