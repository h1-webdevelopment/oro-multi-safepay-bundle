define(function(require) {
    'use strict';

    var MultiSafepayCheckout;
    var _ = require('underscore');
    var mediator = require('oroui/js/mediator');
    var BaseComponent = require('oroui/js/app/components/base/component');

    MultiSafepayCheckout = BaseComponent.extend({
        /**
         * @property {Object}
         */
        options: {
            gateway: null,
            paymentMethod: null,
            selectors: {
                container: '',
            }
        },

        $el: null,

        /**
         * @inheritDoc
         */
        initialize: function(options) {
            this.options = _.extend({}, this.options, options);

            mediator.on('checkout:place-order:response', this.handleSubmit, this);
            mediator.on('checkout:payment:before-form-serialization', this.beforeTransit, this);

            this.$el = this.options._sourceElement.parent().find(this.options.selectors.container).first();
        },

        /**
         * @param {Object} eventData
         */
        beforeTransit: function(eventData) {
             if (eventData.paymentMethod === this.options.paymentMethod) {
                mediator.trigger(
                    'checkout:payment:additional-data:set',
                    JSON.stringify(
                        {
                            'msp_issuer': this.getIssuersField().val(),
                            'gateway': this.options.gateway
                        }
                    )
                );
            }
        },

        getIssuersField: function () {
            return this.$el.find('select[name=h1_multi_safepay_issuers]');
        },

        /**
         * @param {Object} eventData
         */
        handleSubmit: function(eventData) {
            if (eventData.responseData.paymentMethod === this.options.paymentMethod) {
                eventData.stopped = true;
                if (!eventData.responseData.purchaseRedirectUrl) {
                    mediator.execute('redirectTo', {url: eventData.responseData.errorUrl}, {redirect: true});
                    return;
                }

                window.location = eventData.responseData.purchaseRedirectUrl;
            }
        },

        dispose: function() {
            if (this.disposed) {
                return;
            }

            mediator.off('checkout:place-order:response', this.handleSubmit, this);

            MultiSafepayCheckout.__super__.dispose.call(this);
        }
    });

    return MultiSafepayCheckout;
});
