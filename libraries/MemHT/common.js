$(document).ready( function() {
	/* XHTML VALID "NEW WINDOW" TARGET */
    $('a[rel="external"]').click(function() {
        window.open($(this).attr('href'));
        return false;
    });
	$('a[rel="external nofollow"]').click(function() {
        window.open($(this).attr('href'));
        return false;
    });
	/* COLORTIPS */
	$('a[rel="tooltip"]').colorTip({color:'yellow'});
});

/* SHOW HIDE */
function showhide(id) {
	 if (document.getElementById){
    		if(document.getElementById(id).style.display == 'none'){
      			document.getElementById(id).style.display = 'inline';
    		} else {
      			document.getElementById(id).style.display = 'none';
    		}
  	}
}
/* POPUP WINDOW */
function openPopup(url,w,h) {
	var newwindow = '';
	if (!newwindow.closed && newwindow.location) {
		newwindow.location.href = url;
	} else {
		newwindow=window.open(url,'memhtportal','toolbar=no,scrollbars=yes,status=no,directories=no,menubar=no,location=no,resizable=yes,top=20,screenx=20,left=20,screeny=20,width='+w+',height='+h+'');
		if (!newwindow.opener) newwindow.opener = self;
	}
	if (window.focus) {newwindow.focus()}
	return false;
}