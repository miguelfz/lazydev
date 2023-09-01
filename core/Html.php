<?php

namespace Lazydev\Core;

/**
 * classe Html
 */
class Html
{
    /*
     * Mesma coisa que o getLink, com a diferença que circunda a tag <a> com a tag <li>
     * Usada principalmente para o menu.
     * 
     * @author  Healfull 
     * @param String $name
     * @param String $controller
     * @param String $action
     * @param array $urlParams Array associativo para especificar as variáveis opcionais enviadas via get
     * @param array $linkParams Array associativo para especificar os atributos HTML adicionais da tag <a>
     * @return string
     */

    public function getMenuLink(string $name, string $controller, string $action, array $urlParams = NULL, array $linkParams = NULL)
    {
        if (!Acl::check($controller, $action, Session::get(Acl::$loggedSession))) {
            return '';
        }
        return '<li>' . $this->getLink($name, $controller, $action, $urlParams, $linkParams) . '</li>';
    }

    /**
     * Constrói um link (ancora) padrão lazyphp.
     * 
     * <b>Exemplo de uso 1:</b><br>
     * <?php echo $this->Html->getLink('Listar usuarios', 'Usuario', 'all');?>
     * 
     * <b>Retorna: </b><br>
     * &lt;a href=&quot;/Usuario/all&quot;&gt;Listar usuarios&lt;/a&gt;<br>
     * 
     * <b>Exemplo de uso 2:</b><br>
     * <?php echo $this->Html->getLink('Ver usuario', 'Usuario', 'all', array('id' => 1));?>
     * 
     * <b>Retorna: </b><br>
     * &lt;a href=&quot;/Usuario/all/?id=1&quot;&gt;Ver Usuario&lt;/a&gt;<br>
     * 
     * @param String $name
     * @param String $controller
     * @param String $action
     * @param array $urlParams Array associativo para especificar as variáveis opcionais enviadas via get
     * @param array $linkParams Array associativo para especificar os atributos HTML adicionais da tag <a>
     * @return string
     */
    public function getLink(string $name, string $controller, string $action, array $urlParams = NULL, array $linkParams = NULL)
    {
        if (!Acl::check($controller, $action, Session::get(Acl::$loggedSession))) {
            return '';
        }
        $anchor = '';
        $link = '<a href="';

        $url = PATH . '/' . $controller . '/' . $action . '/';
        $link .= $url;
        if (is_array($urlParams)) {
            $carr = (Config::get('criptedGetParamns'));
            if (is_array($carr)) {
                foreach ($carr as $param) {
                    foreach ($urlParams as $key => $value) {
                        if ($param === $key) {
                            $urlParams[$key] = Cript::cript($value);
                        }
                    }
                }
            }

            foreach ($urlParams as $key => $value) {
                if ($key === '#') {
                    $anchor = '#' . $value;
                } elseif (is_int($key)) {
                    $link .= $value . '/';
                } else {
                    $link .= $key . ':' . $value . '/';
                }
                unset($urlParams[$key]);
            }
        }
        $link .= $anchor . '"';
        if (is_array($linkParams)) {
            foreach ($linkParams as $key => $value) {
                $link .= ' ' . $key . '="' . $value . '"';
            }
        }
        $link = str_replace('//', '/', $link);
        $link .= '>' . $name . '</a>';
        return $link;
    }

