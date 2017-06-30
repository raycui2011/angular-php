var app = angular.module('sampleapp');

app.controller('AddContactController', ['$scope', '$http', 'close',
  function($scope, $http, close) {
  $scope.showMessage = false;
  $scope.result = null;
  
  $scope.addContact = function (){
      var contact = {
          first_name: $scope.first_name,
          last_name: $scope.last_name,
          email: $scope.email,
          mobile: $scope.mobile,
          post_code: $scope.post_code
      };
      var res = $http({
            method: 'POST',
            url: '/ajax/add-contact.php',
            data: JSON.stringify(contact),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
        res.success(function(data, status, headers, config) {
            $scope.message = 'Success!';
            $scope.showMessage = true;
            $scope.result = 'success';
        });
        res.error(function(data, status, headers, config) {
            $scope.showMessage = false;
            $scope.message = 'Failed!';
            $scope.result = 'false';
        });
  };
  
  $scope.close = function(result) {
    close(result, 500); // close, but give 500ms for bootstrap to animate
  };

}]);