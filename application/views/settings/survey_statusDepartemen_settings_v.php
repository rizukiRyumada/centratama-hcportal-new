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
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">Division</th>
                            <th rowspan="2">Department</th>
                            <th rowspan="2">Total</th>
                            <th colspan="2">Engagement</th>
                            <th colspan="2">Service</th>
                            <th colspan="2">360°</th>
                        </tr>
                        <tr>
                            <th>Done</th>
                            <th>%</th>
                            <th>Done</th>
                            <th>%</th>
                            <th>Done</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach($data_survey as $value): ?>
                            <tr>
                                <td rowspan="<?= $value['count_departemen']; ?>"><?= $value['division']; ?></td>
                            <?php $x=0; ?>
                            <?php foreach($value['departemen'] as $k => $v): ?>
                                <?php if($x == 0): ?>
                                        <td><?= $v['nama_departemen']; ?></td>
                                        <td><?= $v['total_employee']; ?></td>
                                        <td><?= $v['exc']['done']; ?></td>
                                        <td><?= $v['exc']['rasio']; ?></td>
                                        <td><?= $v['eng']['done']; ?></td>
                                        <td><?= $v['eng']['rasio']; ?></td>
                                        <td><?= $v['f360']['done']; ?></td>
                                        <td><?= $v['f360']['rasio']; ?></td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td><?= $v['nama_departemen']; ?></td>
                                        <td><?= $v['total_employee']; ?></td>
                                        <td><?= $v['exc']['done']; ?></td>
                                        <td><?= $v['exc']['rasio']; ?></td>
                                        <td><?= $v['eng']['done']; ?></td>
                                        <td><?= $v['eng']['rasio']; ?></td>
                                        <td><?= $v['f360']['done']; ?></td>
                                        <td><?= $v['f360']['rasio']; ?></td>
                                    </tr>    
                                <?php endif; ?>
                                <?php $x++; ?>
                            <?php endforeach;?>
                        <?php endforeach;?>
                    </tbody>

                    <tfoot>
                        
                    </tfoot>
                </table>
            </div><!-- /.card-body -->
        </div><!-- /.card -->
    </div>
</div>