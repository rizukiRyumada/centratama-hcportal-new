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
            <h5 class="modal-title" id="pesanKomentarLabel">Input a Message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p class="text">Please input a message to be revised.</p>
            <textarea id="textareaPesanKomentar" class="ckeditor" name="pesan_komentar" id="" cols="30" rows="10"></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button id="submitPesanKomentar" type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
</div>