/*
Script Name: Codiad Database Query Designer
Author: JSX.RED 
Author URI: http://www.jsx.red

Description: This plugin allow designing database queries through Codiad user interface.

Copyright (c) 2015 by JSX.RED 
distributed as-is and without warranty under the MIT License. See
[root]/license.txt for more. This information must remain intact.

Let us build a better future for all humanity.
Share knowledge. Share emotions. Share fun.
*/
(function(global, $){
    
        var codiad = global.codiad,
        scripts= document.getElementsByTagName('script'),
        path = scripts[scripts.length-1].src.split('?')[0],
        curpath = path.split('/').slice(0, -1).join('/')+'/';

    // Instantiates plugin
    $(function() {    
        codiad.JSXDQD.init();
    });

    codiad.JSXDQD = {
        path: curpath,

        JSXDQD: $(window)
            .outerWidth() - 500,

        init: function(){
       		 // OPEN QUERY DESIGNER [CTRL+Q]
            $.ctrl('81', function() {
                codiad.JSXDQD.open();
            });
        },

        open: function() {
            codiad.modal.load(this.JSXDQD, this.path+'dialog.php');
            codiad.modal.hideOverlay();
        }
    };

})(this, jQuery);
