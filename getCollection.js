 // Wait for the DOM to be fully loaded
 
 $(document).ready(function() {
     
     console.log('asadddddddd outer');

       // Define a variable to hold your blog post data
    var redirects = [];

    // Access the data from the Collection List on the page
    $('.w-dyn-items .w-dyn-item').each(function() {
      var fromURL = $(this).find('h3').text();

      // Add the data to the array as an object
      redirects.push({
        fromURL: fromURL,
      });
    });

    console.log(redirects);
 });