    /**
     * Constrói um link (ancora) padrão lazyphp aberto em MODAL.
     * 
     * <b>Exemplo de uso 1:</b><br>
     * <?php echo $this->Html->getModalLink('Listar usuarios', 'Usuario', 'all');?>
     * 
     * <b>Retorna: </b><br>
     * &lt;a href=&quot;#&quot; data-toggle=&quot;modal&quot;  data-href=&quot;/Usuario/all&quot;&gt;Listar usuarios&lt;/a&gt;<br>
     * 
     * <b>Exemplo de uso 2:</b><br>
     * <?php echo $this->Html->getLink('Ver usuario', 'Usuario', 'all', array('id' => 1));?>
     * 
     * <b>Retorna: </b><br>
     * &lt;a href=&quot;#&quot; data-toggle=&quot;modal&quot;  data-href=&quot;/Usuario/all/?id=1&quot;&gt;Ver Usuario&lt;/a&gt;<br>
     * 
     * @param String $name
     * @param String $controller
     * @param String $action
     * @param array $urlParams Array associativo para especificar as variáveis opcionais enviadas via get
     * @param array $linkParams Array associativo para especificar os atributos HTML adicionais da tag <a>
     * @return string
     */
    public function getModalLink(string $name, string $controller, string $action, array $urlParams = array(), array $linkParams = NULL)
    {
        if (!Acl::check($controller, $action, Session::get(Acl::$loggedSession))) {
            return '';
        }
        $anchor = '';
        $link = '<a data-href="#' . $action . '-' . implode(':', $urlParams) . '" data-target="#modal" data-toggle="modal" href="';

        $url = PATH . '/' . $controller . '/' . $action . '/';
        $link .= $url;
        if (is_array($urlParams)) {
            $carr = (Config::get('criptedGetParamns'));
            if (is_array($carr)) {
                foreach ($carr as $param) {
                    foreach ($urlParams as $key => $value) {
                        if ($param === $key) {
                            $urlParams[$key] = Cript::cript($value);
                        }
                    }
                }
            }

            foreach ($urlParams as $key => $value) {
                if ($key === '#') {
                    $anchor = '#' . $value;
                } elseif (is_int($key)) {
                    $link .= $value . '/';
                } else {
                    $link .= $key . ':' . $value . '/';
                }
                unset($urlParams[$key]);
            }
        }
        $link .= $anchor . '/template:off"';
        if (is_array($linkParams)) {
            foreach ($linkParams as $key => $value) {
                $link .= ' ' . $key . '="' . $value . '"';
            }
        }
        $link .= '>' . $name . '</a>';
        return str_replace('//', '/', $link);
    }

    /**
     * Constrói uma URL no padrão lazyphp.
     * 
     * <b>Exemplo de uso 1:</b><br>
     * <?php echo $this->Html->getUrl('Usuario', 'all');?>
     * 
     * <b>Retorna se rewriteURL estiver definido: </b><br>
     * /Usuario/all<br>
     * 
     * <b>Retorna se rewriteURL NÃO estiver definido: </b><br>
     * index.php?m=Usuario&p=all<br>
     * 
     * <b>Exemplo de uso 2:</b><br>
     * <?php echo $this->Html->getUrl('Usuario', 'all', array('id' => 1));?>
     * 
     * <b>Retorna se rewriteURL estiver definido: </b><br>
     * /Usuario/all/?id=1<br>
     * 
     * <b>Retorna se rewriteURL NÃO estiver definido: </b><br>
     * index.php?m=Usuario&p=all&id=1<br>
     * 
     * @param String $controller
     * @param String $action
     * @param array $urlParams
     * @return string
     */
    public function getUrl(string $controller, string $action, array $urlParams = NULL)
    {
        if (!Acl::check($controller, $action, Session::get(Acl::$loggedSession))) {
            return '';
        }
        $anchor = '';
        $link = '';
        $url = PATH . '/' . $controller . '/' . $action . '/';
        $link .= $url;
        if (is_array($urlParams)) {
            $carr = (Config::get('criptedGetParamns'));
            if (is_array($carr)) {
                foreach ($carr as $param) {
                    foreach ($urlParams as $key => $value) {
                        if ($param === $key) {
                            $urlParams[$key] = Cript::cript($value);
                        }
                    }
                }
            }

            foreach ($urlParams as $key => $value) {
                if ($key === '#') {
                    $anchor = '#' . $value;
                } elseif (is_int($key)) {
                    $link .= $value . '/';
                } else {
                    $link .= $key . ':' . $value . '/';
                }
                unset($urlParams[$key]);
            }
        }
        $link .= $anchor;
        return str_replace('//', '/', $link);
    }

