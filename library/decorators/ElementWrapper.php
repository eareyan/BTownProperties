<?php
class library_decorators_ElementWrapper extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $errors = $this->getElement()->getMessages();
        if (!empty($errors)) {
            //$errors .= ' has-errors';
            return '<p class="oops">' . $content . '<br/>' . print_r($errors['isEmpty'], true) . '</p>';
        }
        return $content;
    }
}
?>
