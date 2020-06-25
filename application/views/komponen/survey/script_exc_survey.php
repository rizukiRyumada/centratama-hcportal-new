<script src="<?= base_url('assets/vendor/writelimit/writelimit.js'); ?>"></script>
<script>
    // $('#nama-departemen').writeLimit('#feedback-nama_departemen', 1000, 'Karakter'); karakter counter code
    <?php foreach($survey2 as $v){
        echo("$('#".$v['id_departemen']."').writeLimit('#feedback-".$v['id_departemen']."', 1000, 'Karakter');");
    } ?>

    //validate and scroll form exc to empty element
    $('#cekForm').click(function () {
        //prepare data
        var radio = Array();

        <?php foreach($survey1 as $k => $v){
            foreach($departemen as $key => $value){
                if($value['id'] != 0){
                    $input_key = $v['id'].'_'.$value['id'];
                    $input_name = 'input[name="'.$input_key.'"]:checked';
                    // $push_name = "$('$input_name').val()";
                    // echo "radio[".$k."][".$key."]['value'] = $('$input_name').val();";
                    echo " var x = $('$input_name').val();
                    radio.push({
                        'value': x,
                        'input_key': '$input_key'
                    });";
                }
            }
        } ?>

        var textarea = Array();
        <?php foreach($survey2 as $k => $v){
            $input_key = $v['id'].'_'.$v['id_departemen'];
            $input_name = 'textarea[name="'.$input_key.'"]';
            // $push_name = "$('$input_name').val()";
            echo" var x = $('$input_name').val();
            textarea.push({
                'value': x,
                'input_key': '$input_key'
            });";
        } ?>

        // console.log(radio);
        // console.log(textarea);

        var count_radio = radio.length-1;

        //scroll to top of the element when there is a fieldset of radio button still not checked
        $.each(radio, function(index, value) {
            if(value.value == null){
                var input_form = value.input_key.split('_');
                
                console.log(value.input_key);
                $([document.documentElement, document.body]).animate({ //for animation
                    scrollTop: $('#'+input_form[0]+'').offset().top
                }, 500);
                return false;
            }

            if(index == count_radio){ //if has been on last loop lets go to the next form
                var count_textarea = textarea.length-1;

                //scroll to top of the element when there is a textarea that has not been texted
                $.each(textarea, function(index, value) {
                    // console.log(value.value);
                    if(value.value == ""){
                        var input_form = value.input_key.split('_');
                        
                        // console.log(value.input_key);
                        $([document.documentElement, document.body]).animate({ //for animation
                            scrollTop: $('#'+input_form[0]+'').offset().top
                        }, 500);
                        return false;
                    }

                    if(index == count_textarea){
                        $('#submitForm').removeClass('d-none');
                        $('#submitForm').addClass('d-block');
                        $('#cekForm').addClass('d-none');
                    }
                });
            }
        });

        // var textarea_value = $("#texta").val();
        // var text_value = $('input[name="textField"]').val();
        // if (
            
        //     textarea_value != '' && text_value != ''
        // ) {
        //     $('input[type="submit"]').attr('disabled', false);
        // } else {
        //     $('input[type="submit"]').attr('disabled', true);
        // }
    });
</script>