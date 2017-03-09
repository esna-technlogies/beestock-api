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
    $('#codes').val(codeValue);
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
            if($(this).val() == countryVal){
                $('#shortCountryCode').val(countryVal);
                $('.codeViewed').html($(this).attr('data-code'));
            }
        })
    })
})
$(window).click(function() {
    $('#codes_wrap').removeClass('open');
});