//Define an angular module for our app
var app = angular.module('sampleapp', ['angularModalService', 'ngAnimate', 'angularUtils.directives.dirPagination']);

app.controller('contactsController', ['$scope','$http', 'ModalService', function($scope, $http, ModalService, $templateCache) {
  $scope.showList = true;
  $scope.contacts = null;
  $scope.currentPage = 1;
  $scope.totalItems = 0;
  $scope.entryLimit = 5;
  $scope.firstItem = 1;
  $scope.lastItem = 5;
  listContacts();
  
  $scope.clearCache = function() { 
    $templateCache.removeAll();
  }
  
  function listContacts(entryLimit) {
    var res = $http({
            method: 'POST',
            url: '/ajax/list-contacts.php',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
    res.success(function(data, status, headers, config) {
        $scope.totalItems = data.length;
        $scope.contacts = data;
    });
    res.error(function(data, status, headers, config) {
        alert( "failure message: " + JSON.stringify({data: data}));
    });
  };
  $scope.pageChangeHandler = function(num) {
      $scope.currentPage = num;
      $scope.firstItem = (num -1) * $scope.entryLimit + 1;
      var pages = $scope.totalItems / $scope.entryLimit;
      var maxItems = (num -1) * $scope.entryLimit + $scope.entryLimit;
      if ($scope.totalItems > maxItems) {
          $scope.lastItem = maxItems;
      } else {
          $scope.lastItem = $scope.totalItems ;
      }
  };
  
  $scope.addContactModal = function () {
    ModalService.showModal({
            templateUrl: '../../partials/contact-form.html',
            controller: "AddContactController",
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
                //$scope.message = "You said " + result;
            });
        });
        listContacts();
  };
  
  $scope.test = function() {
      console.log($scope.entryLimit);
      listContacts($scope.entryLimit);
  }


  $scope.showYesNo = function (id) {
    ModalService.showModal({
            templateUrl: '../../partials/delete-contact.html',
            controller: "DeleteContactController",
            inputs: {
                id: id
            }
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
                listContacts();
            });
        });       
  };
  
  $scope.updateContact= function (contact) {
      var contactObj = {
          id: contact.id,
          first_name: contact.first_name,
          last_name: contact.last_name,
          mobile: contact.mobile,
          email: contact.email,
          post_code: contact.post_code,
          created_at: contact.created_at
      };
      var res = $http({
            method: 'POST',
            url: '/ajax/update-contact.php',
            data: JSON.stringify(contactObj),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
        res.success(function(data, status, headers, config) {
            $scope.message = data;
        });
        res.error(function(data, status, headers, config) {
            alert( "failure message: " + JSON.stringify({data: data}));
        });
      
  };
  
  $scope.cancelEdit = function () {
      $scope.showList = true;
      //listContacts();
  }
  
  $scope.editContact = function (contact) {
    $scope.showList = false;
    $scope.contact = {
        'id' : contact.id,
        'first_name' : contact.first_name,
        'last_name' : contact.last_name,
        'mobile' : contact.mobile,
        'email' : contact.email,
        'post_code' : contact.post_code,
        'created_at' : contact.created_at
      };
  };
    
}]);

app.controller('ModalController', function($scope, close) {
  
 $scope.close = function(result) {
 	close(result, 500); // close, but give 500ms for bootstrap to animate
 };

});