/**
 * Created by Stephen on 7/2/2018.
 */
var subscribers_table;
var subcribers_table_settings;
$(function () {

    /* --- VALUES --- */
    var email_list_user_link_id = $('#email_list_user_link_id').val();

    /* --- MODALS --- */
    var new_subscriber_modal = $('#newSubscriberModal');
    var confirm_delete_modal = $('#confirm_delete_modal');
    var import_csv_modal = $('#import_csv_modal');

    /* --- TABLES --- */
    subscribers_table = $('#subscribers_table');


    /* --- INPUT FIELDS --- */
    var subscriber_first_name_input = $('#subscriber_first_name_input');
    var subscriber_last_name_input = $('#subscriber_last_name_input');
    var subscriber_email_address_input = $('#subscriber_email_address_input');
    var import_csv_confirm_checkbox = $('#import_csv_confirm_checkbox');
    var subscriber_confirm_checkbox = $('#subscriber_confirm_checkbox');
    var email_input = $('#email_input');

    /* --- BUTTONS --- */
    var new_subscriber_submit_button = $('#new_subscriber_submit_button');
    var delete_subscriber_button = $('.delete_subscriber_button');
    var confirm_delete_subscriber_button = $('#confirm_delete_subscriber_button');
    var import_csv_submit_button = $('#import_csv_submit_button');
    var import_csv_button = $('#import_csv_button');
    var subscribers_export_button = $('#subscribers_export_button');


    /* --- IMPORT SUBS COMPONENTS --- */
    var csv_subscribers;
    var file_input = $('#import_csv_file');
    var num_subs_display = $('#num_subs_display');
    var num_subs_count = $('#csv_num_subs');
    var mf_load_spinner = $('#mf_load_spinner');


    init();


    // Start All Subscribers Import function

    /**
     * Import subscribers modal toggle
     */
    import_csv_button.on('click', function(){
        import_csv_modal.modal('toggle');
    });

    file_input.on('change', function() {

        var csv_file = file_input[0].files[0];

        Papa.parse(csv_file, {
            header: true,
            complete: function(results) {
                console.log("Here");
                console.log(results);
                csv_subscribers = results.data;
                num_subs_display.attr('class', '');
                num_subs_count.html(csv_subscribers.length);
            }
        });

    });


    import_csv_submit_button.on('click', function() {

        console.log(csv_subscribers);

        mf_load_spinner.attr('class', '');

        import_csv_submit_button.html('Importing...');
        import_csv_submit_button.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: '/ajax/import_csv_subscribers',
            data: {
                email_list_user_link_id: email_list_user_link_id,
                subscribers: csv_subscribers
            },
            error: function(e) {
                console.log(e);
            },
            success: function(response) {
                console.log(response);
                import_csv_modal.modal('toggle');
                window.location.reload();

            }

        });


    });



    /**
     * On import CSV Checkbox Confirm change check to see
     * whether the checkbox is checked and enable the submit button
     * otherwise disable the button
     *
     */
    import_csv_confirm_checkbox.on('change', function() {

        if($(this).is(":checked")) {
            import_csv_submit_button.prop('disabled', false);
        } else {
            import_csv_submit_button.prop('disabled', true);
        }

    });


    /**
     * On Export Button Click
     *
     * Exports the All Subscribers Table to CSV
     */
    subscribers_export_button.on('click', function() {

        subcribers_table_settings._iDisplayLength = -1;
        subscribers_table.fnDraw();
        subscribers_table.tableToCSV();

        window.location.reload();
    });


    /**
     * On Subscriber Checkbox Confirm change check to see
     * whether the checkbox is checked and enable the submit button
     * otherwise disable the button
     *
     */
    subscriber_confirm_checkbox.on('change', function() {

        if($(this).is(":checked") && email_input.val() !== '') {
            new_subscriber_submit_button.prop('disabled', false);
        } else {
            new_subscriber_submit_button.prop('disabled', true);
        }

    });

    function init() {

        $('#mf-nav-link-subscribers').addClass('mf-nav-active');

        $('.left_col').height($('.right_col').height() + 100);

        //Disable new Subscriber Submit Button
        new_subscriber_submit_button.prop('disabled', true);
        import_csv_submit_button.prop('disabled', true);
        // submit_csv_file_button.prop('disabled', true);

        //Make Table jQuery Datatable instance
        subscribers_table.dataTable({
            "pageLength": 5,
            "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "columnDefs": [ {
                "targets": 'no-sort',
                "orderable": false
            } ]
        });
        subcribers_table_settings = subscribers_table.fnSettings();


    }


});