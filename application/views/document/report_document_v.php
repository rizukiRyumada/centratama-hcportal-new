<div class="row">
    <div class="col-auto my-1">
        <form action="">
            <select class="custom-select mr-sm-2" id="jenis-surat" name="jenis">
                <option value="">All</option>
                <?php 
                $role_id = $this->session->userdata('akses_surat_id');
                $querySurat = "SELECT `document_jenis`.`id`,`jenis_surat`
                FROM `document_jenis`
                JOIN `document_access` ON `document_jenis`.`id` = `document_access`.`surat_id`
                WHERE `document_access`.`role_surat_id` = $role_id";
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

<!-- Document Viewer Modal -->
<div class="modal fade" id="attachFile" tabindex="-1" role="dialog" aria-labelledby="attachFileLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="attachFileLabel">Document Preview</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="AttachmentForm" role="form" action="<?= base_url('document/attachDocument'); ?>" method="POST" enctype="multipart/form-data">
          <div id="fileViewer"></div>
          
          <!-- no_surat buat nama file -->
          <input id="noSurat" type="hidden" name="no_surat" value="">

          <div class="form-group">
            <label for="">Rules:</label>
            <ul>
              <li>File Types allowed : pdf, doc, docx, jpeg, jpg</li>
              <li>Maximum file size : <span class="font-weight-semibold text-danger">1MB (1024 kB)</span></li>
            </ul>
          </div>
          
          <div class="form-group">
            <label for="exampleInputFile">Upload or change Document Files:</label>
            <div class="input-group">
              <div class="custom-file">
                <input type="file" name="document_attach" class="custom-file-input" id="exampleInputFile">
                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
              </div>
              <div class="input-group-append">
                <button id="submitAttachmentForm" type="button" class="btn btn-primary" id=""><i class="fas fa-file-upload"></i> Upload</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#fileViewer">
  Launch demo modalswadwq
</button> -->

<!-- Modal -->
<!-- <div class="modal fade" id="fileViewer" tabindex="-1" role="dialog" aria-labelledby="fileViewerLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header py-1">
        <h5 class="modal-title" id="fileViewerLabel">File Viewer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="height: 85vh;"> -->
        <!-- PDF viewer -->
        <!-- <object data='https://www.irs.gov/pub/irs-pdf/fw4.pdf' 
                type='application/pdf' 
                width='100%' 
                height='100%'>
        <p>This browser does not support inline PDFs. Please download the PDF to view it: <a href="https://www.irs.gov/pub/irs-pdf/fw4.pdf">Download PDF</a></p>
        </object> -->

        <!-- pdf id -->
        <!-- <div id="pdf_viewer"></div> -->

        <!-- <object data="https://www.irs.gov/pub/irs-pdf/fw4.pdf" type="application/pdf" width="100%" height="100%">
          <iframe src="https://www.irs.gov/pub/irs-pdf/fw4.pdf" style="border: none;" width="100%" height="100%">
            This browser does not support PDFs. Please download the PDF to view it: 
            <a href="https://www.irs.gov/pub/irs-pdf/fw4.pdf">Download PDF</a>
          </iframe>
        </object> -->

        <!-- Change Document -->
      <!-- </div>
    </div>
  </div>
</div> -->