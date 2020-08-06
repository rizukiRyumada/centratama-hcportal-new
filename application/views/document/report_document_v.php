<div class="row">
    <div class="col-auto my-1">
        <form action="">
            <select class="custom-select mr-sm-2" id="jenis-surat" name="jenis">
                <option value="">All</option>
                <?php 
                $role_id = $this->session->userdata('akses_surat_id');
                $querySurat = "SELECT `jenis_surat`.`id`,`jenis_surat`
                FROM `jenis_surat`
                JOIN `user_access_surat` ON `jenis_surat`.`id` = `user_access_surat`.`surat_id`
                WHERE `user_access_surat`.`role_surat_id` = $role_id";
                $jenis = $this->db->query($querySurat)->result_array();
                ?>
                <?php foreach ($jenis as $j) : ?>
                <option value="<?= $j['id']; ?>"><?= $j['jenis_surat']; ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-xl col-lg">
        <div class="card mb-2 shadow-lg">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="tableNomor">
                        <thead>
                            <tr>
                                <th>No. Surat</th>
                                <th>Perihal</th>
                                <th>PIC</th>
                                <th>Tanggal</th>
                                <th>Note</th>
                                <th>Tipe Surat</th>
                                <th>File</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fileUploader">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="fileUploader" tabindex="-1" role="dialog" aria-labelledby="fileUploaderLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileUploaderLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form" action="">
            
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-success" data-toggle="modal" data-target="#fileViewer">
  Launch demo modalswadwq
</button>

<!-- Modal -->
<div class="modal fade" id="fileViewer" tabindex="-1" role="dialog" aria-labelledby="fileViewerLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fileViewerLabel">File Viewer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- insert in the document body -->
        <object data='https://www.irs.gov/pub/irs-pdf/fw4.pdf' 
                type='application/pdf' 
                width='100%' 
                height='100%'>
        <p>This browser does not support inline PDFs. Please download the PDF to view it: <a href="https://www.irs.gov/pub/irs-pdf/fw4.pdf">Download PDF</a></p>
        </object>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>