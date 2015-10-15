<?php
class HTMLPurifier
{

    /** Version of HTML Purifier */
    public $version = '4.3.0';

    /** Constant with version of HTML Purifier */
    const VERSION = '4.3.0';

    /** Global configuration object */
    public $config;

    /** Array of extra HTMLPurifier_Filter objects to run on HTML, for backwards compatibility */
    private $filters = array();

    /** Single instance of HTML Purifier */
    private static $instance;

    protected $strategy, $generator;

    /**
     * Resultant HTMLPurifier_Context of last run purification. Is an array
     * of contexts if the last called method was purifyArray().
     */
    public $context;

    /**
     * Initializes the purifier.
     * @param $config Optional HTMLPurifier_Config object for all instances of
     *                the purifier, if omitted, a default configuration is
     *                supplied (which can be overridden on a per-use basis).
     *                The parameter can also be any type that
     *                HTMLPurifier_Config::create() supports.
     */
    public function __construct($config = null) {

        $this->config = HTMLPurifier_Config::create($config);

        $this->strategy     = new HTMLPurifier_Strategy_Core();

    }

    /**
     * Adds a filter to process the output. First come first serve
     * @param $filter HTMLPurifier_Filter object
     */
    public function addFilter($filter) {
        trigger_error('HTMLPurifier->addFilter() is deprecated, use configuration directives in the Filter namespace or Filter.Custom', E_USER_WARNING);
        $this->filters[] = $filter;
    }

    /**
     * Filters an HTML snippet/document to be XSS-free and standards-compliant.
     *
     * @param $html String of HTML to purify
     * @param $config HTMLPurifier_Config object for this operation, if omitted,
     *                defaults to the config object specified during this
     *                object's construction. The parameter can also be any type
     *                that HTMLPurifier_Config::create() supports.
     * @return Purified HTML
     */
    public function purify($html, $config = null) {

        // :TODO: make the config merge in, instead of replace
        $config = $config ? HTMLPurifier_Config::create($config) : $this->config;

        // implementation is partially environment dependant, partially
        // configuration dependant
        $lexer = HTMLPurifier_Lexer::create($config);

        $context = new HTMLPurifier_Context();

        // setup HTML generator
        $this->generator = new HTMLPurifier_Generator($config, $context);
        $context->register('Generator', $this->generator);

        // set up global context variables
        if ($config->get('Core.CollectErrors')) {
            // may get moved out if other facilities use it
            $language_factory = HTMLPurifier_LanguageFactory::instance();
            $language = $language_factory->create($config, $context);
            $context->register('Locale', $language);

            $error_collector = new HTMLPurifier_ErrorCollector($context);
            $context->register('ErrorCollector', $error_collector);
        }

        // setup id_accumulator context, necessary due to the fact that
        // AttrValidator can be called from many places
        $id_accumulator = HTMLPurifier_IDAccumulator::build($config, $context);
        $context->register('IDAccumulator', $id_accumulator);

        $html = HTMLPurifier_Encoder::convertToUTF8($html, $config, $context);

        // setup filters
        $filter_flags = $config->getBatch('Filter');
        $custom_filters = $filter_flags['Custom'];
        unset($filter_flags['Custom']);
        $filters = array();
        foreach ($filter_flags as $filter => $flag) {
            if (!$flag) continue;
            if (strpos($filter, '.') !== false) continue;
            $class = "HTMLPurifier_Filter_$filter";
            $filters[] = new $class;
        }
        foreach ($custom_filters as $filter) {
            // maybe "HTMLPurifier_Filter_$filter", but be consistent with AutoFormat
            $filters[] = $filter;
        }
        $filters = array_merge($filters, $this->filters);
        // maybe prepare(), but later

        for ($i = 0, $filter_size = count($filters); $i < $filter_size; $i++) {
            $html = $filters[$i]->preFilter($html, $config, $context);
        }

        // purified HTML
        $html =
            $this->generator->generateFromTokens(
                // list of tokens
                $this->strategy->execute(
                    // list of un-purified tokens
                    $lexer->tokenizeHTML(
                        // un-purified HTML
                        $html, $config, $context
                    ),
                    $config, $context
                )
            );

        for ($i = $filter_size - 1; $i >= 0; $i--) {
            $html = $filters[$i]->postFilter($html, $config, $context);
        }

        $html = HTMLPurifier_Encoder::convertFromUTF8($html, $config, $context);
        $this->context =& $context;
        return $html;
    }

    /**
     * Filters an array of HTML snippets
     * @param $config Optional HTMLPurifier_Config object for this operation.
     *                See HTMLPurifier::purify() for more details.
     * @return Array of purified HTML
     */
    public function purifyArray($array_of_html, $config = null) {
        $context_array = array();
        foreach ($array_of_html as $key => $html) {
            $array_of_html[$key] = $this->purify($html, $config);
            $context_array[$key] = $this->context;
        }
        $this->context = $context_array;
        return $array_of_html;
    }

    /**
     * Singleton for enforcing just one HTML Purifier in your system
     * @param $prototype Optional prototype HTMLPurifier instance to
     *                   overload singleton with, or HTMLPurifier_Config
     *                   instance to configure the generated version with.
     */
    public static function instance($prototype = null) {
        if (!self::$instance || $prototype) {
            if ($prototype instanceof HTMLPurifier) {
                self::$instance = $prototype;
            } elseif ($prototype) {
                self::$instance = new HTMLPurifier($prototype);
            } else {
                self::$instance = new HTMLPurifier();
            }
        }
        return self::$instance;
    }

    /**
     * @note Backwards compatibility, see instance()
     */
    public static function getInstance($prototype = null) {
        return HTMLPurifier::instance($prototype);
    }

}





/**
 * Defines common attribute collections that modules reference
 */

class HTMLPurifier_AttrCollections
{

    /**
     * Associative array of attribute collections, indexed by name
     */
    public $info = array();

    /**
     * Performs all expansions on internal data for use by other inclusions
     * It also collects all attribute collection extensions from
     * modules
     * @param $attr_types HTMLPurifier_AttrTypes instance
     * @param $modules Hash array of HTMLPurifier_HTMLModule members
     */
    public function __construct($attr_types, $modules) {
        // load extensions from the modules
        foreach ($modules as $module) {
            foreach ($module->attr_collections as $coll_i => $coll) {
                if (!isset($this->info[$coll_i])) {
                    $this->info[$coll_i] = array();
                }
                foreach ($coll as $attr_i => $attr) {
                    if ($attr_i === 0 && isset($this->info[$coll_i][$attr_i])) {
                        // merge in includes
                        $this->info[$coll_i][$attr_i] = array_merge(
                            $this->info[$coll_i][$attr_i], $attr);
                        continue;
                    }
                    $this->info[$coll_i][$attr_i] = $attr;
                }
            }
        }
        // perform internal expansions and inclusions
        foreach ($this->info as $name => $attr) {
            // merge attribute collections that include others
            $this->performInclusions($this->info[$name]);
            // replace string identifiers with actual attribute objects
            $this->expandIdentifiers($this->info[$name], $attr_types);
        }
    }

    /**
     * Takes a reference to an attribute associative array and performs
     * all inclusions specified by the zero index.
     * @param &$attr Reference to attribute array
     */
    public function performInclusions(&$attr) {
        if (!isset($attr[0])) return;
        $merge = $attr[0];
        $seen  = array(); // recursion guard
        // loop through all the inclusions
        for ($i = 0; isset($merge[$i]); $i++) {
            if (isset($seen[$merge[$i]])) continue;
            $seen[$merge[$i]] = true;
            // foreach attribute of the inclusion, copy it over
            if (!isset($this->info[$merge[$i]])) continue;
            foreach ($this->info[$merge[$i]] as $key => $value) {
                if (isset($attr[$key])) continue; // also catches more inclusions
                $attr[$key] = $value;
            }
            if (isset($this->info[$merge[$i]][0])) {
                // recursion
                $merge = array_merge($merge, $this->info[$merge[$i]][0]);
            }
        }
        unset($attr[0]);
    }

    /**
     * Expands all string identifiers in an attribute array by replacing
     * them with the appropriate values inside HTMLPurifier_AttrTypes
     * @param &$attr Reference to attribute array
     * @param $attr_types HTMLPurifier_AttrTypes instance
     */
    public function expandIdentifiers(&$attr, $attr_types) {

        // because foreach will process new elements we add, make sure we
        // skip duplicates
        $processed = array();

        foreach ($attr as $def_i => $def) {
            // skip inclusions
            if ($def_i === 0) continue;

            if (isset($processed[$def_i])) continue;

            // determine whether or not attribute is required
            if ($required = (strpos($def_i, '*') !== false)) {
                // rename the definition
                unset($attr[$def_i]);
                $def_i = trim($def_i, '*');
                $attr[$def_i] = $def;
            }

            $processed[$def_i] = true;

            // if we've already got a literal object, move on
            if (is_object($def)) {
                // preserve previous required
                $attr[$def_i]->required = ($required || $attr[$def_i]->required);
                continue;
            }

            if ($def === false) {
                unset($attr[$def_i]);
                continue;
            }

            if ($t = $attr_types->get($def)) {
                $attr[$def_i] = $t;
                $attr[$def_i]->required = $required;
            } else {
                unset($attr[$def_i]);
            }
        }

    }

}





/**
 * Base class for all validating attribute definitions.
 *
 * This family of classes forms the core for not only HTML attribute validation,
 * but also any sort of string that needs to be validated or cleaned (which
 * means CSS properties and composite definitions are defined here too).
 * Besides defining (through code) what precisely makes the string valid,
 * subclasses are also responsible for cleaning the code if possible.
 */

abstract class HTMLPurifier_AttrDef
{

    /**
     * Tells us whether or not an HTML attribute is minimized. Has no
     * meaning in other contexts.
     */
    public $minimized = false;

    /**
     * Tells us whether or not an HTML attribute is required. Has no
     * meaning in other contexts
     */
    public $required = false;

    /**
     * Validates and cleans passed string according to a definition.
     *
     * @param $string String to be validated and cleaned.
     * @param $config Mandatory HTMLPurifier_Config object.
     * @param $context Mandatory HTMLPurifier_AttrContext object.
     */
    abstract public function validate($string, $config, $context);

    /**
     * Convenience method that parses a string as if it were CDATA.
     *
     * This method process a string in the manner specified at
     * <http://www.w3.org/TR/html4/types.html#h-6.2> by removing
     * leading and trailing whitespace, ignoring line feeds, and replacing
     * carriage returns and tabs with spaces.  While most useful for HTML
     * attributes specified as CDATA, it can also be applied to most CSS
     * values.
     *
     * @note This method is not entirely standards compliant, as trim() removes
     *       more types of whitespace than specified in the spec. In practice,
     *       this is rarely a problem, as those extra characters usually have
     *       already been removed by HTMLPurifier_Encoder.
     *
     * @warning This processing is inconsistent with XML's whitespace handling
     *          as specified by section 3.3.3 and referenced XHTML 1.0 section
     *          4.7.  However, note that we are NOT necessarily
     *          parsing XML, thus, this behavior may still be correct. We
     *          assume that newlines have been normalized.
     */
    public function parseCDATA($string) {
        $string = trim($string);
        $string = str_replace(array("\n", "\t", "\r"), ' ', $string);
        return $string;
    }

    /**
     * Factory method for creating this class from a string.
     * @param $string String construction info
     * @return Created AttrDef object corresponding to $string
     */
    public function make($string) {
        // default implementation, return a flyweight of this object.
        // If $string has an effect on the returned object (i.e. you
        // need to overload this method), it is best
        // to clone or instantiate new copies. (Instantiation is safer.)
        return $this;
    }

    /**
     * Removes spaces from rgb(0, 0, 0) so that shorthand CSS properties work
     * properly. THIS IS A HACK!
     */
    protected function mungeRgb($string) {
        return preg_replace('/rgb\((\d+)\s*,\s*(\d+)\s*,\s*(\d+)\)/', 'rgb(\1,\2,\3)', $string);
    }

    /**
     * Parses a possibly escaped CSS string and returns the "pure" 
     * version of it.
     */
    protected function expandCSSEscape($string) {
        // flexibly parse it
        $ret = '';
        for ($i = 0, $c = strlen($string); $i < $c; $i++) {
            if ($string[$i] === '\\') {
                $i++;
                if ($i >= $c) {
                    $ret .= '\\';
                    break;
                }
                if (ctype_xdigit($string[$i])) {
                    $code = $string[$i];
                    for ($a = 1, $i++; $i < $c && $a < 6; $i++, $a++) {
                        if (!ctype_xdigit($string[$i])) break;
                        $code .= $string[$i];
                    }
                    // We have to be extremely careful when adding
                    // new characters, to make sure we're not breaking
                    // the encoding.
                    $char = HTMLPurifier_Encoder::unichr(hexdec($code));
                    if (HTMLPurifier_Encoder::cleanUTF8($char) === '') continue;
                    $ret .= $char;
                    if ($i < $c && trim($string[$i]) !== '') $i--;
                    continue;
                }
                if ($string[$i] === "\n") continue;
            }
            $ret .= $string[$i];
        }
        return $ret;
    }

}





/**
 * Processes an entire attribute array for corrections needing multiple values.
 *
 * Occasionally, a certain attribute will need to be removed and popped onto
 * another value.  Instead of creating a complex return syntax for
 * HTMLPurifier_AttrDef, we just pass the whole attribute array to a
 * specialized object and have that do the special work.  That is the
 * family of HTMLPurifier_AttrTransform.
 *
 * An attribute transformation can be assigned to run before or after
 * HTMLPurifier_AttrDef validation.  See HTMLPurifier_HTMLDefinition for
 * more details.
 */

abstract class HTMLPurifier_AttrTransform
{

    /**
     * Abstract: makes changes to the attributes dependent on multiple values.
     *
     * @param $attr Assoc array of attributes, usually from
     *              HTMLPurifier_Token_Tag::$attr
     * @param $config Mandatory HTMLPurifier_Config object.
     * @param $context Mandatory HTMLPurifier_Context object
     * @returns Processed attribute array.
     */
    abstract public function transform($attr, $config, $context);

    /**
     * Prepends CSS properties to the style attribute, creating the
     * attribute if it doesn't exist.
     * @param $attr Attribute array to process (passed by reference)
     * @param $css CSS to prepend
     */
    public function prependCSS(&$attr, $css) {
        $attr['style'] = isset($attr['style']) ? $attr['style'] : '';
        $attr['style'] = $css . $attr['style'];
    }

    /**
     * Retrieves and removes an attribute
     * @param $attr Attribute array to process (passed by reference)
     * @param $key Key of attribute to confiscate
     */
    public function confiscateAttr(&$attr, $key) {
        if (!isset($attr[$key])) return null;
        $value = $attr[$key];
        unset($attr[$key]);
        return $value;
    }

}





/**
 * Provides lookup array of attribute types to HTMLPurifier_AttrDef objects
 */
class HTMLPurifier_AttrTypes
{
    /**
     * Lookup array of attribute string identifiers to concrete implementations
     */
    protected $info = array();

    /**
     * Constructs the info array, supplying default implementations for attribute
     * types.
     */
    public function __construct() {
        // pseudo-types, must be instantiated via shorthand
        $this->info['Enum']    = new HTMLPurifier_AttrDef_Enum();
        $this->info['Bool']    = new HTMLPurifier_AttrDef_HTML_Bool();

        $this->info['CDATA']    = new HTMLPurifier_AttrDef_Text();
        $this->info['ID']       = new HTMLPurifier_AttrDef_HTML_ID();
        $this->info['Length']   = new HTMLPurifier_AttrDef_HTML_Length();
        $this->info['MultiLength'] = new HTMLPurifier_AttrDef_HTML_MultiLength();
        $this->info['NMTOKENS'] = new HTMLPurifier_AttrDef_HTML_Nmtokens();
        $this->info['Pixels']   = new HTMLPurifier_AttrDef_HTML_Pixels();
        $this->info['Text']     = new HTMLPurifier_AttrDef_Text();
        $this->info['URI']      = new HTMLPurifier_AttrDef_URI();
        $this->info['LanguageCode'] = new HTMLPurifier_AttrDef_Lang();
        $this->info['Color']    = new HTMLPurifier_AttrDef_HTML_Color();

        // unimplemented aliases
        $this->info['ContentType'] = new HTMLPurifier_AttrDef_Text();
        $this->info['ContentTypes'] = new HTMLPurifier_AttrDef_Text();
        $this->info['Charsets'] = new HTMLPurifier_AttrDef_Text();
        $this->info['Character'] = new HTMLPurifier_AttrDef_Text();

        // "proprietary" types
        $this->info['Class'] = new HTMLPurifier_AttrDef_HTML_Class();

        // number is really a positive integer (one or more digits)
        // FIXME: ^^ not always, see start and value of list items
        $this->info['Number']   = new HTMLPurifier_AttrDef_Integer(false, false, true);
    }

    /**
     * Retrieves a type
     * @param $type String type name
     * @return Object AttrDef for type
     */
    public function get($type) {

        // determine if there is any extra info tacked on
        if (strpos($type, '#') !== false) list($type, $string) = explode('#', $type, 2);
        else $string = '';

        if (!isset($this->info[$type])) {
            trigger_error('Cannot retrieve undefined attribute type ' . $type, E_USER_ERROR);
            return;
        }

        return $this->info[$type]->make($string);

    }

    /**
     * Sets a new implementation for a type
     * @param $type String type name
     * @param $impl Object AttrDef for type
     */
    public function set($type, $impl) {
        $this->info[$type] = $impl;
    }
}





/**
 * Validates the attributes of a token. Doesn't manage required attributes
 * very well. The only reason we factored this out was because RemoveForeignElements
 * also needed it besides ValidateAttributes.
 */
class HTMLPurifier_AttrValidator
{

    /**
     * Validates the attributes of a token, returning a modified token
     * that has valid tokens
     * @param $token Reference to token to validate. We require a reference
     *     because the operation this class performs on the token are
     *     not atomic, so the context CurrentToken to be updated
     *     throughout
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     */
    public function validateToken(&$token, &$config, $context) {

        $definition = $config->getHTMLDefinition();
        $e =& $context->get('ErrorCollector', true);

        // initialize IDAccumulator if necessary
        $ok =& $context->get('IDAccumulator', true);
        if (!$ok) {
            $id_accumulator = HTMLPurifier_IDAccumulator::build($config, $context);
            $context->register('IDAccumulator', $id_accumulator);
        }

        // initialize CurrentToken if necessary
        $current_token =& $context->get('CurrentToken', true);
        if (!$current_token) $context->register('CurrentToken', $token);

        if (
            !$token instanceof HTMLPurifier_Token_Start &&
            !$token instanceof HTMLPurifier_Token_Empty
        ) return $token;

        // create alias to global definition array, see also $defs
        // DEFINITION CALL
        $d_defs = $definition->info_global_attr;

        // don't update token until the very end, to ensure an atomic update
        $attr = $token->attr;

        // do global transformations (pre)
        // nothing currently utilizes this
        foreach ($definition->info_attr_transform_pre as $transform) {
            $attr = $transform->transform($o = $attr, $config, $context);
            if ($e) {
                if ($attr != $o) $e->send(E_NOTICE, 'AttrValidator: Attributes transformed', $o, $attr);
            }
        }

        // do local transformations only applicable to this element (pre)
        // ex. <p align="right"> to <p style="text-align:right;">
        foreach ($definition->info[$token->name]->attr_transform_pre as $transform) {
            $attr = $transform->transform($o = $attr, $config, $context);
            if ($e) {
                if ($attr != $o) $e->send(E_NOTICE, 'AttrValidator: Attributes transformed', $o, $attr);
            }
        }

        // create alias to this element's attribute definition array, see
        // also $d_defs (global attribute definition array)
        // DEFINITION CALL
        $defs = $definition->info[$token->name]->attr;

        $attr_key = false;
        $context->register('CurrentAttr', $attr_key);

        // iterate through all the attribute keypairs
        // Watch out for name collisions: $key has previously been used
        foreach ($attr as $attr_key => $value) {

            // call the definition
            if ( isset($defs[$attr_key]) ) {
                // there is a local definition defined
                if ($defs[$attr_key] === false) {
                    // We've explicitly been told not to allow this element.
                    // This is usually when there's a global definition
                    // that must be overridden.
                    // Theoretically speaking, we could have a
                    // AttrDef_DenyAll, but this is faster!
                    $result = false;
                } else {
                    // validate according to the element's definition
                    $result = $defs[$attr_key]->validate(
                                    $value, $config, $context
                               );
                }
            } elseif ( isset($d_defs[$attr_key]) ) {
                // there is a global definition defined, validate according
                // to the global definition
                $result = $d_defs[$attr_key]->validate(
                                $value, $config, $context
                           );
            } else {
                // system never heard of the attribute? DELETE!
                $result = false;
            }

            // put the results into effect
            if ($result === false || $result === null) {
                // this is a generic error message that should replaced
                // with more specific ones when possible
                if ($e) $e->send(E_ERROR, 'AttrValidator: Attribute removed');

                // remove the attribute
                unset($attr[$attr_key]);
            } elseif (is_string($result)) {
                // generally, if a substitution is happening, there
                // was some sort of implicit correction going on. We'll
                // delegate it to the attribute classes to say exactly what.

                // simple substitution
                $attr[$attr_key] = $result;
            } else {
                // nothing happens
            }

            // we'd also want slightly more complicated substitution
            // involving an array as the return value,
            // although we're not sure how colliding attributes would
            // resolve (certain ones would be completely overriden,
            // others would prepend themselves).
        }

        $context->destroy('CurrentAttr');

        // post transforms

        // global (error reporting untested)
        foreach ($definition->info_attr_transform_post as $transform) {
            $attr = $transform->transform($o = $attr, $config, $context);
            if ($e) {
                if ($attr != $o) $e->send(E_NOTICE, 'AttrValidator: Attributes transformed', $o, $attr);
            }
        }

        // local (error reporting untested)
        foreach ($definition->info[$token->name]->attr_transform_post as $transform) {
            $attr = $transform->transform($o = $attr, $config, $context);
            if ($e) {
                if ($attr != $o) $e->send(E_NOTICE, 'AttrValidator: Attributes transformed', $o, $attr);
            }
        }

        $token->attr = $attr;

        // destroy CurrentToken if we made it ourselves
        if (!$current_token) $context->destroy('CurrentToken');

    }


}





// constants are slow, so we use as few as possible
if (!defined('HTMLPURIFIER_PREFIX')) {
    define('HTMLPURIFIER_PREFIX', dirname(__FILE__) . '/standalone');
    set_include_path(HTMLPURIFIER_PREFIX . PATH_SEPARATOR . get_include_path());
}

// accomodations for versions earlier than 5.0.2
// borrowed from PHP_Compat, LGPL licensed, by Aidan Lister <aidan@php.net>
if (!defined('PHP_EOL')) {
    switch (strtoupper(substr(PHP_OS, 0, 3))) {
        case 'WIN':
            define('PHP_EOL', "\r\n");
            break;
        case 'DAR':
            define('PHP_EOL', "\r");
            break;
        default:
            define('PHP_EOL', "\n");
    }
}

/**
 * Bootstrap class that contains meta-functionality for HTML Purifier such as
 * the autoload function.
 *
 * @note
 *      This class may be used without any other files from HTML Purifier.
 */
class HTMLPurifier_Bootstrap
{

    /**
     * Autoload function for HTML Purifier
     * @param $class Class to load
     */
    public static function autoload($class) {
        $file = HTMLPurifier_Bootstrap::getPath($class);
        if (!$file) return false;
        // Technically speaking, it should be ok and more efficient to
        // just do 'require', but Antonio Parraga reports that with
        // Zend extensions such as Zend debugger and APC, this invariant
        // may be broken.  Since we have efficient alternatives, pay
        // the cost here and avoid the bug.
        require_once HTMLPURIFIER_PREFIX . '/' . $file;
        return true;
    }

    /**
     * Returns the path for a specific class.
     */
    public static function getPath($class) {
        if (strncmp('HTMLPurifier', $class, 12) !== 0) return false;
        // Custom implementations
        if (strncmp('HTMLPurifier_Language_', $class, 22) === 0) {
            $code = str_replace('_', '-', substr($class, 22));
            $file = 'HTMLPurifier/Language/classes/' . $code . '.php';
        } else {
            $file = str_replace('_', '/', $class) . '.php';
        }
        if (!file_exists(HTMLPURIFIER_PREFIX . '/' . $file)) return false;
        return $file;
    }

    /**
     * "Pre-registers" our autoloader on the SPL stack.
     */
    public static function registerAutoload() {
        $autoload = array('HTMLPurifier_Bootstrap', 'autoload');
        if ( ($funcs = spl_autoload_functions()) === false ) {
            spl_autoload_register($autoload);
        } elseif (function_exists('spl_autoload_unregister')) {
            $buggy  = version_compare(PHP_VERSION, '5.2.11', '<');
            $compat = version_compare(PHP_VERSION, '5.1.2', '<=') &&
                      version_compare(PHP_VERSION, '5.1.0', '>=');
            foreach ($funcs as $func) {
                if ($buggy && is_array($func)) {
                    // :TRICKY: There are some compatibility issues and some
                    // places where we need to error out
                    $reflector = new ReflectionMethod($func[0], $func[1]);
                    if (!$reflector->isStatic()) {
                        throw new Exception('
                            HTML Purifier autoloader registrar is not compatible
                            with non-static object methods due to PHP Bug #44144;
                            Please do not use HTMLPurifier.autoload.php (or any
                            file that includes this file); instead, place the code:
                            spl_autoload_register(array(\'HTMLPurifier_Bootstrap\', \'autoload\'))
                            after your own autoloaders.
                        ');
                    }
                    // Suprisingly, spl_autoload_register supports the
                    // Class::staticMethod callback format, although call_user_func doesn't
                    if ($compat) $func = implode('::', $func);
                }
                spl_autoload_unregister($func);
            }
            spl_autoload_register($autoload);
            foreach ($funcs as $func) spl_autoload_register($func);
        }
    }

}





/**
 * Super-class for definition datatype objects, implements serialization
 * functions for the class.
 */
abstract class HTMLPurifier_Definition
{

    /**
     * Has setup() been called yet?
     */
    public $setup = false;

    /**
     * If true, write out the final definition object to the cache after
     * setup.  This will be true only if all invocations to get a raw
     * definition object are also optimized.  This does not cause file
     * system thrashing because on subsequent calls the cached object
     * is used and any writes to the raw definition object are short
     * circuited.  See enduser-customize.html for the high-level
     * picture.
     */
    public $optimized = null;

    /**
     * What type of definition is it?
     */
    public $type;

    /**
     * Sets up the definition object into the final form, something
     * not done by the constructor
     * @param $config HTMLPurifier_Config instance
     */
    abstract protected function doSetup($config);

    /**
     * Setup function that aborts if already setup
     * @param $config HTMLPurifier_Config instance
     */
    public function setup($config) {
        if ($this->setup) return;
        $this->setup = true;
        $this->doSetup($config);
    }

}





/**
 * Defines allowed CSS attributes and what their values are.
 * @see HTMLPurifier_HTMLDefinition
 */
class HTMLPurifier_CSSDefinition extends HTMLPurifier_Definition
{

    public $type = 'CSS';

    /**
     * Assoc array of attribute name to definition object.
     */
    public $info = array();

    /**
     * Constructs the info array.  The meat of this class.
     */
    protected function doSetup($config) {

        $this->info['text-align'] = new HTMLPurifier_AttrDef_Enum(
            array('left', 'right', 'center', 'justify'), false);

        $border_style =
        $this->info['border-bottom-style'] =
        $this->info['border-right-style'] =
        $this->info['border-left-style'] =
        $this->info['border-top-style'] =  new HTMLPurifier_AttrDef_Enum(
            array('none', 'hidden', 'dotted', 'dashed', 'solid', 'double',
            'groove', 'ridge', 'inset', 'outset'), false);

        $this->info['border-style'] = new HTMLPurifier_AttrDef_CSS_Multiple($border_style);

        $this->info['clear'] = new HTMLPurifier_AttrDef_Enum(
            array('none', 'left', 'right', 'both'), false);
        $this->info['float'] = new HTMLPurifier_AttrDef_Enum(
            array('none', 'left', 'right'), false);
        $this->info['font-style'] = new HTMLPurifier_AttrDef_Enum(
            array('normal', 'italic', 'oblique'), false);
        $this->info['font-variant'] = new HTMLPurifier_AttrDef_Enum(
            array('normal', 'small-caps'), false);

        $uri_or_none = new HTMLPurifier_AttrDef_CSS_Composite(
            array(
                new HTMLPurifier_AttrDef_Enum(array('none')),
                new HTMLPurifier_AttrDef_CSS_URI()
            )
        );

        $this->info['list-style-position'] = new HTMLPurifier_AttrDef_Enum(
            array('inside', 'outside'), false);
        $this->info['list-style-type'] = new HTMLPurifier_AttrDef_Enum(
            array('disc', 'circle', 'square', 'decimal', 'lower-roman',
            'upper-roman', 'lower-alpha', 'upper-alpha', 'none'), false);
        $this->info['list-style-image'] = $uri_or_none;

        $this->info['list-style'] = new HTMLPurifier_AttrDef_CSS_ListStyle($config);

        $this->info['text-transform'] = new HTMLPurifier_AttrDef_Enum(
            array('capitalize', 'uppercase', 'lowercase', 'none'), false);
        $this->info['color'] = new HTMLPurifier_AttrDef_CSS_Color();

        $this->info['background-image'] = $uri_or_none;
        $this->info['background-repeat'] = new HTMLPurifier_AttrDef_Enum(
            array('repeat', 'repeat-x', 'repeat-y', 'no-repeat')
        );
        $this->info['background-attachment'] = new HTMLPurifier_AttrDef_Enum(
            array('scroll', 'fixed')
        );
        $this->info['background-position'] = new HTMLPurifier_AttrDef_CSS_BackgroundPosition();

        $border_color =
        $this->info['border-top-color'] =
        $this->info['border-bottom-color'] =
        $this->info['border-left-color'] =
        $this->info['border-right-color'] =
        $this->info['background-color'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_Enum(array('transparent')),
            new HTMLPurifier_AttrDef_CSS_Color()
        ));

        $this->info['background'] = new HTMLPurifier_AttrDef_CSS_Background($config);

        $this->info['border-color'] = new HTMLPurifier_AttrDef_CSS_Multiple($border_color);

        $border_width =
        $this->info['border-top-width'] =
        $this->info['border-bottom-width'] =
        $this->info['border-left-width'] =
        $this->info['border-right-width'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_Enum(array('thin', 'medium', 'thick')),
            new HTMLPurifier_AttrDef_CSS_Length('0') //disallow negative
        ));

        $this->info['border-width'] = new HTMLPurifier_AttrDef_CSS_Multiple($border_width);

        $this->info['letter-spacing'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_Enum(array('normal')),
            new HTMLPurifier_AttrDef_CSS_Length()
        ));

        $this->info['word-spacing'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_Enum(array('normal')),
            new HTMLPurifier_AttrDef_CSS_Length()
        ));

        $this->info['font-size'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_Enum(array('xx-small', 'x-small',
                'small', 'medium', 'large', 'x-large', 'xx-large',
                'larger', 'smaller')),
            new HTMLPurifier_AttrDef_CSS_Percentage(),
            new HTMLPurifier_AttrDef_CSS_Length()
        ));

        $this->info['line-height'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_Enum(array('normal')),
            new HTMLPurifier_AttrDef_CSS_Number(true), // no negatives
            new HTMLPurifier_AttrDef_CSS_Length('0'),
            new HTMLPurifier_AttrDef_CSS_Percentage(true)
        ));

        $margin =
        $this->info['margin-top'] =
        $this->info['margin-bottom'] =
        $this->info['margin-left'] =
        $this->info['margin-right'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_CSS_Length(),
            new HTMLPurifier_AttrDef_CSS_Percentage(),
            new HTMLPurifier_AttrDef_Enum(array('auto'))
        ));

        $this->info['margin'] = new HTMLPurifier_AttrDef_CSS_Multiple($margin);

        // non-negative
        $padding =
        $this->info['padding-top'] =
        $this->info['padding-bottom'] =
        $this->info['padding-left'] =
        $this->info['padding-right'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_CSS_Length('0'),
            new HTMLPurifier_AttrDef_CSS_Percentage(true)
        ));

        $this->info['padding'] = new HTMLPurifier_AttrDef_CSS_Multiple($padding);

        $this->info['text-indent'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_CSS_Length(),
            new HTMLPurifier_AttrDef_CSS_Percentage()
        ));

        $trusted_wh = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_CSS_Length('0'),
            new HTMLPurifier_AttrDef_CSS_Percentage(true),
            new HTMLPurifier_AttrDef_Enum(array('auto'))
        ));
        $max = $config->get('CSS.MaxImgLength');

        $this->info['width'] =
        $this->info['height'] =
            $max === null ?
            $trusted_wh :
            new HTMLPurifier_AttrDef_Switch('img',
                // For img tags:
                new HTMLPurifier_AttrDef_CSS_Composite(array(
                    new HTMLPurifier_AttrDef_CSS_Length('0', $max),
                    new HTMLPurifier_AttrDef_Enum(array('auto'))
                )),
                // For everyone else:
                $trusted_wh
            );

        $this->info['text-decoration'] = new HTMLPurifier_AttrDef_CSS_TextDecoration();

        $this->info['font-family'] = new HTMLPurifier_AttrDef_CSS_FontFamily();

        // this could use specialized code
        $this->info['font-weight'] = new HTMLPurifier_AttrDef_Enum(
            array('normal', 'bold', 'bolder', 'lighter', '100', '200', '300',
            '400', '500', '600', '700', '800', '900'), false);

        // MUST be called after other font properties, as it references
        // a CSSDefinition object
        $this->info['font'] = new HTMLPurifier_AttrDef_CSS_Font($config);

        // same here
        $this->info['border'] =
        $this->info['border-bottom'] =
        $this->info['border-top'] =
        $this->info['border-left'] =
        $this->info['border-right'] = new HTMLPurifier_AttrDef_CSS_Border($config);

        $this->info['border-collapse'] = new HTMLPurifier_AttrDef_Enum(array(
            'collapse', 'separate'));

        $this->info['caption-side'] = new HTMLPurifier_AttrDef_Enum(array(
            'top', 'bottom'));

        $this->info['table-layout'] = new HTMLPurifier_AttrDef_Enum(array(
            'auto', 'fixed'));

        $this->info['vertical-align'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_Enum(array('baseline', 'sub', 'super',
                'top', 'text-top', 'middle', 'bottom', 'text-bottom')),
            new HTMLPurifier_AttrDef_CSS_Length(),
            new HTMLPurifier_AttrDef_CSS_Percentage()
        ));

        $this->info['border-spacing'] = new HTMLPurifier_AttrDef_CSS_Multiple(new HTMLPurifier_AttrDef_CSS_Length(), 2);

        // partial support
        $this->info['white-space'] = new HTMLPurifier_AttrDef_Enum(array('nowrap'));

        if ($config->get('CSS.Proprietary')) {
            $this->doSetupProprietary($config);
        }

        if ($config->get('CSS.AllowTricky')) {
            $this->doSetupTricky($config);
        }

        if ($config->get('CSS.Trusted')) {
            $this->doSetupTrusted($config);
        }

        $allow_important = $config->get('CSS.AllowImportant');
        // wrap all attr-defs with decorator that handles !important
        foreach ($this->info as $k => $v) {
            $this->info[$k] = new HTMLPurifier_AttrDef_CSS_ImportantDecorator($v, $allow_important);
        }

        $this->setupConfigStuff($config);
    }

    protected function doSetupProprietary($config) {
        // Internet Explorer only scrollbar colors
        $this->info['scrollbar-arrow-color']        = new HTMLPurifier_AttrDef_CSS_Color();
        $this->info['scrollbar-base-color']         = new HTMLPurifier_AttrDef_CSS_Color();
        $this->info['scrollbar-darkshadow-color']   = new HTMLPurifier_AttrDef_CSS_Color();
        $this->info['scrollbar-face-color']         = new HTMLPurifier_AttrDef_CSS_Color();
        $this->info['scrollbar-highlight-color']    = new HTMLPurifier_AttrDef_CSS_Color();
        $this->info['scrollbar-shadow-color']       = new HTMLPurifier_AttrDef_CSS_Color();

        // technically not proprietary, but CSS3, and no one supports it
        $this->info['opacity']          = new HTMLPurifier_AttrDef_CSS_AlphaValue();
        $this->info['-moz-opacity']     = new HTMLPurifier_AttrDef_CSS_AlphaValue();
        $this->info['-khtml-opacity']   = new HTMLPurifier_AttrDef_CSS_AlphaValue();

        // only opacity, for now
        $this->info['filter'] = new HTMLPurifier_AttrDef_CSS_Filter();

    }

    protected function doSetupTricky($config) {
        $this->info['display'] = new HTMLPurifier_AttrDef_Enum(array(
            'inline', 'block', 'list-item', 'run-in', 'compact',
            'marker', 'table', 'inline-table', 'table-row-group',
            'table-header-group', 'table-footer-group', 'table-row',
            'table-column-group', 'table-column', 'table-cell', 'table-caption', 'none'
        ));
        $this->info['visibility'] = new HTMLPurifier_AttrDef_Enum(array(
            'visible', 'hidden', 'collapse'
        ));
        $this->info['overflow'] = new HTMLPurifier_AttrDef_Enum(array('visible', 'hidden', 'auto', 'scroll'));
    }

    protected function doSetupTrusted($config) {
        $this->info['position'] = new HTMLPurifier_AttrDef_Enum(array(
            'static', 'relative', 'absolute', 'fixed'
        ));
        $this->info['top'] =
        $this->info['left'] =
        $this->info['right'] =
        $this->info['bottom'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_CSS_Length(),
            new HTMLPurifier_AttrDef_CSS_Percentage(),
            new HTMLPurifier_AttrDef_Enum(array('auto')),
        ));
        $this->info['z-index'] = new HTMLPurifier_AttrDef_CSS_Composite(array(
            new HTMLPurifier_AttrDef_Integer(),
            new HTMLPurifier_AttrDef_Enum(array('auto')),
        ));
    }

    /**
     * Performs extra config-based processing. Based off of
     * HTMLPurifier_HTMLDefinition.
     * @todo Refactor duplicate elements into common class (probably using
     *       composition, not inheritance).
     */
    protected function setupConfigStuff($config) {

        // setup allowed elements
        $support = "(for information on implementing this, see the ".
                   "support forums) ";
        $allowed_properties = $config->get('CSS.AllowedProperties');
        if ($allowed_properties !== null) {
            foreach ($this->info as $name => $d) {
                if(!isset($allowed_properties[$name])) unset($this->info[$name]);
                unset($allowed_properties[$name]);
            }
            // emit errors
            foreach ($allowed_properties as $name => $d) {
                // :TODO: Is this htmlspecialchars() call really necessary?
                $name = htmlspecialchars($name);
                trigger_error("Style attribute '$name' is not supported $support", E_USER_WARNING);
            }
        }

        $forbidden_properties = $config->get('CSS.ForbiddenProperties');
        if ($forbidden_properties !== null) {
            foreach ($this->info as $name => $d) {
                if (isset($forbidden_properties[$name])) {
                    unset($this->info[$name]);
                }
            }
        }

    }
}





/**
 * Defines allowed child nodes and validates tokens against it.
 */
abstract class HTMLPurifier_ChildDef
{
    /**
     * Type of child definition, usually right-most part of class name lowercase.
     * Used occasionally in terms of context.
     */
    public $type;

    /**
     * Bool that indicates whether or not an empty array of children is okay
     *
     * This is necessary for redundant checking when changes affecting
     * a child node may cause a parent node to now be disallowed.
     */
    public $allow_empty;

    /**
     * Lookup array of all elements that this definition could possibly allow
     */
    public $elements = array();

    /**
     * Get lookup of tag names that should not close this element automatically.
     * All other elements will do so.
     */
    public function getAllowedElements($config) {
        return $this->elements;
    }

    /**
     * Validates nodes according to definition and returns modification.
     *
     * @param $tokens_of_children Array of HTMLPurifier_Token
     * @param $config HTMLPurifier_Config object
     * @param $context HTMLPurifier_Context object
     * @return bool true to leave nodes as is
     * @return bool false to remove parent node
     * @return array of replacement child tokens
     */
    abstract public function validateChildren($tokens_of_children, $config, $context);
}





/**
 * Configuration object that triggers customizable behavior.
 *
 * @warning This class is strongly defined: that means that the class
 *          will fail if an undefined directive is retrieved or set.
 *
 * @note Many classes that could (although many times don't) use the
 *       configuration object make it a mandatory parameter.  This is
 *       because a configuration object should always be forwarded,
 *       otherwise, you run the risk of missing a parameter and then
 *       being stumped when a configuration directive doesn't work.
 *
 * @todo Reconsider some of the public member variables
 */
class HTMLPurifier_Config
{

    /**
     * HTML Purifier's version
     */
    public $version = '4.3.0';

    /**
     * Bool indicator whether or not to automatically finalize
     * the object if a read operation is done
     */
    public $autoFinalize = true;

    // protected member variables

    /**
     * Namespace indexed array of serials for specific namespaces (see
     * getSerial() for more info).
     */
    protected $serials = array();

    /**
     * Serial for entire configuration object
     */
    protected $serial;

    /**
     * Parser for variables
     */
    protected $parser;

    /**
     * Reference HTMLPurifier_ConfigSchema for value checking
     * @note This is public for introspective purposes. Please don't
     *       abuse!
     */
    public $def;

    /**
     * Indexed array of definitions
     */
    protected $definitions;

    /**
     * Bool indicator whether or not config is finalized
     */
    protected $finalized = false;

    /**
     * Property list containing configuration directives.
     */
    protected $plist;

    /**
     * Whether or not a set is taking place due to an
     * alias lookup.
     */
    private $aliasMode;

    /**
     * Set to false if you do not want line and file numbers in errors
     * (useful when unit testing).  This will also compress some errors
     * and exceptions.
     */
    public $chatty = true;

    /**
     * Current lock; only gets to this namespace are allowed.
     */
    private $lock;

    /**
     * @param $definition HTMLPurifier_ConfigSchema that defines what directives
     *                    are allowed.
     */
    public function __construct($definition, $parent = null) {
        $parent = $parent ? $parent : $definition->defaultPlist;
        $this->plist = new HTMLPurifier_PropertyList($parent);
        $this->def = $definition; // keep a copy around for checking
        $this->parser = new HTMLPurifier_VarParser_Flexible();
    }

    /**
     * Convenience constructor that creates a config object based on a mixed var
     * @param mixed $config Variable that defines the state of the config
     *                      object. Can be: a HTMLPurifier_Config() object,
     *                      an array of directives based on loadArray(),
     *                      or a string filename of an ini file.
     * @param HTMLPurifier_ConfigSchema Schema object
     * @return Configured HTMLPurifier_Config object
     */
    public static function create($config, $schema = null) {
        if ($config instanceof HTMLPurifier_Config) {
            // pass-through
            return $config;
        }
        if (!$schema) {
            $ret = HTMLPurifier_Config::createDefault();
        } else {
            $ret = new HTMLPurifier_Config($schema);
        }
        if (is_string($config)) $ret->loadIni($config);
        elseif (is_array($config)) $ret->loadArray($config);
        return $ret;
    }

    /**
     * Creates a new config object that inherits from a previous one.
     * @param HTMLPurifier_Config $config Configuration object to inherit
     *        from.
     * @return HTMLPurifier_Config object with $config as its parent.
     */
    public static function inherit(HTMLPurifier_Config $config) {
        return new HTMLPurifier_Config($config->def, $config->plist);
    }

    /**
     * Convenience constructor that creates a default configuration object.
     * @return Default HTMLPurifier_Config object.
     */
    public static function createDefault() {
        $definition = HTMLPurifier_ConfigSchema::instance();
        $config = new HTMLPurifier_Config($definition);
        return $config;
    }

    /**
     * Retreives a value from the configuration.
     * @param $key String key
     */
    public function get($key, $a = null) {
        if ($a !== null) {
            $this->triggerError("Using deprecated API: use \$config->get('$key.$a') instead", E_USER_WARNING);
            $key = "$key.$a";
        }
        if (!$this->finalized) $this->autoFinalize();
        if (!isset($this->def->info[$key])) {
            // can't add % due to SimpleTest bug
            $this->triggerError('Cannot retrieve value of undefined directive ' . htmlspecialchars($key),
                E_USER_WARNING);
            return;
        }
        if (isset($this->def->info[$key]->isAlias)) {
            $d = $this->def->info[$key];
            $this->triggerError('Cannot get value from aliased directive, use real name ' . $d->key,
                E_USER_ERROR);
            return;
        }
        if ($this->lock) {
            list($ns) = explode('.', $key);
            if ($ns !== $this->lock) {
                $this->triggerError('Cannot get value of namespace ' . $ns . ' when lock for ' . $this->lock . ' is active, this probably indicates a Definition setup method is accessing directives that are not within its namespace', E_USER_ERROR);
                return;
            }
        }
        return $this->plist->get($key);
    }

    /**
     * Retreives an array of directives to values from a given namespace
     * @param $namespace String namespace
     */
    public function getBatch($namespace) {
        if (!$this->finalized) $this->autoFinalize();
        $full = $this->getAll();
        if (!isset($full[$namespace])) {
            $this->triggerError('Cannot retrieve undefined namespace ' . htmlspecialchars($namespace),
                E_USER_WARNING);
            return;
        }
        return $full[$namespace];
    }

    /**
     * Returns a md5 signature of a segment of the configuration object
     * that uniquely identifies that particular configuration
     * @note Revision is handled specially and is removed from the batch
     *       before processing!
     * @param $namespace Namespace to get serial for
     */
    public function getBatchSerial($namespace) {
        if (empty($this->serials[$namespace])) {
            $batch = $this->getBatch($namespace);
            unset($batch['DefinitionRev']);
            $this->serials[$namespace] = md5(serialize($batch));
        }
        return $this->serials[$namespace];
    }

    /**
     * Returns a md5 signature for the entire configuration object
     * that uniquely identifies that particular configuration
     */
    public function getSerial() {
        if (empty($this->serial)) {
            $this->serial = md5(serialize($this->getAll()));
        }
        return $this->serial;
    }

    /**
     * Retrieves all directives, organized by namespace
     * @warning This is a pretty inefficient function, avoid if you can
     */
    public function getAll() {
        if (!$this->finalized) $this->autoFinalize();
        $ret = array();
        foreach ($this->plist->squash() as $name => $value) {
            list($ns, $key) = explode('.', $name, 2);
            $ret[$ns][$key] = $value;
        }
        return $ret;
    }

    /**
     * Sets a value to configuration.
     * @param $key String key
     * @param $value Mixed value
     */
    public function set($key, $value, $a = null) {
        if (strpos($key, '.') === false) {
            $namespace = $key;
            $directive = $value;
            $value = $a;
            $key = "$key.$directive";
            $this->triggerError("Using deprecated API: use \$config->set('$key', ...) instead", E_USER_NOTICE);
        } else {
            list($namespace) = explode('.', $key);
        }
        if ($this->isFinalized('Cannot set directive after finalization')) return;
        if (!isset($this->def->info[$key])) {
            $this->triggerError('Cannot set undefined directive ' . htmlspecialchars($key) . ' to value',
                E_USER_WARNING);
            return;
        }
        $def = $this->def->info[$key];

        if (isset($def->isAlias)) {
            if ($this->aliasMode) {
                $this->triggerError('Double-aliases not allowed, please fix '.
                    'ConfigSchema bug with' . $key, E_USER_ERROR);
                return;
            }
            $this->aliasMode = true;
            $this->set($def->key, $value);
            $this->aliasMode = false;
            $this->triggerError("$key is an alias, preferred directive name is {$def->key}", E_USER_NOTICE);
            return;
        }

        // Raw type might be negative when using the fully optimized form
        // of stdclass, which indicates allow_null == true
        $rtype = is_int($def) ? $def : $def->type;
        if ($rtype < 0) {
            $type = -$rtype;
            $allow_null = true;
        } else {
            $type = $rtype;
            $allow_null = isset($def->allow_null);
        }

        try {
            $value = $this->parser->parse($value, $type, $allow_null);
        } catch (HTMLPurifier_VarParserException $e) {
            $this->triggerError('Value for ' . $key . ' is of invalid type, should be ' . HTMLPurifier_VarParser::getTypeName($type), E_USER_WARNING);
            return;
        }
        if (is_string($value) && is_object($def)) {
            // resolve value alias if defined
            if (isset($def->aliases[$value])) {
                $value = $def->aliases[$value];
            }
            // check to see if the value is allowed
            if (isset($def->allowed) && !isset($def->allowed[$value])) {
                $this->triggerError('Value not supported, valid values are: ' .
                    $this->_listify($def->allowed), E_USER_WARNING);
                return;
            }
        }
        $this->plist->set($key, $value);

        // reset definitions if the directives they depend on changed
        // this is a very costly process, so it's discouraged
        // with finalization
        if ($namespace == 'HTML' || $namespace == 'CSS' || $namespace == 'URI') {
            $this->definitions[$namespace] = null;
        }

        $this->serials[$namespace] = false;
    }

    /**
     * Convenience function for error reporting
     */
    private function _listify($lookup) {
        $list = array();
        foreach ($lookup as $name => $b) $list[] = $name;
        return implode(', ', $list);
    }

    /**
     * Retrieves object reference to the HTML definition.
     * @param $raw Return a copy that has not been setup yet. Must be
     *             called before it's been setup, otherwise won't work.
     * @param $optimized If true, this method may return null, to
     *             indicate that a cached version of the modified
     *             definition object is available and no further edits
     *             are necessary.  Consider using
     *             maybeGetRawHTMLDefinition, which is more explicitly
     *             named, instead.
     */
    public function getHTMLDefinition($raw = false, $optimized = false) {
        return $this->getDefinition('HTML', $raw, $optimized);
    }

    /**
     * Retrieves object reference to the CSS definition
     * @param $raw Return a copy that has not been setup yet. Must be
     *             called before it's been setup, otherwise won't work.
     * @param $optimized If true, this method may return null, to
     *             indicate that a cached version of the modified
     *             definition object is available and no further edits
     *             are necessary.  Consider using
     *             maybeGetRawCSSDefinition, which is more explicitly
     *             named, instead.
     */
    public function getCSSDefinition($raw = false, $optimized = false) {
        return $this->getDefinition('CSS', $raw, $optimized);
    }

    /**
     * Retrieves object reference to the URI definition
     * @param $raw Return a copy that has not been setup yet. Must be
     *             called before it's been setup, otherwise won't work.
     * @param $optimized If true, this method may return null, to
     *             indicate that a cached version of the modified
     *             definition object is available and no further edits
     *             are necessary.  Consider using
     *             maybeGetRawURIDefinition, which is more explicitly
     *             named, instead.
     */
    public function getURIDefinition($raw = false, $optimized = false) {
        return $this->getDefinition('URI', $raw, $optimized);
    }

    /**
     * Retrieves a definition
     * @param $type Type of definition: HTML, CSS, etc
     * @param $raw  Whether or not definition should be returned raw
     * @param $optimized Only has an effect when $raw is true.  Whether
     *        or not to return null if the result is already present in
     *        the cache.  This is off by default for backwards
     *        compatibility reasons, but you need to do things this
     *        way in order to ensure that caching is done properly.
     *        Check out enduser-customize.html for more details.
     *        We probably won't ever change this default, as much as the
     *        maybe semantics is the "right thing to do."
     */
    public function getDefinition($type, $raw = false, $optimized = false) {
        if ($optimized && !$raw) {
            throw new HTMLPurifier_Exception("Cannot set optimized = true when raw = false");
        }
        if (!$this->finalized) $this->autoFinalize();
        // temporarily suspend locks, so we can handle recursive definition calls
        $lock = $this->lock;
        $this->lock = null;
        $factory = HTMLPurifier_DefinitionCacheFactory::instance();
        $cache = $factory->create($type, $this);
        $this->lock = $lock;
        if (!$raw) {
            // full definition
            // ---------------
            // check if definition is in memory
            if (!empty($this->definitions[$type])) {
                $def = $this->definitions[$type];
                // check if the definition is setup
                if ($def->setup) {
                    return $def;
                } else {
                    $def->setup($this);
                    if ($def->optimized) $cache->add($def, $this);
                    return $def;
                }
            }
            // check if definition is in cache
            $def = $cache->get($this);
            if ($def) {
                // definition in cache, save to memory and return it
                $this->definitions[$type] = $def;
                return $def;
            }
            // initialize it
            $def = $this->initDefinition($type);
            // set it up
            $this->lock = $type;
            $def->setup($this);
            $this->lock = null;
            // save in cache
            $cache->add($def, $this);
            // return it
            return $def;
        } else {
            // raw definition
            // --------------
            // check preconditions
            $def = null;
            if ($optimized) {
                if (is_null($this->get($type . '.DefinitionID'))) {
                    // fatally error out if definition ID not set
                    throw new HTMLPurifier_Exception("Cannot retrieve raw version without specifying %$type.DefinitionID");
                }
            }
            if (!empty($this->definitions[$type])) {
                $def = $this->definitions[$type];
                if ($def->setup && !$optimized) {
                    $extra = $this->chatty ? " (try moving this code block earlier in your initialization)" : "";
                    throw new HTMLPurifier_Exception("Cannot retrieve raw definition after it has already been setup" . $extra);
                }
                if ($def->optimized === null) {
                    $extra = $this->chatty ? " (try flushing your cache)" : "";
                    throw new HTMLPurifier_Exception("Optimization status of definition is unknown" . $extra);
                }
                if ($def->optimized !== $optimized) {
                    $msg = $optimized ? "optimized" : "unoptimized";
                    $extra = $this->chatty ? " (this backtrace is for the first inconsistent call, which was for a $msg raw definition)" : "";
                    throw new HTMLPurifier_Exception("Inconsistent use of optimized and unoptimized raw definition retrievals" . $extra);
                }
            }
            // check if definition was in memory
            if ($def) {
                if ($def->setup) {
                    // invariant: $optimized === true (checked above)
                    return null;
                } else {
                    return $def;
                }
            }
            // if optimized, check if definition was in cache
            // (because we do the memory check first, this formulation
            // is prone to cache slamming, but I think
            // guaranteeing that either /all/ of the raw
            // setup code or /none/ of it is run is more important.)
            if ($optimized) {
                // This code path only gets run once; once we put
                // something in $definitions (which is guaranteed by the
                // trailing code), we always short-circuit above.
                $def = $cache->get($this);
                if ($def) {
                    // save the full definition for later, but don't
                    // return it yet
                    $this->definitions[$type] = $def;
                    return null;
                }
            }
            // check invariants for creation
            if (!$optimized) {
                if (!is_null($this->get($type . '.DefinitionID'))) {
                    if ($this->chatty) {
                        $this->triggerError("Due to a documentation error in previous version of HTML Purifier, your definitions are not being cached.  If this is OK, you can remove the %$type.DefinitionRev and %$type.DefinitionID declaration.  Otherwise, modify your code to use maybeGetRawDefinition, and test if the returned value is null before making any edits (if it is null, that means that a cached version is available, and no raw operations are necessary).  See <a href='http://htmlpurifier.org/docs/enduser-customize.html#optimized'>Customize</a> for more details", E_USER_WARNING);
                    } else {
                        $this->triggerError("Useless DefinitionID declaration", E_USER_WARNING);
                    }
                }
            }
            // initialize it
            $def = $this->initDefinition($type);
            $def->optimized = $optimized;
            return $def;
        }
        throw new HTMLPurifier_Exception("The impossible happened!");
    }

    private function initDefinition($type) {
        // quick checks failed, let's create the object
        if ($type == 'HTML') {
            $def = new HTMLPurifier_HTMLDefinition();
        } elseif ($type == 'CSS') {
            $def = new HTMLPurifier_CSSDefinition();
        } elseif ($type == 'URI') {
            $def = new HTMLPurifier_URIDefinition();
        } else {
            throw new HTMLPurifier_Exception("Definition of $type type not supported");
        }
        $this->definitions[$type] = $def;
        return $def;
    }

    public function maybeGetRawDefinition($name) {
        return $this->getDefinition($name, true, true);
    }

    public function maybeGetRawHTMLDefinition() {
        return $this->getDefinition('HTML', true, true);
    }

    public function maybeGetRawCSSDefinition() {
        return $this->getDefinition('CSS', true, true);
    }

    public function maybeGetRawURIDefinition() {
        return $this->getDefinition('URI', true, true);
    }

    /**
     * Loads configuration values from an array with the following structure:
     * Namespace.Directive => Value
     * @param $config_array Configuration associative array
     */
    public function loadArray($config_array) {
        if ($this->isFinalized('Cannot load directives after finalization')) return;
        foreach ($config_array as $key => $value) {
            $key = str_replace('_', '.', $key);
            if (strpos($key, '.') !== false) {
                $this->set($key, $value);
            } else {
                $namespace = $key;
                $namespace_values = $value;
                foreach ($namespace_values as $directive => $value) {
                    $this->set($namespace .'.'. $directive, $value);
                }
            }
        }
    }

    /**
     * Returns a list of array(namespace, directive) for all directives
     * that are allowed in a web-form context as per an allowed
     * namespaces/directives list.
     * @param $allowed List of allowed namespaces/directives
     */
    public static function getAllowedDirectivesForForm($allowed, $schema = null) {
        if (!$schema) {
            $schema = HTMLPurifier_ConfigSchema::instance();
        }
        if ($allowed !== true) {
             if (is_string($allowed)) $allowed = array($allowed);
             $allowed_ns = array();
             $allowed_directives = array();
             $blacklisted_directives = array();
             foreach ($allowed as $ns_or_directive) {
                 if (strpos($ns_or_directive, '.') !== false) {
                     // directive
                     if ($ns_or_directive[0] == '-') {
                         $blacklisted_directives[substr($ns_or_directive, 1)] = true;
                     } else {
                         $allowed_directives[$ns_or_directive] = true;
                     }
                 } else {
                     // namespace
                     $allowed_ns[$ns_or_directive] = true;
                 }
             }
        }
        $ret = array();
        foreach ($schema->info as $key => $def) {
            list($ns, $directive) = explode('.', $key, 2);
            if ($allowed !== true) {
                if (isset($blacklisted_directives["$ns.$directive"])) continue;
                if (!isset($allowed_directives["$ns.$directive"]) && !isset($allowed_ns[$ns])) continue;
            }
            if (isset($def->isAlias)) continue;
            if ($directive == 'DefinitionID' || $directive == 'DefinitionRev') continue;
            $ret[] = array($ns, $directive);
        }
        return $ret;
    }

    /**
     * Loads configuration values from $_GET/$_POST that were posted
     * via ConfigForm
     * @param $array $_GET or $_POST array to import
     * @param $index Index/name that the config variables are in
     * @param $allowed List of allowed namespaces/directives
     * @param $mq_fix Boolean whether or not to enable magic quotes fix
     * @param $schema Instance of HTMLPurifier_ConfigSchema to use, if not global copy
     */
    public static function loadArrayFromForm($array, $index = false, $allowed = true, $mq_fix = true, $schema = null) {
        $ret = HTMLPurifier_Config::prepareArrayFromForm($array, $index, $allowed, $mq_fix, $schema);
        $config = HTMLPurifier_Config::create($ret, $schema);
        return $config;
    }

    /**
     * Merges in configuration values from $_GET/$_POST to object. NOT STATIC.
     * @note Same parameters as loadArrayFromForm
     */
    public function mergeArrayFromForm($array, $index = false, $allowed = true, $mq_fix = true) {
         $ret = HTMLPurifier_Config::prepareArrayFromForm($array, $index, $allowed, $mq_fix, $this->def);
         $this->loadArray($ret);
    }

    /**
     * Prepares an array from a form into something usable for the more
     * strict parts of HTMLPurifier_Config
     */
    public static function prepareArrayFromForm($array, $index = false, $allowed = true, $mq_fix = true, $schema = null) {
        if ($index !== false) $array = (isset($array[$index]) && is_array($array[$index])) ? $array[$index] : array();
        $mq = $mq_fix && function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc();

        $allowed = HTMLPurifier_Config::getAllowedDirectivesForForm($allowed, $schema);
        $ret = array();
        foreach ($allowed as $key) {
            list($ns, $directive) = $key;
            $skey = "$ns.$directive";
            if (!empty($array["Null_$skey"])) {
                $ret[$ns][$directive] = null;
                continue;
            }
            if (!isset($array[$skey])) continue;
            $value = $mq ? stripslashes($array[$skey]) : $array[$skey];
            $ret[$ns][$directive] = $value;
        }
        return $ret;
    }

    /**
     * Loads configuration values from an ini file
     * @param $filename Name of ini file
     */
    public function loadIni($filename) {
        if ($this->isFinalized('Cannot load directives after finalization')) return;
        $array = parse_ini_file($filename, true);
        $this->loadArray($array);
    }

    /**
     * Checks whether or not the configuration object is finalized.
     * @param $error String error message, or false for no error
     */
    public function isFinalized($error = false) {
        if ($this->finalized && $error) {
            $this->triggerError($error, E_USER_ERROR);
        }
        return $this->finalized;
    }

    /**
     * Finalizes configuration only if auto finalize is on and not
     * already finalized
     */
    public function autoFinalize() {
        if ($this->autoFinalize) {
            $this->finalize();
        } else {
            $this->plist->squash(true);
        }
    }

    /**
     * Finalizes a configuration object, prohibiting further change
     */
    public function finalize() {
        $this->finalized = true;
        unset($this->parser);
    }

    /**
     * Produces a nicely formatted error message by supplying the
     * stack frame information OUTSIDE of HTMLPurifier_Config.
     */
    protected function triggerError($msg, $no) {
        // determine previous stack frame
        $extra = '';
        if ($this->chatty) {
            $trace = debug_backtrace();
            // zip(tail(trace), trace) -- but PHP is not Haskell har har
            for ($i = 0, $c = count($trace); $i < $c - 1; $i++) {
                if ($trace[$i + 1]['class'] === 'HTMLPurifier_Config') {
                    continue;
                }
                $frame = $trace[$i];
                $extra = " invoked on line {$frame['line']} in file {$frame['file']}";
                break;
            }
        }
        trigger_error($msg . $extra, $no);
    }

    /**
     * Returns a serialized form of the configuration object that can
     * be reconstituted.
     */
    public function serialize() {
        $this->getDefinition('HTML');
        $this->getDefinition('CSS');
        $this->getDefinition('URI');
        return serialize($this);
    }

}





/**
 * Configuration definition, defines directives and their defaults.
 */
class HTMLPurifier_ConfigSchema {

    /**
     * Defaults of the directives and namespaces.
     * @note This shares the exact same structure as HTMLPurifier_Config::$conf
     */
    public $defaults = array();

    /**
     * The default property list. Do not edit this property list.
     */
    public $defaultPlist;

    /**
     * Definition of the directives. The structure of this is:
     *
     *  array(
     *      'Namespace' => array(
     *          'Directive' => new stdclass(),
     *      )
     *  )
     *
     * The stdclass may have the following properties:
     *
     *  - If isAlias isn't set:
     *      - type: Integer type of directive, see HTMLPurifier_VarParser for definitions
     *      - allow_null: If set, this directive allows null values
     *      - aliases: If set, an associative array of value aliases to real values
     *      - allowed: If set, a lookup array of allowed (string) values
     *  - If isAlias is set:
     *      - namespace: Namespace this directive aliases to
     *      - name: Directive name this directive aliases to
     *
     * In certain degenerate cases, stdclass will actually be an integer. In
     * that case, the value is equivalent to an stdclass with the type
     * property set to the integer. If the integer is negative, type is
     * equal to the absolute value of integer, and allow_null is true.
     *
     * This class is friendly with HTMLPurifier_Config. If you need introspection
     * about the schema, you're better of using the ConfigSchema_Interchange,
     * which uses more memory but has much richer information.
     */
    public $info = array();

    /**
     * Application-wide singleton
     */
    static protected $singleton;

    public function __construct() {
        $this->defaultPlist = new HTMLPurifier_PropertyList();
    }

    /**
     * Unserializes the default ConfigSchema.
     */
    public static function makeFromSerial() {
        $contents = file_get_contents(HTMLPURIFIER_PREFIX . '/HTMLPurifier/ConfigSchema/schema.ser');
        $r = unserialize($contents);
        if (!$r) {
            $hash = sha1($contents);
            trigger_error("Unserialization of configuration schema failed, sha1 of file was $hash", E_USER_ERROR);
        }
        return $r;
    }

    /**
     * Retrieves an instance of the application-wide configuration definition.
     */
    public static function instance($prototype = null) {
        if ($prototype !== null) {
            HTMLPurifier_ConfigSchema::$singleton = $prototype;
        } elseif (HTMLPurifier_ConfigSchema::$singleton === null || $prototype === true) {
            HTMLPurifier_ConfigSchema::$singleton = HTMLPurifier_ConfigSchema::makeFromSerial();
        }
        return HTMLPurifier_ConfigSchema::$singleton;
    }

    /**
     * Defines a directive for configuration
     * @warning Will fail of directive's namespace is defined.
     * @warning This method's signature is slightly different from the legacy
     *          define() static method! Beware!
     * @param $namespace Namespace the directive is in
     * @param $name Key of directive
     * @param $default Default value of directive
     * @param $type Allowed type of the directive. See
     *      HTMLPurifier_DirectiveDef::$type for allowed values
     * @param $allow_null Whether or not to allow null values
     */
    public function add($key, $default, $type, $allow_null) {
        $obj = new stdclass();
        $obj->type = is_int($type) ? $type : HTMLPurifier_VarParser::$types[$type];
        if ($allow_null) $obj->allow_null = true;
        $this->info[$key] = $obj;
        $this->defaults[$key] = $default;
        $this->defaultPlist->set($key, $default);
    }

    /**
     * Defines a directive value alias.
     *
     * Directive value aliases are convenient for developers because it lets
     * them set a directive to several values and get the same result.
     * @param $namespace Directive's namespace
     * @param $name Name of Directive
     * @param $aliases Hash of aliased values to the real alias
     */
    public function addValueAliases($key, $aliases) {
        if (!isset($this->info[$key]->aliases)) {
            $this->info[$key]->aliases = array();
        }
        foreach ($aliases as $alias => $real) {
            $this->info[$key]->aliases[$alias] = $real;
        }
    }

    /**
     * Defines a set of allowed values for a directive.
     * @warning This is slightly different from the corresponding static
     *          method definition.
     * @param $namespace Namespace of directive
     * @param $name Name of directive
     * @param $allowed Lookup array of allowed values
     */
    public function addAllowedValues($key, $allowed) {
        $this->info[$key]->allowed = $allowed;
    }

    /**
     * Defines a directive alias for backwards compatibility
     * @param $namespace
     * @param $name Directive that will be aliased
     * @param $new_namespace
     * @param $new_name Directive that the alias will be to
     */
    public function addAlias($key, $new_key) {
        $obj = new stdclass;
        $obj->key = $new_key;
        $obj->isAlias = true;
        $this->info[$key] = $obj;
    }

    /**
     * Replaces any stdclass that only has the type property with type integer.
     */
    public function postProcess() {
        foreach ($this->info as $key => $v) {
            if (count((array) $v) == 1) {
                $this->info[$key] = $v->type;
            } elseif (count((array) $v) == 2 && isset($v->allow_null)) {
                $this->info[$key] = -$v->type;
            }
        }
    }

}





/**
 * @todo Unit test
 */
class HTMLPurifier_ContentSets
{

    /**
     * List of content set strings (pipe seperators) indexed by name.
     */
    public $info = array();

    /**
     * List of content set lookups (element => true) indexed by name.
     * @note This is in HTMLPurifier_HTMLDefinition->info_content_sets
     */
    public $lookup = array();

    /**
     * Synchronized list of defined content sets (keys of info)
     */
    protected $keys = array();
    /**
     * Synchronized list of defined content values (values of info)
     */
    protected $values = array();

    /**
     * Merges in module's content sets, expands identifiers in the content
     * sets and populates the keys, values and lookup member variables.
     * @param $modules List of HTMLPurifier_HTMLModule
     */
    public function __construct($modules) {
        if (!is_array($modules)) $modules = array($modules);
        // populate content_sets based on module hints
        // sorry, no way of overloading
        foreach ($modules as $module_i => $module) {
            foreach ($module->content_sets as $key => $value) {
                $temp = $this->convertToLookup($value);
                if (isset($this->lookup[$key])) {
                    // add it into the existing content set
                    $this->lookup[$key] = array_merge($this->lookup[$key], $temp);
                } else {
                    $this->lookup[$key] = $temp;
                }
            }
        }
        $old_lookup = false;
        while ($old_lookup !== $this->lookup) {
            $old_lookup = $this->lookup;
            foreach ($this->lookup as $i => $set) {
                $add = array();
                foreach ($set as $element => $x) {
                    if (isset($this->lookup[$element])) {
                        $add += $this->lookup[$element];
                        unset($this->lookup[$i][$element]);
                    }
                }
                $this->lookup[$i] += $add;
            }
        }

        foreach ($this->lookup as $key => $lookup) {
            $this->info[$key] = implode(' | ', array_keys($lookup));
        }
        $this->keys   = array_keys($this->info);
        $this->values = array_values($this->info);
    }

    /**
     * Accepts a definition; generates and assigns a ChildDef for it
     * @param $def HTMLPurifier_ElementDef reference
     * @param $module Module that defined the ElementDef
     */
    public function generateChildDef(&$def, $module) {
        if (!empty($def->child)) return; // already done!
        $content_model = $def->content_model;
        if (is_string($content_model)) {
            // Assume that $this->keys is alphanumeric
            $def->content_model = preg_replace_callback(
                '/\b(' . implode('|', $this->keys) . ')\b/',
                array($this, 'generateChildDefCallback'),
                $content_model
            );
            //$def->content_model = str_replace(
            //    $this->keys, $this->values, $content_model);
        }
        $def->child = $this->getChildDef($def, $module);
    }

    public function generateChildDefCallback($matches) {
        return $this->info[$matches[0]];
    }

    /**
     * Instantiates a ChildDef based on content_model and content_model_type
     * member variables in HTMLPurifier_ElementDef
     * @note This will also defer to modules for custom HTMLPurifier_ChildDef
     *       subclasses that need content set expansion
     * @param $def HTMLPurifier_ElementDef to have ChildDef extracted
     * @return HTMLPurifier_ChildDef corresponding to ElementDef
     */
    public function getChildDef($def, $module) {
        $value = $def->content_model;
        if (is_object($value)) {
            trigger_error(
                'Literal object child definitions should be stored in '.
                'ElementDef->child not ElementDef->content_model',
                E_USER_NOTICE
            );
            return $value;
        }
        switch ($def->content_model_type) {
            case 'required':
                return new HTMLPurifier_ChildDef_Required($value);
            case 'optional':
                return new HTMLPurifier_ChildDef_Optional($value);
            case 'empty':
                return new HTMLPurifier_ChildDef_Empty();
            case 'custom':
                return new HTMLPurifier_ChildDef_Custom($value);
        }
        // defer to its module
        $return = false;
        if ($module->defines_child_def) { // save a func call
            $return = $module->getChildDef($def);
        }
        if ($return !== false) return $return;
        // error-out
        trigger_error(
            'Could not determine which ChildDef class to instantiate',
            E_USER_ERROR
        );
        return false;
    }

    /**
     * Converts a string list of elements separated by pipes into
     * a lookup array.
     * @param $string List of elements
     * @return Lookup array of elements
     */
    protected function convertToLookup($string) {
        $array = explode('|', str_replace(' ', '', $string));
        $ret = array();
        foreach ($array as $i => $k) {
            $ret[$k] = true;
        }
        return $ret;
    }

}





/**
 * Registry object that contains information about the current context.
 * @warning Is a bit buggy when variables are set to null: it thinks
 *          they don't exist! So use false instead, please.
 * @note Since the variables Context deals with may not be objects,
 *       references are very important here! Do not remove!
 */
class HTMLPurifier_Context
{

    /**
     * Private array that stores the references.
     */
    private $_storage = array();

    /**
     * Registers a variable into the context.
     * @param $name String name
     * @param $ref Reference to variable to be registered
     */
    public function register($name, &$ref) {
        if (isset($this->_storage[$name])) {
            trigger_error("Name $name produces collision, cannot re-register",
                          E_USER_ERROR);
            return;
        }
        $this->_storage[$name] =& $ref;
    }

    /**
     * Retrieves a variable reference from the context.
     * @param $name String name
     * @param $ignore_error Boolean whether or not to ignore error
     */
    public function &get($name, $ignore_error = false) {
        if (!isset($this->_storage[$name])) {
            if (!$ignore_error) {
                trigger_error("Attempted to retrieve non-existent variable $name",
                              E_USER_ERROR);
            }
            $var = null; // so we can return by reference
            return $var;
        }
        return $this->_storage[$name];
    }

    /**
     * Destorys a variable in the context.
     * @param $name String name
     */
    public function destroy($name) {
        if (!isset($this->_storage[$name])) {
            trigger_error("Attempted to destroy non-existent variable $name",
                          E_USER_ERROR);
            return;
        }
        unset($this->_storage[$name]);
    }

    /**
     * Checks whether or not the variable exists.
     * @param $name String name
     */
    public function exists($name) {
        return isset($this->_storage[$name]);
    }

    /**
     * Loads a series of variables from an associative array
     * @param $context_array Assoc array of variables to load
     */
    public function loadArray($context_array) {
        foreach ($context_array as $key => $discard) {
            $this->register($key, $context_array[$key]);
        }
    }

}





/**
 * Abstract class representing Definition cache managers that implements
 * useful common methods and is a factory.
 * @todo Create a separate maintenance file advanced users can use to
 *       cache their custom HTMLDefinition, which can be loaded
 *       via a configuration directive
 * @todo Implement memcached
 */
abstract class HTMLPurifier_DefinitionCache
{

    public $type;

    /**
     * @param $name Type of definition objects this instance of the
     *      cache will handle.
     */
    public function __construct($type) {
        $this->type = $type;
    }

    /**
     * Generates a unique identifier for a particular configuration
     * @param Instance of HTMLPurifier_Config
     */
    public function generateKey($config) {
        return $config->version . ',' . // possibly replace with function calls
               $config->getBatchSerial($this->type) . ',' .
               $config->get($this->type . '.DefinitionRev');
    }

    /**
     * Tests whether or not a key is old with respect to the configuration's
     * version and revision number.
     * @param $key Key to test
     * @param $config Instance of HTMLPurifier_Config to test against
     */
    public function isOld($key, $config) {
        if (substr_count($key, ',') < 2) return true;
        list($version, $hash, $revision) = explode(',', $key, 3);
        $compare = version_compare($version, $config->version);
        // version mismatch, is always old
        if ($compare != 0) return true;
        // versions match, ids match, check revision number
        if (
            $hash == $config->getBatchSerial($this->type) &&
            $revision < $config->get($this->type . '.DefinitionRev')
        ) return true;
        return false;
    }

    /**
     * Checks if a definition's type jives with the cache's type
     * @note Throws an error on failure
     * @param $def Definition object to check
     * @return Boolean true if good, false if not
     */
    public function checkDefType($def) {
        if ($def->type !== $this->type) {
            trigger_error("Cannot use definition of type {$def->type} in cache for {$this->type}");
            return false;
        }
        return true;
    }

    /**
     * Adds a definition object to the cache
     */
    abstract public function add($def, $config);

    /**
     * Unconditionally saves a definition object to the cache
     */
    abstract public function set($def, $config);

    /**
     * Replace an object in the cache
     */
    abstract public function replace($def, $config);

    /**
     * Retrieves a definition object from the cache
     */
    abstract public function get($config);

    /**
     * Removes a definition object to the cache
     */
    abstract public function remove($config);

    /**
     * Clears all objects from cache
     */
    abstract public function flush($config);

    /**
     * Clears all expired (older version or revision) objects from cache
     * @note Be carefuly implementing this method as flush. Flush must
     *       not interfere with other Definition types, and cleanup()
     *       should not be repeatedly called by userland code.
     */
    abstract public function cleanup($config);

}





/**
 * Responsible for creating definition caches.
 */
class HTMLPurifier_DefinitionCacheFactory
{

    protected $caches = array('Serializer' => array());
    protected $implementations = array();
    protected $decorators = array();

    /**
     * Initialize default decorators
     */
    public function setup() {
        $this->addDecorator('Cleanup');
    }

    /**
     * Retrieves an instance of global definition cache factory.
     */
    public static function instance($prototype = null) {
        static $instance;
        if ($prototype !== null) {
            $instance = $prototype;
        } elseif ($instance === null || $prototype === true) {
            $instance = new HTMLPurifier_DefinitionCacheFactory();
            $instance->setup();
        }
        return $instance;
    }

    /**
     * Registers a new definition cache object
     * @param $short Short name of cache object, for reference
     * @param $long Full class name of cache object, for construction
     */
    public function register($short, $long) {
        $this->implementations[$short] = $long;
    }

    /**
     * Factory method that creates a cache object based on configuration
     * @param $name Name of definitions handled by cache
     * @param $config Instance of HTMLPurifier_Config
     */
    public function create($type, $config) {
        $method = $config->get('Cache.DefinitionImpl');
        if ($method === null) {
            return new HTMLPurifier_DefinitionCache_Null($type);
        }
        if (!empty($this->caches[$method][$type])) {
            return $this->caches[$method][$type];
        }
        if (
          isset($this->implementations[$method]) &&
          class_exists($class = $this->implementations[$method], false)
        ) {
            $cache = new $class($type);
        } else {
            if ($method != 'Serializer') {
                trigger_error("Unrecognized DefinitionCache $method, using Serializer instead", E_USER_WARNING);
            }
            $cache = new HTMLPurifier_DefinitionCache_Serializer($type);
        }
        foreach ($this->decorators as $decorator) {
            $new_cache = $decorator->decorate($cache);
            // prevent infinite recursion in PHP 4
            unset($cache);
            $cache = $new_cache;
        }
        $this->caches[$method][$type] = $cache;
        return $this->caches[$method][$type];
    }

    /**
     * Registers a decorator to add to all new cache objects
     * @param
     */
    public function addDecorator($decorator) {
        if (is_string($decorator)) {
            $class = "HTMLPurifier_DefinitionCache_Decorator_$decorator";
            $decorator = new $class;
        }
        $this->decorators[$decorator->name] = $decorator;
    }

}





/**
 * Represents a document type, contains information on which modules
 * need to be loaded.
 * @note This class is inspected by Printer_HTMLDefinition->renderDoctype.
 *       If structure changes, please update that function.
 */
class HTMLPurifier_Doctype
{
    /**
     * Full name of doctype
     */
    public $name;

    /**
     * List of standard modules (string identifiers or literal objects)
     * that this doctype uses
     */
    public $modules = array();

    /**
     * List of modules to use for tidying up code
     */
    public $tidyModules = array();

    /**
     * Is the language derived from XML (i.e. XHTML)?
     */
    public $xml = true;

    /**
     * List of aliases for this doctype
     */
    public $aliases = array();

    /**
     * Public DTD identifier
     */
    public $dtdPublic;

    /**
     * System DTD identifier
     */
    public $dtdSystem;

    public function __construct($name = null, $xml = true, $modules = array(),
        $tidyModules = array(), $aliases = array(), $dtd_public = null, $dtd_system = null
    ) {
        $this->name         = $name;
        $this->xml          = $xml;
        $this->modules      = $modules;
        $this->tidyModules  = $tidyModules;
        $this->aliases      = $aliases;
        $this->dtdPublic    = $dtd_public;
        $this->dtdSystem    = $dtd_system;
    }
}





class HTMLPurifier_DoctypeRegistry
{

    /**
     * Hash of doctype names to doctype objects
     */
    protected $doctypes;

    /**
     * Lookup table of aliases to real doctype names
     */
    protected $aliases;

    /**
     * Registers a doctype to the registry
     * @note Accepts a fully-formed doctype object, or the
     *       parameters for constructing a doctype object
     * @param $doctype Name of doctype or literal doctype object
     * @param $modules Modules doctype will load
     * @param $modules_for_modes Modules doctype will load for certain modes
     * @param $aliases Alias names for doctype
     * @return Editable registered doctype
     */
    public function register($doctype, $xml = true, $modules = array(),
        $tidy_modules = array(), $aliases = array(), $dtd_public = null, $dtd_system = null
    ) {
        if (!is_array($modules)) $modules = array($modules);
        if (!is_array($tidy_modules)) $tidy_modules = array($tidy_modules);
        if (!is_array($aliases)) $aliases = array($aliases);
        if (!is_object($doctype)) {
            $doctype = new HTMLPurifier_Doctype(
                $doctype, $xml, $modules, $tidy_modules, $aliases, $dtd_public, $dtd_system
            );
        }
        $this->doctypes[$doctype->name] = $doctype;
        $name = $doctype->name;
        // hookup aliases
        foreach ($doctype->aliases as $alias) {
            if (isset($this->doctypes[$alias])) continue;
            $this->aliases[$alias] = $name;
        }
        // remove old aliases
        if (isset($this->aliases[$name])) unset($this->aliases[$name]);
        return $doctype;
    }

    /**
     * Retrieves reference to a doctype of a certain name
     * @note This function resolves aliases
     * @note When possible, use the more fully-featured make()
     * @param $doctype Name of doctype
     * @return Editable doctype object
     */
    public function get($doctype) {
        if (isset($this->aliases[$doctype])) $doctype = $this->aliases[$doctype];
        if (!isset($this->doctypes[$doctype])) {
            trigger_error('Doctype ' . htmlspecialchars($doctype) . ' does not exist', E_USER_ERROR);
            $anon = new HTMLPurifier_Doctype($doctype);
            return $anon;
        }
        return $this->doctypes[$doctype];
    }

    /**
     * Creates a doctype based on a configuration object,
     * will perform initialization on the doctype
     * @note Use this function to get a copy of doctype that config
     *       can hold on to (this is necessary in order to tell
     *       Generator whether or not the current document is XML
     *       based or not).
     */
    public function make($config) {
        return clone $this->get($this->getDoctypeFromConfig($config));
    }

    /**
     * Retrieves the doctype from the configuration object
     */
    public function getDoctypeFromConfig($config) {
        // recommended test
        $doctype = $config->get('HTML.Doctype');
        if (!empty($doctype)) return $doctype;
        $doctype = $config->get('HTML.CustomDoctype');
        if (!empty($doctype)) return $doctype;
        // backwards-compatibility
        if ($config->get('HTML.XHTML')) {
            $doctype = 'XHTML 1.0';
        } else {
            $doctype = 'HTML 4.01';
        }
        if ($config->get('HTML.Strict')) {
            $doctype .= ' Strict';
        } else {
            $doctype .= ' Transitional';
        }
        return $doctype;
    }

}





/**
 * Structure that stores an HTML element definition. Used by
 * HTMLPurifier_HTMLDefinition and HTMLPurifier_HTMLModule.
 * @note This class is inspected by HTMLPurifier_Printer_HTMLDefinition.
 *       Please update that class too.
 * @warning If you add new properties to this class, you MUST update
 *          the mergeIn() method.
 */
class HTMLPurifier_ElementDef
{

    /**
     * Does the definition work by itself, or is it created solely
     * for the purpose of merging into another definition?
     */
    public $standalone = true;

    /**
     * Associative array of attribute name to HTMLPurifier_AttrDef
     * @note Before being processed by HTMLPurifier_AttrCollections
     *       when modules are finalized during
     *       HTMLPurifier_HTMLDefinition->setup(), this array may also
     *       contain an array at index 0 that indicates which attribute
     *       collections to load into the full array. It may also
     *       contain string indentifiers in lieu of HTMLPurifier_AttrDef,
     *       see HTMLPurifier_AttrTypes on how they are expanded during
     *       HTMLPurifier_HTMLDefinition->setup() processing.
     */
    public $attr = array();

    /**
     * Indexed list of tag's HTMLPurifier_AttrTransform to be done before validation
     */
    public $attr_transform_pre = array();

    /**
     * Indexed list of tag's HTMLPurifier_AttrTransform to be done after validation
     */
    public $attr_transform_post = array();

    /**
     * HTMLPurifier_ChildDef of this tag.
     */
    public $child;

    /**
     * Abstract string representation of internal ChildDef rules. See
     * HTMLPurifier_ContentSets for how this is parsed and then transformed
     * into an HTMLPurifier_ChildDef.
     * @warning This is a temporary variable that is not available after
     *      being processed by HTMLDefinition
     */
    public $content_model;

    /**
     * Value of $child->type, used to determine which ChildDef to use,
     * used in combination with $content_model.
     * @warning This must be lowercase
     * @warning This is a temporary variable that is not available after
     *      being processed by HTMLDefinition
     */
    public $content_model_type;



    /**
     * Does the element have a content model (#PCDATA | Inline)*? This
     * is important for chameleon ins and del processing in
     * HTMLPurifier_ChildDef_Chameleon. Dynamically set: modules don't
     * have to worry about this one.
     */
    public $descendants_are_inline = false;

    /**
     * List of the names of required attributes this element has. Dynamically
     * populated by HTMLPurifier_HTMLDefinition::getElement
     */
    public $required_attr = array();

    /**
     * Lookup table of tags excluded from all descendants of this tag.
     * @note SGML permits exclusions for all descendants, but this is
     *       not possible with DTDs or XML Schemas. W3C has elected to
     *       use complicated compositions of content_models to simulate
     *       exclusion for children, but we go the simpler, SGML-style
     *       route of flat-out exclusions, which correctly apply to
     *       all descendants and not just children. Note that the XHTML
     *       Modularization Abstract Modules are blithely unaware of such
     *       distinctions.
     */
    public $excludes = array();

    /**
     * This tag is explicitly auto-closed by the following tags.
     */
    public $autoclose = array();

    /**
     * If a foreign element is found in this element, test if it is
     * allowed by this sub-element; if it is, instead of closing the
     * current element, place it inside this element.
     */
    public $wrap;

    /**
     * Whether or not this is a formatting element affected by the
     * "Active Formatting Elements" algorithm.
     */
    public $formatting;

    /**
     * Low-level factory constructor for creating new standalone element defs
     */
    public static function create($content_model, $content_model_type, $attr) {
        $def = new HTMLPurifier_ElementDef();
        $def->content_model = $content_model;
        $def->content_model_type = $content_model_type;
        $def->attr = $attr;
        return $def;
    }

    /**
     * Merges the values of another element definition into this one.
     * Values from the new element def take precedence if a value is
     * not mergeable.
     */
    public function mergeIn($def) {

        // later keys takes precedence
        foreach($def->attr as $k => $v) {
            if ($k === 0) {
                // merge in the includes
                // sorry, no way to override an include
                foreach ($v as $v2) {
                    $this->attr[0][] = $v2;
                }
                continue;
            }
            if ($v === false) {
                if (isset($this->attr[$k])) unset($this->attr[$k]);
                continue;
            }
            $this->attr[$k] = $v;
        }
        $this->_mergeAssocArray($this->attr_transform_pre, $def->attr_transform_pre);
        $this->_mergeAssocArray($this->attr_transform_post, $def->attr_transform_post);
        $this->_mergeAssocArray($this->excludes, $def->excludes);

        if(!empty($def->content_model)) {
            $this->content_model =
                str_replace("#SUPER", $this->content_model, $def->content_model);
            $this->child = false;
        }
        if(!empty($def->content_model_type)) {
            $this->content_model_type = $def->content_model_type;
            $this->child = false;
        }
        if(!is_null($def->child)) $this->child = $def->child;
        if(!is_null($def->formatting)) $this->formatting = $def->formatting;
        if($def->descendants_are_inline) $this->descendants_are_inline = $def->descendants_are_inline;

    }

    /**
     * Merges one array into another, removes values which equal false
     * @param $a1 Array by reference that is merged into
     * @param $a2 Array that merges into $a1
     */
    private function _mergeAssocArray(&$a1, $a2) {
        foreach ($a2 as $k => $v) {
            if ($v === false) {
                if (isset($a1[$k])) unset($a1[$k]);
                continue;
            }
            $a1[$k] = $v;
        }
    }

}





/**
 * A UTF-8 specific character encoder that handles cleaning and transforming.
 * @note All functions in this class should be static.
 */
class HTMLPurifier_Encoder
{

    /**
     * Constructor throws fatal error if you attempt to instantiate class
     */
    private function __construct() {
        trigger_error('Cannot instantiate encoder, call methods statically', E_USER_ERROR);
    }

    /**
     * Error-handler that mutes errors, alternative to shut-up operator.
     */
    public static function muteErrorHandler() {}

    /**
     * Cleans a UTF-8 string for well-formedness and SGML validity
     *
     * It will parse according to UTF-8 and return a valid UTF8 string, with
     * non-SGML codepoints excluded.
     *
     * @note Just for reference, the non-SGML code points are 0 to 31 and
     *       127 to 159, inclusive.  However, we allow code points 9, 10
     *       and 13, which are the tab, line feed and carriage return
     *       respectively. 128 and above the code points map to multibyte
     *       UTF-8 representations.
     *
     * @note Fallback code adapted from utf8ToUnicode by Henri Sivonen and
     *       hsivonen@iki.fi at <http://iki.fi/hsivonen/php-utf8/> under the
     *       LGPL license.  Notes on what changed are inside, but in general,
     *       the original code transformed UTF-8 text into an array of integer
     *       Unicode codepoints. Understandably, transforming that back to
     *       a string would be somewhat expensive, so the function was modded to
     *       directly operate on the string.  However, this discourages code
     *       reuse, and the logic enumerated here would be useful for any
     *       function that needs to be able to understand UTF-8 characters.
     *       As of right now, only smart lossless character encoding converters
     *       would need that, and I'm probably not going to implement them.
     *       Once again, PHP 6 should solve all our problems.
     */
    public static function cleanUTF8($str, $force_php = false) {

        // UTF-8 validity is checked since PHP 4.3.5
        // This is an optimization: if the string is already valid UTF-8, no
        // need to do PHP stuff. 99% of the time, this will be the case.
        // The regexp matches the XML char production, as well as well as excluding
        // non-SGML codepoints U+007F to U+009F
        if (preg_match('/^[\x{9}\x{A}\x{D}\x{20}-\x{7E}\x{A0}-\x{D7FF}\x{E000}-\x{FFFD}\x{10000}-\x{10FFFF}]*$/Du', $str)) {
            return $str;
        }

        $mState = 0; // cached expected number of octets after the current octet
                     // until the beginning of the next UTF8 character sequence
        $mUcs4  = 0; // cached Unicode character
        $mBytes = 1; // cached expected number of octets in the current sequence

        // original code involved an $out that was an array of Unicode
        // codepoints.  Instead of having to convert back into UTF-8, we've
        // decided to directly append valid UTF-8 characters onto a string
        // $out once they're done.  $char accumulates raw bytes, while $mUcs4
        // turns into the Unicode code point, so there's some redundancy.

        $out = '';
        $char = '';

        $len = strlen($str);
        for($i = 0; $i < $len; $i++) {
            $in = ord($str{$i});
            $char .= $str[$i]; // append byte to char
            if (0 == $mState) {
                // When mState is zero we expect either a US-ASCII character
                // or a multi-octet sequence.
                if (0 == (0x80 & ($in))) {
                    // US-ASCII, pass straight through.
                    if (($in <= 31 || $in == 127) &&
                        !($in == 9 || $in == 13 || $in == 10) // save \r\t\n
                    ) {
                        // control characters, remove
                    } else {
                        $out .= $char;
                    }
                    // reset
                    $char = '';
                    $mBytes = 1;
                } elseif (0xC0 == (0xE0 & ($in))) {
                    // First octet of 2 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x1F) << 6;
                    $mState = 1;
                    $mBytes = 2;
                } elseif (0xE0 == (0xF0 & ($in))) {
                    // First octet of 3 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x0F) << 12;
                    $mState = 2;
                    $mBytes = 3;
                } elseif (0xF0 == (0xF8 & ($in))) {
                    // First octet of 4 octet sequence
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x07) << 18;
                    $mState = 3;
                    $mBytes = 4;
                } elseif (0xF8 == (0xFC & ($in))) {
                    // First octet of 5 octet sequence.
                    //
                    // This is illegal because the encoded codepoint must be
                    // either:
                    // (a) not the shortest form or
                    // (b) outside the Unicode range of 0-0x10FFFF.
                    // Rather than trying to resynchronize, we will carry on
                    // until the end of the sequence and let the later error
                    // handling code catch it.
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 0x03) << 24;
                    $mState = 4;
                    $mBytes = 5;
                } elseif (0xFC == (0xFE & ($in))) {
                    // First octet of 6 octet sequence, see comments for 5
                    // octet sequence.
                    $mUcs4 = ($in);
                    $mUcs4 = ($mUcs4 & 1) << 30;
                    $mState = 5;
                    $mBytes = 6;
                } else {
                    // Current octet is neither in the US-ASCII range nor a
                    // legal first octet of a multi-octet sequence.
                    $mState = 0;
                    $mUcs4  = 0;
                    $mBytes = 1;
                    $char = '';
                }
            } else {
                // When mState is non-zero, we expect a continuation of the
                // multi-octet sequence
                if (0x80 == (0xC0 & ($in))) {
                    // Legal continuation.
                    $shift = ($mState - 1) * 6;
                    $tmp = $in;
                    $tmp = ($tmp & 0x0000003F) << $shift;
                    $mUcs4 |= $tmp;

                    if (0 == --$mState) {
                        // End of the multi-octet sequence. mUcs4 now contains
                        // the final Unicode codepoint to be output

                        // Check for illegal sequences and codepoints.

                        // From Unicode 3.1, non-shortest form is illegal
                        if (((2 == $mBytes) && ($mUcs4 < 0x0080)) ||
                            ((3 == $mBytes) && ($mUcs4 < 0x0800)) ||
                            ((4 == $mBytes) && ($mUcs4 < 0x10000)) ||
                            (4 < $mBytes) ||
                            // From Unicode 3.2, surrogate characters = illegal
                            (($mUcs4 & 0xFFFFF800) == 0xD800) ||
                            // Codepoints outside the Unicode range are illegal
                            ($mUcs4 > 0x10FFFF)
                        ) {

                        } elseif (0xFEFF != $mUcs4 && // omit BOM
                            // check for valid Char unicode codepoints
                            (
                                0x9 == $mUcs4 ||
                                0xA == $mUcs4 ||
                                0xD == $mUcs4 ||
                                (0x20 <= $mUcs4 && 0x7E >= $mUcs4) ||
                                // 7F-9F is not strictly prohibited by XML,
                                // but it is non-SGML, and thus we don't allow it
                                (0xA0 <= $mUcs4 && 0xD7FF >= $mUcs4) ||
                                (0x10000 <= $mUcs4 && 0x10FFFF >= $mUcs4)
                            )
                        ) {
                            $out .= $char;
                        }
                        // initialize UTF8 cache (reset)
                        $mState = 0;
                        $mUcs4  = 0;
                        $mBytes = 1;
                        $char = '';
                    }
                } else {
                    // ((0xC0 & (*in) != 0x80) && (mState != 0))
                    // Incomplete multi-octet sequence.
                    // used to result in complete fail, but we'll reset
                    $mState = 0;
                    $mUcs4  = 0;
                    $mBytes = 1;
                    $char ='';
                }
            }
        }
        return $out;
    }

    /**
     * Translates a Unicode codepoint into its corresponding UTF-8 character.
     * @note Based on Feyd's function at
     *       <http://forums.devnetwork.net/viewtopic.php?p=191404#191404>,
     *       which is in public domain.
     * @note While we're going to do code point parsing anyway, a good
     *       optimization would be to refuse to translate code points that
     *       are non-SGML characters.  However, this could lead to duplication.
     * @note This is very similar to the unichr function in
     *       maintenance/generate-entity-file.php (although this is superior,
     *       due to its sanity checks).
     */

    // +----------+----------+----------+----------+
    // | 33222222 | 22221111 | 111111   |          |
    // | 10987654 | 32109876 | 54321098 | 76543210 | bit
    // +----------+----------+----------+----------+
    // |          |          |          | 0xxxxxxx | 1 byte 0x00000000..0x0000007F
    // |          |          | 110yyyyy | 10xxxxxx | 2 byte 0x00000080..0x000007FF
    // |          | 1110zzzz | 10yyyyyy | 10xxxxxx | 3 byte 0x00000800..0x0000FFFF
    // | 11110www | 10wwzzzz | 10yyyyyy | 10xxxxxx | 4 byte 0x00010000..0x0010FFFF
    // +----------+----------+----------+----------+
    // | 00000000 | 00011111 | 11111111 | 11111111 | Theoretical upper limit of legal scalars: 2097151 (0x001FFFFF)
    // | 00000000 | 00010000 | 11111111 | 11111111 | Defined upper limit of legal scalar codes
    // +----------+----------+----------+----------+

    public static function unichr($code) {
        if($code > 1114111 or $code < 0 or
          ($code >= 55296 and $code <= 57343) ) {
            // bits are set outside the "valid" range as defined
            // by UNICODE 4.1.0
            return '';
        }

        $x = $y = $z = $w = 0;
        if ($code < 128) {
            // regular ASCII character
            $x = $code;
        } else {
            // set up bits for UTF-8
            $x = ($code & 63) | 128;
            if ($code < 2048) {
                $y = (($code & 2047) >> 6) | 192;
            } else {
                $y = (($code & 4032) >> 6) | 128;
                if($code < 65536) {
                    $z = (($code >> 12) & 15) | 224;
                } else {
                    $z = (($code >> 12) & 63) | 128;
                    $w = (($code >> 18) & 7)  | 240;
                }
            }
        }
        // set up the actual character
        $ret = '';
        if($w) $ret .= chr($w);
        if($z) $ret .= chr($z);
        if($y) $ret .= chr($y);
        $ret .= chr($x);

        return $ret;
    }

    /**
     * Converts a string to UTF-8 based on configuration.
     */
    public static function convertToUTF8($str, $config, $context) {
        $encoding = $config->get('Core.Encoding');
        if ($encoding === 'utf-8') return $str;
        static $iconv = null;
        if ($iconv === null) $iconv = function_exists('iconv');
        set_error_handler(array('HTMLPurifier_Encoder', 'muteErrorHandler'));
        if ($iconv && !$config->get('Test.ForceNoIconv')) {
            $str = iconv($encoding, 'utf-8//IGNORE', $str);
            if ($str === false) {
                // $encoding is not a valid encoding
                restore_error_handler();
                trigger_error('Invalid encoding ' . $encoding, E_USER_ERROR);
                return '';
            }
            // If the string is bjorked by Shift_JIS or a similar encoding
            // that doesn't support all of ASCII, convert the naughty
            // characters to their true byte-wise ASCII/UTF-8 equivalents.
            $str = strtr($str, HTMLPurifier_Encoder::testEncodingSupportsASCII($encoding));
            restore_error_handler();
            return $str;
        } elseif ($encoding === 'iso-8859-1') {
            $str = utf8_encode($str);
            restore_error_handler();
            return $str;
        }
        trigger_error('Encoding not supported, please install iconv', E_USER_ERROR);
    }

    /**
     * Converts a string from UTF-8 based on configuration.
     * @note Currently, this is a lossy conversion, with unexpressable
     *       characters being omitted.
     */
    public static function convertFromUTF8($str, $config, $context) {
        $encoding = $config->get('Core.Encoding');
        if ($encoding === 'utf-8') return $str;
        static $iconv = null;
        if ($iconv === null) $iconv = function_exists('iconv');
        if ($escape = $config->get('Core.EscapeNonASCIICharacters')) {
            $str = HTMLPurifier_Encoder::convertToASCIIDumbLossless($str);
        }
        set_error_handler(array('HTMLPurifier_Encoder', 'muteErrorHandler'));
        if ($iconv && !$config->get('Test.ForceNoIconv')) {
            // Undo our previous fix in convertToUTF8, otherwise iconv will barf
            $ascii_fix = HTMLPurifier_Encoder::testEncodingSupportsASCII($encoding);
            if (!$escape && !empty($ascii_fix)) {
                $clear_fix = array();
                foreach ($ascii_fix as $utf8 => $native) $clear_fix[$utf8] = '';
                $str = strtr($str, $clear_fix);
            }
            $str = strtr($str, array_flip($ascii_fix));
            // Normal stuff
            $str = iconv('utf-8', $encoding . '//IGNORE', $str);
            restore_error_handler();
            return $str;
        } elseif ($encoding === 'iso-8859-1') {
            $str = utf8_decode($str);
            restore_error_handler();
            return $str;
        }
        trigger_error('Encoding not supported', E_USER_ERROR);
    }

    /**
     * Lossless (character-wise) conversion of HTML to ASCII
     * @param $str UTF-8 string to be converted to ASCII
     * @returns ASCII encoded string with non-ASCII character entity-ized
     * @warning Adapted from MediaWiki, claiming fair use: this is a common
     *       algorithm. If you disagree with this license fudgery,
     *       implement it yourself.
     * @note Uses decimal numeric entities since they are best supported.
     * @note This is a DUMB function: it has no concept of keeping
     *       character entities that the projected character encoding
     *       can allow. We could possibly implement a smart version
     *       but that would require it to also know which Unicode
     *       codepoints the charset supported (not an easy task).
     * @note Sort of with cleanUTF8() but it assumes that $str is
     *       well-formed UTF-8
     */
    public static function convertToASCIIDumbLossless($str) {
        $bytesleft = 0;
        $result = '';
        $working = 0;
        $len = strlen($str);
        for( $i = 0; $i < $len; $i++ ) {
            $bytevalue = ord( $str[$i] );
            if( $bytevalue <= 0x7F ) { //0xxx xxxx
                $result .= chr( $bytevalue );
                $bytesleft = 0;
            } elseif( $bytevalue <= 0xBF ) { //10xx xxxx
                $working = $working << 6;
                $working += ($bytevalue & 0x3F);
                $bytesleft--;
                if( $bytesleft <= 0 ) {
                    $result .= "&#" . $working . ";";
                }
            } elseif( $bytevalue <= 0xDF ) { //110x xxxx
                $working = $bytevalue & 0x1F;
                $bytesleft = 1;
            } elseif( $bytevalue <= 0xEF ) { //1110 xxxx
                $working = $bytevalue & 0x0F;
                $bytesleft = 2;
            } else { //1111 0xxx
                $working = $bytevalue & 0x07;
                $bytesleft = 3;
            }
        }
        return $result;
    }

    /**
     * This expensive function tests whether or not a given character
     * encoding supports ASCII. 7/8-bit encodings like Shift_JIS will
     * fail this test, and require special processing. Variable width
     * encodings shouldn't ever fail.
     *
     * @param string $encoding Encoding name to test, as per iconv format
     * @param bool $bypass Whether or not to bypass the precompiled arrays.
     * @return Array of UTF-8 characters to their corresponding ASCII,
     *      which can be used to "undo" any overzealous iconv action.
     */
    public static function testEncodingSupportsASCII($encoding, $bypass = false) {
        static $encodings = array();
        if (!$bypass) {
            if (isset($encodings[$encoding])) return $encodings[$encoding];
            $lenc = strtolower($encoding);
            switch ($lenc) {
                case 'shift_jis':
                    return array("\xC2\xA5" => '\\', "\xE2\x80\xBE" => '~');
                case 'johab':
                    return array("\xE2\x82\xA9" => '\\');
            }
            if (strpos($lenc, 'iso-8859-') === 0) return array();
        }
        $ret = array();
        set_error_handler(array('HTMLPurifier_Encoder', 'muteErrorHandler'));
        if (iconv('UTF-8', $encoding, 'a') === false) return false;
        for ($i = 0x20; $i <= 0x7E; $i++) { // all printable ASCII chars
            $c = chr($i); // UTF-8 char
            $r = iconv('UTF-8', "$encoding//IGNORE", $c); // initial conversion
            if (
                $r === '' ||
                // This line is needed for iconv implementations that do not
                // omit characters that do not exist in the target character set
                ($r === $c && iconv($encoding, 'UTF-8//IGNORE', $r) !== $c)
            ) {
                // Reverse engineer: what's the UTF-8 equiv of this byte
                // sequence? This assumes that there's no variable width
                // encoding that doesn't support ASCII.
                $ret[iconv($encoding, 'UTF-8//IGNORE', $c)] = $c;
            }
        }
        restore_error_handler();
        $encodings[$encoding] = $ret;
        return $ret;
    }


}





/**
 * Object that provides entity lookup table from entity name to character
 */
class HTMLPurifier_EntityLookup {

    /**
     * Assoc array of entity name to character represented.
     */
    public $table;

    /**
     * Sets up the entity lookup table from the serialized file contents.
     * @note The serialized contents are versioned, but were generated
     *       using the maintenance script generate_entity_file.php
     * @warning This is not in constructor to help enforce the Singleton
     */
    public function setup($file = false) {
        if (!$file) {
            $file = HTMLPURIFIER_PREFIX . '/HTMLPurifier/EntityLookup/entities.ser';
        }
        $this->table = unserialize(file_get_contents($file));
    }

    /**
     * Retrieves sole instance of the object.
     * @param Optional prototype of custom lookup table to overload with.
     */
    public static function instance($prototype = false) {
        // no references, since PHP doesn't copy unless modified
        static $instance = null;
        if ($prototype) {
            $instance = $prototype;
        } elseif (!$instance) {
            $instance = new HTMLPurifier_EntityLookup();
            $instance->setup();
        }
        return $instance;
    }

}





// if want to implement error collecting here, we'll need to use some sort
// of global data (probably trigger_error) because it's impossible to pass
// $config or $context to the callback functions.

/**
 * Handles referencing and derefencing character entities
 */
class HTMLPurifier_EntityParser
{

    /**
     * Reference to entity lookup table.
     */
    protected $_entity_lookup;

    /**
     * Callback regex string for parsing entities.
     */
    protected $_substituteEntitiesRegex =
'/&(?:[#]x([a-fA-F0-9]+)|[#]0*(\d+)|([A-Za-z_:][A-Za-z0-9.\-_:]*));?/';
//     1. hex             2. dec      3. string (XML style)


    /**
     * Decimal to parsed string conversion table for special entities.
     */
    protected $_special_dec2str =
            array(
                    34 => '"',
                    38 => '&',
                    39 => "'",
                    60 => '<',
                    62 => '>'
            );

    /**
     * Stripped entity names to decimal conversion table for special entities.
     */
    protected $_special_ent2dec =
            array(
                    'quot' => 34,
                    'amp'  => 38,
                    'lt'   => 60,
                    'gt'   => 62
            );

    /**
     * Substitutes non-special entities with their parsed equivalents. Since
     * running this whenever you have parsed character is t3h 5uck, we run
     * it before everything else.
     *
     * @param $string String to have non-special entities parsed.
     * @returns Parsed string.
     */
    public function substituteNonSpecialEntities($string) {
        // it will try to detect missing semicolons, but don't rely on it
        return preg_replace_callback(
            $this->_substituteEntitiesRegex,
            array($this, 'nonSpecialEntityCallback'),
            $string
            );
    }

    /**
     * Callback function for substituteNonSpecialEntities() that does the work.
     *
     * @param $matches  PCRE matches array, with 0 the entire match, and
     *                  either index 1, 2 or 3 set with a hex value, dec value,
     *                  or string (respectively).
     * @returns Replacement string.
     */

    protected function nonSpecialEntityCallback($matches) {
        // replaces all but big five
        $entity = $matches[0];
        $is_num = (@$matches[0][1] === '#');
        if ($is_num) {
            $is_hex = (@$entity[2] === 'x');
            $code = $is_hex ? hexdec($matches[1]) : (int) $matches[2];

            // abort for special characters
            if (isset($this->_special_dec2str[$code]))  return $entity;

            return HTMLPurifier_Encoder::unichr($code);
        } else {
            if (isset($this->_special_ent2dec[$matches[3]])) return $entity;
            if (!$this->_entity_lookup) {
                $this->_entity_lookup = HTMLPurifier_EntityLookup::instance();
            }
            if (isset($this->_entity_lookup->table[$matches[3]])) {
                return $this->_entity_lookup->table[$matches[3]];
            } else {
                return $entity;
            }
        }
    }

    /**
     * Substitutes only special entities with their parsed equivalents.
     *
     * @notice We try to avoid calling this function because otherwise, it
     * would have to be called a lot (for every parsed section).
     *
     * @param $string String to have non-special entities parsed.
     * @returns Parsed string.
     */
    public function substituteSpecialEntities($string) {
        return preg_replace_callback(
            $this->_substituteEntitiesRegex,
            array($this, 'specialEntityCallback'),
            $string);
    }

    /**
     * Callback function for substituteSpecialEntities() that does the work.
     *
     * This callback has same syntax as nonSpecialEntityCallback().
     *
     * @param $matches  PCRE-style matches array, with 0 the entire match, and
     *                  either index 1, 2 or 3 set with a hex value, dec value,
     *                  or string (respectively).
     * @returns Replacement string.
     */
    protected function specialEntityCallback($matches) {
        $entity = $matches[0];
        $is_num = (@$matches[0][1] === '#');
        if ($is_num) {
            $is_hex = (@$entity[2] === 'x');
            $int = $is_hex ? hexdec($matches[1]) : (int) $matches[2];
            return isset($this->_special_dec2str[$int]) ?
                $this->_special_dec2str[$int] :
                $entity;
        } else {
            return isset($this->_special_ent2dec[$matches[3]]) ?
                $this->_special_ent2dec[$matches[3]] :
                $entity;
        }
    }

}





/**
 * Error collection class that enables HTML Purifier to report HTML
 * problems back to the user
 */
class HTMLPurifier_ErrorCollector
{

    /**
     * Identifiers for the returned error array. These are purposely numeric
     * so list() can be used.
     */
    const LINENO   = 0;
    const SEVERITY = 1;
    const MESSAGE  = 2;
    const CHILDREN = 3;

    protected $errors;
    protected $_current;
    protected $_stacks = array(array());
    protected $locale;
    protected $generator;
    protected $context;

    protected $lines = array();

    public function __construct($context) {
        $this->locale    =& $context->get('Locale');
        $this->context   = $context;
        $this->_current  =& $this->_stacks[0];
        $this->errors    =& $this->_stacks[0];
    }

    /**
     * Sends an error message to the collector for later use
     * @param $severity int Error severity, PHP error style (don't use E_USER_)
     * @param $msg string Error message text
     * @param $subst1 string First substitution for $msg
     * @param $subst2 string ...
     */
    public function send($severity, $msg) {

        $args = array();
        if (func_num_args() > 2) {
            $args = func_get_args();
            array_shift($args);
            unset($args[0]);
        }

        $token = $this->context->get('CurrentToken', true);
        $line  = $token ? $token->line : $this->context->get('CurrentLine', true);
        $col   = $token ? $token->col  : $this->context->get('CurrentCol',  true);
        $attr  = $this->context->get('CurrentAttr', true);

        // perform special substitutions, also add custom parameters
        $subst = array();
        if (!is_null($token)) {
            $args['CurrentToken'] = $token;
        }
        if (!is_null($attr)) {
            $subst['$CurrentAttr.Name'] = $attr;
            if (isset($token->attr[$attr])) $subst['$CurrentAttr.Value'] = $token->attr[$attr];
        }

        if (empty($args)) {
            $msg = $this->locale->getMessage($msg);
        } else {
            $msg = $this->locale->formatMessage($msg, $args);
        }

        if (!empty($subst)) $msg = strtr($msg, $subst);

        // (numerically indexed)
        $error = array(
            self::LINENO   => $line,
            self::SEVERITY => $severity,
            self::MESSAGE  => $msg,
            self::CHILDREN => array()
        );
        $this->_current[] = $error;


        // NEW CODE BELOW ...

        $struct = null;
        // Top-level errors are either:
        //  TOKEN type, if $value is set appropriately, or
        //  "syntax" type, if $value is null
        $new_struct = new HTMLPurifier_ErrorStruct();
        $new_struct->type = HTMLPurifier_ErrorStruct::TOKEN;
        if ($token) $new_struct->value = clone $token;
        if (is_int($line) && is_int($col)) {
            if (isset($this->lines[$line][$col])) {
                $struct = $this->lines[$line][$col];
            } else {
                $struct = $this->lines[$line][$col] = $new_struct;
            }
            // These ksorts may present a performance problem
            ksort($this->lines[$line], SORT_NUMERIC);
        } else {
            if (isset($this->lines[-1])) {
                $struct = $this->lines[-1];
            } else {
                $struct = $this->lines[-1] = $new_struct;
            }
        }
        ksort($this->lines, SORT_NUMERIC);

        // Now, check if we need to operate on a lower structure
        if (!empty($attr)) {
            $struct = $struct->getChild(HTMLPurifier_ErrorStruct::ATTR, $attr);
            if (!$struct->value) {
                $struct->value = array($attr, 'PUT VALUE HERE');
            }
        }
        if (!empty($cssprop)) {
            $struct = $struct->getChild(HTMLPurifier_ErrorStruct::CSSPROP, $cssprop);
            if (!$struct->value) {
                // if we tokenize CSS this might be a little more difficult to do
                $struct->value = array($cssprop, 'PUT VALUE HERE');
            }
        }

        // Ok, structs are all setup, now time to register the error
        $struct->addError($severity, $msg);
    }

    /**
     * Retrieves raw error data for custom formatter to use
     * @param List of arrays in format of array(line of error,
     *        error severity, error message,
     *        recursive sub-errors array)
     */
    public function getRaw() {
        return $this->errors;
    }

    /**
     * Default HTML formatting implementation for error messages
     * @param $config Configuration array, vital for HTML output nature
     * @param $errors Errors array to display; used for recursion.
     */
    public function getHTMLFormatted($config, $errors = null) {
        $ret = array();

        $this->generator = new HTMLPurifier_Generator($config, $this->context);
        if ($errors === null) $errors = $this->errors;

        // 'At line' message needs to be removed

        // generation code for new structure goes here. It needs to be recursive.
        foreach ($this->lines as $line => $col_array) {
            if ($line == -1) continue;
            foreach ($col_array as $col => $struct) {
                $this->_renderStruct($ret, $struct, $line, $col);
            }
        }
        if (isset($this->lines[-1])) {
            $this->_renderStruct($ret, $this->lines[-1]);
        }

        if (empty($errors)) {
            return '<p>' . $this->locale->getMessage('ErrorCollector: No errors') . '</p>';
        } else {
            return '<ul><li>' . implode('</li><li>', $ret) . '</li></ul>';
        }

    }

    private function _renderStruct(&$ret, $struct, $line = null, $col = null) {
        $stack = array($struct);
        $context_stack = array(array());
        while ($current = array_pop($stack)) {
            $context = array_pop($context_stack);
            foreach ($current->errors as $error) {
                list($severity, $msg) = $error;
                $string = '';
                $string .= '<div>';
                // W3C uses an icon to indicate the severity of the error.
                $error = $this->locale->getErrorName($severity);
                $string .= "<span class=\"error e$severity\"><strong>$error</strong></span> ";
                if (!is_null($line) && !is_null($col)) {
                    $string .= "<em class=\"location\">Line $line, Column $col: </em> ";
                } else {
                    $string .= '<em class="location">End of Document: </em> ';
                }
                $string .= '<strong class="description">' . $this->generator->escape($msg) . '</strong> ';
                $string .= '</div>';
                // Here, have a marker for the character on the column appropriate.
                // Be sure to clip extremely long lines.
                //$string .= '<pre>';
                //$string .= '';
                //$string .= '</pre>';
                $ret[] = $string;
            }
            foreach ($current->children as $type => $array) {
                $context[] = $current;
                $stack = array_merge($stack, array_reverse($array, true));
                for ($i = count($array); $i > 0; $i--) {
                    $context_stack[] = $context;
                }
            }
        }
    }

}





/**
 * Records errors for particular segments of an HTML document such as tokens,
 * attributes or CSS properties. They can contain error structs (which apply
 * to components of what they represent), but their main purpose is to hold
 * errors applying to whatever struct is being used.
 */
class HTMLPurifier_ErrorStruct
{

    /**
     * Possible values for $children first-key. Note that top-level structures
     * are automatically token-level.
     */
    const TOKEN     = 0;
    const ATTR      = 1;
    const CSSPROP   = 2;

    /**
     * Type of this struct.
     */
    public $type;

    /**
     * Value of the struct we are recording errors for. There are various
     * values for this:
     *  - TOKEN: Instance of HTMLPurifier_Token
     *  - ATTR: array('attr-name', 'value')
     *  - CSSPROP: array('prop-name', 'value')
     */
    public $value;

    /**
     * Errors registered for this structure.
     */
    public $errors = array();

    /**
     * Child ErrorStructs that are from this structure. For example, a TOKEN
     * ErrorStruct would contain ATTR ErrorStructs. This is a multi-dimensional
     * array in structure: [TYPE]['identifier']
     */
    public $children = array();

    public function getChild($type, $id) {
        if (!isset($this->children[$type][$id])) {
            $this->children[$type][$id] = new HTMLPurifier_ErrorStruct();
            $this->children[$type][$id]->type = $type;
        }
        return $this->children[$type][$id];
    }

    public function addError($severity, $message) {
        $this->errors[] = array($severity, $message);
    }

}





/**
 * Global exception class for HTML Purifier; any exceptions we throw
 * are from here.
 */
class HTMLPurifier_Exception extends Exception
{

}





/**
 * Represents a pre or post processing filter on HTML Purifier's output
 *
 * Sometimes, a little ad-hoc fixing of HTML has to be done before
 * it gets sent through HTML Purifier: you can use filters to acheive
 * this effect. For instance, YouTube videos can be preserved using
 * this manner. You could have used a decorator for this task, but
 * PHP's support for them is not terribly robust, so we're going
 * to just loop through the filters.
 *
 * Filters should be exited first in, last out. If there are three filters,
 * named 1, 2 and 3, the order of execution should go 1->preFilter,
 * 2->preFilter, 3->preFilter, purify, 3->postFilter, 2->postFilter,
 * 1->postFilter.
 *
 * @note Methods are not declared abstract as it is perfectly legitimate
 *       for an implementation not to want anything to happen on a step
 */

class HTMLPurifier_Filter
{

    /**
     * Name of the filter for identification purposes
     */
    public $name;

    /**
     * Pre-processor function, handles HTML before HTML Purifier
     */
    public function preFilter($html, $config, $context) {
        return $html;
    }

    /**
     * Post-processor function, handles HTML after HTML Purifier
     */
    public function postFilter($html, $config, $context) {
        return $html;
    }

}





/**
 * Generates HTML from tokens.
 * @todo Refactor interface so that configuration/context is determined
 *       upon instantiation, no need for messy generateFromTokens() calls
 * @todo Make some of the more internal functions protected, and have
 *       unit tests work around that
 */
class HTMLPurifier_Generator
{

    /**
     * Whether or not generator should produce XML output
     */
    private $_xhtml = true;

    /**
     * :HACK: Whether or not generator should comment the insides of <script> tags
     */
    private $_scriptFix = false;

    /**
     * Cache of HTMLDefinition during HTML output to determine whether or
     * not attributes should be minimized.
     */
    private $_def;

    /**
     * Cache of %Output.SortAttr
     */
    private $_sortAttr;

    /**
     * Cache of %Output.FlashCompat
     */
    private $_flashCompat;

    /**
     * Cache of %Output.FixInnerHTML
     */
    private $_innerHTMLFix;

    /**
     * Stack for keeping track of object information when outputting IE
     * compatibility code.
     */
    private $_flashStack = array();

    /**
     * Configuration for the generator
     */
    protected $config;


    /**
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     */
    public function __construct($config, $context) {
        $this->config = $config;
        $this->_scriptFix = $config->get('Output.CommentScriptContents');
        $this->_innerHTMLFix = $config->get('Output.FixInnerHTML');
        $this->_sortAttr = $config->get('Output.SortAttr');
        $this->_flashCompat = $config->get('Output.FlashCompat');
        $this->_def = $config->getHTMLDefinition();
        $this->_xhtml = $this->_def->doctype->xml;
    }

    /**
     * Generates HTML from an array of tokens.
     * @param $tokens Array of HTMLPurifier_Token
     * @param $config HTMLPurifier_Config object
     * @return Generated HTML
     */
    public function generateFromTokens($tokens) {
        if (!$tokens) return '';

        // Basic algorithm
        $html = '';
        for ($i = 0, $size = count($tokens); $i < $size; $i++) {
            if ($this->_scriptFix && $tokens[$i]->name === 'script'
                && $i + 2 < $size && $tokens[$i+2] instanceof HTMLPurifier_Token_End) {
                // script special case
                // the contents of the script block must be ONE token
                // for this to work.
                $html .= $this->generateFromToken($tokens[$i++]);
                $html .= $this->generateScriptFromToken($tokens[$i++]);
            }
            $html .= $this->generateFromToken($tokens[$i]);
        }

        // Tidy cleanup
        if (extension_loaded('tidy') && $this->config->get('Output.TidyFormat')) {
            $tidy = new Tidy;
            $tidy->parseString($html, array(
               'indent'=> true,
               'output-xhtml' => $this->_xhtml,
               'show-body-only' => true,
               'indent-spaces' => 2,
               'wrap' => 68,
            ), 'utf8');
            $tidy->cleanRepair();
            $html = (string) $tidy; // explicit cast necessary
        }

        // Normalize newlines to system defined value
        if ($this->config->get('Core.NormalizeNewlines')) {
            $nl = $this->config->get('Output.Newline');
            if ($nl === null) $nl = PHP_EOL;
            if ($nl !== "\n") $html = str_replace("\n", $nl, $html);
        }
        return $html;
    }

    /**
     * Generates HTML from a single token.
     * @param $token HTMLPurifier_Token object.
     * @return Generated HTML
     */
    public function generateFromToken($token) {
        if (!$token instanceof HTMLPurifier_Token) {
            trigger_error('Cannot generate HTML from non-HTMLPurifier_Token object', E_USER_WARNING);
            return '';

        } elseif ($token instanceof HTMLPurifier_Token_Start) {
            $attr = $this->generateAttributes($token->attr, $token->name);
            if ($this->_flashCompat) {
                if ($token->name == "object") {
                    $flash = new stdclass();
                    $flash->attr = $token->attr;
                    $flash->param = array();
                    $this->_flashStack[] = $flash;
                }
            }
            return '<' . $token->name . ($attr ? ' ' : '') . $attr . '>';

        } elseif ($token instanceof HTMLPurifier_Token_End) {
            $_extra = '';
            if ($this->_flashCompat) {
                if ($token->name == "object" && !empty($this->_flashStack)) {
                    // doesn't do anything for now
                }
            }
            return $_extra . '</' . $token->name . '>';

        } elseif ($token instanceof HTMLPurifier_Token_Empty) {
            if ($this->_flashCompat && $token->name == "param" && !empty($this->_flashStack)) {
                $this->_flashStack[count($this->_flashStack)-1]->param[$token->attr['name']] = $token->attr['value'];
            }
            $attr = $this->generateAttributes($token->attr, $token->name);
             return '<' . $token->name . ($attr ? ' ' : '') . $attr .
                ( $this->_xhtml ? ' /': '' ) // <br /> v. <br>
                . '>';

        } elseif ($token instanceof HTMLPurifier_Token_Text) {
            return $this->escape($token->data, ENT_NOQUOTES);

        } elseif ($token instanceof HTMLPurifier_Token_Comment) {
            return '<!--' . $token->data . '-->';
        } else {
            return '';

        }
    }

    /**
     * Special case processor for the contents of script tags
     * @warning This runs into problems if there's already a literal
     *          --> somewhere inside the script contents.
     */
    public function generateScriptFromToken($token) {
        if (!$token instanceof HTMLPurifier_Token_Text) return $this->generateFromToken($token);
        // Thanks <http://lachy.id.au/log/2005/05/script-comments>
        $data = preg_replace('#//\s*$#', '', $token->data);
        return '<!--//--><![CDATA[//><!--' . "\n" . trim($data) . "\n" . '//--><!]]>';
    }

    /**
     * Generates attribute declarations from attribute array.
     * @note This does not include the leading or trailing space.
     * @param $assoc_array_of_attributes Attribute array
     * @param $element Name of element attributes are for, used to check
     *        attribute minimization.
     * @return Generate HTML fragment for insertion.
     */
    public function generateAttributes($assoc_array_of_attributes, $element = false) {
        $html = '';
        if ($this->_sortAttr) ksort($assoc_array_of_attributes);
        foreach ($assoc_array_of_attributes as $key => $value) {
            if (!$this->_xhtml) {
                // Remove namespaced attributes
                if (strpos($key, ':') !== false) continue;
                // Check if we should minimize the attribute: val="val" -> val
                if ($element && !empty($this->_def->info[$element]->attr[$key]->minimized)) {
                    $html .= $key . ' ';
                    continue;
                }
            }
            // Workaround for Internet Explorer innerHTML bug.
            // Essentially, Internet Explorer, when calculating
            // innerHTML, omits quotes if there are no instances of
            // angled brackets, quotes or spaces.  However, when parsing
            // HTML (for example, when you assign to innerHTML), it
            // treats backticks as quotes.  Thus,
            //      <img alt="``" />
            // becomes
            //      <img alt=`` />
            // becomes
            //      <img alt='' />
            // Fortunately, all we need to do is trigger an appropriate
            // quoting style, which we do by adding an extra space.
            // This also is consistent with the W3C spec, which states
            // that user agents may ignore leading or trailing
            // whitespace (in fact, most don't, at least for attributes
            // like alt, but an extra space at the end is barely
            // noticeable).  Still, we have a configuration knob for
            // this, since this transformation is not necesary if you
            // don't process user input with innerHTML or you don't plan
            // on supporting Internet Explorer.
            if ($this->_innerHTMLFix) {
                if (strpos($value, '`') !== false) {
                    // check if correct quoting style would not already be
                    // triggered
                    if (strcspn($value, '"\' <>') === strlen($value)) {
                        // protect!
                        $value .= ' ';
                    }
                }
            }
            $html .= $key.'="'.$this->escape($value).'" ';
        }
        return rtrim($html);
    }

    /**
     * Escapes raw text data.
     * @todo This really ought to be protected, but until we have a facility
     *       for properly generating HTML here w/o using tokens, it stays
     *       public.
     * @param $string String data to escape for HTML.
     * @param $quote Quoting style, like htmlspecialchars. ENT_NOQUOTES is
     *               permissible for non-attribute output.
     * @return String escaped data.
     */
    public function escape($string, $quote = null) {
        // Workaround for APC bug on Mac Leopard reported by sidepodcast
        // http://htmlpurifier.org/phorum/read.php?3,4823,4846
        if ($quote === null) $quote = ENT_COMPAT;
        return htmlspecialchars($string, $quote, 'UTF-8');
    }

}





/**
 * Definition of the purified HTML that describes allowed children,
 * attributes, and many other things.
 *
 * Conventions:
 *
 * All member variables that are prefixed with info
 * (including the main $info array) are used by HTML Purifier internals
 * and should not be directly edited when customizing the HTMLDefinition.
 * They can usually be set via configuration directives or custom
 * modules.
 *
 * On the other hand, member variables without the info prefix are used
 * internally by the HTMLDefinition and MUST NOT be used by other HTML
 * Purifier internals. Many of them, however, are public, and may be
 * edited by userspace code to tweak the behavior of HTMLDefinition.
 *
 * @note This class is inspected by Printer_HTMLDefinition; please
 *       update that class if things here change.
 *
 * @warning Directives that change this object's structure must be in
 *          the HTML or Attr namespace!
 */
class HTMLPurifier_HTMLDefinition extends HTMLPurifier_Definition
{

    // FULLY-PUBLIC VARIABLES ---------------------------------------------

    /**
     * Associative array of element names to HTMLPurifier_ElementDef
     */
    public $info = array();

    /**
     * Associative array of global attribute name to attribute definition.
     */
    public $info_global_attr = array();

    /**
     * String name of parent element HTML will be going into.
     */
    public $info_parent = 'div';

    /**
     * Definition for parent element, allows parent element to be a
     * tag that's not allowed inside the HTML fragment.
     */
    public $info_parent_def;

    /**
     * String name of element used to wrap inline elements in block context
     * @note This is rarely used except for BLOCKQUOTEs in strict mode
     */
    public $info_block_wrapper = 'p';

    /**
     * Associative array of deprecated tag name to HTMLPurifier_TagTransform
     */
    public $info_tag_transform = array();

    /**
     * Indexed list of HTMLPurifier_AttrTransform to be performed before validation.
     */
    public $info_attr_transform_pre = array();

    /**
     * Indexed list of HTMLPurifier_AttrTransform to be performed after validation.
     */
    public $info_attr_transform_post = array();

    /**
     * Nested lookup array of content set name (Block, Inline) to
     * element name to whether or not it belongs in that content set.
     */
    public $info_content_sets = array();

    /**
     * Indexed list of HTMLPurifier_Injector to be used.
     */
    public $info_injector = array();

    /**
     * Doctype object
     */
    public $doctype;



    // RAW CUSTOMIZATION STUFF --------------------------------------------

    /**
     * Adds a custom attribute to a pre-existing element
     * @note This is strictly convenience, and does not have a corresponding
     *       method in HTMLPurifier_HTMLModule
     * @param $element_name String element name to add attribute to
     * @param $attr_name String name of attribute
     * @param $def Attribute definition, can be string or object, see
     *             HTMLPurifier_AttrTypes for details
     */
    public function addAttribute($element_name, $attr_name, $def) {
        $module = $this->getAnonymousModule();
        if (!isset($module->info[$element_name])) {
            $element = $module->addBlankElement($element_name);
        } else {
            $element = $module->info[$element_name];
        }
        $element->attr[$attr_name] = $def;
    }

    /**
     * Adds a custom element to your HTML definition
     * @note See HTMLPurifier_HTMLModule::addElement for detailed
     *       parameter and return value descriptions.
     */
    public function addElement($element_name, $type, $contents, $attr_collections, $attributes = array()) {
        $module = $this->getAnonymousModule();
        // assume that if the user is calling this, the element
        // is safe. This may not be a good idea
        $element = $module->addElement($element_name, $type, $contents, $attr_collections, $attributes);
        return $element;
    }

    /**
     * Adds a blank element to your HTML definition, for overriding
     * existing behavior
     * @note See HTMLPurifier_HTMLModule::addBlankElement for detailed
     *       parameter and return value descriptions.
     */
    public function addBlankElement($element_name) {
        $module  = $this->getAnonymousModule();
        $element = $module->addBlankElement($element_name);
        return $element;
    }

    /**
     * Retrieves a reference to the anonymous module, so you can
     * bust out advanced features without having to make your own
     * module.
     */
    public function getAnonymousModule() {
        if (!$this->_anonModule) {
            $this->_anonModule = new HTMLPurifier_HTMLModule();
            $this->_anonModule->name = 'Anonymous';
        }
        return $this->_anonModule;
    }

    private $_anonModule;


    // PUBLIC BUT INTERNAL VARIABLES --------------------------------------

    public $type = 'HTML';
    public $manager; /**< Instance of HTMLPurifier_HTMLModuleManager */

    /**
     * Performs low-cost, preliminary initialization.
     */
    public function __construct() {
        $this->manager = new HTMLPurifier_HTMLModuleManager();
    }

    protected function doSetup($config) {
        $this->processModules($config);
        $this->setupConfigStuff($config);
        unset($this->manager);

        // cleanup some of the element definitions
        foreach ($this->info as $k => $v) {
            unset($this->info[$k]->content_model);
            unset($this->info[$k]->content_model_type);
        }
    }

    /**
     * Extract out the information from the manager
     */
    protected function processModules($config) {

        if ($this->_anonModule) {
            // for user specific changes
            // this is late-loaded so we don't have to deal with PHP4
            // reference wonky-ness
            $this->manager->addModule($this->_anonModule);
            unset($this->_anonModule);
        }

        $this->manager->setup($config);
        $this->doctype = $this->manager->doctype;

        foreach ($this->manager->modules as $module) {
            foreach($module->info_tag_transform as $k => $v) {
                if ($v === false) unset($this->info_tag_transform[$k]);
                else $this->info_tag_transform[$k] = $v;
            }
            foreach($module->info_attr_transform_pre as $k => $v) {
                if ($v === false) unset($this->info_attr_transform_pre[$k]);
                else $this->info_attr_transform_pre[$k] = $v;
            }
            foreach($module->info_attr_transform_post as $k => $v) {
                if ($v === false) unset($this->info_attr_transform_post[$k]);
                else $this->info_attr_transform_post[$k] = $v;
            }
            foreach ($module->info_injector as $k => $v) {
                if ($v === false) unset($this->info_injector[$k]);
                else $this->info_injector[$k] = $v;
            }
        }

        $this->info = $this->manager->getElements();
        $this->info_content_sets = $this->manager->contentSets->lookup;

    }

    /**
     * Sets up stuff based on config. We need a better way of doing this.
     */
    protected function setupConfigStuff($config) {

        $block_wrapper = $config->get('HTML.BlockWrapper');
        if (isset($this->info_content_sets['Block'][$block_wrapper])) {
            $this->info_block_wrapper = $block_wrapper;
        } else {
            trigger_error('Cannot use non-block element as block wrapper',
                E_USER_ERROR);
        }

        $parent = $config->get('HTML.Parent');
        $def = $this->manager->getElement($parent, true);
        if ($def) {
            $this->info_parent = $parent;
            $this->info_parent_def = $def;
        } else {
            trigger_error('Cannot use unrecognized element as parent',
                E_USER_ERROR);
            $this->info_parent_def = $this->manager->getElement($this->info_parent, true);
        }

        // support template text
        $support = "(for information on implementing this, see the ".
                   "support forums) ";

        // setup allowed elements -----------------------------------------

        $allowed_elements = $config->get('HTML.AllowedElements');
        $allowed_attributes = $config->get('HTML.AllowedAttributes'); // retrieve early

        if (!is_array($allowed_elements) && !is_array($allowed_attributes)) {
            $allowed = $config->get('HTML.Allowed');
            if (is_string($allowed)) {
                list($allowed_elements, $allowed_attributes) = $this->parseTinyMCEAllowedList($allowed);
            }
        }

        if (is_array($allowed_elements)) {
            foreach ($this->info as $name => $d) {
                if(!isset($allowed_elements[$name])) unset($this->info[$name]);
                unset($allowed_elements[$name]);
            }
            // emit errors
            foreach ($allowed_elements as $element => $d) {
                $element = htmlspecialchars($element); // PHP doesn't escape errors, be careful!
                trigger_error("Element '$element' is not supported $support", E_USER_WARNING);
            }
        }

        // setup allowed attributes ---------------------------------------

        $allowed_attributes_mutable = $allowed_attributes; // by copy!
        if (is_array($allowed_attributes)) {

            // This actually doesn't do anything, since we went away from
            // global attributes. It's possible that userland code uses
            // it, but HTMLModuleManager doesn't!
            foreach ($this->info_global_attr as $attr => $x) {
                $keys = array($attr, "*@$attr", "*.$attr");
                $delete = true;
                foreach ($keys as $key) {
                    if ($delete && isset($allowed_attributes[$key])) {
                        $delete = false;
                    }
                    if (isset($allowed_attributes_mutable[$key])) {
                        unset($allowed_attributes_mutable[$key]);
                    }
                }
                if ($delete) unset($this->info_global_attr[$attr]);
            }

            foreach ($this->info as $tag => $info) {
                foreach ($info->attr as $attr => $x) {
                    $keys = array("$tag@$attr", $attr, "*@$attr", "$tag.$attr", "*.$attr");
                    $delete = true;
                    foreach ($keys as $key) {
                        if ($delete && isset($allowed_attributes[$key])) {
                            $delete = false;
                        }
                        if (isset($allowed_attributes_mutable[$key])) {
                            unset($allowed_attributes_mutable[$key]);
                        }
                    }
                    if ($delete) {
                        if ($this->info[$tag]->attr[$attr]->required) {
                            trigger_error("Required attribute '$attr' in element '$tag' was not allowed, which means '$tag' will not be allowed either", E_USER_WARNING);
                        }
                        unset($this->info[$tag]->attr[$attr]);
                    }
                }
            }
            // emit errors
            foreach ($allowed_attributes_mutable as $elattr => $d) {
                $bits = preg_split('/[.@]/', $elattr, 2);
                $c = count($bits);
                switch ($c) {
                    case 2:
                        if ($bits[0] !== '*') {
                            $element = htmlspecialchars($bits[0]);
                            $attribute = htmlspecialchars($bits[1]);
                            if (!isset($this->info[$element])) {
                                trigger_error("Cannot allow attribute '$attribute' if element '$element' is not allowed/supported $support");
                            } else {
                                trigger_error("Attribute '$attribute' in element '$element' not supported $support",
                                    E_USER_WARNING);
                            }
                            break;
                        }
                        // otherwise fall through
                    case 1:
                        $attribute = htmlspecialchars($bits[0]);
                        trigger_error("Global attribute '$attribute' is not ".
                            "supported in any elements $support",
                            E_USER_WARNING);
                        break;
                }
            }

        }

        // setup forbidden elements ---------------------------------------

        $forbidden_elements   = $config->get('HTML.ForbiddenElements');
        $forbidden_attributes = $config->get('HTML.ForbiddenAttributes');

        foreach ($this->info as $tag => $info) {
            if (isset($forbidden_elements[$tag])) {
                unset($this->info[$tag]);
                continue;
            }
            foreach ($info->attr as $attr => $x) {
                if (
                    isset($forbidden_attributes["$tag@$attr"]) ||
                    isset($forbidden_attributes["*@$attr"]) ||
                    isset($forbidden_attributes[$attr])
                ) {
                    unset($this->info[$tag]->attr[$attr]);
                    continue;
                } // this segment might get removed eventually
                elseif (isset($forbidden_attributes["$tag.$attr"])) {
                    // $tag.$attr are not user supplied, so no worries!
                    trigger_error("Error with $tag.$attr: tag.attr syntax not supported for HTML.ForbiddenAttributes; use tag@attr instead", E_USER_WARNING);
                }
            }
        }
        foreach ($forbidden_attributes as $key => $v) {
            if (strlen($key) < 2) continue;
            if ($key[0] != '*') continue;
            if ($key[1] == '.') {
                trigger_error("Error with $key: *.attr syntax not supported for HTML.ForbiddenAttributes; use attr instead", E_USER_WARNING);
            }
        }

        // setup injectors -----------------------------------------------------
        foreach ($this->info_injector as $i => $injector) {
            if ($injector->checkNeeded($config) !== false) {
                // remove injector that does not have it's required
                // elements/attributes present, and is thus not needed.
                unset($this->info_injector[$i]);
            }
        }
    }

    /**
     * Parses a TinyMCE-flavored Allowed Elements and Attributes list into
     * separate lists for processing. Format is element[attr1|attr2],element2...
     * @warning Although it's largely drawn from TinyMCE's implementation,
     *      it is different, and you'll probably have to modify your lists
     * @param $list String list to parse
     * @param array($allowed_elements, $allowed_attributes)
     * @todo Give this its own class, probably static interface
     */
    public function parseTinyMCEAllowedList($list) {

        $list = str_replace(array(' ', "\t"), '', $list);

        $elements = array();
        $attributes = array();

        $chunks = preg_split('/(,|[\n\r]+)/', $list);
        foreach ($chunks as $chunk) {
            if (empty($chunk)) continue;
            // remove TinyMCE element control characters
            if (!strpos($chunk, '[')) {
                $element = $chunk;
                $attr = false;
            } else {
                list($element, $attr) = explode('[', $chunk);
            }
            if ($element !== '*') $elements[$element] = true;
            if (!$attr) continue;
            $attr = substr($attr, 0, strlen($attr) - 1); // remove trailing ]
            $attr = explode('|', $attr);
            foreach ($attr as $key) {
                $attributes["$element.$key"] = true;
            }
        }

        return array($elements, $attributes);

    }


}





/**
 * Represents an XHTML 1.1 module, with information on elements, tags
 * and attributes.
 * @note Even though this is technically XHTML 1.1, it is also used for
 *       regular HTML parsing. We are using modulization as a convenient
 *       way to represent the internals of HTMLDefinition, and our
 *       implementation is by no means conforming and does not directly
 *       use the normative DTDs or XML schemas.
 * @note The public variables in a module should almost directly
 *       correspond to the variables in HTMLPurifier_HTMLDefinition.
 *       However, the prefix info carries no special meaning in these
 *       objects (include it anyway if that's the correspondence though).
 * @todo Consider making some member functions protected
 */

class HTMLPurifier_HTMLModule
{

    // -- Overloadable ----------------------------------------------------

    /**
     * Short unique string identifier of the module
     */
    public $name;

    /**
     * Informally, a list of elements this module changes. Not used in
     * any significant way.
     */
    public $elements = array();

    /**
     * Associative array of element names to element definitions.
     * Some definitions may be incomplete, to be merged in later
     * with the full definition.
     */
    public $info = array();

    /**
     * Associative array of content set names to content set additions.
     * This is commonly used to, say, add an A element to the Inline
     * content set. This corresponds to an internal variable $content_sets
     * and NOT info_content_sets member variable of HTMLDefinition.
     */
    public $content_sets = array();

    /**
     * Associative array of attribute collection names to attribute
     * collection additions. More rarely used for adding attributes to
     * the global collections. Example is the StyleAttribute module adding
     * the style attribute to the Core. Corresponds to HTMLDefinition's
     * attr_collections->info, since the object's data is only info,
     * with extra behavior associated with it.
     */
    public $attr_collections = array();

    /**
     * Associative array of deprecated tag name to HTMLPurifier_TagTransform
     */
    public $info_tag_transform = array();

    /**
     * List of HTMLPurifier_AttrTransform to be performed before validation.
     */
    public $info_attr_transform_pre = array();

    /**
     * List of HTMLPurifier_AttrTransform to be performed after validation.
     */
    public $info_attr_transform_post = array();

    /**
     * List of HTMLPurifier_Injector to be performed during well-formedness fixing.
     * An injector will only be invoked if all of it's pre-requisites are met;
     * if an injector fails setup, there will be no error; it will simply be
     * silently disabled.
     */
    public $info_injector = array();

    /**
     * Boolean flag that indicates whether or not getChildDef is implemented.
     * For optimization reasons: may save a call to a function. Be sure
     * to set it if you do implement getChildDef(), otherwise it will have
     * no effect!
     */
    public $defines_child_def = false;

    /**
     * Boolean flag whether or not this module is safe. If it is not safe, all
     * of its members are unsafe. Modules are safe by default (this might be
     * slightly dangerous, but it doesn't make much sense to force HTML Purifier,
     * which is based off of safe HTML, to explicitly say, "This is safe," even
     * though there are modules which are "unsafe")
     *
     * @note Previously, safety could be applied at an element level granularity.
     *       We've removed this ability, so in order to add "unsafe" elements
     *       or attributes, a dedicated module with this property set to false
     *       must be used.
     */
    public $safe = true;

    /**
     * Retrieves a proper HTMLPurifier_ChildDef subclass based on
     * content_model and content_model_type member variables of
     * the HTMLPurifier_ElementDef class. There is a similar function
     * in HTMLPurifier_HTMLDefinition.
     * @param $def HTMLPurifier_ElementDef instance
     * @return HTMLPurifier_ChildDef subclass
     */
    public function getChildDef($def) {return false;}

    // -- Convenience -----------------------------------------------------

    /**
     * Convenience function that sets up a new element
     * @param $element Name of element to add
     * @param $type What content set should element be registered to?
     *              Set as false to skip this step.
     * @param $contents Allowed children in form of:
     *              "$content_model_type: $content_model"
     * @param $attr_includes What attribute collections to register to
     *              element?
     * @param $attr What unique attributes does the element define?
     * @note See ElementDef for in-depth descriptions of these parameters.
     * @return Created element definition object, so you
     *         can set advanced parameters
     */
    public function addElement($element, $type, $contents, $attr_includes = array(), $attr = array()) {
        $this->elements[] = $element;
        // parse content_model
        list($content_model_type, $content_model) = $this->parseContents($contents);
        // merge in attribute inclusions
        $this->mergeInAttrIncludes($attr, $attr_includes);
        // add element to content sets
        if ($type) $this->addElementToContentSet($element, $type);
        // create element
        $this->info[$element] = HTMLPurifier_ElementDef::create(
            $content_model, $content_model_type, $attr
        );
        // literal object $contents means direct child manipulation
        if (!is_string($contents)) $this->info[$element]->child = $contents;
        return $this->info[$element];
    }

    /**
     * Convenience function that creates a totally blank, non-standalone
     * element.
     * @param $element Name of element to create
     * @return Created element
     */
    public function addBlankElement($element) {
        if (!isset($this->info[$element])) {
            $this->elements[] = $element;
            $this->info[$element] = new HTMLPurifier_ElementDef();
            $this->info[$element]->standalone = false;
        } else {
            trigger_error("Definition for $element already exists in module, cannot redefine");
        }
        return $this->info[$element];
    }

    /**
     * Convenience function that registers an element to a content set
     * @param Element to register
     * @param Name content set (warning: case sensitive, usually upper-case
     *        first letter)
     */
    public function addElementToContentSet($element, $type) {
        if (!isset($this->content_sets[$type])) $this->content_sets[$type] = '';
        else $this->content_sets[$type] .= ' | ';
        $this->content_sets[$type] .= $element;
    }

    /**
     * Convenience function that transforms single-string contents
     * into separate content model and content model type
     * @param $contents Allowed children in form of:
     *                  "$content_model_type: $content_model"
     * @note If contents is an object, an array of two nulls will be
     *       returned, and the callee needs to take the original $contents
     *       and use it directly.
     */
    public function parseContents($contents) {
        if (!is_string($contents)) return array(null, null); // defer
        switch ($contents) {
            // check for shorthand content model forms
            case 'Empty':
                return array('empty', '');
            case 'Inline':
                return array('optional', 'Inline | #PCDATA');
            case 'Flow':
                return array('optional', 'Flow | #PCDATA');
        }
        list($content_model_type, $content_model) = explode(':', $contents);
        $content_model_type = strtolower(trim($content_model_type));
        $content_model = trim($content_model);
        return array($content_model_type, $content_model);
    }

    /**
     * Convenience function that merges a list of attribute includes into
     * an attribute array.
     * @param $attr Reference to attr array to modify
     * @param $attr_includes Array of includes / string include to merge in
     */
    public function mergeInAttrIncludes(&$attr, $attr_includes) {
        if (!is_array($attr_includes)) {
            if (empty($attr_includes)) $attr_includes = array();
            else $attr_includes = array($attr_includes);
        }
        $attr[0] = $attr_includes;
    }

    /**
     * Convenience function that generates a lookup table with boolean
     * true as value.
     * @param $list List of values to turn into a lookup
     * @note You can also pass an arbitrary number of arguments in
     *       place of the regular argument
     * @return Lookup array equivalent of list
     */
    public function makeLookup($list) {
        if (is_string($list)) $list = func_get_args();
        $ret = array();
        foreach ($list as $value) {
            if (is_null($value)) continue;
            $ret[$value] = true;
        }
        return $ret;
    }

    /**
     * Lazy load construction of the module after determining whether
     * or not it's needed, and also when a finalized configuration object
     * is available.
     * @param $config Instance of HTMLPurifier_Config
     */
    public function setup($config) {}

}





class HTMLPurifier_HTMLModuleManager
{

    /**
     * Instance of HTMLPurifier_DoctypeRegistry
     */
    public $doctypes;

    /**
     * Instance of current doctype
     */
    public $doctype;

    /**
     * Instance of HTMLPurifier_AttrTypes
     */
    public $attrTypes;

    /**
     * Active instances of modules for the specified doctype are
     * indexed, by name, in this array.
     */
    public $modules = array();

    /**
     * Array of recognized HTMLPurifier_Module instances, indexed by
     * module's class name. This array is usually lazy loaded, but a
     * user can overload a module by pre-emptively registering it.
     */
    public $registeredModules = array();

    /**
     * List of extra modules that were added by the user using addModule().
     * These get unconditionally merged into the current doctype, whatever
     * it may be.
     */
    public $userModules = array();

    /**
     * Associative array of element name to list of modules that have
     * definitions for the element; this array is dynamically filled.
     */
    public $elementLookup = array();

    /** List of prefixes we should use for registering small names */
    public $prefixes = array('HTMLPurifier_HTMLModule_');

    public $contentSets;     /**< Instance of HTMLPurifier_ContentSets */
    public $attrCollections; /**< Instance of HTMLPurifier_AttrCollections */

    /** If set to true, unsafe elements and attributes will be allowed */
    public $trusted = false;

    public function __construct() {

        // editable internal objects
        $this->attrTypes = new HTMLPurifier_AttrTypes();
        $this->doctypes  = new HTMLPurifier_DoctypeRegistry();

        // setup basic modules
        $common = array(
            'CommonAttributes', 'Text', 'Hypertext', 'List',
            'Presentation', 'Edit', 'Bdo', 'Tables', 'Image',
            'StyleAttribute',
            // Unsafe:
            'Scripting', 'Object',  'Forms',
            // Sorta legacy, but present in strict:
            'Name',
        );
        $transitional = array('Legacy', 'Target');
        $xml = array('XMLCommonAttributes');
        $non_xml = array('NonXMLCommonAttributes');

        // setup basic doctypes
        $this->doctypes->register(
            'HTML 4.01 Transitional', false,
            array_merge($common, $transitional, $non_xml),
            array('Tidy_Transitional', 'Tidy_Proprietary'),
            array(),
            '-//W3C//DTD HTML 4.01 Transitional//EN',
            'http://www.w3.org/TR/html4/loose.dtd'
        );

        $this->doctypes->register(
            'HTML 4.01 Strict', false,
            array_merge($common, $non_xml),
            array('Tidy_Strict', 'Tidy_Proprietary', 'Tidy_Name'),
            array(),
            '-//W3C//DTD HTML 4.01//EN',
            'http://www.w3.org/TR/html4/strict.dtd'
        );

        $this->doctypes->register(
            'XHTML 1.0 Transitional', true,
            array_merge($common, $transitional, $xml, $non_xml),
            array('Tidy_Transitional', 'Tidy_XHTML', 'Tidy_Proprietary', 'Tidy_Name'),
            array(),
            '-//W3C//DTD XHTML 1.0 Transitional//EN',
            'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'
        );

        $this->doctypes->register(
            'XHTML 1.0 Strict', true,
            array_merge($common, $xml, $non_xml),
            array('Tidy_Strict', 'Tidy_XHTML', 'Tidy_Strict', 'Tidy_Proprietary', 'Tidy_Name'),
            array(),
            '-//W3C//DTD XHTML 1.0 Strict//EN',
            'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'
        );

        $this->doctypes->register(
            'XHTML 1.1', true,
            array_merge($common, $xml, array('Ruby')),
            array('Tidy_Strict', 'Tidy_XHTML', 'Tidy_Proprietary', 'Tidy_Strict', 'Tidy_Name'), // Tidy_XHTML1_1
            array(),
            '-//W3C//DTD XHTML 1.1//EN',
            'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd'
        );

    }

    /**
     * Registers a module to the recognized module list, useful for
     * overloading pre-existing modules.
     * @param $module Mixed: string module name, with or without
     *                HTMLPurifier_HTMLModule prefix, or instance of
     *                subclass of HTMLPurifier_HTMLModule.
     * @param $overload Boolean whether or not to overload previous modules.
     *                  If this is not set, and you do overload a module,
     *                  HTML Purifier will complain with a warning.
     * @note This function will not call autoload, you must instantiate
     *       (and thus invoke) autoload outside the method.
     * @note If a string is passed as a module name, different variants
     *       will be tested in this order:
     *          - Check for HTMLPurifier_HTMLModule_$name
     *          - Check all prefixes with $name in order they were added
     *          - Check for literal object name
     *          - Throw fatal error
     *       If your object name collides with an internal class, specify
     *       your module manually. All modules must have been included
     *       externally: registerModule will not perform inclusions for you!
     */
    public function registerModule($module, $overload = false) {
        if (is_string($module)) {
            // attempt to load the module
            $original_module = $module;
            $ok = false;
            foreach ($this->prefixes as $prefix) {
                $module = $prefix . $original_module;
                if (class_exists($module)) {
                    $ok = true;
                    break;
                }
            }
            if (!$ok) {
                $module = $original_module;
                if (!class_exists($module)) {
                    trigger_error($original_module . ' module does not exist',
                        E_USER_ERROR);
                    return;
                }

            }
            $module = new $module();
        }
        if (empty($module->name)) {
            trigger_error('Module instance of ' . get_class($module) . ' must have name');
            return;
        }
        if (!$overload && isset($this->registeredModules[$module->name])) {
            trigger_error('Overloading ' . $module->name . ' without explicit overload parameter', E_USER_WARNING);
        }
        $this->registeredModules[$module->name] = $module;
    }

    /**
     * Adds a module to the current doctype by first registering it,
     * and then tacking it on to the active doctype
     */
    public function addModule($module) {
        $this->registerModule($module);
        if (is_object($module)) $module = $module->name;
        $this->userModules[] = $module;
    }

    /**
     * Adds a class prefix that registerModule() will use to resolve a
     * string name to a concrete class
     */
    public function addPrefix($prefix) {
        $this->prefixes[] = $prefix;
    }

    /**
     * Performs processing on modules, after being called you may
     * use getElement() and getElements()
     * @param $config Instance of HTMLPurifier_Config
     */
    public function setup($config) {

        $this->trusted = $config->get('HTML.Trusted');

        // generate
        $this->doctype = $this->doctypes->make($config);
        $modules = $this->doctype->modules;

        // take out the default modules that aren't allowed
        $lookup = $config->get('HTML.AllowedModules');
        $special_cases = $config->get('HTML.CoreModules');

        if (is_array($lookup)) {
            foreach ($modules as $k => $m) {
                if (isset($special_cases[$m])) continue;
                if (!isset($lookup[$m])) unset($modules[$k]);
            }
        }

        // custom modules
        if ($config->get('HTML.Proprietary')) {
            $modules[] = 'Proprietary';
        }
        if ($config->get('HTML.SafeObject')) {
            $modules[] = 'SafeObject';
        }
        if ($config->get('HTML.SafeEmbed')) {
            $modules[] = 'SafeEmbed';
        }
        if ($config->get('HTML.Nofollow')) {
            $modules[] = 'Nofollow';
        }

        // merge in custom modules
        $modules = array_merge($modules, $this->userModules);

        foreach ($modules as $module) {
            $this->processModule($module);
            $this->modules[$module]->setup($config);
        }

        foreach ($this->doctype->tidyModules as $module) {
            $this->processModule($module);
            $this->modules[$module]->setup($config);
        }

        // prepare any injectors
        foreach ($this->modules as $module) {
            $n = array();
            foreach ($module->info_injector as $i => $injector) {
                if (!is_object($injector)) {
                    $class = "HTMLPurifier_Injector_$injector";
                    $injector = new $class;
                }
                $n[$injector->name] = $injector;
            }
            $module->info_injector = $n;
        }

        // setup lookup table based on all valid modules
        foreach ($this->modules as $module) {
            foreach ($module->info as $name => $def) {
                if (!isset($this->elementLookup[$name])) {
                    $this->elementLookup[$name] = array();
                }
                $this->elementLookup[$name][] = $module->name;
            }
        }

        // note the different choice
        $this->contentSets = new HTMLPurifier_ContentSets(
            // content set assembly deals with all possible modules,
            // not just ones deemed to be "safe"
            $this->modules
        );
        $this->attrCollections = new HTMLPurifier_AttrCollections(
            $this->attrTypes,
            // there is no way to directly disable a global attribute,
            // but using AllowedAttributes or simply not including
            // the module in your custom doctype should be sufficient
            $this->modules
        );
    }

    /**
     * Takes a module and adds it to the active module collection,
     * registering it if necessary.
     */
    public function processModule($module) {
        if (!isset($this->registeredModules[$module]) || is_object($module)) {
            $this->registerModule($module);
        }
        $this->modules[$module] = $this->registeredModules[$module];
    }

    /**
     * Retrieves merged element definitions.
     * @return Array of HTMLPurifier_ElementDef
     */
    public function getElements() {

        $elements = array();
        foreach ($this->modules as $module) {
            if (!$this->trusted && !$module->safe) continue;
            foreach ($module->info as $name => $v) {
                if (isset($elements[$name])) continue;
                $elements[$name] = $this->getElement($name);
            }
        }

        // remove dud elements, this happens when an element that
        // appeared to be safe actually wasn't
        foreach ($elements as $n => $v) {
            if ($v === false) unset($elements[$n]);
        }

        return $elements;

    }

    /**
     * Retrieves a single merged element definition
     * @param $name Name of element
     * @param $trusted Boolean trusted overriding parameter: set to true
     *                 if you want the full version of an element
     * @return Merged HTMLPurifier_ElementDef
     * @note You may notice that modules are getting iterated over twice (once
     *       in getElements() and once here). This
     *       is because
     */
    public function getElement($name, $trusted = null) {

        if (!isset($this->elementLookup[$name])) {
            return false;
        }

        // setup global state variables
        $def = false;
        if ($trusted === null) $trusted = $this->trusted;

        // iterate through each module that has registered itself to this
        // element
        foreach($this->elementLookup[$name] as $module_name) {

            $module = $this->modules[$module_name];

            // refuse to create/merge from a module that is deemed unsafe--
            // pretend the module doesn't exist--when trusted mode is not on.
            if (!$trusted && !$module->safe) {
                continue;
            }

            // clone is used because, ideally speaking, the original
            // definition should not be modified. Usually, this will
            // make no difference, but for consistency's sake
            $new_def = clone $module->info[$name];

            if (!$def && $new_def->standalone) {
                $def = $new_def;
            } elseif ($def) {
                // This will occur even if $new_def is standalone. In practice,
                // this will usually result in a full replacement.
                $def->mergeIn($new_def);
            } else {
                // :TODO:
                // non-standalone definitions that don't have a standalone
                // to merge into could be deferred to the end
                continue;
            }

            // attribute value expansions
            $this->attrCollections->performInclusions($def->attr);
            $this->attrCollections->expandIdentifiers($def->attr, $this->attrTypes);

            // descendants_are_inline, for ChildDef_Chameleon
            if (is_string($def->content_model) &&
                strpos($def->content_model, 'Inline') !== false) {
                if ($name != 'del' && $name != 'ins') {
                    // this is for you, ins/del
                    $def->descendants_are_inline = true;
                }
            }

            $this->contentSets->generateChildDef($def, $module);
        }

        // This can occur if there is a blank definition, but no base to
        // mix it in with
        if (!$def) return false;

        // add information on required attributes
        foreach ($def->attr as $attr_name => $attr_def) {
            if ($attr_def->required) {
                $def->required_attr[] = $attr_name;
            }
        }

        return $def;

    }

}





/**
 * Component of HTMLPurifier_AttrContext that accumulates IDs to prevent dupes
 * @note In Slashdot-speak, dupe means duplicate.
 * @note The default constructor does not accept $config or $context objects:
 *       use must use the static build() factory method to perform initialization.
 */
class HTMLPurifier_IDAccumulator
{

    /**
     * Lookup table of IDs we've accumulated.
     * @public
     */
    public $ids = array();

    /**
     * Builds an IDAccumulator, also initializing the default blacklist
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @return Fully initialized HTMLPurifier_IDAccumulator
     */
    public static function build($config, $context) {
        $id_accumulator = new HTMLPurifier_IDAccumulator();
        $id_accumulator->load($config->get('Attr.IDBlacklist'));
        return $id_accumulator;
    }

    /**
     * Add an ID to the lookup table.
     * @param $id ID to be added.
     * @return Bool status, true if success, false if there's a dupe
     */
    public function add($id) {
        if (isset($this->ids[$id])) return false;
        return $this->ids[$id] = true;
    }

    /**
     * Load a list of IDs into the lookup table
     * @param $array_of_ids Array of IDs to load
     * @note This function doesn't care about duplicates
     */
    public function load($array_of_ids) {
        foreach ($array_of_ids as $id) {
            $this->ids[$id] = true;
        }
    }

}





/**
 * Injects tokens into the document while parsing for well-formedness.
 * This enables "formatter-like" functionality such as auto-paragraphing,
 * smiley-ification and linkification to take place.
 *
 * A note on how handlers create changes; this is done by assigning a new
 * value to the $token reference. These values can take a variety of forms and
 * are best described HTMLPurifier_Strategy_MakeWellFormed->processToken()
 * documentation.
 *
 * @todo Allow injectors to request a re-run on their output. This
 *       would help if an operation is recursive.
 */
abstract class HTMLPurifier_Injector
{

    /**
     * Advisory name of injector, this is for friendly error messages
     */
    public $name;

    /**
     * Instance of HTMLPurifier_HTMLDefinition
     */
    protected $htmlDefinition;

    /**
     * Reference to CurrentNesting variable in Context. This is an array
     * list of tokens that we are currently "inside"
     */
    protected $currentNesting;

    /**
     * Reference to InputTokens variable in Context. This is an array
     * list of the input tokens that are being processed.
     */
    protected $inputTokens;

    /**
     * Reference to InputIndex variable in Context. This is an integer
     * array index for $this->inputTokens that indicates what token
     * is currently being processed.
     */
    protected $inputIndex;

    /**
     * Array of elements and attributes this injector creates and therefore
     * need to be allowed by the definition. Takes form of
     * array('element' => array('attr', 'attr2'), 'element2')
     */
    public $needed = array();

    /**
     * Index of inputTokens to rewind to.
     */
    protected $rewind = false;

    /**
     * Rewind to a spot to re-perform processing. This is useful if you
     * deleted a node, and now need to see if this change affected any
     * earlier nodes. Rewinding does not affect other injectors, and can
     * result in infinite loops if not used carefully.
     * @warning HTML Purifier will prevent you from fast-forwarding with this
     *          function.
     */
    public function rewind($index) {
        $this->rewind = $index;
    }

    /**
     * Retrieves rewind, and then unsets it.
     */
    public function getRewind() {
        $r = $this->rewind;
        $this->rewind = false;
        return $r;
    }

    /**
     * Prepares the injector by giving it the config and context objects:
     * this allows references to important variables to be made within
     * the injector. This function also checks if the HTML environment
     * will work with the Injector (see checkNeeded()).
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @return Boolean false if success, string of missing needed element/attribute if failure
     */
    public function prepare($config, $context) {
        $this->htmlDefinition = $config->getHTMLDefinition();
        // Even though this might fail, some unit tests ignore this and
        // still test checkNeeded, so be careful. Maybe get rid of that
        // dependency.
        $result = $this->checkNeeded($config);
        if ($result !== false) return $result;
        $this->currentNesting =& $context->get('CurrentNesting');
        $this->inputTokens    =& $context->get('InputTokens');
        $this->inputIndex     =& $context->get('InputIndex');
        return false;
    }

    /**
     * This function checks if the HTML environment
     * will work with the Injector: if p tags are not allowed, the
     * Auto-Paragraphing injector should not be enabled.
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @return Boolean false if success, string of missing needed element/attribute if failure
     */
    public function checkNeeded($config) {
        $def = $config->getHTMLDefinition();
        foreach ($this->needed as $element => $attributes) {
            if (is_int($element)) $element = $attributes;
            if (!isset($def->info[$element])) return $element;
            if (!is_array($attributes)) continue;
            foreach ($attributes as $name) {
                if (!isset($def->info[$element]->attr[$name])) return "$element.$name";
            }
        }
        return false;
    }

    /**
     * Tests if the context node allows a certain element
     * @param $name Name of element to test for
     * @return True if element is allowed, false if it is not
     */
    public function allowsElement($name) {
        if (!empty($this->currentNesting)) {
            $parent_token = array_pop($this->currentNesting);
            $this->currentNesting[] = $parent_token;
            $parent = $this->htmlDefinition->info[$parent_token->name];
        } else {
            $parent = $this->htmlDefinition->info_parent_def;
        }
        if (!isset($parent->child->elements[$name]) || isset($parent->excludes[$name])) {
            return false;
        }
        // check for exclusion
        for ($i = count($this->currentNesting) - 2; $i >= 0; $i--) {
            $node = $this->currentNesting[$i];
            $def  = $this->htmlDefinition->info[$node->name];
            if (isset($def->excludes[$name])) return false;
        }
        return true;
    }

    /**
     * Iterator function, which starts with the next token and continues until
     * you reach the end of the input tokens.
     * @warning Please prevent previous references from interfering with this
     *          functions by setting $i = null beforehand!
     * @param &$i Current integer index variable for inputTokens
     * @param &$current Current token variable. Do NOT use $token, as that variable is also a reference
     */
    protected function forward(&$i, &$current) {
        if ($i === null) $i = $this->inputIndex + 1;
        else $i++;
        if (!isset($this->inputTokens[$i])) return false;
        $current = $this->inputTokens[$i];
        return true;
    }

    /**
     * Similar to _forward, but accepts a third parameter $nesting (which
     * should be initialized at 0) and stops when we hit the end tag
     * for the node $this->inputIndex starts in.
     */
    protected function forwardUntilEndToken(&$i, &$current, &$nesting) {
        $result = $this->forward($i, $current);
        if (!$result) return false;
        if ($nesting === null) $nesting = 0;
        if     ($current instanceof HTMLPurifier_Token_Start) $nesting++;
        elseif ($current instanceof HTMLPurifier_Token_End) {
            if ($nesting <= 0) return false;
            $nesting--;
        }
        return true;
    }

    /**
     * Iterator function, starts with the previous token and continues until
     * you reach the beginning of input tokens.
     * @warning Please prevent previous references from interfering with this
     *          functions by setting $i = null beforehand!
     * @param &$i Current integer index variable for inputTokens
     * @param &$current Current token variable. Do NOT use $token, as that variable is also a reference
     */
    protected function backward(&$i, &$current) {
        if ($i === null) $i = $this->inputIndex - 1;
        else $i--;
        if ($i < 0) return false;
        $current = $this->inputTokens[$i];
        return true;
    }

    /**
     * Initializes the iterator at the current position. Use in a do {} while;
     * loop to force the _forward and _backward functions to start at the
     * current location.
     * @warning Please prevent previous references from interfering with this
     *          functions by setting $i = null beforehand!
     * @param &$i Current integer index variable for inputTokens
     * @param &$current Current token variable. Do NOT use $token, as that variable is also a reference
     */
    protected function current(&$i, &$current) {
        if ($i === null) $i = $this->inputIndex;
        $current = $this->inputTokens[$i];
    }

    /**
     * Handler that is called when a text token is processed
     */
    public function handleText(&$token) {}

    /**
     * Handler that is called when a start or empty token is processed
     */
    public function handleElement(&$token) {}

    /**
     * Handler that is called when an end token is processed
     */
    public function handleEnd(&$token) {
        $this->notifyEnd($token);
    }

    /**
     * Notifier that is called when an end token is processed
     * @note This differs from handlers in that the token is read-only
     * @deprecated
     */
    public function notifyEnd($token) {}


}





/**
 * Represents a language and defines localizable string formatting and
 * other functions, as well as the localized messages for HTML Purifier.
 */
class HTMLPurifier_Language
{

    /**
     * ISO 639 language code of language. Prefers shortest possible version
     */
    public $code = 'en';

    /**
     * Fallback language code
     */
    public $fallback = false;

    /**
     * Array of localizable messages
     */
    public $messages = array();

    /**
     * Array of localizable error codes
     */
    public $errorNames = array();

    /**
     * True if no message file was found for this language, so English
     * is being used instead. Check this if you'd like to notify the
     * user that they've used a non-supported language.
     */
    public $error = false;

    /**
     * Has the language object been loaded yet?
     * @todo Make it private, fix usage in HTMLPurifier_LanguageTest
     */
    public $_loaded = false;

    /**
     * Instances of HTMLPurifier_Config and HTMLPurifier_Context
     */
    protected $config, $context;

    public function __construct($config, $context) {
        $this->config  = $config;
        $this->context = $context;
    }

    /**
     * Loads language object with necessary info from factory cache
     * @note This is a lazy loader
     */
    public function load() {
        if ($this->_loaded) return;
        $factory = HTMLPurifier_LanguageFactory::instance();
        $factory->loadLanguage($this->code);
        foreach ($factory->keys as $key) {
            $this->$key = $factory->cache[$this->code][$key];
        }
        $this->_loaded = true;
    }

    /**
     * Retrieves a localised message.
     * @param $key string identifier of message
     * @return string localised message
     */
    public function getMessage($key) {
        if (!$this->_loaded) $this->load();
        if (!isset($this->messages[$key])) return "[$key]";
        return $this->messages[$key];
    }

    /**
     * Retrieves a localised error name.
     * @param $int integer error number, corresponding to PHP's error
     *             reporting
     * @return string localised message
     */
    public function getErrorName($int) {
        if (!$this->_loaded) $this->load();
        if (!isset($this->errorNames[$int])) return "[Error: $int]";
        return $this->errorNames[$int];
    }

    /**
     * Converts an array list into a string readable representation
     */
    public function listify($array) {
        $sep      = $this->getMessage('Item separator');
        $sep_last = $this->getMessage('Item separator last');
        $ret = '';
        for ($i = 0, $c = count($array); $i < $c; $i++) {
            if ($i == 0) {
            } elseif ($i + 1 < $c) {
                $ret .= $sep;
            } else {
                $ret .= $sep_last;
            }
            $ret .= $array[$i];
        }
        return $ret;
    }

    /**
     * Formats a localised message with passed parameters
     * @param $key string identifier of message
     * @param $args Parameters to substitute in
     * @return string localised message
     * @todo Implement conditionals? Right now, some messages make
     *     reference to line numbers, but those aren't always available
     */
    public function formatMessage($key, $args = array()) {
        if (!$this->_loaded) $this->load();
        if (!isset($this->messages[$key])) return "[$key]";
        $raw = $this->messages[$key];
        $subst = array();
        $generator = false;
        foreach ($args as $i => $value) {
            if (is_object($value)) {
                if ($value instanceof HTMLPurifier_Token) {
                    // factor this out some time
                    if (!$generator) $generator = $this->context->get('Generator');
                    if (isset($value->name)) $subst['$'.$i.'.Name'] = $value->name;
                    if (isset($value->data)) $subst['$'.$i.'.Data'] = $value->data;
                    $subst['$'.$i.'.Compact'] =
                    $subst['$'.$i.'.Serialized'] = $generator->generateFromToken($value);
                    // a more complex algorithm for compact representation
                    // could be introduced for all types of tokens. This
                    // may need to be factored out into a dedicated class
                    if (!empty($value->attr)) {
                        $stripped_token = clone $value;
                        $stripped_token->attr = array();
                        $subst['$'.$i.'.Compact'] = $generator->generateFromToken($stripped_token);
                    }
                    $subst['$'.$i.'.Line'] = $value->line ? $value->line : 'unknown';
                }
                continue;
            } elseif (is_array($value)) {
                $keys = array_keys($value);
                if (array_keys($keys) === $keys) {
                    // list
                    $subst['$'.$i] = $this->listify($value);
                } else {
                    // associative array
                    // no $i implementation yet, sorry
                    $subst['$'.$i.'.Keys'] = $this->listify($keys);
                    $subst['$'.$i.'.Values'] = $this->listify(array_values($value));
                }
                continue;
            }
            $subst['$' . $i] = $value;
        }
        return strtr($raw, $subst);
    }

}





/**
 * Class responsible for generating HTMLPurifier_Language objects, managing
 * caching and fallbacks.
 * @note Thanks to MediaWiki for the general logic, although this version
 *       has been entirely rewritten
 * @todo Serialized cache for languages
 */
class HTMLPurifier_LanguageFactory
{

    /**
     * Cache of language code information used to load HTMLPurifier_Language objects
     * Structure is: $factory->cache[$language_code][$key] = $value
     * @value array map
     */
    public $cache;

    /**
     * Valid keys in the HTMLPurifier_Language object. Designates which
     * variables to slurp out of a message file.
     * @value array list
     */
    public $keys = array('fallback', 'messages', 'errorNames');

    /**
     * Instance of HTMLPurifier_AttrDef_Lang to validate language codes
     * @value object HTMLPurifier_AttrDef_Lang
     */
    protected $validator;

    /**
     * Cached copy of dirname(__FILE__), directory of current file without
     * trailing slash
     * @value string filename
     */
    protected $dir;

    /**
     * Keys whose contents are a hash map and can be merged
     * @value array lookup
     */
    protected $mergeable_keys_map = array('messages' => true, 'errorNames' => true);

    /**
     * Keys whose contents are a list and can be merged
     * @value array lookup
     */
    protected $mergeable_keys_list = array();

    /**
     * Retrieve sole instance of the factory.
     * @param $prototype Optional prototype to overload sole instance with,
     *                   or bool true to reset to default factory.
     */
    public static function instance($prototype = null) {
        static $instance = null;
        if ($prototype !== null) {
            $instance = $prototype;
        } elseif ($instance === null || $prototype == true) {
            $instance = new HTMLPurifier_LanguageFactory();
            $instance->setup();
        }
        return $instance;
    }

    /**
     * Sets up the singleton, much like a constructor
     * @note Prevents people from getting this outside of the singleton
     */
    public function setup() {
        $this->validator = new HTMLPurifier_AttrDef_Lang();
        $this->dir = HTMLPURIFIER_PREFIX . '/HTMLPurifier';
    }

    /**
     * Creates a language object, handles class fallbacks
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @param $code Code to override configuration with. Private parameter.
     */
    public function create($config, $context, $code = false) {

        // validate language code
        if ($code === false) {
            $code = $this->validator->validate(
              $config->get('Core.Language'), $config, $context
            );
        } else {
            $code = $this->validator->validate($code, $config, $context);
        }
        if ($code === false) $code = 'en'; // malformed code becomes English

        $pcode = str_replace('-', '_', $code); // make valid PHP classname
        static $depth = 0; // recursion protection

        if ($code == 'en') {
            $lang = new HTMLPurifier_Language($config, $context);
        } else {
            $class = 'HTMLPurifier_Language_' . $pcode;
            $file  = $this->dir . '/Language/classes/' . $code . '.php';
            if (file_exists($file) || class_exists($class, false)) {
                $lang = new $class($config, $context);
            } else {
                // Go fallback
                $raw_fallback = $this->getFallbackFor($code);
                $fallback = $raw_fallback ? $raw_fallback : 'en';
                $depth++;
                $lang = $this->create($config, $context, $fallback);
                if (!$raw_fallback) {
                    $lang->error = true;
                }
                $depth--;
            }
        }

        $lang->code = $code;

        return $lang;

    }

    /**
     * Returns the fallback language for language
     * @note Loads the original language into cache
     * @param $code string language code
     */
    public function getFallbackFor($code) {
        $this->loadLanguage($code);
        return $this->cache[$code]['fallback'];
    }

    /**
     * Loads language into the cache, handles message file and fallbacks
     * @param $code string language code
     */
    public function loadLanguage($code) {
        static $languages_seen = array(); // recursion guard

        // abort if we've already loaded it
        if (isset($this->cache[$code])) return;

        // generate filename
        $filename = $this->dir . '/Language/messages/' . $code . '.php';

        // default fallback : may be overwritten by the ensuing include
        $fallback = ($code != 'en') ? 'en' : false;

        // load primary localisation
        if (!file_exists($filename)) {
            // skip the include: will rely solely on fallback
            $filename = $this->dir . '/Language/messages/en.php';
            $cache = array();
        } else {
            include $filename;
            $cache = compact($this->keys);
        }

        // load fallback localisation
        if (!empty($fallback)) {

            // infinite recursion guard
            if (isset($languages_seen[$code])) {
                trigger_error('Circular fallback reference in language ' .
                    $code, E_USER_ERROR);
                $fallback = 'en';
            }
            $language_seen[$code] = true;

            // load the fallback recursively
            $this->loadLanguage($fallback);
            $fallback_cache = $this->cache[$fallback];

            // merge fallback with current language
            foreach ( $this->keys as $key ) {
                if (isset($cache[$key]) && isset($fallback_cache[$key])) {
                    if (isset($this->mergeable_keys_map[$key])) {
                        $cache[$key] = $cache[$key] + $fallback_cache[$key];
                    } elseif (isset($this->mergeable_keys_list[$key])) {
                        $cache[$key] = array_merge( $fallback_cache[$key], $cache[$key] );
                    }
                } else {
                    $cache[$key] = $fallback_cache[$key];
                }
            }

        }

        // save to cache for later retrieval
        $this->cache[$code] = $cache;

        return;
    }

}





/**
 * Represents a measurable length, with a string numeric magnitude
 * and a unit. This object is immutable.
 */
class HTMLPurifier_Length
{

    /**
     * String numeric magnitude.
     */
    protected $n;

    /**
     * String unit. False is permitted if $n = 0.
     */
    protected $unit;

    /**
     * Whether or not this length is valid. Null if not calculated yet.
     */
    protected $isValid;

    /**
     * Lookup array of units recognized by CSS 2.1
     */
    protected static $allowedUnits = array(
        'em' => true, 'ex' => true, 'px' => true, 'in' => true,
        'cm' => true, 'mm' => true, 'pt' => true, 'pc' => true
    );

    /**
     * @param number $n Magnitude
     * @param string $u Unit
     */
    public function __construct($n = '0', $u = false) {
        $this->n = (string) $n;
        $this->unit = $u !== false ? (string) $u : false;
    }

    /**
     * @param string $s Unit string, like '2em' or '3.4in'
     * @warning Does not perform validation.
     */
    static public function make($s) {
        if ($s instanceof HTMLPurifier_Length) return $s;
        $n_length = strspn($s, '1234567890.+-');
        $n = substr($s, 0, $n_length);
        $unit = substr($s, $n_length);
        if ($unit === '') $unit = false;
        return new HTMLPurifier_Length($n, $unit);
    }

    /**
     * Validates the number and unit.
     */
    protected function validate() {
        // Special case:
        if ($this->n === '+0' || $this->n === '-0') $this->n = '0';
        if ($this->n === '0' && $this->unit === false) return true;
        if (!ctype_lower($this->unit)) $this->unit = strtolower($this->unit);
        if (!isset(HTMLPurifier_Length::$allowedUnits[$this->unit])) return false;
        // Hack:
        $def = new HTMLPurifier_AttrDef_CSS_Number();
        $result = $def->validate($this->n, false, false);
        if ($result === false) return false;
        $this->n = $result;
        return true;
    }

    /**
     * Returns string representation of number.
     */
    public function toString() {
        if (!$this->isValid()) return false;
        return $this->n . $this->unit;
    }

    /**
     * Retrieves string numeric magnitude.
     */
    public function getN() {return $this->n;}

    /**
     * Retrieves string unit.
     */
    public function getUnit() {return $this->unit;}

    /**
     * Returns true if this length unit is valid.
     */
    public function isValid() {
        if ($this->isValid === null) $this->isValid = $this->validate();
        return $this->isValid;
    }

    /**
     * Compares two lengths, and returns 1 if greater, -1 if less and 0 if equal.
     * @warning If both values are too large or small, this calculation will
     *          not work properly
     */
    public function compareTo($l) {
        if ($l === false) return false;
        if ($l->unit !== $this->unit) {
            $converter = new HTMLPurifier_UnitConverter();
            $l = $converter->convert($l, $this->unit);
            if ($l === false) return false;
        }
        return $this->n - $l->n;
    }

}





/**
 * Forgivingly lexes HTML (SGML-style) markup into tokens.
 *
 * A lexer parses a string of SGML-style markup and converts them into
 * corresponding tokens.  It doesn't check for well-formedness, although its
 * internal mechanism may make this automatic (such as the case of
 * HTMLPurifier_Lexer_DOMLex).  There are several implementations to choose
 * from.
 *
 * A lexer is HTML-oriented: it might work with XML, but it's not
 * recommended, as we adhere to a subset of the specification for optimization
 * reasons. This might change in the future. Also, most tokenizers are not
 * expected to handle DTDs or PIs.
 *
 * This class should not be directly instantiated, but you may use create() to
 * retrieve a default copy of the lexer.  Being a supertype, this class
 * does not actually define any implementation, but offers commonly used
 * convenience functions for subclasses.
 *
 * @note The unit tests will instantiate this class for testing purposes, as
 *       many of the utility functions require a class to be instantiated.
 *       This means that, even though this class is not runnable, it will
 *       not be declared abstract.
 *
 * @par
 *
 * @note
 * We use tokens rather than create a DOM representation because DOM would:
 *
 * @par
 *  -# Require more processing and memory to create,
 *  -# Is not streamable, and
 *  -# Has the entire document structure (html and body not needed).
 *
 * @par
 * However, DOM is helpful in that it makes it easy to move around nodes
 * without a lot of lookaheads to see when a tag is closed. This is a
 * limitation of the token system and some workarounds would be nice.
 */
class HTMLPurifier_Lexer
{

    /**
     * Whether or not this lexer implements line-number/column-number tracking.
     * If it does, set to true.
     */
    public $tracksLineNumbers = false;

    // -- STATIC ----------------------------------------------------------

    /**
     * Retrieves or sets the default Lexer as a Prototype Factory.
     *
     * By default HTMLPurifier_Lexer_DOMLex will be returned. There are
     * a few exceptions involving special features that only DirectLex
     * implements.
     *
     * @note The behavior of this class has changed, rather than accepting
     *       a prototype object, it now accepts a configuration object.
     *       To specify your own prototype, set %Core.LexerImpl to it.
     *       This change in behavior de-singletonizes the lexer object.
     *
     * @param $config Instance of HTMLPurifier_Config
     * @return Concrete lexer.
     */
    public static function create($config) {

        if (!($config instanceof HTMLPurifier_Config)) {
            $lexer = $config;
            trigger_error("Passing a prototype to
              HTMLPurifier_Lexer::create() is deprecated, please instead
              use %Core.LexerImpl", E_USER_WARNING);
        } else {
            $lexer = $config->get('Core.LexerImpl');
        }

        $needs_tracking =
            $config->get('Core.MaintainLineNumbers') ||
            $config->get('Core.CollectErrors');

        $inst = null;
        if (is_object($lexer)) {
            $inst = $lexer;
        } else {

            if (is_null($lexer)) { do {
                // auto-detection algorithm

                if ($needs_tracking) {
                    $lexer = 'DirectLex';
                    break;
                }

                if (
                    class_exists('DOMDocument') &&
                    method_exists('DOMDocument', 'loadHTML') &&
                    !extension_loaded('domxml')
                ) {
                    // check for DOM support, because while it's part of the
                    // core, it can be disabled compile time. Also, the PECL
                    // domxml extension overrides the default DOM, and is evil
                    // and nasty and we shan't bother to support it
                    $lexer = 'DOMLex';
                } else {
                    $lexer = 'DirectLex';
                }

            } while(0); } // do..while so we can break

            // instantiate recognized string names
            switch ($lexer) {
                case 'DOMLex':
                    $inst = new HTMLPurifier_Lexer_DOMLex();
                    break;
                case 'DirectLex':
                    $inst = new HTMLPurifier_Lexer_DirectLex();
                    break;
                case 'PH5P':
                    $inst = new HTMLPurifier_Lexer_PH5P();
                    break;
                default:
                    throw new HTMLPurifier_Exception("Cannot instantiate unrecognized Lexer type " . htmlspecialchars($lexer));
            }
        }

        if (!$inst) throw new HTMLPurifier_Exception('No lexer was instantiated');

        // once PHP DOM implements native line numbers, or we
        // hack out something using XSLT, remove this stipulation
        if ($needs_tracking && !$inst->tracksLineNumbers) {
            throw new HTMLPurifier_Exception('Cannot use lexer that does not support line numbers with Core.MaintainLineNumbers or Core.CollectErrors (use DirectLex instead)');
        }

        return $inst;

    }

    // -- CONVENIENCE MEMBERS ---------------------------------------------

    public function __construct() {
        $this->_entity_parser = new HTMLPurifier_EntityParser();
    }

    /**
     * Most common entity to raw value conversion table for special entities.
     */
    protected $_special_entity2str =
            array(
                    '&quot;' => '"',
                    '&amp;'  => '&',
                    '&lt;'   => '<',
                    '&gt;'   => '>',
                    '&#39;'  => "'",
                    '&#039;' => "'",
                    '&#x27;' => "'"
            );

    /**
     * Parses special entities into the proper characters.
     *
     * This string will translate escaped versions of the special characters
     * into the correct ones.
     *
     * @warning
     * You should be able to treat the output of this function as
     * completely parsed, but that's only because all other entities should
     * have been handled previously in substituteNonSpecialEntities()
     *
     * @param $string String character data to be parsed.
     * @returns Parsed character data.
     */
    public function parseData($string) {

        // following functions require at least one character
        if ($string === '') return '';

        // subtracts amps that cannot possibly be escaped
        $num_amp = substr_count($string, '&') - substr_count($string, '& ') -
            ($string[strlen($string)-1] === '&' ? 1 : 0);

        if (!$num_amp) return $string; // abort if no entities
        $num_esc_amp = substr_count($string, '&amp;');
        $string = strtr($string, $this->_special_entity2str);

        // code duplication for sake of optimization, see above
        $num_amp_2 = substr_count($string, '&') - substr_count($string, '& ') -
            ($string[strlen($string)-1] === '&' ? 1 : 0);

        if ($num_amp_2 <= $num_esc_amp) return $string;

        // hmm... now we have some uncommon entities. Use the callback.
        $string = $this->_entity_parser->substituteSpecialEntities($string);
        return $string;
    }

    /**
     * Lexes an HTML string into tokens.
     *
     * @param $string String HTML.
     * @return HTMLPurifier_Token array representation of HTML.
     */
    public function tokenizeHTML($string, $config, $context) {
        trigger_error('Call to abstract class', E_USER_ERROR);
    }

    /**
     * Translates CDATA sections into regular sections (through escaping).
     *
     * @param $string HTML string to process.
     * @returns HTML with CDATA sections escaped.
     */
    protected static function escapeCDATA($string) {
        return preg_replace_callback(
            '/<!\[CDATA\[(.+?)\]\]>/s',
            array('HTMLPurifier_Lexer', 'CDATACallback'),
            $string
        );
    }

    /**
     * Special CDATA case that is especially convoluted for <script>
     */
    protected static function escapeCommentedCDATA($string) {
        return preg_replace_callback(
            '#<!--//--><!\[CDATA\[//><!--(.+?)//--><!\]\]>#s',
            array('HTMLPurifier_Lexer', 'CDATACallback'),
            $string
        );
    }

    /**
     * Special Internet Explorer conditional comments should be removed.
     */
    protected static function removeIEConditional($string) {
        return preg_replace(
            '#<!--\[if [^>]+\]>.*?<!\[endif\]-->#si', // probably should generalize for all strings
            '',
            $string
        );
    }

    /**
     * Callback function for escapeCDATA() that does the work.
     *
     * @warning Though this is public in order to let the callback happen,
     *          calling it directly is not recommended.
     * @params $matches PCRE matches array, with index 0 the entire match
     *                  and 1 the inside of the CDATA section.
     * @returns Escaped internals of the CDATA section.
     */
    protected static function CDATACallback($matches) {
        // not exactly sure why the character set is needed, but whatever
        return htmlspecialchars($matches[1], ENT_COMPAT, 'UTF-8');
    }

    /**
     * Takes a piece of HTML and normalizes it by converting entities, fixing
     * encoding, extracting bits, and other good stuff.
     * @todo Consider making protected
     */
    public function normalize($html, $config, $context) {

        // normalize newlines to \n
        if ($config->get('Core.NormalizeNewlines')) {
            $html = str_replace("\r\n", "\n", $html);
            $html = str_replace("\r", "\n", $html);
        }

        if ($config->get('HTML.Trusted')) {
            // escape convoluted CDATA
            $html = $this->escapeCommentedCDATA($html);
        }

        // escape CDATA
        $html = $this->escapeCDATA($html);

        $html = $this->removeIEConditional($html);

        // extract body from document if applicable
        if ($config->get('Core.ConvertDocumentToFragment')) {
            $e = false;
            if ($config->get('Core.CollectErrors')) {
                $e =& $context->get('ErrorCollector');
            }
            $new_html = $this->extractBody($html);
            if ($e && $new_html != $html) {
                $e->send(E_WARNING, 'Lexer: Extracted body');
            }
            $html = $new_html;
        }

        // expand entities that aren't the big five
        $html = $this->_entity_parser->substituteNonSpecialEntities($html);

        // clean into wellformed UTF-8 string for an SGML context: this has
        // to be done after entity expansion because the entities sometimes
        // represent non-SGML characters (horror, horror!)
        $html = HTMLPurifier_Encoder::cleanUTF8($html);

        // if processing instructions are to removed, remove them now
        if ($config->get('Core.RemoveProcessingInstructions')) {
            $html = preg_replace('#<\?.+?\?>#s', '', $html);
        }

        return $html;
    }

    /**
     * Takes a string of HTML (fragment or document) and returns the content
     * @todo Consider making protected
     */
    public function extractBody($html) {
        $matches = array();
        $result = preg_match('!<body[^>]*>(.*)</body>!is', $html, $matches);
        if ($result) {
            return $matches[1];
        } else {
            return $html;
        }
    }

}





/**
 * Class that handles operations involving percent-encoding in URIs.
 *
 * @warning
 *      Be careful when reusing instances of PercentEncoder. The object
 *      you use for normalize() SHOULD NOT be used for encode(), or
 *      vice-versa.
 */
class HTMLPurifier_PercentEncoder
{

    /**
     * Reserved characters to preserve when using encode().
     */
    protected $preserve = array();

    /**
     * String of characters that should be preserved while using encode().
     */
    public function __construct($preserve = false) {
        // unreserved letters, ought to const-ify
        for ($i = 48; $i <= 57;  $i++) $this->preserve[$i] = true; // digits
        for ($i = 65; $i <= 90;  $i++) $this->preserve[$i] = true; // upper-case
        for ($i = 97; $i <= 122; $i++) $this->preserve[$i] = true; // lower-case
        $this->preserve[45] = true; // Dash         -
        $this->preserve[46] = true; // Period       .
        $this->preserve[95] = true; // Underscore   _
        $this->preserve[126]= true; // Tilde        ~

        // extra letters not to escape
        if ($preserve !== false) {
            for ($i = 0, $c = strlen($preserve); $i < $c; $i++) {
                $this->preserve[ord($preserve[$i])] = true;
            }
        }
    }

    /**
     * Our replacement for urlencode, it encodes all non-reserved characters,
     * as well as any extra characters that were instructed to be preserved.
     * @note
     *      Assumes that the string has already been normalized, making any
     *      and all percent escape sequences valid. Percents will not be
     *      re-escaped, regardless of their status in $preserve
     * @param $string String to be encoded
     * @return Encoded string.
     */
    public function encode($string) {
        $ret = '';
        for ($i = 0, $c = strlen($string); $i < $c; $i++) {
            if ($string[$i] !== '%' && !isset($this->preserve[$int = ord($string[$i])]) ) {
                $ret .= '%' . sprintf('%02X', $int);
            } else {
                $ret .= $string[$i];
            }
        }
        return $ret;
    }

    /**
     * Fix up percent-encoding by decoding unreserved characters and normalizing.
     * @warning This function is affected by $preserve, even though the
     *          usual desired behavior is for this not to preserve those
     *          characters. Be careful when reusing instances of PercentEncoder!
     * @param $string String to normalize
     */
    public function normalize($string) {
        if ($string == '') return '';
        $parts = explode('%', $string);
        $ret = array_shift($parts);
        foreach ($parts as $part) {
            $length = strlen($part);
            if ($length < 2) {
                $ret .= '%25' . $part;
                continue;
            }
            $encoding = substr($part, 0, 2);
            $text     = substr($part, 2);
            if (!ctype_xdigit($encoding)) {
                $ret .= '%25' . $part;
                continue;
            }
            $int = hexdec($encoding);
            if (isset($this->preserve[$int])) {
                $ret .= chr($int) . $text;
                continue;
            }
            $encoding = strtoupper($encoding);
            $ret .= '%' . $encoding . $text;
        }
        return $ret;
    }

}





/**
 * Generic property list implementation
 */
class HTMLPurifier_PropertyList
{
    /**
     * Internal data-structure for properties
     */
    protected $data = array();

    /**
     * Parent plist
     */
    protected $parent;

    protected $cache;

    public function __construct($parent = null) {
        $this->parent = $parent;
    }

    /**
     * Recursively retrieves the value for a key
     */
    public function get($name) {
        if ($this->has($name)) return $this->data[$name];
        // possible performance bottleneck, convert to iterative if necessary
        if ($this->parent) return $this->parent->get($name);
        throw new HTMLPurifier_Exception("Key '$name' not found");
    }

    /**
     * Sets the value of a key, for this plist
     */
    public function set($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * Returns true if a given key exists
     */
    public function has($name) {
        return array_key_exists($name, $this->data);
    }

    /**
     * Resets a value to the value of it's parent, usually the default. If
     * no value is specified, the entire plist is reset.
     */
    public function reset($name = null) {
        if ($name == null) $this->data = array();
        else unset($this->data[$name]);
    }

    /**
     * Squashes this property list and all of its property lists into a single
     * array, and returns the array. This value is cached by default.
     * @param $force If true, ignores the cache and regenerates the array.
     */
    public function squash($force = false) {
        if ($this->cache !== null && !$force) return $this->cache;
        if ($this->parent) {
            return $this->cache = array_merge($this->parent->squash($force), $this->data);
        } else {
            return $this->cache = $this->data;
        }
    }

    /**
     * Returns the parent plist.
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Sets the parent plist.
     */
    public function setParent($plist) {
        $this->parent = $plist;
    }
}





/**
 * Property list iterator. Do not instantiate this class directly.
 */
class HTMLPurifier_PropertyListIterator extends FilterIterator
{

    protected $l;
    protected $filter;

    /**
     * @param $data Array of data to iterate over
     * @param $filter Optional prefix to only allow values of
     */
    public function __construct(Iterator $iterator, $filter = null) {
        parent::__construct($iterator);
        $this->l = strlen($filter);
        $this->filter = $filter;
    }

    public function accept() {
        $key = $this->getInnerIterator()->key();
        if( strncmp($key, $this->filter, $this->l) !== 0 ) {
            return false;
        }
        return true;
    }

}





/**
 * Supertype for classes that define a strategy for modifying/purifying tokens.
 *
 * While HTMLPurifier's core purpose is fixing HTML into something proper,
 * strategies provide plug points for extra configuration or even extra
 * features, such as custom tags, custom parsing of text, etc.
 */


abstract class HTMLPurifier_Strategy
{

    /**
     * Executes the strategy on the tokens.
     *
     * @param $tokens Array of HTMLPurifier_Token objects to be operated on.
     * @param $config Configuration options
     * @returns Processed array of token objects.
     */
    abstract public function execute($tokens, $config, $context);

}





/**
 * This is in almost every respect equivalent to an array except
 * that it keeps track of which keys were accessed.
 *
 * @warning For the sake of backwards compatibility with early versions
 *     of PHP 5, you must not use the $hash[$key] syntax; if you do
 *     our version of offsetGet is never called.
 */
class HTMLPurifier_StringHash extends ArrayObject
{
    protected $accessed = array();

    /**
     * Retrieves a value, and logs the access.
     */
    public function offsetGet($index) {
        $this->accessed[$index] = true;
        return parent::offsetGet($index);
    }

    /**
     * Returns a lookup array of all array indexes that have been accessed.
     * @return Array in form array($index => true).
     */
    public function getAccessed() {
        return $this->accessed;
    }

    /**
     * Resets the access array.
     */
    public function resetAccessed() {
        $this->accessed = array();
    }
}





/**
 * Parses string hash files. File format is as such:
 *
 *      DefaultKeyValue
 *      KEY: Value
 *      KEY2: Value2
 *      --MULTILINE-KEY--
 *      Multiline
 *      value.
 *
 * Which would output something similar to:
 *
 *      array(
 *          'ID' => 'DefaultKeyValue',
 *          'KEY' => 'Value',
 *          'KEY2' => 'Value2',
 *          'MULTILINE-KEY' => "Multiline\nvalue.\n",
 *      )
 *
 * We use this as an easy to use file-format for configuration schema
 * files, but the class itself is usage agnostic.
 *
 * You can use ---- to forcibly terminate parsing of a single string-hash;
 * this marker is used in multi string-hashes to delimit boundaries.
 */
class HTMLPurifier_StringHashParser
{

    public $default = 'ID';

    /**
     * Parses a file that contains a single string-hash.
     */
    public function parseFile($file) {
        if (!file_exists($file)) return false;
        $fh = fopen($file, 'r');
        if (!$fh) return false;
        $ret = $this->parseHandle($fh);
        fclose($fh);
        return $ret;
    }

    /**
     * Parses a file that contains multiple string-hashes delimited by '----'
     */
    public function parseMultiFile($file) {
        if (!file_exists($file)) return false;
        $ret = array();
        $fh = fopen($file, 'r');
        if (!$fh) return false;
        while (!feof($fh)) {
            $ret[] = $this->parseHandle($fh);
        }
        fclose($fh);
        return $ret;
    }

    /**
     * Internal parser that acepts a file handle.
     * @note While it's possible to simulate in-memory parsing by using
     *       custom stream wrappers, if such a use-case arises we should
     *       factor out the file handle into its own class.
     * @param $fh File handle with pointer at start of valid string-hash
     *            block.
     */
    protected function parseHandle($fh) {
        $state   = false;
        $single  = false;
        $ret     = array();
        do {
            $line = fgets($fh);
            if ($line === false) break;
            $line = rtrim($line, "\n\r");
            if (!$state && $line === '') continue;
            if ($line === '----') break;
            if (strncmp('--#', $line, 3) === 0) {
                // Comment
                continue;
            } elseif (strncmp('--', $line, 2) === 0) {
                // Multiline declaration
                $state = trim($line, '- ');
                if (!isset($ret[$state])) $ret[$state] = '';
                continue;
            } elseif (!$state) {
                $single = true;
                if (strpos($line, ':') !== false) {
                    // Single-line declaration
                    list($state, $line) = explode(':', $line, 2);
                    $line = trim($line);
                } else {
                    // Use default declaration
                    $state  = $this->default;
                }
            }
            if ($single) {
                $ret[$state] = $line;
                $single = false;
                $state  = false;
            } else {
                $ret[$state] .= "$line\n";
            }
        } while (!feof($fh));
        return $ret;
    }

}





/**
 * Defines a mutation of an obsolete tag into a valid tag.
 */
abstract class HTMLPurifier_TagTransform
{

    /**
     * Tag name to transform the tag to.
     */
    public $transform_to;

    /**
     * Transforms the obsolete tag into the valid tag.
     * @param $tag Tag to be transformed.
     * @param $config Mandatory HTMLPurifier_Config object
     * @param $context Mandatory HTMLPurifier_Context object
     */
    abstract public function transform($tag, $config, $context);

    /**
     * Prepends CSS properties to the style attribute, creating the
     * attribute if it doesn't exist.
     * @warning Copied over from AttrTransform, be sure to keep in sync
     * @param $attr Attribute array to process (passed by reference)
     * @param $css CSS to prepend
     */
    protected function prependCSS(&$attr, $css) {
        $attr['style'] = isset($attr['style']) ? $attr['style'] : '';
        $attr['style'] = $css . $attr['style'];
    }

}





/**
 * Abstract base token class that all others inherit from.
 */
class HTMLPurifier_Token {
    public $line; /**< Line number node was on in source document. Null if unknown. */
    public $col;  /**< Column of line node was on in source document. Null if unknown. */

    /**
     * Lookup array of processing that this token is exempt from.
     * Currently, valid values are "ValidateAttributes" and
     * "MakeWellFormed_TagClosedError"
     */
    public $armor = array();

    /**
     * Used during MakeWellFormed.
     */
    public $skip;
    public $rewind;
    public $carryover;

    public function __get($n) {
      if ($n === 'type') {
        trigger_error('Deprecated type property called; use instanceof', E_USER_NOTICE);
        switch (get_class($this)) {
          case 'HTMLPurifier_Token_Start':      return 'start';
          case 'HTMLPurifier_Token_Empty':      return 'empty';
          case 'HTMLPurifier_Token_End':        return 'end';
          case 'HTMLPurifier_Token_Text':       return 'text';
          case 'HTMLPurifier_Token_Comment':    return 'comment';
          default: return null;
        }
      }
    }

    /**
     * Sets the position of the token in the source document.
     */
    public function position($l = null, $c = null) {
        $this->line = $l;
        $this->col  = $c;
    }

    /**
     * Convenience function for DirectLex settings line/col position.
     */
    public function rawPosition($l, $c) {
        if ($c === -1) $l++;
        $this->line = $l;
        $this->col  = $c;
    }

}





/**
 * Factory for token generation.
 *
 * @note Doing some benchmarking indicates that the new operator is much
 *       slower than the clone operator (even discounting the cost of the
 *       constructor).  This class is for that optimization.
 *       Other then that, there's not much point as we don't
 *       maintain parallel HTMLPurifier_Token hierarchies (the main reason why
 *       you'd want to use an abstract factory).
 * @todo Port DirectLex to use this
 */
class HTMLPurifier_TokenFactory
{

    /**
     * Prototypes that will be cloned.
     * @private
     */
    // p stands for prototype
    private $p_start, $p_end, $p_empty, $p_text, $p_comment;

    /**
     * Generates blank prototypes for cloning.
     */
    public function __construct() {
        $this->p_start  = new HTMLPurifier_Token_Start('', array());
        $this->p_end    = new HTMLPurifier_Token_End('');
        $this->p_empty  = new HTMLPurifier_Token_Empty('', array());
        $this->p_text   = new HTMLPurifier_Token_Text('');
        $this->p_comment= new HTMLPurifier_Token_Comment('');
    }

    /**
     * Creates a HTMLPurifier_Token_Start.
     * @param $name Tag name
     * @param $attr Associative array of attributes
     * @return Generated HTMLPurifier_Token_Start
     */
    public function createStart($name, $attr = array()) {
        $p = clone $this->p_start;
        $p->__construct($name, $attr);
        return $p;
    }

    /**
     * Creates a HTMLPurifier_Token_End.
     * @param $name Tag name
     * @return Generated HTMLPurifier_Token_End
     */
    public function createEnd($name) {
        $p = clone $this->p_end;
        $p->__construct($name);
        return $p;
    }

    /**
     * Creates a HTMLPurifier_Token_Empty.
     * @param $name Tag name
     * @param $attr Associative array of attributes
     * @return Generated HTMLPurifier_Token_Empty
     */
    public function createEmpty($name, $attr = array()) {
        $p = clone $this->p_empty;
        $p->__construct($name, $attr);
        return $p;
    }

    /**
     * Creates a HTMLPurifier_Token_Text.
     * @param $data Data of text token
     * @return Generated HTMLPurifier_Token_Text
     */
    public function createText($data) {
        $p = clone $this->p_text;
        $p->__construct($data);
        return $p;
    }

    /**
     * Creates a HTMLPurifier_Token_Comment.
     * @param $data Data of comment token
     * @return Generated HTMLPurifier_Token_Comment
     */
    public function createComment($data) {
        $p = clone $this->p_comment;
        $p->__construct($data);
        return $p;
    }

}





/**
 * HTML Purifier's internal representation of a URI.
 * @note
 *      Internal data-structures are completely escaped. If the data needs
 *      to be used in a non-URI context (which is very unlikely), be sure
 *      to decode it first. The URI may not necessarily be well-formed until
 *      validate() is called.
 */
class HTMLPurifier_URI
{

    public $scheme, $userinfo, $host, $port, $path, $query, $fragment;

    /**
     * @note Automatically normalizes scheme and port
     */
    public function __construct($scheme, $userinfo, $host, $port, $path, $query, $fragment) {
        $this->scheme = is_null($scheme) || ctype_lower($scheme) ? $scheme : strtolower($scheme);
        $this->userinfo = $userinfo;
        $this->host = $host;
        $this->port = is_null($port) ? $port : (int) $port;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
    }

    /**
     * Retrieves a scheme object corresponding to the URI's scheme/default
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @return Scheme object appropriate for validating this URI
     */
    public function getSchemeObj($config, $context) {
        $registry = HTMLPurifier_URISchemeRegistry::instance();
        if ($this->scheme !== null) {
            $scheme_obj = $registry->getScheme($this->scheme, $config, $context);
            if (!$scheme_obj) return false; // invalid scheme, clean it out
        } else {
            // no scheme: retrieve the default one
            $def = $config->getDefinition('URI');
            $scheme_obj = $registry->getScheme($def->defaultScheme, $config, $context);
            if (!$scheme_obj) {
                // something funky happened to the default scheme object
                trigger_error(
                    'Default scheme object "' . $def->defaultScheme . '" was not readable',
                    E_USER_WARNING
                );
                return false;
            }
        }
        return $scheme_obj;
    }

    /**
     * Generic validation method applicable for all schemes. May modify
     * this URI in order to get it into a compliant form.
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @return True if validation/filtering succeeds, false if failure
     */
    public function validate($config, $context) {

        // ABNF definitions from RFC 3986
        $chars_sub_delims = '!$&\'()*+,;=';
        $chars_gen_delims = ':/?#[]@';
        $chars_pchar = $chars_sub_delims . ':@';

        // validate host
        if (!is_null($this->host)) {
            $host_def = new HTMLPurifier_AttrDef_URI_Host();
            $this->host = $host_def->validate($this->host, $config, $context);
            if ($this->host === false) $this->host = null;
        }

        // validate scheme
        // NOTE: It's not appropriate to check whether or not this
        // scheme is in our registry, since a URIFilter may convert a
        // URI that we don't allow into one we do.  So instead, we just
        // check if the scheme can be dropped because there is no host
        // and it is our default scheme.
        if (!is_null($this->scheme) && is_null($this->host) || $this->host === '') {
            // support for relative paths is pretty abysmal when the
            // scheme is present, so axe it when possible
            $def = $config->getDefinition('URI');
            if ($def->defaultScheme === $this->scheme) {
                $this->scheme = null;
            }
        }

        // validate username
        if (!is_null($this->userinfo)) {
            $encoder = new HTMLPurifier_PercentEncoder($chars_sub_delims . ':');
            $this->userinfo = $encoder->encode($this->userinfo);
        }

        // validate port
        if (!is_null($this->port)) {
            if ($this->port < 1 || $this->port > 65535) $this->port = null;
        }

        // validate path
        $path_parts = array();
        $segments_encoder = new HTMLPurifier_PercentEncoder($chars_pchar . '/');
        if (!is_null($this->host)) { // this catches $this->host === ''
            // path-abempty (hier and relative)
            // http://www.example.com/my/path
            // //www.example.com/my/path (looks odd, but works, and
            //                            recognized by most browsers)
            // (this set is valid or invalid on a scheme by scheme
            // basis, so we'll deal with it later)
            // file:///my/path
            // ///my/path
            $this->path = $segments_encoder->encode($this->path);
        } elseif ($this->path !== '') {
            if ($this->path[0] === '/') {
                // path-absolute (hier and relative)
                // http:/my/path
                // /my/path
                if (strlen($this->path) >= 2 && $this->path[1] === '/') {
                    // This could happen if both the host gets stripped
                    // out
                    // http://my/path
                    // //my/path
                    $this->path = '';
                } else {
                    $this->path = $segments_encoder->encode($this->path);
                }
            } elseif (!is_null($this->scheme)) {
                // path-rootless (hier)
                // http:my/path
                // Short circuit evaluation means we don't need to check nz
                $this->path = $segments_encoder->encode($this->path);
            } else {
                // path-noscheme (relative)
                // my/path
                // (once again, not checking nz)
                $segment_nc_encoder = new HTMLPurifier_PercentEncoder($chars_sub_delims . '@');
                $c = strpos($this->path, '/');
                if ($c !== false) {
                    $this->path =
                        $segment_nc_encoder->encode(substr($this->path, 0, $c)) .
                        $segments_encoder->encode(substr($this->path, $c));
                } else {
                    $this->path = $segment_nc_encoder->encode($this->path);
                }
            }
        } else {
            // path-empty (hier and relative)
            $this->path = ''; // just to be safe
        }

        // qf = query and fragment
        $qf_encoder = new HTMLPurifier_PercentEncoder($chars_pchar . '/?');

        if (!is_null($this->query)) {
            $this->query = $qf_encoder->encode($this->query);
        }

        if (!is_null($this->fragment)) {
            $this->fragment = $qf_encoder->encode($this->fragment);
        }

        return true;

    }

    /**
     * Convert URI back to string
     * @return String URI appropriate for output
     */
    public function toString() {
        // reconstruct authority
        $authority = null;
        // there is a rendering difference between a null authority
        // (http:foo-bar) and an empty string authority
        // (http:///foo-bar).
        if (!is_null($this->host)) {
            $authority = '';
            if(!is_null($this->userinfo)) $authority .= $this->userinfo . '@';
            $authority .= $this->host;
            if(!is_null($this->port))     $authority .= ':' . $this->port;
        }

        // Reconstruct the result
        // One might wonder about parsing quirks from browsers after
        // this reconstruction.  Unfortunately, parsing behavior depends
        // on what *scheme* was employed (file:///foo is handled *very*
        // differently than http:///foo), so unfortunately we have to
        // defer to the schemes to do the right thing.
        $result = '';
        if (!is_null($this->scheme))    $result .= $this->scheme . ':';
        if (!is_null($authority))       $result .=  '//' . $authority;
        $result .= $this->path;
        if (!is_null($this->query))     $result .= '?' . $this->query;
        if (!is_null($this->fragment))  $result .= '#' . $this->fragment;

        return $result;
    }

}





class HTMLPurifier_URIDefinition extends HTMLPurifier_Definition
{

    public $type = 'URI';
    protected $filters = array();
    protected $postFilters = array();
    protected $registeredFilters = array();

    /**
     * HTMLPurifier_URI object of the base specified at %URI.Base
     */
    public $base;

    /**
     * String host to consider "home" base, derived off of $base
     */
    public $host;

    /**
     * Name of default scheme based on %URI.DefaultScheme and %URI.Base
     */
    public $defaultScheme;

    public function __construct() {
        $this->registerFilter(new HTMLPurifier_URIFilter_DisableExternal());
        $this->registerFilter(new HTMLPurifier_URIFilter_DisableExternalResources());
        $this->registerFilter(new HTMLPurifier_URIFilter_HostBlacklist());
        $this->registerFilter(new HTMLPurifier_URIFilter_MakeAbsolute());
        $this->registerFilter(new HTMLPurifier_URIFilter_Munge());
    }

    public function registerFilter($filter) {
        $this->registeredFilters[$filter->name] = $filter;
    }

    public function addFilter($filter, $config) {
        $r = $filter->prepare($config);
        if ($r === false) return; // null is ok, for backwards compat
        if ($filter->post) {
            $this->postFilters[$filter->name] = $filter;
        } else {
            $this->filters[$filter->name] = $filter;
        }
    }

    protected function doSetup($config) {
        $this->setupMemberVariables($config);
        $this->setupFilters($config);
    }

    protected function setupFilters($config) {
        foreach ($this->registeredFilters as $name => $filter) {
            $conf = $config->get('URI.' . $name);
            if ($conf !== false && $conf !== null) {
                $this->addFilter($filter, $config);
            }
        }
        unset($this->registeredFilters);
    }

    protected function setupMemberVariables($config) {
        $this->host = $config->get('URI.Host');
        $base_uri = $config->get('URI.Base');
        if (!is_null($base_uri)) {
            $parser = new HTMLPurifier_URIParser();
            $this->base = $parser->parse($base_uri);
            $this->defaultScheme = $this->base->scheme;
            if (is_null($this->host)) $this->host = $this->base->host;
        }
        if (is_null($this->defaultScheme)) $this->defaultScheme = $config->get('URI.DefaultScheme');
    }

    public function filter(&$uri, $config, $context) {
        foreach ($this->filters as $name => $f) {
            $result = $f->filter($uri, $config, $context);
            if (!$result) return false;
        }
        return true;
    }

    public function postFilter(&$uri, $config, $context) {
        foreach ($this->postFilters as $name => $f) {
            $result = $f->filter($uri, $config, $context);
            if (!$result) return false;
        }
        return true;
    }

}





/**
 * Chainable filters for custom URI processing.
 *
 * These filters can perform custom actions on a URI filter object,
 * including transformation or blacklisting.
 *
 * @warning This filter is called before scheme object validation occurs.
 *          Make sure, if you require a specific scheme object, you
 *          you check that it exists. This allows filters to convert
 *          proprietary URI schemes into regular ones.
 */
abstract class HTMLPurifier_URIFilter
{

    /**
     * Unique identifier of filter
     */
    public $name;

    /**
     * True if this filter should be run after scheme validation.
     */
    public $post = false;

    /**
     * Performs initialization for the filter
     */
    public function prepare($config) {return true;}

    /**
     * Filter a URI object
     * @param $uri Reference to URI object variable
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     * @return bool Whether or not to continue processing: false indicates
     *         URL is no good, true indicates continue processing. Note that
     *         all changes are committed directly on the URI object
     */
    abstract public function filter(&$uri, $config, $context);

}





/**
 * Parses a URI into the components and fragment identifier as specified
 * by RFC 3986.
 */
class HTMLPurifier_URIParser
{

    /**
     * Instance of HTMLPurifier_PercentEncoder to do normalization with.
     */
    protected $percentEncoder;

    public function __construct() {
        $this->percentEncoder = new HTMLPurifier_PercentEncoder();
    }

    /**
     * Parses a URI.
     * @param $uri string URI to parse
     * @return HTMLPurifier_URI representation of URI. This representation has
     *         not been validated yet and may not conform to RFC.
     */
    public function parse($uri) {

        $uri = $this->percentEncoder->normalize($uri);

        // Regexp is as per Appendix B.
        // Note that ["<>] are an addition to the RFC's recommended
        // characters, because they represent external delimeters.
        $r_URI = '!'.
            '(([^:/?#"<>]+):)?'. // 2. Scheme
            '(//([^/?#"<>]*))?'. // 4. Authority
            '([^?#"<>]*)'.       // 5. Path
            '(\?([^#"<>]*))?'.   // 7. Query
            '(#([^"<>]*))?'.     // 8. Fragment
            '!';

        $matches = array();
        $result = preg_match($r_URI, $uri, $matches);

        if (!$result) return false; // *really* invalid URI

        // seperate out parts
        $scheme     = !empty($matches[1]) ? $matches[2] : null;
        $authority  = !empty($matches[3]) ? $matches[4] : null;
        $path       = $matches[5]; // always present, can be empty
        $query      = !empty($matches[6]) ? $matches[7] : null;
        $fragment   = !empty($matches[8]) ? $matches[9] : null;

        // further parse authority
        if ($authority !== null) {
            $r_authority = "/^((.+?)@)?(\[[^\]]+\]|[^:]*)(:(\d*))?/";
            $matches = array();
            preg_match($r_authority, $authority, $matches);
            $userinfo   = !empty($matches[1]) ? $matches[2] : null;
            $host       = !empty($matches[3]) ? $matches[3] : '';
            $port       = !empty($matches[4]) ? (int) $matches[5] : null;
        } else {
            $port = $host = $userinfo = null;
        }

        return new HTMLPurifier_URI(
            $scheme, $userinfo, $host, $port, $path, $query, $fragment);
    }

}





/**
 * Validator for the components of a URI for a specific scheme
 */
abstract class HTMLPurifier_URIScheme
{

    /**
     * Scheme's default port (integer).  If an explicit port number is
     * specified that coincides with the default port, it will be
     * elided.
     */
    public $default_port = null;

    /**
     * Whether or not URIs of this schem are locatable by a browser
     * http and ftp are accessible, while mailto and news are not.
     */
    public $browsable = false;

    /**
     * Whether or not the URI always uses <hier_part>, resolves edge cases
     * with making relative URIs absolute
     */
    public $hierarchical = false;

    /**
     * Whether or not the URI may omit a hostname when the scheme is
     * explicitly specified, ala file:///path/to/file. As of writing,
     * 'file' is the only scheme that browsers support his properly.
     */
    public $may_omit_host = false;

    /**
     * Validates the components of a URI for a specific scheme.
     * @param $uri Reference to a HTMLPurifier_URI object
     * @param $config HTMLPurifier_Config object
     * @param $context HTMLPurifier_Context object
     * @return Bool success or failure
     */
    public abstract function doValidate(&$uri, $config, $context);

    /**
     * Public interface for validating components of a URI.  Performs a
     * bunch of default actions. Don't overload this method.
     * @param $uri Reference to a HTMLPurifier_URI object
     * @param $config HTMLPurifier_Config object
     * @param $context HTMLPurifier_Context object
     * @return Bool success or failure
     */
    public function validate(&$uri, $config, $context) {
        if ($this->default_port == $uri->port) $uri->port = null;
        // kludge: browsers do funny things when the scheme but not the
        // authority is set
        if (!$this->may_omit_host &&
            // if the scheme is present, a missing host is always in error
            (!is_null($uri->scheme) && ($uri->host === '' || is_null($uri->host))) ||
            // if the scheme is not present, a *blank* host is in error,
            // since this translates into '///path' which most browsers
            // interpret as being 'http://path'.
             (is_null($uri->scheme) && $uri->host === '')
        ) {
            do {
                if (is_null($uri->scheme)) {
                    if (substr($uri->path, 0, 2) != '//') {
                        $uri->host = null;
                        break;
                    }
                    // URI is '////path', so we cannot nullify the
                    // host to preserve semantics.  Try expanding the
                    // hostname instead (fall through)
                }
                // first see if we can manually insert a hostname
                $host = $config->get('URI.Host');
                if (!is_null($host)) {
                    $uri->host = $host;
                } else {
                    // we can't do anything sensible, reject the URL.
                    return false;
                }
            } while (false);
        }
        return $this->doValidate($uri, $config, $context);
    }

}





/**
 * Registry for retrieving specific URI scheme validator objects.
 */
class HTMLPurifier_URISchemeRegistry
{

    /**
     * Retrieve sole instance of the registry.
     * @param $prototype Optional prototype to overload sole instance with,
     *                   or bool true to reset to default registry.
     * @note Pass a registry object $prototype with a compatible interface and
     *       the function will copy it and return it all further times.
     */
    public static function instance($prototype = null) {
        static $instance = null;
        if ($prototype !== null) {
            $instance = $prototype;
        } elseif ($instance === null || $prototype == true) {
            $instance = new HTMLPurifier_URISchemeRegistry();
        }
        return $instance;
    }

    /**
     * Cache of retrieved schemes.
     */
    protected $schemes = array();

    /**
     * Retrieves a scheme validator object
     * @param $scheme String scheme name like http or mailto
     * @param $config HTMLPurifier_Config object
     * @param $config HTMLPurifier_Context object
     */
    public function getScheme($scheme, $config, $context) {
        if (!$config) $config = HTMLPurifier_Config::createDefault();

        // important, otherwise attacker could include arbitrary file
        $allowed_schemes = $config->get('URI.AllowedSchemes');
        if (!$config->get('URI.OverrideAllowedSchemes') &&
            !isset($allowed_schemes[$scheme])
        ) {
            return;
        }

        if (isset($this->schemes[$scheme])) return $this->schemes[$scheme];
        if (!isset($allowed_schemes[$scheme])) return;

        $class = 'HTMLPurifier_URIScheme_' . $scheme;
        if (!class_exists($class)) return;
        $this->schemes[$scheme] = new $class();
        return $this->schemes[$scheme];
    }

    /**
     * Registers a custom scheme to the cache, bypassing reflection.
     * @param $scheme Scheme name
     * @param $scheme_obj HTMLPurifier_URIScheme object
     */
    public function register($scheme, $scheme_obj) {
        $this->schemes[$scheme] = $scheme_obj;
    }

}





/**
 * Class for converting between different unit-lengths as specified by
 * CSS.
 */
class HTMLPurifier_UnitConverter
{

    const ENGLISH = 1;
    const METRIC = 2;
    const DIGITAL = 3;

    /**
     * Units information array. Units are grouped into measuring systems
     * (English, Metric), and are assigned an integer representing
     * the conversion factor between that unit and the smallest unit in
     * the system. Numeric indexes are actually magical constants that
     * encode conversion data from one system to the next, with a O(n^2)
     * constraint on memory (this is generally not a problem, since
     * the number of measuring systems is small.)
     */
    protected static $units = array(
        self::ENGLISH => array(
            'px' => 3, // This is as per CSS 2.1 and Firefox. Your mileage may vary
            'pt' => 4,
            'pc' => 48,
            'in' => 288,
            self::METRIC => array('pt', '0.352777778', 'mm'),
        ),
        self::METRIC => array(
            'mm' => 1,
            'cm' => 10,
            self::ENGLISH => array('mm', '2.83464567', 'pt'),
        ),
    );

    /**
     * Minimum bcmath precision for output.
     */
    protected $outputPrecision;

    /**
     * Bcmath precision for internal calculations.
     */
    protected $internalPrecision;

    /**
     * Whether or not BCMath is available
     */
    private $bcmath;

    public function __construct($output_precision = 4, $internal_precision = 10, $force_no_bcmath = false) {
        $this->outputPrecision = $output_precision;
        $this->internalPrecision = $internal_precision;
        $this->bcmath = !$force_no_bcmath && function_exists('bcmul');
    }

    /**
     * Converts a length object of one unit into another unit.
     * @param HTMLPurifier_Length $length
     *      Instance of HTMLPurifier_Length to convert. You must validate()
     *      it before passing it here!
     * @param string $to_unit
     *      Unit to convert to.
     * @note
     *      About precision: This conversion function pays very special
     *      attention to the incoming precision of values and attempts
     *      to maintain a number of significant figure. Results are
     *      fairly accurate up to nine digits. Some caveats:
     *          - If a number is zero-padded as a result of this significant
     *            figure tracking, the zeroes will be eliminated.
     *          - If a number contains less than four sigfigs ($outputPrecision)
     *            and this causes some decimals to be excluded, those
     *            decimals will be added on.
     */
    public function convert($length, $to_unit) {

        if (!$length->isValid()) return false;

        $n    = $length->getN();
        $unit = $length->getUnit();

        if ($n === '0' || $unit === false) {
            return new HTMLPurifier_Length('0', false);
        }

        $state = $dest_state = false;
        foreach (self::$units as $k => $x) {
            if (isset($x[$unit])) $state = $k;
            if (isset($x[$to_unit])) $dest_state = $k;
        }
        if (!$state || !$dest_state) return false;

        // Some calculations about the initial precision of the number;
        // this will be useful when we need to do final rounding.
        $sigfigs = $this->getSigFigs($n);
        if ($sigfigs < $this->outputPrecision) $sigfigs = $this->outputPrecision;

        // BCMath's internal precision deals only with decimals. Use
        // our default if the initial number has no decimals, or increase
        // it by how ever many decimals, thus, the number of guard digits
        // will always be greater than or equal to internalPrecision.
        $log = (int) floor(log(abs($n), 10));
        $cp = ($log < 0) ? $this->internalPrecision - $log : $this->internalPrecision; // internal precision

        for ($i = 0; $i < 2; $i++) {

            // Determine what unit IN THIS SYSTEM we need to convert to
            if ($dest_state === $state) {
                // Simple conversion
                $dest_unit = $to_unit;
            } else {
                // Convert to the smallest unit, pending a system shift
                $dest_unit = self::$units[$state][$dest_state][0];
            }

            // Do the conversion if necessary
            if ($dest_unit !== $unit) {
                $factor = $this->div(self::$units[$state][$unit], self::$units[$state][$dest_unit], $cp);
                $n = $this->mul($n, $factor, $cp);
                $unit = $dest_unit;
            }

            // Output was zero, so bail out early. Shouldn't ever happen.
            if ($n === '') {
                $n = '0';
                $unit = $to_unit;
                break;
            }

            // It was a simple conversion, so bail out
            if ($dest_state === $state) {
                break;
            }

            if ($i !== 0) {
                // Conversion failed! Apparently, the system we forwarded
                // to didn't have this unit. This should never happen!
                return false;
            }

            // Pre-condition: $i == 0

            // Perform conversion to next system of units
            $n = $this->mul($n, self::$units[$state][$dest_state][1], $cp);
            $unit = self::$units[$state][$dest_state][2];
            $state = $dest_state;

            // One more loop around to convert the unit in the new system.

        }

        // Post-condition: $unit == $to_unit
        if ($unit !== $to_unit) return false;

        // Useful for debugging:
        //echo "<pre>n";
        //echo "$n\nsigfigs = $sigfigs\nnew_log = $new_log\nlog = $log\nrp = $rp\n</pre>\n";

        $n = $this->round($n, $sigfigs);
        if (strpos($n, '.') !== false) $n = rtrim($n, '0');
        $n = rtrim($n, '.');

        return new HTMLPurifier_Length($n, $unit);
    }

    /**
     * Returns the number of significant figures in a string number.
     * @param string $n Decimal number
     * @return int number of sigfigs
     */
    public function getSigFigs($n) {
        $n = ltrim($n, '0+-');
        $dp = strpos($n, '.'); // decimal position
        if ($dp === false) {
            $sigfigs = strlen(rtrim($n, '0'));
        } else {
            $sigfigs = strlen(ltrim($n, '0.')); // eliminate extra decimal character
            if ($dp !== 0) $sigfigs--;
        }
        return $sigfigs;
    }

    /**
     * Adds two numbers, using arbitrary precision when available.
     */
    private function add($s1, $s2, $scale) {
        if ($this->bcmath) return bcadd($s1, $s2, $scale);
        else return $this->scale($s1 + $s2, $scale);
    }

    /**
     * Multiples two numbers, using arbitrary precision when available.
     */
    private function mul($s1, $s2, $scale) {
        if ($this->bcmath) return bcmul($s1, $s2, $scale);
        else return $this->scale($s1 * $s2, $scale);
    }

    /**
     * Divides two numbers, using arbitrary precision when available.
     */
    private function div($s1, $s2, $scale) {
        if ($this->bcmath) return bcdiv($s1, $s2, $scale);
        else return $this->scale($s1 / $s2, $scale);
    }

    /**
     * Rounds a number according to the number of sigfigs it should have,
     * using arbitrary precision when available.
     */
    private function round($n, $sigfigs) {
        $new_log = (int) floor(log(abs($n), 10)); // Number of digits left of decimal - 1
        $rp = $sigfigs - $new_log - 1; // Number of decimal places needed
        $neg = $n < 0 ? '-' : ''; // Negative sign
        if ($this->bcmath) {
            if ($rp >= 0) {
                $n = bcadd($n, $neg . '0.' .  str_repeat('0', $rp) . '5', $rp + 1);
                $n = bcdiv($n, '1', $rp);
            } else {
                // This algorithm partially depends on the standardized
                // form of numbers that comes out of bcmath.
                $n = bcadd($n, $neg . '5' . str_repeat('0', $new_log - $sigfigs), 0);
                $n = substr($n, 0, $sigfigs + strlen($neg)) . str_repeat('0', $new_log - $sigfigs + 1);
            }
            return $n;
        } else {
            return $this->scale(round($n, $sigfigs - $new_log - 1), $rp + 1);
        }
    }

    /**
     * Scales a float to $scale digits right of decimal point, like BCMath.
     */
    private function scale($r, $scale) {
        if ($scale < 0) {
            // The f sprintf type doesn't support negative numbers, so we
            // need to cludge things manually. First get the string.
            $r = sprintf('%.0f', (float) $r);
            // Due to floating point precision loss, $r will more than likely
            // look something like 4652999999999.9234. We grab one more digit
            // than we need to precise from $r and then use that to round
            // appropriately.
            $precise = (string) round(substr($r, 0, strlen($r) + $scale), -1);
            // Now we return it, truncating the zero that was rounded off.
            return substr($precise, 0, -1) . str_repeat('0', -$scale + 1);
        }
        return sprintf('%.' . $scale . 'f', (float) $r);
    }

}





/**
 * Parses string representations into their corresponding native PHP
 * variable type. The base implementation does a simple type-check.
 */
class HTMLPurifier_VarParser
{

    const STRING    = 1;
    const ISTRING   = 2;
    const TEXT      = 3;
    const ITEXT     = 4;
    const INT       = 5;
    const FLOAT     = 6;
    const BOOL      = 7;
    const LOOKUP    = 8;
    const ALIST     = 9;
    const HASH      = 10;
    const MIXED     = 11;

    /**
     * Lookup table of allowed types. Mainly for backwards compatibility, but
     * also convenient for transforming string type names to the integer constants.
     */
    static public $types = array(
        'string'    => self::STRING,
        'istring'   => self::ISTRING,
        'text'      => self::TEXT,
        'itext'     => self::ITEXT,
        'int'       => self::INT,
        'float'     => self::FLOAT,
        'bool'      => self::BOOL,
        'lookup'    => self::LOOKUP,
        'list'      => self::ALIST,
        'hash'      => self::HASH,
        'mixed'     => self::MIXED
    );

    /**
     * Lookup table of types that are string, and can have aliases or
     * allowed value lists.
     */
    static public $stringTypes = array(
        self::STRING    => true,
        self::ISTRING   => true,
        self::TEXT      => true,
        self::ITEXT     => true,
    );

    /**
     * Validate a variable according to type. Throws
     * HTMLPurifier_VarParserException if invalid.
     * It may return NULL as a valid type if $allow_null is true.
     *
     * @param $var Variable to validate
     * @param $type Type of variable, see HTMLPurifier_VarParser->types
     * @param $allow_null Whether or not to permit null as a value
     * @return Validated and type-coerced variable
     */
    final public function parse($var, $type, $allow_null = false) {
        if (is_string($type)) {
            if (!isset(HTMLPurifier_VarParser::$types[$type])) {
                throw new HTMLPurifier_VarParserException("Invalid type '$type'");
            } else {
                $type = HTMLPurifier_VarParser::$types[$type];
            }
        }
        $var = $this->parseImplementation($var, $type, $allow_null);
        if ($allow_null && $var === null) return null;
        // These are basic checks, to make sure nothing horribly wrong
        // happened in our implementations.
        switch ($type) {
            case (self::STRING):
            case (self::ISTRING):
            case (self::TEXT):
            case (self::ITEXT):
                if (!is_string($var)) break;
                if ($type == self::ISTRING || $type == self::ITEXT) $var = strtolower($var);
                return $var;
            case (self::INT):
                if (!is_int($var)) break;
                return $var;
            case (self::FLOAT):
                if (!is_float($var)) break;
                return $var;
            case (self::BOOL):
                if (!is_bool($var)) break;
                return $var;
            case (self::LOOKUP):
            case (self::ALIST):
            case (self::HASH):
                if (!is_array($var)) break;
                if ($type === self::LOOKUP) {
                    foreach ($var as $k) if ($k !== true) $this->error('Lookup table contains value other than true');
                } elseif ($type === self::ALIST) {
                    $keys = array_keys($var);
                    if (array_keys($keys) !== $keys) $this->error('Indices for list are not uniform');
                }
                return $var;
            case (self::MIXED):
                return $var;
            default:
                $this->errorInconsistent(get_class($this), $type);
        }
        $this->errorGeneric($var, $type);
    }

    /**
     * Actually implements the parsing. Base implementation is to not
     * do anything to $var. Subclasses should overload this!
     */
    protected function parseImplementation($var, $type, $allow_null) {
        return $var;
    }

    /**
     * Throws an exception.
     */
    protected function error($msg) {
        throw new HTMLPurifier_VarParserException($msg);
    }

    /**
     * Throws an inconsistency exception.
     * @note This should not ever be called. It would be called if we
     *       extend the allowed values of HTMLPurifier_VarParser without
     *       updating subclasses.
     */
    protected function errorInconsistent($class, $type) {
        throw new HTMLPurifier_Exception("Inconsistency in $class: ".HTMLPurifier_VarParser::getTypeName($type)." not implemented");
    }

    /**
     * Generic error for if a type didn't work.
     */
    protected function errorGeneric($var, $type) {
        $vtype = gettype($var);
        $this->error("Expected type ".HTMLPurifier_VarParser::getTypeName($type).", got $vtype");
    }

    static public function getTypeName($type) {
        static $lookup;
        if (!$lookup) {
            // Lazy load the alternative lookup table
            $lookup = array_flip(HTMLPurifier_VarParser::$types);
        }
        if (!isset($lookup[$type])) return 'unknown';
        return $lookup[$type];
    }

}





/**
 * Exception type for HTMLPurifier_VarParser
 */
class HTMLPurifier_VarParserException extends HTMLPurifier_Exception
{

}





/**
 * Validates the HTML attribute style, otherwise known as CSS.
 * @note We don't implement the whole CSS specification, so it might be
 *       difficult to reuse this component in the context of validating
 *       actual stylesheet declarations.
 * @note If we were really serious about validating the CSS, we would
 *       tokenize the styles and then parse the tokens. Obviously, we
 *       are not doing that. Doing that could seriously harm performance,
 *       but would make these components a lot more viable for a CSS
 *       filtering solution.
 */
class HTMLPurifier_AttrDef_CSS extends HTMLPurifier_AttrDef
{

    public function validate($css, $config, $context) {

        $css = $this->parseCDATA($css);

        $definition = $config->getCSSDefinition();

        // we're going to break the spec and explode by semicolons.
        // This is because semicolon rarely appears in escaped form
        // Doing this is generally flaky but fast
        // IT MIGHT APPEAR IN URIs, see HTMLPurifier_AttrDef_CSSURI
        // for details

        $declarations = explode(';', $css);
        $propvalues = array();

        /**
         * Name of the current CSS property being validated.
         */
        $property = false;
        $context->register('CurrentCSSProperty', $property);

        foreach ($declarations as $declaration) {
            if (!$declaration) continue;
            if (!strpos($declaration, ':')) continue;
            list($property, $value) = explode(':', $declaration, 2);
            $property = trim($property);
            $value    = trim($value);
            $ok = false;
            do {
                if (isset($definition->info[$property])) {
                    $ok = true;
                    break;
                }
                if (ctype_lower($property)) break;
                $property = strtolower($property);
                if (isset($definition->info[$property])) {
                    $ok = true;
                    break;
                }
            } while(0);
            if (!$ok) continue;
            // inefficient call, since the validator will do this again
            if (strtolower(trim($value)) !== 'inherit') {
                // inherit works for everything (but only on the base property)
                $result = $definition->info[$property]->validate(
                    $value, $config, $context );
            } else {
                $result = 'inherit';
            }
            if ($result === false) continue;
            $propvalues[$property] = $result;
        }

        $context->destroy('CurrentCSSProperty');

        // procedure does not write the new CSS simultaneously, so it's
        // slightly inefficient, but it's the only way of getting rid of
        // duplicates. Perhaps config to optimize it, but not now.

        $new_declarations = '';
        foreach ($propvalues as $prop => $value) {
            $new_declarations .= "$prop:$value;";
        }

        return $new_declarations ? $new_declarations : false;

    }

}





// Enum = Enumerated
/**
 * Validates a keyword against a list of valid values.
 * @warning The case-insensitive compare of this function uses PHP's
 *          built-in strtolower and ctype_lower functions, which may
 *          cause problems with international comparisons
 */
class HTMLPurifier_AttrDef_Enum extends HTMLPurifier_AttrDef
{

    /**
     * Lookup table of valid values.
     * @todo Make protected
     */
    public $valid_values   = array();

    /**
     * Bool indicating whether or not enumeration is case sensitive.
     * @note In general this is always case insensitive.
     */
    protected $case_sensitive = false; // values according to W3C spec

    /**
     * @param $valid_values List of valid values
     * @param $case_sensitive Bool indicating whether or not case sensitive
     */
    public function __construct(
        $valid_values = array(), $case_sensitive = false
    ) {
        $this->valid_values = array_flip($valid_values);
        $this->case_sensitive = $case_sensitive;
    }

    public function validate($string, $config, $context) {
        $string = trim($string);
        if (!$this->case_sensitive) {
            // we may want to do full case-insensitive libraries
            $string = ctype_lower($string) ? $string : strtolower($string);
        }
        $result = isset($this->valid_values[$string]);

        return $result ? $string : false;
    }

    /**
     * @param $string In form of comma-delimited list of case-insensitive
     *      valid values. Example: "foo,bar,baz". Prepend "s:" to make
     *      case sensitive
     */
    public function make($string) {
        if (strlen($string) > 2 && $string[0] == 's' && $string[1] == ':') {
            $string = substr($string, 2);
            $sensitive = true;
        } else {
            $sensitive = false;
        }
        $values = explode(',', $string);
        return new HTMLPurifier_AttrDef_Enum($values, $sensitive);
    }

}





/**
 * Validates an integer.
 * @note While this class was modeled off the CSS definition, no currently
 *       allowed CSS uses this type.  The properties that do are: widows,
 *       orphans, z-index, counter-increment, counter-reset.  Some of the
 *       HTML attributes, however, find use for a non-negative version of this.
 */
class HTMLPurifier_AttrDef_Integer extends HTMLPurifier_AttrDef
{

    /**
     * Bool indicating whether or not negative values are allowed
     */
    protected $negative = true;

    /**
     * Bool indicating whether or not zero is allowed
     */
    protected $zero = true;

    /**
     * Bool indicating whether or not positive values are allowed
     */
    protected $positive = true;

    /**
     * @param $negative Bool indicating whether or not negative values are allowed
     * @param $zero Bool indicating whether or not zero is allowed
     * @param $positive Bool indicating whether or not positive values are allowed
     */
    public function __construct(
        $negative = true, $zero = true, $positive = true
    ) {
        $this->negative = $negative;
        $this->zero     = $zero;
        $this->positive = $positive;
    }

    public function validate($integer, $config, $context) {

        $integer = $this->parseCDATA($integer);
        if ($integer === '') return false;

        // we could possibly simply typecast it to integer, but there are
        // certain fringe cases that must not return an integer.

        // clip leading sign
        if ( $this->negative && $integer[0] === '-' ) {
            $digits = substr($integer, 1);
            if ($digits === '0') $integer = '0'; // rm minus sign for zero
        } elseif( $this->positive && $integer[0] === '+' ) {
            $digits = $integer = substr($integer, 1); // rm unnecessary plus
        } else {
            $digits = $integer;
        }

        // test if it's numeric
        if (!ctype_digit($digits)) return false;

        // perform scope tests
        if (!$this->zero     && $integer == 0) return false;
        if (!$this->positive && $integer > 0) return false;
        if (!$this->negative && $integer < 0) return false;

        return $integer;

    }

}





/**
 * Validates the HTML attribute lang, effectively a language code.
 * @note Built according to RFC 3066, which obsoleted RFC 1766
 */
class HTMLPurifier_AttrDef_Lang extends HTMLPurifier_AttrDef
{

    public function validate($string, $config, $context) {

        $string = trim($string);
        if (!$string) return false;

        $subtags = explode('-', $string);
        $num_subtags = count($subtags);

        if ($num_subtags == 0) return false; // sanity check

        // process primary subtag : $subtags[0]
        $length = strlen($subtags[0]);
        switch ($length) {
            case 0:
                return false;
            case 1:
                if (! ($subtags[0] == 'x' || $subtags[0] == 'i') ) {
                    return false;
                }
                break;
            case 2:
            case 3:
                if (! ctype_alpha($subtags[0]) ) {
                    return false;
                } elseif (! ctype_lower($subtags[0]) ) {
                    $subtags[0] = strtolower($subtags[0]);
                }
                break;
            default:
                return false;
        }

        $new_string = $subtags[0];
        if ($num_subtags == 1) return $new_string;

        // process second subtag : $subtags[1]
        $length = strlen($subtags[1]);
        if ($length == 0 || ($length == 1 && $subtags[1] != 'x') || $length > 8 || !ctype_alnum($subtags[1])) {
            return $new_string;
        }
        if (!ctype_lower($subtags[1])) $subtags[1] = strtolower($subtags[1]);

        $new_string .= '-' . $subtags[1];
        if ($num_subtags == 2) return $new_string;

        // process all other subtags, index 2 and up
        for ($i = 2; $i < $num_subtags; $i++) {
            $length = strlen($subtags[$i]);
            if ($length == 0 || $length > 8 || !ctype_alnum($subtags[$i])) {
                return $new_string;
            }
            if (!ctype_lower($subtags[$i])) {
                $subtags[$i] = strtolower($subtags[$i]);
            }
            $new_string .= '-' . $subtags[$i];
        }

        return $new_string;

    }

}





/**
 * Decorator that, depending on a token, switches between two definitions.
 */
class HTMLPurifier_AttrDef_Switch
{

    protected $tag;
    protected $withTag, $withoutTag;

    /**
     * @param string $tag Tag name to switch upon
     * @param HTMLPurifier_AttrDef $with_tag Call if token matches tag
     * @param HTMLPurifier_AttrDef $without_tag Call if token doesn't match, or there is no token
     */
    public function __construct($tag, $with_tag, $without_tag) {
        $this->tag = $tag;
        $this->withTag = $with_tag;
        $this->withoutTag = $without_tag;
    }

    public function validate($string, $config, $context) {
        $token = $context->get('CurrentToken', true);
        if (!$token || $token->name !== $this->tag) {
            return $this->withoutTag->validate($string, $config, $context);
        } else {
            return $this->withTag->validate($string, $config, $context);
        }
    }

}





/**
 * Validates arbitrary text according to the HTML spec.
 */
class HTMLPurifier_AttrDef_Text extends HTMLPurifier_AttrDef
{

    public function validate($string, $config, $context) {
        return $this->parseCDATA($string);
    }

}





/**
 * Validates a URI as defined by RFC 3986.
 * @note Scheme-specific mechanics deferred to HTMLPurifier_URIScheme
 */
class HTMLPurifier_AttrDef_URI extends HTMLPurifier_AttrDef
{

    protected $parser;
    protected $embedsResource;

    /**
     * @param $embeds_resource_resource Does the URI here result in an extra HTTP request?
     */
    public function __construct($embeds_resource = false) {
        $this->parser = new HTMLPurifier_URIParser();
        $this->embedsResource = (bool) $embeds_resource;
    }

    public function make($string) {
        $embeds = (bool) $string;
        return new HTMLPurifier_AttrDef_URI($embeds);
    }

    public function validate($uri, $config, $context) {

        if ($config->get('URI.Disable')) return false;

        $uri = $this->parseCDATA($uri);

        // parse the URI
        $uri = $this->parser->parse($uri);
        if ($uri === false) return false;

        // add embedded flag to context for validators
        $context->register('EmbeddedURI', $this->embedsResource);

        $ok = false;
        do {

            // generic validation
            $result = $uri->validate($config, $context);
            if (!$result) break;

            // chained filtering
            $uri_def = $config->getDefinition('URI');
            $result = $uri_def->filter($uri, $config, $context);
            if (!$result) break;

            // scheme-specific validation
            $scheme_obj = $uri->getSchemeObj($config, $context);
            if (!$scheme_obj) break;
            if ($this->embedsResource && !$scheme_obj->browsable) break;
            $result = $scheme_obj->validate($uri, $config, $context);
            if (!$result) break;

            // Post chained filtering
            $result = $uri_def->postFilter($uri, $config, $context);
            if (!$result) break;

            // survived gauntlet
            $ok = true;

        } while (false);

        $context->destroy('EmbeddedURI');
        if (!$ok) return false;

        // back to string
        return $uri->toString();

    }

}





/**
 * Validates a number as defined by the CSS spec.
 */
class HTMLPurifier_AttrDef_CSS_Number extends HTMLPurifier_AttrDef
{

    /**
     * Bool indicating whether or not only positive values allowed.
     */
    protected $non_negative = false;

    /**
     * @param $non_negative Bool indicating whether negatives are forbidden
     */
    public function __construct($non_negative = false) {
        $this->non_negative = $non_negative;
    }

    /**
     * @warning Some contexts do not pass $config, $context. These
     *          variables should not be used without checking HTMLPurifier_Length
     */
    public function validate($number, $config, $context) {

        $number = $this->parseCDATA($number);

        if ($number === '') return false;
        if ($number === '0') return '0';

        $sign = '';
        switch ($number[0]) {
            case '-':
                if ($this->non_negative) return false;
                $sign = '-';
            case '+':
                $number = substr($number, 1);
        }

        if (ctype_digit($number)) {
            $number = ltrim($number, '0');
            return $number ? $sign . $number : '0';
        }

        // Period is the only non-numeric character allowed
        if (strpos($number, '.') === false) return false;

        list($left, $right) = explode('.', $number, 2);

        if ($left === '' && $right === '') return false;
        if ($left !== '' && !ctype_digit($left)) return false;

        $left  = ltrim($left,  '0');
        $right = rtrim($right, '0');

        if ($right === '') {
            return $left ? $sign . $left : '0';
        } elseif (!ctype_digit($right)) {
            return false;
        }

        return $sign . $left . '.' . $right;

    }

}





class HTMLPurifier_AttrDef_CSS_AlphaValue extends HTMLPurifier_AttrDef_CSS_Number
{

    public function __construct() {
        parent::__construct(false); // opacity is non-negative, but we will clamp it
    }

    public function validate($number, $config, $context) {
        $result = parent::validate($number, $config, $context);
        if ($result === false) return $result;
        $float = (float) $result;
        if ($float < 0.0) $result = '0';
        if ($float > 1.0) $result = '1';
        return $result;
    }

}





/**
 * Validates shorthand CSS property background.
 * @warning Does not support url tokens that have internal spaces.
 */
class HTMLPurifier_AttrDef_CSS_Background extends HTMLPurifier_AttrDef
{

    /**
     * Local copy of component validators.
     * @note See HTMLPurifier_AttrDef_Font::$info for a similar impl.
     */
    protected $info;

    public function __construct($config) {
        $def = $config->getCSSDefinition();
        $this->info['background-color'] = $def->info['background-color'];
        $this->info['background-image'] = $def->info['background-image'];
        $this->info['background-repeat'] = $def->info['background-repeat'];
        $this->info['background-attachment'] = $def->info['background-attachment'];
        $this->info['background-position'] = $def->info['background-position'];
    }

    public function validate($string, $config, $context) {

        // regular pre-processing
        $string = $this->parseCDATA($string);
        if ($string === '') return false;

        // munge rgb() decl if necessary
        $string = $this->mungeRgb($string);

        // assumes URI doesn't have spaces in it
        $bits = explode(' ', strtolower($string)); // bits to process

        $caught = array();
        $caught['color']    = false;
        $caught['image']    = false;
        $caught['repeat']   = false;
        $caught['attachment'] = false;
        $caught['position'] = false;

        $i = 0; // number of catches
        $none = false;

        foreach ($bits as $bit) {
            if ($bit === '') continue;
            foreach ($caught as $key => $status) {
                if ($key != 'position') {
                    if ($status !== false) continue;
                    $r = $this->info['background-' . $key]->validate($bit, $config, $context);
                } else {
                    $r = $bit;
                }
                if ($r === false) continue;
                if ($key == 'position') {
                    if ($caught[$key] === false) $caught[$key] = '';
                    $caught[$key] .= $r . ' ';
                } else {
                    $caught[$key] = $r;
                }
                $i++;
                break;
            }
        }

        if (!$i) return false;
        if ($caught['position'] !== false) {
            $caught['position'] = $this->info['background-position']->
                validate($caught['position'], $config, $context);
        }

        $ret = array();
        foreach ($caught as $value) {
            if ($value === false) continue;
            $ret[] = $value;
        }

        if (empty($ret)) return false;
        return implode(' ', $ret);

    }

}





/* W3C says:
    [ // adjective and number must be in correct order, even if
      // you could switch them without introducing ambiguity.
      // some browsers support that syntax
        [
            <percentage> | <length> | left | center | right
        ]
        [
            <percentage> | <length> | top | center | bottom
        ]?
    ] |
    [ // this signifies that the vertical and horizontal adjectives
      // can be arbitrarily ordered, however, there can only be two,
      // one of each, or none at all
        [
            left | center | right
        ] ||
        [
            top | center | bottom
        ]
    ]
    top, left = 0%
    center, (none) = 50%
    bottom, right = 100%
*/

/* QuirksMode says:
    keyword + length/percentage must be ordered correctly, as per W3C

    Internet Explorer and Opera, however, support arbitrary ordering. We
    should fix it up.

    Minor issue though, not strictly necessary.
*/

// control freaks may appreciate the ability to convert these to
// percentages or something, but it's not necessary

/**
 * Validates the value of background-position.
 */
class HTMLPurifier_AttrDef_CSS_BackgroundPosition extends HTMLPurifier_AttrDef
{

    protected $length;
    protected $percentage;

    public function __construct() {
        $this->length     = new HTMLPurifier_AttrDef_CSS_Length();
        $this->percentage = new HTMLPurifier_AttrDef_CSS_Percentage();
    }

    public function validate($string, $config, $context) {
        $string = $this->parseCDATA($string);
        $bits = explode(' ', $string);

        $keywords = array();
        $keywords['h'] = false; // left, right
        $keywords['v'] = false; // top, bottom
        $keywords['ch'] = false; // center (first word)
        $keywords['cv'] = false; // center (second word)
        $measures = array();

        $i = 0;

        $lookup = array(
            'top' => 'v',
            'bottom' => 'v',
            'left' => 'h',
            'right' => 'h',
            'center' => 'c'
        );

        foreach ($bits as $bit) {
            if ($bit === '') continue;

            // test for keyword
            $lbit = ctype_lower($bit) ? $bit : strtolower($bit);
            if (isset($lookup[$lbit])) {
                $status = $lookup[$lbit];
                if ($status == 'c') {
                    if ($i == 0) {
                        $status = 'ch';
                    } else {
                        $status = 'cv';
                    }
                }
                $keywords[$status] = $lbit;
                $i++;
            }

            // test for length
            $r = $this->length->validate($bit, $config, $context);
            if ($r !== false) {
                $measures[] = $r;
                $i++;
            }

            // test for percentage
            $r = $this->percentage->validate($bit, $config, $context);
            if ($r !== false) {
                $measures[] = $r;
                $i++;
            }

        }

        if (!$i) return false; // no valid values were caught

        $ret = array();

        // first keyword
        if     ($keywords['h'])     $ret[] = $keywords['h'];
        elseif ($keywords['ch']) {
            $ret[] = $keywords['ch'];
            $keywords['cv'] = false; // prevent re-use: center = center center
        }
        elseif (count($measures))   $ret[] = array_shift($measures);

        if     ($keywords['v'])     $ret[] = $keywords['v'];
        elseif ($keywords['cv'])    $ret[] = $keywords['cv'];
        elseif (count($measures))   $ret[] = array_shift($measures);

        if (empty($ret)) return false;
        return implode(' ', $ret);

    }

}





/**
 * Validates the border property as defined by CSS.
 */
class HTMLPurifier_AttrDef_CSS_Border extends HTMLPurifier_AttrDef
{

    /**
     * Local copy of properties this property is shorthand for.
     */
    protected $info = array();

    public function __construct($config) {
        $def = $config->getCSSDefinition();
        $this->info['border-width'] = $def->info['border-width'];
        $this->info['border-style'] = $def->info['border-style'];
        $this->info['border-top-color'] = $def->info['border-top-color'];
    }

    public function validate($string, $config, $context) {
        $string = $this->parseCDATA($string);
        $string = $this->mungeRgb($string);
        $bits = explode(' ', $string);
        $done = array(); // segments we've finished
        $ret = ''; // return value
        foreach ($bits as $bit) {
            foreach ($this->info as $propname => $validator) {
                if (isset($done[$propname])) continue;
                $r = $validator->validate($bit, $config, $context);
                if ($r !== false) {
                    $ret .= $r . ' ';
                    $done[$propname] = true;
                    break;
                }
            }
        }
        return rtrim($ret);
    }

}





/**
 * Validates Color as defined by CSS.
 */
class HTMLPurifier_AttrDef_CSS_Color extends HTMLPurifier_AttrDef
{

    public function validate($color, $config, $context) {

        static $colors = null;
        if ($colors === null) $colors = $config->get('Core.ColorKeywords');

        $color = trim($color);
        if ($color === '') return false;

        $lower = strtolower($color);
        if (isset($colors[$lower])) return $colors[$lower];

        if (strpos($color, 'rgb(') !== false) {
            // rgb literal handling
            $length = strlen($color);
            if (strpos($color, ')') !== $length - 1) return false;
            $triad = substr($color, 4, $length - 4 - 1);
            $parts = explode(',', $triad);
            if (count($parts) !== 3) return false;
            $type = false; // to ensure that they're all the same type
            $new_parts = array();
            foreach ($parts as $part) {
                $part = trim($part);
                if ($part === '') return false;
                $length = strlen($part);
                if ($part[$length - 1] === '%') {
                    // handle percents
                    if (!$type) {
                        $type = 'percentage';
                    } elseif ($type !== 'percentage') {
                        return false;
                    }
                    $num = (float) substr($part, 0, $length - 1);
                    if ($num < 0) $num = 0;
                    if ($num > 100) $num = 100;
                    $new_parts[] = "$num%";
                } else {
                    // handle integers
                    if (!$type) {
                        $type = 'integer';
                    } elseif ($type !== 'integer') {
                        return false;
                    }
                    $num = (int) $part;
                    if ($num < 0) $num = 0;
                    if ($num > 255) $num = 255;
                    $new_parts[] = (string) $num;
                }
            }
            $new_triad = implode(',', $new_parts);
            $color = "rgb($new_triad)";
        } else {
            // hexadecimal handling
            if ($color[0] === '#') {
                $hex = substr($color, 1);
            } else {
                $hex = $color;
                $color = '#' . $color;
            }
            $length = strlen($hex);
            if ($length !== 3 && $length !== 6) return false;
            if (!ctype_xdigit($hex)) return false;
        }

        return $color;

    }

}





/**
 * Allows multiple validators to attempt to validate attribute.
 *
 * Composite is just what it sounds like: a composite of many validators.
 * This means that multiple HTMLPurifier_AttrDef objects will have a whack
 * at the string.  If one of them passes, that's what is returned.  This is
 * especially useful for CSS values, which often are a choice between
 * an enumerated set of predefined values or a flexible data type.
 */
class HTMLPurifier_AttrDef_CSS_Composite extends HTMLPurifier_AttrDef
{

    /**
     * List of HTMLPurifier_AttrDef objects that may process strings
     * @todo Make protected
     */
    public $defs;

    /**
     * @param $defs List of HTMLPurifier_AttrDef objects
     */
    public function __construct($defs) {
        $this->defs = $defs;
    }

    public function validate($string, $config, $context) {
        foreach ($this->defs as $i => $def) {
            $result = $this->defs[$i]->validate($string, $config, $context);
            if ($result !== false) return $result;
        }
        return false;
    }

}





/**
 * Decorator which enables CSS properties to be disabled for specific elements.
 */
class HTMLPurifier_AttrDef_CSS_DenyElementDecorator extends HTMLPurifier_AttrDef
{
    public $def, $element;

    /**
     * @param $def Definition to wrap
     * @param $element Element to deny
     */
    public function __construct($def, $element) {
        $this->def = $def;
        $this->element = $element;
    }
    /**
     * Checks if CurrentToken is set and equal to $this->element
     */
    public function validate($string, $config, $context) {
        $token = $context->get('CurrentToken', true);
        if ($token && $token->name == $this->element) return false;
        return $this->def->validate($string, $config, $context);
    }
}





/**
 * Microsoft's proprietary filter: CSS property
 * @note Currently supports the alpha filter. In the future, this will
 *       probably need an extensible framework
 */
class HTMLPurifier_AttrDef_CSS_Filter extends HTMLPurifier_AttrDef
{

    protected $intValidator;

    public function __construct() {
        $this->intValidator = new HTMLPurifier_AttrDef_Integer();
    }

    public function validate($value, $config, $context) {
        $value = $this->parseCDATA($value);
        if ($value === 'none') return $value;
        // if we looped this we could support multiple filters
        $function_length = strcspn($value, '(');
        $function = trim(substr($value, 0, $function_length));
        if ($function !== 'alpha' &&
            $function !== 'Alpha' &&
            $function !== 'progid:DXImageTransform.Microsoft.Alpha'
            ) return false;
        $cursor = $function_length + 1;
        $parameters_length = strcspn($value, ')', $cursor);
        $parameters = substr($value, $cursor, $parameters_length);
        $params = explode(',', $parameters);
        $ret_params = array();
        $lookup = array();
        foreach ($params as $param) {
            list($key, $value) = explode('=', $param);
            $key   = trim($key);
            $value = trim($value);
            if (isset($lookup[$key])) continue;
            if ($key !== 'opacity') continue;
            $value = $this->intValidator->validate($value, $config, $context);
            if ($value === false) continue;
            $int = (int) $value;
            if ($int > 100) $value = '100';
            if ($int < 0) $value = '0';
            $ret_params[] = "$key=$value";
            $lookup[$key] = true;
        }
        $ret_parameters = implode(',', $ret_params);
        $ret_function = "$function($ret_parameters)";
        return $ret_function;
    }

}





/**
 * Validates shorthand CSS property font.
 */
class HTMLPurifier_AttrDef_CSS_Font extends HTMLPurifier_AttrDef
{

    /**
     * Local copy of component validators.
     *
     * @note If we moved specific CSS property definitions to their own
     *       classes instead of having them be assembled at run time by
     *       CSSDefinition, this wouldn't be necessary.  We'd instantiate
     *       our own copies.
     */
    protected $info = array();

    public function __construct($config) {
        $def = $config->getCSSDefinition();
        $this->info['font-style']   = $def->info['font-style'];
        $this->info['font-variant'] = $def->info['font-variant'];
        $this->info['font-weight']  = $def->info['font-weight'];
        $this->info['font-size']    = $def->info['font-size'];
        $this->info['line-height']  = $def->info['line-height'];
        $this->info['font-family']  = $def->info['font-family'];
    }

    public function validate($string, $config, $context) {

        static $system_fonts = array(
            'caption' => true,
            'icon' => true,
            'menu' => true,
            'message-box' => true,
            'small-caption' => true,
            'status-bar' => true
        );

        // regular pre-processing
        $string = $this->parseCDATA($string);
        if ($string === '') return false;

        // check if it's one of the keywords
        $lowercase_string = strtolower($string);
        if (isset($system_fonts[$lowercase_string])) {
            return $lowercase_string;
        }

        $bits = explode(' ', $string); // bits to process
        $stage = 0; // this indicates what we're looking for
        $caught = array(); // which stage 0 properties have we caught?
        $stage_1 = array('font-style', 'font-variant', 'font-weight');
        $final = ''; // output

        for ($i = 0, $size = count($bits); $i < $size; $i++) {
            if ($bits[$i] === '') continue;
            switch ($stage) {

                // attempting to catch font-style, font-variant or font-weight
                case 0:
                    foreach ($stage_1 as $validator_name) {
                        if (isset($caught[$validator_name])) continue;
                        $r = $this->info[$validator_name]->validate(
                                                $bits[$i], $config, $context);
                        if ($r !== false) {
                            $final .= $r . ' ';
                            $caught[$validator_name] = true;
                            break;
                        }
                    }
                    // all three caught, continue on
                    if (count($caught) >= 3) $stage = 1;
                    if ($r !== false) break;

                // attempting to catch font-size and perhaps line-height
                case 1:
                    $found_slash = false;
                    if (strpos($bits[$i], '/') !== false) {
                        list($font_size, $line_height) =
                                                    explode('/', $bits[$i]);
                        if ($line_height === '') {
                            // ooh, there's a space after the slash!
                            $line_height = false;
                            $found_slash = true;
                        }
                    } else {
                        $font_size = $bits[$i];
                        $line_height = false;
                    }
                    $r = $this->info['font-size']->validate(
                                              $font_size, $config, $context);
                    if ($r !== false) {
                        $final .= $r;
                        // attempt to catch line-height
                        if ($line_height === false) {
                            // we need to scroll forward
                            for ($j = $i + 1; $j < $size; $j++) {
                                if ($bits[$j] === '') continue;
                                if ($bits[$j] === '/') {
                                    if ($found_slash) {
                                        return false;
                                    } else {
                                        $found_slash = true;
                                        continue;
                                    }
                                }
                                $line_height = $bits[$j];
                                break;
                            }
                        } else {
                            // slash already found
                            $found_slash = true;
                            $j = $i;
                        }
                        if ($found_slash) {
                            $i = $j;
                            $r = $this->info['line-height']->validate(
                                              $line_height, $config, $context);
                            if ($r !== false) {
                                $final .= '/' . $r;
                            }
                        }
                        $final .= ' ';
                        $stage = 2;
                        break;
                    }
                    return false;

                // attempting to catch font-family
                case 2:
                    $font_family =
                        implode(' ', array_slice($bits, $i, $size - $i));
                    $r = $this->info['font-family']->validate(
                                              $font_family, $config, $context);
                    if ($r !== false) {
                        $final .= $r . ' ';
                        // processing completed successfully
                        return rtrim($final);
                    }
                    return false;
            }
        }
        return false;
    }

}





/**
 * Validates a font family list according to CSS spec
 */
class HTMLPurifier_AttrDef_CSS_FontFamily extends HTMLPurifier_AttrDef
{

    protected $mask = null;

    public function __construct() {
        $this->mask = '- ';
        for ($c = 'a'; $c <= 'z'; $c++) $this->mask .= $c;
        for ($c = 'A'; $c <= 'Z'; $c++) $this->mask .= $c;
        for ($c = '0'; $c <= '9'; $c++) $this->mask .= $c; // cast-y, but should be fine
        // special bytes used by UTF-8
        for ($i = 0x80; $i <= 0xFF; $i++) {
            // We don't bother excluding invalid bytes in this range,
            // because the our restriction of well-formed UTF-8 will
            // prevent these from ever occurring.
            $this->mask .= chr($i);
        }

        /*
            PHP's internal strcspn implementation is
            O(length of string * length of mask), making it inefficient
            for large masks.  However, it's still faster than
            preg_match 8)
          for (p = s1;;) {
            spanp = s2;
            do {
              if (*spanp == c || p == s1_end) {
                return p - s1;
              }
            } while (spanp++ < (s2_end - 1));
            c = *++p;
          }
         */
        // possible optimization: invert the mask.
    }

    public function validate($string, $config, $context) {
        static $generic_names = array(
            'serif' => true,
            'sans-serif' => true,
            'monospace' => true,
            'fantasy' => true,
            'cursive' => true
        );
        $allowed_fonts = $config->get('CSS.AllowedFonts');

        // assume that no font names contain commas in them
        $fonts = explode(',', $string);
        $final = '';
        foreach($fonts as $font) {
            $font = trim($font);
            if ($font === '') continue;
            // match a generic name
            if (isset($generic_names[$font])) {
                if ($allowed_fonts === null || isset($allowed_fonts[$font])) {
                    $final .= $font . ', ';
                }
                continue;
            }
            // match a quoted name
            if ($font[0] === '"' || $font[0] === "'") {
                $length = strlen($font);
                if ($length <= 2) continue;
                $quote = $font[0];
                if ($font[$length - 1] !== $quote) continue;
                $font = substr($font, 1, $length - 2);
            }

            $font = $this->expandCSSEscape($font);

            // $font is a pure representation of the font name

            if ($allowed_fonts !== null && !isset($allowed_fonts[$font])) {
                continue;
            }

            if (ctype_alnum($font) && $font !== '') {
                // very simple font, allow it in unharmed
                $final .= $font . ', ';
                continue;
            }

            // bugger out on whitespace.  form feed (0C) really
            // shouldn't show up regardless
            $font = str_replace(array("\n", "\t", "\r", "\x0C"), ' ', $font);

            // Here, there are various classes of characters which need
            // to be treated differently:
            //  - Alphanumeric characters are essentially safe.  We
            //    handled these above.
            //  - Spaces require quoting, though most parsers will do
            //    the right thing if there aren't any characters that
            //    can be misinterpreted
            //  - Dashes rarely occur, but they fairly unproblematic
            //    for parsing/rendering purposes.
            //  The above characters cover the majority of Western font
            //  names.
            //  - Arbitrary Unicode characters not in ASCII.  Because
            //    most parsers give little thought to Unicode, treatment
            //    of these codepoints is basically uniform, even for
            //    punctuation-like codepoints.  These characters can
            //    show up in non-Western pages and are supported by most
            //    major browsers, for example: "ＭＳ 明朝" is a
            //    legitimate font-name
            //    <http://ja.wikipedia.org/wiki/MS_明朝>.  See
            //    the CSS3 spec for more examples:
            //    <http://www.w3.org/TR/2011/WD-css3-fonts-20110324/localizedfamilynames.png>
            //    You can see live samples of these on the Internet:
            //    <http://www.google.co.jp/search?q=font-family+ＭＳ+明朝|ゴシック>
            //    However, most of these fonts have ASCII equivalents:
            //    for example, 'MS Mincho', and it's considered
            //    professional to use ASCII font names instead of
            //    Unicode font names.  Thanks Takeshi Terada for
            //    providing this information.
            //  The following characters, to my knowledge, have not been
            //  used to name font names.
            //  - Single quote.  While theoretically you might find a
            //    font name that has a single quote in its name (serving
            //    as an apostrophe, e.g. Dave's Scribble), I haven't
            //    been able to find any actual examples of this.
            //    Internet Explorer's cssText translation (which I
            //    believe is invoked by innerHTML) normalizes any
            //    quoting to single quotes, and fails to escape single
            //    quotes.  (Note that this is not IE's behavior for all
            //    CSS properties, just some sort of special casing for
            //    font-family).  So a single quote *cannot* be used
            //    safely in the font-family context if there will be an
            //    innerHTML/cssText translation.  Note that Firefox 3.x
            //    does this too.
            //  - Double quote.  In IE, these get normalized to
            //    single-quotes, no matter what the encoding.  (Fun
            //    fact, in IE8, the 'content' CSS property gained
            //    support, where they special cased to preserve encoded
            //    double quotes, but still translate unadorned double
            //    quotes into single quotes.)  So, because their
            //    fixpoint behavior is identical to single quotes, they
            //    cannot be allowed either.  Firefox 3.x displays
            //    single-quote style behavior.
            //  - Backslashes are reduced by one (so \\ -> \) every
            //    iteration, so they cannot be used safely.  This shows
            //    up in IE7, IE8 and FF3
            //  - Semicolons, commas and backticks are handled properly.
            //  - The rest of the ASCII punctuation is handled properly.
            // We haven't checked what browsers do to unadorned
            // versions, but this is not important as long as the
            // browser doesn't /remove/ surrounding quotes (as IE does
            // for HTML).
            //
            // With these results in hand, we conclude that there are
            // various levels of safety:
            //  - Paranoid: alphanumeric, spaces and dashes(?)
            //  - International: Paranoid + non-ASCII Unicode
            //  - Edgy: Everything except quotes, backslashes
            //  - NoJS: Standards compliance, e.g. sod IE. Note that
            //    with some judicious character escaping (since certain
            //    types of escaping doesn't work) this is theoretically
            //    OK as long as innerHTML/cssText is not called.
            // We believe that international is a reasonable default
            // (that we will implement now), and once we do more
            // extensive research, we may feel comfortable with dropping
            // it down to edgy.

            // Edgy: alphanumeric, spaces, dashes and Unicode.  Use of
            // str(c)spn assumes that the string was already well formed
            // Unicode (which of course it is).
            if (strspn($font, $this->mask) !== strlen($font)) {
                continue;
            }

            // Historical:
            // In the absence of innerHTML/cssText, these ugly
            // transforms don't pose a security risk (as \\ and \"
            // might--these escapes are not supported by most browsers).
            // We could try to be clever and use single-quote wrapping
            // when there is a double quote present, but I have choosen
            // not to implement that.  (NOTE: you can reduce the amount
            // of escapes by one depending on what quoting style you use)
            // $font = str_replace('\\', '\\5C ', $font);
            // $font = str_replace('"',  '\\22 ', $font);
            // $font = str_replace("'",  '\\27 ', $font);

            // font possibly with spaces, requires quoting
            $final .= "'$font', ";
        }
        $final = rtrim($final, ', ');
        if ($final === '') return false;
        return $final;
    }

}





/**
 * Decorator which enables !important to be used in CSS values.
 */
class HTMLPurifier_AttrDef_CSS_ImportantDecorator extends HTMLPurifier_AttrDef
{
    public $def, $allow;

    /**
     * @param $def Definition to wrap
     * @param $allow Whether or not to allow !important
     */
    public function __construct($def, $allow = false) {
        $this->def = $def;
        $this->allow = $allow;
    }
    /**
     * Intercepts and removes !important if necessary
     */
    public function validate($string, $config, $context) {
        // test for ! and important tokens
        $string = trim($string);
        $is_important = false;
        // :TODO: optimization: test directly for !important and ! important
        if (strlen($string) >= 9 && substr($string, -9) === 'important') {
            $temp = rtrim(substr($string, 0, -9));
            // use a temp, because we might want to restore important
            if (strlen($temp) >= 1 && substr($temp, -1) === '!') {
                $string = rtrim(substr($temp, 0, -1));
                $is_important = true;
            }
        }
        $string = $this->def->validate($string, $config, $context);
        if ($this->allow && $is_important) $string .= ' !important';
        return $string;
    }
}





/**
 * Represents a Length as defined by CSS.
 */
class HTMLPurifier_AttrDef_CSS_Length extends HTMLPurifier_AttrDef
{

    protected $min, $max;

    /**
     * @param HTMLPurifier_Length $max Minimum length, or null for no bound. String is also acceptable.
     * @param HTMLPurifier_Length $max Maximum length, or null for no bound. String is also acceptable.
     */
    public function __construct($min = null, $max = null) {
        $this->min = $min !== null ? HTMLPurifier_Length::make($min) : null;
        $this->max = $max !== null ? HTMLPurifier_Length::make($max) : null;
    }

    public function validate($string, $config, $context) {
        $string = $this->parseCDATA($string);

        // Optimizations
        if ($string === '') return false;
        if ($string === '0') return '0';
        if (strlen($string) === 1) return false;

        $length = HTMLPurifier_Length::make($string);
        if (!$length->isValid()) return false;

        if ($this->min) {
            $c = $length->compareTo($this->min);
            if ($c === false) return false;
            if ($c < 0) return false;
        }
        if ($this->max) {
            $c = $length->compareTo($this->max);
            if ($c === false) return false;
            if ($c > 0) return false;
        }

        return $length->toString();
    }

}





/**
 * Validates shorthand CSS property list-style.
 * @warning Does not support url tokens that have internal spaces.
 */
class HTMLPurifier_AttrDef_CSS_ListStyle extends HTMLPurifier_AttrDef
{

    /**
     * Local copy of component validators.
     * @note See HTMLPurifier_AttrDef_CSS_Font::$info for a similar impl.
     */
    protected $info;

    public function __construct($config) {
        $def = $config->getCSSDefinition();
        $this->info['list-style-type']     = $def->info['list-style-type'];
        $this->info['list-style-position'] = $def->info['list-style-position'];
        $this->info['list-style-image'] = $def->info['list-style-image'];
    }

    public function validate($string, $config, $context) {

        // regular pre-processing
        $string = $this->parseCDATA($string);
        if ($string === '') return false;

        // assumes URI doesn't have spaces in it
        $bits = explode(' ', strtolower($string)); // bits to process

        $caught = array();
        $caught['type']     = false;
        $caught['position'] = false;
        $caught['image']    = false;

        $i = 0; // number of catches
        $none = false;

        foreach ($bits as $bit) {
            if ($i >= 3) return; // optimization bit
            if ($bit === '') continue;
            foreach ($caught as $key => $status) {
                if ($status !== false) continue;
                $r = $this->info['list-style-' . $key]->validate($bit, $config, $context);
                if ($r === false) continue;
                if ($r === 'none') {
                    if ($none) continue;
                    else $none = true;
                    if ($key == 'image') continue;
                }
                $caught[$key] = $r;
                $i++;
                break;
            }
        }

        if (!$i) return false;

        $ret = array();

        // construct type
        if ($caught['type']) $ret[] = $caught['type'];

        // construct image
        if ($caught['image']) $ret[] = $caught['image'];

        // construct position
        if ($caught['position']) $ret[] = $caught['position'];

        if (empty($ret)) return false;
        return implode(' ', $ret);

    }

}





/**
 * Framework class for strings that involve multiple values.
 *
 * Certain CSS properties such as border-width and margin allow multiple
 * lengths to be specified.  This class can take a vanilla border-width
 * definition and multiply it, usually into a max of four.
 *
 * @note Even though the CSS specification isn't clear about it, inherit
 *       can only be used alone: it will never manifest as part of a multi
 *       shorthand declaration.  Thus, this class does not allow inherit.
 */
class HTMLPurifier_AttrDef_CSS_Multiple extends HTMLPurifier_AttrDef
{

    /**
     * Instance of component definition to defer validation to.
     * @todo Make protected
     */
    public $single;

    /**
     * Max number of values allowed.
     * @todo Make protected
     */
    public $max;

    /**
     * @param $single HTMLPurifier_AttrDef to multiply
     * @param $max Max number of values allowed (usually four)
     */
    public function __construct($single, $max = 4) {
        $this->single = $single;
        $this->max = $max;
    }

    public function validate($string, $config, $context) {
        $string = $this->parseCDATA($string);
        if ($string === '') return false;
        $parts = explode(' ', $string); // parseCDATA replaced \r, \t and \n
        $length = count($parts);
        $final = '';
        for ($i = 0, $num = 0; $i < $length && $num < $this->max; $i++) {
            if (ctype_space($parts[$i])) continue;
            $result = $this->single->validate($parts[$i], $config, $context);
            if ($result !== false) {
                $final .= $result . ' ';
                $num++;
            }
        }
        if ($final === '') return false;
        return rtrim($final);
    }

}





/**
 * Validates a Percentage as defined by the CSS spec.
 */
class HTMLPurifier_AttrDef_CSS_Percentage extends HTMLPurifier_AttrDef
{

    /**
     * Instance of HTMLPurifier_AttrDef_CSS_Number to defer number validation
     */
    protected $number_def;

    /**
     * @param Bool indicating whether to forbid negative values
     */
    public function __construct($non_negative = false) {
        $this->number_def = new HTMLPurifier_AttrDef_CSS_Number($non_negative);
    }

    public function validate($string, $config, $context) {

        $string = $this->parseCDATA($string);

        if ($string === '') return false;
        $length = strlen($string);
        if ($length === 1) return false;
        if ($string[$length - 1] !== '%') return false;

        $number = substr($string, 0, $length - 1);
        $number = $this->number_def->validate($number, $config, $context);

        if ($number === false) return false;
        return "$number%";

    }

}





/**
 * Validates the value for the CSS property text-decoration
 * @note This class could be generalized into a version that acts sort of
 *       like Enum except you can compound the allowed values.
 */
class HTMLPurifier_AttrDef_CSS_TextDecoration extends HTMLPurifier_AttrDef
{

    public function validate($string, $config, $context) {

        static $allowed_values = array(
            'line-through' => true,
            'overline' => true,
            'underline' => true,
        );

        $string = strtolower($this->parseCDATA($string));

        if ($string === 'none') return $string;

        $parts = explode(' ', $string);
        $final = '';
        foreach ($parts as $part) {
            if (isset($allowed_values[$part])) {
                $final .= $part . ' ';
            }
        }
        $final = rtrim($final);
        if ($final === '') return false;
        return $final;

    }

}





/**
 * Validates a URI in CSS syntax, which uses url('http://example.com')
 * @note While theoretically speaking a URI in a CSS document could
 *       be non-embedded, as of CSS2 there is no such usage so we're
 *       generalizing it. This may need to be changed in the future.
 * @warning Since HTMLPurifier_AttrDef_CSS blindly uses semicolons as
 *          the separator, you cannot put a literal semicolon in
 *          in the URI. Try percent encoding it, in that case.
 */
class HTMLPurifier_AttrDef_CSS_URI extends HTMLPurifier_AttrDef_URI
{

    public function __construct() {
        parent::__construct(true); // always embedded
    }

    public function validate($uri_string, $config, $context) {
        // parse the URI out of the string and then pass it onto
        // the parent object

        $uri_string = $this->parseCDATA($uri_string);
        if (strpos($uri_string, 'url(') !== 0) return false;
        $uri_string = substr($uri_string, 4);
        $new_length = strlen($uri_string) - 1;
        if ($uri_string[$new_length] != ')') return false;
        $uri = trim(substr($uri_string, 0, $new_length));

        if (!empty($uri) && ($uri[0] == "'" || $uri[0] == '"')) {
            $quote = $uri[0];
            $new_length = strlen($uri) - 1;
            if ($uri[$new_length] !== $quote) return false;
            $uri = substr($uri, 1, $new_length - 1);
        }

        $uri = $this->expandCSSEscape($uri);

        $result = parent::validate($uri, $config, $context);

        if ($result === false) return false;

        // extra sanity check; should have been done by URI
        $result = str_replace(array('"', "\\", "\n", "\x0c", "\r"), "", $result);

        // suspicious characters are ()'; we're going to percent encode
        // them for safety.
        $result = str_replace(array('(', ')', "'"), array('%28', '%29', '%27'), $result);

        // there's an extra bug where ampersands lose their escaping on
        // an innerHTML cycle, so a very unlucky query parameter could
        // then change the meaning of the URL.  Unfortunately, there's
        // not much we can do about that...

        return "url(\"$result\")";

    }

}





/**
 * Validates a boolean attribute
 */
class HTMLPurifier_AttrDef_HTML_Bool extends HTMLPurifier_AttrDef
{

    protected $name;
    public $minimized = true;

    public function __construct($name = false) {$this->name = $name;}

    public function validate($string, $config, $context) {
        if (empty($string)) return false;
        return $this->name;
    }

    /**
     * @param $string Name of attribute
     */
    public function make($string) {
        return new HTMLPurifier_AttrDef_HTML_Bool($string);
    }

}





/**
 * Validates contents based on NMTOKENS attribute type.
 */
class HTMLPurifier_AttrDef_HTML_Nmtokens extends HTMLPurifier_AttrDef
{

    public function validate($string, $config, $context) {

        $string = trim($string);

        // early abort: '' and '0' (strings that convert to false) are invalid
        if (!$string) return false;

        $tokens = $this->split($string, $config, $context);
        $tokens = $this->filter($tokens, $config, $context);
        if (empty($tokens)) return false;
        return implode(' ', $tokens);

    }

    /**
     * Splits a space separated list of tokens into its constituent parts.
     */
    protected function split($string, $config, $context) {
        // OPTIMIZABLE!
        // do the preg_match, capture all subpatterns for reformulation

        // we don't support U+00A1 and up codepoints or
        // escaping because I don't know how to do that with regexps
        // and plus it would complicate optimization efforts (you never
        // see that anyway).
        $pattern = '/(?:(?<=\s)|\A)'. // look behind for space or string start
                   '((?:--|-?[A-Za-z_])[A-Za-z_\-0-9]*)'.
                   '(?:(?=\s)|\z)/'; // look ahead for space or string end
        preg_match_all($pattern, $string, $matches);
        return $matches[1];
    }

    /**
     * Template method for removing certain tokens based on arbitrary criteria.
     * @note If we wanted to be really functional, we'd do an array_filter
     *       with a callback. But... we're not.
     */
    protected function filter($tokens, $config, $context) {
        return $tokens;
    }

}





/**
 * Implements special behavior for class attribute (normally NMTOKENS)
 */
class HTMLPurifier_AttrDef_HTML_Class extends HTMLPurifier_AttrDef_HTML_Nmtokens
{
    protected function split($string, $config, $context) {
        // really, this twiddle should be lazy loaded
        $name = $config->getDefinition('HTML')->doctype->name;
        if ($name == "XHTML 1.1" || $name == "XHTML 2.0") {
            return parent::split($string, $config, $context);
        } else {
            return preg_split('/\s+/', $string);
        }
    }
    protected function filter($tokens, $config, $context) {
        $allowed = $config->get('Attr.AllowedClasses');
        $forbidden = $config->get('Attr.ForbiddenClasses');
        $ret = array();
        foreach ($tokens as $token) {
            if (
                ($allowed === null || isset($allowed[$token])) &&
                !isset($forbidden[$token]) &&
                // We need this O(n) check because of PHP's array
                // implementation that casts -0 to 0.
                !in_array($token, $ret, true)
            ) {
                $ret[] = $token;
            }
        }
        return $ret;
    }
}



/**
 * Validates a color according to the HTML spec.
 */
class HTMLPurifier_AttrDef_HTML_Color extends HTMLPurifier_AttrDef
{

    public function validate($string, $config, $context) {

        static $colors = null;
        if ($colors === null) $colors = $config->get('Core.ColorKeywords');

        $string = trim($string);

        if (empty($string)) return false;
        if (isset($colors[$string])) return $colors[$string];
        if ($string[0] === '#') $hex = substr($string, 1);
        else $hex = $string;

        $length = strlen($hex);
        if ($length !== 3 && $length !== 6) return false;
        if (!ctype_xdigit($hex)) return false;
        if ($length === 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];

        return "#$hex";

    }

}





/**
 * Special-case enum attribute definition that lazy loads allowed frame targets
 */
class HTMLPurifier_AttrDef_HTML_FrameTarget extends HTMLPurifier_AttrDef_Enum
{

    public $valid_values = false; // uninitialized value
    protected $case_sensitive = false;

    public function __construct() {}

    public function validate($string, $config, $context) {
        if ($this->valid_values === false) $this->valid_values = $config->get('Attr.AllowedFrameTargets');
        return parent::validate($string, $config, $context);
    }

}





/**
 * Validates the HTML attribute ID.
 * @warning Even though this is the id processor, it
 *          will ignore the directive Attr:IDBlacklist, since it will only
 *          go according to the ID accumulator. Since the accumulator is
 *          automatically generated, it will have already absorbed the
 *          blacklist. If you're hacking around, make sure you use load()!
 */

class HTMLPurifier_AttrDef_HTML_ID extends HTMLPurifier_AttrDef
{

    // ref functionality disabled, since we also have to verify
    // whether or not the ID it refers to exists

    public function validate($id, $config, $context) {

        if (!$config->get('Attr.EnableID')) return false;

        $id = trim($id); // trim it first

        if ($id === '') return false;

        $prefix = $config->get('Attr.IDPrefix');
        if ($prefix !== '') {
            $prefix .= $config->get('Attr.IDPrefixLocal');
            // prevent re-appending the prefix
            if (strpos($id, $prefix) !== 0) $id = $prefix . $id;
        } elseif ($config->get('Attr.IDPrefixLocal') !== '') {
            trigger_error('%Attr.IDPrefixLocal cannot be used unless '.
                '%Attr.IDPrefix is set', E_USER_WARNING);
        }

        //if (!$this->ref) {
            $id_accumulator =& $context->get('IDAccumulator');
            if (isset($id_accumulator->ids[$id])) return false;
        //}

        // we purposely avoid using regex, hopefully this is faster

        if (ctype_alpha($id)) {
            $result = true;
        } else {
            if (!ctype_alpha(@$id[0])) return false;
            $trim = trim( // primitive style of regexps, I suppose
                $id,
                'A..Za..z0..9:-._'
              );
            $result = ($trim === '');
        }

        $regexp = $config->get('Attr.IDBlacklistRegexp');
        if ($regexp && preg_match($regexp, $id)) {
            return false;
        }

        if (/*!$this->ref && */$result) $id_accumulator->add($id);

        // if no change was made to the ID, return the result
        // else, return the new id if stripping whitespace made it
        //     valid, or return false.
        return $result ? $id : false;

    }

}





/**
 * Validates an integer representation of pixels according to the HTML spec.
 */
class HTMLPurifier_AttrDef_HTML_Pixels extends HTMLPurifier_AttrDef
{

    protected $max;

    public function __construct($max = null) {
        $this->max = $max;
    }

    public function validate($string, $config, $context) {

        $string = trim($string);
        if ($string === '0') return $string;
        if ($string === '')  return false;
        $length = strlen($string);
        if (substr($string, $length - 2) == 'px') {
            $string = substr($string, 0, $length - 2);
        }
        if (!is_numeric($string)) return false;
        $int = (int) $string;

        if ($int < 0) return '0';

        // upper-bound value, extremely high values can
        // crash operating systems, see <http://ha.ckers.org/imagecrash.html>
        // WARNING, above link WILL crash you if you're using Windows

        if ($this->max !== null && $int > $this->max) return (string) $this->max;

        return (string) $int;

    }

    public function make($string) {
        if ($string === '') $max = null;
        else $max = (int) $string;
        $class = get_class($this);
        return new $class($max);
    }

}





/**
 * Validates the HTML type length (not to be confused with CSS's length).
 *
 * This accepts integer pixels or percentages as lengths for certain
 * HTML attributes.
 */

class HTMLPurifier_AttrDef_HTML_Length extends HTMLPurifier_AttrDef_HTML_Pixels
{

    public function validate($string, $config, $context) {

        $string = trim($string);
        if ($string === '') return false;

        $parent_result = parent::validate($string, $config, $context);
        if ($parent_result !== false) return $parent_result;

        $length = strlen($string);
        $last_char = $string[$length - 1];

        if ($last_char !== '%') return false;

        $points = substr($string, 0, $length - 1);

        if (!is_numeric($points)) return false;

        $points = (int) $points;

        if ($points < 0) return '0%';
        if ($points > 100) return '100%';

        return ((string) $points) . '%';

    }

}





/**
 * Validates a rel/rev link attribute against a directive of allowed values
 * @note We cannot use Enum because link types allow multiple
 *       values.
 * @note Assumes link types are ASCII text
 */
class HTMLPurifier_AttrDef_HTML_LinkTypes extends HTMLPurifier_AttrDef
{

    /** Name config attribute to pull. */
    protected $name;

    public function __construct($name) {
        $configLookup = array(
            'rel' => 'AllowedRel',
            'rev' => 'AllowedRev'
        );
        if (!isset($configLookup[$name])) {
            trigger_error('Unrecognized attribute name for link '.
                'relationship.', E_USER_ERROR);
            return;
        }
        $this->name = $configLookup[$name];
    }

    public function validate($string, $config, $context) {

        $allowed = $config->get('Attr.' . $this->name);
        if (empty($allowed)) return false;

        $string = $this->parseCDATA($string);
        $parts = explode(' ', $string);

        // lookup to prevent duplicates
        $ret_lookup = array();
        foreach ($parts as $part) {
            $part = strtolower(trim($part));
            if (!isset($allowed[$part])) continue;
            $ret_lookup[$part] = true;
        }

        if (empty($ret_lookup)) return false;
        $string = implode(' ', array_keys($ret_lookup));

        return $string;

    }

}





/**
 * Validates a MultiLength as defined by the HTML spec.
 *
 * A multilength is either a integer (pixel count), a percentage, or
 * a relative number.
 */
class HTMLPurifier_AttrDef_HTML_MultiLength extends HTMLPurifier_AttrDef_HTML_Length
{

    public function validate($string, $config, $context) {

        $string = trim($string);
        if ($string === '') return false;

        $parent_result = parent::validate($string, $config, $context);
        if ($parent_result !== false) return $parent_result;

        $length = strlen($string);
        $last_char = $string[$length - 1];

        if ($last_char !== '*') return false;

        $int = substr($string, 0, $length - 1);

        if ($int == '') return '*';
        if (!is_numeric($int)) return false;

        $int = (int) $int;

        if ($int < 0) return false;
        if ($int == 0) return '0';
        if ($int == 1) return '*';
        return ((string) $int) . '*';

    }

}





abstract class HTMLPurifier_AttrDef_URI_Email extends HTMLPurifier_AttrDef
{

    /**
     * Unpacks a mailbox into its display-name and address
     */
    function unpack($string) {
        // needs to be implemented
    }

}

// sub-implementations





/**
 * Validates a host according to the IPv4, IPv6 and DNS (future) specifications.
 */
class HTMLPurifier_AttrDef_URI_Host extends HTMLPurifier_AttrDef
{

    /**
     * Instance of HTMLPurifier_AttrDef_URI_IPv4 sub-validator
     */
    protected $ipv4;

    /**
     * Instance of HTMLPurifier_AttrDef_URI_IPv6 sub-validator
     */
    protected $ipv6;

    public function __construct() {
        $this->ipv4 = new HTMLPurifier_AttrDef_URI_IPv4();
        $this->ipv6 = new HTMLPurifier_AttrDef_URI_IPv6();
    }

    public function validate($string, $config, $context) {
        $length = strlen($string);
        // empty hostname is OK; it's usually semantically equivalent:
        // the default host as defined by a URI scheme is used:
        //
        //      If the URI scheme defines a default for host, then that
        //      default applies when the host subcomponent is undefined
        //      or when the registered name is empty (zero length).
        if ($string === '') return '';
        if ($length > 1 && $string[0] === '[' && $string[$length-1] === ']') {
            //IPv6
            $ip = substr($string, 1, $length - 2);
            $valid = $this->ipv6->validate($ip, $config, $context);
            if ($valid === false) return false;
            return '['. $valid . ']';
        }

        // need to do checks on unusual encodings too
        $ipv4 = $this->ipv4->validate($string, $config, $context);
        if ($ipv4 !== false) return $ipv4;

        // A regular domain name.

        // This breaks I18N domain names, but we don't have proper IRI support,
        // so force users to insert Punycode. If there's complaining we'll
        // try to fix things into an international friendly form.

        // The productions describing this are:
        $a   = '[a-z]';     // alpha
        $an  = '[a-z0-9]';  // alphanum
        $and = '[a-z0-9-]'; // alphanum | "-"
        // domainlabel = alphanum | alphanum *( alphanum | "-" ) alphanum
        $domainlabel   = "$an($and*$an)?";
        // toplabel    = alpha | alpha *( alphanum | "-" ) alphanum
        $toplabel      = "$a($and*$an)?";
        // hostname    = *( domainlabel "." ) toplabel [ "." ]
        $match = preg_match("/^($domainlabel\.)*$toplabel\.?$/i", $string);
        if (!$match) return false;

        return $string;
    }

}





/**
 * Validates an IPv4 address
 * @author Feyd @ forums.devnetwork.net (public domain)
 */
class HTMLPurifier_AttrDef_URI_IPv4 extends HTMLPurifier_AttrDef
{

    /**
     * IPv4 regex, protected so that IPv6 can reuse it
     */
    protected $ip4;

    public function validate($aIP, $config, $context) {

        if (!$this->ip4) $this->_loadRegex();

        if (preg_match('#^' . $this->ip4 . '$#s', $aIP))
        {
                return $aIP;
        }

        return false;

    }

    /**
     * Lazy load function to prevent regex from being stuffed in
     * cache.
     */
    protected function _loadRegex() {
        $oct = '(?:25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]|[0-9])'; // 0-255
        $this->ip4 = "(?:{$oct}\\.{$oct}\\.{$oct}\\.{$oct})";
    }

}





/**
 * Validates an IPv6 address.
 * @author Feyd @ forums.devnetwork.net (public domain)
 * @note This function requires brackets to have been removed from address
 *       in URI.
 */
class HTMLPurifier_AttrDef_URI_IPv6 extends HTMLPurifier_AttrDef_URI_IPv4
{

    public function validate($aIP, $config, $context) {

        if (!$this->ip4) $this->_loadRegex();

        $original = $aIP;

        $hex = '[0-9a-fA-F]';
        $blk = '(?:' . $hex . '{1,4})';
        $pre = '(?:/(?:12[0-8]|1[0-1][0-9]|[1-9][0-9]|[0-9]))';   // /0 - /128

        //      prefix check
        if (strpos($aIP, '/') !== false)
        {
                if (preg_match('#' . $pre . '$#s', $aIP, $find))
                {
                        $aIP = substr($aIP, 0, 0-strlen($find[0]));
                        unset($find);
                }
                else
                {
                        return false;
                }
        }

        //      IPv4-compatiblity check
        if (preg_match('#(?<=:'.')' . $this->ip4 . '$#s', $aIP, $find))
        {
                $aIP = substr($aIP, 0, 0-strlen($find[0]));
                $ip = explode('.', $find[0]);
                $ip = array_map('dechex', $ip);
                $aIP .= $ip[0] . $ip[1] . ':' . $ip[2] . $ip[3];
                unset($find, $ip);
        }

        //      compression check
        $aIP = explode('::', $aIP);
        $c = count($aIP);
        if ($c > 2)
        {
                return false;
        }
        elseif ($c == 2)
        {
                list($first, $second) = $aIP;
                $first = explode(':', $first);
                $second = explode(':', $second);

                if (count($first) + count($second) > 8)
                {
                        return false;
                }

                while(count($first) < 8)
                {
                        array_push($first, '0');
                }

                array_splice($first, 8 - count($second), 8, $second);
                $aIP = $first;
                unset($first,$second);
        }
        else
        {
                $aIP = explode(':', $aIP[0]);
        }
        $c = count($aIP);

        if ($c != 8)
        {
                return false;
        }

        //      All the pieces should be 16-bit hex strings. Are they?
        foreach ($aIP as $piece)
        {
                if (!preg_match('#^[0-9a-fA-F]{4}$#s', sprintf('%04s', $piece)))
                {
                        return false;
                }
        }

        return $original;

    }

}





/**
 * Primitive email validation class based on the regexp found at
 * http://www.regular-expressions.info/email.html
 */
class HTMLPurifier_AttrDef_URI_Email_SimpleCheck extends HTMLPurifier_AttrDef_URI_Email
{

    public function validate($string, $config, $context) {
        // no support for named mailboxes i.e. "Bob <bob@example.com>"
        // that needs more percent encoding to be done
        if ($string == '') return false;
        $string = trim($string);
        $result = preg_match('/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $string);
        return $result ? $string : false;
    }

}





/**
 * Pre-transform that changes proprietary background attribute to CSS.
 */
class HTMLPurifier_AttrTransform_Background extends HTMLPurifier_AttrTransform {

    public function transform($attr, $config, $context) {

        if (!isset($attr['background'])) return $attr;

        $background = $this->confiscateAttr($attr, 'background');
        // some validation should happen here

        $this->prependCSS($attr, "background-image:url($background);");

        return $attr;

    }

}





// this MUST be placed in post, as it assumes that any value in dir is valid

/**
 * Post-trasnform that ensures that bdo tags have the dir attribute set.
 */
class HTMLPurifier_AttrTransform_BdoDir extends HTMLPurifier_AttrTransform
{

    public function transform($attr, $config, $context) {
        if (isset($attr['dir'])) return $attr;
        $attr['dir'] = $config->get('Attr.DefaultTextDir');
        return $attr;
    }

}





/**
 * Pre-transform that changes deprecated bgcolor attribute to CSS.
 */
class HTMLPurifier_AttrTransform_BgColor extends HTMLPurifier_AttrTransform {

    public function transform($attr, $config, $context) {

        if (!isset($attr['bgcolor'])) return $attr;

        $bgcolor = $this->confiscateAttr($attr, 'bgcolor');
        // some validation should happen here

        $this->prependCSS($attr, "background-color:$bgcolor;");

        return $attr;

    }

}





/**
 * Pre-transform that changes converts a boolean attribute to fixed CSS
 */
class HTMLPurifier_AttrTransform_BoolToCSS extends HTMLPurifier_AttrTransform {

    /**
     * Name of boolean attribute that is trigger
     */
    protected $attr;

    /**
     * CSS declarations to add to style, needs trailing semicolon
     */
    protected $css;

    /**
     * @param $attr string attribute name to convert from
     * @param $css string CSS declarations to add to style (needs semicolon)
     */
    public function __construct($attr, $css) {
        $this->attr = $attr;
        $this->css  = $css;
    }

    public function transform($attr, $config, $context) {
        if (!isset($attr[$this->attr])) return $attr;
        unset($attr[$this->attr]);
        $this->prependCSS($attr, $this->css);
        return $attr;
    }

}





/**
 * Pre-transform that changes deprecated border attribute to CSS.
 */
class HTMLPurifier_AttrTransform_Border extends HTMLPurifier_AttrTransform {

    public function transform($attr, $config, $context) {
        if (!isset($attr['border'])) return $attr;
        $border_width = $this->confiscateAttr($attr, 'border');
        // some validation should happen here
        $this->prependCSS($attr, "border:{$border_width}px solid;");
        return $attr;
    }

}





/**
 * Generic pre-transform that converts an attribute with a fixed number of
 * values (enumerated) to CSS.
 */
class HTMLPurifier_AttrTransform_EnumToCSS extends HTMLPurifier_AttrTransform {

    /**
     * Name of attribute to transform from
     */
    protected $attr;

    /**
     * Lookup array of attribute values to CSS
     */
    protected $enumToCSS = array();

    /**
     * Case sensitivity of the matching
     * @warning Currently can only be guaranteed to work with ASCII
     *          values.
     */
    protected $caseSensitive = false;

    /**
     * @param $attr String attribute name to transform from
     * @param $enumToCSS Lookup array of attribute values to CSS
     * @param $case_sensitive Boolean case sensitivity indicator, default false
     */
    public function __construct($attr, $enum_to_css, $case_sensitive = false) {
        $this->attr = $attr;
        $this->enumToCSS = $enum_to_css;
        $this->caseSensitive = (bool) $case_sensitive;
    }

    public function transform($attr, $config, $context) {

        if (!isset($attr[$this->attr])) return $attr;

        $value = trim($attr[$this->attr]);
        unset($attr[$this->attr]);

        if (!$this->caseSensitive) $value = strtolower($value);

        if (!isset($this->enumToCSS[$value])) {
            return $attr;
        }

        $this->prependCSS($attr, $this->enumToCSS[$value]);

        return $attr;

    }

}





// must be called POST validation

/**
 * Transform that supplies default values for the src and alt attributes
 * in img tags, as well as prevents the img tag from being removed
 * because of a missing alt tag. This needs to be registered as both
 * a pre and post attribute transform.
 */
class HTMLPurifier_AttrTransform_ImgRequired extends HTMLPurifier_AttrTransform
{

    public function transform($attr, $config, $context) {

        $src = true;
        if (!isset($attr['src'])) {
            if ($config->get('Core.RemoveInvalidImg')) return $attr;
            $attr['src'] = $config->get('Attr.DefaultInvalidImage');
            $src = false;
        }

        if (!isset($attr['alt'])) {
            if ($src) {
                $alt = $config->get('Attr.DefaultImageAlt');
                if ($alt === null) {
                    // truncate if the alt is too long
                    $attr['alt'] = substr(basename($attr['src']),0,40);
                } else {
                    $attr['alt'] = $alt;
                }
            } else {
                $attr['alt'] = $config->get('Attr.DefaultInvalidImageAlt');
            }
        }

        return $attr;

    }

}





/**
 * Pre-transform that changes deprecated hspace and vspace attributes to CSS
 */
class HTMLPurifier_AttrTransform_ImgSpace extends HTMLPurifier_AttrTransform {

    protected $attr;
    protected $css = array(
        'hspace' => array('left', 'right'),
        'vspace' => array('top', 'bottom')
    );

    public function __construct($attr) {
        $this->attr = $attr;
        if (!isset($this->css[$attr])) {
            trigger_error(htmlspecialchars($attr) . ' is not valid space attribute');
        }
    }

    public function transform($attr, $config, $context) {

        if (!isset($attr[$this->attr])) return $attr;

        $width = $this->confiscateAttr($attr, $this->attr);
        // some validation could happen here

        if (!isset($this->css[$this->attr])) return $attr;

        $style = '';
        foreach ($this->css[$this->attr] as $suffix) {
            $property = "margin-$suffix";
            $style .= "$property:{$width}px;";
        }

        $this->prependCSS($attr, $style);

        return $attr;

    }

}





/**
 * Performs miscellaneous cross attribute validation and filtering for
 * input elements. This is meant to be a post-transform.
 */
class HTMLPurifier_AttrTransform_Input extends HTMLPurifier_AttrTransform {

    protected $pixels;

    public function __construct() {
        $this->pixels = new HTMLPurifier_AttrDef_HTML_Pixels();
    }

    public function transform($attr, $config, $context) {
        if (!isset($attr['type'])) $t = 'text';
        else $t = strtolower($attr['type']);
        if (isset($attr['checked']) && $t !== 'radio' && $t !== 'checkbox') {
            unset($attr['checked']);
        }
        if (isset($attr['maxlength']) && $t !== 'text' && $t !== 'password') {
            unset($attr['maxlength']);
        }
        if (isset($attr['size']) && $t !== 'text' && $t !== 'password') {
            $result = $this->pixels->validate($attr['size'], $config, $context);
            if ($result === false) unset($attr['size']);
            else $attr['size'] = $result;
        }
        if (isset($attr['src']) && $t !== 'image') {
            unset($attr['src']);
        }
        if (!isset($attr['value']) && ($t === 'radio' || $t === 'checkbox')) {
            $attr['value'] = '';
        }
        return $attr;
    }

}





/**
 * Post-transform that copies lang's value to xml:lang (and vice-versa)
 * @note Theoretically speaking, this could be a pre-transform, but putting
 *       post is more efficient.
 */
class HTMLPurifier_AttrTransform_Lang extends HTMLPurifier_AttrTransform
{

    public function transform($attr, $config, $context) {

        $lang     = isset($attr['lang']) ? $attr['lang'] : false;
        $xml_lang = isset($attr['xml:lang']) ? $attr['xml:lang'] : false;

        if ($lang !== false && $xml_lang === false) {
            $attr['xml:lang'] = $lang;
        } elseif ($xml_lang !== false) {
            $attr['lang'] = $xml_lang;
        }

        return $attr;

    }

}





/**
 * Class for handling width/height length attribute transformations to CSS
 */
class HTMLPurifier_AttrTransform_Length extends HTMLPurifier_AttrTransform
{

    protected $name;
    protected $cssName;

    public function __construct($name, $css_name = null) {
        $this->name = $name;
        $this->cssName = $css_name ? $css_name : $name;
    }

    public function transform($attr, $config, $context) {
        if (!isset($attr[$this->name])) return $attr;
        $length = $this->confiscateAttr($attr, $this->name);
        if(ctype_digit($length)) $length .= 'px';
        $this->prependCSS($attr, $this->cssName . ":$length;");
        return $attr;
    }

}





/**
 * Pre-transform that changes deprecated name attribute to ID if necessary
 */
class HTMLPurifier_AttrTransform_Name extends HTMLPurifier_AttrTransform
{

    public function transform($attr, $config, $context) {
        // Abort early if we're using relaxed definition of name
        if ($config->get('HTML.Attr.Name.UseCDATA')) return $attr;
        if (!isset($attr['name'])) return $attr;
        $id = $this->confiscateAttr($attr, 'name');
        if ( isset($attr['id']))   return $attr;
        $attr['id'] = $id;
        return $attr;
    }

}





/**
 * Post-transform that performs validation to the name attribute; if
 * it is present with an equivalent id attribute, it is passed through;
 * otherwise validation is performed.
 */
class HTMLPurifier_AttrTransform_NameSync extends HTMLPurifier_AttrTransform
{

    public function __construct() {
        $this->idDef = new HTMLPurifier_AttrDef_HTML_ID();
    }

    public function transform($attr, $config, $context) {
        if (!isset($attr['name'])) return $attr;
        $name = $attr['name'];
        if (isset($attr['id']) && $attr['id'] === $name) return $attr;
        $result = $this->idDef->validate($name, $config, $context);
        if ($result === false) unset($attr['name']);
        else $attr['name'] = $result;
        return $attr;
    }

}





// must be called POST validation

/**
 * Adds rel="nofollow" to all outbound links.  This transform is
 * only attached if Attr.Nofollow is TRUE.
 */
class HTMLPurifier_AttrTransform_Nofollow extends HTMLPurifier_AttrTransform
{
    private $parser;

    public function __construct() {
        $this->parser = new HTMLPurifier_URIParser();
    }

    public function transform($attr, $config, $context) {

        if (!isset($attr['href'])) {
            return $attr;
        }

        // XXX Kind of inefficient
        $url = $this->parser->parse($attr['href']);
        $scheme = $url->getSchemeObj($config, $context);

        if (!is_null($url->host) && $scheme !== false && $scheme->browsable) {
            if (isset($attr['rel'])) {
                $attr['rel'] .= ' nofollow';
            } else {
                $attr['rel'] = 'nofollow';
            }
        }

        return $attr;

    }

}





class HTMLPurifier_AttrTransform_SafeEmbed extends HTMLPurifier_AttrTransform
{
    public $name = "SafeEmbed";

    public function transform($attr, $config, $context) {
        $attr['allowscriptaccess'] = 'never';
        $attr['allownetworking'] = 'internal';
        $attr['type'] = 'application/x-shockwave-flash';
        return $attr;
    }
}





/**
 * Writes default type for all objects. Currently only supports flash.
 */
class HTMLPurifier_AttrTransform_SafeObject extends HTMLPurifier_AttrTransform
{
    public $name = "SafeObject";

    function transform($attr, $config, $context) {
        if (!isset($attr['type'])) $attr['type'] = 'application/x-shockwave-flash';
        return $attr;
    }
}





/**
 * Validates name/value pairs in param tags to be used in safe objects. This
 * will only allow name values it recognizes, and pre-fill certain attributes
 * with required values.
 *
 * @note
 *      This class only supports Flash. In the future, Quicktime support
 *      may be added.
 *
 * @warning
 *      This class expects an injector to add the necessary parameters tags.
 */
class HTMLPurifier_AttrTransform_SafeParam extends HTMLPurifier_AttrTransform
{
    public $name = "SafeParam";
    private $uri;

    public function __construct() {
        $this->uri = new HTMLPurifier_AttrDef_URI(true); // embedded
        $this->wmode = new HTMLPurifier_AttrDef_Enum(array('window', 'opaque', 'transparent'));
    }

    public function transform($attr, $config, $context) {
        // If we add support for other objects, we'll need to alter the
        // transforms.
        switch ($attr['name']) {
            // application/x-shockwave-flash
            // Keep this synchronized with Injector/SafeObject.php
            case 'allowScriptAccess':
                $attr['value'] = 'never';
                break;
            case 'allowNetworking':
                $attr['value'] = 'internal';
                break;
            case 'allowFullScreen':
                if ($config->get('HTML.FlashAllowFullScreen')) {
                    $attr['value'] = ($attr['value'] == 'true') ? 'true' : 'false';
                } else {
                    $attr['value'] = 'false';
                }
                break;
            case 'wmode':
                $attr['value'] = $this->wmode->validate($attr['value'], $config, $context);
                break;
            case 'movie':
            case 'src':
                $attr['name'] = "movie";
                $attr['value'] = $this->uri->validate($attr['value'], $config, $context);
                break;
            case 'flashvars':
                // we're going to allow arbitrary inputs to the SWF, on
                // the reasoning that it could only hack the SWF, not us.
                break;
            // add other cases to support other param name/value pairs
            default:
                $attr['name'] = $attr['value'] = null;
        }
        return $attr;
    }
}





/**
 * Implements required attribute stipulation for <script>
 */
class HTMLPurifier_AttrTransform_ScriptRequired extends HTMLPurifier_AttrTransform
{
    public function transform($attr, $config, $context) {
        if (!isset($attr['type'])) {
            $attr['type'] = 'text/javascript';
        }
        return $attr;
    }
}





/**
 * Sets height/width defaults for <textarea>
 */
class HTMLPurifier_AttrTransform_Textarea extends HTMLPurifier_AttrTransform
{

    public function transform($attr, $config, $context) {
        // Calculated from Firefox
        if (!isset($attr['cols'])) $attr['cols'] = '22';
        if (!isset($attr['rows'])) $attr['rows'] = '3';
        return $attr;
    }

}





/**
 * Definition that uses different definitions depending on context.
 *
 * The del and ins tags are notable because they allow different types of
 * elements depending on whether or not they're in a block or inline context.
 * Chameleon allows this behavior to happen by using two different
 * definitions depending on context.  While this somewhat generalized,
 * it is specifically intended for those two tags.
 */
class HTMLPurifier_ChildDef_Chameleon extends HTMLPurifier_ChildDef
{

    /**
     * Instance of the definition object to use when inline. Usually stricter.
     */
    public $inline;

    /**
     * Instance of the definition object to use when block.
     */
    public $block;

    public $type = 'chameleon';

    /**
     * @param $inline List of elements to allow when inline.
     * @param $block List of elements to allow when block.
     */
    public function __construct($inline, $block) {
        $this->inline = new HTMLPurifier_ChildDef_Optional($inline);
        $this->block  = new HTMLPurifier_ChildDef_Optional($block);
        $this->elements = $this->block->elements;
    }

    public function validateChildren($tokens_of_children, $config, $context) {
        if ($context->get('IsInline') === false) {
            return $this->block->validateChildren(
                $tokens_of_children, $config, $context);
        } else {
            return $this->inline->validateChildren(
                $tokens_of_children, $config, $context);
        }
    }
}





/**
 * Custom validation class, accepts DTD child definitions
 *
 * @warning Currently this class is an all or nothing proposition, that is,
 *          it will only give a bool return value.
 */
class HTMLPurifier_ChildDef_Custom extends HTMLPurifier_ChildDef
{
    public $type = 'custom';
    public $allow_empty = false;
    /**
     * Allowed child pattern as defined by the DTD
     */
    public $dtd_regex;
    /**
     * PCRE regex derived from $dtd_regex
     * @private
     */
    private $_pcre_regex;
    /**
     * @param $dtd_regex Allowed child pattern from the DTD
     */
    public function __construct($dtd_regex) {
        $this->dtd_regex = $dtd_regex;
        $this->_compileRegex();
    }
    /**
     * Compiles the PCRE regex from a DTD regex ($dtd_regex to $_pcre_regex)
     */
    protected function _compileRegex() {
        $raw = str_replace(' ', '', $this->dtd_regex);
        if ($raw{0} != '(') {
            $raw = "($raw)";
        }
        $el = '[#a-zA-Z0-9_.-]+';
        $reg = $raw;

        // COMPLICATED! AND MIGHT BE BUGGY! I HAVE NO CLUE WHAT I'M
        // DOING! Seriously: if there's problems, please report them.

        // collect all elements into the $elements array
        preg_match_all("/$el/", $reg, $matches);
        foreach ($matches[0] as $match) {
            $this->elements[$match] = true;
        }

        // setup all elements as parentheticals with leading commas
        $reg = preg_replace("/$el/", '(,\\0)', $reg);

        // remove commas when they were not solicited
        $reg = preg_replace("/([^,(|]\(+),/", '\\1', $reg);

        // remove all non-paranthetical commas: they are handled by first regex
        $reg = preg_replace("/,\(/", '(', $reg);

        $this->_pcre_regex = $reg;
    }
    public function validateChildren($tokens_of_children, $config, $context) {
        $list_of_children = '';
        $nesting = 0; // depth into the nest
        foreach ($tokens_of_children as $token) {
            if (!empty($token->is_whitespace)) continue;

            $is_child = ($nesting == 0); // direct

            if ($token instanceof HTMLPurifier_Token_Start) {
                $nesting++;
            } elseif ($token instanceof HTMLPurifier_Token_End) {
                $nesting--;
            }

            if ($is_child) {
                $list_of_children .= $token->name . ',';
            }
        }
        // add leading comma to deal with stray comma declarations
        $list_of_children = ',' . rtrim($list_of_children, ',');
        $okay =
            preg_match(
                '/^,?'.$this->_pcre_regex.'$/',
                $list_of_children
            );

        return (bool) $okay;
    }
}





/**
 * Definition that disallows all elements.
 * @warning validateChildren() in this class is actually never called, because
 *          empty elements are corrected in HTMLPurifier_Strategy_MakeWellFormed
 *          before child definitions are parsed in earnest by
 *          HTMLPurifier_Strategy_FixNesting.
 */
class HTMLPurifier_ChildDef_Empty extends HTMLPurifier_ChildDef
{
    public $allow_empty = true;
    public $type = 'empty';
    public function __construct() {}
    public function validateChildren($tokens_of_children, $config, $context) {
        return array();
    }
}





/**
 * Definition that allows a set of elements, but disallows empty children.
 */
class HTMLPurifier_ChildDef_Required extends HTMLPurifier_ChildDef
{
    /**
     * Lookup table of allowed elements.
     * @public
     */
    public $elements = array();
    /**
     * Whether or not the last passed node was all whitespace.
     */
    protected $whitespace = false;
    /**
     * @param $elements List of allowed element names (lowercase).
     */
    public function __construct($elements) {
        if (is_string($elements)) {
            $elements = str_replace(' ', '', $elements);
            $elements = explode('|', $elements);
        }
        $keys = array_keys($elements);
        if ($keys == array_keys($keys)) {
            $elements = array_flip($elements);
            foreach ($elements as $i => $x) {
                $elements[$i] = true;
                if (empty($i)) unset($elements[$i]); // remove blank
            }
        }
        $this->elements = $elements;
    }
    public $allow_empty = false;
    public $type = 'required';
    public function validateChildren($tokens_of_children, $config, $context) {
        // Flag for subclasses
        $this->whitespace = false;

        // if there are no tokens, delete parent node
        if (empty($tokens_of_children)) return false;

        // the new set of children
        $result = array();

        // current depth into the nest
        $nesting = 0;

        // whether or not we're deleting a node
        $is_deleting = false;

        // whether or not parsed character data is allowed
        // this controls whether or not we silently drop a tag
        // or generate escaped HTML from it
        $pcdata_allowed = isset($this->elements['#PCDATA']);

        // a little sanity check to make sure it's not ALL whitespace
        $all_whitespace = true;

        // some configuration
        $escape_invalid_children = $config->get('Core.EscapeInvalidChildren');

        // generator
        $gen = new HTMLPurifier_Generator($config, $context);

        foreach ($tokens_of_children as $token) {
            if (!empty($token->is_whitespace)) {
                $result[] = $token;
                continue;
            }
            $all_whitespace = false; // phew, we're not talking about whitespace

            $is_child = ($nesting == 0);

            if ($token instanceof HTMLPurifier_Token_Start) {
                $nesting++;
            } elseif ($token instanceof HTMLPurifier_Token_End) {
                $nesting--;
            }

            if ($is_child) {
                $is_deleting = false;
                if (!isset($this->elements[$token->name])) {
                    $is_deleting = true;
                    if ($pcdata_allowed && $token instanceof HTMLPurifier_Token_Text) {
                        $result[] = $token;
                    } elseif ($pcdata_allowed && $escape_invalid_children) {
                        $result[] = new HTMLPurifier_Token_Text(
                            $gen->generateFromToken($token)
                        );
                    }
                    continue;
                }
            }
            if (!$is_deleting || ($pcdata_allowed && $token instanceof HTMLPurifier_Token_Text)) {
                $result[] = $token;
            } elseif ($pcdata_allowed && $escape_invalid_children) {
                $result[] =
                    new HTMLPurifier_Token_Text(
                        $gen->generateFromToken($token)
                    );
            } else {
                // drop silently
            }
        }
        if (empty($result)) return false;
        if ($all_whitespace) {
            $this->whitespace = true;
            return false;
        }
        if ($tokens_of_children == $result) return true;
        return $result;
    }
}





/**
 * Definition that allows a set of elements, and allows no children.
 * @note This is a hack to reuse code from HTMLPurifier_ChildDef_Required,
 *       really, one shouldn't inherit from the other.  Only altered behavior
 *       is to overload a returned false with an array.  Thus, it will never
 *       return false.
 */
class HTMLPurifier_ChildDef_Optional extends HTMLPurifier_ChildDef_Required
{
    public $allow_empty = true;
    public $type = 'optional';
    public function validateChildren($tokens_of_children, $config, $context) {
        $result = parent::validateChildren($tokens_of_children, $config, $context);
        // we assume that $tokens_of_children is not modified
        if ($result === false) {
            if (empty($tokens_of_children)) return true;
            elseif ($this->whitespace) return $tokens_of_children;
            else return array();
        }
        return $result;
    }
}





/**
 * Takes the contents of blockquote when in strict and reformats for validation.
 */
class HTMLPurifier_ChildDef_StrictBlockquote extends HTMLPurifier_ChildDef_Required
{
    protected $real_elements;
    protected $fake_elements;
    public $allow_empty = true;
    public $type = 'strictblockquote';
    protected $init = false;

    /**
     * @note We don't want MakeWellFormed to auto-close inline elements since
     *       they might be allowed.
     */
    public function getAllowedElements($config) {
        $this->init($config);
        return $this->fake_elements;
    }

    public function validateChildren($tokens_of_children, $config, $context) {

        $this->init($config);

        // trick the parent class into thinking it allows more
        $this->elements = $this->fake_elements;
        $result = parent::validateChildren($tokens_of_children, $config, $context);
        $this->elements = $this->real_elements;

        if ($result === false) return array();
        if ($result === true) $result = $tokens_of_children;

        $def = $config->getHTMLDefinition();
        $block_wrap_start = new HTMLPurifier_Token_Start($def->info_block_wrapper);
        $block_wrap_end   = new HTMLPurifier_Token_End(  $def->info_block_wrapper);
        $is_inline = false;
        $depth = 0;
        $ret = array();

        // assuming that there are no comment tokens
        foreach ($result as $i => $token) {
            $token = $result[$i];
            // ifs are nested for readability
            if (!$is_inline) {
                if (!$depth) {
                     if (
                        ($token instanceof HTMLPurifier_Token_Text && !$token->is_whitespace) ||
                        (!$token instanceof HTMLPurifier_Token_Text && !isset($this->elements[$token->name]))
                     ) {
                        $is_inline = true;
                        $ret[] = $block_wrap_start;
                     }
                }
            } else {
                if (!$depth) {
                    // starting tokens have been inline text / empty
                    if ($token instanceof HTMLPurifier_Token_Start || $token instanceof HTMLPurifier_Token_Empty) {
                        if (isset($this->elements[$token->name])) {
                            // ended
                            $ret[] = $block_wrap_end;
                            $is_inline = false;
                        }
                    }
                }
            }
            $ret[] = $token;
            if ($token instanceof HTMLPurifier_Token_Start) $depth++;
            if ($token instanceof HTMLPurifier_Token_End)   $depth--;
        }
        if ($is_inline) $ret[] = $block_wrap_end;
        return $ret;
    }

    private function init($config) {
        if (!$this->init) {
            $def = $config->getHTMLDefinition();
            // allow all inline elements
            $this->real_elements = $this->elements;
            $this->fake_elements = $def->info_content_sets['Flow'];
            $this->fake_elements['#PCDATA'] = true;
            $this->init = true;
        }
    }
}





/**
 * Definition for tables
 */
class HTMLPurifier_ChildDef_Table extends HTMLPurifier_ChildDef
{
    public $allow_empty = false;
    public $type = 'table';
    public $elements = array('tr' => true, 'tbody' => true, 'thead' => true,
        'tfoot' => true, 'caption' => true, 'colgroup' => true, 'col' => true);
    public function __construct() {}
    public function validateChildren($tokens_of_children, $config, $context) {
        if (empty($tokens_of_children)) return false;

        // this ensures that the loop gets run one last time before closing
        // up. It's a little bit of a hack, but it works! Just make sure you
        // get rid of the token later.
        $tokens_of_children[] = false;

        // only one of these elements is allowed in a table
        $caption = false;
        $thead   = false;
        $tfoot   = false;

        // as many of these as you want
        $cols    = array();
        $content = array();

        $nesting = 0; // current depth so we can determine nodes
        $is_collecting = false; // are we globbing together tokens to package
                                // into one of the collectors?
        $collection = array(); // collected nodes
        $tag_index = 0; // the first node might be whitespace,
                            // so this tells us where the start tag is

        foreach ($tokens_of_children as $token) {
            $is_child = ($nesting == 0);

            if ($token === false) {
                // terminating sequence started
            } elseif ($token instanceof HTMLPurifier_Token_Start) {
                $nesting++;
            } elseif ($token instanceof HTMLPurifier_Token_End) {
                $nesting--;
            }

            // handle node collection
            if ($is_collecting) {
                if ($is_child) {
                    // okay, let's stash the tokens away
                    // first token tells us the type of the collection
                    switch ($collection[$tag_index]->name) {
                        case 'tr':
                        case 'tbody':
                            $content[] = $collection;
                            break;
                        case 'caption':
                            if ($caption !== false) break;
                            $caption = $collection;
                            break;
                        case 'thead':
                        case 'tfoot':
                            // access the appropriate variable, $thead or $tfoot
                            $var = $collection[$tag_index]->name;
                            if ($$var === false) {
                                $$var = $collection;
                            } else {
                                // transmutate the first and less entries into
                                // tbody tags, and then put into content
                                $collection[$tag_index]->name = 'tbody';
                                $collection[count($collection)-1]->name = 'tbody';
                                $content[] = $collection;
                            }
                            break;
                         case 'colgroup':
                            $cols[] = $collection;
                            break;
                    }
                    $collection = array();
                    $is_collecting = false;
                    $tag_index = 0;
                } else {
                    // add the node to the collection
                    $collection[] = $token;
                }
            }

            // terminate
            if ($token === false) break;

            if ($is_child) {
                // determine what we're dealing with
                if ($token->name == 'col') {
                    // the only empty tag in the possie, we can handle it
                    // immediately
                    $cols[] = array_merge($collection, array($token));
                    $collection = array();
                    $tag_index = 0;
                    continue;
                }
                switch($token->name) {
                    case 'caption':
                    case 'colgroup':
                    case 'thead':
                    case 'tfoot':
                    case 'tbody':
                    case 'tr':
                        $is_collecting = true;
                        $collection[] = $token;
                        continue;
                    default:
                        if (!empty($token->is_whitespace)) {
                            $collection[] = $token;
                            $tag_index++;
                        }
                        continue;
                }
            }
        }

        if (empty($content)) return false;

        $ret = array();
        if ($caption !== false) $ret = array_merge($ret, $caption);
        if ($cols !== false)    foreach ($cols as $token_array) $ret = array_merge($ret, $token_array);
        if ($thead !== false)   $ret = array_merge($ret, $thead);
        if ($tfoot !== false)   $ret = array_merge($ret, $tfoot);
        foreach ($content as $token_array) $ret = array_merge($ret, $token_array);
        if (!empty($collection) && $is_collecting == false){
            // grab the trailing space
            $ret = array_merge($ret, $collection);
        }

        array_pop($tokens_of_children); // remove phantom token

        return ($ret === $tokens_of_children) ? true : $ret;

    }
}





class HTMLPurifier_DefinitionCache_Decorator extends HTMLPurifier_DefinitionCache
{

    /**
     * Cache object we are decorating
     */
    public $cache;

    public function __construct() {}

    /**
     * Lazy decorator function
     * @param $cache Reference to cache object to decorate
     */
    public function decorate(&$cache) {
        $decorator = $this->copy();
        // reference is necessary for mocks in PHP 4
        $decorator->cache =& $cache;
        $decorator->type  = $cache->type;
        return $decorator;
    }

    /**
     * Cross-compatible clone substitute
     */
    public function copy() {
        return new HTMLPurifier_DefinitionCache_Decorator();
    }

    public function add($def, $config) {
        return $this->cache->add($def, $config);
    }

    public function set($def, $config) {
        return $this->cache->set($def, $config);
    }

    public function replace($def, $config) {
        return $this->cache->replace($def, $config);
    }

    public function get($config) {
        return $this->cache->get($config);
    }

    public function remove($config) {
        return $this->cache->remove($config);
    }

    public function flush($config) {
        return $this->cache->flush($config);
    }

    public function cleanup($config) {
        return $this->cache->cleanup($config);
    }

}





/**
 * Null cache object to use when no caching is on.
 */
class HTMLPurifier_DefinitionCache_Null extends HTMLPurifier_DefinitionCache
{

    public function add($def, $config) {
        return false;
    }

    public function set($def, $config) {
        return false;
    }

    public function replace($def, $config) {
        return false;
    }

    public function remove($config) {
        return false;
    }

    public function get($config) {
        return false;
    }

    public function flush($config) {
        return false;
    }

    public function cleanup($config) {
        return false;
    }

}





class HTMLPurifier_DefinitionCache_Serializer extends
      HTMLPurifier_DefinitionCache
{

    public function add($def, $config) {
        if (!$this->checkDefType($def)) return;
        $file = $this->generateFilePath($config);
        if (file_exists($file)) return false;
        if (!$this->_prepareDir($config)) return false;
        return $this->_write($file, serialize($def), $config);
    }

    public function set($def, $config) {
        if (!$this->checkDefType($def)) return;
        $file = $this->generateFilePath($config);
        if (!$this->_prepareDir($config)) return false;
        return $this->_write($file, serialize($def), $config);
    }

    public function replace($def, $config) {
        if (!$this->checkDefType($def)) return;
        $file = $this->generateFilePath($config);
        if (!file_exists($file)) return false;
        if (!$this->_prepareDir($config)) return false;
        return $this->_write($file, serialize($def), $config);
    }

    public function get($config) {
        $file = $this->generateFilePath($config);
        if (!file_exists($file)) return false;
        return unserialize(file_get_contents($file));
    }

    public function remove($config) {
        $file = $this->generateFilePath($config);
        if (!file_exists($file)) return false;
        return unlink($file);
    }

    public function flush($config) {
        if (!$this->_prepareDir($config)) return false;
        $dir = $this->generateDirectoryPath($config);
        $dh  = opendir($dir);
        while (false !== ($filename = readdir($dh))) {
            if (empty($filename)) continue;
            if ($filename[0] === '.') continue;
            unlink($dir . '/' . $filename);
        }
    }

    public function cleanup($config) {
        if (!$this->_prepareDir($config)) return false;
        $dir = $this->generateDirectoryPath($config);
        $dh  = opendir($dir);
        while (false !== ($filename = readdir($dh))) {
            if (empty($filename)) continue;
            if ($filename[0] === '.') continue;
            $key = substr($filename, 0, strlen($filename) - 4);
            if ($this->isOld($key, $config)) unlink($dir . '/' . $filename);
        }
    }

    /**
     * Generates the file path to the serial file corresponding to
     * the configuration and definition name
     * @todo Make protected
     */
    public function generateFilePath($config) {
        $key = $this->generateKey($config);
        return $this->generateDirectoryPath($config) . '/' . $key . '.ser';
    }

    /**
     * Generates the path to the directory contain this cache's serial files
     * @note No trailing slash
     * @todo Make protected
     */
    public function generateDirectoryPath($config) {
        $base = $this->generateBaseDirectoryPath($config);
        return $base . '/' . $this->type;
    }

    /**
     * Generates path to base directory that contains all definition type
     * serials
     * @todo Make protected
     */
    public function generateBaseDirectoryPath($config) {
        $base = $config->get('Cache.SerializerPath');
        $base = is_null($base) ? HTMLPURIFIER_PREFIX . '/HTMLPurifier/DefinitionCache/Serializer' : $base;
        return $base;
    }

    /**
     * Convenience wrapper function for file_put_contents
     * @param $file File name to write to
     * @param $data Data to write into file
     * @param $config Config object
     * @return Number of bytes written if success, or false if failure.
     */
    private function _write($file, $data, $config) {
        $result = file_put_contents($file, $data);
        if ($result !== false) {
            // set permissions of the new file (no execute)
            $chmod = $config->get('Cache.SerializerPermissions');
            if (!$chmod) {
                $chmod = 0644; // invalid config or simpletest
            }
            $chmod = $chmod & 0666;
            chmod($file, $chmod);
        }
        return $result;
    }

    /**
     * Prepares the directory that this type stores the serials in
     * @param $config Config object
     * @return True if successful
     */
    private function _prepareDir($config) {
        $directory = $this->generateDirectoryPath($config);
        $chmod = $config->get('Cache.SerializerPermissions');
        if (!$chmod) {
            $chmod = 0755; // invalid config or simpletest
        }
        if (!is_dir($directory)) {
            $base = $this->generateBaseDirectoryPath($config);
            if (!is_dir($base)) {
                trigger_error('Base directory '.$base.' does not exist,
                    please create or change using %Cache.SerializerPath',
                    E_USER_WARNING);
                return false;
            } elseif (!$this->_testPermissions($base, $chmod)) {
                return false;
            }
            $old = umask(0000);
            mkdir($directory, $chmod);
            umask($old);
        } elseif (!$this->_testPermissions($directory, $chmod)) {
            return false;
        }
        return true;
    }

    /**
     * Tests permissions on a directory and throws out friendly
     * error messages and attempts to chmod it itself if possible
     * @param $dir Directory path
     * @param $chmod Permissions
     * @return True if directory writable
     */
    private function _testPermissions($dir, $chmod) {
        // early abort, if it is writable, everything is hunky-dory
        if (is_writable($dir)) return true;
        if (!is_dir($dir)) {
            // generally, you'll want to handle this beforehand
            // so a more specific error message can be given
            trigger_error('Directory '.$dir.' does not exist',
                E_USER_WARNING);
            return false;
        }
        if (function_exists('posix_getuid')) {
            // POSIX system, we can give more specific advice
            if (fileowner($dir) === posix_getuid()) {
                // we can chmod it ourselves
                $chmod = $chmod | 0700;
                if (chmod($dir, $chmod)) return true;
            } elseif (filegroup($dir) === posix_getgid()) {
                $chmod = $chmod | 0070;
            } else {
                // PHP's probably running as nobody, so we'll
                // need to give global permissions
                $chmod = $chmod | 0777;
            }
            trigger_error('Directory '.$dir.' not writable, '.
                'please chmod to ' . decoct($chmod),
                E_USER_WARNING);
        } else {
            // generic error message
            trigger_error('Directory '.$dir.' not writable, '.
                'please alter file permissions',
                E_USER_WARNING);
        }
        return false;
    }

}





/**
 * Definition cache decorator class that cleans up the cache
 * whenever there is a cache miss.
 */
class HTMLPurifier_DefinitionCache_Decorator_Cleanup extends
      HTMLPurifier_DefinitionCache_Decorator
{

    public $name = 'Cleanup';

    public function copy() {
        return new HTMLPurifier_DefinitionCache_Decorator_Cleanup();
    }

    public function add($def, $config) {
        $status = parent::add($def, $config);
        if (!$status) parent::cleanup($config);
        return $status;
    }

    public function set($def, $config) {
        $status = parent::set($def, $config);
        if (!$status) parent::cleanup($config);
        return $status;
    }

    public function replace($def, $config) {
        $status = parent::replace($def, $config);
        if (!$status) parent::cleanup($config);
        return $status;
    }

    public function get($config) {
        $ret = parent::get($config);
        if (!$ret) parent::cleanup($config);
        return $ret;
    }

}





/**
 * Definition cache decorator class that saves all cache retrievals
 * to PHP's memory; good for unit tests or circumstances where
 * there are lots of configuration objects floating around.
 */
class HTMLPurifier_DefinitionCache_Decorator_Memory extends
      HTMLPurifier_DefinitionCache_Decorator
{

    protected $definitions;
    public $name = 'Memory';

    public function copy() {
        return new HTMLPurifier_DefinitionCache_Decorator_Memory();
    }

    public function add($def, $config) {
        $status = parent::add($def, $config);
        if ($status) $this->definitions[$this->generateKey($config)] = $def;
        return $status;
    }

    public function set($def, $config) {
        $status = parent::set($def, $config);
        if ($status) $this->definitions[$this->generateKey($config)] = $def;
        return $status;
    }

    public function replace($def, $config) {
        $status = parent::replace($def, $config);
        if ($status) $this->definitions[$this->generateKey($config)] = $def;
        return $status;
    }

    public function get($config) {
        $key = $this->generateKey($config);
        if (isset($this->definitions[$key])) return $this->definitions[$key];
        $this->definitions[$key] = parent::get($config);
        return $this->definitions[$key];
    }

}





/**
 * XHTML 1.1 Bi-directional Text Module, defines elements that
 * declare directionality of content. Text Extension Module.
 */
class HTMLPurifier_HTMLModule_Bdo extends HTMLPurifier_HTMLModule
{

    public $name = 'Bdo';
    public $attr_collections = array(
        'I18N' => array('dir' => false)
    );

    public function setup($config) {
        $bdo = $this->addElement(
            'bdo', 'Inline', 'Inline', array('Core', 'Lang'),
            array(
                'dir' => 'Enum#ltr,rtl', // required
                // The Abstract Module specification has the attribute
                // inclusions wrong for bdo: bdo allows Lang
            )
        );
        $bdo->attr_transform_post['required-dir'] = new HTMLPurifier_AttrTransform_BdoDir();

        $this->attr_collections['I18N']['dir'] = 'Enum#ltr,rtl';
    }

}





class HTMLPurifier_HTMLModule_CommonAttributes extends HTMLPurifier_HTMLModule
{
    public $name = 'CommonAttributes';

    public $attr_collections = array(
        'Core' => array(
            0 => array('Style'),
            // 'xml:space' => false,
            'class' => 'Class',
            'id' => 'ID',
            'title' => 'CDATA',
        ),
        'Lang' => array(),
        'I18N' => array(
            0 => array('Lang'), // proprietary, for xml:lang/lang
        ),
        'Common' => array(
            0 => array('Core', 'I18N')
        )
    );

}





/**
 * XHTML 1.1 Edit Module, defines editing-related elements. Text Extension
 * Module.
 */
class HTMLPurifier_HTMLModule_Edit extends HTMLPurifier_HTMLModule
{

    public $name = 'Edit';

    public function setup($config) {
        $contents = 'Chameleon: #PCDATA | Inline ! #PCDATA | Flow';
        $attr = array(
            'cite' => 'URI',
            // 'datetime' => 'Datetime', // not implemented
        );
        $this->addElement('del', 'Inline', $contents, 'Common', $attr);
        $this->addElement('ins', 'Inline', $contents, 'Common', $attr);
    }

    // HTML 4.01 specifies that ins/del must not contain block
    // elements when used in an inline context, chameleon is
    // a complicated workaround to acheive this effect

    // Inline context ! Block context (exclamation mark is
    // separator, see getChildDef for parsing)

    public $defines_child_def = true;
    public function getChildDef($def) {
        if ($def->content_model_type != 'chameleon') return false;
        $value = explode('!', $def->content_model);
        return new HTMLPurifier_ChildDef_Chameleon($value[0], $value[1]);
    }

}





/**
 * XHTML 1.1 Forms module, defines all form-related elements found in HTML 4.
 */
class HTMLPurifier_HTMLModule_Forms extends HTMLPurifier_HTMLModule
{
    public $name = 'Forms';
    public $safe = false;

    public $content_sets = array(
        'Block' => 'Form',
        'Inline' => 'Formctrl',
    );

    public function setup($config) {
        $form = $this->addElement('form', 'Form',
          'Required: Heading | List | Block | fieldset', 'Common', array(
            'accept' => 'ContentTypes',
            'accept-charset' => 'Charsets',
            'action*' => 'URI',
            'method' => 'Enum#get,post',
            // really ContentType, but these two are the only ones used today
            'enctype' => 'Enum#application/x-www-form-urlencoded,multipart/form-data',
        ));
        $form->excludes = array('form' => true);

        $input = $this->addElement('input', 'Formctrl', 'Empty', 'Common', array(
            'accept' => 'ContentTypes',
            'accesskey' => 'Character',
            'alt' => 'Text',
            'checked' => 'Bool#checked',
            'disabled' => 'Bool#disabled',
            'maxlength' => 'Number',
            'name' => 'CDATA',
            'readonly' => 'Bool#readonly',
            'size' => 'Number',
            'src' => 'URI#embeds',
            'tabindex' => 'Number',
            'type' => 'Enum#text,password,checkbox,button,radio,submit,reset,file,hidden,image',
            'value' => 'CDATA',
        ));
        $input->attr_transform_post[] = new HTMLPurifier_AttrTransform_Input();

        $this->addElement('select', 'Formctrl', 'Required: optgroup | option', 'Common', array(
            'disabled' => 'Bool#disabled',
            'multiple' => 'Bool#multiple',
            'name' => 'CDATA',
            'size' => 'Number',
            'tabindex' => 'Number',
        ));

        $this->addElement('option', false, 'Optional: #PCDATA', 'Common', array(
            'disabled' => 'Bool#disabled',
            'label' => 'Text',
            'selected' => 'Bool#selected',
            'value' => 'CDATA',
        ));
        // It's illegal for there to be more than one selected, but not
        // be multiple. Also, no selected means undefined behavior. This might
        // be difficult to implement; perhaps an injector, or a context variable.

        $textarea = $this->addElement('textarea', 'Formctrl', 'Optional: #PCDATA', 'Common', array(
            'accesskey' => 'Character',
            'cols*' => 'Number',
            'disabled' => 'Bool#disabled',
            'name' => 'CDATA',
            'readonly' => 'Bool#readonly',
            'rows*' => 'Number',
            'tabindex' => 'Number',
        ));
        $textarea->attr_transform_pre[] = new HTMLPurifier_AttrTransform_Textarea();

        $button = $this->addElement('button', 'Formctrl', 'Optional: #PCDATA | Heading | List | Block | Inline', 'Common', array(
            'accesskey' => 'Character',
            'disabled' => 'Bool#disabled',
            'name' => 'CDATA',
            'tabindex' => 'Number',
            'type' => 'Enum#button,submit,reset',
            'value' => 'CDATA',
        ));

        // For exclusions, ideally we'd specify content sets, not literal elements
        $button->excludes = $this->makeLookup(
            'form', 'fieldset', // Form
            'input', 'select', 'textarea', 'label', 'button', // Formctrl
            'a' // as per HTML 4.01 spec, this is omitted by modularization
        );

        // Extra exclusion: img usemap="" is not permitted within this element.
        // We'll omit this for now, since we don't have any good way of
        // indicating it yet.

        // This is HIGHLY user-unfriendly; we need a custom child-def for this
        $this->addElement('fieldset', 'Form', 'Custom: (#WS?,legend,(Flow|#PCDATA)*)', 'Common');

        $label = $this->addElement('label', 'Formctrl', 'Optional: #PCDATA | Inline', 'Common', array(
            'accesskey' => 'Character',
            // 'for' => 'IDREF', // IDREF not implemented, cannot allow
        ));
        $label->excludes = array('label' => true);

        $this->addElement('legend', false, 'Optional: #PCDATA | Inline', 'Common', array(
            'accesskey' => 'Character',
        ));

        $this->addElement('optgroup', false, 'Required: option', 'Common', array(
            'disabled' => 'Bool#disabled',
            'label*' => 'Text',
        ));

        // Don't forget an injector for <isindex>. This one's a little complex
        // because it maps to multiple elements.

    }
}





/**
 * XHTML 1.1 Hypertext Module, defines hypertext links. Core Module.
 */
class HTMLPurifier_HTMLModule_Hypertext extends HTMLPurifier_HTMLModule
{

    public $name = 'Hypertext';

    public function setup($config) {
        $a = $this->addElement(
            'a', 'Inline', 'Inline', 'Common',
            array(
                // 'accesskey' => 'Character',
                // 'charset' => 'Charset',
                'href' => 'URI',
                // 'hreflang' => 'LanguageCode',
                'rel' => new HTMLPurifier_AttrDef_HTML_LinkTypes('rel'),
                'rev' => new HTMLPurifier_AttrDef_HTML_LinkTypes('rev'),
                // 'tabindex' => 'Number',
                // 'type' => 'ContentType',
            )
        );
        $a->formatting = true;
        $a->excludes = array('a' => true);
    }

}





/**
 * XHTML 1.1 Image Module provides basic image embedding.
 * @note There is specialized code for removing empty images in
 *       HTMLPurifier_Strategy_RemoveForeignElements
 */
class HTMLPurifier_HTMLModule_Image extends HTMLPurifier_HTMLModule
{

    public $name = 'Image';

    public function setup($config) {
        $max = $config->get('HTML.MaxImgLength');
        $img = $this->addElement(
            'img', 'Inline', 'Empty', 'Common',
            array(
                'alt*' => 'Text',
                // According to the spec, it's Length, but percents can
                // be abused, so we allow only Pixels.
                'height' => 'Pixels#' . $max,
                'width'  => 'Pixels#' . $max,
                'longdesc' => 'URI',
                'src*' => new HTMLPurifier_AttrDef_URI(true), // embedded
            )
        );
        if ($max === null || $config->get('HTML.Trusted')) {
            $img->attr['height'] =
            $img->attr['width'] = 'Length';
        }

        // kind of strange, but splitting things up would be inefficient
        $img->attr_transform_pre[] =
        $img->attr_transform_post[] =
            new HTMLPurifier_AttrTransform_ImgRequired();
    }

}





/**
 * XHTML 1.1 Legacy module defines elements that were previously
 * deprecated.
 *
 * @note Not all legacy elements have been implemented yet, which
 *       is a bit of a reverse problem as compared to browsers! In
 *       addition, this legacy module may implement a bit more than
 *       mandated by XHTML 1.1.
 *
 * This module can be used in combination with TransformToStrict in order
 * to transform as many deprecated elements as possible, but retain
 * questionably deprecated elements that do not have good alternatives
 * as well as transform elements that don't have an implementation.
 * See docs/ref-strictness.txt for more details.
 */

class HTMLPurifier_HTMLModule_Legacy extends HTMLPurifier_HTMLModule
{

    public $name = 'Legacy';

    public function setup($config) {

        $this->addElement('basefont', 'Inline', 'Empty', false, array(
            'color' => 'Color',
            'face' => 'Text', // extremely broad, we should
            'size' => 'Text', // tighten it
            'id' => 'ID'
        ));
        $this->addElement('center', 'Block', 'Flow', 'Common');
        $this->addElement('dir', 'Block', 'Required: li', 'Common', array(
            'compact' => 'Bool#compact'
        ));
        $this->addElement('font', 'Inline', 'Inline', array('Core', 'I18N'), array(
            'color' => 'Color',
            'face' => 'Text', // extremely broad, we should
            'size' => 'Text', // tighten it
        ));
        $this->addElement('menu', 'Block', 'Required: li', 'Common', array(
            'compact' => 'Bool#compact'
        ));

        $s = $this->addElement('s', 'Inline', 'Inline', 'Common');
        $s->formatting = true;

        $strike = $this->addElement('strike', 'Inline', 'Inline', 'Common');
        $strike->formatting = true;

        $u = $this->addElement('u', 'Inline', 'Inline', 'Common');
        $u->formatting = true;

        // setup modifications to old elements

        $align = 'Enum#left,right,center,justify';

        $address = $this->addBlankElement('address');
        $address->content_model = 'Inline | #PCDATA | p';
        $address->content_model_type = 'optional';
        $address->child = false;

        $blockquote = $this->addBlankElement('blockquote');
        $blockquote->content_model = 'Flow | #PCDATA';
        $blockquote->content_model_type = 'optional';
        $blockquote->child = false;

        $br = $this->addBlankElement('br');
        $br->attr['clear'] = 'Enum#left,all,right,none';

        $caption = $this->addBlankElement('caption');
        $caption->attr['align'] = 'Enum#top,bottom,left,right';

        $div = $this->addBlankElement('div');
        $div->attr['align'] = $align;

        $dl = $this->addBlankElement('dl');
        $dl->attr['compact'] = 'Bool#compact';

        for ($i = 1; $i <= 6; $i++) {
            $h = $this->addBlankElement("h$i");
            $h->attr['align'] = $align;
        }

        $hr = $this->addBlankElement('hr');
        $hr->attr['align'] = $align;
        $hr->attr['noshade'] = 'Bool#noshade';
        $hr->attr['size'] = 'Pixels';
        $hr->attr['width'] = 'Length';

        $img = $this->addBlankElement('img');
        $img->attr['align'] = 'Enum#top,middle,bottom,left,right';
        $img->attr['border'] = 'Pixels';
        $img->attr['hspace'] = 'Pixels';
        $img->attr['vspace'] = 'Pixels';

        // figure out this integer business

        $li = $this->addBlankElement('li');
        $li->attr['value'] = new HTMLPurifier_AttrDef_Integer();
        $li->attr['type']  = 'Enum#s:1,i,I,a,A,disc,square,circle';

        $ol = $this->addBlankElement('ol');
        $ol->attr['compact'] = 'Bool#compact';
        $ol->attr['start'] = new HTMLPurifier_AttrDef_Integer();
        $ol->attr['type'] = 'Enum#s:1,i,I,a,A';

        $p = $this->addBlankElement('p');
        $p->attr['align'] = $align;

        $pre = $this->addBlankElement('pre');
        $pre->attr['width'] = 'Number';

        // script omitted

        $table = $this->addBlankElement('table');
        $table->attr['align'] = 'Enum#left,center,right';
        $table->attr['bgcolor'] = 'Color';

        $tr = $this->addBlankElement('tr');
        $tr->attr['bgcolor'] = 'Color';

        $th = $this->addBlankElement('th');
        $th->attr['bgcolor'] = 'Color';
        $th->attr['height'] = 'Length';
        $th->attr['nowrap'] = 'Bool#nowrap';
        $th->attr['width'] = 'Length';

        $td = $this->addBlankElement('td');
        $td->attr['bgcolor'] = 'Color';
        $td->attr['height'] = 'Length';
        $td->attr['nowrap'] = 'Bool#nowrap';
        $td->attr['width'] = 'Length';

        $ul = $this->addBlankElement('ul');
        $ul->attr['compact'] = 'Bool#compact';
        $ul->attr['type'] = 'Enum#square,disc,circle';

    }

}





/**
 * XHTML 1.1 List Module, defines list-oriented elements. Core Module.
 */
class HTMLPurifier_HTMLModule_List extends HTMLPurifier_HTMLModule
{

    public $name = 'List';

    // According to the abstract schema, the List content set is a fully formed
    // one or more expr, but it invariably occurs in an optional declaration
    // so we're not going to do that subtlety. It might cause trouble
    // if a user defines "List" and expects that multiple lists are
    // allowed to be specified, but then again, that's not very intuitive.
    // Furthermore, the actual XML Schema may disagree. Regardless,
    // we don't have support for such nested expressions without using
    // the incredibly inefficient and draconic Custom ChildDef.

    public $content_sets = array('Flow' => 'List');

    public function setup($config) {
        $ol = $this->addElement('ol', 'List', 'Required: li', 'Common');
        $ol->wrap = "li";
        $ul = $this->addElement('ul', 'List', 'Required: li', 'Common');
        $ul->wrap = "li";
        $this->addElement('dl', 'List', 'Required: dt | dd', 'Common');

        $this->addElement('li', false, 'Flow', 'Common');

        $this->addElement('dd', false, 'Flow', 'Common');
        $this->addElement('dt', false, 'Inline', 'Common');
    }

}





class HTMLPurifier_HTMLModule_Name extends HTMLPurifier_HTMLModule
{

    public $name = 'Name';

    public function setup($config) {
        $elements = array('a', 'applet', 'form', 'frame', 'iframe', 'img', 'map');
        foreach ($elements as $name) {
            $element = $this->addBlankElement($name);
            $element->attr['name'] = 'CDATA';
            if (!$config->get('HTML.Attr.Name.UseCDATA')) {
                $element->attr_transform_post['NameSync'] = new HTMLPurifier_AttrTransform_NameSync();
            }
        }
    }

}





/**
 * Module adds the nofollow attribute transformation to a tags.  It
 * is enabled by HTML.Nofollow
 */
class HTMLPurifier_HTMLModule_Nofollow extends HTMLPurifier_HTMLModule
{

    public $name = 'Nofollow';

    public function setup($config) {
        $a = $this->addBlankElement('a');
        $a->attr_transform_post[] = new HTMLPurifier_AttrTransform_Nofollow();
    }

}





class HTMLPurifier_HTMLModule_NonXMLCommonAttributes extends HTMLPurifier_HTMLModule
{
    public $name = 'NonXMLCommonAttributes';

    public $attr_collections = array(
        'Lang' => array(
            'lang' => 'LanguageCode',
        )
    );
}





/**
 * XHTML 1.1 Object Module, defines elements for generic object inclusion
 * @warning Users will commonly use <embed> to cater to legacy browsers: this
 *      module does not allow this sort of behavior
 */
class HTMLPurifier_HTMLModule_Object extends HTMLPurifier_HTMLModule
{

    public $name = 'Object';
    public $safe = false;

    public function setup($config) {

        $this->addElement('object', 'Inline', 'Optional: #PCDATA | Flow | param', 'Common',
            array(
                'archive' => 'URI',
                'classid' => 'URI',
                'codebase' => 'URI',
                'codetype' => 'Text',
                'data' => 'URI',
                'declare' => 'Bool#declare',
                'height' => 'Length',
                'name' => 'CDATA',
                'standby' => 'Text',
                'tabindex' => 'Number',
                'type' => 'ContentType',
                'width' => 'Length'
            )
        );

        $this->addElement('param', false, 'Empty', false,
            array(
                'id' => 'ID',
                'name*' => 'Text',
                'type' => 'Text',
                'value' => 'Text',
                'valuetype' => 'Enum#data,ref,object'
           )
        );

    }

}





/**
 * XHTML 1.1 Presentation Module, defines simple presentation-related
 * markup. Text Extension Module.
 * @note The official XML Schema and DTD specs further divide this into
 *       two modules:
 *          - Block Presentation (hr)
 *          - Inline Presentation (b, big, i, small, sub, sup, tt)
 *       We have chosen not to heed this distinction, as content_sets
 *       provides satisfactory disambiguation.
 */
class HTMLPurifier_HTMLModule_Presentation extends HTMLPurifier_HTMLModule
{

    public $name = 'Presentation';

    public function setup($config) {
        $this->addElement('hr',     'Block',  'Empty',  'Common');
        $this->addElement('sub',    'Inline', 'Inline', 'Common');
        $this->addElement('sup',    'Inline', 'Inline', 'Common');
        $b = $this->addElement('b',      'Inline', 'Inline', 'Common');
        $b->formatting = true;
        $big = $this->addElement('big',    'Inline', 'Inline', 'Common');
        $big->formatting = true;
        $i = $this->addElement('i',      'Inline', 'Inline', 'Common');
        $i->formatting = true;
        $small = $this->addElement('small',  'Inline', 'Inline', 'Common');
        $small->formatting = true;
        $tt = $this->addElement('tt',     'Inline', 'Inline', 'Common');
        $tt->formatting = true;
    }

}





/**
 * Module defines proprietary tags and attributes in HTML.
 * @warning If this module is enabled, standards-compliance is off!
 */
class HTMLPurifier_HTMLModule_Proprietary extends HTMLPurifier_HTMLModule
{

    public $name = 'Proprietary';

    public function setup($config) {

        $this->addElement('marquee', 'Inline', 'Flow', 'Common',
            array(
                'direction' => 'Enum#left,right,up,down',
                'behavior' => 'Enum#alternate',
                'width' => 'Length',
                'height' => 'Length',
                'scrolldelay' => 'Number',
                'scrollamount' => 'Number',
                'loop' => 'Number',
                'bgcolor' => 'Color',
                'hspace' => 'Pixels',
                'vspace' => 'Pixels',
            )
        );

    }

}





/**
 * XHTML 1.1 Ruby Annotation Module, defines elements that indicate
 * short runs of text alongside base text for annotation or pronounciation.
 */
class HTMLPurifier_HTMLModule_Ruby extends HTMLPurifier_HTMLModule
{

    public $name = 'Ruby';

    public function setup($config) {
        $this->addElement('ruby', 'Inline',
            'Custom: ((rb, (rt | (rp, rt, rp))) | (rbc, rtc, rtc?))',
            'Common');
        $this->addElement('rbc', false, 'Required: rb', 'Common');
        $this->addElement('rtc', false, 'Required: rt', 'Common');
        $rb = $this->addElement('rb', false, 'Inline', 'Common');
        $rb->excludes = array('ruby' => true);
        $rt = $this->addElement('rt', false, 'Inline', 'Common', array('rbspan' => 'Number'));
        $rt->excludes = array('ruby' => true);
        $this->addElement('rp', false, 'Optional: #PCDATA', 'Common');
    }

}





/**
 * A "safe" embed module. See SafeObject. This is a proprietary element.
 */
class HTMLPurifier_HTMLModule_SafeEmbed extends HTMLPurifier_HTMLModule
{

    public $name = 'SafeEmbed';

    public function setup($config) {

        $max = $config->get('HTML.MaxImgLength');
        $embed = $this->addElement(
            'embed', 'Inline', 'Empty', 'Common',
            array(
                'src*' => 'URI#embedded',
                'type' => 'Enum#application/x-shockwave-flash',
                'width' => 'Pixels#' . $max,
                'height' => 'Pixels#' . $max,
                'allowscriptaccess' => 'Enum#never',
                'allownetworking' => 'Enum#internal',
                'flashvars' => 'Text',
                'wmode' => 'Enum#window,transparent,opaque',
                'name' => 'ID',
            )
        );
        $embed->attr_transform_post[] = new HTMLPurifier_AttrTransform_SafeEmbed();

    }

}





/**
 * A "safe" object module. In theory, objects permitted by this module will
 * be safe, and untrusted users can be allowed to embed arbitrary flash objects
 * (maybe other types too, but only Flash is supported as of right now).
 * Highly experimental.
 */
class HTMLPurifier_HTMLModule_SafeObject extends HTMLPurifier_HTMLModule
{

    public $name = 'SafeObject';

    public function setup($config) {

        // These definitions are not intrinsically safe: the attribute transforms
        // are a vital part of ensuring safety.

        $max = $config->get('HTML.MaxImgLength');
        $object = $this->addElement(
            'object',
            'Inline',
            'Optional: param | Flow | #PCDATA',
            'Common',
            array(
                // While technically not required by the spec, we're forcing
                // it to this value.
                'type'   => 'Enum#application/x-shockwave-flash',
                'width'  => 'Pixels#' . $max,
                'height' => 'Pixels#' . $max,
                'data'   => 'URI#embedded',
                'codebase' => new HTMLPurifier_AttrDef_Enum(array(
                    'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0')),
            )
        );
        $object->attr_transform_post[] = new HTMLPurifier_AttrTransform_SafeObject();

        $param = $this->addElement('param', false, 'Empty', false,
            array(
                'id' => 'ID',
                'name*' => 'Text',
                'value' => 'Text'
            )
        );
        $param->attr_transform_post[] = new HTMLPurifier_AttrTransform_SafeParam();
        $this->info_injector[] = 'SafeObject';

    }

}





/*

WARNING: THIS MODULE IS EXTREMELY DANGEROUS AS IT ENABLES INLINE SCRIPTING
INSIDE HTML PURIFIER DOCUMENTS. USE ONLY WITH TRUSTED USER INPUT!!!

*/

/**
 * XHTML 1.1 Scripting module, defines elements that are used to contain
 * information pertaining to executable scripts or the lack of support
 * for executable scripts.
 * @note This module does not contain inline scripting elements
 */
class HTMLPurifier_HTMLModule_Scripting extends HTMLPurifier_HTMLModule
{
    public $name = 'Scripting';
    public $elements = array('script', 'noscript');
    public $content_sets = array('Block' => 'script | noscript', 'Inline' => 'script | noscript');
    public $safe = false;

    public function setup($config) {
        // TODO: create custom child-definition for noscript that
        // auto-wraps stray #PCDATA in a similar manner to
        // blockquote's custom definition (we would use it but
        // blockquote's contents are optional while noscript's contents
        // are required)

        // TODO: convert this to new syntax, main problem is getting
        // both content sets working

        // In theory, this could be safe, but I don't see any reason to
        // allow it.
        $this->info['noscript'] = new HTMLPurifier_ElementDef();
        $this->info['noscript']->attr = array( 0 => array('Common') );
        $this->info['noscript']->content_model = 'Heading | List | Block';
        $this->info['noscript']->content_model_type = 'required';

        $this->info['script'] = new HTMLPurifier_ElementDef();
        $this->info['script']->attr = array(
            'defer' => new HTMLPurifier_AttrDef_Enum(array('defer')),
            'src'   => new HTMLPurifier_AttrDef_URI(true),
            'type'  => new HTMLPurifier_AttrDef_Enum(array('text/javascript'))
        );
        $this->info['script']->content_model = '#PCDATA';
        $this->info['script']->content_model_type = 'optional';
        $this->info['script']->attr_transform_pre['type'] =
        $this->info['script']->attr_transform_post['type'] =
            new HTMLPurifier_AttrTransform_ScriptRequired();
    }
}





/**
 * XHTML 1.1 Edit Module, defines editing-related elements. Text Extension
 * Module.
 */
class HTMLPurifier_HTMLModule_StyleAttribute extends HTMLPurifier_HTMLModule
{

    public $name = 'StyleAttribute';
    public $attr_collections = array(
        // The inclusion routine differs from the Abstract Modules but
        // is in line with the DTD and XML Schemas.
        'Style' => array('style' => false), // see constructor
        'Core' => array(0 => array('Style'))
    );

    public function setup($config) {
        $this->attr_collections['Style']['style'] = new HTMLPurifier_AttrDef_CSS();
    }

}





/**
 * XHTML 1.1 Tables Module, fully defines accessible table elements.
 */
class HTMLPurifier_HTMLModule_Tables extends HTMLPurifier_HTMLModule
{

    public $name = 'Tables';

    public function setup($config) {

        $this->addElement('caption', false, 'Inline', 'Common');

        $this->addElement('table', 'Block',
            new HTMLPurifier_ChildDef_Table(),  'Common',
            array(
                'border' => 'Pixels',
                'cellpadding' => 'Length',
                'cellspacing' => 'Length',
                'frame' => 'Enum#void,above,below,hsides,lhs,rhs,vsides,box,border',
                'rules' => 'Enum#none,groups,rows,cols,all',
                'summary' => 'Text',
                'width' => 'Length'
            )
        );

        // common attributes
        $cell_align = array(
            'align' => 'Enum#left,center,right,justify,char',
            'charoff' => 'Length',
            'valign' => 'Enum#top,middle,bottom,baseline',
        );

        $cell_t = array_merge(
            array(
                'abbr'    => 'Text',
                'colspan' => 'Number',
                'rowspan' => 'Number',
            ),
            $cell_align
        );
        $this->addElement('td', false, 'Flow', 'Common', $cell_t);
        $this->addElement('th', false, 'Flow', 'Common', $cell_t);

        $this->addElement('tr', false, 'Required: td | th', 'Common', $cell_align);

        $cell_col = array_merge(
            array(
                'span'  => 'Number',
                'width' => 'MultiLength',
            ),
            $cell_align
        );
        $this->addElement('col',      false, 'Empty',         'Common', $cell_col);
        $this->addElement('colgroup', false, 'Optional: col', 'Common', $cell_col);

        $this->addElement('tbody', false, 'Required: tr', 'Common', $cell_align);
        $this->addElement('thead', false, 'Required: tr', 'Common', $cell_align);
        $this->addElement('tfoot', false, 'Required: tr', 'Common', $cell_align);

    }

}





/**
 * XHTML 1.1 Target Module, defines target attribute in link elements.
 */
class HTMLPurifier_HTMLModule_Target extends HTMLPurifier_HTMLModule
{

    public $name = 'Target';

    public function setup($config) {
        $elements = array('a');
        foreach ($elements as $name) {
            $e = $this->addBlankElement($name);
            $e->attr = array(
                'target' => new HTMLPurifier_AttrDef_HTML_FrameTarget()
            );
        }
    }

}





/**
 * XHTML 1.1 Text Module, defines basic text containers. Core Module.
 * @note In the normative XML Schema specification, this module
 *       is further abstracted into the following modules:
 *          - Block Phrasal (address, blockquote, pre, h1, h2, h3, h4, h5, h6)
 *          - Block Structural (div, p)
 *          - Inline Phrasal (abbr, acronym, cite, code, dfn, em, kbd, q, samp, strong, var)
 *          - Inline Structural (br, span)
 *       This module, functionally, does not distinguish between these
 *       sub-modules, but the code is internally structured to reflect
 *       these distinctions.
 */
class HTMLPurifier_HTMLModule_Text extends HTMLPurifier_HTMLModule
{

    public $name = 'Text';
    public $content_sets = array(
        'Flow' => 'Heading | Block | Inline'
    );

    public function setup($config) {

        // Inline Phrasal -------------------------------------------------
        $this->addElement('abbr',    'Inline', 'Inline', 'Common');
        $this->addElement('acronym', 'Inline', 'Inline', 'Common');
        $this->addElement('cite',    'Inline', 'Inline', 'Common');
        $this->addElement('dfn',     'Inline', 'Inline', 'Common');
        $this->addElement('kbd',     'Inline', 'Inline', 'Common');
        $this->addElement('q',       'Inline', 'Inline', 'Common', array('cite' => 'URI'));
        $this->addElement('samp',    'Inline', 'Inline', 'Common');
        $this->addElement('var',     'Inline', 'Inline', 'Common');

        $em = $this->addElement('em',      'Inline', 'Inline', 'Common');
        $em->formatting = true;

        $strong = $this->addElement('strong',  'Inline', 'Inline', 'Common');
        $strong->formatting = true;

        $code = $this->addElement('code',    'Inline', 'Inline', 'Common');
        $code->formatting = true;

        // Inline Structural ----------------------------------------------
        $this->addElement('span', 'Inline', 'Inline', 'Common');
        $this->addElement('br',   'Inline', 'Empty',  'Core');

        // Block Phrasal --------------------------------------------------
        $this->addElement('address',     'Block', 'Inline', 'Common');
        $this->addElement('blockquote',  'Block', 'Optional: Heading | Block | List', 'Common', array('cite' => 'URI') );
        $pre = $this->addElement('pre', 'Block', 'Inline', 'Common');
        $pre->excludes = $this->makeLookup(
            'img', 'big', 'small', 'object', 'applet', 'font', 'basefont' );
        $this->addElement('h1', 'Heading', 'Inline', 'Common');
        $this->addElement('h2', 'Heading', 'Inline', 'Common');
        $this->addElement('h3', 'Heading', 'Inline', 'Common');
        $this->addElement('h4', 'Heading', 'Inline', 'Common');
        $this->addElement('h5', 'Heading', 'Inline', 'Common');
        $this->addElement('h6', 'Heading', 'Inline', 'Common');

        // Block Structural -----------------------------------------------
        $p = $this->addElement('p', 'Block', 'Inline', 'Common');
        $p->autoclose = array_flip(array("address", "blockquote", "center", "dir", "div", "dl", "fieldset", "ol", "p", "ul"));

        $this->addElement('div', 'Block', 'Flow', 'Common');

    }

}





/**
 * Abstract class for a set of proprietary modules that clean up (tidy)
 * poorly written HTML.
 * @todo Figure out how to protect some of these methods/properties
 */
class HTMLPurifier_HTMLModule_Tidy extends HTMLPurifier_HTMLModule
{

    /**
     * List of supported levels. Index zero is a special case "no fixes"
     * level.
     */
    public $levels = array(0 => 'none', 'light', 'medium', 'heavy');

    /**
     * Default level to place all fixes in. Disabled by default
     */
    public $defaultLevel = null;

    /**
     * Lists of fixes used by getFixesForLevel(). Format is:
     *      HTMLModule_Tidy->fixesForLevel[$level] = array('fix-1', 'fix-2');
     */
    public $fixesForLevel = array(
        'light'  => array(),
        'medium' => array(),
        'heavy'  => array()
    );

    /**
     * Lazy load constructs the module by determining the necessary
     * fixes to create and then delegating to the populate() function.
     * @todo Wildcard matching and error reporting when an added or
     *       subtracted fix has no effect.
     */
    public function setup($config) {

        // create fixes, initialize fixesForLevel
        $fixes = $this->makeFixes();
        $this->makeFixesForLevel($fixes);

        // figure out which fixes to use
        $level = $config->get('HTML.TidyLevel');
        $fixes_lookup = $this->getFixesForLevel($level);

        // get custom fix declarations: these need namespace processing
        $add_fixes    = $config->get('HTML.TidyAdd');
        $remove_fixes = $config->get('HTML.TidyRemove');

        foreach ($fixes as $name => $fix) {
            // needs to be refactored a little to implement globbing
            if (
                isset($remove_fixes[$name]) ||
                (!isset($add_fixes[$name]) && !isset($fixes_lookup[$name]))
            ) {
                unset($fixes[$name]);
            }
        }

        // populate this module with necessary fixes
        $this->populate($fixes);

    }

    /**
     * Retrieves all fixes per a level, returning fixes for that specific
     * level as well as all levels below it.
     * @param $level String level identifier, see $levels for valid values
     * @return Lookup up table of fixes
     */
    public function getFixesForLevel($level) {
        if ($level == $this->levels[0]) {
            return array();
        }
        $activated_levels = array();
        for ($i = 1, $c = count($this->levels); $i < $c; $i++) {
            $activated_levels[] = $this->levels[$i];
            if ($this->levels[$i] == $level) break;
        }
        if ($i == $c) {
            trigger_error(
                'Tidy level ' . htmlspecialchars($level) . ' not recognized',
                E_USER_WARNING
            );
            return array();
        }
        $ret = array();
        foreach ($activated_levels as $level) {
            foreach ($this->fixesForLevel[$level] as $fix) {
                $ret[$fix] = true;
            }
        }
        return $ret;
    }

    /**
     * Dynamically populates the $fixesForLevel member variable using
     * the fixes array. It may be custom overloaded, used in conjunction
     * with $defaultLevel, or not used at all.
     */
    public function makeFixesForLevel($fixes) {
        if (!isset($this->defaultLevel)) return;
        if (!isset($this->fixesForLevel[$this->defaultLevel])) {
            trigger_error(
                'Default level ' . $this->defaultLevel . ' does not exist',
                E_USER_ERROR
            );
            return;
        }
        $this->fixesForLevel[$this->defaultLevel] = array_keys($fixes);
    }

    /**
     * Populates the module with transforms and other special-case code
     * based on a list of fixes passed to it
     * @param $lookup Lookup table of fixes to activate
     */
    public function populate($fixes) {
        foreach ($fixes as $name => $fix) {
            // determine what the fix is for
            list($type, $params) = $this->getFixType($name);
            switch ($type) {
                case 'attr_transform_pre':
                case 'attr_transform_post':
                    $attr = $params['attr'];
                    if (isset($params['element'])) {
                        $element = $params['element'];
                        if (empty($this->info[$element])) {
                            $e = $this->addBlankElement($element);
                        } else {
                            $e = $this->info[$element];
                        }
                    } else {
                        $type = "info_$type";
                        $e = $this;
                    }
                    // PHP does some weird parsing when I do
                    // $e->$type[$attr], so I have to assign a ref.
                    $f =& $e->$type;
                    $f[$attr] = $fix;
                    break;
                case 'tag_transform':
                    $this->info_tag_transform[$params['element']] = $fix;
                    break;
                case 'child':
                case 'content_model_type':
                    $element = $params['element'];
                    if (empty($this->info[$element])) {
                        $e = $this->addBlankElement($element);
                    } else {
                        $e = $this->info[$element];
                    }
                    $e->$type = $fix;
                    break;
                default:
                    trigger_error("Fix type $type not supported", E_USER_ERROR);
                    break;
            }
        }
    }

    /**
     * Parses a fix name and determines what kind of fix it is, as well
     * as other information defined by the fix
     * @param $name String name of fix
     * @return array(string $fix_type, array $fix_parameters)
     * @note $fix_parameters is type dependant, see populate() for usage
     *       of these parameters
     */
    public function getFixType($name) {
        // parse it
        $property = $attr = null;
        if (strpos($name, '#') !== false) list($name, $property) = explode('#', $name);
        if (strpos($name, '@') !== false) list($name, $attr)     = explode('@', $name);

        // figure out the parameters
        $params = array();
        if ($name !== '')    $params['element'] = $name;
        if (!is_null($attr)) $params['attr'] = $attr;

        // special case: attribute transform
        if (!is_null($attr)) {
            if (is_null($property)) $property = 'pre';
            $type = 'attr_transform_' . $property;
            return array($type, $params);
        }

        // special case: tag transform
        if (is_null($property)) {
            return array('tag_transform', $params);
        }

        return array($property, $params);

    }

    /**
     * Defines all fixes the module will perform in a compact
     * associative array of fix name to fix implementation.
     */
    public function makeFixes() {}

}





class HTMLPurifier_HTMLModule_XMLCommonAttributes extends HTMLPurifier_HTMLModule
{
    public $name = 'XMLCommonAttributes';

    public $attr_collections = array(
        'Lang' => array(
            'xml:lang' => 'LanguageCode',
        )
    );
}





/**
 * Name is deprecated, but allowed in strict doctypes, so onl
 */
class HTMLPurifier_HTMLModule_Tidy_Name extends HTMLPurifier_HTMLModule_Tidy
{
    public $name = 'Tidy_Name';
    public $defaultLevel = 'heavy';
    public function makeFixes() {

        $r = array();

        // @name for img, a -----------------------------------------------
        // Technically, it's allowed even on strict, so we allow authors to use
        // it. However, it's deprecated in future versions of XHTML.
        $r['img@name'] =
        $r['a@name'] = new HTMLPurifier_AttrTransform_Name();

        return $r;
    }
}





class HTMLPurifier_HTMLModule_Tidy_Proprietary extends HTMLPurifier_HTMLModule_Tidy
{

    public $name = 'Tidy_Proprietary';
    public $defaultLevel = 'light';

    public function makeFixes() {
        $r = array();
        $r['table@background'] = new HTMLPurifier_AttrTransform_Background();
        $r['td@background']    = new HTMLPurifier_AttrTransform_Background();
        $r['th@background']    = new HTMLPurifier_AttrTransform_Background();
        $r['tr@background']    = new HTMLPurifier_AttrTransform_Background();
        $r['thead@background'] = new HTMLPurifier_AttrTransform_Background();
        $r['tfoot@background'] = new HTMLPurifier_AttrTransform_Background();
        $r['tbody@background'] = new HTMLPurifier_AttrTransform_Background();
        $r['table@height']     = new HTMLPurifier_AttrTransform_Length('height');
        return $r;
    }

}





class HTMLPurifier_HTMLModule_Tidy_XHTMLAndHTML4 extends HTMLPurifier_HTMLModule_Tidy
{

    public function makeFixes() {

        $r = array();

        // == deprecated tag transforms ===================================

        $r['font']   = new HTMLPurifier_TagTransform_Font();
        $r['menu']   = new HTMLPurifier_TagTransform_Simple('ul');
        $r['dir']    = new HTMLPurifier_TagTransform_Simple('ul');
        $r['center'] = new HTMLPurifier_TagTransform_Simple('div',  'text-align:center;');
        $r['u']      = new HTMLPurifier_TagTransform_Simple('span', 'text-decoration:underline;');
        $r['s']      = new HTMLPurifier_TagTransform_Simple('span', 'text-decoration:line-through;');
        $r['strike'] = new HTMLPurifier_TagTransform_Simple('span', 'text-decoration:line-through;');

        // == deprecated attribute transforms =============================

        $r['caption@align'] =
            new HTMLPurifier_AttrTransform_EnumToCSS('align', array(
                // we're following IE's behavior, not Firefox's, due
                // to the fact that no one supports caption-side:right,
                // W3C included (with CSS 2.1). This is a slightly
                // unreasonable attribute!
                'left'   => 'text-align:left;',
                'right'  => 'text-align:right;',
                'top'    => 'caption-side:top;',
                'bottom' => 'caption-side:bottom;' // not supported by IE
            ));


        // @align for img -------------------------------------------------
        $r['img@align'] =
            new HTMLPurifier_AttrTransform_EnumToCSS('align', array(
                'left'   => 'float:left;',
                'right'  => 'float:right;',
                'top'    => 'vertical-align:top;',
                'middle' => 'vertical-align:middle;',
                'bottom' => 'vertical-align:baseline;',
            ));

        // @align for table -----------------------------------------------
        $r['table@align'] =
            new HTMLPurifier_AttrTransform_EnumToCSS('align', array(
                'left'   => 'float:left;',
                'center' => 'margin-left:auto;margin-right:auto;',
                'right'  => 'float:right;'
            ));

        // @align for hr -----------------------------------------------
        $r['hr@align'] =
            new HTMLPurifier_AttrTransform_EnumToCSS('align', array(
                // we use both text-align and margin because these work
                // for different browsers (IE and Firefox, respectively)
                // and the melange makes for a pretty cross-compatible
                // solution
                'left'   => 'margin-left:0;margin-right:auto;text-align:left;',
                'center' => 'margin-left:auto;margin-right:auto;text-align:center;',
                'right'  => 'margin-left:auto;margin-right:0;text-align:right;'
            ));

        // @align for h1, h2, h3, h4, h5, h6, p, div ----------------------
        // {{{
            $align_lookup = array();
            $align_values = array('left', 'right', 'center', 'justify');
            foreach ($align_values as $v) $align_lookup[$v] = "text-align:$v;";
        // }}}
        $r['h1@align'] =
        $r['h2@align'] =
        $r['h3@align'] =
        $r['h4@align'] =
        $r['h5@align'] =
        $r['h6@align'] =
        $r['p@align']  =
        $r['div@align'] =
            new HTMLPurifier_AttrTransform_EnumToCSS('align', $align_lookup);

        // @bgcolor for table, tr, td, th ---------------------------------
        $r['table@bgcolor'] =
        $r['td@bgcolor'] =
        $r['th@bgcolor'] =
            new HTMLPurifier_AttrTransform_BgColor();

        // @border for img ------------------------------------------------
        $r['img@border'] = new HTMLPurifier_AttrTransform_Border();

        // @clear for br --------------------------------------------------
        $r['br@clear'] =
            new HTMLPurifier_AttrTransform_EnumToCSS('clear', array(
                'left'  => 'clear:left;',
                'right' => 'clear:right;',
                'all'   => 'clear:both;',
                'none'  => 'clear:none;',
            ));

        // @height for td, th ---------------------------------------------
        $r['td@height'] =
        $r['th@height'] =
            new HTMLPurifier_AttrTransform_Length('height');

        // @hspace for img ------------------------------------------------
        $r['img@hspace'] = new HTMLPurifier_AttrTransform_ImgSpace('hspace');

        // @noshade for hr ------------------------------------------------
        // this transformation is not precise but often good enough.
        // different browsers use different styles to designate noshade
        $r['hr@noshade'] =
            new HTMLPurifier_AttrTransform_BoolToCSS(
                'noshade',
                'color:#808080;background-color:#808080;border:0;'
            );

        // @nowrap for td, th ---------------------------------------------
        $r['td@nowrap'] =
        $r['th@nowrap'] =
            new HTMLPurifier_AttrTransform_BoolToCSS(
                'nowrap',
                'white-space:nowrap;'
            );

        // @size for hr  --------------------------------------------------
        $r['hr@size'] = new HTMLPurifier_AttrTransform_Length('size', 'height');

        // @type for li, ol, ul -------------------------------------------
        // {{{
            $ul_types = array(
                'disc'   => 'list-style-type:disc;',
                'square' => 'list-style-type:square;',
                'circle' => 'list-style-type:circle;'
            );
            $ol_types = array(
                '1'   => 'list-style-type:decimal;',
                'i'   => 'list-style-type:lower-roman;',
                'I'   => 'list-style-type:upper-roman;',
                'a'   => 'list-style-type:lower-alpha;',
                'A'   => 'list-style-type:upper-alpha;'
            );
            $li_types = $ul_types + $ol_types;
        // }}}

        $r['ul@type'] = new HTMLPurifier_AttrTransform_EnumToCSS('type', $ul_types);
        $r['ol@type'] = new HTMLPurifier_AttrTransform_EnumToCSS('type', $ol_types, true);
        $r['li@type'] = new HTMLPurifier_AttrTransform_EnumToCSS('type', $li_types, true);

        // @vspace for img ------------------------------------------------
        $r['img@vspace'] = new HTMLPurifier_AttrTransform_ImgSpace('vspace');

        // @width for hr, td, th ------------------------------------------
        $r['td@width'] =
        $r['th@width'] =
        $r['hr@width'] = new HTMLPurifier_AttrTransform_Length('width');

        return $r;

    }

}





class HTMLPurifier_HTMLModule_Tidy_Strict extends HTMLPurifier_HTMLModule_Tidy_XHTMLAndHTML4
{
    public $name = 'Tidy_Strict';
    public $defaultLevel = 'light';

    public function makeFixes() {
        $r = parent::makeFixes();
        $r['blockquote#content_model_type'] = 'strictblockquote';
        return $r;
    }

    public $defines_child_def = true;
    public function getChildDef($def) {
        if ($def->content_model_type != 'strictblockquote') return parent::getChildDef($def);
        return new HTMLPurifier_ChildDef_StrictBlockquote($def->content_model);
    }
}





class HTMLPurifier_HTMLModule_Tidy_Transitional extends HTMLPurifier_HTMLModule_Tidy_XHTMLAndHTML4
{
    public $name = 'Tidy_Transitional';
    public $defaultLevel = 'heavy';
}





class HTMLPurifier_HTMLModule_Tidy_XHTML extends HTMLPurifier_HTMLModule_Tidy
{

    public $name = 'Tidy_XHTML';
    public $defaultLevel = 'medium';

    public function makeFixes() {
        $r = array();
        $r['@lang'] = new HTMLPurifier_AttrTransform_Lang();
        return $r;
    }

}





/**
 * Injector that auto paragraphs text in the root node based on
 * double-spacing.
 * @todo Ensure all states are unit tested, including variations as well.
 * @todo Make a graph of the flow control for this Injector.
 */
class HTMLPurifier_Injector_AutoParagraph extends HTMLPurifier_Injector
{

    public $name = 'AutoParagraph';
    public $needed = array('p');

    private function _pStart() {
        $par = new HTMLPurifier_Token_Start('p');
        $par->armor['MakeWellFormed_TagClosedError'] = true;
        return $par;
    }

    public function handleText(&$token) {
        $text = $token->data;
        // Does the current parent allow <p> tags?
        if ($this->allowsElement('p')) {
            if (empty($this->currentNesting) || strpos($text, "\n\n") !== false) {
                // Note that we have differing behavior when dealing with text
                // in the anonymous root node, or a node inside the document.
                // If the text as a double-newline, the treatment is the same;
                // if it doesn't, see the next if-block if you're in the document.

                $i = $nesting = null;
                if (!$this->forwardUntilEndToken($i, $current, $nesting) && $token->is_whitespace) {
                    // State 1.1: ...    ^ (whitespace, then document end)
                    //               ----
                    // This is a degenerate case
                } else {
                    if (!$token->is_whitespace || $this->_isInline($current)) {
                        // State 1.2: PAR1
                        //            ----

                        // State 1.3: PAR1\n\nPAR2
                        //            ------------

                        // State 1.4: <div>PAR1\n\nPAR2 (see State 2)
                        //                 ------------
                        $token = array($this->_pStart());
                        $this->_splitText($text, $token);
                    } else {
                        // State 1.5: \n<hr />
                        //            --
                    }
                }
            } else {
                // State 2:   <div>PAR1... (similar to 1.4)
                //                 ----

                // We're in an element that allows paragraph tags, but we're not
                // sure if we're going to need them.
                if ($this->_pLookAhead()) {
                    // State 2.1: <div>PAR1<b>PAR1\n\nPAR2
                    //                 ----
                    // Note: This will always be the first child, since any
                    // previous inline element would have triggered this very
                    // same routine, and found the double newline. One possible
                    // exception would be a comment.
                    $token = array($this->_pStart(), $token);
                } else {
                    // State 2.2.1: <div>PAR1<div>
                    //                   ----

                    // State 2.2.2: <div>PAR1<b>PAR1</b></div>
                    //                   ----
                }
            }
        // Is the current parent a <p> tag?
        } elseif (
            !empty($this->currentNesting) &&
            $this->currentNesting[count($this->currentNesting)-1]->name == 'p'
        ) {
            // State 3.1: ...<p>PAR1
            //                  ----

            // State 3.2: ...<p>PAR1\n\nPAR2
            //                  ------------
            $token = array();
            $this->_splitText($text, $token);
        // Abort!
        } else {
            // State 4.1: ...<b>PAR1
            //                  ----

            // State 4.2: ...<b>PAR1\n\nPAR2
            //                  ------------
        }
    }

    public function handleElement(&$token) {
        // We don't have to check if we're already in a <p> tag for block
        // tokens, because the tag would have been autoclosed by MakeWellFormed.
        if ($this->allowsElement('p')) {
            if (!empty($this->currentNesting)) {
                if ($this->_isInline($token)) {
                    // State 1: <div>...<b>
                    //                  ---

                    // Check if this token is adjacent to the parent token
                    // (seek backwards until token isn't whitespace)
                    $i = null;
                    $this->backward($i, $prev);

                    if (!$prev instanceof HTMLPurifier_Token_Start) {
                        // Token wasn't adjacent

                        if (
                            $prev instanceof HTMLPurifier_Token_Text &&
                            substr($prev->data, -2) === "\n\n"
                        ) {
                            // State 1.1.4: <div><p>PAR1</p>\n\n<b>
                            //                                  ---

                            // Quite frankly, this should be handled by splitText
                            $token = array($this->_pStart(), $token);
                        } else {
                            // State 1.1.1: <div><p>PAR1</p><b>
                            //                              ---

                            // State 1.1.2: <div><br /><b>
                            //                         ---

                            // State 1.1.3: <div>PAR<b>
                            //                      ---
                        }

                    } else {
                        // State 1.2.1: <div><b>
                        //                   ---

                        // Lookahead to see if <p> is needed.
                        if ($this->_pLookAhead()) {
                            // State 1.3.1: <div><b>PAR1\n\nPAR2
                            //                   ---
                            $token = array($this->_pStart(), $token);
                        } else {
                            // State 1.3.2: <div><b>PAR1</b></div>
                            //                   ---

                            // State 1.3.3: <div><b>PAR1</b><div></div>\n\n</div>
                            //                   ---
                        }
                    }
                } else {
                    // State 2.3: ...<div>
                    //               -----
                }
            } else {
                if ($this->_isInline($token)) {
                    // State 3.1: <b>
                    //            ---
                    // This is where the {p} tag is inserted, not reflected in
                    // inputTokens yet, however.
                    $token = array($this->_pStart(), $token);
                } else {
                    // State 3.2: <div>
                    //            -----
                }

                $i = null;
                if ($this->backward($i, $prev)) {
                    if (
                        !$prev instanceof HTMLPurifier_Token_Text
                    ) {
                        // State 3.1.1: ...</p>{p}<b>
                        //                        ---

                        // State 3.2.1: ...</p><div>
                        //                     -----

                        if (!is_array($token)) $token = array($token);
                        array_unshift($token, new HTMLPurifier_Token_Text("\n\n"));
                    } else {
                        // State 3.1.2: ...</p>\n\n{p}<b>
                        //                            ---

                        // State 3.2.2: ...</p>\n\n<div>
                        //                         -----

                        // Note: PAR<ELEM> cannot occur because PAR would have been
                        // wrapped in <p> tags.
                    }
                }
            }
        } else {
            // State 2.2: <ul><li>
            //                ----

            // State 2.4: <p><b>
            //               ---
        }
    }

    /**
     * Splits up a text in paragraph tokens and appends them
     * to the result stream that will replace the original
     * @param $data String text data that will be processed
     *    into paragraphs
     * @param $result Reference to array of tokens that the
     *    tags will be appended onto
     * @param $config Instance of HTMLPurifier_Config
     * @param $context Instance of HTMLPurifier_Context
     */
    private function _splitText($data, &$result) {
        $raw_paragraphs = explode("\n\n", $data);
        $paragraphs  = array(); // without empty paragraphs
        $needs_start = false;
        $needs_end   = false;

        $c = count($raw_paragraphs);
        if ($c == 1) {
            // There were no double-newlines, abort quickly. In theory this
            // should never happen.
            $result[] = new HTMLPurifier_Token_Text($data);
            return;
        }
        for ($i = 0; $i < $c; $i++) {
            $par = $raw_paragraphs[$i];
            if (trim($par) !== '') {
                $paragraphs[] = $par;
            } else {
                if ($i == 0) {
                    // Double newline at the front
                    if (empty($result)) {
                        // The empty result indicates that the AutoParagraph
                        // injector did not add any start paragraph tokens.
                        // This means that we have been in a paragraph for
                        // a while, and the newline means we should start a new one.
                        $result[] = new HTMLPurifier_Token_End('p');
                        $result[] = new HTMLPurifier_Token_Text("\n\n");
                        // However, the start token should only be added if
                        // there is more processing to be done (i.e. there are
                        // real paragraphs in here). If there are none, the
                        // next start paragraph tag will be handled by the
                        // next call to the injector
                        $needs_start = true;
                    } else {
                        // We just started a new paragraph!
                        // Reinstate a double-newline for presentation's sake, since
                        // it was in the source code.
                        array_unshift($result, new HTMLPurifier_Token_Text("\n\n"));
                    }
                } elseif ($i + 1 == $c) {
                    // Double newline at the end
                    // There should be a trailing </p> when we're finally done.
                    $needs_end = true;
                }
            }
        }

        // Check if this was just a giant blob of whitespace. Move this earlier,
        // perhaps?
        if (empty($paragraphs)) {
            return;
        }

        // Add the start tag indicated by \n\n at the beginning of $data
        if ($needs_start) {
            $result[] = $this->_pStart();
        }

        // Append the paragraphs onto the result
        foreach ($paragraphs as $par) {
            $result[] = new HTMLPurifier_Token_Text($par);
            $result[] = new HTMLPurifier_Token_End('p');
            $result[] = new HTMLPurifier_Token_Text("\n\n");
            $result[] = $this->_pStart();
        }

        // Remove trailing start token; Injector will handle this later if
        // it was indeed needed. This prevents from needing to do a lookahead,
        // at the cost of a lookbehind later.
        array_pop($result);

        // If there is no need for an end tag, remove all of it and let
        // MakeWellFormed close it later.
        if (!$needs_end) {
            array_pop($result); // removes \n\n
            array_pop($result); // removes </p>
        }

    }

    /**
     * Returns true if passed token is inline (and, ergo, allowed in
     * paragraph tags)
     */
    private function _isInline($token) {
        return isset($this->htmlDefinition->info['p']->child->elements[$token->name]);
    }

    /**
     * Looks ahead in the token list and determines whether or not we need
     * to insert a <p> tag.
     */
    private function _pLookAhead() {
        $this->current($i, $current);
        if ($current instanceof HTMLPurifier_Token_Start) $nesting = 1;
        else $nesting = 0;
        $ok = false;
        while ($this->forwardUntilEndToken($i, $current, $nesting)) {
            $result = $this->_checkNeedsP($current);
            if ($result !== null) {
                $ok = $result;
                break;
            }
        }
        return $ok;
    }

    /**
     * Determines if a particular token requires an earlier inline token
     * to get a paragraph. This should be used with _forwardUntilEndToken
     */
    private function _checkNeedsP($current) {
        if ($current instanceof HTMLPurifier_Token_Start){
            if (!$this->_isInline($current)) {
                // <div>PAR1<div>
                //      ----
                // Terminate early, since we hit a block element
                return false;
            }
        } elseif ($current instanceof HTMLPurifier_Token_Text) {
            if (strpos($current->data, "\n\n") !== false) {
                // <div>PAR1<b>PAR1\n\nPAR2
                //      ----
                return true;
            } else {
                // <div>PAR1<b>PAR1...
                //      ----
            }
        }
        return null;
    }

}





/**
 * Injector that displays the URL of an anchor instead of linking to it, in addition to showing the text of the link.
 */
class HTMLPurifier_Injector_DisplayLinkURI extends HTMLPurifier_Injector
{

    public $name = 'DisplayLinkURI';
    public $needed = array('a');

    public function handleElement(&$token) {
    }

    public function handleEnd(&$token) {
        if (isset($token->start->attr['href'])){
            $url = $token->start->attr['href'];
            unset($token->start->attr['href']);
            $token = array($token, new HTMLPurifier_Token_Text(" ($url)"));
        } else {
            // nothing to display
        }
    }
}





/**
 * Injector that converts http, https and ftp text URLs to actual links.
 */
class HTMLPurifier_Injector_Linkify extends HTMLPurifier_Injector
{

    public $name = 'Linkify';
    public $needed = array('a' => array('href'));

    public function handleText(&$token) {
        if (!$this->allowsElement('a')) return;

        if (strpos($token->data, '://') === false) {
            // our really quick heuristic failed, abort
            // this may not work so well if we want to match things like
            // "google.com", but then again, most people don't
            return;
        }

        // there is/are URL(s). Let's split the string:
        // Note: this regex is extremely permissive
        $bits = preg_split('#((?:https?|ftp)://[^\s\'"<>()]+)#S', $token->data, -1, PREG_SPLIT_DELIM_CAPTURE);

        $token = array();

        // $i = index
        // $c = count
        // $l = is link
        for ($i = 0, $c = count($bits), $l = false; $i < $c; $i++, $l = !$l) {
            if (!$l) {
                if ($bits[$i] === '') continue;
                $token[] = new HTMLPurifier_Token_Text($bits[$i]);
            } else {
                $token[] = new HTMLPurifier_Token_Start('a', array('href' => $bits[$i]));
                $token[] = new HTMLPurifier_Token_Text($bits[$i]);
                $token[] = new HTMLPurifier_Token_End('a');
            }
        }

    }

}





/**
 * Injector that converts configuration directive syntax %Namespace.Directive
 * to links
 */
class HTMLPurifier_Injector_PurifierLinkify extends HTMLPurifier_Injector
{

    public $name = 'PurifierLinkify';
    public $docURL;
    public $needed = array('a' => array('href'));

    public function prepare($config, $context) {
        $this->docURL = $config->get('AutoFormat.PurifierLinkify.DocURL');
        return parent::prepare($config, $context);
    }

    public function handleText(&$token) {
        if (!$this->allowsElement('a')) return;
        if (strpos($token->data, '%') === false) return;

        $bits = preg_split('#%([a-z0-9]+\.[a-z0-9]+)#Si', $token->data, -1, PREG_SPLIT_DELIM_CAPTURE);
        $token = array();

        // $i = index
        // $c = count
        // $l = is link
        for ($i = 0, $c = count($bits), $l = false; $i < $c; $i++, $l = !$l) {
            if (!$l) {
                if ($bits[$i] === '') continue;
                $token[] = new HTMLPurifier_Token_Text($bits[$i]);
            } else {
                $token[] = new HTMLPurifier_Token_Start('a',
                    array('href' => str_replace('%s', $bits[$i], $this->docURL)));
                $token[] = new HTMLPurifier_Token_Text('%' . $bits[$i]);
                $token[] = new HTMLPurifier_Token_End('a');
            }
        }

    }

}





class HTMLPurifier_Injector_RemoveEmpty extends HTMLPurifier_Injector
{

    private $context, $config, $attrValidator, $removeNbsp, $removeNbspExceptions;

    public function prepare($config, $context) {
        parent::prepare($config, $context);
        $this->config = $config;
        $this->context = $context;
        $this->removeNbsp = $config->get('AutoFormat.RemoveEmpty.RemoveNbsp');
        $this->removeNbspExceptions = $config->get('AutoFormat.RemoveEmpty.RemoveNbsp.Exceptions');
        $this->attrValidator = new HTMLPurifier_AttrValidator();
    }

    public function handleElement(&$token) {
        if (!$token instanceof HTMLPurifier_Token_Start) return;
        $next = false;
        for ($i = $this->inputIndex + 1, $c = count($this->inputTokens); $i < $c; $i++) {
            $next = $this->inputTokens[$i];
            if ($next instanceof HTMLPurifier_Token_Text) {
                if ($next->is_whitespace) continue;
                if ($this->removeNbsp && !isset($this->removeNbspExceptions[$token->name])) {
                    $plain = str_replace("\xC2\xA0", "", $next->data);
                    $isWsOrNbsp = $plain === '' || ctype_space($plain);
                    if ($isWsOrNbsp) continue;
                }
            }
            break;
        }
        if (!$next || ($next instanceof HTMLPurifier_Token_End && $next->name == $token->name)) {
            if ($token->name == 'colgroup') return;
            $this->attrValidator->validateToken($token, $this->config, $this->context);
            $token->armor['ValidateAttributes'] = true;
            if (isset($token->attr['id']) || isset($token->attr['name'])) return;
            $token = $i - $this->inputIndex + 1;
            for ($b = $this->inputIndex - 1; $b > 0; $b--) {
                $prev = $this->inputTokens[$b];
                if ($prev instanceof HTMLPurifier_Token_Text && $prev->is_whitespace) continue;
                break;
            }
            // This is safe because we removed the token that triggered this.
            $this->rewind($b - 1);
            return;
        }
    }

}





/**
 * Injector that removes spans with no attributes
 */
class HTMLPurifier_Injector_RemoveSpansWithoutAttributes extends HTMLPurifier_Injector
{
    public $name = 'RemoveSpansWithoutAttributes';
    public $needed = array('span');

    private $attrValidator;

    /**
     * Used by AttrValidator
     */
    private $config;
    private $context;

    public function prepare($config, $context) {
        $this->attrValidator = new HTMLPurifier_AttrValidator();
        $this->config = $config;
        $this->context = $context;
        return parent::prepare($config, $context);
    }

    public function handleElement(&$token) {
        if ($token->name !== 'span' || !$token instanceof HTMLPurifier_Token_Start) {
            return;
        }

        // We need to validate the attributes now since this doesn't normally
        // happen until after MakeWellFormed. If all the attributes are removed
        // the span needs to be removed too.
        $this->attrValidator->validateToken($token, $this->config, $this->context);
        $token->armor['ValidateAttributes'] = true;

        if (!empty($token->attr)) {
            return;
        }

        $nesting = 0;
        $spanContentTokens = array();
        while ($this->forwardUntilEndToken($i, $current, $nesting)) {}

        if ($current instanceof HTMLPurifier_Token_End && $current->name === 'span') {
            // Mark closing span tag for deletion
            $current->markForDeletion = true;
            // Delete open span tag
            $token = false;
        }
    }

    public function handleEnd(&$token) {
        if ($token->markForDeletion) {
            $token = false;
        }
    }
}





/**
 * Adds important param elements to inside of object in order to make
 * things safe.
 */
class HTMLPurifier_Injector_SafeObject extends HTMLPurifier_Injector
{
    public $name = 'SafeObject';
    public $needed = array('object', 'param');

    protected $objectStack = array();
    protected $paramStack  = array();

    // Keep this synchronized with AttrTransform/SafeParam.php
    protected $addParam = array(
        'allowScriptAccess' => 'never',
        'allowNetworking' => 'internal',
    );
    protected $allowedParam = array(
        'wmode' => true,
        'movie' => true,
        'flashvars' => true,
        'src' => true,
        'allowFullScreen' => true, // if omitted, assume to be 'false'
    );

    public function prepare($config, $context) {
        parent::prepare($config, $context);
    }

    public function handleElement(&$token) {
        if ($token->name == 'object') {
            $this->objectStack[] = $token;
            $this->paramStack[] = array();
            $new = array($token);
            foreach ($this->addParam as $name => $value) {
                $new[] = new HTMLPurifier_Token_Empty('param', array('name' => $name, 'value' => $value));
            }
            $token = $new;
        } elseif ($token->name == 'param') {
            $nest = count($this->currentNesting) - 1;
            if ($nest >= 0 && $this->currentNesting[$nest]->name === 'object') {
                $i = count($this->objectStack) - 1;
                if (!isset($token->attr['name'])) {
                    $token = false;
                    return;
                }
                $n = $token->attr['name'];
                // We need this fix because YouTube doesn't supply a data
                // attribute, which we need if a type is specified. This is
                // *very* Flash specific.
                if (!isset($this->objectStack[$i]->attr['data']) &&
                    ($token->attr['name'] == 'movie' || $token->attr['name'] == 'src')) {
                    $this->objectStack[$i]->attr['data'] = $token->attr['value'];
                }
                // Check if the parameter is the correct value but has not
                // already been added
                if (
                    !isset($this->paramStack[$i][$n]) &&
                    isset($this->addParam[$n]) &&
                    $token->attr['name'] === $this->addParam[$n]
                ) {
                    // keep token, and add to param stack
                    $this->paramStack[$i][$n] = true;
                } elseif (isset($this->allowedParam[$n])) {
                    // keep token, don't do anything to it
                    // (could possibly check for duplicates here)
                } else {
                    $token = false;
                }
            } else {
                // not directly inside an object, DENY!
                $token = false;
            }
        }
    }

    public function handleEnd(&$token) {
        // This is the WRONG way of handling the object and param stacks;
        // we should be inserting them directly on the relevant object tokens
        // so that the global stack handling handles it.
        if ($token->name == 'object') {
            array_pop($this->objectStack);
            array_pop($this->paramStack);
        }
    }

}





/**
 * Parser that uses PHP 5's DOM extension (part of the core).
 *
 * In PHP 5, the DOM XML extension was revamped into DOM and added to the core.
 * It gives us a forgiving HTML parser, which we use to transform the HTML
 * into a DOM, and then into the tokens.  It is blazingly fast (for large
 * documents, it performs twenty times faster than
 * HTMLPurifier_Lexer_DirectLex,and is the default choice for PHP 5.
 *
 * @note Any empty elements will have empty tokens associated with them, even if
 * this is prohibited by the spec. This is cannot be fixed until the spec
 * comes into play.
 *
 * @note PHP's DOM extension does not actually parse any entities, we use
 *       our own function to do that.
 *
 * @warning DOM tends to drop whitespace, which may wreak havoc on indenting.
 *          If this is a huge problem, due to the fact that HTML is hand
 *          edited and you are unable to get a parser cache that caches the
 *          the output of HTML Purifier while keeping the original HTML lying
 *          around, you may want to run Tidy on the resulting output or use
 *          HTMLPurifier_DirectLex
 */

class HTMLPurifier_Lexer_DOMLex extends HTMLPurifier_Lexer
{

    private $factory;

    public function __construct() {
        // setup the factory
        parent::__construct();
        $this->factory = new HTMLPurifier_TokenFactory();
    }

    public function tokenizeHTML($html, $config, $context) {

        $html = $this->normalize($html, $config, $context);

        // attempt to armor stray angled brackets that cannot possibly
        // form tags and thus are probably being used as emoticons
        if ($config->get('Core.AggressivelyFixLt')) {
            $char = '[^a-z!\/]';
            $comment = "/<!--(.*?)(-->|\z)/is";
            $html = preg_replace_callback($comment, array($this, 'callbackArmorCommentEntities'), $html);
            do {
                $old = $html;
                $html = preg_replace("/<($char)/i", '&lt;\\1', $html);
            } while ($html !== $old);
            $html = preg_replace_callback($comment, array($this, 'callbackUndoCommentSubst'), $html); // fix comments
        }

        // preprocess html, essential for UTF-8
        $html = $this->wrapHTML($html, $config, $context);

        $doc = new DOMDocument();
        $doc->encoding = 'UTF-8'; // theoretically, the above has this covered

        set_error_handler(array($this, 'muteErrorHandler'));
        $doc->loadHTML($html);
        restore_error_handler();

        $tokens = array();
        $this->tokenizeDOM(
            $doc->getElementsByTagName('html')->item(0)-> // <html>
                  getElementsByTagName('body')->item(0)-> //   <body>
                  getElementsByTagName('div')->item(0)    //     <div>
            , $tokens);
        return $tokens;
    }

    /**
     * Iterative function that tokenizes a node, putting it into an accumulator.
     * To iterate is human, to recurse divine - L. Peter Deutsch
     * @param $node     DOMNode to be tokenized.
     * @param $tokens   Array-list of already tokenized tokens.
     * @returns Tokens of node appended to previously passed tokens.
     */
    protected function tokenizeDOM($node, &$tokens) {

        $level = 0;
        $nodes = array($level => array($node));
        $closingNodes = array();
        do {
            while (!empty($nodes[$level])) {
                $node = array_shift($nodes[$level]); // FIFO
                $collect = $level > 0 ? true : false;
                $needEndingTag = $this->createStartNode($node, $tokens, $collect);
                if ($needEndingTag) {
                    $closingNodes[$level][] = $node;
                }
                if ($node->childNodes && $node->childNodes->length) {
                    $level++;
                    $nodes[$level] = array();
                    foreach ($node->childNodes as $childNode) {
                        array_push($nodes[$level], $childNode);
                    }
                }
            }
            $level--;
            if ($level && isset($closingNodes[$level])) {
                while($node = array_pop($closingNodes[$level])) {
                    $this->createEndNode($node, $tokens);
                }
            }
        } while ($level > 0);
    }

    /**
     * @param $node  DOMNode to be tokenized.
     * @param $tokens   Array-list of already tokenized tokens.
     * @param $collect  Says whether or start and close are collected, set to
     *                    false at first recursion because it's the implicit DIV
     *                    tag you're dealing with.
     * @returns bool if the token needs an endtoken
     */
    protected function createStartNode($node, &$tokens, $collect) {
        // intercept non element nodes. WE MUST catch all of them,
        // but we're not getting the character reference nodes because
        // those should have been preprocessed
        if ($node->nodeType === XML_TEXT_NODE) {
            $tokens[] = $this->factory->createText($node->data);
            return false;
        } elseif ($node->nodeType === XML_CDATA_SECTION_NODE) {
            // undo libxml's special treatment of <script> and <style> tags
            $last = end($tokens);
            $data = $node->data;
            // (note $node->tagname is already normalized)
            if ($last instanceof HTMLPurifier_Token_Start && ($last->name == 'script' || $last->name == 'style')) {
                $new_data = trim($data);
                if (substr($new_data, 0, 4) === '<!--') {
                    $data = substr($new_data, 4);
                    if (substr($data, -3) === '-->') {
                        $data = substr($data, 0, -3);
                    } else {
                        // Highly suspicious! Not sure what to do...
                    }
                }
            }
            $tokens[] = $this->factory->createText($this->parseData($data));
            return false;
        } elseif ($node->nodeType === XML_COMMENT_NODE) {
            // this is code is only invoked for comments in script/style in versions
            // of libxml pre-2.6.28 (regular comments, of course, are still
            // handled regularly)
            $tokens[] = $this->factory->createComment($node->data);
            return false;
        } elseif (
            // not-well tested: there may be other nodes we have to grab
            $node->nodeType !== XML_ELEMENT_NODE
        ) {
            return false;
        }

        $attr = $node->hasAttributes() ? $this->transformAttrToAssoc($node->attributes) : array();

        // We still have to make sure that the element actually IS empty
        if (!$node->childNodes->length) {
            if ($collect) {
                $tokens[] = $this->factory->createEmpty($node->tagName, $attr);
            }
            return false;
        } else {
            if ($collect) {
                $tokens[] = $this->factory->createStart(
                    $tag_name = $node->tagName, // somehow, it get's dropped
                    $attr
                );
            }
            return true;
        }
    }

    protected function createEndNode($node, &$tokens) {
        $tokens[] = $this->factory->createEnd($node->tagName);
    }


    /**
     * Converts a DOMNamedNodeMap of DOMAttr objects into an assoc array.
     *
     * @param $attribute_list DOMNamedNodeMap of DOMAttr objects.
     * @returns Associative array of attributes.
     */
    protected function transformAttrToAssoc($node_map) {
        // NamedNodeMap is documented very well, so we're using undocumented
        // features, namely, the fact that it implements Iterator and
        // has a ->length attribute
        if ($node_map->length === 0) return array();
        $array = array();
        foreach ($node_map as $attr) {
            $array[$attr->name] = $attr->value;
        }
        return $array;
    }

    /**
     * An error handler that mutes all errors
     */
    public function muteErrorHandler($errno, $errstr) {}

    /**
     * Callback function for undoing escaping of stray angled brackets
     * in comments
     */
    public function callbackUndoCommentSubst($matches) {
        return '<!--' . strtr($matches[1], array('&amp;'=>'&','&lt;'=>'<')) . $matches[2];
    }

    /**
     * Callback function that entity-izes ampersands in comments so that
     * callbackUndoCommentSubst doesn't clobber them
     */
    public function callbackArmorCommentEntities($matches) {
        return '<!--' . str_replace('&', '&amp;', $matches[1]) . $matches[2];
    }

    /**
     * Wraps an HTML fragment in the necessary HTML
     */
    protected function wrapHTML($html, $config, $context) {
        $def = $config->getDefinition('HTML');
        $ret = '';

        if (!empty($def->doctype->dtdPublic) || !empty($def->doctype->dtdSystem)) {
            $ret .= '<!DOCTYPE html ';
            if (!empty($def->doctype->dtdPublic)) $ret .= 'PUBLIC "' . $def->doctype->dtdPublic . '" ';
            if (!empty($def->doctype->dtdSystem)) $ret .= '"' . $def->doctype->dtdSystem . '" ';
            $ret .= '>';
        }

        $ret .= '<html><head>';
        $ret .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        // No protection if $html contains a stray </div>!
        $ret .= '</head><body><div>'.$html.'</div></body></html>';
        return $ret;
    }

}





/**
 * Our in-house implementation of a parser.
 *
 * A pure PHP parser, DirectLex has absolutely no dependencies, making
 * it a reasonably good default for PHP4.  Written with efficiency in mind,
 * it can be four times faster than HTMLPurifier_Lexer_PEARSax3, although it
 * pales in comparison to HTMLPurifier_Lexer_DOMLex.
 *
 * @todo Reread XML spec and document differences.
 */
class HTMLPurifier_Lexer_DirectLex extends HTMLPurifier_Lexer
{

    public $tracksLineNumbers = true;

    /**
     * Whitespace characters for str(c)spn.
     */
    protected $_whitespace = "\x20\x09\x0D\x0A";

    /**
     * Callback function for script CDATA fudge
     * @param $matches, in form of array(opening tag, contents, closing tag)
     */
    protected function scriptCallback($matches) {
        return $matches[1] . htmlspecialchars($matches[2], ENT_COMPAT, 'UTF-8') . $matches[3];
    }

    public function tokenizeHTML($html, $config, $context) {

        // special normalization for script tags without any armor
        // our "armor" heurstic is a < sign any number of whitespaces after
        // the first script tag
        if ($config->get('HTML.Trusted')) {
            $html = preg_replace_callback('#(<script[^>]*>)(\s*[^<].+?)(</script>)#si',
                array($this, 'scriptCallback'), $html);
        }

        $html = $this->normalize($html, $config, $context);

        $cursor = 0; // our location in the text
        $inside_tag = false; // whether or not we're parsing the inside of a tag
        $array = array(); // result array

        // This is also treated to mean maintain *column* numbers too
        $maintain_line_numbers = $config->get('Core.MaintainLineNumbers');

        if ($maintain_line_numbers === null) {
            // automatically determine line numbering by checking
            // if error collection is on
            $maintain_line_numbers = $config->get('Core.CollectErrors');
        }

        if ($maintain_line_numbers) {
            $current_line = 1;
            $current_col  = 0;
            $length = strlen($html);
        } else {
            $current_line = false;
            $current_col  = false;
            $length = false;
        }
        $context->register('CurrentLine', $current_line);
        $context->register('CurrentCol',  $current_col);
        $nl = "\n";
        // how often to manually recalculate. This will ALWAYS be right,
        // but it's pretty wasteful. Set to 0 to turn off
        $synchronize_interval = $config->get('Core.DirectLexLineNumberSyncInterval');

        $e = false;
        if ($config->get('Core.CollectErrors')) {
            $e =& $context->get('ErrorCollector');
        }

        // for testing synchronization
        $loops = 0;

        while(++$loops) {

            // $cursor is either at the start of a token, or inside of
            // a tag (i.e. there was a < immediately before it), as indicated
            // by $inside_tag

            if ($maintain_line_numbers) {

                // $rcursor, however, is always at the start of a token.
                $rcursor = $cursor - (int) $inside_tag;

                // Column number is cheap, so we calculate it every round.
                // We're interested at the *end* of the newline string, so
                // we need to add strlen($nl) == 1 to $nl_pos before subtracting it
                // from our "rcursor" position.
                $nl_pos = strrpos($html, $nl, $rcursor - $length);
                $current_col = $rcursor - (is_bool($nl_pos) ? 0 : $nl_pos + 1);

                // recalculate lines
                if (
                    $synchronize_interval &&  // synchronization is on
                    $cursor > 0 &&            // cursor is further than zero
                    $loops % $synchronize_interval === 0 // time to synchronize!
                ) {
                    $current_line = 1 + $this->substrCount($html, $nl, 0, $cursor);
                }

            }

            $position_next_lt = strpos($html, '<', $cursor);
            $position_next_gt = strpos($html, '>', $cursor);

            // triggers on "<b>asdf</b>" but not "asdf <b></b>"
            // special case to set up context
            if ($position_next_lt === $cursor) {
                $inside_tag = true;
                $cursor++;
            }

            if (!$inside_tag && $position_next_lt !== false) {
                // We are not inside tag and there still is another tag to parse
                $token = new
                    HTMLPurifier_Token_Text(
                        $this->parseData(
                            substr(
                                $html, $cursor, $position_next_lt - $cursor
                            )
                        )
                    );
                if ($maintain_line_numbers) {
                    $token->rawPosition($current_line, $current_col);
                    $current_line += $this->substrCount($html, $nl, $cursor, $position_next_lt - $cursor);
                }
                $array[] = $token;
                $cursor  = $position_next_lt + 1;
                $inside_tag = true;
                continue;
            } elseif (!$inside_tag) {
                // We are not inside tag but there are no more tags
                // If we're already at the end, break
                if ($cursor === strlen($html)) break;
                // Create Text of rest of string
                $token = new
                    HTMLPurifier_Token_Text(
                        $this->parseData(
                            substr(
                                $html, $cursor
                            )
                        )
                    );
                if ($maintain_line_numbers) $token->rawPosition($current_line, $current_col);
                $array[] = $token;
                break;
            } elseif ($inside_tag && $position_next_gt !== false) {
                // We are in tag and it is well formed
                // Grab the internals of the tag
                $strlen_segment = $position_next_gt - $cursor;

                if ($strlen_segment < 1) {
                    // there's nothing to process!
                    $token = new HTMLPurifier_Token_Text('<');
                    $cursor++;
                    continue;
                }

                $segment = substr($html, $cursor, $strlen_segment);

                if ($segment === false) {
                    // somehow, we attempted to access beyond the end of
                    // the string, defense-in-depth, reported by Nate Abele
                    break;
                }

                // Check if it's a comment
                if (
                    substr($segment, 0, 3) === '!--'
                ) {
                    // re-determine segment length, looking for -->
                    $position_comment_end = strpos($html, '-->', $cursor);
                    if ($position_comment_end === false) {
                        // uh oh, we have a comment that extends to
                        // infinity. Can't be helped: set comment
                        // end position to end of string
                        if ($e) $e->send(E_WARNING, 'Lexer: Unclosed comment');
                        $position_comment_end = strlen($html);
                        $end = true;
                    } else {
                        $end = false;
                    }
                    $strlen_segment = $position_comment_end - $cursor;
                    $segment = substr($html, $cursor, $strlen_segment);
                    $token = new
                        HTMLPurifier_Token_Comment(
                            substr(
                                $segment, 3, $strlen_segment - 3
                            )
                        );
                    if ($maintain_line_numbers) {
                        $token->rawPosition($current_line, $current_col);
                        $current_line += $this->substrCount($html, $nl, $cursor, $strlen_segment);
                    }
                    $array[] = $token;
                    $cursor = $end ? $position_comment_end : $position_comment_end + 3;
                    $inside_tag = false;
                    continue;
                }

                // Check if it's an end tag
                $is_end_tag = (strpos($segment,'/') === 0);
                if ($is_end_tag) {
                    $type = substr($segment, 1);
                    $token = new HTMLPurifier_Token_End($type);
                    if ($maintain_line_numbers) {
                        $token->rawPosition($current_line, $current_col);
                        $current_line += $this->substrCount($html, $nl, $cursor, $position_next_gt - $cursor);
                    }
                    $array[] = $token;
                    $inside_tag = false;
                    $cursor = $position_next_gt + 1;
                    continue;
                }

                // Check leading character is alnum, if not, we may
                // have accidently grabbed an emoticon. Translate into
                // text and go our merry way
                if (!ctype_alpha($segment[0])) {
                    // XML:  $segment[0] !== '_' && $segment[0] !== ':'
                    if ($e) $e->send(E_NOTICE, 'Lexer: Unescaped lt');
                    $token = new HTMLPurifier_Token_Text('<');
                    if ($maintain_line_numbers) {
                        $token->rawPosition($current_line, $current_col);
                        $current_line += $this->substrCount($html, $nl, $cursor, $position_next_gt - $cursor);
                    }
                    $array[] = $token;
                    $inside_tag = false;
                    continue;
                }

                // Check if it is explicitly self closing, if so, remove
                // trailing slash. Remember, we could have a tag like <br>, so
                // any later token processing scripts must convert improperly
                // classified EmptyTags from StartTags.
                $is_self_closing = (strrpos($segment,'/') === $strlen_segment-1);
                if ($is_self_closing) {
                    $strlen_segment--;
                    $segment = substr($segment, 0, $strlen_segment);
                }

                // Check if there are any attributes
                $position_first_space = strcspn($segment, $this->_whitespace);

                if ($position_first_space >= $strlen_segment) {
                    if ($is_self_closing) {
                        $token = new HTMLPurifier_Token_Empty($segment);
                    } else {
                        $token = new HTMLPurifier_Token_Start($segment);
                    }
                    if ($maintain_line_numbers) {
                        $token->rawPosition($current_line, $current_col);
                        $current_line += $this->substrCount($html, $nl, $cursor, $position_next_gt - $cursor);
                    }
                    $array[] = $token;
                    $inside_tag = false;
                    $cursor = $position_next_gt + 1;
                    continue;
                }

                // Grab out all the data
                $type = substr($segment, 0, $position_first_space);
                $attribute_string =
                    trim(
                        substr(
                            $segment, $position_first_space
                        )
                    );
                if ($attribute_string) {
                    $attr = $this->parseAttributeString(
                                    $attribute_string
                                  , $config, $context
                              );
                } else {
                    $attr = array();
                }

                if ($is_self_closing) {
                    $token = new HTMLPurifier_Token_Empty($type, $attr);
                } else {
                    $token = new HTMLPurifier_Token_Start($type, $attr);
                }
                if ($maintain_line_numbers) {
                    $token->rawPosition($current_line, $current_col);
                    $current_line += $this->substrCount($html, $nl, $cursor, $position_next_gt - $cursor);
                }
                $array[] = $token;
                $cursor = $position_next_gt + 1;
                $inside_tag = false;
                continue;
            } else {
                // inside tag, but there's no ending > sign
                if ($e) $e->send(E_WARNING, 'Lexer: Missing gt');
                $token = new
                    HTMLPurifier_Token_Text(
                        '<' .
                        $this->parseData(
                            substr($html, $cursor)
                        )
                    );
                if ($maintain_line_numbers) $token->rawPosition($current_line, $current_col);
                // no cursor scroll? Hmm...
                $array[] = $token;
                break;
            }
            break;
        }

        $context->destroy('CurrentLine');
        $context->destroy('CurrentCol');
        return $array;
    }

    /**
     * PHP 5.0.x compatible substr_count that implements offset and length
     */
    protected function substrCount($haystack, $needle, $offset, $length) {
        static $oldVersion;
        if ($oldVersion === null) {
            $oldVersion = version_compare(PHP_VERSION, '5.1', '<');
        }
        if ($oldVersion) {
            $haystack = substr($haystack, $offset, $length);
            return substr_count($haystack, $needle);
        } else {
            return substr_count($haystack, $needle, $offset, $length);
        }
    }

    /**
     * Takes the inside of an HTML tag and makes an assoc array of attributes.
     *
     * @param $string Inside of tag excluding name.
     * @returns Assoc array of attributes.
     */
    public function parseAttributeString($string, $config, $context) {
        $string = (string) $string; // quick typecast

        if ($string == '') return array(); // no attributes

        $e = false;
        if ($config->get('Core.CollectErrors')) {
            $e =& $context->get('ErrorCollector');
        }

        // let's see if we can abort as quickly as possible
        // one equal sign, no spaces => one attribute
        $num_equal = substr_count($string, '=');
        $has_space = strpos($string, ' ');
        if ($num_equal === 0 && !$has_space) {
            // bool attribute
            return array($string => $string);
        } elseif ($num_equal === 1 && !$has_space) {
            // only one attribute
            list($key, $quoted_value) = explode('=', $string);
            $quoted_value = trim($quoted_value);
            if (!$key) {
                if ($e) $e->send(E_ERROR, 'Lexer: Missing attribute key');
                return array();
            }
            if (!$quoted_value) return array($key => '');
            $first_char = @$quoted_value[0];
            $last_char  = @$quoted_value[strlen($quoted_value)-1];

            $same_quote = ($first_char == $last_char);
            $open_quote = ($first_char == '"' || $first_char == "'");

            if ( $same_quote && $open_quote) {
                // well behaved
                $value = substr($quoted_value, 1, strlen($quoted_value) - 2);
            } else {
                // not well behaved
                if ($open_quote) {
                    if ($e) $e->send(E_ERROR, 'Lexer: Missing end quote');
                    $value = substr($quoted_value, 1);
                } else {
                    $value = $quoted_value;
                }
            }
            if ($value === false) $value = '';
            return array($key => $this->parseData($value));
        }

        // setup loop environment
        $array  = array(); // return assoc array of attributes
        $cursor = 0; // current position in string (moves forward)
        $size   = strlen($string); // size of the string (stays the same)

        // if we have unquoted attributes, the parser expects a terminating
        // space, so let's guarantee that there's always a terminating space.
        $string .= ' ';

        while(true) {

            if ($cursor >= $size) {
                break;
            }

            $cursor += ($value = strspn($string, $this->_whitespace, $cursor));
            // grab the key

            $key_begin = $cursor; //we're currently at the start of the key

            // scroll past all characters that are the key (not whitespace or =)
            $cursor += strcspn($string, $this->_whitespace . '=', $cursor);

            $key_end = $cursor; // now at the end of the key

            $key = substr($string, $key_begin, $key_end - $key_begin);

            if (!$key) {
                if ($e) $e->send(E_ERROR, 'Lexer: Missing attribute key');
                $cursor += strcspn($string, $this->_whitespace, $cursor + 1); // prevent infinite loop
                continue; // empty key
            }

            // scroll past all whitespace
            $cursor += strspn($string, $this->_whitespace, $cursor);

            if ($cursor >= $size) {
                $array[$key] = $key;
                break;
            }

            // if the next character is an equal sign, we've got a regular
            // pair, otherwise, it's a bool attribute
            $first_char = @$string[$cursor];

            if ($first_char == '=') {
                // key="value"

                $cursor++;
                $cursor += strspn($string, $this->_whitespace, $cursor);

                if ($cursor === false) {
                    $array[$key] = '';
                    break;
                }

                // we might be in front of a quote right now

                $char = @$string[$cursor];

                if ($char == '"' || $char == "'") {
                    // it's quoted, end bound is $char
                    $cursor++;
                    $value_begin = $cursor;
                    $cursor = strpos($string, $char, $cursor);
                    $value_end = $cursor;
                } else {
                    // it's not quoted, end bound is whitespace
                    $value_begin = $cursor;
                    $cursor += strcspn($string, $this->_whitespace, $cursor);
                    $value_end = $cursor;
                }

                // we reached a premature end
                if ($cursor === false) {
                    $cursor = $size;
                    $value_end = $cursor;
                }

                $value = substr($string, $value_begin, $value_end - $value_begin);
                if ($value === false) $value = '';
                $array[$key] = $this->parseData($value);
                $cursor++;

            } else {
                // boolattr
                if ($key !== '') {
                    $array[$key] = $key;
                } else {
                    // purely theoretical
                    if ($e) $e->send(E_ERROR, 'Lexer: Missing attribute key');
                }

            }
        }
        return $array;
    }

}





/**
 * Composite strategy that runs multiple strategies on tokens.
 */
abstract class HTMLPurifier_Strategy_Composite extends HTMLPurifier_Strategy
{

    /**
     * List of strategies to run tokens through.
     */
    protected $strategies = array();

    abstract public function __construct();

    public function execute($tokens, $config, $context) {
        foreach ($this->strategies as $strategy) {
            $tokens = $strategy->execute($tokens, $config, $context);
        }
        return $tokens;
    }

}





/**
 * Core strategy composed of the big four strategies.
 */
class HTMLPurifier_Strategy_Core extends HTMLPurifier_Strategy_Composite
{

    public function __construct() {
        $this->strategies[] = new HTMLPurifier_Strategy_RemoveForeignElements();
        $this->strategies[] = new HTMLPurifier_Strategy_MakeWellFormed();
        $this->strategies[] = new HTMLPurifier_Strategy_FixNesting();
        $this->strategies[] = new HTMLPurifier_Strategy_ValidateAttributes();
    }

}





/**
 * Takes a well formed list of tokens and fixes their nesting.
 *
 * HTML elements dictate which elements are allowed to be their children,
 * for example, you can't have a p tag in a span tag.  Other elements have
 * much more rigorous definitions: tables, for instance, require a specific
 * order for their elements.  There are also constraints not expressible by
 * document type definitions, such as the chameleon nature of ins/del
 * tags and global child exclusions.
 *
 * The first major objective of this strategy is to iterate through all the
 * nodes (not tokens) of the list of tokens and determine whether or not
 * their children conform to the element's definition.  If they do not, the
 * child definition may optionally supply an amended list of elements that
 * is valid or require that the entire node be deleted (and the previous
 * node rescanned).
 *
 * The second objective is to ensure that explicitly excluded elements of
 * an element do not appear in its children.  Code that accomplishes this
 * task is pervasive through the strategy, though the two are distinct tasks
 * and could, theoretically, be seperated (although it's not recommended).
 *
 * @note Whether or not unrecognized children are silently dropped or
 *       translated into text depends on the child definitions.
 *
 * @todo Enable nodes to be bubbled out of the structure.
 */

class HTMLPurifier_Strategy_FixNesting extends HTMLPurifier_Strategy
{

    public function execute($tokens, $config, $context) {
        //####################################################################//
        // Pre-processing

        // get a copy of the HTML definition
        $definition = $config->getHTMLDefinition();

        // insert implicit "parent" node, will be removed at end.
        // DEFINITION CALL
        $parent_name = $definition->info_parent;
        array_unshift($tokens, new HTMLPurifier_Token_Start($parent_name));
        $tokens[] = new HTMLPurifier_Token_End($parent_name);

        // setup the context variable 'IsInline', for chameleon processing
        // is 'false' when we are not inline, 'true' when it must always
        // be inline, and an integer when it is inline for a certain
        // branch of the document tree
        $is_inline = $definition->info_parent_def->descendants_are_inline;
        $context->register('IsInline', $is_inline);

        // setup error collector
        $e =& $context->get('ErrorCollector', true);

        //####################################################################//
        // Loop initialization

        // stack that contains the indexes of all parents,
        // $stack[count($stack)-1] being the current parent
        $stack = array();

        // stack that contains all elements that are excluded
        // it is organized by parent elements, similar to $stack,
        // but it is only populated when an element with exclusions is
        // processed, i.e. there won't be empty exclusions.
        $exclude_stack = array();

        // variable that contains the start token while we are processing
        // nodes. This enables error reporting to do its job
        $start_token = false;
        $context->register('CurrentToken', $start_token);

        //####################################################################//
        // Loop

        // iterate through all start nodes. Determining the start node
        // is complicated so it has been omitted from the loop construct
        for ($i = 0, $size = count($tokens) ; $i < $size; ) {

            //################################################################//
            // Gather information on children

            // child token accumulator
            $child_tokens = array();

            // scroll to the end of this node, report number, and collect
            // all children
            for ($j = $i, $depth = 0; ; $j++) {
                if ($tokens[$j] instanceof HTMLPurifier_Token_Start) {
                    $depth++;
                    // skip token assignment on first iteration, this is the
                    // token we currently are on
                    if ($depth == 1) continue;
                } elseif ($tokens[$j] instanceof HTMLPurifier_Token_End) {
                    $depth--;
                    // skip token assignment on last iteration, this is the
                    // end token of the token we're currently on
                    if ($depth == 0) break;
                }
                $child_tokens[] = $tokens[$j];
            }

            // $i is index of start token
            // $j is index of end token

            $start_token = $tokens[$i]; // to make token available via CurrentToken

            //################################################################//
            // Gather information on parent

            // calculate parent information
            if ($count = count($stack)) {
                $parent_index = $stack[$count-1];
                $parent_name  = $tokens[$parent_index]->name;
                if ($parent_index == 0) {
                    $parent_def   = $definition->info_parent_def;
                } else {
                    $parent_def   = $definition->info[$parent_name];
                }
            } else {
                // processing as if the parent were the "root" node
                // unknown info, it won't be used anyway, in the future,
                // we may want to enforce one element only (this is
                // necessary for HTML Purifier to clean entire documents
                $parent_index = $parent_name = $parent_def = null;
            }

            // calculate context
            if ($is_inline === false) {
                // check if conditions make it inline
                if (!empty($parent_def) && $parent_def->descendants_are_inline) {
                    $is_inline = $count - 1;
                }
            } else {
                // check if we're out of inline
                if ($count === $is_inline) {
                    $is_inline = false;
                }
            }

            //################################################################//
            // Determine whether element is explicitly excluded SGML-style

            // determine whether or not element is excluded by checking all
            // parent exclusions. The array should not be very large, two
            // elements at most.
            $excluded = false;
            if (!empty($exclude_stack)) {
                foreach ($exclude_stack as $lookup) {
                    if (isset($lookup[$tokens[$i]->name])) {
                        $excluded = true;
                        // no need to continue processing
                        break;
                    }
                }
            }

            //################################################################//
            // Perform child validation

            if ($excluded) {
                // there is an exclusion, remove the entire node
                $result = false;
                $excludes = array(); // not used, but good to initialize anyway
            } else {
                // DEFINITION CALL
                if ($i === 0) {
                    // special processing for the first node
                    $def = $definition->info_parent_def;
                } else {
                    $def = $definition->info[$tokens[$i]->name];

                }

                if (!empty($def->child)) {
                    // have DTD child def validate children
                    $result = $def->child->validateChildren(
                        $child_tokens, $config, $context);
                } else {
                    // weird, no child definition, get rid of everything
                    $result = false;
                }

                // determine whether or not this element has any exclusions
                $excludes = $def->excludes;
            }

            // $result is now a bool or array

            //################################################################//
            // Process result by interpreting $result

            if ($result === true || $child_tokens === $result) {
                // leave the node as is

                // register start token as a parental node start
                $stack[] = $i;

                // register exclusions if there are any
                if (!empty($excludes)) $exclude_stack[] = $excludes;

                // move cursor to next possible start node
                $i++;

            } elseif($result === false) {
                // remove entire node

                if ($e) {
                    if ($excluded) {
                        $e->send(E_ERROR, 'Strategy_FixNesting: Node excluded');
                    } else {
                        $e->send(E_ERROR, 'Strategy_FixNesting: Node removed');
                    }
                }

                // calculate length of inner tokens and current tokens
                $length = $j - $i + 1;

                // perform removal
                array_splice($tokens, $i, $length);

                // update size
                $size -= $length;

                // there is no start token to register,
                // current node is now the next possible start node
                // unless it turns out that we need to do a double-check

                // this is a rought heuristic that covers 100% of HTML's
                // cases and 99% of all other cases. A child definition
                // that would be tricked by this would be something like:
                // ( | a b c) where it's all or nothing. Fortunately,
                // our current implementation claims that that case would
                // not allow empty, even if it did
                if (!$parent_def->child->allow_empty) {
                    // we need to do a double-check
                    $i = $parent_index;
                    array_pop($stack);
                }

                // PROJECTED OPTIMIZATION: Process all children elements before
                // reprocessing parent node.

            } else {
                // replace node with $result

                // calculate length of inner tokens
                $length = $j - $i - 1;

                if ($e) {
                    if (empty($result) && $length) {
                        $e->send(E_ERROR, 'Strategy_FixNesting: Node contents removed');
                    } else {
                        $e->send(E_WARNING, 'Strategy_FixNesting: Node reorganized');
                    }
                }

                // perform replacement
                array_splice($tokens, $i + 1, $length, $result);

                // update size
                $size -= $length;
                $size += count($result);

                // register start token as a parental node start
                $stack[] = $i;

                // register exclusions if there are any
                if (!empty($excludes)) $exclude_stack[] = $excludes;

                // move cursor to next possible start node
                $i++;

            }

            //################################################################//
            // Scroll to next start node

            // We assume, at this point, that $i is the index of the token
            // that is the first possible new start point for a node.

            // Test if the token indeed is a start tag, if not, move forward
            // and test again.
            $size = count($tokens);
            while ($i < $size and !$tokens[$i] instanceof HTMLPurifier_Token_Start) {
                if ($tokens[$i] instanceof HTMLPurifier_Token_End) {
                    // pop a token index off the stack if we ended a node
                    array_pop($stack);
                    // pop an exclusion lookup off exclusion stack if
                    // we ended node and that node had exclusions
                    if ($i == 0 || $i == $size - 1) {
                        // use specialized var if it's the super-parent
                        $s_excludes = $definition->info_parent_def->excludes;
                    } else {
                        $s_excludes = $definition->info[$tokens[$i]->name]->excludes;
                    }
                    if ($s_excludes) {
                        array_pop($exclude_stack);
                    }
                }
                $i++;
            }

        }

        //####################################################################//
        // Post-processing

        // remove implicit parent tokens at the beginning and end
        array_shift($tokens);
        array_pop($tokens);

        // remove context variables
        $context->destroy('IsInline');
        $context->destroy('CurrentToken');

        //####################################################################//
        // Return

        return $tokens;

    }

}





/**
 * Takes tokens makes them well-formed (balance end tags, etc.)
 *
 * Specification of the armor attributes this strategy uses:
 *
 *      - MakeWellFormed_TagClosedError: This armor field is used to
 *        suppress tag closed errors for certain tokens [TagClosedSuppress],
 *        in particular, if a tag was generated automatically by HTML
 *        Purifier, we may rely on our infrastructure to close it for us
 *        and shouldn't report an error to the user [TagClosedAuto].
 */
class HTMLPurifier_Strategy_MakeWellFormed extends HTMLPurifier_Strategy
{

    /**
     * Array stream of tokens being processed.
     */
    protected $tokens;

    /**
     * Current index in $tokens.
     */
    protected $t;

    /**
     * Current nesting of elements.
     */
    protected $stack;

    /**
     * Injectors active in this stream processing.
     */
    protected $injectors;

    /**
     * Current instance of HTMLPurifier_Config.
     */
    protected $config;

    /**
     * Current instance of HTMLPurifier_Context.
     */
    protected $context;

    public function execute($tokens, $config, $context) {

        $definition = $config->getHTMLDefinition();

        // local variables
        $generator = new HTMLPurifier_Generator($config, $context);
        $escape_invalid_tags = $config->get('Core.EscapeInvalidTags');
        // used for autoclose early abortion
        $global_parent_allowed_elements = array();
        if (isset($definition->info[$definition->info_parent])) {
            // may be unset under testing circumstances
            $global_parent_allowed_elements = $definition->info[$definition->info_parent]->child->getAllowedElements($config);
        }
        $e = $context->get('ErrorCollector', true);
        $t = false; // token index
        $i = false; // injector index
        $token      = false; // the current token
        $reprocess  = false; // whether or not to reprocess the same token
        $stack = array();

        // member variables
        $this->stack   =& $stack;
        $this->t       =& $t;
        $this->tokens  =& $tokens;
        $this->config  = $config;
        $this->context = $context;

        // context variables
        $context->register('CurrentNesting', $stack);
        $context->register('InputIndex',     $t);
        $context->register('InputTokens',    $tokens);
        $context->register('CurrentToken',   $token);

        // -- begin INJECTOR --

        $this->injectors = array();

        $injectors = $config->getBatch('AutoFormat');
        $def_injectors = $definition->info_injector;
        $custom_injectors = $injectors['Custom'];
        unset($injectors['Custom']); // special case
        foreach ($injectors as $injector => $b) {
            // XXX: Fix with a legitimate lookup table of enabled filters
            if (strpos($injector, '.') !== false) continue;
            $injector = "HTMLPurifier_Injector_$injector";
            if (!$b) continue;
            $this->injectors[] = new $injector;
        }
        foreach ($def_injectors as $injector) {
            // assumed to be objects
            $this->injectors[] = $injector;
        }
        foreach ($custom_injectors as $injector) {
            if (!$injector) continue;
            if (is_string($injector)) {
                $injector = "HTMLPurifier_Injector_$injector";
                $injector = new $injector;
            }
            $this->injectors[] = $injector;
        }

        // give the injectors references to the definition and context
        // variables for performance reasons
        foreach ($this->injectors as $ix => $injector) {
            $error = $injector->prepare($config, $context);
            if (!$error) continue;
            array_splice($this->injectors, $ix, 1); // rm the injector
            trigger_error("Cannot enable {$injector->name} injector because $error is not allowed", E_USER_WARNING);
        }

        // -- end INJECTOR --

        // a note on reprocessing:
        //      In order to reduce code duplication, whenever some code needs
        //      to make HTML changes in order to make things "correct", the
        //      new HTML gets sent through the purifier, regardless of its
        //      status. This means that if we add a start token, because it
        //      was totally necessary, we don't have to update nesting; we just
        //      punt ($reprocess = true; continue;) and it does that for us.

        // isset is in loop because $tokens size changes during loop exec
        for (
            $t = 0;
            $t == 0 || isset($tokens[$t - 1]);
            // only increment if we don't need to reprocess
            $reprocess ? $reprocess = false : $t++
        ) {

            // check for a rewind
            if (is_int($i) && $i >= 0) {
                // possibility: disable rewinding if the current token has a
                // rewind set on it already. This would offer protection from
                // infinite loop, but might hinder some advanced rewinding.
                $rewind_to = $this->injectors[$i]->getRewind();
                if (is_int($rewind_to) && $rewind_to < $t) {
                    if ($rewind_to < 0) $rewind_to = 0;
                    while ($t > $rewind_to) {
                        $t--;
                        $prev = $tokens[$t];
                        // indicate that other injectors should not process this token,
                        // but we need to reprocess it
                        unset($prev->skip[$i]);
                        $prev->rewind = $i;
                        if ($prev instanceof HTMLPurifier_Token_Start) array_pop($this->stack);
                        elseif ($prev instanceof HTMLPurifier_Token_End) $this->stack[] = $prev->start;
                    }
                }
                $i = false;
            }

            // handle case of document end
            if (!isset($tokens[$t])) {
                // kill processing if stack is empty
                if (empty($this->stack)) break;

                // peek
                $top_nesting = array_pop($this->stack);
                $this->stack[] = $top_nesting;

                // send error [TagClosedSuppress]
                if ($e && !isset($top_nesting->armor['MakeWellFormed_TagClosedError'])) {
                    $e->send(E_NOTICE, 'Strategy_MakeWellFormed: Tag closed by document end', $top_nesting);
                }

                // append, don't splice, since this is the end
                $tokens[] = new HTMLPurifier_Token_End($top_nesting->name);

                // punt!
                $reprocess = true;
                continue;
            }

            $token = $tokens[$t];

            //echo '<br>'; printTokens($tokens, $t); printTokens($this->stack);
            //flush();

            // quick-check: if it's not a tag, no need to process
            if (empty($token->is_tag)) {
                if ($token instanceof HTMLPurifier_Token_Text) {
                    foreach ($this->injectors as $i => $injector) {
                        if (isset($token->skip[$i])) continue;
                        if ($token->rewind !== null && $token->rewind !== $i) continue;
                        $injector->handleText($token);
                        $this->processToken($token, $i);
                        $reprocess = true;
                        break;
                    }
                }
                // another possibility is a comment
                continue;
            }

            if (isset($definition->info[$token->name])) {
                $type = $definition->info[$token->name]->child->type;
            } else {
                $type = false; // Type is unknown, treat accordingly
            }

            // quick tag checks: anything that's *not* an end tag
            $ok = false;
            if ($type === 'empty' && $token instanceof HTMLPurifier_Token_Start) {
                // claims to be a start tag but is empty
                $token = new HTMLPurifier_Token_Empty($token->name, $token->attr, $token->line, $token->col, $token->armor);
                $ok = true;
            } elseif ($type && $type !== 'empty' && $token instanceof HTMLPurifier_Token_Empty) {
                // claims to be empty but really is a start tag
                $this->swap(new HTMLPurifier_Token_End($token->name));
                $this->insertBefore(new HTMLPurifier_Token_Start($token->name, $token->attr, $token->line, $token->col, $token->armor));
                // punt (since we had to modify the input stream in a non-trivial way)
                $reprocess = true;
                continue;
            } elseif ($token instanceof HTMLPurifier_Token_Empty) {
                // real empty token
                $ok = true;
            } elseif ($token instanceof HTMLPurifier_Token_Start) {
                // start tag

                // ...unless they also have to close their parent
                if (!empty($this->stack)) {

                    // Performance note: you might think that it's rather
                    // inefficient, recalculating the autoclose information
                    // for every tag that a token closes (since when we
                    // do an autoclose, we push a new token into the
                    // stream and then /process/ that, before
                    // re-processing this token.)  But this is
                    // necessary, because an injector can make an
                    // arbitrary transformations to the autoclosing
                    // tokens we introduce, so things may have changed
                    // in the meantime.  Also, doing the inefficient thing is
                    // "easy" to reason about (for certain perverse definitions
                    // of "easy")

                    $parent = array_pop($this->stack);
                    $this->stack[] = $parent;

                    if (isset($definition->info[$parent->name])) {
                        $elements = $definition->info[$parent->name]->child->getAllowedElements($config);
                        $autoclose = !isset($elements[$token->name]);
                    } else {
                        $autoclose = false;
                    }

                    if ($autoclose && $definition->info[$token->name]->wrap) {
                        // Check if an element can be wrapped by another 
                        // element to make it valid in a context (for 
                        // example, <ul><ul> needs a <li> in between)
                        $wrapname = $definition->info[$token->name]->wrap;
                        $wrapdef = $definition->info[$wrapname];
                        $elements = $wrapdef->child->getAllowedElements($config);
                        $parent_elements = $definition->info[$parent->name]->child->getAllowedElements($config);
                        if (isset($elements[$token->name]) && isset($parent_elements[$wrapname])) {
                            $newtoken = new HTMLPurifier_Token_Start($wrapname);
                            $this->insertBefore($newtoken);
                            $reprocess = true;
                            continue;
                        }
                    }

                    $carryover = false;
                    if ($autoclose && $definition->info[$parent->name]->formatting) {
                        $carryover = true;
                    }

                    if ($autoclose) {
                        // check if this autoclose is doomed to fail
                        // (this rechecks $parent, which his harmless)
                        $autoclose_ok = isset($global_parent_allowed_elements[$token->name]);
                        if (!$autoclose_ok) {
                            foreach ($this->stack as $ancestor) {
                                $elements = $definition->info[$ancestor->name]->child->getAllowedElements($config);
                                if (isset($elements[$token->name])) {
                                    $autoclose_ok = true;
                                    break;
                                }
                                if ($definition->info[$token->name]->wrap) {
                                    $wrapname = $definition->info[$token->name]->wrap;
                                    $wrapdef = $definition->info[$wrapname];
                                    $wrap_elements = $wrapdef->child->getAllowedElements($config);
                                    if (isset($wrap_elements[$token->name]) && isset($elements[$wrapname])) {
                                        $autoclose_ok = true;
                                        break;
                                    }
                                }
                            }
                        }
                        if ($autoclose_ok) {
                            // errors need to be updated
                            $new_token = new HTMLPurifier_Token_End($parent->name);
                            $new_token->start = $parent;
                            if ($carryover) {
                                $element = clone $parent;
                                // [TagClosedAuto]
                                $element->armor['MakeWellFormed_TagClosedError'] = true;
                                $element->carryover = true;
                                $this->processToken(array($new_token, $token, $element));
                            } else {
                                $this->insertBefore($new_token);
                            }
                            // [TagClosedSuppress]
                            if ($e && !isset($parent->armor['MakeWellFormed_TagClosedError'])) {
                                if (!$carryover) {
                                    $e->send(E_NOTICE, 'Strategy_MakeWellFormed: Tag auto closed', $parent);
                                } else {
                                    $e->send(E_NOTICE, 'Strategy_MakeWellFormed: Tag carryover', $parent);
                                }
                            }
                        } else {
                            $this->remove();
                        }
                        $reprocess = true;
                        continue;
                    }

                }
                $ok = true;
            }

            if ($ok) {
                foreach ($this->injectors as $i => $injector) {
                    if (isset($token->skip[$i])) continue;
                    if ($token->rewind !== null && $token->rewind !== $i) continue;
                    $injector->handleElement($token);
                    $this->processToken($token, $i);
                    $reprocess = true;
                    break;
                }
                if (!$reprocess) {
                    // ah, nothing interesting happened; do normal processing
                    $this->swap($token);
                    if ($token instanceof HTMLPurifier_Token_Start) {
                        $this->stack[] = $token;
                    } elseif ($token instanceof HTMLPurifier_Token_End) {
                        throw new HTMLPurifier_Exception('Improper handling of end tag in start code; possible error in MakeWellFormed');
                    }
                }
                continue;
            }

            // sanity check: we should be dealing with a closing tag
            if (!$token instanceof HTMLPurifier_Token_End) {
                throw new HTMLPurifier_Exception('Unaccounted for tag token in input stream, bug in HTML Purifier');
            }

            // make sure that we have something open
            if (empty($this->stack)) {
                if ($escape_invalid_tags) {
                    if ($e) $e->send(E_WARNING, 'Strategy_MakeWellFormed: Unnecessary end tag to text');
                    $this->swap(new HTMLPurifier_Token_Text(
                        $generator->generateFromToken($token)
                    ));
                } else {
                    $this->remove();
                    if ($e) $e->send(E_WARNING, 'Strategy_MakeWellFormed: Unnecessary end tag removed');
                }
                $reprocess = true;
                continue;
            }

            // first, check for the simplest case: everything closes neatly.
            // Eventually, everything passes through here; if there are problems
            // we modify the input stream accordingly and then punt, so that
            // the tokens get processed again.
            $current_parent = array_pop($this->stack);
            if ($current_parent->name == $token->name) {
                $token->start = $current_parent;
                foreach ($this->injectors as $i => $injector) {
                    if (isset($token->skip[$i])) continue;
                    if ($token->rewind !== null && $token->rewind !== $i) continue;
                    $injector->handleEnd($token);
                    $this->processToken($token, $i);
                    $this->stack[] = $current_parent;
                    $reprocess = true;
                    break;
                }
                continue;
            }

            // okay, so we're trying to close the wrong tag

            // undo the pop previous pop
            $this->stack[] = $current_parent;

            // scroll back the entire nest, trying to find our tag.
            // (feature could be to specify how far you'd like to go)
            $size = count($this->stack);
            // -2 because -1 is the last element, but we already checked that
            $skipped_tags = false;
            for ($j = $size - 2; $j >= 0; $j--) {
                if ($this->stack[$j]->name == $token->name) {
                    $skipped_tags = array_slice($this->stack, $j);
                    break;
                }
            }

            // we didn't find the tag, so remove
            if ($skipped_tags === false) {
                if ($escape_invalid_tags) {
                    $this->swap(new HTMLPurifier_Token_Text(
                        $generator->generateFromToken($token)
                    ));
                    if ($e) $e->send(E_WARNING, 'Strategy_MakeWellFormed: Stray end tag to text');
                } else {
                    $this->remove();
                    if ($e) $e->send(E_WARNING, 'Strategy_MakeWellFormed: Stray end tag removed');
                }
                $reprocess = true;
                continue;
            }

            // do errors, in REVERSE $j order: a,b,c with </a></b></c>
            $c = count($skipped_tags);
            if ($e) {
                for ($j = $c - 1; $j > 0; $j--) {
                    // notice we exclude $j == 0, i.e. the current ending tag, from
                    // the errors... [TagClosedSuppress]
                    if (!isset($skipped_tags[$j]->armor['MakeWellFormed_TagClosedError'])) {
                        $e->send(E_NOTICE, 'Strategy_MakeWellFormed: Tag closed by element end', $skipped_tags[$j]);
                    }
                }
            }

            // insert tags, in FORWARD $j order: c,b,a with </a></b></c>
            $replace = array($token);
            for ($j = 1; $j < $c; $j++) {
                // ...as well as from the insertions
                $new_token = new HTMLPurifier_Token_End($skipped_tags[$j]->name);
                $new_token->start = $skipped_tags[$j];
                array_unshift($replace, $new_token);
                if (isset($definition->info[$new_token->name]) && $definition->info[$new_token->name]->formatting) {
                    // [TagClosedAuto]
                    $element = clone $skipped_tags[$j];
                    $element->carryover = true;
                    $element->armor['MakeWellFormed_TagClosedError'] = true;
                    $replace[] = $element;
                }
            }
            $this->processToken($replace);
            $reprocess = true;
            continue;
        }

        $context->destroy('CurrentNesting');
        $context->destroy('InputTokens');
        $context->destroy('InputIndex');
        $context->destroy('CurrentToken');

        unset($this->injectors, $this->stack, $this->tokens, $this->t);
        return $tokens;
    }

    /**
     * Processes arbitrary token values for complicated substitution patterns.
     * In general:
     *
     * If $token is an array, it is a list of tokens to substitute for the
     * current token. These tokens then get individually processed. If there
     * is a leading integer in the list, that integer determines how many
     * tokens from the stream should be removed.
     *
     * If $token is a regular token, it is swapped with the current token.
     *
     * If $token is false, the current token is deleted.
     *
     * If $token is an integer, that number of tokens (with the first token
     * being the current one) will be deleted.
     *
     * @param $token Token substitution value
     * @param $injector Injector that performed the substitution; default is if
     *        this is not an injector related operation.
     */
    protected function processToken($token, $injector = -1) {

        // normalize forms of token
        if (is_object($token)) $token = array(1, $token);
        if (is_int($token))    $token = array($token);
        if ($token === false)  $token = array(1);
        if (!is_array($token)) throw new HTMLPurifier_Exception('Invalid token type from injector');
        if (!is_int($token[0])) array_unshift($token, 1);
        if ($token[0] === 0) throw new HTMLPurifier_Exception('Deleting zero tokens is not valid');

        // $token is now an array with the following form:
        // array(number nodes to delete, new node 1, new node 2, ...)

        $delete = array_shift($token);
        $old = array_splice($this->tokens, $this->t, $delete, $token);

        if ($injector > -1) {
            // determine appropriate skips
            $oldskip = isset($old[0]) ? $old[0]->skip : array();
            foreach ($token as $object) {
                $object->skip = $oldskip;
                $object->skip[$injector] = true;
            }
        }

    }

    /**
     * Inserts a token before the current token. Cursor now points to
     * this token.  You must reprocess after this.
     */
    private function insertBefore($token) {
        array_splice($this->tokens, $this->t, 0, array($token));
    }

    /**
     * Removes current token. Cursor now points to new token occupying previously
     * occupied space.  You must reprocess after this.
     */
    private function remove() {
        array_splice($this->tokens, $this->t, 1);
    }

    /**
     * Swap current token with new token. Cursor points to new token (no
     * change).  You must reprocess after this.
     */
    private function swap($token) {
        $this->tokens[$this->t] = $token;
    }

}





/**
 * Removes all unrecognized tags from the list of tokens.
 *
 * This strategy iterates through all the tokens and removes unrecognized
 * tokens. If a token is not recognized but a TagTransform is defined for
 * that element, the element will be transformed accordingly.
 */

class HTMLPurifier_Strategy_RemoveForeignElements extends HTMLPurifier_Strategy
{

    public function execute($tokens, $config, $context) {
        $definition = $config->getHTMLDefinition();
        $generator = new HTMLPurifier_Generator($config, $context);
        $result = array();

        $escape_invalid_tags = $config->get('Core.EscapeInvalidTags');
        $remove_invalid_img  = $config->get('Core.RemoveInvalidImg');

        // currently only used to determine if comments should be kept
        $trusted = $config->get('HTML.Trusted');

        $remove_script_contents = $config->get('Core.RemoveScriptContents');
        $hidden_elements     = $config->get('Core.HiddenElements');

        // remove script contents compatibility
        if ($remove_script_contents === true) {
            $hidden_elements['script'] = true;
        } elseif ($remove_script_contents === false && isset($hidden_elements['script'])) {
            unset($hidden_elements['script']);
        }

        $attr_validator = new HTMLPurifier_AttrValidator();

        // removes tokens until it reaches a closing tag with its value
        $remove_until = false;

        // converts comments into text tokens when this is equal to a tag name
        $textify_comments = false;

        $token = false;
        $context->register('CurrentToken', $token);

        $e = false;
        if ($config->get('Core.CollectErrors')) {
            $e =& $context->get('ErrorCollector');
        }

        foreach($tokens as $token) {
            if ($remove_until) {
                if (empty($token->is_tag) || $token->name !== $remove_until) {
                    continue;
                }
            }
            if (!empty( $token->is_tag )) {
                // DEFINITION CALL

                // before any processing, try to transform the element
                if (
                    isset($definition->info_tag_transform[$token->name])
                ) {
                    $original_name = $token->name;
                    // there is a transformation for this tag
                    // DEFINITION CALL
                    $token = $definition->
                                info_tag_transform[$token->name]->
                                    transform($token, $config, $context);
                    if ($e) $e->send(E_NOTICE, 'Strategy_RemoveForeignElements: Tag transform', $original_name);
                }

                if (isset($definition->info[$token->name])) {

                    // mostly everything's good, but
                    // we need to make sure required attributes are in order
                    if (
                        ($token instanceof HTMLPurifier_Token_Start || $token instanceof HTMLPurifier_Token_Empty) &&
                        $definition->info[$token->name]->required_attr &&
                        ($token->name != 'img' || $remove_invalid_img) // ensure config option still works
                    ) {
                        $attr_validator->validateToken($token, $config, $context);
                        $ok = true;
                        foreach ($definition->info[$token->name]->required_attr as $name) {
                            if (!isset($token->attr[$name])) {
                                $ok = false;
                                break;
                            }
                        }
                        if (!$ok) {
                            if ($e) $e->send(E_ERROR, 'Strategy_RemoveForeignElements: Missing required attribute', $name);
                            continue;
                        }
                        $token->armor['ValidateAttributes'] = true;
                    }

                    if (isset($hidden_elements[$token->name]) && $token instanceof HTMLPurifier_Token_Start) {
                        $textify_comments = $token->name;
                    } elseif ($token->name === $textify_comments && $token instanceof HTMLPurifier_Token_End) {
                        $textify_comments = false;
                    }

                } elseif ($escape_invalid_tags) {
                    // invalid tag, generate HTML representation and insert in
                    if ($e) $e->send(E_WARNING, 'Strategy_RemoveForeignElements: Foreign element to text');
                    $token = new HTMLPurifier_Token_Text(
                        $generator->generateFromToken($token)
                    );
                } else {
                    // check if we need to destroy all of the tag's children
                    // CAN BE GENERICIZED
                    if (isset($hidden_elements[$token->name])) {
                        if ($token instanceof HTMLPurifier_Token_Start) {
                            $remove_until = $token->name;
                        } elseif ($token instanceof HTMLPurifier_Token_Empty) {
                            // do nothing: we're still looking
                        } else {
                            $remove_until = false;
                        }
                        if ($e) $e->send(E_ERROR, 'Strategy_RemoveForeignElements: Foreign meta element removed');
                    } else {
                        if ($e) $e->send(E_ERROR, 'Strategy_RemoveForeignElements: Foreign element removed');
                    }
                    continue;
                }
            } elseif ($token instanceof HTMLPurifier_Token_Comment) {
                // textify comments in script tags when they are allowed
                if ($textify_comments !== false) {
                    $data = $token->data;
                    $token = new HTMLPurifier_Token_Text($data);
                } elseif ($trusted) {
                    // keep, but perform comment cleaning
                    if ($e) {
                        // perform check whether or not there's a trailing hyphen
                        if (substr($token->data, -1) == '-') {
                            $e->send(E_NOTICE, 'Strategy_RemoveForeignElements: Trailing hyphen in comment removed');
                        }
                    }
                    $token->data = rtrim($token->data, '-');
                    $found_double_hyphen = false;
                    while (strpos($token->data, '--') !== false) {
                        if ($e && !$found_double_hyphen) {
                            $e->send(E_NOTICE, 'Strategy_RemoveForeignElements: Hyphens in comment collapsed');
                        }
                        $found_double_hyphen = true; // prevent double-erroring
                        $token->data = str_replace('--', '-', $token->data);
                    }
                } else {
                    // strip comments
                    if ($e) $e->send(E_NOTICE, 'Strategy_RemoveForeignElements: Comment removed');
                    continue;
                }
            } elseif ($token instanceof HTMLPurifier_Token_Text) {
            } else {
                continue;
            }
            $result[] = $token;
        }
        if ($remove_until && $e) {
            // we removed tokens until the end, throw error
            $e->send(E_ERROR, 'Strategy_RemoveForeignElements: Token removed to end', $remove_until);
        }

        $context->destroy('CurrentToken');

        return $result;
    }

}





/**
 * Validate all attributes in the tokens.
 */

class HTMLPurifier_Strategy_ValidateAttributes extends HTMLPurifier_Strategy
{

    public function execute($tokens, $config, $context) {

        // setup validator
        $validator = new HTMLPurifier_AttrValidator();

        $token = false;
        $context->register('CurrentToken', $token);

        foreach ($tokens as $key => $token) {

            // only process tokens that have attributes,
            //   namely start and empty tags
            if (!$token instanceof HTMLPurifier_Token_Start && !$token instanceof HTMLPurifier_Token_Empty) continue;

            // skip tokens that are armored
            if (!empty($token->armor['ValidateAttributes'])) continue;

            // note that we have no facilities here for removing tokens
            $validator->validateToken($token, $config, $context);

            $tokens[$key] = $token; // for PHP 4
        }
        $context->destroy('CurrentToken');

        return $tokens;
    }

}





/**
 * Transforms FONT tags to the proper form (SPAN with CSS styling)
 *
 * This transformation takes the three proprietary attributes of FONT and
 * transforms them into their corresponding CSS attributes.  These are color,
 * face, and size.
 *
 * @note Size is an interesting case because it doesn't map cleanly to CSS.
 *       Thanks to
 *       http://style.cleverchimp.com/font_size_intervals/altintervals.html
 *       for reasonable mappings.
 * @warning This doesn't work completely correctly; specifically, this
 *          TagTransform operates before well-formedness is enforced, so
 *          the "active formatting elements" algorithm doesn't get applied.
 */
class HTMLPurifier_TagTransform_Font extends HTMLPurifier_TagTransform
{

    public $transform_to = 'span';

    protected $_size_lookup = array(
        '0' => 'xx-small',
        '1' => 'xx-small',
        '2' => 'small',
        '3' => 'medium',
        '4' => 'large',
        '5' => 'x-large',
        '6' => 'xx-large',
        '7' => '300%',
        '-1' => 'smaller',
        '-2' => '60%',
        '+1' => 'larger',
        '+2' => '150%',
        '+3' => '200%',
        '+4' => '300%'
    );

    public function transform($tag, $config, $context) {

        if ($tag instanceof HTMLPurifier_Token_End) {
            $new_tag = clone $tag;
            $new_tag->name = $this->transform_to;
            return $new_tag;
        }

        $attr = $tag->attr;
        $prepend_style = '';

        // handle color transform
        if (isset($attr['color'])) {
            $prepend_style .= 'color:' . $attr['color'] . ';';
            unset($attr['color']);
        }

        // handle face transform
        if (isset($attr['face'])) {
            $prepend_style .= 'font-family:' . $attr['face'] . ';';
            unset($attr['face']);
        }

        // handle size transform
        if (isset($attr['size'])) {
            // normalize large numbers
            if ($attr['size'] !== '') {
                if ($attr['size']{0} == '+' || $attr['size']{0} == '-') {
                    $size = (int) $attr['size'];
                    if ($size < -2) $attr['size'] = '-2';
                    if ($size > 4)  $attr['size'] = '+4';
                } else {
                    $size = (int) $attr['size'];
                    if ($size > 7) $attr['size'] = '7';
                }
            }
            if (isset($this->_size_lookup[$attr['size']])) {
                $prepend_style .= 'font-size:' .
                  $this->_size_lookup[$attr['size']] . ';';
            }
            unset($attr['size']);
        }

        if ($prepend_style) {
            $attr['style'] = isset($attr['style']) ?
                $prepend_style . $attr['style'] :
                $prepend_style;
        }

        $new_tag = clone $tag;
        $new_tag->name = $this->transform_to;
        $new_tag->attr = $attr;

        return $new_tag;

    }
}





/**
 * Simple transformation, just change tag name to something else,
 * and possibly add some styling. This will cover most of the deprecated
 * tag cases.
 */
class HTMLPurifier_TagTransform_Simple extends HTMLPurifier_TagTransform
{

    protected $style;

    /**
     * @param $transform_to Tag name to transform to.
     * @param $style CSS style to add to the tag
     */
    public function __construct($transform_to, $style = null) {
        $this->transform_to = $transform_to;
        $this->style = $style;
    }

    public function transform($tag, $config, $context) {
        $new_tag = clone $tag;
        $new_tag->name = $this->transform_to;
        if (!is_null($this->style) &&
            ($new_tag instanceof HTMLPurifier_Token_Start || $new_tag instanceof HTMLPurifier_Token_Empty)
        ) {
            $this->prependCSS($new_tag->attr, $this->style);
        }
        return $new_tag;
    }

}





/**
 * Concrete comment token class. Generally will be ignored.
 */
class HTMLPurifier_Token_Comment extends HTMLPurifier_Token
{
    public $data; /**< Character data within comment. */
    public $is_whitespace = true;
    /**
     * Transparent constructor.
     *
     * @param $data String comment data.
     */
    public function __construct($data, $line = null, $col = null) {
        $this->data = $data;
        $this->line = $line;
        $this->col  = $col;
    }
}





/**
 * Abstract class of a tag token (start, end or empty), and its behavior.
 */
class HTMLPurifier_Token_Tag extends HTMLPurifier_Token
{
    /**
     * Static bool marker that indicates the class is a tag.
     *
     * This allows us to check objects with <tt>!empty($obj->is_tag)</tt>
     * without having to use a function call <tt>is_a()</tt>.
     */
    public $is_tag = true;

    /**
     * The lower-case name of the tag, like 'a', 'b' or 'blockquote'.
     *
     * @note Strictly speaking, XML tags are case sensitive, so we shouldn't
     * be lower-casing them, but these tokens cater to HTML tags, which are
     * insensitive.
     */
    public $name;

    /**
     * Associative array of the tag's attributes.
     */
    public $attr = array();

    /**
     * Non-overloaded constructor, which lower-cases passed tag name.
     *
     * @param $name String name.
     * @param $attr Associative array of attributes.
     */
    public function __construct($name, $attr = array(), $line = null, $col = null, $armor = array()) {
        $this->name = ctype_lower($name) ? $name : strtolower($name);
        foreach ($attr as $key => $value) {
            // normalization only necessary when key is not lowercase
            if (!ctype_lower($key)) {
                $new_key = strtolower($key);
                if (!isset($attr[$new_key])) {
                    $attr[$new_key] = $attr[$key];
                }
                if ($new_key !== $key) {
                    unset($attr[$key]);
                }
            }
        }
        $this->attr = $attr;
        $this->line = $line;
        $this->col  = $col;
        $this->armor = $armor;
    }
}





/**
 * Concrete empty token class.
 */
class HTMLPurifier_Token_Empty extends HTMLPurifier_Token_Tag
{

}





/**
 * Concrete end token class.
 *
 * @warning This class accepts attributes even though end tags cannot. This
 * is for optimization reasons, as under normal circumstances, the Lexers
 * do not pass attributes.
 */
class HTMLPurifier_Token_End extends HTMLPurifier_Token_Tag
{
    /**
     * Token that started this node. Added by MakeWellFormed. Please
     * do not edit this!
     */
    public $start;
}





/**
 * Concrete start token class.
 */
class HTMLPurifier_Token_Start extends HTMLPurifier_Token_Tag
{

}





/**
 * Concrete text token class.
 *
 * Text tokens comprise of regular parsed character data (PCDATA) and raw
 * character data (from the CDATA sections). Internally, their
 * data is parsed with all entities expanded. Surprisingly, the text token
 * does have a "tag name" called #PCDATA, which is how the DTD represents it
 * in permissible child nodes.
 */
class HTMLPurifier_Token_Text extends HTMLPurifier_Token
{

    public $name = '#PCDATA'; /**< PCDATA tag name compatible with DTD. */
    public $data; /**< Parsed character data of text. */
    public $is_whitespace; /**< Bool indicating if node is whitespace. */

    /**
     * Constructor, accepts data and determines if it is whitespace.
     *
     * @param $data String parsed character data.
     */
    public function __construct($data, $line = null, $col = null) {
        $this->data = $data;
        $this->is_whitespace = ctype_space($data);
        $this->line = $line;
        $this->col  = $col;
    }

}





class HTMLPurifier_URIFilter_DisableExternal extends HTMLPurifier_URIFilter
{
    public $name = 'DisableExternal';
    protected $ourHostParts = false;
    public function prepare($config) {
        $our_host = $config->getDefinition('URI')->host;
        if ($our_host !== null) $this->ourHostParts = array_reverse(explode('.', $our_host));
    }
    public function filter(&$uri, $config, $context) {
        if (is_null($uri->host)) return true;
        if ($this->ourHostParts === false) return false;
        $host_parts = array_reverse(explode('.', $uri->host));
        foreach ($this->ourHostParts as $i => $x) {
            if (!isset($host_parts[$i])) return false;
            if ($host_parts[$i] != $this->ourHostParts[$i]) return false;
        }
        return true;
    }
}





class HTMLPurifier_URIFilter_DisableExternalResources extends HTMLPurifier_URIFilter_DisableExternal
{
    public $name = 'DisableExternalResources';
    public function filter(&$uri, $config, $context) {
        if (!$context->get('EmbeddedURI', true)) return true;
        return parent::filter($uri, $config, $context);
    }
}





class HTMLPurifier_URIFilter_DisableResources extends HTMLPurifier_URIFilter
{
    public $name = 'DisableResources';
    public function filter(&$uri, $config, $context) {
        return !$context->get('EmbeddedURI', true);
    }
}





class HTMLPurifier_URIFilter_HostBlacklist extends HTMLPurifier_URIFilter
{
    public $name = 'HostBlacklist';
    protected $blacklist = array();
    public function prepare($config) {
        $this->blacklist = $config->get('URI.HostBlacklist');
        return true;
    }
    public function filter(&$uri, $config, $context) {
        foreach($this->blacklist as $blacklisted_host_fragment) {
            if (strpos($uri->host, $blacklisted_host_fragment) !== false) {
                return false;
            }
        }
        return true;
    }
}





// does not support network paths

class HTMLPurifier_URIFilter_MakeAbsolute extends HTMLPurifier_URIFilter
{
    public $name = 'MakeAbsolute';
    protected $base;
    protected $basePathStack = array();
    public function prepare($config) {
        $def = $config->getDefinition('URI');
        $this->base = $def->base;
        if (is_null($this->base)) {
            trigger_error('URI.MakeAbsolute is being ignored due to lack of value for URI.Base configuration', E_USER_WARNING);
            return false;
        }
        $this->base->fragment = null; // fragment is invalid for base URI
        $stack = explode('/', $this->base->path);
        array_pop($stack); // discard last segment
        $stack = $this->_collapseStack($stack); // do pre-parsing
        $this->basePathStack = $stack;
        return true;
    }
    public function filter(&$uri, $config, $context) {
        if (is_null($this->base)) return true; // abort early
        if (
            $uri->path === '' && is_null($uri->scheme) &&
            is_null($uri->host) && is_null($uri->query) && is_null($uri->fragment)
        ) {
            // reference to current document
            $uri = clone $this->base;
            return true;
        }
        if (!is_null($uri->scheme)) {
            // absolute URI already: don't change
            if (!is_null($uri->host)) return true;
            $scheme_obj = $uri->getSchemeObj($config, $context);
            if (!$scheme_obj) {
                // scheme not recognized
                return false;
            }
            if (!$scheme_obj->hierarchical) {
                // non-hierarchal URI with explicit scheme, don't change
                return true;
            }
            // special case: had a scheme but always is hierarchical and had no authority
        }
        if (!is_null($uri->host)) {
            // network path, don't bother
            return true;
        }
        if ($uri->path === '') {
            $uri->path = $this->base->path;
        } elseif ($uri->path[0] !== '/') {
            // relative path, needs more complicated processing
            $stack = explode('/', $uri->path);
            $new_stack = array_merge($this->basePathStack, $stack);
            if ($new_stack[0] !== '' && !is_null($this->base->host)) {
                array_unshift($new_stack, '');
            }
            $new_stack = $this->_collapseStack($new_stack);
            $uri->path = implode('/', $new_stack);
        } else {
            // absolute path, but still we should collapse
            $uri->path = implode('/', $this->_collapseStack(explode('/', $uri->path)));
        }
        // re-combine
        $uri->scheme = $this->base->scheme;
        if (is_null($uri->userinfo)) $uri->userinfo = $this->base->userinfo;
        if (is_null($uri->host))     $uri->host     = $this->base->host;
        if (is_null($uri->port))     $uri->port     = $this->base->port;
        return true;
    }

    /**
     * Resolve dots and double-dots in a path stack
     */
    private function _collapseStack($stack) {
        $result = array();
        $is_folder = false;
        for ($i = 0; isset($stack[$i]); $i++) {
            $is_folder = false;
            // absorb an internally duplicated slash
            if ($stack[$i] == '' && $i && isset($stack[$i+1])) continue;
            if ($stack[$i] == '..') {
                if (!empty($result)) {
                    $segment = array_pop($result);
                    if ($segment === '' && empty($result)) {
                        // error case: attempted to back out too far:
                        // restore the leading slash
                        $result[] = '';
                    } elseif ($segment === '..') {
                        $result[] = '..'; // cannot remove .. with ..
                    }
                } else {
                    // relative path, preserve the double-dots
                    $result[] = '..';
                }
                $is_folder = true;
                continue;
            }
            if ($stack[$i] == '.') {
                // silently absorb
                $is_folder = true;
                continue;
            }
            $result[] = $stack[$i];
        }
        if ($is_folder) $result[] = '';
        return $result;
    }
}





class HTMLPurifier_URIFilter_Munge extends HTMLPurifier_URIFilter
{
    public $name = 'Munge';
    public $post = true;
    private $target, $parser, $doEmbed, $secretKey;

    protected $replace = array();

    public function prepare($config) {
        $this->target    = $config->get('URI.' . $this->name);
        $this->parser    = new HTMLPurifier_URIParser();
        $this->doEmbed   = $config->get('URI.MungeResources');
        $this->secretKey = $config->get('URI.MungeSecretKey');
        return true;
    }
    public function filter(&$uri, $config, $context) {
        if ($context->get('EmbeddedURI', true) && !$this->doEmbed) return true;

        $scheme_obj = $uri->getSchemeObj($config, $context);
        if (!$scheme_obj) return true; // ignore unknown schemes, maybe another postfilter did it
        if (is_null($uri->host) || empty($scheme_obj->browsable)) {
            return true;
        }
        // don't redirect if target host is our host
        if ($uri->host === $config->getDefinition('URI')->host) {
            return true;
        }

        $this->makeReplace($uri, $config, $context);
        $this->replace = array_map('rawurlencode', $this->replace);

        $new_uri = strtr($this->target, $this->replace);
        $new_uri = $this->parser->parse($new_uri);
        // don't redirect if the target host is the same as the
        // starting host
        if ($uri->host === $new_uri->host) return true;
        $uri = $new_uri; // overwrite
        return true;
    }

    protected function makeReplace($uri, $config, $context) {
        $string = $uri->toString();
        // always available
        $this->replace['%s'] = $string;
        $this->replace['%r'] = $context->get('EmbeddedURI', true);
        $token = $context->get('CurrentToken', true);
        $this->replace['%n'] = $token ? $token->name : null;
        $this->replace['%m'] = $context->get('CurrentAttr', true);
        $this->replace['%p'] = $context->get('CurrentCSSProperty', true);
        // not always available
        if ($this->secretKey) $this->replace['%t'] = sha1($this->secretKey . ':' . $string);
    }

}





/**
 * Implements data: URI for base64 encoded images supported by GD.
 */
class HTMLPurifier_URIScheme_data extends HTMLPurifier_URIScheme {

    public $browsable = true;
    public $allowed_types = array(
        // you better write validation code for other types if you
        // decide to allow them
        'image/jpeg' => true,
        'image/gif' => true,
        'image/png' => true,
        );
    // this is actually irrelevant since we only write out the path
    // component
    public $may_omit_host = true;

    public function doValidate(&$uri, $config, $context) {
        $result = explode(',', $uri->path, 2);
        $is_base64 = false;
        $charset = null;
        $content_type = null;
        if (count($result) == 2) {
            list($metadata, $data) = $result;
            // do some legwork on the metadata
            $metas = explode(';', $metadata);
            while(!empty($metas)) {
                $cur = array_shift($metas);
                if ($cur == 'base64') {
                    $is_base64 = true;
                    break;
                }
                if (substr($cur, 0, 8) == 'charset=') {
                    // doesn't match if there are arbitrary spaces, but
                    // whatever dude
                    if ($charset !== null) continue; // garbage
                    $charset = substr($cur, 8); // not used
                } else {
                    if ($content_type !== null) continue; // garbage
                    $content_type = $cur;
                }
            }
        } else {
            $data = $result[0];
        }
        if ($content_type !== null && empty($this->allowed_types[$content_type])) {
            return false;
        }
        if ($charset !== null) {
            // error; we don't allow plaintext stuff
            $charset = null;
        }
        $data = rawurldecode($data);
        if ($is_base64) {
            $raw_data = base64_decode($data);
        } else {
            $raw_data = $data;
        }
        // XXX probably want to refactor this into a general mechanism
        // for filtering arbitrary content types
        $file = tempnam("/tmp", "");
        file_put_contents($file, $raw_data);
        if (function_exists('exif_imagetype')) {
            $image_code = exif_imagetype($file);
        } elseif (function_exists('getimagesize')) {
            set_error_handler(array($this, 'muteErrorHandler'));
            $info = getimagesize($file);
            restore_error_handler();
            if ($info == false) return false;
            $image_code = $info[2];
        } else {
            trigger_error("could not find exif_imagetype or getimagesize functions", E_USER_ERROR);
        }
        $real_content_type = image_type_to_mime_type($image_code);
        if ($real_content_type != $content_type) {
            // we're nice guys; if the content type is something else we
            // support, change it over
            if (empty($this->allowed_types[$real_content_type])) return false;
            $content_type = $real_content_type;
        }
        // ok, it's kosher, rewrite what we need
        $uri->userinfo = null;
        $uri->host = null;
        $uri->port = null;
        $uri->fragment = null;
        $uri->query = null;
        $uri->path = "$content_type;base64," . base64_encode($raw_data);
        return true;
    }

    public function muteErrorHandler($errno, $errstr) {}

}




/**
 * Validates file as defined by RFC 1630 and RFC 1738.
 */
class HTMLPurifier_URIScheme_file extends HTMLPurifier_URIScheme {

    // Generally file:// URLs are not accessible from most
    // machines, so placing them as an img src is incorrect.
    public $browsable = false;

    // Basically the *only* URI scheme for which this is true, since
    // accessing files on the local machine is very common.  In fact,
    // browsers on some operating systems don't understand the
    // authority, though I hear it is used on Windows to refer to
    // network shares.
    public $may_omit_host = true;

    public function doValidate(&$uri, $config, $context) {
        // Authentication method is not supported
        $uri->userinfo = null;
        // file:// makes no provisions for accessing the resource
        $uri->port     = null;
        // While it seems to work on Firefox, the querystring has
        // no possible effect and is thus stripped.
        $uri->query    = null;
        return true;
    }

}





/**
 * Validates ftp (File Transfer Protocol) URIs as defined by generic RFC 1738.
 */
class HTMLPurifier_URIScheme_ftp extends HTMLPurifier_URIScheme {

    public $default_port = 21;
    public $browsable = true; // usually
    public $hierarchical = true;

    public function doValidate(&$uri, $config, $context) {
        $uri->query    = null;

        // typecode check
        $semicolon_pos = strrpos($uri->path, ';'); // reverse
        if ($semicolon_pos !== false) {
            $type = substr($uri->path, $semicolon_pos + 1); // no semicolon
            $uri->path = substr($uri->path, 0, $semicolon_pos);
            $type_ret = '';
            if (strpos($type, '=') !== false) {
                // figure out whether or not the declaration is correct
                list($key, $typecode) = explode('=', $type, 2);
                if ($key !== 'type') {
                    // invalid key, tack it back on encoded
                    $uri->path .= '%3B' . $type;
                } elseif ($typecode === 'a' || $typecode === 'i' || $typecode === 'd') {
                    $type_ret = ";type=$typecode";
                }
            } else {
                $uri->path .= '%3B' . $type;
            }
            $uri->path = str_replace(';', '%3B', $uri->path);
            $uri->path .= $type_ret;
        }

        return true;
    }

}





/**
 * Validates http (HyperText Transfer Protocol) as defined by RFC 2616
 */
class HTMLPurifier_URIScheme_http extends HTMLPurifier_URIScheme {

    public $default_port = 80;
    public $browsable = true;
    public $hierarchical = true;

    public function doValidate(&$uri, $config, $context) {
        $uri->userinfo = null;
        return true;
    }

}





/**
 * Validates https (Secure HTTP) according to http scheme.
 */
class HTMLPurifier_URIScheme_https extends HTMLPurifier_URIScheme_http {

    public $default_port = 443;

}





// VERY RELAXED! Shouldn't cause problems, not even Firefox checks if the
// email is valid, but be careful!

/**
 * Validates mailto (for E-mail) according to RFC 2368
 * @todo Validate the email address
 * @todo Filter allowed query parameters
 */

class HTMLPurifier_URIScheme_mailto extends HTMLPurifier_URIScheme {

    public $browsable = false;
    public $may_omit_host = true;

    public function doValidate(&$uri, $config, $context) {
        $uri->userinfo = null;
        $uri->host     = null;
        $uri->port     = null;
        // we need to validate path against RFC 2368's addr-spec
        return true;
    }

}





/**
 * Validates news (Usenet) as defined by generic RFC 1738
 */
class HTMLPurifier_URIScheme_news extends HTMLPurifier_URIScheme {

    public $browsable = false;
    public $may_omit_host = true;

    public function doValidate(&$uri, $config, $context) {
        $uri->userinfo = null;
        $uri->host     = null;
        $uri->port     = null;
        $uri->query    = null;
        // typecode check needed on path
        return true;
    }

}





/**
 * Validates nntp (Network News Transfer Protocol) as defined by generic RFC 1738
 */
class HTMLPurifier_URIScheme_nntp extends HTMLPurifier_URIScheme {

    public $default_port = 119;
    public $browsable = false;

    public function doValidate(&$uri, $config, $context) {
        $uri->userinfo = null;
        $uri->query    = null;
        return true;
    }

}





/**
 * Performs safe variable parsing based on types which can be used by
 * users. This may not be able to represent all possible data inputs,
 * however.
 */
class HTMLPurifier_VarParser_Flexible extends HTMLPurifier_VarParser
{

    protected function parseImplementation($var, $type, $allow_null) {
        if ($allow_null && $var === null) return null;
        switch ($type) {
            // Note: if code "breaks" from the switch, it triggers a generic
            // exception to be thrown. Specific errors can be specifically
            // done here.
            case self::MIXED :
            case self::ISTRING :
            case self::STRING :
            case self::TEXT :
            case self::ITEXT :
                return $var;
            case self::INT :
                if (is_string($var) && ctype_digit($var)) $var = (int) $var;
                return $var;
            case self::FLOAT :
                if ((is_string($var) && is_numeric($var)) || is_int($var)) $var = (float) $var;
                return $var;
            case self::BOOL :
                if (is_int($var) && ($var === 0 || $var === 1)) {
                    $var = (bool) $var;
                } elseif (is_string($var)) {
                    if ($var == 'on' || $var == 'true' || $var == '1') {
                        $var = true;
                    } elseif ($var == 'off' || $var == 'false' || $var == '0') {
                        $var = false;
                    } else {
                        throw new HTMLPurifier_VarParserException("Unrecognized value '$var' for $type");
                    }
                }
                return $var;
            case self::ALIST :
            case self::HASH :
            case self::LOOKUP :
                if (is_string($var)) {
                    // special case: technically, this is an array with
                    // a single empty string item, but having an empty
                    // array is more intuitive
                    if ($var == '') return array();
                    if (strpos($var, "\n") === false && strpos($var, "\r") === false) {
                        // simplistic string to array method that only works
                        // for simple lists of tag names or alphanumeric characters
                        $var = explode(',',$var);
                    } else {
                        $var = preg_split('/(,|[\n\r]+)/', $var);
                    }
                    // remove spaces
                    foreach ($var as $i => $j) $var[$i] = trim($j);
                    if ($type === self::HASH) {
                        // key:value,key2:value2
                        $nvar = array();
                        foreach ($var as $keypair) {
                            $c = explode(':', $keypair, 2);
                            if (!isset($c[1])) continue;
                            $nvar[trim($c[0])] = trim($c[1]);
                        }
                        $var = $nvar;
                    }
                }
                if (!is_array($var)) break;
                $keys = array_keys($var);
                if ($keys === array_keys($keys)) {
                    if ($type == self::ALIST) return $var;
                    elseif ($type == self::LOOKUP) {
                        $new = array();
                        foreach ($var as $key) {
                            $new[$key] = true;
                        }
                        return $new;
                    } else break;
                }
                if ($type === self::ALIST) {
                    trigger_error("Array list did not have consecutive integer indexes", E_USER_WARNING);
                    return array_values($var);
                }
                if ($type === self::LOOKUP) {
                    foreach ($var as $key => $value) {
                        if ($value !== true) {
                            trigger_error("Lookup array has non-true value at key '$key'; maybe your input array was not indexed numerically", E_USER_WARNING);
                        }
                        $var[$key] = true;
                    }
                }
                return $var;
            default:
                $this->errorInconsistent(__CLASS__, $type);
        }
        $this->errorGeneric($var, $type);
    }

}





/**
 * This variable parser uses PHP's internal code engine. Because it does
 * this, it can represent all inputs; however, it is dangerous and cannot
 * be used by users.
 */
class HTMLPurifier_VarParser_Native extends HTMLPurifier_VarParser
{

    protected function parseImplementation($var, $type, $allow_null) {
        return $this->evalExpression($var);
    }

    protected function evalExpression($expr) {
        $var = null;
        $result = eval("\$var = $expr;");
        if ($result === false) {
            throw new HTMLPurifier_VarParserException("Fatal error in evaluated code");
        }
        return $var;
    }

}



