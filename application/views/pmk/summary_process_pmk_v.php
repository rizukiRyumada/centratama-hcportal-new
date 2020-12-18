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
                                <b>Status</b>
                                <?php 
                                    $status = json_decode($summary['status_now'], true);
                                    echo'<a id="status" class="float-right" href="javascript:showTimeline('."'".$status['trigger']."'".', 2)" ><span class="badge badge-'.$status['status']['css_color'].'">'.$status['status']['name_text'].'</span></a>';
                                ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 align-self-center">
                        <ul class="list-group list-group-unbordered">
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
                            <th style="width: 200px;" >Position</th>
                            <th>Departement</th>
                            <th style="width: 100px;">BOD</th>
                            <th style="width: 100px;">Join Date</th>
                            <th>Employee Status</th>
                            <th>End of Contract</th>
                            <th style="width: 240px;">Contract/Probation</th>
                            <th>Assessment Status</th>
                            <th>Assessment Score</th>
                            <th style="width: 60px;"><?= "PA ".$pa_year[0]['periode']; ?><br/><span id="pa1_score"><?= $pa_year[0]['year']; ?></span></th>
                            <th style="width: 60px;"><?= "PA ".$pa_year[1]['periode']; ?><br/><span id="pa2_score"><?= $pa_year[1]['year']; ?></span></th>
                            <th style="width: 60px;"><?= "PA ".$pa_year[2]['periode']; ?><br/><span id="pa3_score"><?= $pa_year[2]['year']; ?></span></th>
                            <!-- bagian ini hanya untuk hc divhead, divhead, dan CEO -->
                            <th>Recomendation</th>
                            <th>New Entity</th>
                            <th>View Assessment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data_summary as $v): ?>
                            <tr>
                                <td><?= $v['nik']; ?></td>
                                <td><?= $v['emp_name']; ?></td>
                                <td><?= $v['position']; ?></td>
                                <td><?= $v['department']; ?></td>
                                <td><?= $v['date_birth']; ?></td>
                                <td><?= $v['date_join']; ?></td>
                                <td><?= $v['emp_stats']; ?></td>
                                <td><?= $v['eoc_probation']; ?></td>
                                <td>
                                    <?php foreach($v['yoc_probation'] as $value): ?>
                                        <p class="m-0"><?= $value; ?></p>
                                    <?php endforeach;?>
                                </td>
                                <td>
                                    <?php 
                                        $status = json_decode($v['status_now'], true);
                                        echo'<a id="status" class="float-right w-100" href="javascript:showTimeline('."'".$status['trigger']."'".', 1)" ><span class="badge badge-'.$status['status']['css_color'].' w-100">'.$status['status']['name'].'</span></a>';
                                    ?>    
                                </td>
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
                                <!-- bagian ini hanya untuk hc divhead, divhead, dan CEO -->
                                <td>
                                    <div class="row" style="width: 440px;">
                                        <div class="col-6">
                                            <select class="custom-select" name="recomendation" id="chooser_recomendation<?= $v['id']; ?>" data-id="<?= $v['id']; ?>" data-value="<?= $v['recomendation']; ?>" data-saved="" disabled>
                                                <option value="">Select Action</option>
                                                <option value="0">Terminated</option>
                                                <option value="1">Extended</option>
                                                <option value="2">Permanent</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select class="custom-select" name="extend_for" id="chooser_extendfor<?= $v['id']; ?>" data-id="<?= $v['id']; ?>" data-value="<?= $v['extend_for']; ?>" disabled>
                                                <option value="">Months</option>
                                                <?php for($x = 1; $x < 13; $x++): ?>
                                                    <option value="<?= $x; ?>"><?= $x; ?> 
                                                        <?php if($x == 1): ?>
                                                            Month
                                                        <?php else: ?>
                                                            Months    
                                                        <?php endif; ?>
                                                    </option>
                                                <?php endfor;?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <select class="custom-select" name="entity_new" id="chooser_entityNew<?= $v['id']; ?>" data-id="<?= $v['id']; ?>" data-value="<?= $v['entity_new']; ?>" data-contract="<?= $v['contract']; ?>" data-entity_last="<?= $v['entity_last']['id']; ?>" style="width: 200px;" disabled>
                                        <option value="">Choose Entity</option>
                                        <?php foreach($entity as $value): ?>
                                            <option value="<?= $value['id']; ?>"><?= $value['nama_entity']; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </td>
                                <td>
                                    <div class="container h-100 m-0 px-auto"> <div class="row justify-content-center align-self-center w-100 m-0"><a class="btn btn-primary w-100" href="<?= base_url('pmk/assessment')."?id=".$v['id']."&direct_summary=".$id_summary; ?>"><i class="fa fa-search mx-auto"></i></a></div></div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- notes dan submit button hanya untuk hirarki_org N, CEO, dan HC Divhead dan pada statusnya -->
