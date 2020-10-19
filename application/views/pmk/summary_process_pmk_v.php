<div class="row">
    <div class="col">
        <div class="card ">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Division</b> <a id="division" class="float-right"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Created</b> <a id="created" class="float-right"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Modified</b> <a id="modified" class="float-right"></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 align-self-center">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Status</b> <a id="status" class="float-right"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Month</b> <a id="bulan" class="float-right"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Year</b> <a id="tahun" class="float-right"></a>
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
                            <th>Division</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Employee Name</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>