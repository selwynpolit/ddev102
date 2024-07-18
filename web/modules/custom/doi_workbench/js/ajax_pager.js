// (function ($, Drupal) {
//   Drupal.behaviors.doiWorkbenchPagerAjax = {
//     attach: function (context, settings) {
//       $('.pager__item a', context).once('ajax_pager').on('click', function (e) {
//         e.preventDefault();
//         var href = $(this).attr('href');
//         // Perform AJAX request to the href and update the block content.
//         $.ajax({
//           url: href,
//           type: 'GET',
//           success: function (data) {
//             // Replace the block content with the new content from the server.
//             // You might need to adjust the selector based on your block's structure.
//             $('#block-id').html($(data).find('#block-id').html());
//             Drupal.attachBehaviors('#block-id');
//           }
//         });
//       });
//     }
//   };
// })(jQuery, Drupal);


// (function ($, Drupal) {
//   Drupal.behaviors.doiWorkbenchPagerAjax = {
//     attach: function (context, settings) {
//       $('.pager__item a', context).each(function () {
//         if (!$(this).hasClass('ajax-pager-processed')) {
//           $(this).addClass('ajax-pager-processed').on('click', function (e) {
//             e.preventDefault();
//             var href = $(this).attr('href');
//             // Perform AJAX request to the href and update the block content.
//             $.ajax({
//               url: href,
//               type: 'GET',
//               success: function (data) {
//                 // Replace the block content with the new content from the server.
//                 // You might need to adjust the selector based on your block's structure.
//                 $('#block-id').html($(data).find('#block-id').html());
//                 Drupal.attachBehaviors('#block-id');
//               }
//             });
//           });
//         }
//       });
//     }
//   };
// })(jQuery, Drupal);


// (function ($, Drupal) {
//   Drupal.behaviors.doiWorkbenchPagerAjax = {
//     attach: function (context, settings) {
//       $('.pager__item a', context).each(function () {
//         if (!$(this).hasClass('ajax-pager-processed')) {
//           $(this).addClass('ajax-pager-processed').on('click', function (e) {
//             e.preventDefault();
//             var href = $(this).attr('href');
//             var page = href.split('page=')[1]; // Assuming the href contains 'page=' parameter
//
//             // Perform AJAX request to the custom endpoint.
//             $.ajax({
//               url: '/doi-workbench/ajax-content?page=' + page,
//               type: 'GET',
//               success: function (data) {
//                 $('#block-doi-workbench-editor-menu-links').html(data);
//                 Drupal.attachBehaviors('#block-doi-workbench-editor-menu-links');
//               }
//             });
//           });
//         }
//       });
//     }
//   };
// })(jQuery, Drupal);


// (function ($, Drupal) {
//   Drupal.behaviors.customPagerAjax = {
//     attach: function (context, settings) {
//       $('.pager__item a', context).each(function () {
//         var $this = $(this);
//         if ($.fn.once) {
//           // If .once() is available, use it.
//           $this.once('custom-pager-ajax').on('click', function (e) {
//             e.preventDefault();
//             var currentPage = $this.attr('data-page-number');
//             makeAjaxCall(currentPage);
//           });
//         } else if (!$this.hasClass('custom-pager-ajax-processed')) {
//           // Fallback: Use a class to ensure the event handler is attached only once.
//           $this.addClass(['custom-pager-ajax-processed','use-ajax']).on('click', function (e) {
//             e.preventDefault();
//             var currentPage = $this.attr('data-page-number');
//             makeAjaxCall(currentPage);
//           });
//         }
//       });
//
//       function makeAjaxCall(currentPage) {
//         $.ajax({
//           //url: '/doi-workbench/ajax-pager',
//           url: '/doi-workbench/ajax-callback',
//           type: 'GET',
//           headers: {'X-Current-Page': currentPage},
//           success: function (response) {
//             $('#menu-listing-ajax-wrapper').html(response);
//           }
//         });
//       }
//     }
//   };
// })(jQuery, Drupal);


(function ($, Drupal) {
  Drupal.behaviors.customPagerAjax = {
    attach: function (context, settings) {
      $('.pager__item a', context).each(function () {
        var $this = $(this);
        if (!$this.hasClass('custom-pager-ajax-processed')) {
          $this.addClass(['custom-pager-ajax-processed', 'use-ajax']);
        }
      });
    },
  };
})(jQuery, Drupal);
