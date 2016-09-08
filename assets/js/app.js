(function($) {
    $(document).ready(function () {

        // $('.button-collapse').sideNav({
        //         menuWidth: 300, // Default is 240
        //         edge: 'left', // Choose the horizontal origin
        //         closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
        //     }
        // );

        $('aside nav a').each(function (index, element) {
            if($(element).attr('href') === window.location.href){
                $(element).parent().parent().parent().children('a').addClass('active');
                $(element).parent().parent().show();
            }
        });

        $('aside > nav > ul > li > a').on('click', function(e) {
            if(!$(this).hasClass('admin')){
                e.preventDefault();
                $(this).parent().children('a').toggleClass('active');
                $(this).parent().children('ul').toggle();
            }
        });

        $('.modal-trigger').leanModal({
                dismissible: true, // Modal can be dismissed by clicking outside of the modal
                opacity: .5, // Opacity of modal background
                in_duration: 300, // Transition in duration
                out_duration: 200, // Transition out duration
                starting_top: '4%', // Starting top style attribute
                ending_top: '10%'
            }
        );

        $(document).ready(function() {
            $('select').material_select();
        });

        if(window.location.pathname.indexOf('admin/') > -1){
            var config = {
                lineNumbers: false,
                matchBrackets: true,
                mode: "application/x-httpd-php",
                indentUnit: 0,
                indentWithTabs: false,
                lineWrapping: true,
                theme: 'dracula',
                inputStyle: 'contenteditable',
                lineWiseCopyCut: true
            };
            if(window.location.pathname.indexOf('subject') > -1 && window.location.pathname.indexOf('edit') > -1){
                var codes = new CodeMirror.fromTextArea($('.codes')[0], config);
                tinymce.init({ selector:'.desc' });
            } else {
                var codes = new CodeMirror.fromTextArea($('.codes')[0], config);
                tinymce.init({ 
                    selector:'.desc',
                    plugins: [
                        "code"
                    ]
                });
            }
        } else {
            var config = {
                lineNumbers: true,
                matchBrackets: true,
                mode: "application/x-httpd-php",
                indentUnit: 0,
                indentWithTabs: true,
                lineWrapping: true,
                theme: 'dracula',
                inputStyle: 'contenteditable',
                lineWiseCopyCut: true
            };
            $('.doc h5').on('click', function (){
                var arrow = $(this).children('div');

                if(!$(this).parent().children('div').hasClass('CodeMirror')) {
                    arrow.addClass('animationArrow');
                    new CodeMirror.fromTextArea($(this).parent().children('.code')[0], config);
                } else {
                    $(this).parent().children('.CodeMirror').toggle();
                    arrow.toggleClass('animationArrow animationArrowUp');
                }
            });
        }
    });
}(jQuery, window, undefined))