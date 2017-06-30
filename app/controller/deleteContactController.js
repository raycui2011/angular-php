var app = angular.module('sampleapp');

app.controller('DeleteContactController', ['$scope', '$http', 'close', 'id',
  function($scope, $http, close, id) {
  $scope.showMessage = false;
  $scope.result = null;
  $scope.id = id;
  
  $scope.deleteContact = function (id){
      var contact = {
          id: $scope.id
      };
      var res = $http({
            method: 'POST',
            url: '/ajax/delete-contact.php',
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
        close();
 };
  
  $scope.close = function() {
    close({result: $scope.result}, 500); // close, but give 500ms for bootstrap to animate
  };

}]); 



