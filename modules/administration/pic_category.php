<?php

/**
 * PIC - category-Klasse;
 * Kategorien verwalten
 *
 * @author Gritschacher Tobias
 * @copyright 09/2008
 * @version 0.1
 *
 */

class pic_category extends FFB_Auth_AdminPictory {

    public function __construct() {
        parent::__construct();
    }

    public function __default() {
    }

    public function getList() {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(PicCategoryPeer::CATEGORY_TITLE);
        $this->getCategoryByCriteria($criteria);
    }

    //returns categories by given criteria
    private function getCategoryByCriteria($criteria) {
        $items = PicCategoryPeer::doSelect($criteria);
        $categories = array();
        $i=0;
        foreach($items as $item) {
            if($item) {
                $categories[$i]['category_id'] = $item->getCategoryId();
                $categories[$i]['category_title'] = $item->getCategoryTitle();
                $i++;
            }
        }
        $this->numResults = $i;
        $this->categories = $categories;
    }
}
?>
