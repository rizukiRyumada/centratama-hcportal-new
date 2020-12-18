<script>
    // $(window).bind('beforeunload', function(){
    //     return '>>>>>Before You Go<<<<<<<< \n Your Entered data would be Reset.';
    // });
    
    <?= $this->session->flashdata('all_survey'); ?>
    <?= $this->session->flashdata('one_survey'); ?>
</script>