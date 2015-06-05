<?php

/**
 * This class encapsulates the logic to show a property
 *
 * @author enriqueareyan & mercerjd
 */
class library_view_helper_Tabler {        
    
    public function showTable($paginator, $view, $id, array $colNames=null){
        $ret = "";
        if (count($paginator)){
            $ret  = $this->control($view, $paginator);
            $ret .= $this->top($view, $paginator, $id, $colNames);
            $ret .= $this->middle($view, $paginator);
            $ret .= $this->bottom($view, $paginator);
            $ret .= $this->control($view, $paginator);
        }
    
        return $ret;
    }

    public function control($view, $paginator) {
        return $view->paginationControl($paginator, Zend_Paginator::getDefaultScrollingStyle(),Zend_View_Helper_PaginationControl::getDefaultViewPartial(),$view->URL_Mapping);
    }
    
    public function top($view, $paginator, $id='', array $colNames=null) {
        $ret  = "<table id=\"$id\">";
        $ret .= "<thead><tr>";
        foreach($colNames as $name) $ret .= "<td>$name</td>";
        $ret .= '</tr></thead>';
        return $ret;
    }
    
    public function middle($view, $paginator) {
        $ret = "<tbody>";
        foreach ($paginator as $row){
            $ret .= '<tr>';
            $ret .= $this->formatRow($row);
            $ret .= '</tr>';
        }
        $ret .= "</tbody>";
        return $ret;
    }
    
    public function bottom($view, $paginator) {
        return "</table>";
    }

    public function formatRow($row) {
        $ret = "";
        foreach($row as $k=>$v) $ret .= "<td>$v</td>";
        return $ret;
    }
}