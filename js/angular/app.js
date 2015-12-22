 var app = angular.module('pn', []);
 app.run(['$rootScope',function(rsp) {


 	rsp.user = {};
 	rsp.user.role = parseInt($('meta[name="auth-role"]')[0].content || 0);
 	rsp.user.id =  parseInt($('meta[name="auth"]')[0].content );
 }]);
