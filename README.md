# Fotohog for Elementor

A custom WordPress plugin that adds a Polaroid-style photo stack widget for Elementor.

## Overview

`Fotohog for Elementor` adds a visual widget where images can be displayed as:

- a stacked pile of photos
- a stack that expands into a grid on hover
- an always-on grid

The widget supports captions, lightbox, custom links, dark card theme, grayscale modes, proximity tilt effects, and smart hover autoscaling to keep the grid inside its container.

## Features

- Elementor widget: **Fotohog**
- Polaroid-style photo cards
- Display modes:
  - `Stack only`
  - `Stack to grid on hover`
  - `Grid only`
- Per-photo settings:
  - custom caption
  - click action (`None`, `Open lightbox`, `Open URL`)
- Light/Dark card theme
- Optional texture card surface with one or more PNG backgrounds
- Image color modes:
  - Normal static
  - Black and white static
  - Black and white to color
  - Color to black and white
- Mouse proximity bend effect (desktop)
- Hover-grid auto-scale to fit container
  - minimum scale option
  - easing option
- Mobile responsiveness with touch-friendly fallbacks
- Legacy gallery fallback support

## Requirements

- WordPress 6.0+
- PHP 7.4+
- Elementor plugin installed and activated

## Installation

1. Copy the plugin folder into `wp-content/plugins/`.
2. Activate **Fotohog for Elementor** in WordPress Admin.
3. Open a page with Elementor.
4. Search for the widget: **Fotohog**.

## Usage

1. Add photos in the `Photos` repeater.
2. Choose your `Display mode`.
3. Configure style settings:
  - photo width
  - spread / rotation
  - grid columns / gap
  - animation preset
  - card theme
  - card surface (`Classic polaroid` or `Texture from PNG`)
  - texture PNG list + texture fit (when texture mode is selected)
  - color mode
4. Optional:
  - enable proximity bend
  - enable autoscale and adjust minimum scale + easing

## Notes

- If Elementor is not active, the plugin shows an admin notice.
- Lightbox behavior uses Elementor frontend lightbox attributes.
- On touch devices, hover-based effects gracefully fallback to readable grid behavior.

## License

No license defined yet.

## Contributing

Issues and pull requests are welcome.
