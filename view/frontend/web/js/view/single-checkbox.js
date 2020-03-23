/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */
define(
    [
        'ko',
        'Magento_Checkout/js/model/quote',
        'jquery',
        'Magento_Ui/js/modal/modal',
        'Magento_Ui/js/form/element/single-checkbox',
        'uiRegistry',
        'underscore',
        'jquery/ui',
        'jquery/jquery.cookie'
    ],
    function (ko, quote, $, modal, Component, registry, _) {
        "use strict";

        return Component.extend({
            defaults: {
                visible: window.mageworxNoContactDevileryData.enabled,
                value: 1,
                label: window.mageworxNoContactDevileryData.label,
                additionalInfo: window.mageworxNoContactDevileryData.description,
                shipping_methods: window.mageworxNoContactDevileryData.shipping_methods,
                payment_methods: window.mageworxNoContactDevileryData.payment_methods,
                shippingMethodAvailable: true,
                paymentMethodAvailable: true
            },

            observableProperties: [
                'shipping_methods',
                'payment_methods',
                'shippingMethodAvailable',
                'paymentMethodAvailable'
            ],

            /**
             * Invokes initialize method of parent class,
             * contains initialization logic
             */
            initialize: function () {
                this._super();

                return this;
            },

            /** @inheritdoc */
            initObservable: function () {
                this._super()
                    .observe(this.observableProperties);

                this.value(0);
                this.initSubscription();

                this.valid = ko.computed(function () {
                    return this.shippingMethodAvailable() && this.paymentMethodAvailable();
                }, this);

                return this;
            },

            initSubscription: function () {

                if (_.isEmpty(this.payment_methods())) {
                    this.paymentMethodAvailable(true);
                }

                if (_.isEmpty(this.shipping_methods())) {
                    this.shippingMethodAvailable(true); // Any method
                }

                // Validate shipping method
                quote.shippingMethod.subscribe(function (method) {
                    if (!method) {
                        this.shippingMethodAvailable(true); //show by default
                        return;
                    }

                    if (_.isEmpty(this.shipping_methods())) {
                        this.shippingMethodAvailable(true); // Any method
                        return;
                    }

                    var methodCode = method.carrier_code + '_' + method.method_code;
                    if (this.shipping_methods().indexOf(methodCode) !== -1) {
                        this.shippingMethodAvailable(true);
                    } else {
                        this.shippingMethodAvailable(false);
                    }
                }, this);

                // Validate payment method
                quote.paymentMethod.subscribe(function (method) {
                    if (!method) {
                        this.paymentMethodAvailable(true);  //show by default
                        return;
                    }

                    if (_.isEmpty(this.payment_methods())) {
                        this.paymentMethodAvailable(true); // Any method
                        return;
                    }

                    var methodCode = method.method;
                    if (this.payment_methods().indexOf(methodCode) !== -1) {
                        this.paymentMethodAvailable(true);
                    } else {
                        this.paymentMethodAvailable(false);
                    }
                }, this);

                this.value.subscribe(function (val) {
                    this.saveChanges();
                }, this);
            },

            saveChanges: function () {
                var data = {
                    value: this.value() ? 1 : 0
                };

                $.ajax({
                    url: BASE_URL + 'nocontact_delivery/value/set',
                    type: 'POST',
                    isAjax: true,
                    dataType: 'json',
                    data: data,
                    success: function (xhr, status, errorThrown) {
                        //console.log(xhr);
                    },
                    error: function (xhr, status, errorThrown) {
                        console.log('There was an error saving quote.');
                    }
                });

                return true;
            },

        });
    }
);
