<div class="row">
    <div class="col-12">
        <div class="card">
            <nav class="card-header navbar navbar-expand-md navbar-light bg-light mb-3">
                <a href="<?= base_url('appSettings/survey'); ?>" class="btn btn-primary"><i class="fa fa-chevron-left text-white"></i></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Pricing</a>
                        </li>
                    </ul> -->
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item"><a class="nav-link" href="<?= base_url('survey/settings_status'); ?>"><i class="fa fa-id-card"></i> Employee</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#"><i class="fa fa-sitemap"></i> Summary</a></li>
                    </ul>
                </div>
            </nav><!-- /.card-header -->
            <div class="card-body">
            </div><!-- /.card-body -->
            <div class="card-body table-responsive p-0 border-top">
                <table class="table table-bordered table-hover text-nowrap">
                    <thead>
                        <tr class="bg-blue">
                            <th rowspan="2">Division</th>
                            <th rowspan="2">Department</th>
                            <!-- <th rowspan="2">Total</th> -->
                            <th colspan="3">Engagement</th>
                            <th colspan="3">Service</th>
                            <th colspan="3">360°</th>
                        </tr>
                        <tr class="bg-blue">
                            <th>Total</th>
                            <th>Done</th>
                            <th>%</th>
                            <th>Total</th>
                            <th>Done</th>
                            <th>%</th>
                            <th>Total</th>
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
                                        <!-- <td><?= $v['total_employee']; ?></td> -->
                                        <td><?= $v['eng']['total']; ?></td>
                                        <td><?= $v['eng']['done']; ?></td>
                                        <td><?= substr($v['eng']['rasio'], 0, 4); ?></td>
                                        <td><?= $v['exc']['total']; ?></td>
                                        <td><?= $v['exc']['done']; ?></td>
                                        <td><?= substr($v['exc']['rasio'], 0, 4); ?></td>
                                        <td><?= $v['f360']['total']; ?></td>
                                        <td><?= $v['f360']['done']; ?></td>
                                        <td><?= substr($v['f360']['rasio'], 0, 4); ?></td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                    <td><?= $v['nama_departemen']; ?></td>
                                        <!-- <td><?= $v['total_employee']; ?></td> -->
                                        <td><?= $v['eng']['total']; ?></td>
                                        <td><?= $v['eng']['done']; ?></td>
                                        <td><?= substr($v['eng']['rasio'], 0, 4); ?></td>
                                        <td><?= $v['exc']['total']; ?></td>
                                        <td><?= $v['exc']['done']; ?></td>
                                        <td><?= substr($v['exc']['rasio'], 0, 4); ?></td>
                                        <td><?= $v['f360']['total']; ?></td>
                                        <td><?= $v['f360']['done']; ?></td>
                                        <td><?= substr($v['f360']['rasio'], 0, 4); ?></td>
                                    </tr>    
                                <?php endif; ?>
                                <?php $x++; ?>
                            <?php endforeach;?>
                            <!-- subtotal -->
                            <tr class="bg-blue">
                                <td colspan="2"><?= $value['division']; ?> Total</td>
                                <td><?= $value['total_eng']; ?></td>
                                <td><?= $value['total_done_eng']; ?></td>
                                <td><?= substr($value['total_rasio_eng'], 0, 4); ?></td>
                                <td><?= $value['total_exc']; ?></td>
                                <td><?= $value['total_done_exc']; ?></td>
                                <td><?= substr($value['total_rasio_exc'], 0, 4); ?></td>
                                <td><?= $value['total_f360']; ?></td>
                                <td><?= $value['total_done_f360']; ?></td>
                                <td><?= substr($value['total_rasio_f360'], 0, 4); ?></td>
                            </tr>
                        <?php endforeach;?>
                        <!-- grandtotal -->
                        <tr>
                            <td colspan="2">Grand Total</td>
                            <td><?= $counter_data_survey['total_eng']; ?></td>
                            <td><?= $counter_data_survey['total_done_eng']; ?></td>
                            <td><?= substr($counter_data_survey['total_rasio_eng'], 0, 4); ?></td>
                            <td><?= $counter_data_survey['total_exc']; ?></td>
                            <td><?= $counter_data_survey['total_done_exc']; ?></td>
                            <td><?= substr($counter_data_survey['total_rasio_exc'], 0, 4); ?></td>
                            <td><?= $counter_data_survey['total_f360']; ?></td>
                            <td><?= $counter_data_survey['total_done_f360']; ?></td>
                            <td><?= substr($counter_data_survey['total_rasio_f360'], 0, 4); ?></td>
                        </tr>
                    </tbody>

                    <tfoot>
                        
                    </tfoot>
                </table>
            </div>
        </div><!-- /.card -->
    </div>
</div>

<!-- variable buat export data -->
<script>
    var excelFileName = "Survey Status Summary";
</script>