<!-- Main Script -->
<script>
    $(document).ready(function() {
        nTable = $('#tableNomor').DataTable({
            responsive: true,
            "autoWidth" : true,
            "processing" : true,
            "language" : { processing: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>'},
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('document/ajax_no') ?>",
                "type": "post",
                "data": function(data){
                    data.jenis_surat = $('#jenis-surat').val();
                },
                complete: (data) => {
                    console.log(data.responseJSON);
                }
            },
            "columnDefs": [
                { "targets": [0], "orderable": true },
                { "targets": [1], "orderable": true },
                { "targets": [2], "orderable": true },
                { "targets": [3], "orderable": true },
                { "targets": [4], "orderable": true },
                { "targets": [5], "orderable": true },
                {
                    classNmae: "",
                    // data: 'status',
                    targets: [6],
                    render: (data, type) => {
                        // if(type === 'display'){
                        //     var status = ''; // status name
                        //     var cssClass = ''; // class name

                        //     switch(data) {
                        //         case '0':
                        //             status = 'Sick';
                        //             cssClass = 'text-danger';
                        //             break;
                        //         case '1':
                        //             status = "Healthy";
                        //             cssClass = 'text-success';
                        //             break;
                        //     }
                        //     return '<p class="m-0 font-weight-bold text-center '+cssClass+'">'+status+'</p>';
                        //     // return status;
                        // }
                        // return data;

                        if(data.file_name != ""){
                            return '<button class="btn btn-primary w-100 triggerOpenFile" data-no_surat="'+data.no_surat+'" data-file_name="'+data.file_name+'" data-file_type="'+data.file_type+'" ><i class="fas fa-file" ></i></button>';
                        } else {
                            return '<button class="btn btn-secondary w-100 triggerAttachFile" title="Attach file to this Document." data-no_surat="'+data.no_surat+'" ><i class="fa fa-file-upload" ></i></button>';
                        }

                        return data;
                    }
                }
            ]
        });

        // jenis surat filter
        $('#jenis-surat').change(function(){
            nTable.ajax.reload();
        });

        // submit form attachment
        $('#submitAttachmentForm').on('click', function() {
            Swal.fire({
                icon: 'info',
                title: 'Uploading files',
                html: '<p>The files is being uploaded please wait.<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            });

            $('#AttachmentForm').submit();
        });

        // upload document file trigger
        $('#tableNomor').DataTable().on('click', '.triggerAttachFile', function() {
            let no_surat = $(this).data('no_surat');

            // hapus semua elemen di dalem file viewer
            let box = $('#fileViewer');
            box.empty();

            // set nomor surat dan tampilkan modal
            $('#noSurat').val(no_surat);
            $('#attachFile').modal('show');
        });
        // document viewer trigger
        $('#tableNomor').DataTable().on('click', '.triggerOpenFile', function() {
            let no_surat = $(this).data('no_surat');
            // buat nama file dan ambil nama file
            let file = $(this).data('file_name')+'.'+$(this).data('file_type');
            let file_name = $(this).data('file_name');
            let file_type = $(this).data('file_type');

            // hapus semua elemen di dalem file viewer
            let box = $('#fileViewer');
            // while (box.firstChild) {box.removeChild(box.firstChild);}
            box.empty();

            if(file_type == 'pdf'){
                // box.append('<object data="<?= base_url('assets/document/surat/'); ?>'+file+'" type="application/pdf" width="100%" style="height: 85vh"><p>This browser does not support inline PDFs. Please download the PDF to view it: <a href="<?= base_url('assets/document/surat/'); ?>'+file+'">Download PDF</a></p></object>');

                let pdfURL = '<?= base_url('assets/document/surat/'); ?>'+file;

                let options = {
                    pdfOpenParams: {
                        navpanes: 0,
                        toolbar: 0,
                        statusbar: 0,
                        view: "FitV"
                    }
                };

                box.append('<div id="pdfViewer" style="width: 100%; height: 85vh;"></div>');
                PDFObject.embed(pdfURL, '#pdfViewer', options);
            } else {
                box.append('<img src="<?= base_url('assets/document/surat/'); ?>'+file+'" alt="'+file_name+' document file" style="width: 100%; height: auto;" >');
            }

            // set nomor surat dan tampilkan modal
            $('#noSurat').val(no_surat);
            $('#attachFile').modal('show');

            console.log(file);
            console.log(file_type);
        });
    });

    $(document).ready(function() {
        $("#jenis").change(function() {
            var id = $(this).val();

            $.ajax({
                url: "<?= base_url('document/getSub') ?>",
                method: "POST",
                data: {
                    jenis: id
                },
                async: true,
                dataType: "json",
                success: function(data) {
                    var html = "<option value=''>- Subjenis Surat -</option>";
                    var i;
                    for (i = 0; i < data.length; i++) {
                        html +=
                            "<option value=" +
                            data[i].tipe_surat +
                            ">" +
                            data[i].tipe_surat +
                            "</option>";
                    }
                    $("#tipe").html(html);
                }
            });
            return false;
        });
    });

    $(document).ready(function() {
        $("#entity").change(function() {
            var entity = $("#entity").val();
            var jenis = $("#jenis").val();
            var sub = $("#tipe").val();
            var isi = "";

            $.ajax({
                url: "<?= base_url('document/lihatnomor') ?>",
                method: "POST",
                data: {
                    jenis : jenis,
                    entity: entity,
                    sub: sub
                },
                async: true,
                dataType: "json",
                success: function(data) {
                    isi =
                        data.no +
                        "/" +
                        data.entity +
                        "-HC/" +
                        data.sub +
                        "/" +
                        data.bulan +
                        "/" +
                        data.tahun;
                    $(".hasil").val(isi);
                }
            });
        });
    });
</script>

<!-- Validation Script -->
<script src="/assets/vendor/node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="/assets/vendor/node_modules/jquery-validation/dist/additional-methods.min.js"></script>
<script>
    $('#suratForm').validate({
        rules: {
            no: {
                required: true,
            },
            perihal: {
                required: true,
            }
        },
        messages: {
            no: {
                required: "Please generate the Document Number by Choosing The Type of Document, Sub Type, and Entity.",
            },
            perihal: {
                required: "Please enter the Perihal.",
            }
        },
        errorElement: 'span',
        errorClass: 'text-right pr-2',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });

    // javascript validator for document attach upload
    // $('#formid').validate({
    //     rules: { 
    //         document_attach: { 
    //             required: true, 
    //             extension: "png|jpe?g|gif", 
    //             filesize: 1048576  
    //         }
    //     },
    //     messages: { 
    //         document_attach: "File must be JPG, GIF or PNG, less than 1MB" }
    // });
</script>

