var WpListings = {};

WpListings = WpListings || {};
WpListings.Frontend = WpListings.Frontend || {};

WpListings.Frontend.Form = (function ($) {
    var _currentCategory;

    function Form() {

        //Init form
        $('.wp-listings-form select[name="category"]').each(function(index, object){
            if($(object).val() != "") {
                this.showFieldgroup($(object).val());
            }
        }.bind(this));

        //Submit & Search
        $('.wp-listings-form select[name="category"]').on('change', function (e) {
            var fieldgroupId = $(e.target).closest('select').val();
            this.showFieldgroup(fieldgroupId);
        }.bind(this));
    }

    Form.prototype.showFieldgroup = function(fieldgroupId) {
        $('[data-fieldgroup-key]').hide().prop('disabled', true);
        $('[data-fieldgroup-key="' + fieldgroupId + '"]').show().prop('disabled', false);
    };

    return new Form();

})(jQuery);
