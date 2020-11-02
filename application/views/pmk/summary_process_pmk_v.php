<div class="row">
    <div class="col">
        <div class="card ">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-2">
                        <a href="<?= base_url('pmk/index?direct=sumhis'); ?>" class="btn btn-primary"><i class="fas fa-chevron-left"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Division</b> <a id="division" class="float-right"><?= $summary['divisi_name']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Created</b> <a id="created" class="float-right"><?= $summary['created']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Modified</b> <a id="modified" class="float-right"><?= $summary['modified']; ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 align-self-center">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Status</b>
                                <?php 
                                    $status = json_decode($summary['status_now'], true);
                                    echo'<a id="status" class="float-right" href="javascript:showTimeline('."'".$status['trigger']."'".')" ><span class="badge badge-'.$status['status']['css_color'].'">'.$status['status']['name_text'].'</span></a>';
                                ?>
                            </li>
                            <li class="list-group-item">
                                <b>Month</b> <a id="bulan" class="float-right"><?= $summary['bulan']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Year</b> <a id="tahun" class="float-right"><?= $summary['tahun']; ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- main section -->
<div class="row">
    <div class="col">
        <div class="card card-primary card-outline-tabs card-outline">
            <div class="overlay" style="display:none;"><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div>
            <div class="card-body table-responsive">
                <table id="table_summaryProcess" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>NIK</th>
                            <th>Employee Name</th>
                            <th>BOD</th>
                            <th>Join Date</th>
                            <th>Employee Status</th>
                            <th>End of Contract</th>
                            <th>Contract<br/>#</th>
                            <th>Year of Contract/Probation</th>
                            <th>Position</th>
                            <th>Departement</th>
                            <th>Division</th>
                            <th>Entity</th>
                            <th>Assessment</th>
                            <th style="width: 60px;"><?= "PA ".$pa_year[0]['periode']; ?><br/><span id="pa1_score"><?= $pa_year[0]['year']; ?></span></th>
                            <th style="width: 60px;"><?= "PA ".$pa_year[1]['periode']; ?><br/><span id="pa2_score"><?= $pa_year[1]['year']; ?></span></th>
                            <th style="width: 60px;"><?= "PA ".$pa_year[2]['periode']; ?><br/><span id="pa3_score"><?= $pa_year[2]['year']; ?></span></th>
                            <th>Status</th>
                            <!-- bagian ini hanya untuk hc divhead, divhead, dan CEO -->
                            <?php if($is_akses == 1): ?>
                                <th>Summary</th>
                                <th>Choose New Entity</th>
                            <?php endif; ?>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data_summary as $v): ?>
                            <tr>
                                <td><?= $v['nik']; ?></td>
                                <td><?= $v['emp_name']; ?></td>
                                <td><?= $v['date_birth']; ?></td>
                                <td><?= $v['date_join']; ?></td>
                                <td><?= $v['emp_stats']; ?></td>
                                <td><?= $v['eoc_probation']; ?></td>
                                <td><?= $v['contract']; ?></td>
                                <td><?= $v['yoc_probation']; ?></td>
                                <td><?= $v['position']; ?></td>
                                <td><?= $v['department']; ?></td>
                                <td><?= $v['divisi']; ?></td>
                                <td><?= $v['entity']; ?></td>
                                <td><?= $v['survey_rerata']; ?></td>
                                <td>
                                    <?php if(!empty($v['pa1']['score'])): ?>
                                        <?=$v['pa1']['score']." (".$v['pa1']['rating'].")"; ?>
                                    <?php endif; ?></td>
                                <td>
                                    <?php if(!empty($v['pa2']['score'])): ?>
                                        <?=$v['pa2']['score']." (".$v['pa2']['rating'].")"; ?>
                                    <?php endif; ?></td>
                                <td>
                                    <?php if(!empty($v['pa3']['score'])): ?>
                                        <?=$v['pa3']['score']." (".$v['pa3']['rating'].")"; ?>
                                    <?php endif; ?></td>
                                <td>
                                    <?php 
                                        $status = json_decode($v['status_now'], true);
                                        echo'<a id="status" class="float-right" href="javascript:showTimeline('."'".$status['trigger']."'".')" ><span class="badge badge-'.$status['status']['css_color'].'">'.$status['status']['name'].'</span></a>';
                                    ?>    
                                </td>
                                <!-- bagian ini hanya untuk hc divhead, divhead, dan CEO -->
                                <?php if($is_akses == 1): ?>
                                    <td>
                                        <select class="custom-select" name="summary" id="chooser_summary<?= $v['id']; ?>" data-id="<?= $v['id']; ?>" data-value="<?= $v['summary']; ?>" style="width: 200px;" >
                                            <option value="">Select Action</option>
                                            <option value="0">Terminated</option>
                                            <option value="1">Extended</option>
                                            <option value="2">Permanent</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="custom-select" name="entity_new" id="chooser_entityNew<?= $v['id']; ?>" data-id="<?= $v['id']; ?>" data-value="<?= $v['entity_new']; ?>" style="width: 200px;" disabled>
                                            <option value="">Choose Entity</option>
                                            <?php foreach($entity as $value): ?>
                                                <option value="<?= $value['id']; ?>"><?= $value['nama_entity']; ?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </td>
                                <?php endif; ?>
                                <td></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- notes dan submit button hanya untuk hirarki_org N, CEO, dan HC Divhead -->
<?php if($is_akses == 1): ?>
    <div id="notes_container" class="row">
        <div class="col">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Notes</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <?php 
                    $summary_notes = json_decode($summary['notes'], true);
                    ?>
                    <?php if(!empty($summary_notes)): ?>
                        <?php foreach($summary_notes as $key => $value): ?>
                            <?php if(!empty($value)): ?>
                                <div class="form-group">
                                    <label>Notes from <?= $key; ?></label>
                                    <div class="form-control"><?= $value; ?></div>
                                </div>   
                            <?php endif; ?>
                        <?php endforeach;?>
                    <?php endif; ?>
                    <!-- <div class="form-group">
                        <label></label>
                        <textarea class="form-control" rows="3" placeholder="Enter your notes..."></textarea>
                    </div> -->
                    <form id="form_notes" action="<?= base_url('pmk/updateSummaryProcess'); ?>" method="post">
                        <div class="form-group">
                            <label >Your Notes</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Enter your notes..."></textarea>
                        </div>

                        <!-- data summary yang diperlukan -->
                        <input type="hidden" name="id_summary">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="button_container" class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-end">
                        <div class="col-lg-4">
                            <div class="btn-group w-100">
                                <button id="button_submit" class="btn btn-lg btn-success"><i class="fas fa-paper-plane"></i> Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>