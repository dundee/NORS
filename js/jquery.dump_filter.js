$(function() 
{
	var dump = new Object();
	dump.action = $('#filter_form').attr('action');
	dump.table = $('#table_name').val();
	dump.order = '';
	dump.a = 'desc';
	dump.paging = 0;
	dump.name = '';
	
	dump.setName = function()
	{
		this.name = $('#filter').val();
		this.renew();
		return false;
	}
	
	dump.setOrder = function(ord, elem)
	{ 
		$('#dump th a').removeClass();
		
		if (this.order == ord) {
			if (this.a == 'asc') {
				this.a = 'desc';
			} else {
				this.a = 'asc';
			}
		} else {
			this.a = 'asc'
		}
		$(elem).addClass(this.a);
		this.order = ord;
		this.renew();
	}
	
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
   			data: "name="+this.name+"&table="+this.table+"&order="+this.order+"&a="+this.a+"&page="+this.paging,
			dataType: "json",
   			url: this.action,
   			success: function(obj){
				$('#dump tbody').html(obj.html);	
				$('#paging').html(obj.paging);
				$("#paging a").click(function()
				{
					dump.setPaging(this.title);
					return false;
				});
   			}
 		});
	};
	
	$("#filter").keyup(function()
	{
		dump.setName();
	});
	
	$("#dump th a").click(function()
	{
		dump.setOrder(this.title, this);
		dump.setPaging('0');
		return false;
	});
	
	$("#paging a").click(function()
	{
		dump.setPaging(this.title);
		return false;
	});
});