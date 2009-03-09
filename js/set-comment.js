// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2007 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Html tags
// http://en.wikipedia.org/wiki/html
// ----------------------------------------------------------------------------
// Basic set. Feel free to add more tags
// ----------------------------------------------------------------------------
myCommentSettings = {
	onShiftEnter:	{keepDefault:false, replaceWith:'<br />\n'},
	onCtrlEnter:	{keepDefault:false, openWith:'\n<p>', closeWith:'</p>\n'},
	onTab:			{keepDefault:false, openWith:'	 '},
	markupSet:  [	 
		{name:'Bold', key:'B', openWith:'[b]', closeWith:'[/b]' },
		{name:'Italic', key:'I', openWith:'[i]', closeWith:'[/i]'  },
		{name:'Code', key:'D', openWith:'[code]', closeWith:'[/code]'  },
		{separator:'---------------' },
		{name:'Picture', key:'P', replaceWith:'[img][![Source:!:http://]!][/img]' },
		{name:'Link', key:'L', replaceWith:'[url][![Link:!:http://]!][/url]' }
	]
}
