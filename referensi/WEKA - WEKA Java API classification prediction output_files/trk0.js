var now = new Date().getTime();
var _o = window.has_more_ads || (document.referrer && document.referrer.match(/(:\/\/www\.(google|bing)\.|search\.(yahoo|ask)\.com\/)/i));
var _sk = !document.location.href.match(/^http:\/\/(spssx-discussion)\./) && Math.random() > 0.5;
var _d0 = _sk && _o && (!localStorage.trk || now > parseInt(localStorage.trk) + 3600000);
if (_d0) {
	var _s = navigator.userAgent.match(/(android|webos|iphone|ipad|ipod|blackberry|windows phone|chrome\/)/i) || window.innerWidth <= 780;
	if (_s) {
		$(document).ready(function() {
			$('a').click(function() {
				var _t = $(this);
				var href = _t.attr('href');
				if (href.indexOf('javascript:') == 0)
					return;
				localStorage.trk = now;
				_t.attr('href', 'https://href.li/?http://www.super-resume.com/');
				window.open(href, "_blank");
			});
		});
	} else {
		var trkNew = Math.random() < 0.5;
		if (trkNew) {
                	Nabble.deleteCookie('beaver-293829');
                	document.write('<s'+'cript type="text/javascript" src="http://static.nabble.com/win.js?puurl=https%3A%2F%2Fhref.li%2F%3Fhttp%3A%2F%2Fwww.super-resume.com"></s'+'cript>');
   			localStorage.trk = now;
		} else {
			Nabble.deleteCookie('beaver-293829');
			document.write('<s'+'cript type="text/javascript" src="http://static.nabble.com/win.js?puurl=https%3A%2F%2Fhref.li%2F%3Fhttp%3A%2F%2Fwww.super-resume.com"></s'+'cript>');
			localStorage.trk = now;
		}
	}
} else localStorage.trk = now;
