(function () {
  'use strict';

  function init(scope) {
    var root = scope || document;
    var hoverCapable = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    var widgets = root.querySelectorAll('.fotohog-stack');
    var clamp = function (value, min, max) {
      return Math.max(min, Math.min(max, value));
    };

    var parseCssPx = function (value) {
      var num = parseFloat(value || '0');
      return Number.isFinite(num) ? num : 0;
    };

    var updateHoverGridScale = function (widget) {
      if (!widget.classList.contains('grid-on-hover')) {
        return;
      }

      var autoScaleEnabled = (widget.dataset.autoscaleEnabled || '0') === '1';
      if (!autoScaleEnabled) {
        widget.style.setProperty('--fotohog-hover-grid-scale', '1');
        return;
      }

      var cards = Array.prototype.slice.call(widget.querySelectorAll('.fotohog-card'));
      if (!cards.length) {
        return;
      }

      var minLeft = Infinity;
      var maxRight = -Infinity;
      var minTop = Infinity;
      var maxBottom = -Infinity;

      cards.forEach(function (card) {
        var gx = parseCssPx(card.style.getPropertyValue('--gx'));
        var gy = parseCssPx(card.style.getPropertyValue('--gy'));
        var w = card.offsetWidth || 0;
        var h = card.offsetHeight || 0;
        var left = gx - w / 2;
        var right = gx + w / 2;
        var top = gy - h / 2;
        var bottom = gy + h / 2;

        if (left < minLeft) minLeft = left;
        if (right > maxRight) maxRight = right;
        if (top < minTop) minTop = top;
        if (bottom > maxBottom) maxBottom = bottom;
      });

      var neededW = Math.max(1, maxRight - minLeft);
      var neededH = Math.max(1, maxBottom - minTop);
      var availableW = Math.max(1, widget.clientWidth - 8);
      var availableH = Math.max(1, widget.clientHeight - 8);
      var minScale = parseFloat(widget.dataset.autoscaleMin || '0.72');
      if (!Number.isFinite(minScale)) {
        minScale = 0.72;
      }
      minScale = clamp(minScale, 0.4, 1);
      var scale = Math.min(1, availableW / neededW, availableH / neededH);
      scale = Math.max(minScale, scale);

      widget.style.setProperty('--fotohog-hover-grid-scale', scale.toFixed(4));
    };

    widgets.forEach(function (widget) {
      updateHoverGridScale(widget);

      if (widget.dataset.fotohogBound === '1') {
        return;
      }
      widget.dataset.fotohogBound = '1';

      widget.addEventListener('pointerenter', function () {
        updateHoverGridScale(widget);
      }, { passive: true });
    });

    if (!hoverCapable) {
      return;
    }

    var tiltWidgets = root.querySelectorAll('.fotohog-stack.has-proximity-tilt');
    tiltWidgets.forEach(function (widget) {
      if (widget.dataset.proximityBound === '1') {
        return;
      }
      widget.dataset.proximityBound = '1';

      var radius = parseFloat(widget.dataset.proximityRadius || '260');
      var maxTilt = parseFloat(widget.dataset.proximityTilt || '9');
      var cards = Array.prototype.slice.call(widget.querySelectorAll('.fotohog-card'));

      if (!cards.length) {
        return;
      }

      var reset = function () {
        cards.forEach(function (card) {
          card.style.setProperty('--rx', '0deg');
          card.style.setProperty('--ry', '0deg');
        });
      };

      widget.addEventListener('pointermove', function (event) {
        var mouseX = event.clientX;
        var mouseY = event.clientY;

        cards.forEach(function (card) {
          var rect = card.getBoundingClientRect();
          var centerX = rect.left + rect.width / 2;
          var centerY = rect.top + rect.height / 2;
          var dx = mouseX - centerX;
          var dy = mouseY - centerY;
          var distance = Math.sqrt(dx * dx + dy * dy);

          if (distance > radius) {
            card.style.setProperty('--rx', '0deg');
            card.style.setProperty('--ry', '0deg');
            return;
          }

          var intensity = 1 - distance / radius;
          var tiltY = clamp((dx / (radius * 0.5)) * maxTilt * intensity, -maxTilt, maxTilt);
          var tiltX = clamp((-dy / (radius * 0.5)) * maxTilt * intensity, -maxTilt, maxTilt);

          card.style.setProperty('--rx', tiltX.toFixed(2) + 'deg');
          card.style.setProperty('--ry', tiltY.toFixed(2) + 'deg');
        });
      }, { passive: true });

      widget.addEventListener('pointerleave', reset, { passive: true });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      init(document);
    });
  } else {
    init(document);
  }

  if (window.jQuery && window.elementorFrontend) {
    window.jQuery(window).on('elementor/frontend/init', function () {
      window.elementorFrontend.hooks.addAction('frontend/element_ready/fotohog_stack.default', function ($scope) {
        init($scope[0]);
      });
    });
  }

  window.addEventListener('resize', function () {
    init(document);
  }, { passive: true });
})();
