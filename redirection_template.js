window.onload = function(){
    var jsonUrl = "#json_url#";
    fetch(jsonUrl)
        .then((response) => response.json())
        .then((json) => { 
            let redirects = json;
            console.log(redirects);

            let pathName = window.location.pathname;
            let search = window.location.search;
            
            let redirectMatch = redirects.find(function (redirect) {
                return redirect.from === pathName;
            });
            if (redirectMatch) {
                window.location.href = redirectMatch.to + search;
            }
        });
}
  