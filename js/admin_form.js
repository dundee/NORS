$(function() {
	if ($("textarea").length      > 0)  {
		$("textarea").markItUp(mySettings);
		$("textarea").after('<p><small>shift + enter = &lt;br /&gt;, ctrl + enter = &lt;p&gt;&lt;p/&gt;</small></p>');
	}
	if ($(".next_file").length    > 0)  $(".next_file").click(new_file);
	if ($(".delete_file").length  > 0)  $(".delete_file").click(delete_file);
	if ($(".update_label").length > 0)  $(".update_label").click(update_label);

	if ($("#date").length > 0)        $("#date").datetimepicker();
	else {
		$("#datetimepicker_div").hide();
	}

	window.setInterval("pingServer()", 300000);
});

function new_file()
{
		var id = $(this).prev().prev().attr('id');
		$(this).parent().clone().insertAfter($(this).parent());
		//var html = $(this).clone();
		$(this).remove();
		//html.appendTo(id);
		$(".next_file").click(new_file);
		return false;
}

function delete_file()
{
	if (!confirm('Opravdu smazat?')) return false;

	var parent = $(this).parent().parent().parent();
	var id = parent.attr('id');
	var file = $(this).attr('title');
	var url = new String(window.location);

	while (url.indexOf("&") != -1) {
		url = url.replace("&",";","gi");
	}

	$.ajax({
		type: "POST",
	 	cache: false,
	 	async: true,
	 	data: "name="+id+"&url="+url+"&file="+file,
		dataType: "html",
	 	url: "?command=fileManager-del",
	 	success: function(html){
			parent.html(html);
			$(".delete_file").click(delete_file);
	 	}
	});
	return false;
}

function update_label()
{
	var parent    = $(this).parent().parent().parent();
	var id        = parent.attr('id');
	var file      = $(this).attr('title');
	var url       = new String(window.location);
	var label     = $(this).siblings('.label').val();
	var category = $(this).siblings('.kategorie_foto').val();

	while (url.indexOf("&") != -1) {
		url = url.replace("&",";","gi");
	}

	$.ajax({
		type: "POST",
	 	cache: false,
	 	async: true,
	 	data: "name="+id+"&url="+url+"&file="+file+"&label="+label+"&category="+category,
		dataType: "html",
	 	url: "?command=fileManager-update"
	});
	return false;
}

function pingServer()
{
	$.ajax({
		type: "POST",
	 	cache: false,
	 	async: true,
		dataType: "html",
	 	url: "?command=adminForm-ping"
	});
	return false;
}
