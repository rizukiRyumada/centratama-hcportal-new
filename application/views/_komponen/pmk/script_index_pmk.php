<script>
    $(document).ready(() => {
        <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
            let ajax_start_time;
            $.ajax({
                url: '<?= base_url('pmk/pmk_refresh'); ?>',
                beforeSend: () => {
                    toastr["warning"]("While the PMK data is being refreshed.", "Please Wait...");
                    ajax_start_time = new Date().getTime(); // ajax stopwatch
                },
                success: (data) => {
                    let vya = JSON.parse(data);
                    // ubah spinner jadi data angka
                    $('#eoc').empty().append(vya.counter_pmk);
                    $('#act').empty().append(vya.counter_active);
                    $('#cpt').empty().append(vya.counter_inactive);
                    if(vya.counter_new != ""){
                        toastr["info"]("There is "+vya.counter_new+" new employe that will reach out the end of contract.", "New Data Added")
                    } else {
                        // nothing
                    }

                    let ajax_request_time = new Date().getTime() - ajax_start_time;
                    toastr["success"]("PMK Form Data successfully refreshed.<br/><small>Retrieved in "+ajax_request_time+" ms", "Completed</small>")
                },
                error: () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                }
            });
        <?php endif; ?>

        // jika dia divhead, admin, hc divhead, atau ceo jalankan skrip ini
        
    });
</script>