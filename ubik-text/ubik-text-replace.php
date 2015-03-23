<?php // ==== REPLACE ==== //

// Text replacement; before adding anything be sure to consult the list of existing replacements: http://codex.wordpress.org/Function_Reference/wptexturize
// @filter: ubik_text_replace
function ubik_text_replace( $content = '' ) {
  return apply_filters( 'ubik_text_replace', ubik_text_replace_simple( $content ) );
}



// Simple text replacement; expands on core `wptexturize` function to provide additional typographic shorthand and clean-up using simple matching
// @filter: ubik_text_replace_simple
function ubik_text_replace_simple( $content = '' ) {

  // Initialize the map we will be using later
  $map = array();

  // This array is wrapped in a filter which means you can easily extend what replacements are done using the filter system and array_merge()
  // Please note that these replacement rules are applied in sequence
  $replacements = apply_filters( 'ubik_text_replace_simple', array(
    'dividers'    => ubik_text_replace_dividers()
  , 'diacritics'  => ubik_text_replace_diacritics()
  , 'typography'  => ubik_text_replace_typography()
  , 'figures'     => ubik_text_replace_figures()
  , 'currencies'  => ubik_text_replace_currencies()
  , 'ip'          => ubik_text_replace_ip()
  , 'hearts'      => ubik_text_replace_hearts()
  , 'arrows'      => ubik_text_replace_arrows()
  , 'custom'      => ubik_text_replace_custom()
  ) );

  // Iterate through and build the main array for text replacement
  foreach ( $replacements as $group => $data ) {
    $map = array_merge( $map, $data );
  }

  // Return content with all replacements made
  return str_replace( array_keys( $map ), array_values( $map ), $content );
}
add_filter( 'ubik_text_replace', 'ubik_text_replace_simple' );



// Dividers (should be first)
function ubik_text_replace_dividers() {
  return apply_filters( 'ubik_text_replace_dividers', array(
    '<p>&lt;3</p>'  => '<p class="divider heart">&#x2665;</p>'          // <3 = Normal heart: http://codepoints.net/U+2665
  , '<p>/&lt;3</p>' => '<p class="divider floral-heart">&#x2766;</p>'   // Floral heart: https://en.wikipedia.org/wiki/Fleuron_(typography)
  , '<p>*</p>'      => '<p class="divider asterism">&#x2042;</p>'       // Asterisk on its own line = Asterism: https://en.wikipedia.org/wiki/Asterism_(typography)
  ) );
}



