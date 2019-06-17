$(function() {

    /* --- INTEGRATION SHOPIFY --- */
    var integrationDomainInput = $('#integrate-shopify-domain');
    var integrationConfirmBtn = $('#confirm-integrate-store');

    /* --- INTEGRATIONS CHECK --- */
    var integrationLoader = $('#integration-check-loader');
    var integrationText = $('#integration-check-text');
    var integrationSuccess = $('#integration-check-success');
    var integrationError = $('#integration-check-error');

    /* --- INTEGRATION CONFIGS --- */
    var shopifyAPI;
    var shopifyWebhooks;


    init();

    $("#purchase-chart").sparkline('html', {
        enableTagOptions: true,
        type: 'line',
        width: '100%',
        height: '50',
        lineColor: '#00b9b0',
        fillColor: 'rgba(0, 185, 176,0.4)',
        lineWidth: 2,
        chartRangeMin: 0,
        spotColor: false,
        minSpotColor: false,
        maxSpotColor: false
    });


    integrationConfirmBtn.on('click', function(e) {
        var domain = integrationDomainInput.val();

        $.ajax({
            type: 'POST',
            url: '/integrations/shopify/install/url',
            data: {
                shopify_domain: domain,
            },
            error: function(e) {
                console.log(e);
            },
            success: function(response) {
                console.log(response);
                location.assign(response.data.url);
            }
        });
    });


    function init() {
        $('#mf-nav-link-dashboard').addClass('mf-nav-active');

        $.ajax({
            type: 'POST',
            url: "/api_v1/integrations/get",
            data: {
            },
            error: function(e) {
                console.log(e);
            },
            success: function(response) {
                console.log("Integrations Retrieved: \n ----------------------");
                console.log(response);
                // Save API URL and Webhooks
                if (response.shopify_config.API_URL !== null && response.shopify_config.API_URL !== "") {
                    shopifyAPI = response.shopify_config.API_URL;
                    shopifyWebhooks = response.shopify_config.webhooks;
                }

                if (response.integrations.length > 0) {
                    // console.log(response.integrations);
                    setTimeout(function(){
                        if (response.integrations[0].type == 1) {
                            checkShopifyIntegration(response.shopify_config.client_id, response.shopify_config.client_secret, response.integrations[0].info);
                        }
                    }, 500);
                } else {
                    setTimeout(function(){
                        integrationCheckComplete(false);
                    }, 500);
                }
            }
        });
    }

    function checkShopifyIntegration(api_key, secret_key, data) {
        integrationText.html('Connecting to Shopify...');

        $.ajax({
            type: 'GET',
            url: shopifyAPI + "admin/webhooks.json",
            data: {
                access_token: data.access_token,
                client_id: api_key,
                client_secret: secret_key,
            },
            error: function(e) {
                console.log(e);
                setTimeout(function(){
                    integrationCheckComplete(false);
                }, 500);
            },
            success: function(response) {
                setTimeout(function(){
                    integrationCheckComplete(true);
                }, 750);
            }

        });
    }

    function integrationCheckComplete(success) {

        if (success) {
            integrationLoader.hide();
            integrationSuccess.css('display', 'block');
        } else {
            integrationLoader.hide();
            integrationError.css('display', 'block');
        }

    }

});