jQuery(document).ready(function(){
    jQuery('.addel').addel({
        events: {
            added: function (event) {
                event.preventDefault();
            }
        }
    });
});