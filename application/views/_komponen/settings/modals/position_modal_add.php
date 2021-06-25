<?php // modal tambah posisi ?>
<div class="modal fade" id="modal_tambah_posisi" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modal_tambah_posisiLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_tambah_posisiLabel">Tambah Posisi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- position name -->
        <div class="row">
          <div class="col form-group">
            <label for="position_name_tambah">Position Name</label>
            <input name="position_name" type="text" class="form-control" id="position_name_tambah" placeholder="Enter Position Name" maxlength="8" required>
          </div>
        </div>
        <!-- divisi department -->
        <div class="row">
          <div class="col-lg-6 form-group">
            <label for="divisi_tambah">Division</label>
            <select name="divisi" id="divisi_tambah" class="div custom-select form-control form-control-sm">
              <option value="0">Pilih Divisi...</option>
              <?php foreach ($divisi as $v) : ?>
                <option value="div-<?= $v['id'] ?>"><?= $v['division'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-6 form-group">
            <label for="department_tambah">Department</label>
            <select name="departement" id="department_tambah" class="custom-select form-control form-control-sm" disabled>
              <option value="">Please choose Division first...</option>
            </select>
          </div>
        </div>
        <!-- assistant is_head mpp -->
        <div class="row">
          <div class="col-lg-4 form-group">
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="customSwitch1">
              <label class="custom-control-label" for="customSwitch1">Is Assistant?</label>
            </div>
          </div>
          <div class="col-lg-4 form-group">
            <label for="isHead_tambah">Is Head</label>
            <select name="is_head" id="isHead_tambah" class="custom-select form-control">
              <option value="">Pilih Is Head...</option>
              <option value="0">Employee</option>
              <option value="1">Division Head</option>
              <option value="2">Department Head</option>
            </select>
          </div>
          <div class="col-lg-4 form-group">
            <label for="mpp_tambah">MPP</label>
            <input type="number" name="mpp" id="mpp_tambah" class="form-control">
          </div>
        </div>
        <!-- hirarki_org job_grade -->
        <div class="row">
          <div class="col-lg-6 form-group">
            <label for="hirarkiOrg_tambah">Hirarki Org</label>
            <select name="hirarki_org" id="hirarkiOrg_tambah" class="custom-select form-control">
              <option value="">Pilih Hirarki Org...</option>
              <option value="N">N</option>
              <option value="N-1">N-1</option>
              <option value="N-2">N-2</option>
              <option value="N-3">N-3</option>
              <option value="N-4">N-4</option>
            </select>
          </div>
          <div class="col-lg-6 form-group">
            <label for="jobGrade_tambah">Job Grade</label>
            <select name="job_grade" id="jobGrade_tambah" class="custom-select form-control">
              <option value="">Pilih Job Grade...</option>
              <?php foreach ($master_level as $v) : ?>
                <option value="<?= $v['id']; ?>"><?= $v['id']; ?> - <?= $v['name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <!-- atasan 1 -->
        <div class="row bg-gray mb-3">
          <div class="col py-2">
            <h5 class="font-weight-bold m-0">Atasan 1</h5>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 form-group">
            <label for="divisi_atasan1_tambah">Division</label>
            <select name="divisi_atasan1" id="divisi_atasan1_tambah" class="div custom-select form-control form-control-sm">
              <option value="0">Pilih Divisi...</option>
              <?php foreach ($divisi as $v) : ?>
                <option value="div-<?= $v['id'] ?>"><?= $v['division'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-4 form-group">
            <label for="department_atasan1_tambah">Department</label>
            <select name="department_atasan1" id="department_atasan1_tambah" class="custom-select form-control form-control-sm" disabled>
              <option value="">Please choose Division first...</option>
            </select>
          </div>
          <div class="col-lg-4 form-group">
            <label for="position_atasan1_tambah">Position</label>
            <input name="position_atasan1" type="text" class="form-control" id="position_atasan1_tambah" placeholder="Enter a NIK" maxlength="8" required>
          </div>
        </div>
        <!-- atasan 2 -->
        <div class="row bg-gray mb-3">
          <div class="col py-2">
            <h5 class="font-weight-bold m-0">Atasan 2</h5>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 form-group">
            <label for="divisi_atasan2_tambah">Division</label>
            <select name="divisi_atasan2" id="divisi_atasan2_tambah" class="div custom-select form-control form-control-sm">
              <option value="0">Pilih Divisi...</option>
              <?php foreach ($divisi as $v) : ?>
                <option value="div-<?= $v['id'] ?>"><?= $v['division'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-4 form-group">
            <label for="department_atasan2_tambah">Department</label>
            <select name="department_atasan2" id="department_atasan2_tambah" class="custom-select form-control form-control-sm" disabled>
              <option value="">Please choose Division first...</option>
            </select>
          </div>
          <div class="col-lg-4 form-group">
            <label for="position_atasan2_tambah">Position</label>
            <input name="position_atasan2" type="text" class="form-control" id="position_atasan2_tambah" placeholder="Enter a NIK" maxlength="8" required>
          </div>
        </div>
        <!-- approver 1 -->
        <div class="row bg-gray-light mb-3">
          <div class="col py-2">
            <h5 class="font-weight-bold m-0">Approver 1</h5>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 form-group">
            <label for="divisi_approver1_tambah">Division</label>
            <select name="divisi_approver1" id="divisi_approver1_tambah" class="div custom-select form-control form-control-sm">
              <option value="0">Pilih Divisi...</option>
              <?php foreach ($divisi as $v) : ?>
                <option value="div-<?= $v['id'] ?>"><?= $v['division'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-4 form-group">
            <label for="department_approver1_tambah">Department</label>
            <select name="department_approver1" id="department_approver1_tambah" class="custom-select form-control form-control-sm" disabled>
              <option value="">Please choose Division first...</option>
            </select>
          </div>
          <div class="col-lg-4 form-group">
            <label for="position_approver1_tambah">Position</label>
            <input name="position_approver1" type="text" class="form-control" id="position_approver1_tambah" placeholder="Enter a NIK" maxlength="8" required>
          </div>
        </div>
        <!-- approver 2 -->
        <div class="row bg-gray-light mb-3">
          <div class="col py-2">
            <h5 class="font-weight-bold m-0">Approver 2</h5>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 form-group">
            <label for="divisi_approver2_tambah">Division</label>
            <select name="divisi_approver2" id="divisi_approver2_tambah" class="div custom-select form-control form-control-sm">
              <option value="0">Pilih Divisi...</option>
              <?php foreach ($divisi as $v) : ?>
                <option value="div-<?= $v['id'] ?>"><?= $v['division'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-lg-4 form-group">
            <label for="department_approver2_tambah">Department</label>
            <select name="department_approver2" id="department_approver2_tambah" class="custom-select form-control form-control-sm" disabled>
              <option value="">Please choose Division first...</option>
            </select>
          </div>
          <div class="col-lg-4 form-group">
            <label for="position_approver2_tambah">Position</label>
            <input name="position_approver2" type="text" class="form-control" id="position_approver2_tambah" placeholder="Enter a NIK" maxlength="8" required>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div>
    </div>
  </div>
</div>