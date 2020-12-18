<!--/* -------------------------------------------------------------------------- */
    /*                                MAIN CONTENT                                */
    /* -------------------------------------------------------------------------- */ -->
<!-- desktop table cards -->
<div class="row f360-desktop-wrapper"> 
    <div class="col">
        <div class="container-fluid">

            <?php if(!empty($data_atasan)): ?>
                <!-- table data atasan -->
                <div class="row">
                    <div class="col-12 p-0">
                        <div class="card card-blue">
                            <div class="card-header">
                                <h3 class="card-title">Superior</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                                <!-- card tools -->
                            </div><!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="th-f360-wider">Employee Name</th>
                                            <th class="th-f360">Departement</th>
                                            <th class="th-f360">Division</th>
                                            <th class="th-f360-wider">Position</th>
                                            <th class="th-f360-btn"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data_atasan as $v): ?>
                                            <tr>
                                                <td class="th-f360-wider"><?= $v['emp_name']; ?></td>
                                                <td class="th-f360"><?= $v['departemen']; ?></td>
                                                <td class="th-f360"><?= $v['divisi']; ?></td>
                                                <td class="th-f360-wider"><?= $v['position_name']; ?></td>
                                                <td class="th-f360-btn">
                                                    <div class="d-flex justify-content-center">
                                                        <?php if($v['status'] == FALSE): ?>
                                                            <a href="<?= base_url('survey/f360survey/'); ?>?nik=<?= $v['nik']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                                        <?php else: ?>
                                                            <i class="fa fa-check-circle fa-2x text-success"></i>    
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div><!-- /.card-body -->

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            <?php endif; ?>

            <?php if(!empty($data_peers)): ?>
                <!-- table data peers -->
                <div class="row">
                    <div class="col-12 p-0">
                        <div class="card card-orange">
                            <div class="card-header">
                                <h3 class="card-title">Peers</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="th-f360-wider">Employee Name</th>
                                            <th class="th-f360">Departement</th>
                                            <th class="th-f360">Division</th>
                                            <th class="th-f360-wider">Position</th>
                                            <th class="th-f360-btn"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data_peers as $v): ?>
                                            <tr>
                                                <td class="th-f360-wider"><?= $v['emp_name']; ?></td>
                                                <td class="th-f360"><?= $v['departemen']; ?></td>
                                                <td class="th-f360"><?= $v['divisi']; ?></td>
                                                <td class="th-f360-wider"><?= $v['position_name']; ?></td>
                                                <td class="th-f360-btn">
                                                    <div class="d-flex justify-content-center">
                                                        <?php if($v['status'] == FALSE): ?>
                                                            <a href="<?= base_url('survey/f360survey/'); ?>?nik=<?= $v['nik']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                                        <?php else: ?>
                                                            <i class="fa fa-check-circle fa-2x text-success"></i>    
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            <?php endif; ?>

            <?php if(!empty($data_other_function)): ?>
                <!-- table data Other Function -->
                <div class="row">
                    <div class="col-12 p-0">
                        <div class="card card-yellow">
                            <div class="card-header">
                                <h3 class="card-title">Peers Other Function</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <div class="overlay dark"></div>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="th-f360-wider">Employee Name</th>
                                            <th class="th-f360">Departement</th>
                                            <th class="th-f360">Division</th>
                                            <th class="th-f360-wider">Position</th>
                                            <th class="th-f360-btn"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- jika ada survey yang sudah selesai -->
                                        <?php $x=1; ?>
                                        <?php if(!empty($data_complete_of)): ?>
                                            <?php foreach($data_complete_of as $k => $v): ?>
                                                <tr>
                                                    <td class="th-f360-wider"><?= $v['emp_name']; ?></td>
                                                    <td class="th-f360" ><?= $v['departemen']; ?></td>
                                                    <td class="th-f360" ><?= $v['divisi']; ?></td>
                                                    <td class="th-f360-wider" ><?= $v['position_name']; ?></td>
                                                    <td class="th-f360-btn"><i class="fa fa-check-circle fa-2x text-success"></i></td>
                                                </tr>
                                                <?php $x++; ?>
                                            <?php endforeach;?>
                                        <?php endif; ?>
                                        <!-- other function penilaian data terdaftar -->
                                        <?php if(!empty($data_other_function_penilaian)): ?>
                                            <?php foreach($data_other_function_penilaian as $k => $v): ?>
                                                <tr>
                                                    <td class="th-f360-wider"><?= $v['emp_name']; ?></td>
                                                    <td class="th-f360" ><?= $v['departemen']; ?></td>
                                                    <td class="th-f360" ><?= $v['divisi']; ?></td>
                                                    <td class="th-f360-wider" ><?= $v['position_name']; ?></td>
                                                    <td class="th-f360-btn">
                                                        <div id="hrefWrapper_<?= $x ?>" class="justify-content-center">
                                                            <a id="href_<?= $x ?>" href="<?= base_url('survey/f360survey?nik=').$v['nik']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $x++; ?>
                                            <?php endforeach;?>
                                        <?php endif; ?>
                                        <?php if(!empty($data_notyet_of)): ?>
                                            <!-- options -->
                                            <?php $y=1; ?>
                                            <?php for($x; $x<=$max_feedback_other_peers; $x++){ ?>
                                                <tr>
                                                    <td class="th-f360-wider">
                                                        <div class="input-group-sm">
                                                            <select class="custom-select" id="f360getEmployeDesktop_<?= $x; ?>">
                                                                <?php if($y == 1): ?>
                                                                    <option value="" selected>Choose...</option>
                                                                    <?php foreach($data_notyet_of as $v): ?>
                                                                        <option class="select2-selection__choice" value="<?= $v['nik']; ?>"><?= $v['emp_name']; ?></option>
                                                                    <?php endforeach;?>
                                                                <?php else: ?>
                                                                    <option value="" selected>Please choose the top selection first...</option>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="th-f360" id="dept_<?= $x ?>"></td>
                                                    <td class="th-f360" id="div_<?= $x ?>"></td>
                                                    <td class="th-f360-wider" id="pos_<?= $x ?>"></td>
                                                    <td class="th-f360-btn">
                                                        <div id="hrefWrapper_<?= $x ?>" class="invisible justify-content-center">
                                                            <a href="#" id="href_<?= $x ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $y++; ?>
                                            <?php } ?><!-- /options -->
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div><!-- /desktop table cards -->

