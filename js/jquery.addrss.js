$(function() {
	$("#rss").submit(function(){
		elem = this;
		$.ajax({
   			type: "POST",
   			cache: false,
   			async: true,
   			data: "source="+$('#source').val()+"&title="+$('#title').val(),
			dataType: "json",
   			url: elem.action,
   			success: function(obj){
				$('#rss').html(obj.html);
				elem.action = obj.action;	
   			}
 		});
 		return false;
	});
});