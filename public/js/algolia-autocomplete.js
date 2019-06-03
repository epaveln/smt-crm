function hideIcon(self) {

    self.style.backgroundImage = 'none';
    self.style.placeholder = '';
}
$(document).ready(function() {
    $('#search-input').each(function() {
        var autocompleteUrl = $(this).data('autocomplete-url');
        var contractorType = $(this).data('autocomplete-type');
        
        switch (contractorType) {
            case 'customer': break;
            case 'supplier': break;
            default: contractorType = 'customer';
        }

        $(this).autocomplete({hint: false}, [
            {
                source: function(query, cb) {
                    $.ajax({
                        url: autocompleteUrl+'?query='+query+'&contractorType='+contractorType
                    }).then(function(data) {
                        cb(data.contractors);
                    });
                },
                displayKey: 'name',
                templates: {
                    suggestion: function(suggestion) {
                        return '<a  class="suggestion" href="../../user/show/contractor/'+suggestion.id+'">'+suggestion.name+'</a>';
                    }
                },
                debounce: 500 // only request every 1/2 second
            }
        ])
    });
});