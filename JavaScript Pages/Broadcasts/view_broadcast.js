/**
 * Created by Stephen on 7/11/2018.
 */
/**
 * Created by Stephen on 6/27/2018.
 */
$(function() {


    /* --- MODALS --- */
    var confirm_delete_modal = $('#confirm_delete_modal');


    /* --- BUTTONS --- */
    var delete_broadcast_button = $('.delete_broadcast_button');
    var confirm_delete_broadcast_button = $('#confirm_delete_broadcast_button');


    init();

    /**
     * On Button Click Function
     * ------------------------
     * Deletes the Current Email broadcast
     *
     */
    delete_broadcast_button.on('click', function() {

        //Get the ID of broadcast
        var id = $(this).data('id');

        //Open Up Confirmation Modal
        confirm_delete_modal.modal('toggle');

        //On Confirm Delete
        confirm_delete_broadcast_button.on('click', function(){

            $.ajax({
                type:'POST',
                url: '/ajax/broadcast/archive_broadcast',
                dataType: "json",
                data: {
                    broadcast_user_link_ID: id
                },
                error: function(e) {
                    console.log(e);
                },
                success: function(response) {
                    confirm_delete_modal.modal('toggle');
                    console.log(response);
                    window.location.href = '/broadcasts';
                }
            });
        });

    });

    function init() {

        $('#mf-nav-link-broadcasts').addClass('mf-nav-active');

        $('.left_col').height($('.right_col').height() + 100);

    }


});