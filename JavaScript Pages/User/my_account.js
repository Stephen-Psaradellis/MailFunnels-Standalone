$(function() {

    var shopify_access_token = $('#mf-shopify-token').val();
    var api_key = $('#shopify_api').val();
    var secret_key = $('#shopify_secret').val();



    init();


    function init() {
        $('#mf-nav-link-account').addClass('mf-nav-active');

        var hook =  {
            topic: "orders/create",
            address: "https://whatever.hostname.com/",
            format: "json"
        };

        $.ajax({
            type: 'GET',
            url: "https://mailfunnelstesting.myshopify.com/admin/webhooks.json",
            data: {
                access_token: shopify_access_token,
                client_id: api_key,
                client_secret: secret_key,
                webhook: hook
            },
            error: function(e) {
                console.log(e);
            },
            success: function(response) {
                console.log(response);
            }

        });
    }
});