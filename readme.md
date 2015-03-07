# Ubik Text

Simple text processing functions for WordPress. Most of these functions are used internally but you can hook into them in your theme as well.

* `ubik_text_truncate()` truncates, neatens, and trims text for excerpts and meta descriptions, among other things.
* `ubik_text_replacement()` extends `wptexturize` with additional text replacement shortcuts (browse the source for a full list).
* Strip opening `<aside>` tags from post contents; this way you can open a post with an aside (e.g. "This post was originally written three years ago...") without it dominating SEO.
* Strip code.

Part of the [Ubik](https://github.com/synapticism/ubik) family of WordPress components.



## Installation

Install via Composer:

```composer require synapticism/ubik-text```

Install via Bower (warning: no dependency management):

```bower install https://github.com/synapticism/ubik-text.git -D```

See [Pendrell](https://github.com/synapticism/pendrell) for an example of integration and usage.



## Configuration

Two ways to configure this component:

* Set constants and variables in `functions.php` (or some equivalent in your theme) prior to loading this component.
* Copy `ubik-text-config-defaults.php` to `ubik-text-config.php` and modify to suit your needs.

Defaults (browse the source for the latest; this list may be outdated):

```
// Nothing yet...
```

Filters:

```
// Browse the source for a current list
ubik_text_replacement
ubik_text_replacement_groups
ubik_text_truncate
ubik_text_truncate_strip
ubik_text_truncate_length
ubik_text_truncate_ending
ubik_text_truncate_delimiter
```



## License

GPLv3.
