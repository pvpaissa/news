//This function creates a list of all items
h2n = 0;
h3n = 0;
h4n = 0;
h5n = 0;
h6n = 0;

$("#article > h2").each(function() {

    //Create the html DOM element in a variable
    list = $("<a class='btn btn-secondary'>" + $(this).text() + "</a>");

    //Add the tag attribute and populate it with the current tag type (H2/H3)
    list.attr("tag", $(this).prop("tagName"));

    //Set the correct n attribute for our tag, and increment the varable hXn by one
    if ($(this).prop("tagName") == "H2") { list.attr("n", h2n); h2n++; }
    else if ($(this).prop("tagName") == "H3") { list.attr("n", h3n); h3n++; }
    else if ($(this).prop("tagName") == "H4") { list.attr("n", h4n); h4n++; }
    else if ($(this).prop("tagName") == "H5") { list.attr("n", h5n); h5n++; }
    else if ($(this).prop("tagName") == "H6") { list.attr("n", h6n); h6n++; }

    //Append our record
    $('#blog-toc').append($(list));
});

//The handler for the list clicked objects
$('#blog-toc a').on('click', function(){
    clicked = $(this);

    if (clicked.attr("tag") == "top")
    {
        $('html, body').animate({scrollTop:0}, 'slow');
        return true;
    }

    //Store the pointer to the original element that the index represents
    //Printed out this should spell "#message H2:eq(4)"
    goto_ele = $("#article " + clicked.attr("tag") + ":eq(" + clicked.attr("n") + ")");

    //Scroll to the right element
    $('html, body')
        .stop(true,true)
        .animate(
            {
                //Go to the called elements top position
                scrollTop: goto_ele.offset().top - 70
            },
            'fast'
        );

    return true;
});