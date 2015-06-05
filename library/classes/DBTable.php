<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBTable
 *
 * @author mercerjd
 */
class library_classes_DBTable extends ArrayObject {
    function __construct($a) {
       parent::__construct($a);
    }

    function element($e) {
?>
            <td><?php echo $e; ?></td>
<?php
    }
    
    function __toString() {
        if(count($this)==0) return '';
        
        $fields = array_keys($this[0]);
        
        ob_start();
        ?>
        <table border="1">
            <thead>
                <tr>
                    <?php foreach($fields as $field): ?>
                    <th><?php echo $field ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($this as $row): ?>
                <tr>
                    <?php foreach($row as $element): ?>
                    <?php $this->element($element); ?>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php
        return ob_get_clean();
    }    
}

?>
