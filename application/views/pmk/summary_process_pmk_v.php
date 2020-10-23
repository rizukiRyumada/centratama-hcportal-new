<div class="row">
    <div class="col">
        <div class="card ">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-2">
                        <a href="<?= base_url('pmk'); ?>" class="btn btn-primary"><i class="fas fa-chevron-left"></i></a>
                    </div>
                </div>
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
                            <th><span id="pa1_score"></span><br/>Score</th>
                            <th><span id="pa1_rating"></span><br/>Rating</th>
                            <th><span id="pa2_score"></span><br/>Score</th>
                            <th><span id="pa2_rating"></span><br/>Rating</th>
                            <th><span id="pa3_score"></span><br/>Score</th>
                            <th><span id="pa3_rating"></span><br/>Rating</th>
                            <th>Status</th>
                            <th>Summary</th>
                            <th>Choose New Entity</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row"></div>
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-end">
                    <div class="col-lg-4">
                        <div class="btn-group w-100">
                            <button id="button_submit" class="btn btn-lg btn-success"><i class="fas fa-paper-plane"></i> Submit</button>
                            <button id="button_save" class="btn btn-lg btn-warning"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>