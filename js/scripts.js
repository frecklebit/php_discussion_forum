$(document).ready(function() {
	$('#quick-reply').click(function(){
		$('#ta-reply').focus();
	});
	
	Notification($('#notification'));
	navigateTo('');
});

function Purge(sAction)
{
	var aAction = sAction.split('.');
	var answer = confirm("Are you sure you want to delete this "+ aAction[0] +"?");
	
	if(answer)
	{
		$.post(
			"inc/process.php", 
			{ 
				action: 'del', 
				obj: pluralize(aAction[0]), 
				id: aAction[1]
			},
			function(data)
			{ 
				alert(data);
				location.href = location.href;
			}
		);
	}
	else
	{
		return null;
	}
}

function Notification(obj)
{
	if(obj.text())
	{
		var msg = obj.text();
		var class = obj.attr('class');
		
		alert(uppercase(class) + ': ' + msg);
	}
}

function pluralize(string)
{
	if(string.match(/y$/)){
		string = string.replace(/y$/, 'ies');
	}else{
		string += 's';
	}
	
	return string;
}
function uppercase(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

jQuery.parseQuery = function(qs,options) {
	var q = (typeof qs === 'string'?qs:window.location.search), o = {'f':function(v){return unescape(v).replace(/\+/g,' ');}}, options = (typeof qs === 'object' && typeof options === 'undefined')?qs:options, o = jQuery.extend({}, o, options), params = {};
	jQuery.each(q.match(/^\??(.*)$/)[1].split('&'),function(i,p){
		p = p.split('=');
		p[1] = o.f(p[1]);
		params[p[0]] = params[p[0]]?((params[p[0]] instanceof Array)?(params[p[0]].push(p[1]),params[p[0]]):[params[p[0]],p[1]]):p[1];
	});
	return params;
}