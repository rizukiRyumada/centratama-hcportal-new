<!-- information section -->
<div class="row mb-3">
    <!-- <div class="col-md-2 d-md-inline-block d-none">
        <img src="http://localhost:82/assets/img/illustration/contract.svg" alt="" class="responsive-image">
    </div> -->
    <div class="col-md-2 d-md-inline-block d-none">
        <img src="http://localhost:82/assets/img/illustration/summary.svg" alt="" class="responsive-image">
    </div>
    <div class="col-md-10">
        <div class="row h-100">
            <div class="col align-self-center">
                <p class="text m-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit, rem amet, ut quia necessitatibus vel, obcaecati maiores natus doloribus aliquid rerum voluptates saepe. Enim commodi, nesciunt laudantium deserunt veniam quod?</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col">
        <div class="card ">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="row h-100">
                            <div class="col align-self-center">
                                <div>
                                <div class="text-center">
                                    <i class="fa fa-users fa-5x text-<?= $divisi['color']; ?>"></i>

                                    <!-- <img class="profile-user-img img-fluid img-circle"
                                    src="../../dist/img/user4-128x128.jpg"
                                    alt="User profile picture"> -->
                                </div>
                                <!-- <h3 class="profile-username text-center"><?= $divisi['division']; ?></h3> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8 align-self-center">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Division Name</b> <a class="float-right"><?= $divisi['division']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Division Head</b> <a class="float-right"><?= $divisi['divhead_name']; ?></a>
                            </li>
                            <!-- <li class="list-group-item">
                                <b>Division</b> <a class="float-right"><?= $employee['divisi']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Department</b> <a class="float-right"><?= $employee['departemen']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Position</b> <a class="float-right"><?= $employee['position_name']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Start of Contract</b> <a class="float-right"><?= date("d-m-o", $contract['date_start']); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>End of Contract</b> <a class="float-right"><?= date("d-m-o", $contract['date_end']); ?></a>
                            </li> -->
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
            <div class="overlay"><img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80"></div>
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="switch-data nav-link active" data-toggle="pill" href="javascript:void(0)" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true" data-switch="0" ><i class="fas fa-clipboard-list"></i> My Task</a>
                    </li>
                    <li class="nav-item">
                        <a class="switch-data nav-link " data-toggle="pill" href="javascript:void(0)" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false" data-switch="1" ><i class="fas fa-history"></i> History</a>
                    </li>
                </ul>
            </div>
            <div class="card-body table-responsive">

                <!-- filter table -->
                <div id="filterTools" class="row justify-content-end" style="display: none;">
                    <div id="statusChooser" class="col-lg-4 col-sm-6" >
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select id="status" class="custom-select form-control form-control-sm">
                                <option value="">All</option>
                                <?php foreach($status_summary as $v): ?>
                                    <option value="<?= $v['id']; ?>"><?= $v['name_text']; ?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div id="daterangeChooser" class="col-lg-4 col-sm-6" >
                        <div class="form-group">
                            <label for="daterange">Pick a daterange:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input class="form-control" type="text" name="dateChooser" id="daterange" value="<?= date('m/01/o', strtotime("-2 month", time())) ?> - <?= date('m/t/o', strtotime("+2 month", time())); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="buttonResetFilter" class="row justify-content-end" style="display: none;">
                    <div class="col-sm-2">
                        <button id="resetFilterAsses" class="btn btn-danger w-100"><i class="fa fa-filter fa-rotate-180"></i> Reset</button>
                    </div>
                </div><!-- /filter table -->

                <hr id="filter_divider" style="display: none;"/>

                <table id="table_indexSummary" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Modified</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>