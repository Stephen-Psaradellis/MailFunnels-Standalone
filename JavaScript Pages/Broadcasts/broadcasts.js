/**
 * Created by Stephen on 6/27/2018.
 */
$(function() {

    /* --- TABLE COMPONENTS --- */
    var broadcastsTable = $('#broadcasts-table');
    var tableLoader = $('#table-loader');
    var tableContent = $('#table-container');


    /* --- MODALS --- */
    var new_broadcast_modal = $('#newbroadcastModal');
    var confirm_delete_modal = $('#confirm_delete_modal');


    /* --- INPUT FIELDS --- */
    var broadcast_name_input = $('#broadcast_name_input');
    var broadcast_description_input = $('#broadcast_description_input');
    var broadcast_email_template_select = $('#broadcast_email_template_select');
    var broadcast_email_list_select = $('#broadcast_email_list_select');


    /* --- BUTTONS --- */
    var new_broadcast_submit = $('#new_broadcast_submit_button');
    var delete_broadcast_button = $('.delete_broadcast_button');
    var confirm_delete_broadcast_button = $('#confirm_delete_broadcast_button');

    var use_new_broadcast_checkbox = $('#mf-broadcast-new-style');


    init();



    /**
     * Button On Click Function
     * ------------------------
     *
     * Creates a new Broadcast
     *
     */
    new_broadcast_submit.on('click', function(e) {


        var broadcast_name = broadcast_name_input.val();
        var broadcast_description = broadcast_description_input.text();
        var email_template_ID = broadcast_email_template_select.val();
        var email_list_ID = broadcast_email_list_select.val();

        $.ajax({
            type:'POST',
            url: '/ajax/broadcast/add_broadcast',
            data: {
                name: broadcast_name,
                description: broadcast_description,
                email_template_ID: email_template_ID,
                email_list_ID: email_list_ID
            },
            error: function(e) {
                console.log(e);
            },
            success: function(response) {
                console.log(response);
                window.location.reload(true);
                new_broadcast_modal.modal('toggle');
            }
        });
    });


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
                    window.location.reload(true);
                }
            });
        });

    });

    broadcastsTable.on('all.bs.table', function (e, name, args) {
        console.log(args);
        tableLoader.hide();
        tableContent.removeClass('invisible');
    });

    function init() {

        $('#mf-nav-link-broadcasts').addClass('mf-nav-active');
        broadcastsTable.bootstrapTable();

        use_new_broadcast_checkbox.val(0);

        $('.left_col').height($('.right_col').height() + 100);

    }


});

function nameFormatter(value, row) {
    return "<h5 class='table-name text-semibold'>"+value+"</h5>" +
        "<small class='table-sub-description'>"+ row['info.description'] +"</small>";
}

function statFormatter(value, row) {
    return "<strong class='text-lg'>"+value+"</strong> (<span class='text-primary text-semibold text-sm'>+ 0.00%</span>)";
}

function actionFormatter(value, row) {
    var html = "<div class='btn-group'>" +
        "<a href='/broadcast/"+ value +"' class='btn btn-sm btn-primary add-tooltip' data-original-title='View' data-container='body' data-id='"+value+"'>View</a>" +
        "<a class='btn btn-sm btn-danger icon-btn add-tooltip' data-original-title='Delete' data-container='body'><i class='fa fa-trash text-white'></i></a></div>";
    return html;
}