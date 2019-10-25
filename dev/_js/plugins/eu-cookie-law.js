// Creare's 'Implied Consent' EU Cookie Law Banner v:2.4
// Conceived by Robert Kent, James Bavington & Tom Foyster
 
var dropCookie = true;                      // false disables the Cookie, allowing you to style the banner
var cookieDuration = 14;                    // Number of days before the cookie expires, and the banner reappears
var cookieName = 'complianceCookie';        // Name of our cookie
var cookieValue = 'on';                     // Value of cookie
var datenschutzCookieText;

function createDiv(datenschutzCookieText){

    var bodytag = document.getElementsByTagName('body')[0];
    var div = document.createElement('div');

    div.setAttribute('id','cookie-law');

    if (typeof datenschutzCookieText === 'undefined' || datenschutzCookieText == "") {
        // <a class='close-cookie-banner button' href='javascript:void(0);' onclick='removeMe();'><span>Verstanden</span></a>
      var datenschutzCookieText = "<p class='datenschutz__text'>Um Ihnen einen optimalen Service zu bieten, verwendet diese Webseite Cookies. Sie erklären sich damit einverstanden, wenn Sie diese Webseite besuchen.</p><p class='datenschutz__links'><a class='datenschutz__link' href='/datenschutz' target='blank'>Datenschutzerklärung</a> <button class='close-cookie-banner button' href='javascript:void(0);' onclick='removeMe();'>verstanden</button>.</p>";
    }
    
    div.innerHTML = datenschutzCookieText;    
    // Be advised the Close Banner 'X' link requires jQuery
     
    // bodytag.appendChild(div); // Adds the Cookie Law Banner just before the closing </body> tag
    // or
    bodytag.insertBefore(div,bodytag.firstChild); // Adds the Cookie Law Banner just after the opening <body> tag
     
    document.getElementsByTagName('body')[0].className+=' cookiebanner'; //Adds a class tothe <body> tag when the banner is visible

}
 
 
function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000)); 
        var expires = "; expires="+date.toGMTString(); 
    }
    else var expires = "";
    if(window.dropCookie) { 
        document.cookie = name+"="+value+expires+"; path=/"; 
    }
}
 
function checkCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
 
function eraseCookie(name) {
    createCookie(name,"",-1);
}

function removeMe(){
	var element = document.getElementById('cookie-law');
	element.parentNode.removeChild(element);
    createCookie(window.cookieName,window.cookieValue, window.cookieDuration); // Create the cookie
}


$(document).ready(function(){

    if(checkCookie(window.cookieName) != window.cookieValue){
        createDiv(datenschutzCookieText);
    }
});
