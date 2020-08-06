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
                        return '<button id="openFile" class="btn btn-primary w-100" data-file="'+data+'" ><i class="fa fa-paperclip" ></i></button>';
                    }
                    
                }
            ]
        });

        $('#jenis-surat').change(function(){
            nTable.ajax.reload();
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
</script>