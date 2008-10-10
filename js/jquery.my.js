$(function() {
	//$('#edit_form').ajaxForm();
	//$().ajaxStart(myBlockUI);
	//$().ajaxStop(myUnblockUI);
	//$("#text").jTagEditor();
	//check_dates();
	$('.component > span').hide();
	
	$(".ajax").click(function(){
		elem = this;
		$.ajax({
   			type: "POST",
   			cache: false,
   			async: true,
   			data: "id="+$(elem).parent().attr('id'),
			dataType: "json",
   			url: elem.href,
   			success: function(obj){
				$('#'+obj.id+' > span').html('<br />'+obj.text).toggle('slow');	
   			}
 		});
 		return false;
	});
});


function check_dates(){
	if( $.browser.opera ) return true;
	var val = $("input[@name=date]").val();
	if (val.length == 0) return false;
	//2007-05-04T12:45Z
	var val2 = val.substring(0,10)+" "+val.substring(11,16)+":00";
	$("input[@name=date]").val(val2);
}
