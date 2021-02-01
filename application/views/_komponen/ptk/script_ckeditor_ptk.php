    /* -------------------------------------------------------------------------- */
    /*                             CKEDITOR instances                             */
    /* -------------------------------------------------------------------------- */
    // CKEDITOR Instances
    CKEDITOR.replace('ska', {
        enterMode: CKEDITOR.ENTER_BR,
        on: {
            instanceReady: function(evt) {
                $('.ckeditor_loader').slideUp(); // sembunyikan loader
                CKEDITOR.instances['ska'].setData(cke_ska);

                if(<?php if(!empty($is_edit)){ echo($is_edit); } else { echo(0); } ?> != 1){
                    CKEDITOR.instances['ska'].setReadOnly();
                }
            }
        }
    });
    CKEDITOR.replace( 'req_special', {
        enterMode: CKEDITOR.ENTER_BR,
        on: {
            instanceReady: function(evt) {
                CKEDITOR.instances['req_special'].setData(cke_req_special);

                if(<?php if(!empty($is_edit)){ echo($is_edit); } else { echo(0); } ?> != 1){
                    CKEDITOR.instances['req_special'].setReadOnly();
                }
            }
        }
    });
    CKEDITOR.replace( 'outline', {
        enterMode: CKEDITOR.ENTER_BR,
        on: {
            instanceReady: function(evt) {
                CKEDITOR.instances['outline'].setData(cke_outline);

                if(<?php if(!empty($is_edit)){ echo($is_edit); } else { echo(0); } ?> != 1){
                    CKEDITOR.instances['outline'].setReadOnly();
                }
            }
        }
    });
    CKEDITOR.replace( 'main_responsibilities', {
        enterMode: CKEDITOR.ENTER_BR,
        on: {
            instanceReady: function(evt) {
                CKEDITOR.instances['main_responsibilities'].setData(cke_main_responsibilities);

                if(<?php if(!empty($is_edit)){ echo($is_edit); } else { echo(0); } ?> != 1){
                    CKEDITOR.instances['main_responsibilities'].setReadOnly();
                }
            }
        }
    });
    CKEDITOR.replace( 'tasks', {
        enterMode: CKEDITOR.ENTER_BR,
        on: {
            instanceReady: function(evt) {
                CKEDITOR.instances['tasks'].setData(cke_tasks);

                if(<?php if(!empty($is_edit)){ echo($is_edit); } else { echo(0); } ?> != 1){
                    CKEDITOR.instances['tasks'].setReadOnly();
                }

                $('.overlay').fadeOut(); // hapus overlay
            }
        }
    });