(function($) {
    
    $.entwine(function($) {
        
        /**
         * Class: .cms-edit-form .field.switchable
         *
         * Hide each switchable field except for the currently selected media type
         */
        $('.cms').entwine({
            onmatch: function(){
				var message = '<div class="cms-disabled"><div class="message bad"><h1>Development URL</h1><p>It looks like you\'re using an old link to access this website\'s CMS.</p><p>We recommend managing the website from the primary production domain: <em>'+ss_primary_domain+'</em>.</p><a href="'+ss_primary_domain+'/admin" class="button">Take me there</a></div></div>';
                $('.cms').html(message);
            }
        });
    });    
})(jQuery);