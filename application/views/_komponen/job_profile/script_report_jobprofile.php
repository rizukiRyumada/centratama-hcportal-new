<script>
$(document).ready(function () {
    var mTable = $('#myTask').DataTable({
        responsive: true,
        deferRender: true,
        pagingType: 'full_numbers',
        'dom': 'Bfrtip',
        'buttons': [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel" aria-hidden="true"></i> Export to Excel',
                title: '',
                filename: 'Report Job Profile-<?= date("dmY-Hi"); ?>',
                exportOptions: {
                    modifier: {
                        //Datatables Core
                        order: 'index',
                        page: 'all',
                        search: 'none'
                    },
                    columns: [0,1,2,3,4]
                }
            }
        ]
    });

    //filter table dengan DOM
    $('#divisi').change(function(){
        mTable.column(0).search(this.value).order([0, 'asc']).draw();// filter kolom pertama
        mTable.column(4).search('').order([4, 'asc']).draw();// hapus filter kolom ke 5
        mTable.column(1).search('').order([1, 'asc']).draw();// hapus filter kolom kedua
        $('#status').prop('selectedIndex',0);// kembalikan status ke default
    });
    $('#departement').change(function(){
        mTable.column(1).search(this.value).order([1, 'asc']).draw();
        mTable.column(4).search('').order([4, 'asc']).draw(); // reset filter status
        $('#status').prop('selectedIndex',0); // balikkan ke default
    });
    $('#status').change(function(){
        mTable.column(4).search(this.value).order([4, 'asc']).draw();
    });

    //mapping untuk pilihan departemen, supaya dapat tampil sesuai dengan divisi yang dipilih
    $('#divisi').change(function(){
        var dipilih = $(this).val(); //ambil value dari yang terpilih

        if(dipilih == ""){
            mTable.column(1).search(dipilih).order([1, 'asc']).draw(); //kosongkan filter dom departement
        }

        $.ajax({
            url: "<?php echo base_url('job_profile/ajax_getdepartement'); ?>",
            data: {
                divisi: dipilih //kirim ke server php
            },
            method: "POST",
            success: function(data) { //jadi nanti dia balikin datanya dengan variable data
                $('#departement').empty().append('<option value="">All</option>'); //kosongkan selection value dan tambahkan satu selection option

                $.each(JSON.parse(data), function(i, v) {
                    $('#departement').append('<option value="dept-' + v.id + '">' + v.nama_departemen + '</option>'); //tambahkan 1 per 1 option yang didapatkan
                });
            }
        })
    });

    // $('#riwayat-nomor').DataTable({
    //     'dom': 'Bfrtip',
    //     'buttons': [
    //         {
    //             extend: 'excel',
    //             text: 'Export to Excel',
    //             title: '',
    //             filename: 'Report Job Profile-<?= date("dmY-Hi"); ?>',
    //             exportOptions: {
    //                 modifier: {
    //                     //Datatables Core
    //                     order: 'index',
    //                     page: 'all',
    //                     search: 'none'
    //                 },
    //                 columns: [0,1,2,3,4]
    //             }
    //         }
    //     ]
    // });
});
</script>