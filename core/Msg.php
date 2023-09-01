<?php

namespace Lazydev\Core;

class Msg
{

    /**
     * Mostra uma mensagem na próxima renderização de uma view
     * 
     * @param String $msg
     * @param int $tipo {1,2,3,4}  1=Success, 2=Warning, 3=Error, 4=Notice, 5, debug, default=Info
     */
    function __construct($msg, $tipo = 5)
    {
        switch ($tipo) {
            case 1:
                $msgHtml = $this->getMsgTemplate('success', $msg);
                break;
            case 2:
                $msgHtml = $this->getMsgTemplate('warning', $msg);
                break;
            case 3:
                $msgHtml = $this->getMsgTemplate('error', $msg);
                break;
            case 4:
                $msgHtml = $this->getMsgTemplate('notice', $msg);
                break;
            case 5:
                $this->debug($msg);
                return;
                break;
            default:
                $msgHtml = $this->getMsgTemplate('info', $msg);
        }
        $msgarr = is_array(Session::get('frameworkMsg')) ? Session::get('frameworkMsg') : [];
        array_push($msgarr, $msgHtml);
        Session::set('frameworkMsg', $msgarr);
    }

    function debug($msg)
    {
        $msgHtml = $this->getMsgTemplate('debugMsg', $msg);
        $msgarr = is_array(Session::get('frameworkDebugMsg')) ? Session::get('frameworkDebugMsg') : [];
        array_push($msgarr, $msgHtml);
        Session::set('frameworkDebugMsg', $msgarr);
    }

    private function getMsgTemplate($class, $msg)
    {
        $id = uniqid('id');
        $template = '<div class="alertContainer">';
        $template .= '<input type="checkbox" id="'.$id.'" class="alertCheckbox" autocomplete="off">';
        $template .= '<div class="alert ' . $class . '" role="alert">';        
        $template .= '<label class="alertClose" for="'.$id.'">X</label>';
        $template .= '<span class="alertText">' . $msg . '<br class="clear"></span>';
        $template .= '</div>';
        $template .=  '</div>';
        return $template;
    }

    /**
     * Busca as mensagens do sistema.<br>
     * <b>deve ser utilizado apenas no template.</b><br>
     * 
     * Exemplo: <?php echo $this->getMsg();?>
     * 
     * 
     * @return String mensagem
     */
    public static function getMsg()
    {
        $msgarr = Session::get('frameworkMsg');
        Session::set('frameworkMsg', []);
        if (is_array($msgarr)) {
            return implode("\r\n", $msgarr);
        }
        return NULL;
    }

    public static function getDebugMsg()
    {
        $msgarr = Session::get('frameworkDebugMsg');
        Session::set('frameworkDebugMsg', []);
        if (is_array($msgarr)) {
            return implode("\r\n", $msgarr);
        }
        return NULL;
    }
}
