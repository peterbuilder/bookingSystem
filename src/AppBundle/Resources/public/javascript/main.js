/**
 * Created by piotr on 21.02.17.
 */

jQuery(function(){
    jQuery('.button').hover(function () {
        $(this).addClass('activeButton');
    },
    function () {
        $(this).removeClass('activeButton');
    })
})

