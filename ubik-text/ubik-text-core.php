<?php // ==== TEXT ==== //

// A library of functions for manipulating text; used by Ubik Excerpt, Ubik SEO, and maybe a few more

// Text replacement; expands on core `wptexturize` function to provide additional typographic shorthand and clean-up using simple matching
// Before adding anything be sure to consult the list of existing replacements: http://codex.wordpress.org/Function_Reference/wptexturize
// @filter: ubik_text_replacement
// @filter: ubik_text_replacement_groups
function ubik_text_replacement( $content = '' ) {

  // Initialize the map we will be using later
  $map = array();

  // Allows for end-users to disable entire groups of replacements (also works with custom additions)
  $groups = apply_filters( 'ubik_text_replacement_groups', array( 'dividers', 'typography', 'figures', 'currency', 'ip', 'hearts', 'arrows', 'zerowidth' ) );

  // This array is wrapped in a filter which means you can easily extend what replacements are done using the filter system and array_merge()
  // Please note that these replacement rules are applied in sequence
  $replacements = apply_filters( 'ubik_text_replacement', array(
    'dividers' => array(
      '<p>&lt;3</p>'  => '<p class="divider heart">&#x2665;</p>'          // <3 = Normal heart: http://codepoints.net/U+2665
    , '<p>/&lt;3</p>' => '<p class="divider floral-heart">&#x2766;</p>'   // Floral heart: https://en.wikipedia.org/wiki/Fleuron_(typography)
    , '<p>*</p>'      => '<p class="divider asterism">&#x2042;</p>'       // Asterisk on its own line = Asterism: https://en.wikipedia.org/wiki/Asterism_(typography)
    )
  , 'typography' => array(
      '&#8212;'       => '&#8202;&#8212;&#8202;'                          // Surround em dashes in hair spaces
    , '&#8211;'       => '&#8202;&#8211;&#8202;'                          // Surround en dashes in hair spaces
    , '/No.'          => '<span class="numero">&#8470;</span>'            // Numero sign: https://en.wikipedia.org/wiki/Numero_sign
    , '/|P'           => '<span class="pilcrow">&#x00B6;</span>'          // Pilcrow: https://en.wikipedia.org/wiki/Pilcrow
    , '/|S'           => '<span class="section">&#x00A7;</span>'          // Section symbol: https://en.wikipedia.org/wiki/Section_sign
    , '?!'            => '&#8253;'                                        // Interrobang: https://en.wikipedia.org/wiki/Interrobang
    , ' & '           => ' <span class="ampersand">&</span> '             // A styling hook for ampersands
    )
  , 'figures' => array(
      ' 1/2 '     => '&#189; '
    , ' 1/4 '     => '&#188; '
    , ' 3/4 '     => '&#190; '
    , '+/-'       => '&#177;&#8202;'
    , '+-'        => '&#177;&#8202;'
    , '^oC'       => '&#176;C'
    , '^oF'       => '&#176;F'
    )
  , 'currency' => array(
      'CNY '      => '&#165;&#8202;'  // Chinese renminbi
    , 'EUR '      => '&#8364;&#8202;' // Euro
    , 'GBP '      => '&#163;&#8202;'  // Pound
    , 'JPY '      => '&#165;&#8202;'  // Japanese yen
    , 'KRW '      => '&#8361;&#8202;' // Korean won
    , 'THB '      => '&#3647;&#8202;' // Thai baht
    )
  , 'ip' => array(
      '(c)'       => '&#169;'         // Copyright: https://en.wikipedia.org/wiki/Copyright_symbol
    , '(p)'       => '&#8471;'        // Sound recording: https://en.wikipedia.org/wiki/Sound_recording_copyright_symbol
    , '(r)'       => '&#174;'         // Registed trademark: https://en.wikipedia.org/wiki/Registered_trademark_symbol
    , '(sm)'      => '&#8480;'        // Service mark: https://en.wikipedia.org/wiki/Service_mark_symbol
    )
  , 'hearts' => array(
      ' &lt;3 '       => ' <span class="heart">&#x2665;</span> '          // <3 = Normal heart: http://codepoints.net/U+2665
    , ' &lt;33 '      => ' <span class="heart">&#x2764;</span> '          // <33 = Heavy black heart: http://codepoints.net/U+2764
    , '/&lt;3'        => '<span class="floral-heart">&#x2766;</span>'     // Floral heart: https://en.wikipedia.org/wiki/Fleuron_(typography)
    , '/-&lt;3'       => '<span class="floral-bullet">&#x2767;</span>'    // Floral heart bullet point: https://en.wikipedia.org/wiki/Fleuron_(typography)
    )
  , 'arrows' => array(
      '==>'       => '&nbsp;&rarr;'
    , '==&gt;'    => '&nbsp;&rarr;'
    , '&lt;=='    => '&larr;&nbsp;'
    )
  , 'zerowidth' => array(
      '/ZWSP'         => '&#x200B;'   // Zero-width space: https://en.wikipedia.org/wiki/Zero-width_space
    , '/ZWNJ'         => '&#x200C;'   // Zero-width non-joiner: https://en.wikipedia.org/wiki/Zero-width_non-joiner
    , '/ZWJ'          => '&#x200D;'   // Zero-width joiner: https://en.wikipedia.org/wiki/Zero-width_joiner
    )
  ) );

  // Iterate through and build the main array for text replacement
  foreach ( $replacements as $group => $data ) {
    if ( in_array( $group, $groups ) )
      $map = array_merge( $map, $data );
  }

  // Iterate through the map object and replace
  foreach ( $map as $pattern => $replacement ) {
    $content = str_replace( $pattern, $replacement, $content );
  }

  return $content;
}



// Strip opening `aside` elements from a string
// Use case: allows the use of `<aside>This post is a continuation of...</aside>` without this throw-away text dominating the feed and meta descriptions
function ubik_text_strip_asides( $text ) {
  if ( strpos( $text, '<aside' ) < 10 ) // Anywhere in the first 10 characters
    $text = preg_replace( '/<aside>(.*?)<\/aside>/si', '', $text, 1 );
  return $text;
}



// Strip code blocks wrapped in `pre` and `code` elements
function ubik_text_strip_code( $text ) {

  // Handles `<pre><code class="language-` style of markup via Markdown
  $text = preg_replace( '/<pre><code(.*?)<\/code><\/pre>/siu', '', $text );

  // Handles `script` and `style` tags (which shouldn't be in our content at all); adapted from WP core
  $text = preg_replace( '/<(script|style)[^>]*?>.*?<\/\/(script|style)>/siu', '', $text );

  return $text;
}



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
    $strip = apply_filters( 'ubik_text_truncate_strip', array( 'asides', 'code', 'tags' ) ); // Note: 'shortcodes' *not* included by default

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

  // Strip all tags
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
