<!-- jquery-validation -->
<script src="/assets/vendor/node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="/assets/vendor/node_modules/jquery-validation/dist/additional-methods.min.js"></script>

<script>
    $('#profileForm').validate({
        rules: {
            name: {
                required: true,
                minlength: 5
            },
            email: {
                required: true,
                email: true
            },
            password_current: {
                required: true,
                minlength: 8
            },
            password: {
                minlength: 8
            },
            password2:{
                equalTo: '[name="password"]'
            }
        },
        messages: {
            name: {
                required: "Please enter your Name",
                minlength: "Your Name must be at least 5 characters long."
            },
            email: {
                required: "Please enter your Email",
                email: "This is not the correct Email."
            },
            password_current: {
                required: "Please type your password to save changes.",
                minlength: "Your Password must be at least 8 characters long."
            },
            password: {
                minlength: "Your new Password must be at least 8 characters long."
            },
            password2:{
                equalTo: "Password doesn't match with the first one."
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
</script>