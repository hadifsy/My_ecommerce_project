$(document).ready(function(){
    
    $('.login-page h1 span').click(function () {
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.' + $(this).data('class')).fadeIn().siblings('form').hide();
    });
    
     //start select box it
     $("select").selectBoxIt({
         autoWidth: false,
     });
    
	//place holder effect
	$('[placeholder]').focus(function(){
		$(this).attr('data-text', $(this).attr('placeholder'));
		$(this).attr('placeholder', '');
	}).blur(function(){
		$(this).attr('placeholder', $(this).attr('data-text'));
	});
    
    //add astrisk to requird filds
    $('input').each(function(){
        if($(this).attr('required') === 'required'){
            $(this).after('<span class="astrisk">*</span>');
        }    
    });
    
    //confirm message on button
    $('.confirm').click(function(){
        return confirm('Ary you sure?');
    });
    
    //old code
    // $('.new-ad .panel-body .live-name').keyup(function() {
    //     $('.live .caption h3').text($(this).val());
    // });

    // $('.new-ad .panel-body .live-price').keyup(function() {
    //     $('.live .price-tag').text('$' + $(this).val());
    // });

    // $('.new-ad .panel-body .live-desc').keyup(function() {
    //     $('.live .caption p').text($(this).val());
    // });

    // new code
    $('.live').keyup(function(){
       $($(this).data('class')).text($(this).val());  
    });

});