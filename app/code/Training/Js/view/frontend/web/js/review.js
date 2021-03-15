define([
    'uiComponent',
    'jquery',
    'ko'
], function (Component, $, ko) {
    'use strict';
    return Component.extend({
        defaults: {
            reviewerName: ko.observable(''),
            reviewerMessage: ko.observable(''),
            isLoading: ko.observable(false),
            url: '',
        },

        initialize: function () {
            this._super();
            this.nextReview();
            return this;
        },
        nextReview: function () {
            var self = this;
            self.isLoading(true);
            $.ajax({
                url: self.url,
                type: "POST",
                cache: false,
                dataType: 'json'})
                .done(function(data){
                    if (data.name && data.message) {
                        self.reviewerName(data.name);
                        self.reviewerMessage(data.message);
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
