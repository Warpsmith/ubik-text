<?php // ==== TRUNCATE ==== //

// Truncate text; a replacement for the native `wp_trim_words` function; @TODO: multibyte support
// @filter: ubik_text_truncate
// @filter: ubik_text_truncate_strip
// @filter: ubik_text_truncate_length
// @filter: ubik_text_truncate_ending
// @filter: ubik_text_truncate_delimiter
function ubik_text_truncate(
  $text = '',
  $words = 55,
  $ending = '... ',
  $delimiter = '. ',
  $strip = ''
) {

  // Exit early
  if ( empty( $text ) )
    return;

  // Filter the number of words returned
  $words = (int) apply_filters( 'ubik_text_truncate_length', $words );

  // Filter the ending
  $ending = (string) apply_filters( 'ubik_text_truncate_ending', $ending );

  // Filter the delimiter
  $delimiter = (string) apply_filters( 'ubik_text_truncate_delimiter', $delimiter );

  // Check the $strip array
  if ( empty( $strip ) || !is_array( $strip ) )
    $strip = apply_filters( 'ubik_text_truncate_strip', array( 'asides', 'code', 'footnotes', 'tags' ) ); // Note: 'shortcodes' *not* included by default

  // Shortcode handler; this one goes first as shortcodes may introduce HTML and other stuff that we may want to strip later
  if ( in_array( 'shortcodes', $strip ) ) {
    $text = strip_shortcodes( $text );
  } else {
    $text = do_shortcode( $text );
  }

  // Strip opening asides
  if ( in_array( 'asides', $strip ) )
    $text = ubik_text_strip_asides( $text );

  // Strip code wrapped in `pre` and `code` elements
  if ( in_array( 'code', $strip ) )
    $text = ubik_text_strip_code( $text );

  // Strip footnotes wrapped in `sup` elements
  if ( in_array( 'footnotes', $strip ) )
    $text = ubik_text_strip_footnotes( $text );

  // Strip all tags (but keep the contents)
  if ( in_array( 'tags', $strip ) )
    $text = strip_tags( $text ); // Abandoning `wp_strip_all_tags` here...

  // Strip any remaining tags
  $text = str_replace( ']]>', ']]&gt;', $text );

  // A modification of the core `wp_trim_words` function
  if ( 'characters' == _x( 'words', 'word count: words or characters?' ) && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {

    // This code block handles character-based languages (e.g. Chinese)
    $text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
    preg_match_all( '/./u', $text, $words_array );
    $words_array = array_slice( $words_array[0], 0, $words + 1 );
    $sep = '';

  } else {

    // This handles non-character based input the default WordPress way
    $words_array = preg_split( "/[\n\r\t ]+/", $text, $words + 1, PREG_SPLIT_NO_EMPTY );
    $sep = ' ';
  }

  // Save the final count
  $words_count = count( $words_array );

  // Trim the array to the desired word count
  if ( $words_count > $words )
    array_pop( $words_array );

  // Make a string from the array of words
  $text = implode( $sep, $words_array );

  // Strip out trailing punctuation and add the excerpt ending as needed
  if ( $words_count >= $words ) {
    if ( !preg_match( '/[.!?]$/u', $text ) ) { // Could also try \p{P} for punctuation; @TODO: i18n
      $text = preg_replace('/^[\p{P}|\p{S}|\s]+|[\p{P}|\p{S}|\s]+$/u', '', $text ) . $ending;
    }
  } else {
    if ( !preg_match( '/[.!?]$/u', $text ) ) {
      $text = preg_replace('/^[\p{P}|\p{S}|\s]+|[\p{P}|\p{S}|\s]+$/u', '', $text ) . $delimiter;
    }
  }

  return apply_filters( 'ubik_text_truncate', $text );
}
