$(function() {
	if ($(".clicker")) {
		$(".clicker").click(pack);
		$(".clicker").each(collapse);
	}
});

function pack()
{
	$(this).siblings().toggle('slow');
	if( $(this).siblings().css('display') == 'none') {
		$(this).parent().css('list-style-image', 'url("../../styles/default/images/plus.gif")');
	} else {
		$(this).parent().css('list-style-image', 'url("../../styles/default/images/minus.gif")');
	}
	return false;
}

function collapse()
{
	$(this).siblings().hide();
	$(this).parent().css('list-style-image', 'url("../../styles/default/images/plus.gif")');
	return true;
}
