// setup an "add a tag" link
var $addTagLink = $('<a class="btn btn-outline-secondary add_tag_link-email" href="#">' +
    '                    <i class="fas fa-plus-circle"></i>  Добавить email' +
    '                </a>');
var $newLinkLi = $('<li></li>').append($addTagLink);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    var $collectionHolder = $('ul.collection-ul-email');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    addTagExisted($collectionHolder);

    $addTagLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see code block below)
        addTagForm($collectionHolder, $newLinkLi);
    });


});






function addTagExisted($collectionHolder) {
    // get the new index
    var index = $collectionHolder.data('index');

    $(".collection-li").append('<a href="#" class="remove-tag existed-field"><i class="far fa-trash-alt"></i></a><div class="clearfix separator"></div>');

    // handle the removal, just for this example
    $('.remove-tag').click(function(e) {

        e.preventDefault();

        $(this).parent().children(".form-group").children(".form-control").val('toBe@Deleted');
        $(this).parent().children(".form-group").children(".form-control").hide();
        $(this).parent().children(".form-group").children(".required").hide();
        $(this).hide();

        return false;
    });
}





function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li class="collection-li"></li>').append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-tag"><i class="far fa-trash-alt"></i></a><div class="clearfix separator"></div>');

    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-tag').click(function(e) {

        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}

//$('.remove-tag').click(function(e) {
//    alert($(this).parent().get( 0 ).tagName);
//});