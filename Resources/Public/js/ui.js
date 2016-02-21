jQuery(document).ready(function() {

    jQuery('#bc-uploader .bc-controls li').click(function(){
       var index = jQuery(this).attr('data-index');

        jQuery('#bc-uploader .bc-content > div').hide();
        jQuery('#bc-uploader .bc-content > div[data-index="'+index+'"]').show();
    });


    jQuery('.circle-container').circleProgress({
        animation: false,
        value: 0,
        size: 200,
        fill: {
            gradient: ["red"]
        }
    }).on('circle-animation-progress', function(event, progress, stepValue) {
    //    jQuery(this).find('strong').text(String(stepValue.toFixed(2)).substr(1));
    });

});