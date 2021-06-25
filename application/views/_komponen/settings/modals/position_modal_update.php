<?php //modal update 
?>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Position Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <p class="text-sm text-secondary text-bold mb-0">Current Data</p>
          <hr class="mt-0 mb-1">
          <p class="text-sm text-secondary">
            Table Name: position_2021_01
            <br />
            Updated at: 22/11/2021
          </p>
          <hr class="mt-0">
        </div>
        <div>
          <div class="form-group">
            <label for="templateData">Template Data</label>
            <div>
              <button type="submit" class="btn btn-success"><i class="fa fa-download"></i> Template file (.csv)</button>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>