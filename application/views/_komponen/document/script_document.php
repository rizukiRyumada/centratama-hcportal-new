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

        // order berdasarkan tanggal descending
        $('#basicTable').DataTable().order([2, "desc"]).draw();

        // jenis surat filter
        $('#jenis-surat').change(function(){
            nTable.ajax.reload();
        });

        // submit form attachment
        $('#submitAttachmentForm').on('click', function() {
            Swal.fire({
                icon: 'info',
                title: 'Uploading file...',
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
            let file_url = '<?= base_url('assets/document/surat/'); ?>'+file

            // hapus semua elemen di dalem file viewer
            let box = $('#fileViewer');
            // while (box.firstChild) {box.removeChild(box.firstChild);}
            box.empty();
            // tambahkan bar nav
            box.append('<nav class="navbar navbar-expand-lg navbar-light bg-light"><button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse" id="navbarSupportedContent"><ul class="nav nav-pills ml-auto"><li class="nav-item"><a class="nav-link" id="deleteFile" href="javascript:deleteFile();" data-no_surat="'+no_surat+'" data-filename="'+file+'"><i class="fa fa-trash"></i> Delete</a></li><li class="nav-item"><a class="nav-link" href="'+file_url+'"><i class="fa fa-file-download"></i> Download</a></li></ul></div></nav>');
            if(file_type == 'pdf'){
                // box.append('<object data="<?= base_url('assets/document/surat/'); ?>'+file+'" type="application/pdf" width="100%" style="height: 85vh"><p>This browser does not support inline PDFs. Please download the PDF to view it: <a href="<?= base_url('assets/document/surat/'); ?>'+file+'">Download PDF</a></p></object>');
                // let pdfURL = file_url;
                let options = {
                    pdfOpenParams: {
                        navpanes: 0,
                        toolbar: 0,
                        statusbar: 0,
                        view: "FitV"
                    }
                };

                box.append('<div id="pdfViewer" style="width: 100%; height: 85vh;"></div>');
                PDFObject.embed(file_url, '#pdfViewer', options);
            } else {
                box.append('<img src="'+file_url+'" alt="'+file_name+' document file" style="width: 100%; height: auto;" >');
            }

            // set nomor surat dan tampilkan modal
            $('#noSurat').val(no_surat);
            $('#attachFile').modal('show');

            // console.log(file);
            // console.log(file_type);
        });
    });

    // Filter Jenis
    $("#jenis").change(function() {
        var id = $(this).val();

        if(id != ""){
            $.ajax({
                url: "<?= base_url('document/getSub') ?>",
                method: "POST",
                data: {
                    jenis: id
                },
                async: true,
                dataType: "json",
                success: function(data) {
                    var html = "<option value=''>- Choose One -</option>";
                    var i;
                    for (i = 0; i < data.length; i++) {
                        html +=
                            "<option value=" +
                            data[i].tipe_surat +
                            ">" +
                            data[i].tipe_surat +
                            "</option>";
                    }
                    $("#tipe").html(html); // masukkan option ke tag tipe
                    $(".hasil").attr("placeholder", "Choose Document Subtype."); // ganti placeholder nomor
                    $(".hasil").val(""); // kosongkan value hasil
                    // $('#entity').prop('selectedIndex',0);// kembalikan entity ke default
                }
            });
        } else {
            $("#tipe").html(""); // masukkan option ke tag tipe
            $("#tipe").html("<option value=''>- Choose One -</option>"); // masukkan option ke tag tipe
            $(".hasil").attr("placeholder", "Choose Document Type."); // ganti placeholder nomor
            $(".hasil").val(""); // kosongkan value hasil
        }
        return false;
    });

    $("#tipe").change(() => {
        // $('#entity').prop('selectedIndex',0);// kembalikan entity ke default
        cekEntitySubtype();
    });

    $("#entity").change(function() {
        cekEntitySubtype();
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
                required: "Please generate the Document Number by Choosing The Type of Document, Sub Type, and then choose Entity.",
            },
            perihal: {
                required: "Please enter The Subject.",
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

    // function buat nomor surat
    function buatNomor(){
        var entity = $("#entity").val();
        var jenis = $("#jenis").val();
        var sub = $("#tipe").val();
        var isi = "";

        if(entity != "" && jenis != "" && sub != ""){
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
        } else {
            let msg = "";
            let x = 0;
            if(jenis == ""){
                empty[x] = "jenis";
                x++;
            }
            if(sub == ""){
                empty[x] = "sub jenis";
                x++;
            }
            if(entity == ""){
                empty[x] = "entity";
                x++;
            }
            if(x != 0){
                for(let y = 0; y < x; y++){
                    if(y == 0 && y != x) {
                        msg += empty[y] + ", ";
                    } else if(y < x && y+1 != x) {
                        msg += empty[y] + ", ";
                    } else if(y+1 == x){
                        msg += empty[y] + "and ";
                    } else if(y == x){
                        msg += empty[y];
                    }
                }
            }

            console.log(msg);
        }
    }

    // function for delete document file
    function deleteFile(){
        // ambil nama file
        let filename = $("#deleteFile").data('filename');
        let no_surat = $("#deleteFile").data('no_surat');
        console.log(filename);

        let href = "<?= base_url('document/deleteDocument'); ?>?filename="+filename+"&no_surat="+no_surat
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    icon: 'info',
                    title: 'Deleting File...',
                    html: '<p>The files is being deleted please wait.<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                });

                $.ajax({
                    url: href,
                    success: function() {
                        location.reload();
                    }
                });
            }
        });
    }

    // function cek entity subtype
    function cekEntitySubtype() {
        let choosenEntity = $('#entity').val();
        let choosenSubType = $('#tipe').val();

        if(choosenEntity != "" && choosenSubType != ""){
            buatNomor();
        } else {
            if(choosenSubType == ""){
                $(".hasil").attr("placeholder", "Choose Document Subtype."); // ganti placeholder
                $(".hasil").val(""); // kosongkan value hasil
            } else {
                $(".hasil").attr("placeholder", "Choose Entity."); // ganti placeholder
                $(".hasil").val(""); // kosongkan value hasil
            }
        }
    }
</script>

