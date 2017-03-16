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

            console.log('validated') ;
            // Use Ajax to submit form data
            // $.post($form.attr('action'), $form.serialize(), function(result) {
            //    console.log( result );
            // }, 'json');
        });
});
