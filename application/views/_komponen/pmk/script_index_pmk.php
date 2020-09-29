<script>
    <?php if($this->session->userdata('role_id') == 1 || $userApp_admin == 1): ?>
        $(document).ready(() => {
            $.ajax({
                url: '<?= base_url('pmk/pmk_refresh'); ?>',
                beforeSend: () => {

                },
                success: (data) => {
                    console.log(data);
                },
                error: () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    })
                }
            })
        });
    <?php endif; ?>
</script>