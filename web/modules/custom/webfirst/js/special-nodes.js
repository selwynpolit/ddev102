// Add the class special-nodes to my custom block
(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.specialNodesBlock = {
    attach: function (context, settings) {
      $('.block-webfirst-latest-special-nodes', context).addClass('special-nodes');
    }
  };
})(jQuery, Drupal);
