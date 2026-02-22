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

    var updateHoverSliderStart = function (widget) {
      if (!widget.classList.contains('slider-on-hover')) {
        return;
      }
      widget.dataset.hoverSlideIndex = '0';

      var cards = Array.prototype.slice.call(widget.querySelectorAll('.fotohog-card'));
      if (!cards.length) {
        widget.style.setProperty('--fotohog-slider-hover-shift', '0px');
        return;
      }

      var firstLeft = Infinity;
      cards.forEach(function (card) {
        var sx = parseCssPx(card.style.getPropertyValue('--sx'));
        var w = card.offsetWidth || card.getBoundingClientRect().width || 0;
        var left = sx - (w / 2);
        if (left < firstLeft) {
          firstLeft = left;
        }
      });

      if (!Number.isFinite(firstLeft)) {
        widget.style.setProperty('--fotohog-slider-hover-shift', '0px');
        return;
      }

      var targetLeft = (-widget.clientWidth / 2) + 4;
      var shift = targetLeft - firstLeft;
      widget.style.setProperty('--fotohog-slider-hover-shift', shift.toFixed(2) + 'px');
    };

    var setHoverSliderIndex = function (widget, index) {
      if (!widget.classList.contains('slider-on-hover')) {
        return;
      }

      var cards = Array.prototype.slice.call(widget.querySelectorAll('.fotohog-card'));
      if (!cards.length) {
        widget.dataset.hoverSlideIndex = '0';
        widget.style.setProperty('--fotohog-slider-hover-shift', '0px');
        return;
      }

      var safeIndex = Math.max(0, Math.min(cards.length - 1, Number(index) || 0));
      var card = cards[safeIndex];
      var sx = parseCssPx(card.style.getPropertyValue('--sx'));
      var w = card.offsetWidth || card.getBoundingClientRect().width || 0;
      var left = sx - (w / 2);
      var targetLeft = (-widget.clientWidth / 2) + 4;
      var shift = targetLeft - left;

      widget.dataset.hoverSlideIndex = String(safeIndex);
      widget.style.setProperty('--fotohog-slider-hover-shift', shift.toFixed(2) + 'px');
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
      updateHoverSliderStart(widget);

      if (widget.dataset.fotohogBound === '1') {
        return;
      }
      widget.dataset.fotohogBound = '1';

      widget.addEventListener('pointerenter', function () {
        updateHoverGridScale(widget);
        updateHoverSliderStart(widget);
      }, { passive: true });
    });

    var navs = root.querySelectorAll('.fotohog-slider-nav');
    navs.forEach(function (nav) {
      var widget = nav.previousElementSibling;
      if (!widget || !widget.classList || !widget.classList.contains('fotohog-stack')) {
        return;
      }

      if (nav.dataset.fotohogNavBound === '1') {
        return;
      }
      nav.dataset.fotohogNavBound = '1';

      var prevBtn = nav.querySelector('.fotohog-slider-nav-btn.is-prev');
      var nextBtn = nav.querySelector('.fotohog-slider-nav-btn.is-next');
      var dots = Array.prototype.slice.call(nav.querySelectorAll('.fotohog-slider-dot'));
      if (!prevBtn || !nextBtn) {
        return;
      }

      var getStep = function () {
        var firstCard = widget.querySelector('.fotohog-card');
        if (!firstCard) {
          return Math.max(160, widget.clientWidth * 0.8);
        }
        var cardWidth = firstCard.getBoundingClientRect().width || firstCard.offsetWidth || 220;
        return Math.max(120, cardWidth + 12);
      };

      var getCards = function () {
        return Array.prototype.slice.call(widget.querySelectorAll('.fotohog-card'));
      };

      var isScrollableSlider = function () {
        var style = window.getComputedStyle(widget);
        return (widget.scrollWidth - widget.clientWidth) > 2 && (style.overflowX === 'auto' || style.overflowX === 'scroll');
      };

      var setDotState = function (activeIndex) {
        if (!dots.length) {
          return;
        }

        dots.forEach(function (dot, index) {
          var active = index === activeIndex;
          dot.classList.toggle('is-active', active);
          dot.setAttribute('aria-pressed', active ? 'true' : 'false');
        });

        if (dots[activeIndex] && typeof dots[activeIndex].scrollIntoView === 'function') {
          dots[activeIndex].scrollIntoView({ inline: 'nearest', block: 'nearest' });
        }
      };

      var updateNavState = function () {
        if (isScrollableSlider()) {
          var maxScroll = Math.max(0, widget.scrollWidth - widget.clientWidth);
          var current = widget.scrollLeft;
          prevBtn.disabled = current <= 2;
          nextBtn.disabled = current >= (maxScroll - 2);

          if (dots.length) {
            var cards = getCards();
            var activeIndex = 0;
            var bestDistance = Infinity;

            cards.forEach(function (card, index) {
              var distance = Math.abs((card.offsetLeft || 0) - current);
              if (distance < bestDistance) {
                bestDistance = distance;
                activeIndex = index;
              }
            });

            setDotState(activeIndex);
          }
          return;
        }

        if (widget.classList.contains('slider-on-hover')) {
          var cardCount = getCards().length;
          var maxIndex = Math.max(0, cardCount - 1);
          var hoverIndex = parseInt(widget.dataset.hoverSlideIndex || '0', 10);
          if (!Number.isFinite(hoverIndex)) {
            hoverIndex = 0;
          }
          hoverIndex = Math.max(0, Math.min(maxIndex, hoverIndex));
          prevBtn.disabled = hoverIndex <= 0;
          nextBtn.disabled = hoverIndex >= maxIndex;
          setDotState(hoverIndex);
          return;
        }

        prevBtn.disabled = true;
        nextBtn.disabled = true;
      };

      var moveHoverSlider = function (direction) {
        var hoverIndex = parseInt(widget.dataset.hoverSlideIndex || '0', 10);
        if (!Number.isFinite(hoverIndex)) {
          hoverIndex = 0;
        }
        setHoverSliderIndex(widget, hoverIndex + direction);
        updateNavState();
      };

      var scrollByStep = function (direction) {
        widget.scrollBy({
          left: getStep() * direction,
          behavior: 'smooth'
        });
      };

      prevBtn.addEventListener('click', function () {
        if (isScrollableSlider()) {
          scrollByStep(-1);
          return;
        }
        moveHoverSlider(-1);
      });

      nextBtn.addEventListener('click', function () {
        if (isScrollableSlider()) {
          scrollByStep(1);
          return;
        }
        moveHoverSlider(1);
      });

      dots.forEach(function (dot) {
        dot.addEventListener('click', function () {
          var index = parseInt(dot.dataset.slideIndex || '0', 10);
          if (!Number.isFinite(index) || index < 0) {
            index = 0;
          }
          var cards = widget.querySelectorAll('.fotohog-card');
          var target = cards[index];
          if (!target) {
            return;
          }
          if (isScrollableSlider()) {
            widget.scrollTo({
              left: target.offsetLeft || 0,
              behavior: 'smooth'
            });
            return;
          }

          setHoverSliderIndex(widget, index);
          updateNavState();
        });
      });

      widget.addEventListener('scroll', updateNavState, { passive: true });
      widget.addEventListener('pointerenter', updateNavState, { passive: true });
      window.addEventListener('resize', updateNavState, { passive: true });
      updateNavState();
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
