// setup an "add a tag" link
var $addTagLinkPhone = $('<a class="btn btn-outline-secondary add_tag_link-phone" href="#">' +
    '                    <i class="fas fa-plus-circle"></i>  Добавить телефон' +
    '                </a>');
var $newLinkLiPhone = $('<li></li>').append($addTagLinkPhone);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    var $collectionHolderPhone = $('ul.collection-ul-phone');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolderPhone.append($newLinkLiPhone);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolderPhone.data('index', $collectionHolderPhone.find(':input').length);

    $addTagLinkPhone.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see code block below)
        addTagForm($collectionHolderPhone, $newLinkLiPhone);
    });
});

function addTagFormPhone($collectionHolderPhone, $newLinkLiPhone) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolderPhone.data('prototype');

    // get the new index
    var index = $collectionHolderPhone.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolderPhone.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li class="collection-li"></li>').append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-tag-phone"><i class="far fa-trash-alt"></i></a><div class="clearfix separator"></div>');

    $newLinkLiPhone.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-tag-phone').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}