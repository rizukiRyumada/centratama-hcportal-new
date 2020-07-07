<script>

    //validate and scroll form exc to empty element
    $('#cekF360Form').click(function() {
        var form = Array();
        <?php foreach($pertanyaan as $value): ?>
            <?php foreach($value['survey_pertanyaan'] as $v): ?>
                var x = $('input[name="<?= $v['id']; ?>"]:checked').val();
                form.push({
                    value: x,
                    input_key: "<?= $v['id']; ?>"
                });
            <?php endforeach;?>
        <?php endforeach;?>

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
                $('#cekF360Form').addClass('d-none');
            }
        });
    });
</script>