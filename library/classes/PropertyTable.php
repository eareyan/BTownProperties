<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PropertyTable
 *
 * @author mercerjd
 */
class library_classes_PropertyTable extends library_classes_DBTable {

    function element($e) {
?>
            <td><?php echo "[$e]"; ?></td>
<?php
    }
}

?>
