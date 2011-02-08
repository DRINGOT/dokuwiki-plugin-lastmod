<?php
/**
 * Lastmod Plugin: Display the timestamp of the last modification 
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Dennis Ploeger <develop@dieploegers.de>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_lastmod extends DokuWiki_Syntax_Plugin {

    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Dennis Ploeger',
            'email'  => 'develop@dieploegers.de',
            'date'   => '2008-11-09',
            'name'   => 'Lastmod Plugin',
            'desc'   => 'Displays the timestamp of the last modification of the current or another page.',
            'url'    => 'http://wiki.splitbrain.org/plugin:lastmod',
        );
    }

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'substition';
    }
   
    /**
     * What about paragraphs?
     */
    function getPType(){
        return 'normal';
    }

    /**
     * Where to sort in?
     */ 
    function getSort(){
        return 155;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~LASTMOD[^~]*~~',$mode,'plugin_lastmod');
    }


    /**
     * Handle the match
     */

    function handle($match, $state, $pos, &$handler){

        global $ID,$INFO;

        if (preg_match("/:/", $match)) {

            preg_match("/:([^~]*)/", $match, $matches);

            $id = $matches[1];

            $id_save = $ID;
            $ID = $id;

            $tmp_info = pageinfo();

            $lastmod = $tmp_info['lastmod'];

            $ID = $id_save;

        } else {

            $lastmod = $INFO['lastmod'];

        }
    
        return array($lastmod);
    }

    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {
        global $INFO, $conf;

        if($mode == 'xhtml'){

            if (preg_match("/%/", $conf['dformat'])) {
            
                $renderer->doc .= strftime($conf['dformat'], $data[0]);

            } else {

                $renderer->doc .= date($conf['dformat'], $data[0]);

            }

            return true;
        }
        return false;
    }

}

//Setup VIM: ex: et ts=4 enc=utf-8 :

?>
