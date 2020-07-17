<!-- time script -->
<script>
function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('checkTime').innerHTML =
  h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
}
function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}

$(document).ready(() => startTime());
</script>

<!-- validation -->
<script>
$('#checkInSick').validate({
  rules: {
    notes: {
      required: true,
      minlength: 5
    }
  },
  messages: {
    notes: {
      required: "Please enter the Notes.",
      minlength: "Your Notes must be at least 5 characters long."
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

// form Other sickness trigger
$('input[name="lainnyaTrigger"]').on('change', () => {
  if($('input[name="lainnyaTrigger"]').prop("checked") == true) {
    $('#othersForm').fadeIn();
  } else if($('input[name="lainnyaTrigger"]').prop("checked") == false) {
    $('#othersForm').fadeOut();
  }
})
</script>