    /**
     * Retorna um form-group padrão Bootstrao contendo um campo de formulário input
     *  
     * @param String $label
     * @param Array $params
     * @return string
     */
    public function getFormInput(string $label, array $params = [])
    {
        if (key_exists('type', $params) && $params['type'] == 'checkbox') {
            return $this->getFormInputCheckbox($label, $params);
        }
        $id = key_exists('id', $params) ? $params['id'] : 'id' . uniqid();
        $params['id'] = $id;
        $field = '<div class="form-group" ' . ((key_exists('type', $params) && $params['type'] == 'hidden') ? 'hidden' : '') . '><!-- form-group -->' . "\n";
        if ($label) {
            $field .= '<label for="' . $id . '" class="font-weight-bold mb-0">' . "\n";
            $field .= $label;
            if (key_exists('required', $params) || in_array('required', $params)) {
                $field .= ' <small class="text-danger font-weight-bold">*</small> ' . "\n";
            }
            $field .= '</label>' . "\n";
        }
        $class = 'form-control ';
        if (key_exists('class', $params)) {
            $class .= $params['class'];
        }
        #$field .= '<div class="col-sm-6 col-lg-8 col-xl-10">';
        $field .= '<input class="' . $class . '"';
        $field .= $this->extractParams($params);
        $field .= '>' . "\n";
        if (key_exists('help', $params)) {
            $field .= '<small id="emailHelp" class="form-text text-muted">' . $params['help'] . '</small>' . "\n";
        }
        $field .= '</div><!-- /form-group -->';
        return $field;
    }

    private function getFormInputCheckbox(string $label = '', array $params = [])
    {
        $id = key_exists('id', $params) ? $params['id'] : 'id' . uniqid();
        $params['id'] = $id;
        $field = '<div class="form-group form-check">';
        $field .= '<label for="' . $id . '">';
        $class = 'form-check-input ';
        if (key_exists('class', $params)) {
            $class .= $params['class'];
        }
        $field .= '<input class="' . $class . '"';
        $field .= $this->extractParams($params);
        $field .= '> ' . $label . '</label>';
        $field .= '</div>';
        return $field;
    }

    /**
     * Retorna um form-group padrão Bootstrao contendo um campo de formulário textarea
     *  
     * @param String $label
     * @param String $params
     * @param String $value
     * @return string
     */
    public function getFormTextarea(string $label, array $params = [])
    {
        $value = key_exists('value', $params) ? $params['value'] : '';
        $id = key_exists('id', $params) ? $params['id'] : 'id' . uniqid();
        $params['id'] = $id;
        $field = '<div class="form-group">';
        $field .= '<label for="' . $id . '" class="font-weight-bold mb-0">';
        $field .= $label;
        if (key_exists('required', $params) || in_array('required', $params)) {
            $field .= ' <small class="text-danger font-weight-bold">*</small> ';
        }
        $field .= '</label>';
        $field .= '<textarea class="form-control" ';
        foreach ($params as $key => $v) {
            if ($key == 'value') {
                continue;
            }
            if (is_int($key)) {
                $field .= ' ' . $v;
            } else {
                $field .= ' ' . $key . '="' . $v . '"';
            }
        }
        $field .= '>' . $value . '</textarea>';
        if (key_exists('help', $params)) {
            $field .= '<small id="emailHelp" class="form-text text-muted">' . $params['help'] . '</small>' . "\n";
        }
        $field .= '</div>';
        return $field;
    }

