var myForm = $('#loginform');
var myFormId = 'loginform';

//validation
$(document).ready(function() {
    myForm.bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        submitHandler: function(validator, form, submitButton) {
            $.ajax({
                type: 'POST',
                url: submitionlink,
                data: $(form).serialize(),
                success: function(result) {
                    console.log("registered successfully and should redirect with logged in");
                    $('#'+myFormId+' .messages').html('<div id="signupaler-2" class="alert alert-success">\
                                                <span> '+result.response.user.uuid+'</span>\
                                            </div>');
                    myForm.data('bootstrapValidator').resetForm();
                    myForm.hide();
                }
            })
            .fail(function(result) {
                $('#'+myFormId+' .messages').html('');
                $.each( result.responseJSON.error.details , function( key, value ) {
                    console.log( key + ": " + value.message );
                    myForm.prepend('<div id="signupaler-2" class="alert alert-danger">\
                                            <span> '+value.message+'</span>\
                                        </div>');
                });
            })
            return false;
        },
        fields: {
            email: {
                validators: {
                    notEmpty: {
                        message: error_messages.email.empty
                    },
                    emailAddress: {
                        message: error_messages.email.failed
                    }
                }
            }
        }
    })
});