<div id="notes_container" class="row">
    <div class="col">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Notes</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                </div>
            </div>
            <div class="card-body">
                <form id="form_notes" action="<?= base_url('pmk/updateSummaryProcess'); ?>" method="post">
                    <div class="row">
                        <?php if(!empty($summary_notes)): ?>
                            <?php foreach($summary_notes as $key => $value): ?>
                                <?php if(!empty($value['text'])): ?>
                                    <?php if($key != $whoami && $is_akses == 1): ?>
                                        <div class="col bg-gray-light p-2 p-sm-3 m-1 border-radius-1">
                                            <div class="form-group">
                                                <label class="font-weight-bold mb-3" ><?= $value['by']; ?></label>
                                                <div class="p-3 bg-white overflow-auto" style="height: 380px"><?= $value['text']; ?></div>
                                            </div>
                                            <div class="form-group m-0">
                                                <div class="row">
                                                    <div class="col-lg-4 align-self-middle">
                                                        <p class="m-0 font-weight-bold">Time:</p>
                                                    </div>
                                                    <div class="col-lg-8 align-self-middle">
                                                        <p class="m-0"><?= date("j M'Y, H:i", $value['time']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif($is_akses == 0): ?>
                                        <div class="col bg-gray-light p-2 p-sm-3 m-1 border-radius-1">
                                            <div class="form-group">
                                                <label class="font-weight-bold mb-3" ><?= $value['by']; ?></label>
                                                <div class="p-3 bg-white overflow-auto" style="height: 380px"><?= $value['text']; ?></div>
                                            </div>
                                            <div class="form-group m-0">
                                                <div class="row">
                                                    <div class="col-lg-4 align-self-middle">
                                                        <p class="m-0 font-weight-bold">Time:</p>
                                                    </div>
                                                    <div class="col-lg-8 align-self-middle">
                                                        <p class="m-0"><?= date("j M'Y, H:i", $value['time']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php $x++; endif; ?>
                            <?php endforeach;?>
                        <?php endif; ?>
                        <!-- <div class="form-group">
                            <label></label>
                            <textarea class="form-control" rows="3" placeholder="Enter your notes..."></textarea>
                        </div> -->
                        <div id="container_notes" class="col-lg bg-gray p-2 p-sm-3 m-1 border-radius-1">
                            <div class="form-group">
                                <label class="font-weight-bold mb-3" >
                                    <?php if($this->userApp_admin == 1 || $this->session->userdata('role_id') == 1): ?>
                                        HC Division Head Draft Notes*
                                    <?php else: ?>
                                        <?= $position_my['position_name']; ?>                                        
                                    <?php endif; ?>
                                </label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Enter your notes..."></textarea>
                            </div>
                            <div id="ckeditor_loader" class="form-group">
                                <p class="m-0 text-center">
                                    <!-- <i class="fa fa-spinner fa-spin fa-2x"></i> -->
                                    <img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80">
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- data summary yang diperlukan -->
                    <input type="hidden" name="id_summary">
                    <input type="hidden" name="action">
                    <input type="hidden" name="revise_to">
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
                            <button class="button-action btn btn-lg btn-success" data-action="1" disabled><i class="fas fa-paper-plane" ></i> Approve</button>
                            <?php if($summary['status_now_id'] != "pmksum-01"): ?>
                                <button class="button-action btn btn-lg btn-warning" data-action="0" disabled><i class="fas fa-edit" ></i> Revise</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal pilih dimana mau di mundurkan -->
<div id="modal_revise" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_reviseLabel">Revise Summary</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <p class="m-0">Address revise to...</p>
                </div>
                <div class="form-group">
                    <!-- <label>Minimal</label> -->
                    <select class="form-control select2" style="width: 100%;" name="revise_to">
                        <!-- <option value="" selected="selected">Choose to who?</option> -->
                        <optgroup label="Revise to who?">
                            <?php if(!empty($status_before)): ?>
                                <?php foreach($status_before as $v): ?>
                                    <option value="<?= $v['id']; ?>"><?= $v['pic_name']; ?></option>
                                <?php endforeach;?>
                            <?php endif; ?>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button id="modal_btn_revise" type="button" class="btn btn-warning"><i class="fas fa-edit" ></i> Revise</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- load tampilan viewer status -->
<?php $this->load->view('pmk/viewer_status') ?>