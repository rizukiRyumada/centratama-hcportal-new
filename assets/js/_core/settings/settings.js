const button_goUpdate = $('#goUpdate');

button_goUpdate.on('click', function() {
  Swal.fire({
    icon: 'info',
    title: 'Please Wait',
    html: '<p>' + "Preparing data positions." + '<br/><br/><i class="fa fa-spinner fa-spin fa-2x"></i></p>',
    showConfirmButton: false,
    allowOutsideClick: false,
    allowEscapeKey: false,
    allowEnterKey: false
  });
});