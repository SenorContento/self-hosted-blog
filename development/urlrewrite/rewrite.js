// https://stackoverflow.com/a/3354511/6828099
// https://stackoverflow.com/a/3340186/6828099

window.history.pushState({"html":"I am Text!!!","pageTitle":"I Am Title!!!"},"", "/development/urlrewrite/i-am-rewritten...");

//processAjaxData(response, urlPath);
function processAjaxData(response, urlPath) {
     document.getElementById("content").innerHTML = response.html;
     document.title = response.pageTitle;
     window.history.pushState({"html":response.html,"pageTitle":response.pageTitle},"", urlPath);
 }

 window.onpopstate = function(e) {
    if(e.state){
        document.getElementById("content").innerHTML = e.state.html;
        document.title = e.state.pageTitle;
    }
};