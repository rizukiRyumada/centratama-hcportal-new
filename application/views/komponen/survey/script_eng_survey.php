<script>
    //validate enggagement form survey to scroll to empty element
    $('#cekEngForm').click( () => {
        // prepare data
        var form = Array();

        // var x = $('input[name="01"]:checked').val();
        // console.log(x);

        <?php foreach($survey_data as $k => $v){
            $input_key = $v['id'];
            $input_name = 'input[name="'.$input_key.'"]:checked';
            // $push_name = "$('$input_name').val()";
            // echo "radio[".$k."][".$key."]['value'] = $('$input_name').val();";
            echo " var x = $('$input_name').val();
            form.push({
                'value': x,
                'input_key': '$input_key'
            });";
        } ?>

        let count_form = form.length-1;
        //scroll to top of the element when there is a fieldset of radio button still not checked
        $.each(form, (index, value) => {
            if(value.value == null){
                var $window = $(window),
                    $element = $("#"+value.input_key+""),
                    elementTop = $element.offset().top,
                    elementHeight = $element.height(),
                    viewportHeight = $window.height(),
                    scrollIt = elementTop - ((viewportHeight - elementHeight) / 2);

                $window.scrollTop(scrollIt);
                return false;
            }

            if(index == count_form){
                $('#submitForm').removeClass('d-none');
                $('#submitForm').addClass('d-block');
                $('#cekEngForm').addClass('d-none');
            }
        });
    });
</script>