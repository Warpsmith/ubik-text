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
  , 'typography'  => ubik_text_replace_typography()
  , 'diacritics'  => ubik_text_replace_diacritics()
  , 'figures'     => ubik_text_replace_figures()
  , 'currencies'  => ubik_text_replace_currencies()
  , 'ip'          => ubik_text_replace_ip()
  , 'hearts'      => ubik_text_replace_hearts()
  , 'arrows'      => ubik_text_replace_arrows()
  , 'zerowidth'   => ubik_text_replace_zerowidth()
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



// Typhography
function ubik_text_replace_typography() {
  return apply_filters( 'ubik_text_replace_typography', array(
    '&#8211;'       => '&#x200A;&#8211;&#x200A;'                        // En dash; surrounded with hair spaces: &#x200A;
  , '&#8212;'       => '&#x200A;&#8212;&#x200A;'                        // Em dash; surrounded with hair spaces: &#x200A;
  , ' & '           => ' <span class="ampersand">&</span> '             // A styling hook for ampersands
  , '/No.'          => '<span class="numero">&#x2116;</span>'           // Numero sign: https://en.wikipedia.org/wiki/Numero_sign
  , '/|P'           => '<span class="pilcrow">&#x00B6;</span>'          // Pilcrow: https://en.wikipedia.org/wiki/Pilcrow
  , '/|S'           => '<span class="section">&#x00A7;</span>'          // Section symbol: https://en.wikipedia.org/wiki/Section_sign
  , '?!'            => '&#x203D;'                                       // Interrobang: https://en.wikipedia.org/wiki/Interrobang
  , '/AE'           => '&#x00C6;'                                       // AE: https://codepoints.net/U+00C6
  , '/ae'           => '&#x00E6;'                                       // ae: https://codepoints.net/U+00E6
  , '/OE'           => '&#x0152;'                                       // OE: https://codepoints.net/U+0152
  , '/oe'           => '&#x0153;'                                       // oe: https://codepoints.net/U+0153
  ) );
}



// Common English words with diacritics; for reference: https://en.wiktionary.org/wiki/Appendix:English_words_with_diacritics
function ubik_text_replace_diacritics() {

  // Diacritic definitions
  $ae         = '&#x00E6;';
  $ae_c       = '&#x00C6;';
  $oe         = '&#x0153;';
  $a_circum   = '&#x00E2;';
  $a_grave    = '&#x00E0;';
  $a_ring     = '&#x00E5;';
  $a_umlaut   = '&#x00E4;';
  $c_cedilla  = '&#x00E7;';
  $e_acute    = '&#x00E9;';
  $e_acute_c  = '&#x00C9;';
  $e_circum   = '&#x00FA;';
  $i_umlaut   = '&#x00EF;';
  $n_tilde    = '&#x00F1;';
  $o_umlaut   = '&#x00F6;';

  // Some of these have had the first character lopped off to account for differences in capitalization
  return apply_filters( 'ubik_text_replace_diacritics', array(
    'Aegis'         => $ae_c . 'gis'
  , 'aegis'         => $ae . 'gis'
  , 'Aeolian'       => $ae_c . 'olian'
  , 'aeolian'       => $ae . 'olian'
  , 'Aeon'          => $ae_c . 'on'
  , 'aeon'          => $ae . 'on'
  , 'aesar'         => $ae . 'sar' // Caesar
  , 'Aesthe'        => $ae_c . 'sthet' // Aesthetic, aesthete, anaesthesia
  , 'aesthe'        => $ae . 'sthet' // Aesthetic, aesthete, anaesthesia
  , 'Aether'        => $ae_c . 'ther'
  , 'aether'        => $ae . 'ther'
  , 'Algae'         => 'Alg' . $ae
  , 'algae'         => 'alg' . $ae
  , 'naesthes'      => 'n' . $ae . 's' // Anaesthesia
  , 'ntennae'       => 'ntenn' . $ae // Antennae
  , 'Archae'        => 'Arch' . $ae
  , 'archae'        => 'arch' . $ae
  , 'cyclopaed'     => 'cyclop' . $ae . 'd' // Encyclopaedia
  , 'cycloped'      => 'cyclop' . $ae . 'd' // Encyclopedia
  , 'daemon'        => 'd' . $ae . 'mon' // Daemon
  , 'iaeresis'      => 'i' . $ae . 'resis' // Diaeresis
  , 'formulae'      => 'formul' . $ae // Formulae
  , 'edieval'       => 'edi' . $ae . 'val' // Medieval
  , 'ebulae'        => 'ebul' . $ae // Nebulae
  , 'novae'         => 'nov' . $ae // (Super)novae
  , 'Paleo'         => 'Pal' . $ae . 'o'
  , 'paleo'         => 'pal' . $ae . 'o'
  , 'angaea'        => 'ang' . $ae . 'a' // Pangaea
  , 'personae'      => 'person' . $ae // Personae
  , 'rimeval'       => 'rim' . $ae . 'val' // Primeval
  , ' vitae'        => ' vit' . $ae // Curriculum vitae
  , 'fetid '        => 'f' . $oe . 'tid ' // Foetid
  , 'foetid'        => 'f' . $oe . 'tid' // Foetid
  , 'fetus '        => 'f' . $oe . 'tus ' // Foetus
  , 'foetus'        => 'f' . $oe . 'tus' // Foetus
  , 'oeuvre'        => $oe . 'uvre' // Will catch manoeuvre
  , 'Belle epoque'  => 'Belle ' . $e_acute_c . 'poque'
  , 'belle epoque'  => 'belle ' . $e_acute . 'poque'
  , 'bete noir'     => 'b' . $e_circum . 'te noir' // Handles endings with or without 'e'
  , 'ric-a-brac'    => 'ric-' . $a_grave . '-brac'
  , 'Cliche'        => 'Clich' . $e_acute
  , 'cliche'        => 'clich' . $e_acute
  , 'ooperat'       => 'co' . $o_umlaut . 'perat' // Cooperate; covers several variants
  , 'oordinat'      => 'co' . $o_umlaut . 'rdinat' // Coordinate
  , 'oup de grace'  => 'oup de gr' . $a_circum . 'ce' // Coup de grace
  , ' dais '        => ' da' . $i_umlaut . 's ' // Spaced out
  , 'declasse'      => 'd' . $e_acute . 'class' . $e_acute
  , 'eja vu'        => $e_acute . 'j' . $a_grave . ' vu' // Deja vu
  , 'enouement'     => $e_acute . 'nouement' // Denouement
  , 'detente'       => 'd' . $e_acute . 'tente'
  , 'oppelganger'   => 'oppelg' . $a_umlaut . 'nger' // Doppelganger
  , 'El Nino'       => 'El Ni' . $n_tilde . 'o'
  , 'emigre'        => $e_acute . 'migr' . $e_acute
  , 'entree'        => 'entr' . $e_acute . 'e'
  , 'facade'        => 'fa' . $c_cedilla . 'ade'
  , 'fiance'        => 'fianc' . $e_acute
  , 'foehn wind'    => 'f' . $o_umlaut . 'hn wind'
  , 'fohn wind'     => 'f' . $o_umlaut . 'hn wind'
  , 'alapeno'       => 'alape' . $n_tilde . 'o' // Jalapeno
  , 'La Nina'       => 'La Ni' . $n_tilde . 'a'
  , 'matinee'       => 'matin' . $e_acute . 'e'
  , 'melange'       => 'm' . $e_acute . 'lange'
  , 'Naivete'       => 'Na' . $i_umlaut . 'vet' . $e_acute // Needs to be in this order
  , 'naivete'       => 'na' . $i_umlaut . 'vet' . $e_acute // Needs to be in this order
  , 'naive'         => 'na' . $i_umlaut . 've'
  , 'Naive'         => 'Na' . $i_umlaut . 've'
  , ' passe '       => ' pass' . $e_acute . ' '
  , ' passe.'       => ' pass' . $e_acute . '.'
  , ' passe!'       => ' pass' . $e_acute . '!'
  , ' passe?'       => ' pass' . $e_acute . '?'
  , 'protoge'       => 'prot' . $e_acute . 'g' . $e_acute
  , 'puree'         => 'pur' . $e_acute . 'e'
  , 'Quebec'        => 'Qu' . $e_acute . 'bec'
  , 'morgasbord'    => 'm' . $o_umlaut . 'rg' . $a_ring . 'sbord'
  ) );
}



