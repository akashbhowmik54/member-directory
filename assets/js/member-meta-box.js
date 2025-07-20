jQuery(document).ready(function($) {
    $('.select-media').on('click', function(e) {
        e.preventDefault();

        const button = $(this);
        const target = $('#' + button.data('target'));
        const preview = $('#preview_' + button.data('target').replace('member_', ''));

        const frame = wp.media({
            title: 'Select Image',
            multiple: false,
            library: { type: 'image' },
            button: { text: 'Use this image' }
        });

        frame.on('select', function() {
            const attachment = frame.state().get('selection').first().toJSON();
            target.val(attachment.id);
            preview.html(`<img src="${attachment.url}" style="max-width:100px; display:block; margin-top:5px;">`);
        });

        frame.open();
    });
});
