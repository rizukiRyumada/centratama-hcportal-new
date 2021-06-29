<?php //modal update 
?>
<div class="modal fade" id="modalUpdatePosition" tabindex="-1" aria-labelledby="modalUpdatePositionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUpdatePositionLabel">Update Position Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
          <div class="form-group">
            <label for="templateData">Template Data</label>
            <div>
              <a href="<?= base_url('settings/ajax_downloadPositionData') ?>" id="downloadCsvData" type="submit" class="btn btn-success"><i class="fa fa-download"></i> Template file (.csv)</a>
            </div>
            <small class="form-text text-muted">Download the file above, fill the new position data, and then upload it by click the button below.</small>
          </div>
          <form>
            <div class="form-group">
              <label for="positionData">Upload a new Position Data</label>
              <small class="form-text text-danger">Please select <b>.csv</b> file</small>
              <small class="form-text text-muted mb-1">This will update master_position to HC Portal.</small>
              <div id="fileuploader"></div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button id="updateToNewPosition" type="button" class="btn btn-secondary"><i id="icon_updateToNewPosition" class="fa fa-sync-alt"></i> Update</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>