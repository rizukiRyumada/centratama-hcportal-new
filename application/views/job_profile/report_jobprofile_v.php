    <div class="card shadow mb-4">
        <div class="card-header bg-gray-light">
            <?php if($jp_user['role_id']==1 || $jp_user['position_id']==1 || $userApp_admin == 1): ?>
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label for="divisi">Division:</label>
                            <select id="divisi" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <?php foreach($divisi as $v): ?>
                                    <option value="div-<?= $v['id'] ?>"><?= $v['division'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label for="departement">Departement:</label>
                            <select id="departement" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <?php foreach($dept as $v): ?>
                                    <option value="dept-<?= $v['id'] ?>"><?= $v['nama_departemen'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select id="status" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <option value="1">Not Yet Submitted</option>
                                <option value="2">Submitted</option>
                                <option value="3">First Approval</option>
                                <option value="4">Need Revised</option>
                                <option value="5">Final Approval</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php elseif($hirarki_org=="N"): ?>
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label for="departement">Departement :</label>
                            <select id="departement" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <?php foreach($dept as $v): ?>
                                    <option value="dept-<?= $v['id'] ?>"><?= $v['nama_departemen'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label for="status">Status :</label>
                            <select id="status" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <option value="1">Not Yet Submitted</option>
                                <option value="2">Submitted</option>
                                <option value="3">First Approval</option>
                                <option value="4">Need Revised</option>
                                <option value="5">Final Approval</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label for="status">Status :</label>
                            <select id="status" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <option value="1">Not Yet Submitted</option>
                                <option value="2">Submitted</option>
                                <option value="3">First Approval</option>
                                <option value="4">Need Revised</option>
                                <option value="5">Final Approval</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="myTask" width="100%">
                    <thead>
                        <tr class="text-center">
                            <th class="align-middle" >Division</th>
                            <th class="align-middle" >Department</th>
                            <th class="align-middle" >Position</th>
                            <th class="align-middle" >Employee Name</th>
                            <th class="align-middle" >Status</th>
                            <!-- <th class="align-middle"  style="min-width: 45px;" ></th> -->
                            <?php if($jp_user['role_id'] == 1): ?>
                                <th class="align-middle">Verify OD</th>
                            <?php endif; ?>
                            <th class="align-middle" >View Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($approval_data as$v): ?>
                        <tr id="myTask-list">
                            <td data-filter="div-<?= $v['id_div'] ?>"><?= $v['divisi'] ?></td>
                            <td data-filter="dept-<?= $v['id_dept'] ?>"><?= $v['departement'] ?></td>
                            <td><?= $v['posisi'] ?></td>
                            <td><?= $v['emp_name'] ?></td>
                            <?php if($v['status_approval'] == 0): ?>
                                <td  class="align-middle" data-filter="1">
                                    <span class="w-100 badge badge-danger">Not Yet Submitted</span>
                                </td>
                            <?php elseif($v['status_approval'] == 1): ?>
                                <td  class="align-middle" data-filter="2">
                                    <span class="w-100 badge badge-warning">Submitted</span>
                                </td>
                            <?php elseif($v['status_approval'] == 2): ?>
                                <td  class="align-middle" data-filter="3">
                                    <span class="w-100 badge badge-warning">First Approval</span>
                                </td>
                            <?php elseif($v['status_approval'] == 3): ?>
                                <td  class="align-middle" data-filter="4">
                                    <span class="w-100 badge badge-danger">Need Revised</span>
                                </td>
                            <?php elseif($v['status_approval'] == 4): ?>
                                <td  class="align-middle" data-filter="5">
                                    <span class="w-100 badge badge-success">Final Approval</span>
                                </td>
                            <?php endif; ?>
                            <?php if($jp_user['role_id'] == 1): ?>
                                <td class="align-middle">
                                    <div class="container h-100 m-0 px-auto"> <!-- this container make the element to vertically and horizontally centered -->
                                        <div class="row justify-content-center align-self-center w-100 m-0">
                                            <?php if(!empty($v['verify']) || $v['verify'] != 0): ?>
                                                <i class="fa fa-check-circle text-success fa-2x"></i>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            <?php endif; ?>
                            <td class="align-middle">
                                <div class="container h-100 m-0 px-auto"> <!-- this container make the element to vertically and horizontally centered -->
                                    <div class="row justify-content-center align-self-center w-100 m-0">
                                        <a class="btn btn-primary w-100" id="myTask-button" href="<?= base_url('job_profile/reportjp'); ?>?task=<?= $v['nik'] ?>&id=<?= $v['id_posisi']; ?>&status=<?= $v['status_approval'] ?>" title="View Details"><i class="fa fa-search mx-auto"></i></a>    
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- <div class="table-responsive">
                <table class="table table-boredered" id="history" width="100%">
                    <thead>
                        <tr>
                            <th>Division</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Employee Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div> -->
        </div>
    </div>