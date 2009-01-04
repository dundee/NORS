$(function()
{
	$('.help').click(showHelp);
});

function showHelp()
{
	window.alert($(this).attr('title'));
	return false;
}
