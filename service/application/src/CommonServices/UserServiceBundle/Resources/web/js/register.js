$(function () {
    $('.show-password').click(function () {
        $('.show-password span').toggleClass("hidden");
        if($('#password').attr('type') == "password"){
            $('#password').attr('type','text');
        }else{
            $('#password').attr('type','password');
        }
    })
})
function select_code(codeValue , dataCode) {
    $('.codeViewed').html(dataCode);
    $('#shortCountryCode').val(codeValue);
    $('#codes_wrap').removeClass('open');
}
$(function () {
    $('#shortCountryCode option').each(function () {
        var dataCode = $(this).attr('data-code');
        $('#codes').append("<li><a class=\"dropdown-item\" href=\"javascript:select_code('"+$(this).val()+"' , '"+dataCode+"')\">"+$(this).html()+"</a></li>")
    })
    $('.codeViewed').click(function () {
        event.stopPropagation();
        $('#shortCountryCode option').each(function () {
            $('#codes_wrap').addClass('open');
        })
    })
    $('#codes li').click(function(){
        $('#codes li').removeClass('checked');
        $(this).addClass('checked');
    })
    $('#country').change(function(){
        var countryVal = $(this).val();
        $('#shortCountryCode option').each(function () {
            if($(this).attr('value') == countryVal){
                $('#shortCountryCode').val($(this).attr('value'));
                $('.codeViewed').html($(this).attr('data-code'));
            }
        })
    })

})
$(window).click(function() {
    $('#codes_wrap').removeClass('open');
});

//validation
$(document).ready(function() {
    $('#signupform').bootstrapValidator({
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
                    $('#signupform .messages').html('<div id="signupaler-2" class="alert alert-success">\
                                                <span> '+result.response.user.uuid+'</span>\
                                            </div>');
                    $("#signupform").data('bootstrapValidator').resetForm();
                    $("#signupform").hide();
                }
            })
            .fail(function(result) {
                $('#signupform .messages').html('');
                $.each( result.responseJSON.error.details , function( key, value ) {
                    console.log( key + ": " + value.message );
                    $('#signupform').prepend('<div id="signupaler-2" class="alert alert-danger">\
                                                <span> '+value.message+'</span>\
                                            </div>');
                });
            })
            return false;
        },
        fields: {
            firstName: {
                validators: {
                    stringLength: {
                        min: 4,
                    },
                    notEmpty: {
                        message: error_messages.firstName.empty
                    }
                }
            },
            lastName: {
                validators: {
                    stringLength: {
                        min: 4,
                    },
                    notEmpty: {
                        message: error_messages.lastName.empty
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: error_messages.email.empty
                    },
                    emailAddress: {
                        message: error_messages.email.failed
                    }
                }
            },
            "mobileNumber[number]": {
                validators: {
                    notEmpty: {
                        message: error_messages.mobileNumber.empty
                    },
                    stringLength: {
                        min: 4,
                    },
                    mobile: {
                        message: error_messages.mobileNumber.failed
                    }
                }
            },
            "accessInfo[password]": {
                validators: {
                    notEmpty: {
                        message: error_messages.accessInfo.empty
                    },
                    stringLength: {
                        min: 8,
                        message: error_messages.accessInfo.empty
                    }
                }
            },
            country: {
                validators: {
                    notEmpty: {
                        message: error_messages.country.empty
                    }
                }
            },
            'termsAccepted': {
                validators: {
                    choice: {
                        min: 1,
                        message: error_messages.termsAccepted.empty
                    }
                }
            }

        }
    })
});