jQuery(document).ready(function($){
	$('.listbox').sortable();
	$('form').on('submit',function(){
		var data = $('.listbox').sortable('toArray', 'id');
		$.post(SPAjax.ajaxurl, {'action': 'socialplus_save', 'page': $('.listbox').attr('id'), 'data': data});
    });
});