<!-- mobile table cards -->
<div class="row f360-mobile-wrapper">
    <div class="col">
        <div class="container-fluid">
            <!-- table data atasan -->
            <?php if(!empty($data_atasan)): ?>
                <div class="row">
                    <div class="col-12 p-0">
                        <div class="card card-blue">
                            <div class="card-header">
                                <h3 class="card-title">Superior</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                                <!-- card tools -->
                            </div><!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="th-f360-80">Employee Name</th>
                                            <th class="th-f360-btn"></th>
                                            <th class="th-f360-btn"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data_atasan as $v): ?>
                                            <tr>
                                                <td><?= $v['emp_name']; ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="#" class="btnEmployeInfo" data-nik="<?= $v['nik']; ?>" data-emp_name="<?= $v['emp_name']; ?>" data-departemen="<?= $v['departemen']; ?>" data-divisi="<?= $v['divisi']; ?>" data-position_name="<?= $v['position_name']; ?>">
                                                            <i class="fa fa-info-circle fa-2x link-primary"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <?php if($v['status'] == FALSE): ?>
                                                            <a href="<?= base_url('survey/f360survey/'); ?>?nik=<?= $v['nik']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                                        <?php else: ?>
                                                            <i class="fa fa-check-circle fa-2x text-success"></i>    
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                </div>
            <?php endif; ?><!-- /table data atasan -->

            <!-- table data peers -->
            <?php if(!empty($data_peers)): ?>
                <div class="row">
                    <div class="col-12 p-0">
                        <div class="card card-orange">
                            <div class="card-header">
                                <h3 class="card-title">Peers</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="th-f360-80">Employee Name</th>
                                            <th class="th-f360-btn"></th>
                                            <th class="th-f360-btn"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data_peers as $v): ?>
                                            <tr>
                                                <td><?= $v['emp_name']; ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="#" class="btnEmployeInfo" data-nik="<?= $v['nik']; ?>" data-emp_name="<?= $v['emp_name']; ?>" data-departemen="<?= $v['departemen']; ?>" data-divisi="<?= $v['divisi']; ?>" data-position_name="<?= $v['position_name']; ?>">
                                                            <i class="fa fa-info-circle fa-2x link-primary"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <?php if($v['status'] == FALSE): ?>
                                                            <a href="<?= base_url('survey/f360survey/'); ?>?nik=<?= $v['nik']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                                        <?php else: ?>
                                                            <i class="fa fa-check-circle fa-2x text-success"></i>    
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                </div>
            <?php endif; ?><!-- /table data peers -->

            <!-- table data other peers -->
            <?php if(!empty($data_other_function)): ?>
                <div class="row">
                    <div class="col-12 p-0">
                        <div class="card card-yellow">
                            <div class="card-header">
                                <h3 class="card-title">Peers Other Function</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="th-f360-80">Employee Name</th>
                                            <th class="th-f360-btn"></th>
                                            <th class="th-f360-btn"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- jika ada data survey yang sudah selesai -->
                                        <?php $x=1; ?>
                                        <?php if(!empty($data_complete_of)): ?>
                                            <?php foreach($data_complete_of as $k => $v): ?>
                                                <tr>
                                                    <td><?= $v['emp_name']; ?></td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a id="btnEmployeInfo_mobile_<?= $x; ?>" href="#" class="btnEmployeInfo" data-nik="<?= $v['nik']; ?>" data-emp_name="<?= $v['emp_name']; ?>" data-departemen="<?= $v['departemen']; ?>" data-divisi="<?= $v['divisi']; ?>" data-position_name="<?= $v['position_name']; ?>">
                                                                <i class="fa fa-info-circle fa-2x link-primary"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="justify-content-center">
                                                            <i class="fa fa-check-circle fa-2x text-success"></i>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $x++; ?>
                                            <?php endforeach;?>
                                        <?php endif; ?>
                                        <?php if(!empty($data_other_function_penilaian)): ?>
                                            <?php foreach($data_other_function_penilaian as $k => $v): ?>
                                                <tr>
                                                    <td><?= $v['emp_name']; ?></td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a id="btnEmployeInfo_mobile_<?= $x; ?>" href="#" class="btnEmployeInfo" data-nik="<?= $v['nik']; ?>" data-emp_name="<?= $v['emp_name']; ?>" data-departemen="<?= $v['departemen']; ?>" data-divisi="<?= $v['divisi']; ?>" data-position_name="<?= $v['position_name']; ?>">
                                                                <i class="fa fa-info-circle fa-2x link-primary"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="justify-content-center">
                                                            <a href="<?= base_url('survey/f360survey?nik=').$v['nik']; ?>" id="href_mobile_<?= $x; ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $x++; ?>
                                            <?php endforeach;?>
                                        <?php endif; ?>
                                        <!-- options -->
                                        <?php if(!empty($data_notyet_of)): ?>
                                            <?php $y=1; ?>
                                            <?php for($x; $x<=$max_feedback_other_peers; $x++){ ?>
                                                <tr>
                                                    <td>
                                                        <div class="input-group-sm">
                                                            <select class="custom-select" id="f360getEmployeMobile_<?= $x; ?>">
                                                                <?php if($y==1): ?>
                                                                    <option selected>Choose...</option>
                                                                    <?php foreach($data_notyet_of as $v): ?>
                                                                        <option value="<?= $v['nik']; ?>"><?= $v['departemen'].' | '.$v['emp_name']; ?></option>
                                                                    <?php endforeach;?>
                                                                <?php else: ?>
                                                                    <option selected>Please choose the top selection first...</option>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a id="btnEmployeInfo_mobile_<?= $x; ?>" href="#" class="invisible btnEmployeInfo">
                                                                <i class="fa fa-info-circle fa-2x link-primary"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="justify-content-center">
                                                            <a href="#" id="href_mobile_<?= $x; ?>" class="invisible btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>                                                </div>
                                                    </td>
                                                </tr>
                                                <?php $y++; ?>
                                            <?php } ?>
                                        <?php endif; ?><!-- /options -->
                                    </tbody>
                                </table>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                </div>
            <?php endif; ?><!-- /table data other function -->
        </div>
    </div>
</div><!-- /mobile table cards -->

<!--/* -------------------------------------------------------------------------- */
    /*                                   MODALS                                   */
    /* -------------------------------------------------------------------------- */ -->
<!-- Modal Employe Info -->
<div class="modal fade" id="modalEmployeInfo" tabindex="-1" role="dialog" aria-labelledby="modalEmployeInfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEmployeInfoLabel">employe_data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- profile photo -->
                <div class="row justify-content-center mb-3">
                    <div class="col-xs-auto">
                        <i class="fa fa-user-circle fa-4x"></i>
                    </div>
                </div><!-- // profile photo -->
                <div class="row justify-content-center">
                    <div class="col-md-auto">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>NIK</td><td>:</td><td id="infoModal_nik"></td>
                                </tr>
                                <tr>
                                    <td>Division</td><td>:</td><td id="infoModal_divisi"></td>
                                </tr>
                                <tr>
                                    <td>Departement</td><td>:</td><td id="infoModal_departemen"></td>
                                </tr>
                                <tr>
                                    <td>Position</td><td>:</td><td id="infoModal_position"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  