    /**
     * Retorna um form-group padrão Bootstrao contendo um campo de formulário textarea 
     * com wysiwyg editor
     *  
     * @param String $label
     * @param Array $params
     * @return string
     */
    public function getFormTextareaHtml(string $label, array $params)
    {
        $value = key_exists('value', $params) ? $params['value'] : '';
        $id = key_exists('id', $params) ? $params['id'] : 'id' . uniqid();
        $params['id'] = $id;
        $field = '<div class="form-group">';
        $field .= '<label for="' . $id . '" class="font-weight-bold mb-0">';
        $field .= $label;
        if (key_exists('required', $params) || in_array('required', $params)) {
            $field .= ' <small class="text-danger font-weight-bold">*</small> ';
        }
        $field .= '</label>';
        $field .= '<textarea class="form-control" ';
        foreach ($params as $key => $v) {
            if ($key == 'value') {
                continue;
            }
            if (is_int($key)) {
                $field .= ' ' . $v;
            } else {
                $field .= ' ' . $key . '="' . $v . '"';
            }
        }
        $field .= '>' . $value . '</textarea>';
        $field .= '</div>';
        $field .= '<script>';
        $field .= '$(document).ready(function() {';
        $field .= '$(\'#' . $id . '\').summernote(';
        $field .= '{height:200,dialogsInBody: true, prettifyHtml: true, toolbar: [
                [\'style\', [\'bold\', \'italic\', \'underline\', \'strikethrough\', \'clear\']],
                [\'fontsize\', [\'fontsize\']],
                [\'insert\', [ \'gxcode\']],
                [\'color\', [\'color\']],
                [\'tabela\', [\'table\']],
                [\'para\', [\'ul\', \'ol\', \'paragraph\']],
                [\'media\', [\'link\', \'picture\',\'video\']],
                [\'misc\', [\'fullscreen\',\'codeview\']]
                ], lang: \'pt-BR\'}';
        $field .= ');';
        $field .= '});';
        $field .= '</script>';
        return $field;
    }

    /**
     * Retorna um form-group padrão Bootstrao contendo um campo de formulário input radio
     *  
     * @param String $label
     * @param Array $params
     * @param Array $values Array de value=>visible_value
     * @return string
     */
    public function getFormInputRadio(string $label, $params = [], $values = [])
    {
        $field = '<div class="form-group row">';
        $field .= '<label class="col-sm-2 col-form-label font-weight-bold mb-0">';
        $field .= $label;
        if (key_exists('required', $params) || in_array('required', $params)) {
            $field .= ' <small class="text-danger font-weight-bold">*</small> ';
        }
        $field .= '</label>';
        $field .= '<div class="col-sm-10">';
        $checked = FALSE;
        if (key_exists('checked', $params)) {
            $checked = $params['checked'];
            unset($params['checked']);
        }
        foreach ($values as $k => $v) {
            $field .= '<div class="form-check">';
            $id = 'id' . uniqid();
            $params['id'] = $id;
            $class = 'form-check-input ';
            if (key_exists('class', $params)) {
                $class .= $params['class'];
            }
            $field .= '<input class="' . $class . '"';
            $params['value'] = $k;
            $field .= $this->extractParams($params);
            if ($checked) {
                $field .= ($checked == $k ? ' checked' : '');
            }
            $field .= '>';
            $field .= '<label class="form-check-label" for="' . $id . '">';
            $field .= $v;
            $field .= '</label>';
            $field .= '</div>';
        }
        $field .= '</div></div>';
        return $field;
    }

    /**
     * 
     * @param string $label
     * @param Array $params
     * @param Array $values
     * @return string
     */
    public function getFormSelect(string $label, array $params = [], array $values = [])
    {
        if (key_exists('type', $params) && $params['type'] == 'checkbox') {
            return $this->getFormInputCheckbox($label, $params);
        }
        $id = key_exists('id', $params) ? $params['id'] : 'id' . uniqid();
        $field = '<div class="form-group">';
        $field .= '<label for="' . $id . '"  class="font-weight-bold mb-0">';
        $field .= $label;
        if (key_exists('required', $params) || in_array('required', $params)) {
            $field .= ' <small class="text-danger font-weight-bold">*</small> ';
        }
        $field .= '</label>';
        $selected = FALSE;
        if (key_exists('selected', $params)) {
            $selected = $params['selected'];
            unset($params['selected']);
        }
        $class = 'form-control ';
        if (key_exists('class', $params)) {
            $class .= $params['class'];
        }
        $field .= '<div class="">';
        $field .= '<select class="' . $class . '"';
        $field .= $this->extractParams($params);
        $field .= '>';
        if (in_array(null, $values, true)) {
            $field .= '<option value=""> </option>';
        }
        foreach ($values as $k => $v) {
            $checked = ($k == $selected) ? 'selected' : '';
            $field .= '<option ' . $checked . ' value="' . $k . '">' . $v . '</option>';
        }
        $field .= '</select>';
        $field .= '</div></div>';
        return $field;
    }

    private function extractParams(array $params)
    {
        $field = '';
        foreach ($params as $key => $value) {
            if (is_int($key)) {
                $field .= ' ' . $value;
            } else {
                $field .= ' ' . $key . '="' . $value . '"';
            }
        }
        return $field;
    }
}
