<?php // ==== STRIP ==== //

// Strip opening `aside` elements from a string
// Use case: allows the use of `<aside>This post is a continuation of...</aside>` without this throw-away text dominating the feed and meta descriptions
function ubik_text_strip_asides( $text ) {
  if ( strpos( $text, '<aside' ) !== false && strpos( $text, '<aside' ) < 16 ) // Only strip asides in the first 16 characters; this accounts for some of the gunk WP throws in when processing post content for RSS etc.
    $text = preg_replace( '/<aside(.*?)<\/aside>/si', '', $text, 1 );
  return $text;
}

// Strip code blocks wrapped in `pre` and `code` elements
function ubik_text_strip_code( $text ) {
  $text = preg_replace( '/<pre><code(.*?)<\/code><\/pre>/siu', '', $text ); // Handles `<pre><code class="language-` style of markup via Markdown
  $text = preg_replace( '/<(script|style)[^>]*?>.*?<\/\/(script|style)>/siu', '', $text ); // Handles `script` and `style` tags (which shouldn't be in our content at all); adapted from WP core
  return $text;
}

// Playing around with a function to strip paragraph tags off of images and such
function ubik_text_strip_media_p( $content ) {
  $content = preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
  $content = preg_replace( '/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content );
  return $content;
}

// Strip paragraph tags from orphaned more tags; mainly a hack to address more tags placed next to image shortcodes
function ubik_text_strip_more_orphan( $content ) {
  $content = preg_replace( '/<p><span id="more-[0-9]*?"><\/span><\/p>/', '', $content );
  $content = preg_replace( '/<p><span id="more-[0-9]*?"><\/span>(<(div|img|figure)[\s\S]*?)<\/p>/', '$1', $content );
  $content = preg_replace( '/<p>(<(div|img|figure)[\s\S]*?)<span id="more-[0-9]*?"><\/span><\/p>/', '$1', $content );
  return $content;
}
