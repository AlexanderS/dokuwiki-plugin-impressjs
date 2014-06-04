<?php

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class action_plugin_impressjs extends DokuWiki_Action_Plugin {

    /**
     * Constructor
     */
    function action_plugin_impressjs() {
        $this->setupLocale();
    }

    /**
     * register the eventhandlers
     */
    function register(Doku_Event_Handler $contr) {
        $contr->register_hook('TEMPLATE_PAGETOOLS_DISPLAY', 'BEFORE', $this, 'addbutton', array());
    }

    function array_insert(&$array, $index, $insert) {
        $size = count($array);

        if ($index < 0) {
            $index = $size + $index;
        }

        if ($index <= $size) {
            $array = array_slice($array, 0, $index, true) +
                $insert +
                array_slice($array, $index, $size - 1, true);
        }
    }

    /**
     * Add impressjs-button to pagetools
     *
     * @param Doku_Event $event
     * @param mixed      $param not defined
     */
    public function addbutton(&$event, $param) {
        global $ID, $conf;

        $jslocal = $this->getLang('js');

        switch($conf['template']) {
            case 'dokuwiki':
            case 'arago':
            case 'adoradark':
                $this->array_insert($event->data['items'], -1, array('impressjs' =>
                    '<li>'
                    .'    <a href='.exportlink($ID, 'impressjs').'  class="action impressjs" rel="nofollow" title="'.$jslocal['btn_impressjs'].'">'
                    .'        <span>'.$jslocal['btn_impressjs'].'</span>'
                    .'    </a>'
                    .'</li>'));
            break;
        }
    }
}
