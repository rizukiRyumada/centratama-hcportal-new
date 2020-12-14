<!-- modal pesan revisi -->
<!-- Button trigger modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#pesanRevisi">
    Launch demo modal
</button> -->
<!-- Modal -->
<div class="modal fade" id="pesanKomentar" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="pesanKomentarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="pesanKomentarLabel">Input a Comment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <!-- revise to container -->
            <div id="container_reviseto" style="display: none;">
                <div class="form-group">
                    <p class="m-0">Address revise to...</p>
                </div>
                <div class="form-group">
                    <!-- <label>Minimal</label> -->
                    <select class="form-control select2" style="width: 100%;" name="revise_to">
                        <!-- <option value="" selected="selected">Choose to who?</option> -->
                        <optgroup label="Revise to who?">
                            <?php if(!empty($status_before)): ?>
                                <?php foreach($status_before as $v): ?>
                                    <option value="<?= $v['id']; ?>"><?= $v['pic_name']; ?></option>
                                <?php endforeach;?>
                            <?php endif; ?>
                        </optgroup>
                    </select>
                </div>
            </div>
            <!-- <p class="text">Please input a message.</p> -->
            <textarea id="textareaPesanKomentar" class="ckeditor" name="pesan_komentar" id="" cols="30" rows="10"></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
            <button id="submitPesanKomentar" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Save changes</button>
        </div>
        </div>
    </div>
</div>