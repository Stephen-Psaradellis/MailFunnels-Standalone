$(function() {


    /* --- MODALS --- */
    var new_funnel_modal = $('#newFunnelModal');

    var confirm_delete_modal = $('#confirm_delete_modal');

    /* --- INPUT FIELDS --- */
    var funnel_name_input = $('#funnel_name_input');
    var funnel_description_input = $('#funnel_description_input');
    var funnel_trigger_select = $('#funnel_trigger_select');
    var funnel_email_list_select = $('#funnel_email_list_select');
    var funnel_activate_button = $('#funnel_activate_button');
    var funnel_deactivate_button = $('#funnel_deactivate_button');

    /* --- BUTTONS --- */
    var new_funnel_submit = $('#new_funnel_submit_button');
    var delete_funnel_button = $('.delete_funnel_button');

    var confirm_delete_funnel_button = $('#confirm_delete_funnel_button');


    //Initialize the Funnels Page
    init();


    //Disable Submit Button on Form until a list and trigger is selected
    new_funnel_submit.prop("disabled",true);


    funnel_trigger_select.on('change', function() {

        var currentValue = $(this).val();
        if (currentValue !== '') {
            new_funnel_submit.prop('disabled', false);
        } else {
            new_funnel_submit.prop('disabled', true);
        }

    });

    // funnel_email_list_select.on('change', function() {
    //
    //     var currentValue = $(this).val();
    //     if (funnel_trigger_select.val() != '0' && currentValue != '0') {
    //         new_funnel_submit.prop('disabled', false);
    //     } else {
    //         new_funnel_submit.prop('disabled', true);
    //     }
    //
    //
    // });

    funnel_deactivate_button.on('click', function() {
        //Get the Funnel ID
        var funnel_id = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: '/ajax/funnel/deactivate_funnel',
                dataType: "json",
                data: {
                    funnel_id: funnel_id
                },
                error: function (e) {
                    console.log(e);
                },
                success: function (response) {
                    console.log(response);
                    window.location.reload();
                }
            });
    });


    funnel_activate_button.on('click', function() {
        var funnel_id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '/ajax/funnel/activate_funnel',
            dataType: "json",
            data: {
                funnel_id: funnel_id
            },
            error: function (e) {
                console.log(e);
            },
            success: function (response) {
                console.log(response);
                window.location.reload();
            }
        });
    });


    /**
     * Button On Click Function
     * ------------------------
     *
     * Function called when new funnel form is submitted
     * Retrieves input from fields and makes API call to
     * create a new funnel instance
     *
     */
    new_funnel_submit.on('click', function(e) {

        e.preventDefault();

        var funnel_name = funnel_name_input.val();
        var funnel_description = funnel_description_input.val();
        var funnel_trigger_id = funnel_trigger_select.val();
        var funnel_email_list_id = funnel_email_list_select.val();


        $.ajax({
            type:'POST',
            url: '/ajax/funnel/create',
            dataType: "json",
            data: {
                app_id: '1',
                hook_type: funnel_trigger_id,
                email_list_id: funnel_email_list_id,
                name: funnel_name,
                description: funnel_description
            },
            error: function(e) {
                console.log(e);
            },
            success: function(response) {
                console.log(response);
                window.location.reload(true);
                new_funnel_modal.modal('toggle');

            }
        });

    });



    /**
     *  Deletes a Funnel from the DB
     *
     * @param id
     */
    delete_funnel_button.on('click', function() {

        var funnel_user_link_ID = $(this).data('id');

        confirm_delete_modal.modal('toggle');

        confirm_delete_funnel_button.on('click', function(){


            $.ajax({
                type:'POST',
                url: '/ajax/funnel/delete',
                dataType: "json",
                data: {
                    funnel_user_link_ID: funnel_user_link_ID
                },
                error: function(e) {
                    console.log(e);
                },
                success: function(response) {
                    confirm_delete_modal.modal('toggle');
                    console.log(response);
                    window.location.reload(true);
                }
            });



        });

    });


    function init() {

        $('#mf-nav-link-funnels').addClass('mf-nav-active');
    }



});













