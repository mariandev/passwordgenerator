var clip;
function generate(){
	$.ajax({
		url: 'generator.php?phrase='+$('#phrase').val()+'&allow_numbers='+$('#numbers').prop('checked')+'&allow_letters='+$('#letters').prop('checked')+'&allow_cap_letters='+$('#capletters').prop('checked')+'&allow_symbols='+$('#symbols').prop('checked')+'&unique='+$('#unique').prop('checked'),
		success: function(d){
			d = $.parseJSON(d);
			if(d.err){
				$('#error').text(d.err);
				$('#pass_wrap').css('display','none');
				$('#err_wrap').css('display','block');
			}else if(d.password){
				$('#password').text(d.password).attr('data-clipboard-text', d.password).trigger('click');
				$('h4').text("Click the password to copy.");
				$('#err_wrap').css('display','none');
				$('#pass_wrap').css('display','block');
			}
		}
	});
}
$(function(){
	clip = new ZeroClipboard($('#password'), {
	  	moviePath: "js/ZeroClipboard.swf"
	});
	clip.on('complete', function(){
		$('h4').text("Password copied!");
	});
});