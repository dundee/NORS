$(function()
{
	var dump = new Object();
	dump.action = $('#filter_form').attr('action');
	dump.paging = 0;
	dump.controller = $('#paging-controller').attr('title');

	dump.setPaging = function(page)
	{
		this.paging = page;
		this.renew();
	}

	dump.renew = function()
	{
		$.ajax({
			type: "POST",
			cache: false,
			async: true,
			data: "p="+this.paging+"&controller="+this.controller,
			dataType: "html",
			url: '?command=items-paging',
			success: function(html){
				$('#content').html(html);
				$("#paging a").click(function()
				{
					dump.setPaging(this.title);
					return false;
				});
   			}
 		});
	};

	$("#paging a").click(function()
	{
		dump.setPaging(this.title);
		return false;
	});
});
