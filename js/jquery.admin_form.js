$(function() {
	$("#admin_form form #kategorie2").attr('disabled', 'true');
	
	$("#admin_form form #kategorie").change(function(){
		if ($(this).val() == '1') $("#admin_form form #kategorie2").attr('disabled', false);
		else $("#admin_form form #kategorie2").attr('disabled', true);
	});
	
	$("#admin_form form #region_id").change(filter_rejstrik);
	
	$("#admin_form form #select_services").click(function(){
		var out = '';
		for (var i=1; i <= 34; i++) {
			if ($("#service"+i).attr('checked') == true) out += $("#service"+i).val(); 
		}
		$("#services").val(out);
		tb_remove();
	});
	
	if ($("textarea").length      > 0)  $("textarea").markItUp(mySettings);
	if ($(".next_file").length    > 0)  $(".next_file").click(new_file);
	if ($(".delete_file").length  > 0)  $(".delete_file").click(delete_file);
	if ($(".update_label").length > 0)  $(".update_label").click(update_label);
	
	if ($("#date").length > 0)        $("#date").datetimepicker();
	else {
		$("#datetimepicker_div").hide();
	}
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
	var cathegory = $(this).siblings('.kategorie_foto').val();
	
	while (url.indexOf("&") != -1) {
		url = url.replace("&",";","gi");
	}
	
	$.ajax({
		type: "POST",
	 	cache: false,
	 	async: true,
	 	data: "name="+id+"&url="+url+"&file="+file+"&label="+label+"&cathegory="+cathegory,
		dataType: "html",
	 	url: "?command=fileManager-update"
	});
	return false;
}

function filter_rejstrik(){
		if ($(this).val() > 0) {
			$.ajax({
	   			type: "POST",
	   			cache: false,
	   			async: true,
	   			data: "id="+$(this).val(),
				dataType: "html",
	   			url: "?command=rejstrik-filter",
	   			success: function(html){
					if (document.getElementById('rejstrik_mist_id')) {
						$.blockUI('<img src="/superadmin/styles/default/images/loading.gif" />');
						document.getElementById('rejstrik_mist_id').innerHTML = '';
						document.getElementById('rejstrik_mist[]').innerHTML = '';
						//$('#admin_form form #rejstrik_mist_id').empty();
						$.unblockUI(); 
						$('#admin_form form #rejstrik_mist_id').html(html);
						document.getElementById('rejstrik_mist[]').innerHTML = html;
					}	
	   			}
	 		});
	 	} else {  
	 		$('#admin_form form #rejstrik_mist_id').empty();
	 	}
}
