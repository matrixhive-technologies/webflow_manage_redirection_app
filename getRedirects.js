// Wait for the DOM to be fully loaded
$(document).ready(function () {
  console.log("get redirects script called");
  // Define a variable to hold your redirect data
  var redirects = [];

  // Access the data from the Collection List on the page
  $(".w-dyn-items .w-dyn-item").each(function () {
    var from = $(this).find("div #from_url").text();
    var to = $(this).find("div #to_url").text();

    // Add the data to the array as an object
    redirects.push({
      from: from,
      to: to,
    });
  });
  console.log("Redirects", redirects);

  // Get the value of the 'originalUrl' parameter from the query string
  var originalUrlParam = getUrlParameter("originalUrl");

  // Extract the last part of the path from the originalUrl
  if (originalUrlParam) {
    var url_parts = new URL(originalUrlParam);
    var lastPathPart = url_parts.pathname.split("/").pop();

    console.log("Last Path Part", lastPathPart);

    // Check if the lastPathPart matches with the 'from' values in the redirects array
    var redirectMatch = redirects.find(function (redirect) {
      return redirect.from === lastPathPart;
    });

    if (redirectMatch) {
      // If there's a match, redirect the user
      window.location.href = redirectMatch.to;
    } else {
      // If there's no match, redirect to the 404 page with a query parameter
      window.location.href = "/404?redirectFailed=yes";
    }
  }
});

// Function to get URL parameters
function getUrlParameter(name) {
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
  var results = regex.exec(location.search);
  return results === null
    ? ""
    : decodeURIComponent(results[1].replace(/\+/g, " "));
}
