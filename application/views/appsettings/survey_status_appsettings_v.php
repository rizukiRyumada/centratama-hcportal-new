<div class="row">
    <div class="col-12">
        <div class="card">
            <nav class="card-header navbar navbar-expand-md navbar-light bg-light mb-3">
                <a href="<?= base_url('appSettings/survey'); ?>" class="btn btn-primary"><i class="fa fa-chevron-left text-white"></i></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mr-auto pl-2">
                        <li class="nav-item">
                            <a href="<?= base_url('survey/settings_printModeTable'); ?>?url=survey/settings_status" class="btn 
                            <?php if($this->session->userdata('survey_status') == 1): ?>
                                btn-primary
                            <?php else: ?>
                                btn-danger    
                            <?php endif; ?>"><i class="fa fa-print"></i>
                                <?php if($this->session->userdata('survey_status') == 1): ?>
                                    Enable Print Mode
                                <?php else: ?>
                                    Disable Print Mode
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item"><a class="nav-link active" href="#"><i class="fa fa-id-card"></i> Employee</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('survey/settings_statusDepartemen'); ?>"><i class="fa fa-sitemap"></i> Summary</a></li>
                    </ul>
                </div>
            </nav><!-- /.card-header -->
            <div class="card-body">
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
                                        <?php if($v['eng'] == 1): ?>
                                            <i class="fas fa-check-circle fa-2x text-success"></i>
                                        <?php elseif($v['eng'] == 0): ?>
                                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                                        <?php else: ?>
                                            <i class="fas fa-minus-circle fa-2x text-gray"></i>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if($v['eng'] != 2): ?>
                                            <?= $v['eng']; ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($this->session->userdata('survey_status') == 1): ?>
                                        <?php if($v['exc'] == 1): ?>
                                            <i class="fas fa-check-circle fa-2x text-success"></i>
                                        <?php elseif($v['exc'] == 0): ?>
                                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                                        <?php else: ?>
                                            <i class="fas fa-minus-circle fa-2x text-gray"></i>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if($v['exc'] != 2): ?>
                                            <?= $v['exc']; ?>
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

<!-- variable buat export data -->
<script>
    var excelFileName = "Survey Status per Employe";
</script>