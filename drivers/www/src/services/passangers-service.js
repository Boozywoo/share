driversApp
    .factory('passangersService', function ($http, loginService) {

        var self = {};

        self.tour = {};
        self.passangerId = '';
        self.tourId='';

        self.getTour = function (id) {

            var options = {
                method: "GET",
                url: 'http://dev.vimi.by//api/v1/driver/tours/' + id + '?api_token=' + loginService.token
            };

            return $http(options)
                .then(function (response) {
                    self.passengers = response.data.tours.orders;
                    self.tours = response.data;
                    console.log(self.tour)
                    console.log(self.passengers)

                });
        };

        self.getPassenger=function (id) {
            var options = {
                method: "GET",
                url: 'http://dev.vimi.by//api/v1/driver/orders/' + id + '?api_token=' + loginService.token
            };

            return $http(options)
                .then(function (response) {
                    self.passenger = response.data.order;
                });
        }


        return self;
    });