// Common English words with diacritics; for reference: https://en.wiktionary.org/wiki/Appendix:English_words_with_diacritics
function ubik_text_replace_diacritics() {
  return apply_filters( 'ubik_text_replace_diacritics', array(
    // Some of these have had the first character lopped off to account for differences in capitalization
    'Aegis'         => '&AElig;gis'
  , 'aegis'         => '&aelig;gis'
  , 'Aeolian'       => '&AElig;olian'
  , 'aeolian'       => '&aelig;olian'
  , 'Aeon'          => '&AElig;on'
  , 'aeon'          => '&aelig;on'
  , 'aesar'         => '&aelig;sar' // Caesar
  , 'Aesthe'        => '&AElig;sthet' // Aesthetic, aesthete, anaesthesia
  , 'aesthe'        => '&aelig;sthet' // Aesthetic, aesthete, anaesthesia
  , 'Aether'        => '&AElig;ther'
  , 'aether'        => '&aelig;ther'
  , 'Algae'         => 'Alg&aelig;'
  , 'algae'         => 'alg&aelig;'
  , 'naesthes'      => 'n&aelig;s' // Anaesthesia
  , 'ntennae'       => 'ntenn&aelig;' // Antennae
  , 'Archae'        => 'Arch&aelig;'
  , 'archae'        => 'arch&aelig;'
  , 'cyclopaed'     => 'cyclop&aelig;d' // Encyclopaedia
  , 'cycloped'      => 'cyclop&aelig;d' // Encyclopedia
  , 'daemon'        => 'd&aelig;mon' // Daemon
  , 'iaeresis'      => 'i&aelig;resis' // Diaeresis
  , 'formulae'      => 'formul&aelig;' // Formulae
  , 'edieval'       => 'edi&aelig;val' // Medieval
  , 'ebulae'        => 'ebul&aelig;' // Nebulae
  , 'novae'         => 'nov&aelig;' // (Super)novae
  , 'Paleo'         => 'Pal&aelig;o'
  , 'paleo'         => 'pal&aelig;o'
  , 'angaea'        => 'ang&aelig;a' // Pangaea
  , 'personae'      => 'person&aelig;' // Personae
  , 'rimeval'       => 'rim&aelig;val' // Primeval
  , ' vitae'        => ' vit&aelig;' // Curriculum vitae
  , 'fetid '        => 'f&oelig;tid ' // Foetid
  , 'foetid'        => 'f&oelig;tid' // Foetid
  , 'fetus '        => 'f&oelig;tus ' // Foetus
  , 'foetus'        => 'f&oelig;tus' // Foetus
  , 'oeuvre'        => '&oelig;uvre' // Will catch manoeuvre
  , 'Belle epoque'  => 'Belle &Eacute;poque'
  , 'belle epoque'  => 'belle &eacute;poque'
  , 'bete noir'     => 'b&ecirc;te noir' // Handles endings with or without 'e'
  , 'ric-a-brac'    => 'ric-&agrave;-brac'
  , 'Cliche'        => 'Clich&eacute;'
  , 'cliche'        => 'clich&eacute;'
  , 'ooperat'       => 'co&ouml;perat' // Cooperate; covers several variants
  , 'oordinat'      => 'co&ouml;rdinat' // Coordinate
  , 'oup de grace'  => 'oup de gr&acirc;ce' // Coup de grace
  , ' dais '        => ' da&iuml;s ' // Spaced out
  , 'declasse'      => 'd&eacute;class&eacute;'
  , 'eja vu'        => '&eacute;j&agrave; vu' // Deja vu
  , 'enouement'     => '&eacute;nouement' // Denouement
  , 'detente'       => 'd&eacute;tente'
  , 'oppelganger'   => 'oppelg&auml;nger' // Doppelganger
  , 'El Nino'       => 'El Ni&ntilde;o'
  , 'emigre'        => '&eacute;migr&eacute;'
  , 'entree'        => 'entr&eacute;e'
  , 'facade'        => 'fa&ccedil;ade'
  , 'fiance'        => 'fianc&eacute;'
  , 'foehn wind'    => 'f&ouml;hn wind'
  , 'fohn wind'     => 'f&ouml;hn wind'
  , 'alapeno'       => 'alape&ntilde;o' // Jalapeno
  , 'La Nina'       => 'La Ni&ntilde;a'
  , 'matinee'       => 'matin&eacute;e'
  , 'melange'       => 'm&eacute;lange'
  , 'Naivete'       => 'Na&iuml;vet&eacute;' // Needs to precede naive
  , 'naivete'       => 'na&iuml;vet&eacute;'
  , 'naive'         => 'na&iuml;ve'
  , 'Naive'         => 'Na&iuml;ve'
  , ' passe '       => ' pass&eacute; '
  , ' passe.'       => ' pass&eacute;.'
  , ' passe!'       => ' pass&eacute;!'
  , ' passe?'       => ' pass&eacute;?'
  , 'protoge'       => 'prot&eacute;g&eacute;'
  , 'puree'         => 'pur&eacute;e'
  , 'Quebec'        => 'Qu&eacute;bec'
  , 'morgasbord'    => 'm&ouml;rg&aring;sbord'
  ) );
}



