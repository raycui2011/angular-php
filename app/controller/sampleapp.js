//Define an angular module for our app
var app = angular.module('sampleapp', ['angularModalService', 'ngAnimate']);

app.controller('contactsController', ['$scope','$http', 'ModalService', function($scope, $http, ModalService, $templateCache) {
  $scope.showList = true;
  listContacts();
  
  $scope.clearCache = function() { 
    $templateCache.removeAll();
  }
  function listContacts() {
    console.log('it is here2');
    $http.post("ajax/list-contacts.php").success(function(data){
        $scope.contacts = data;
       });
  };
  
  /*$scope.addContact = function (task) {
    $http.post("ajax/add-contact.php?contact="+contact).success(function(data){
        listContacts();
        $scope.taskInput = "";
      });
  };*/
        
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
                console.log('it is here12');
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
      listContacts();
  }
  
  /*$scope.deleteContact = function (task) {
      ModalService.showModal({
            templateUrl: '../../partials/delete-contact.html',
            controller: "DeleteContactController",
        }).then(function(modal) {
            modal.element.modal();
            modal.close.then(function(result) {
                listContacts();
            });
        });
  };*/
  
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
  $scope.toggleStatus = function(item, status, task) {
    if(status=='2'){status='0';}else{status='2';}
      $http.post("ajax/update-contact.php?taskID="+item+"&status="+status).success(function(data){
        getTask();
      });
  };

}]);

app.controller('ModalController', function($scope, close) {
  
 $scope.close = function(result) {
 	close(result, 500); // close, but give 500ms for bootstrap to animate
 };

});