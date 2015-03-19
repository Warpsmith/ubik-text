# Ubik Text

Simple text processing functions for WordPress. Most of these functions are used internally but you can hook into them in your theme as well.

* `ubik_text_replace()` extends `wptexturize` with additional text replacement shortcuts (browse the source for a full list or look below for a few examples).
* `ubik_text_truncate()` truncates, neatens, and trims text for excerpts and meta descriptions, among other things.
* Strip opening `<aside>` tags from post contents; this way you can open a post with an aside (e.g. "This post was originally written three years ago...") without it dominating SEO.
* Strip code, footnotes (added by Markdown Extra), paragraphs surrounding media, and orphaned `<!--more-->` tags.

Part of the [Ubik](https://github.com/synapticism/ubik) family of WordPress components.



## Installation

Install via Composer:

```composer require synapticism/ubik-text```

Install via Bower (warning: no dependency management):

```bower install https://github.com/synapticism/ubik-text.git -D```

See [Pendrell](https://github.com/synapticism/pendrell) for an example of integration and usage.



## Filters

Browse the source for a current list

```
ubik_text_replace
ubik_text_replace_simple
ubik_text_truncate
ubik_text_truncate_strip
ubik_text_truncate_length
ubik_text_truncate_ending
ubik_text_truncate_delimiter
```



## Replacement map

A few examples of what the `ubik_text_replace()` function handles:

| Text | Replacement |
| ---- | ----------- |
| `<p>*</p>` | `<p class="divider asterism">&#x2042;</p>` |
| `archaeology` | archæology |
| `jalapeno` | jalapeño |
| `naive` | naïve |
| `==>` | → |
| `EUR` | € |
| `^oC` | °C |
| `(c)` | © |
| `/No.` | № |
| `/|P` | ¶ |
| ` <3 ` | ♥ |
| `/<3` | ❧ |



## License

GPLv3.
