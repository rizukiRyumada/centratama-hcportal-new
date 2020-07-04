<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Karyawan Survey Status</h3>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-xs-1">
                        <a href="<?= base_url('settings/survey'); ?>" class="btn btn-primary"><i class="fa fa-chevron-left txt-white"></i></a>
                    </div>
                    <div class="col-xs-1 pl-1">
                        <!-- <a href="<?= base_url('survey/settings_printModeTable'); ?>?url=survey/settings_status" class="btn 
                        <?php if($this->session->userdata('survey_status') == 1): ?>
                            btn-secondary
                        <?php else: ?>
                            btn-warning    
                        <?php endif; ?>">Print View</a> -->
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6 toolsMainTable">

                    </div>
                </div>
                <table id="mainTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">NIK</th>
                            <th rowspan="2">Employee Name</th>
                            <th rowspan="2">Division</th>
                            <th rowspan="2">Department</th>
                            <th rowspan="2">Position</th>
                            <th class="text-center" colspan="3">Survey</th>
                        </tr>
                        <tr>
                            <th>Engagement</th>
                            <th>Service</th>
                            <th>360° Feedback</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach($data_karyawan as $v): ?>
                            <tr>
                                <td><?= $v['nik']; ?></td>
                                <td><?= $v['emp_name']; ?></td>
                                <td><?= $v['divisi']; ?></td>
                                <td><?= $v['departemen']; ?></td>
                                <td><?= $v['position']; ?></td>
                                <td class="text-center">
                                    <?php if($this->session->userdata('survey_status') == 1): ?>
                                        <?php if($v['eng'] == TRUE): ?>
                                            <i class="fas fa-check-circle fa-2x text-success"></i>
                                        <?php else: ?>
                                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                                        <?php endif; ?>  
                                    <?php else: ?>
                                        <?php if($v['eng'] == TRUE): ?>
                                            1
                                        <?php else: ?>
                                            0
                                        <?php endif; ?>  
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($this->session->userdata('survey_status') == 1): ?>
                                        <?php if($v['exc'] == TRUE): ?>
                                            <i class="fas fa-check-circle fa-2x text-success"></i>
                                        <?php else: ?>
                                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if($v['exc'] == TRUE): ?>
                                            1
                                        <?php else: ?>
                                            0
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($this->session->userdata('survey_status') == 1): ?>
                                        <?php if($v['f360'] == 1): ?>
                                            <i class="fas fa-check-circle fa-2x text-success"></i>
                                        <?php elseif($v['f360'] == 0): ?>
                                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                                        <?php else: ?>
                                            <i class="fas fa-minus-circle fa-2x text-gray"></i>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if($v['f360'] != 2): ?>
                                            <?= $v['f360']; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div><!-- /.card-body -->
        </div><!-- /.card -->
    </div>
</div>