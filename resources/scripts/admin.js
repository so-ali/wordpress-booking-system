(function ($) {
    "use strict";
    $(document).on('ready', function () {
        $('#bfb-form-builder').formBuilder({
            disableFields: [
                'autocomplete',
                'checkbox-group',
                'file',
                'header',
                'hidden',
                'number',
                'paragraph',
                'radio-group',
                'select',
                'starRating',
                'textarea',
            ],
            disabledActionButtons: ['data'],
            formData: BFB_OBJECT.form_data,
            dataType: 'json',
            render: true,
            onSave: function (evt, formData) {
                jQuery.post(BFB_OBJECT.ajax_url, {
                    action: 'bfb_form_save',
                    nonce: BFB_OBJECT.nonce,
                    data: formData
                }).then(function (res) {
                    alert(res.data.message);
                });
            },
        });
    });
})(jQuery);