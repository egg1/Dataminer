function addRss(maxRss)
{
	if(maxRss != null)
	{
		$("#screen-content").append("</br style=\"clear:both\"><br /><div id=\"rss-content\"><h1>News From BPDM</h1></div>");
	}
	else
	{
		$("#screen-content").append("<div id=\"rss-content\"><h1>News From BPDM</h1></div>");
	}
	
	$.ajax({type:"GET",url:"rss/news.rss",dataType:"xml",cache:false,success:parseXML}); // setup ajax to grab the xml data
	
	function parseXML(xml)
	{
		var items = new Array();
		var i     = 0;
		
		$(xml).find("item").each(function(){
			items[i] = this;
			i++;
		});
		
		items.reverse();
		
		if(items.length > maxRss && maxRss != null)
		{
			for(i=0; i<maxRss; i++)
			{
				var uDate  = $(items[i]).find("pubDate").text().split("-");
				var uDay   = uDate[1];
				var uMonth = getMonthText(uDate[0]);
				
				$("#rss-content").append("<div id=\"rss_"+i+"\"></div>");
				
				$("#rss_"+i).append("<div class=\"date\"><p class=\"day\">"+uDay+"</p><p class=\"month\">"+uMonth+"</p></div>");;
				$("#rss_"+i).append("<a href=\""+$(items[i]).find("link").text()+"\" target=\"_blank\"><h2>"+$(items[i]).find("title").text()+"</h2></a>");
				$("#rss_"+i).append("<p>"+$(items[i]).find("description").text()+"</p>");
				$("#rss_"+i).append("<p class=\"author\">Updated by "+"<a href=\"mailto:"+$(items[i]).find("author").text()+"\">"+$(items[i]).find("author").text()+"</a>"+"</p>");
				$("#rss-content").append("<br style=\"clear:both;\"/>");
			}
			$("#rss-content").append("<a style=\"color:#67a1de;font-size:15px;\" href=\"news.php\">See All News</a>");
		}
		else
		{
			for(i=0; i<items.length; i++)
			{
				var uDate  = $(items[i]).find("pubDate").text().split("-");
				var uDay   = uDate[1];
				var uMonth = getMonthText(uDate[0]);
				
				$("#rss-content").append("<div id=\"rss_"+i+"\"></div>");
				
				$("#rss_"+i).append("<div class=\"date\"><p class=\"day\">"+uDay+"</p><p class=\"month\">"+uMonth+"</p></div>");;
				$("#rss_"+i).append("<a href=\""+replaceData($(items[i]).find("link").text())+"\" target=\"_blank\"><h2>"+replaceData($(items[i]).find("title").text())+"</h2></a>");
				$("#rss_"+i).append("<p>"+replaceData($(items[i]).find("description").text())+"</p>");
				$("#rss_"+i).append("<p class=\"author\">Updated by "+"<a href=\"mailto:"+replaceData($(items[i]).find("author").text())+"\">"+replaceData($(items[i]).find("author").text())+"</a>"+"</p>");
				$("#rss-content").append("<br style=\"clear:both;\"/>");
			}
		}
	}
}

function getMonthText(m)
{
	switch(parseInt(m))
	{
		case 1:
		{
			return "JAN";
			break;
		}
		case 2:
		{
			return "FEB";
			break;
		}
		case 3:
		{
			return "MAR";
			break;	
		}
		case 4:
		{
			return "APR";
			break;
		}
		case 5:
		{
			return "MAY";
			break;
		}
		case 6:
		{
			return "JUN";
			break;
		}
		case 7:
		{
			return "JUL";
			break;
		}
		case 8:
		{
			return "AUG";
			break;
		}
		case 9:
		{
			return "SEP";
			break;
		}
		case 10:
		{
			return "OCT";
			break;
		}
		case 11:
		{
			return "NOV";
			break;
		}
		case 12:
		{
			return "DEC";
			break;
		}
	}	
}

function getRss(optionSet)
{
	$.ajax({type:"GET",url:"rss/news.rss",dataType:"xml",cache:false,success:parseXML}); // setup ajax to grab the xml data
	
	function parseXML(xml)
	{
		var items = new Array();
		var i     = 0;
		
		$(xml).find("item").each(function(){
			items[i] = this;
			i++;
		});
		
		items.reverse();
		
		for(i=0; i<items.length;i++)
		{
			$(optionSet).append("<option value=\""+$(items[i]).find("guid").text()+"\">"+$(items[i]).find("title").text()+"</option>");
		}
		
		showRssEdit();
	}
}

function setRssEditField(id)
{
	$.ajax({type:"GET",url:"rss/news.rss",dataType:"xml",cache:false,success:parseXML}); // setup ajax to grab the xml data
	
	function parseXML(xml)
	{
		var items = new Array();
		var i     = 0;
		
		$(xml).find("item").each(function(){
			items[i] = this;
			i++;
		});
		
		items.reverse();
		
		for(i=0; i<items.length;i++)
		{
			if($(items[i]).find("guid").text() == id)
			{
				$("#e-title").attr("value", replaceData($(items[i]).find("title").text()));
				$("#e-desc").text(replaceData($(items[i]).find("description").text()));
				$("#e-link").attr("value", replaceData($(items[i]).find("link").text()));	
			}
		}
	}
}

function showRssEdit()
{
	var idnum = $('#rss-edit>option:selected').attr("value");
	
	setRssEditField(idnum);
}
			
function setEditor()
{
	window.params = function(){
	var params = {};
	var param_array = window.location.href.split('?')[1].split('&');
	for(var i in param_array){
		x = param_array[i].split('=');
		params[x[0]] = x[1];
	}
	return params;
	}();
	
	var page = window.params.id;
	
	$.ajax({type:"GET",url:"content/"+page+".html",dataType:"html",cache:false,success:function(data)
	{
		data = replaceData(data);
		$("#editor").text(data);
		CKEDITOR.replace('editor', {height:"620"});
		$("#form0").css("display", "block");
	}});
}

function replaceData(d)
{
	d = d.replace(/\\&quot;/g, "");
	d = d.replace(/\\"/g, "\"");
	d = d.replace(/\\'/g, "'");
	
	return(d);
}

function changeExText(tag)
{
	var tagColor = colorToHex($("#"+tag).css("color"));
	
	if(tagColor == "#666666")
	{
		$("#"+tag).attr("value", "");
		$("#"+tag).css("color", "#000");
	}
	
	function colorToHex(color) {
    if (color.substr(0, 1) === '#') {
        return color;
    }
    var digits = /(.*?)rgb\((\d+), (\d+), (\d+)\)/.exec(color);
    
    var red = parseInt(digits[2]);
    var green = parseInt(digits[3]);
    var blue = parseInt(digits[4]);
    
    var rgb = blue | (green << 8) | (red << 16);
    return digits[1] + '#' + rgb.toString(16);
	};
}