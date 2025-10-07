jQuery(document).ready(function($){
    $('#color').wpColorPicker();

    var logo_uploader;
    $('#btn-select-logo').click(function(){

        if(logo_uploader !== undefined){
            logo_uploader.open();
            return;
        }

        logo_uploader = wp.media({
            title : "انتخاب لوگو" ,
            button : {
                text : "انتخاب"
            },
            library : {
                type : 'image'
            }

        });

        logo_uploader.on('select' , function(){
            let logo_seleceted = logo_uploader.state().get('selection');
            $('#logo').val(logo_seleceted.first().toJSON().url);
            $('#logo-preview').attr('src' , logo_seleceted.first().toJSON().url);
        });

        logo_uploader.open();

    });


    
    var bgComments;
    $('#btn-select-bg-comments').click(function(){

        if(bgComments !== undefined){
            bgComments.open();
            return;
        }

        bgComments = wp.media({
            title : "انتخاب لوگو" ,
            button : {
                text : "انتخاب"
            },
            library : {
                type : 'image'
            }

        });

        bgComments.on('select' , function(){
            let logo_seleceted = bgComments.state().get('selection');
            $('#bgComments').val(logo_seleceted.first().toJSON().url);
            $('#bgComments-preview').attr('src' , logo_seleceted.first().toJSON().url);
        });

        bgComments.open();

    });


});