// Figures, numbers, math, etc.
function ubik_text_replace_figures() {
  return apply_filters( 'ubik_text_replace_figures', array(
    ' 1/4 '     => '&#x00BC; '
  , ' 1/2 '     => '&#x00BD; '
  , ' 3/4 '     => '&#x00BE; '
  , '^oC'       => '&#x00B0;C'
  , '^oF'       => '&#x00B0;F'
  , '+/-'       => '&#x00B1;&#x200A;'
  , '+-'        => '&#x00B1;&#x200A;'
  , '^1 '       => '&#x00B9; '
  , '^2 '       => '&#x00B2; '
  , '^3 '       => '&#x00B3; '
  ) );
}



// Currencies
function ubik_text_replace_currencies() {
  return apply_filters( 'ubik_text_replace_currencies', array(
    'CNY '      => '&#165;&#x200A;'   // Chinese renminbi
  , 'EUR '      => '&#8364;&#x200A;'  // Euro
  , 'GBP '      => '&#163;&#x200A;'   // Pound
  , 'JPY '      => '&#165;&#x200A;'   // Japanese yen
  , 'KRW '      => '&#8361;&#x200A;'  // Korean won
  , 'THB '      => '&#3647;&#x200A;'  // Thai baht
  ) );
}



// Intellectual property
function ubik_text_replace_ip() {
  return apply_filters( 'ubik_text_replace_ip', array(
    '(c)'       => '&#x00A9;'         // Copyright: https://en.wikipedia.org/wiki/Copyright_symbol
  , '(p)'       => '&#x2117;'         // Sound recording: https://en.wikipedia.org/wiki/Sound_recording_copyright_symbol
  , '(r)'       => '&#x00AE;'         // Registed trademark: https://en.wikipedia.org/wiki/Registered_trademark_symbol
  , '(sm)'      => '&#x2120;'         // Service mark: https://en.wikipedia.org/wiki/Service_mark_symbol
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
  , '/KHOMUT'   => '&#x0E5B;'        // Thai khomut: https://codepoints.net/U+0E5B
  ) );
}



// Zero-width characters
function ubik_text_replace_zerowidth() {
  return apply_filters( 'ubik_text_replace_zerowidth', array(
    '/ZWSP'         => '&#x200B;'   // Zero-width space: https://en.wikipedia.org/wiki/Zero-width_space
  , '/ZWNJ'         => '&#x200C;'   // Zero-width non-joiner: https://en.wikipedia.org/wiki/Zero-width_non-joiner
  , '/ZWJ'          => '&#x200D;'   // Zero-width joiner: https://en.wikipedia.org/wiki/Zero-width_joiner
  ) );
}



// Hook this function to add your own replacements
function ubik_text_replace_custom() {
  return apply_filters( 'ubik_text_replace_custom', array() );
}
