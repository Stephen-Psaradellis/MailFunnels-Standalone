/**
 * Created by Stephen on 6/14/2018.
 */
$(function() {

    /* --- TABLE COMPONENTS --- */
    var listTable = $('#email-lists-table');
    var tableLoader = $('#table-loader');
    var tableContent = $('#table-container');

    /* --- MODALS --- */
    var new_email_list_modal = $('#newEmailListModal');
    var confirm_delete_modal = $('#confirm_delete_modal');

    /* --- INPUT FIELDS --- */
    var email_list_name_input = $('#email_list_name_input');
    var email_list_description_input = $('#email_list_description_input');


    /* --- BUTTONS --- */
    var new_email_list_submit_button = $('#new_email_list_submit_button');
    var delete_email_list_button = $('.delete_email_list_button');
    var confirm_delete_email_list_button = $('#confirm_delete_email_list_button');

    var use_new_template_checkbox = $('#mf-template-new-style');


    init();


    /**
     * Button On Click Function
     * ------------------------
     *
     * Function called when new email list form is submitted
     * Retrieves input from fields and makes API call to
     * create a new email list instance
     *
     */
    new_email_list_submit_button.on('click', function(e) {

        e.preventDefault();

        var email_list_name = email_list_name_input.val();
        var email_list_description = email_list_description_input.val();

        $.ajax({
            type:'POST',
            url: '/ajax/email_list/add',
            dataType: "json",
            data: {
                name: email_list_name,
                description: email_list_description
            },
            error: function(e) {
                console.log(e);
            },
            success: function(response) {
                console.log(response);
                window.location.reload(true);
                new_email_list_modal.modal('toggle');
            }
        });

    });


    /**
     * On Button Click Function
     * ------------------------
     * Deletes the Current Email List
     *
     */
    delete_email_list_button.on('click', function() {

        //Get the ID of Template
        var id = $(this).data('id');

        //Open Up Confirmation Modal
        confirm_delete_modal.modal('toggle');

        //On Confirm Delete
        confirm_delete_email_list_button.on('click', function(){

            $.ajax({
                type:'POST',
                url: '/ajax/email_list/delete',
                dataType: "json",
                data: {
                    email_list_user_link_id: id
                },
                error: function(e) {
                    console.log(e);
                },
                success: function(response) {
                    confirm_delete_modal.modal('toggle');
                    console.log(response);
                    window.location.reload();
                }
            });
        });

    });

    listTable.on('all.bs.table', function (e, name, args) {
        tableLoader.hide();
        tableContent.removeClass('invisible');
    });

    function init() {

        $('#mf-nav-link-lists').addClass('mf-nav-active');
        listTable.bootstrapTable();

        use_new_template_checkbox.val(0);


        // $('.left_col').height($('.right_col').height() + 100);

    }

});

// function imageFormatter(value, row) {
//     if (value) {
//
//     } else {
//         return "<img src='/img/placeholders/default.jpeg' alt='Profile Picture' class='img-circle img-sm'>";
//     }
//     return "<img src='"+ value +"'alt='Profile Picture' class='img-circle img-sm'>";
// }

function nameFormatter(value, row) {
    return "<h5 class='table-name text-semibold'>"+value+"</h5>" +
        "<small class='table-sub-description'>"+ row.description +"</small>";
}

function statFormatter(value, row) {
    return "<strong class='text-lg'>"+value+"</strong> (<span class='text-primary text-semibold text-sm'>+ 0.00%</span>)";
}

function actionFormatter(value, row) {
    var html = "<div class='btn-group'>" +
        "<a href='/list/"+value+"' class='btn btn-sm btn-primary add-tooltip' data-original-title='View' data-container='body' data-id='"+value+"'>View</a>" +
        "<a class='btn btn-sm btn-danger icon-btn add-tooltip' data-original-title='Delete' data-container='body'><i class='fa fa-trash text-white'></i></a></div>";
    return html;
}