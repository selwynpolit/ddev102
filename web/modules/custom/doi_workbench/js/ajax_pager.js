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


(function ($, Drupal) {
  Drupal.behaviors.doiWorkbenchPagerAjax = {
    attach: function (context, settings) {
      $('.pager__item a', context).each(function () {
        if (!$(this).hasClass('ajax-pager-processed')) {
          $(this).addClass('ajax-pager-processed').on('click', function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var page = href.split('page=')[1]; // Assuming the href contains 'page=' parameter

            // Perform AJAX request to the custom endpoint.
            $.ajax({
              url: '/doi-workbench/ajax-content?page=' + page,
              type: 'GET',
              success: function (data) {
                $('#block-doi-workbench-editor-menu-links').html(data);
                Drupal.attachBehaviors('#block-doi-workbench-editor-menu-links');
              }
            });
          });
        }
      });
    }
  };
})(jQuery, Drupal);
