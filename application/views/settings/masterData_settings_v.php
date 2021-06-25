<!-- /* -------------------------------------------------------------------------- */
     /*                                  main view                                 */
     /* -------------------------------------------------------------------------- */ -->
<div class="row mb-1">
    <div class="col">
        <div class="card">
            <div class="card-body pt-0">
                <div class="row mt-3">
                    <div class="col-12">
                        <h5 class="mb-0 font-weight-bold">Master Employee Management</h5>
                        <p class="font-weight-light">Add, edit, or remove master Employee Data.</p>
                        <a href="<?= base_url('settings/masterData_employee'); ?>" class="btn btn-primary"><i class="fas fa-users-cog"></i> Manage</a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h5 class="mb-0 font-weight-bold">Master Position Management</h5>
                        <p class="font-weight-light">Add, edit, or remove master Position Data.</p>
                        <!-- <a href="<?= base_url('maintenance'); ?>" class="btn btn-success"><i class="fas fa-sitemap"></i> Manage Position</a> -->
                        <a href="<?= base_url('settings/masterData_position'); ?>" class="btn btn-primary"><i class="fas fa-sitemap"></i> Manage</a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h5 class="mb-0 font-weight-bold">Master Division Management</h5>
                        <p class="font-weight-light">Add, edit, or remove master Division Data.</p>
                        <a href="<?= base_url('maintenance'); ?>" class="btn btn-primary"><i class="fas fa-users"></i> Manage</a>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h5 class="mb-0 font-weight-bold">Master Department Management</h5>
                        <p class="font-weight-light">Add, edit, or remove master Department Data.</p>
                        <a href="<?= base_url('maintenance'); ?>" class="btn btn-primary"><i class="fas fa-users"></i> Manage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>