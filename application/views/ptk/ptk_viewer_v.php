<!-- <form id="ptkForm" action="<?= base_url('ptk/createNewForm'); ?>" method="POST" novalidate> -->
<form id="ptkForm" action="<?= base_url('ptk/updateStatus'); ?>" method="POST" novalidate>
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
                    <select id="entityInput" name="entity" class="custom-select" required disabled>
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
                    <input type="hidden" name="job_position_choose" >
                    <select id="positionInput" class="form-control select2" style="display: none; width: 100%;" disabled required>
                        <option value="" >Select an Job Position...</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="jobLevelSelect" class="col-sm-4 col-form-label">Job Level</label>
                <div class="col-sm-8">
                    <select id="jobLevelSelect" name="job_level" class="custom-select jobLevelForm" required disabled>
                        <option value="" >Select Job Level...</option>
                        <?php foreach($master_level as $v): ?>
                            <option value="<?= $v['id']; ?>" ><?= $v['name']; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="divisionForm" class="col-sm-4 col-form-label">Division</label>
                <div class="col-sm-8">
                    <input type="hidden" name="division" value="<?= $division['id']; ?>">
                    <select id="divisionForm" class="custom-select" disabled>
                        <option selected value="<?= $division['id']; ?>"><?= $division['division']; ?></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="departementForm" class="col-sm-4 col-form-label">Department</label>
                <div class="col-sm-8">
                    <input type="hidden" name="department" value="<?= $department['id']; ?>">
                    <select id="departementForm" class="custom-select" disabled>
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
                            <select name="work_location_choose" class="custom-select" disabled>
                                <option selected value="">Select Work Location...</option>
                                <?php foreach($work_location as $v): ?>
                                <option value="<?= $v['id']; ?>"><?= $v['location']; ?></option>
                                <?php endforeach;?>
                            </select>
                            <input type="text" name="work_location_text" placeholder="Where to be placed at?" value="<?php echo set_value('work_location'); ?>" class="form-control" style="display: none;" value="-" required disabled>
                        </div>
                        <div class="col-3 align-self-center">
                            <div class="icheck-primary">
                                <input type="checkbox" name="work_location_otherTrigger" id="work_location_otherTrigger" disabled>
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
                <div id="chooseBudget" class="row text-sm-center px-3">
                    <div class="col-sm-4">
                        <div class="icheck-success">
                            <input type="radio" id="budgetRadio" name="budget" value="1" disabled>
                            <label for="budgetRadio">Budgetted</label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="icheck-danger">
                            <input type="radio" id="unbudgettedRadio" name="budget" value="0" disabled>
                            <label for="unbudgettedRadio">Unbudgetted</label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="icheck-warning">
                            <input type="radio" id="replacementRadio" name="budget" value="2" disabled>
                            <label for="replacementRadio">Replacement</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Replacement Who -->
            <div id="replace" class="form-group row" style="display: none;">
                <!-- <div class="col-sm-4">
                    <div class="icheck-primary">
                        <input type="checkbox" id="replacementForm" name="replacement" disabled>
                        <label for="replacementForm">Replacement</label>
                    </div>
                </div> -->
                <label for="replacement_who" class="col-sm-4 col-form-label">Replacement</label>
                <div class="col-sm-8">
                    <!-- <input id="replacement_who" type="text" name="replacement_who" class="form-control" placeholder="Replacement who?" disabled> -->
                    <div class="input-group">
                        <select id="replacement_who" name="replacement_who" class="form-control" disabled>
                            <option value="" >Replacement who?</option>
                        </select>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Resources -->
            <div class="form-group bg-gray-light p-2">
                <p class="font-weight-bold m-0">Resources</p>
            </div>
            <div id="resource" class="form-group row mb-0">
                <div class="col-sm-6 order-sm-0 order-1">
                    <div class="icheck-success">
                        <input type="radio" id="internalForm" name="resources" value="int" required disabled>
                        <label for="internalForm">Internal</label>
                    </div>
                </div>
                <div class="col-sm-6 order-sm-1 order-0">
                    <div class="icheck-danger">
                        <input type="radio" id="eksternalForm" name="resources" value="ext" required disabled>
                        <label for="eksternalForm">External</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="text" name="internal_who" id="internal_who" class="form-control" placeholder="Who is it?" style="display: none;" disabled>
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
                        <input type="number" class="form-control" id="mppReq" name="mpp_req" min="1" readonly>
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
    
    <!-- Status Employement dan Date Required -->
    <div class="row py-0 px-3">
        <div class="col-lg-6">
            <div class="form-group row">
                <label for="emp_stats" class="col-sm-5 col-form-label">Status of Employement</label>
                <div class="col-sm-7">
                    <select id="emp_stats" name="emp_stats" class="custom-select" required disabled>
                        <option value="" >Select One...</option>
                        <?php foreach($emp_status as $v): ?>
                        <option value="<?= $v['id']; ?>" data-nama="<?= $v['status_name']; ?>" ><?= $v['status_name']; ?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div id="temporary_month_container" class="form-group row" style="display: none;">
                <label for="temporary_month" class="col-sm-5 col-form-label">Temporary Months</label>
                <div class="col-sm-7">
                    <select id="temporary_month" name="temporary_month" class="custom-select" disabled>
                        <option value="" >Select Months...</option>
                        <?php for($x = 1; $x <= 12; $x++): ?>
                            <option value="<?= $x; ?>" ><?= $x; ?> <?php if($x == 1): ?>Month<?php else: ?>Months<?php endif; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group row">
                <label for="date_required" class="col-sm-5 col-form-label">Date Required</label>
                <div class="col-sm-7 input-group mb-3">
                    <input type="text" name="date_required" id="date_required" class="pickadate form-control" placeholder="Click or Tap to choose date" value="<?php echo set_value('date_required'); ?>" required disabled>
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /Status Employement dan Date Required -->
    
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
                    <select id="education" name="education" class="custom-select" required disabled>
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
                    <input type="text" name="majoring" class="form-control" id="majoring" placeholder="Enter Majoring" value="<?php echo set_value('majoring'); ?>" required disabled>
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
                        <input type="number" name="preferred_age" class="form-control" id="age" placeholder="Enter Prefered Age" value="<?php echo set_value('preferred_age'); ?>" required min="15" max="70" disabled>
                        <div class="input-group-append">
                            <span class="input-group-text">year</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- <div class="form-group row">
                <label for="sexForm" class="col-sm-5 col-form-label">Sex</label>
                <div class="col-sm-7">
                    <select id="sexForm" name="sex" class="custom-select" required disabled>
                        <option value="" >Select One...</option>
                        <option value="1">Male</option>
                        <option value="0">Female</option>
                    </select>
                </div>
            </div> -->
        </div>
    </div> <!-- /Preferred Age and Sex -->
    
    <!-- Working Experience -->
    <div class="row px-3 mb-3">
        <div class="col-lg-6">
            <div class="form-group row mb-0">
                <label for="inputEmail3" class="col-lg-5 col-form-label">Working Experience</label>
                <div class="col-lg-7">
                    <div class="icheck-warning">
                        <input type="radio" id="freshGradRadio" name="work_exp" value="0" disabled>
                        <label for="freshGradRadio">Fresh Graduate</label>
                    </div>
                </div>
                <!-- <div class="col-lg-6 align-self-center">
                    <p class="text m-0">Fresh Graduate</p>
                </div> -->
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group row">
                <!-- <label for="inputEmail3" class="col-lg-5 col-form-label">Sex</label> -->
                <div class="col-lg-5">
                    <div class="icheck-success">
                        <input type="radio" id="experiencedRadio" name="work_exp" value="1" disabled>
                        <label for="experiencedRadio">Experience</label>
                    </div>
                </div>
                <!-- <div class="col-lg-4 align-self-center">
                    <p class="text m-0">Experience</p>
                </div> -->
                <div class="col-lg-7">
                    <div id="we_years" class="input-group" style="display: none;" >
                        <input type="number" name="work_exp_years" class="form-control" placeholder="Enter Year of Experience" min="1" max="45" disabled>
                        <div class="input-group-append">
                            <span class="input-group-text">year(s)</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Experience Where -->
            <div id="experienced_at" class="form-group row mb-0" style="display: none;">
                <label for="work_exp_at" class="col-sm-5 col-form-label"></label>
                <div class="col-sm-7">
                    <input type="text" name="work_exp_at" class="form-control" id="work_exp_at" placeholder="Experienced at?" disabled>
                </div>
            </div>
        </div>
    </div> <!-- /Working Experience -->
    
    <!-- Skill, Knowledge, and Abilities (SKA) -->
    <div class="row px-3">
        <div class="col">
            <div class="form-group">
                <label id="ska_label">Skill, Knowledge, and Abilities</label>
                <textarea name="ska" id="ska" class="form-control" rows="3" placeholder="Enter ..." required ><?php echo set_value('ska'); ?></textarea>
            </div>
            <div class="form-group ckeditor_loader">
                <p class="m-0 text-center">
                    <!-- <i class="fa fa-spinner fa-spin fa-2x"></i> -->
                    <img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80">
                </p>
            </div>
        </div>
    </div>
    <!-- /Skill, Knowledge, and Abilities (SKA) -->
    
    <!-- Special Requirement -->
    <div class="row px-3">
        <div class="col">
            <div class="form-group">
                <label id="reqSpecial_label">Special Requirement</label>
                <textarea name="req_special" id="req_special" class="form-control" rows="3" placeholder="Enter ..." required ><?php echo set_value('req_special'); ?></textarea>
            </div>
            <div class="form-group ckeditor_loader">
                <p class="m-0 text-center">
                    <!-- <i class="fa fa-spinner fa-spin fa-2x"></i> -->
                    <img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80">
                </p>
            </div>
        </div>
    </div>
    <!-- /Special Requirement -->
    
    <!-- Outline Why This Position is necessary -->
    <div class="row px-3 py-2 mb-3">
        <div class="col">
            <div class="form-group mb-0">
                <label id="outline_label">Outline Why This Position is necessary</label>
                <textarea name="outline" id="outline" class="form-control" rows="3" placeholder="Enter ..." required><?php echo set_value('outline'); ?></textarea>
            </div>
            <div class="form-group ckeditor_loader">
                <p class="m-0 text-center">
                    <!-- <i class="fa fa-spinner fa-spin fa-2x"></i> -->
                    <img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80">
                </p>
            </div>
        </div>
    </div>
    <!-- /Outline Why This Position is necessary -->
    
    <!-- Main Responsibilities -->
    <div class="row px-3 py-2 mb-3">
        <div class="col">
            <div class="form-group mb-0">
                <label id="main_responsibilities_label">Main Responsibilities</label>
                <textarea name="main_responsibilities" id="main_responsibilities" class="form-control" rows="5" placeholder="Enter ..." required ><?php echo set_value('main_responsibilities'); ?></textarea>
            </div>
            <div class="form-group ckeditor_loader">
                <p class="m-0 text-center">
                    <!-- <i class="fa fa-spinner fa-spin fa-2x"></i> -->
                    <img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80">
                </p>
            </div>
        </div>
    </div>
    <!-- /Main Responsibilities -->
    
    <!-- Tasks -->
    <div class="row px-3 py-2 mb-3">
        <div class="col">
            <div class="form-group mb-0">
                <label id="tasks_label">Tasks</label>
                <textarea name="tasks" id="tasks" class="form-control" rows="5" placeholder="Enter ..." required ><?php echo set_value('tasks'); ?></textarea>
            </div>
            <div class="form-group ckeditor_loader">
                <p class="m-0 text-center">
                    <!-- <i class="fa fa-spinner fa-spin fa-2x"></i> -->
                    <img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80">
                </p>
            </div>
        </div>
    </div>
    <!-- /Tasks -->

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
                    <tr>
                        <td>1</td>
                        <td><input type="text" name="interviewer_name1" class="form-control" id="interviewer_name1" placeholder="Choose Division first" disabled></td>
                        <td><input type="text" name="interviewer_position1" class="form-control" id="interviewer_position1" disabled></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><input type="text" name="interviewer_name2" class="form-control" id="interviewer_name2" placeholder="Choose Department first" disabled></td>
                        <td><input type="text" name="interviewer_position2" class="form-control" id="interviewer_position2" disabled></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><input type="text" name="interviewer_name3" class="form-control" id="interviewer_name3" placeholder="Enter Name..." disabled></td>
                        <td><input type="text" name="interviewer_position3" class="form-control" id="interviewer_position3" placeholder="Enter Position..." disabled></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><input type="text" name="interviewer_name4" class="form-control" id="interviewer_name4" placeholder="Enter Name..." disabled></td>
                        <td><input type="text" name="interviewer_position4" class="form-control" id="interviewer_position4" placeholder="Enter Position..." disabled></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- /Interviewer -->
</form>