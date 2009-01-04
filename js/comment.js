$(function()
{
	$('#check').attr('value', Math.round(2.9)).parent().hide(1);

	$('.reply a').click(function()
	{
		var textarea = document.getElementById('text');
		textarea.value += '[' + $(this).attr('title') + ']';
		$('#text').focus();
		return false;
	});
});
