$(function() {
	if ($(".clicker")) {
		$(".clicker").click(pack);
		$(".clicker").each(collapse);
	}
});

function pack()
{
	if( $(this).siblings().get(0).style.display == 'none') {
		$(this).siblings().show('slow');
		$(this).parent().css('list-style-image', 'url("../../styles/default/images/minus.gif")');
	} else {
		$(this).siblings().hide('slow');
		$(this).parent().css('list-style-image', 'url("../../styles/default/images/plus.gif")');
	}
	return false;
}

function collapse()
{
	$(this).siblings().hide();
	$(this).parent().css('list-style-image', 'url("../../styles/default/images/plus.gif")');
	return true;
}

