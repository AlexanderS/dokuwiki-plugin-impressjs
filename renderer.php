<?php
/**
 * Renderer for XHTML output
 *
 * @author Harry Fuecks <hfuecks@gmail.com>
 * @author Andreas Gohr <andi@splitbrain.org>
 */
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

// we inherit from the XHTML renderer instead directly of the base renderer
require_once DOKU_INC.'inc/parser/xhtml.php';

class renderer_plugin_impressjs extends Doku_Renderer_xhtml {
    private $data_x = 0;
    private $data_y = 0;
    private $data_z = 0;
    private $tpl;
    private $base;
    
    public function document_start(){
        global $conf;
        global $ID;
        global $updateVersion;
        
        $this->base = DOKU_BASE.'lib/plugins/impressjs/tpl/';
        $this->tpl  = $this->getConf('template');

        // prepare seed for js and css
        $tseed   = $updateVersion;
        $depends = getConfigFiles('main');
        foreach($depends as $f) $tseed .= @filemtime($f);
        $tseed   = md5($tseed);
        
        $this->doc .= '<!DOCTYPE html>
<html lang="'.$conf['lang'].'">
<head>
    <meta name="viewport" content="width=1024" />
    <meta charset="utf-8" />
    <title>'.tpl_pagetitle($ID, true).'</title>
                
    <meta name="generator" content="impress.js" />
    <meta name="version" content="impress.js ab44798b081997319f4207dabbb052736acfc512" />
                
    <link rel="stylesheet" href="'.DOKU_BASE.'lib/exe/css.php?t=none&tseed='.$tseed.'" type="text/css" media="screen" />
    <link href="'.$this->base.$this->tpl.'/impress.css" rel="stylesheet" />
    <link href="'.$this->base.$this->tpl.'/impress-extra.css" rel="stylesheet" />
</head>
<body>
    <div id="impress">';
    }
    
    public function document_end(){
        $this->doc .= '</div>
        <script src="'.$this->base.$this->tpl.'/impress.js"></script>
        <script>impress().init();</script></body></html>';
    }
    public function section_close() {
        $this->doc .= "</div>";
        parent::section_close();
    }
    public function header($text, $level, $pos) {
        global $lang;
        
        $this->data_x += $this->getConf('data-x');
        $this->data_y += $this->getConf('data-y');
        $this->data_z += $this->getConf('data-z');
        
        $this->doc .= "<div class='".($level == 1 ? '' : 'slide ')."step' ";
        $this->doc .= "data-x='$this->data_x' data-y='$this->data_y' data-z='$this->data_z' ";
        $this->doc .= "dir='".$lang['direction']."' >";
        $this->doc .= "<h$level>$text</h$level>";
    }
}
