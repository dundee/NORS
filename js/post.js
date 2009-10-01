$(function()
{
	$('.reply a').click(function()
	{
		var textarea = document.getElementById('text');
		textarea.value += '[' + $(this).attr('title') + ']';
		$('#text').focus();
		return false;
	});

	$('#text').markItUp(myCommentSettings);

	//evaluate
	colors = ['#000', '#777772', '#633590', '#39229C', '#2254D3', '#22C0D3', '#22D37B', '#74D322', '#fa0', '#ff0'];
	evaluation = $('#eval a');
	evaluation.each(function(index, item)
	{
		$(item).bind('mouseover', false, function()
		{
			$('#eval a:lt(' + (index+1) + ')').css('background', colors[index]);
		});
		$(item).bind('mouseout', false, function()
		{
	 		$('#eval a:lt(' + (index+1) + ')').css('background', '#fff');
		});

		$(item).click(function()
		{
			evaluation.unbind('mouseover');
			evaluation.unbind('mouseout');
			evaluation.unbind('click');
			$('#eval a:lt(' + (index+1) + ')').css('background', colors[index]);

			var url = new String(window.location);

			while (url.indexOf("&") != -1) {
				url = url.replace("&",";","gi");
			}

			$.ajax({
				type: "POST",
				cache: false,
				async: true,
				data: "value="+this.title+"&url="+url,
				dataType: "html",
				url: "?command=post-evaluate",
				success: function(html){
					$('#eval').html(html);
				}
			});
			return false;
		});
	});

	$('#check').attr('value', Math.round(2.9)).parent().hide();
	$('#subject').parent().hide();

	SyntaxHighlighter.defaults['toolbar'] = false;
	SyntaxHighlighter.defaults['wrap-lines'] = true;
	SyntaxHighlighter.all();

	$('.lightbox').lightBox({
		imageLoading:  '../../styles/default/images/lightbox-ico-loading.gif',
		imageBtnClose: '../../styles/default/images/lightbox-btn-close.gif',
		imageBtnPrev:  '../../styles/default/images/lightbox-btn-prev.gif',
		imageBtnNext:  '../../styles/default/images/lightbox-btn-next.gif',
		imageBlank:    '../../styles/default/images/lightbox-blank.gif'
	});

	$('.gravatar').each(function(index, item)
	{
		el = $(item);
		el.replaceWith($('<img />').attr('src', el.attr('title')).attr('class', 'gravatar'));
	});
});
