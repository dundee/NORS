function addtag(text){
  $("#text").val( $("#text").val() + text );
}

function addlink(){
  var text = prompt('URL odkazu: ', 'http://');
  addtag('[url]'+text+'[/url]');
}

function addimg(){
  var text = prompt('URL obrázku: ', 'http://');
  addtag('[img]'+text+'[/img]');
}

function addlink_admin(){
  var url = prompt('URL: ', 'http://');
  var text = prompt('Text: ', '');
  addtag('<a href="'+url+'">'+text+'</a>');
}

function addimg_admin(){
  var url = prompt('URL: ', './files/');
  var text = prompt('Text: ', '');
  addtag('<img src="'+url+'" alt="'+text+'" />');
}

