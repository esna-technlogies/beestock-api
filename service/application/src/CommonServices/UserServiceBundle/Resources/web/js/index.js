  $(document).ready(function() {
    $('#signupform').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            first_name: {
                validators: {
                        stringLength: {
                        min: 4,
                    },
                        notEmpty: {
                        message: 'Please supply your first name'
                    }
                }
            },
             last_name: {
                validators: {
                     stringLength: {
                        min: 4,
                    },
                    notEmpty: {
                        message: 'Please supply your last name'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'Please supply your email address'
                    },
                    emailAddress: {
                        message: 'Please supply a valid email address'
                    }
                }
            },
            mobile: {
                validators: {
                    notEmpty: {
                        message: 'Please supply your mobile number'
                    },
                    phone: {
                        message: 'Please supply a vaild mobile number '
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'Please supply your password'
                    },
                    stringLength: {
                        min: 8,
                        message: 'Please supply a strong password with min length 6 chars'
                    }
                }
            },
            country: {
                validators: {
                    notEmpty: {
                        message: 'Please supply your country'
                    }
                }
            }
            }
        })
        .on('success.form.bv', function(e) {
            $('#success_message').slideDown({ opacity: "show" }, "slow")

                // $('#signupform').ajaxForm(function() {
                //     //alert("Thank you for your comment!");
                //     console.log("Ajax form submitted");
                // });
                // Do something ...
                $('#contact_form').data('bootstrapValidator').resetForm();

            // Prevent form submission
            e.preventDefault();

            // Get the form instance
            var $form = $(e.target);

            // Get the BootstrapValidator instance
            var bv = $form.data('bootstrapValidator');

            // Use Ajax to submit form data
            $.post($form.attr('action'), $form.serialize(), function(result) {
                console.log(result);
            }, 'json');
        });
});