// Typhography
function ubik_text_replace_typography() {
  return apply_filters( 'ubik_text_replace_typography', array(
    '&#8211;'       => '&#x200A;&ndash;&#x200A;'                        // En dash surrounded with hair spaces
  , '&#8212;'       => '&#x200A;&mdash;&#x200A;'                        // Em dash surrounded with hair spaces
  , ' & '           => ' <span class="ampersand">&</span> '             // A styling hook for ampersands
  , '/No.'          => '<span class="numero">&numero;</span>'           // Numero sign: https://en.wikipedia.org/wiki/Numero_sign
  , '/|P'           => '<span class="pilcrow">&para;</span>'            // Pilcrow: https://en.wikipedia.org/wiki/Pilcrow
  , '/|S'           => '<span class="section">&sect;</span>'            // Section symbol: https://en.wikipedia.org/wiki/Section_sign
  , '/|d'           => '<span class="dagger">&dagger;</span>'           // Dagger
  , '/|D'           => '<span class="dagger">&Dagger;</span>'           // Double dagger
  ) );
}



// Figures, numbers, math, etc.
function ubik_text_replace_figures() {
  return apply_filters( 'ubik_text_replace_figures', array(
    ' 1/4 '     => '&frac14; '
  , ' 1/2 '     => '&frac12; '
  , ' 3/4 '     => '&frac34; '
  , '^oC'       => '&deg;C'
  , '^oF'       => '&deg;F'
  , '+/-'       => '&plusmn;'
  , '+-'        => '&plusmn;'
  , '^1 '       => '&sup1; '
  , '^2 '       => '&sup2; '
  , '^3 '       => '&sup3; '
  // &pi; Pi
  // &fnof; function
  // &sigma; sigma
  // &there4; therefore
  ) );
}



// Currencies
function ubik_text_replace_currencies() {
  return apply_filters( 'ubik_text_replace_currencies', array(
    'EUR '      => '&euro;&#x200A;'   // Euro
  , 'GBP '      => '&pound;&#x200A;'  // Pound
  , 'JPY '      => '&yen;&#x200A;'    // Japanese yen
  , 'KRW '      => '&#x20A9;&#x200A;' // Korean won
  , 'THB '      => '&#x0E3F;&#x200A;' // Thai baht
  ) );
}



// Intellectual property
function ubik_text_replace_ip() {
  return apply_filters( 'ubik_text_replace_ip', array(
    '(c)'       => '&copy;'     // Copyright: https://en.wikipedia.org/wiki/Copyright_symbol
  , '(p)'       => '&#x2117;'   // Sound recording: https://en.wikipedia.org/wiki/Sound_recording_copyright_symbol
  , '(r)'       => '&reg;'      // Registed trademark: https://en.wikipedia.org/wiki/Registered_trademark_symbol
  , '(sm)'      => '&#x2120;'   // Service mark: https://en.wikipedia.org/wiki/Service_mark_symbol
  ) );
}



// Hearts
function ubik_text_replace_hearts() {
  return apply_filters( 'ubik_text_replace_hearts', array(
    ' &lt;3 '       => ' <span class="heart">&#x2665;</span> '          // <3 = Normal heart: http://codepoints.net/U+2665
  , ' &lt;33 '      => ' <span class="heart">&#x2764;</span> '          // <33 = Heavy black heart: http://codepoints.net/U+2764
  , '/&lt;3'        => '<span class="floral-heart">&#x2766;</span>'     // Floral heart: https://en.wikipedia.org/wiki/Fleuron_(typography)
  , '/-&lt;3'       => '<span class="floral-bullet">&#x2767;</span>'    // Floral heart bullet point: https://en.wikipedia.org/wiki/Fleuron_(typography)
  ) );
}



// Arrows
function ubik_text_replace_arrows() {
  return apply_filters( 'ubik_text_replace_arrows', array(
    '==>'       => '&nbsp;&rarr;'
  , '==&gt;'    => '&nbsp;&rarr;'
  , '&lt;=='    => '&larr;&nbsp;'
  ) );
}



// Hook this function to add your own replacements
function ubik_text_replace_custom() {
  return apply_filters( 'ubik_text_replace_custom', array() );
}
