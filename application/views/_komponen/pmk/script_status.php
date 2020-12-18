<script>

    // show timeline dialog with id
    function showTimeline(id, status){
        let url = "";
        if(status == 1){
            url = "pmk/ajax_getTimeline";
        } else if(status == 2) {
            url = "pmk/ajax_getTimeline_summary";
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!'
            });
        }
        $.ajax({
            url: "<?= base_url(); ?>"+url,
            data: {
                id: id
            },
            method: "POST",
            beforeSend: function(){
                $('.timeline').empty(); // kosongkan timeline
                $('#overlay_statusHistory').fadeIn(); // tampilkan

                toastr["warning"]("Retrieving Status History...");

                // show modal
                $('#statusViewer').modal('show');
            },
            complete: function(){

            },
            success: function(data){
                let data_timeline = JSON.parse(data);

                // variabel buat penanda
                let date_before = "";
                let id_timeline = "";
                $.each(data_timeline, function(index, value){
                    // split data timeline
                    let el = value.time.split('<~>');
                    let date_now = el[0];
                    let time_now = el[1];

                    // tambah data timeline
                    if(date_before != date_now){
                        // buat label date
                        id_timeline = "timeline-"+index;
                        date_before = date_now; // set date before dengan date now
                        
                        $('.timeline').append('<div id="'+id_timeline+'" class="time-label"><span class="bg-red">'+date_now+'</span></div>');
                    } else {
                        // nothing
                    }

                    $('#'+id_timeline).parent().append('<div><i class="'+value.icon+' bg-'+value.css_color+'"></i><div id='+index+' class="timeline-item"><span class="time"><i class="fas fa-clock"></i> '+time_now+'</span><h3 class="timeline-header"><span class="text-primary font-weight-bold">'+value.by+'</span> '+value.nik+'</h3></div></div>');

                    // jika tidak kosong tampilkan pesan revisi
                    if(value.text != undefined){
                        $('.timeline-item#'+index).append('<div class="timeline-body">'+value.text+'</div>'); // tambah pesan timeline
                    }
                    $('.timeline-item#'+index).append('<div class="timeline-footer"><span class="badge badge-'+value.css_color+'">'+value.name_text+'</span></div>');
                });

                $('#overlay_statusHistory').fadeOut(); // hide overlay
            },
            error: function(){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!'
                });
            }
        })
    }
</script>