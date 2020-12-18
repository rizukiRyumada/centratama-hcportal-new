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
$('input[name="otherTrigger"]').on('change', () => {
	if($('input[name="otherTrigger"]').prop("checked") == true) {
		$('#othersForm').fadeIn();
	} else if($('input[name="otherTrigger"]').prop("checked") == false) {
		$('#othersForm').fadeOut();
	}
});

// Validator for Checkbox with different name

function onSubmit() {
	var fields = $("input[type='checkbox']").serializeArray(); 
	if (fields.length == 0) { 
		// tampilkan pesan error
		Swal.fire({
			icon: 'error',
			title: 'Error',
			text: 'Please choose your sick category!'
		});
		
		// cancel submit
		return false;
	}

	// cek jika checkbox other dicek
	if($('input[name="otherTrigger"]').prop("checked") == true){
		// cek kotak boxnya
		if($('input[name="other"]').val() == ""){
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Other Sick input form is empty!'
			});
			
			// cancel submit
			return false;
		}
	}
	// else 
	// { 
	//   alert(fields.length + " items selected"); 
	// }
}
// register event on form, not submit button
$('#checkInSick').submit(onSubmit);

// src: https://jsfiddle.net/p8y2e59c/
</script>