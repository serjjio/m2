define([
    'uiComponent',
    'jquery',
    'ko'
], function (Component, $, ko) {
    'use strict';
    return Component.extend({
        defaults: {
            countProduct: ko.observable(''),
            isLoading: ko.observable(false),
            isSuccess: ko.observable(0),
            url: '',
            productId: ''
        },

        initialize: function () {
            this._super();
            return this;
        },

        isVisible: function () {
            return this.isSuccess;
        },

        getCountProduct: function () {
            var self = this;
            self.isLoading(true);
            $.ajax({
                url: self.url,
                type: "POST",
                cache: false,
                data: {productId: self.productId},
                dataType: 'json'
            })
                .done(function (data) {
                    if (data && data.success) {
                        self.isSuccess(1);
                        self.countProduct('Count product: ' + data.qty);
                    } else {
                        console.log(data)
                    }
                })
                .always(function () {
                    self.isLoading(false);
                });
        }
    });
});
