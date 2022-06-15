(function($) {
    'use strict';

    $(".ultp-shortcode-copy").click(function(e) {
        e.preventDefault();
        const that = $(this);
        navigator.clipboard.writeText(that.text());
        that.append("<span>Copied!</span>");
        setTimeout( function(){ that.find('span').remove(); }, 500 );
    });

    $( '.ultp-color-picker' ).wpColorPicker();

    // Add target blank for upgrade button
    $('.toplevel_page_ultp-settings ul > li > a').each(function (e) {
        if($(this).attr('href').indexOf("?ultp=plugins") > 0) {
            $(this).attr('target', '_blank');
        }
    });

    $(document).on('click', '.ultp-addons-enable', function(e){
        const that = this
        $.ajax({
            url: ultp_option_panel.ajax,
            type: 'POST',
            data: {
                action: 'ultp_addon', 
                addon: $(that).data('addon'),
                value: this.checked,
                wpnonce: ultp_option_panel.security
            },
            success: function(data) {
                if( $(that).data('addon') == 'ultp_templates' || $(that).data('addon') == 'ultp_builder' ) {
                    location.reload();   
                }
            },
            error: function(xhr) {
                console.log('Error occured.please try again' + xhr.statusText + xhr.responseText );
            },
        });
    });

    const actionBtn = $('.page-title-action');
    const savedBtn = $(".ultp-saved-templates-action");
    if ( savedBtn.length > 0 ) {
        if(savedBtn.data())
        actionBtn.addClass('ultp-save-templates-pro').text( savedBtn.data('text') )
        actionBtn.attr( 'href', savedBtn.data('link') )
        actionBtn.attr( 'target', '_blank' )
    }

    // Add URL for PostX
    $(document).on('click', '#plugin-information-content ul > li > a', function(e){
        const URL = $(this).attr('href');
        if (URL.includes('downloads/gutenberg-post-blocks-pro')) {
            e.preventDefault();
            window.open("https://www.wpxpo.com/postx/");
        }
    });

    // PostX Builder Metabox Settings
    const selector = $('.postx-meta-sidebar-position select');
    function changeSidebar() {
        if (selector.length > 0) {
            if (selector.val() == 'left' || selector.val() == 'right') {
                $('.postx-meta-sidebar-widget').show();
            } else {
                $('.postx-meta-sidebar-widget').hide();
            }
        }
    }
    changeSidebar();
    selector.on('change', function() {changeSidebar()});
    

})( jQuery );