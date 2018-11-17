$(document).ready(function(){
    
    //
    $('.toggle-info').click(function() {
        $(this).toggleClass('select').parent().next('.panel-body').fadeToggle(100); 
        
        if($(this).hasClass('select')) {
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        } else {
            $(this).html('<i class="fa fa-plus fa-lg"></i>')
        }
        
    });
    
    
     $("select").selectBoxIt({
         autoWidth: false,
     });
    
	console.log('ok');
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
    
    //convert type password to text(show password) on hover & reverse
    var pass = $('.password');
    $('.show-pass').hover(function(){
        pass.attr('type','text');    
    },function(){
        pass.attr('type','password');
    });
    
    //confirm message on button
    $('.confirm').click(function(){
        return confirm('Ary you sure?');
    });
    
    //view option
    $('.cat h3').click(function() {
        $(this).next('div').fadeToggle(200);
    });
    
    $('.option span').click(function() {
        $(this).addClass('active').siblings('span').removeClass('active');
        if($(this).data('view') == 'full') {
                $('.full-view').fadeIn(200);
           } else {
               $('.full-view').fadeOut(200);
           }
    });
    
    //
    $('.sub-link').hover(function () {
        $(this).find('.show-delete').fadeIn();
    }, function () {
        $(this).find('.show-delete').fadeOut(4000);
    });
    
});