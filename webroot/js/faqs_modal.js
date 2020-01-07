/**
 * @fileoverview Faqs Javascript
 * @author nakajimashouhei@gmail.com (Shohei Nakajima)
 */


/**
 * FaqsModal controller
 */
NetCommonsApp.controller('FaqsModal',
    ['$scope', 'NetCommonsModal', 'NC3_URL', 'LOGIN_USER',
      function($scope, NetCommonsModal, NC3_URL, LOGIN_USER) {

        /**
         * Show faq information method
         *
         * @param {number} users.id
         * @return {void}
         */
        $scope.showFaq = function($event, id) {
          NetCommonsModal.show(
              $scope, 'FaqsModal.view',
              NC3_URL + '/faqs/faq_questions/view/' + id,
              {
                size: 'lg'
              }
          );
          if (angular.isObject($event)) {
            $event.preventDefault();
            $event.stopPropagation();
          }
        };
      }]);


/**
 * FaqsModal daialog controller
 */
NetCommonsApp.controller('FaqsModal.view',
    ['$scope', '$uibModalInstance', function($scope, $uibModalInstance) {

      /**
       * dialog cancel
       *
       * @return {void}
       */
      $scope.cancel = function() {
        $uibModalInstance.dismiss('cancel');
      };
    }]);
