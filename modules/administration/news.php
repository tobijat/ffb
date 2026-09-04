<?php

/**
 * ADMIN - NEWS-Klasse;
 * News hinzufügen/ändern/löschen
 *
 * @author Gritschacher Tobias
 * @copyright 10/2008
 * @version 0.1
 *
 */

class news extends FFB_Auth_AdminFfb {

    public function __construct() {
        parent::__construct();
        $this->htmlFile = 'news.php';
    }

    public function __default() {
        $this->administration_modus = $_POST['administration_modus'];
        $this->post = $_POST;
        if (!empty($_POST)) {
            if(isset($_POST['news_administration_change_x']) || isset($_POST['news_administration_change']))
                { $this->changeItem($_POST['news_id']); }
            elseif(isset($_POST['news_administration_delete_x']) || isset($_POST['news_administration_delete']))
            {
                if($this->validateDelete($_POST['news_id']))
                    $this->deleteItem($_POST['news_id']);
                else {
                    $errors = array();
                }
            }
            else {
                if($this->validate()) {
                    if(isset($_POST['news_administration_insert']))
                        { $this->addItem(); }
                    elseif(isset($_POST['news_administration_update']))
                        { $this->updateItem($_POST['news_id']); }
                } else
                    { $this->administration_status = STATUS_CODE_ERROR_VALIDATION; }
            }
        }
        $this->getList();
    }

    //gesamte Team-Liste holen
    public function getList() {
        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(FfbNewsPeer::NEWS_DATE);
        $list = FfbNewsPeer::doSelect($criteria);
        $teams = array();
        if($list) {
            $i=0;
            foreach($list as $item) {
                $news[$i]['news_id'] = $item->getNewsId();
                $news[$i]['news_title'] = $item->getNewsTitle();
                $news[$i]['news_text'] = nl2br($item->getNewsText());
                $news[$i]['news_date'] = $item->getNewsDate();
                $news[$i]['news_symbol'] = $item->getNewsSymbol();
                $news[$i]['news_priority'] = $item->getNewsPriority();
                $i++;
            }
        }
        $this->numResults = $i;
        $this->news = $news;
    }

    //einen Newseintrag ändern - bestehende Daten holen
    private function changeItem($id) {
        $news = array();
        if($id) {
            $item = FfbNewsPeer::retrieveByPK($id);
            if($item) {
                $news['news_id'] = $item->getNewsId();
                $news['news_title'] = $item->getNewsTitle();
                $news['news_text'] = $item->getNewsText();
                $news['news_date'] = $item->getNewsDate();
                $news['news_symbol'] = $item->getNewsSymbol();
                $news['news_priority'] = $item->getNewsPriority();
                $news['news_game_id'] = $item->getNewsGameId();
            }
        }
        $this->post = $news;
        $this->administration_modus = 'update';
    }

    //neuen Newseintrag hinzufügen
    private function addItem() {
        $new_item = new FfbNews();
        $new_item->setNewsTitle($_POST['news_title']);
        $new_item->setNewsText($_POST['news_text']);
        $new_item->setNewsSymbol($_POST['news_symbol']);
        $new_item->setNewsPriority($_POST['news_priority']);
        $new_item->setNewsGameId($_POST['news_game_id']);

        $now = time();
        $date = date('Y', $now).'-'.date('m', $now).'-'.date('d', $now).' '.date('H', $now).':'.date('i', $now).':'.date('s', $now);
        $new_item->setNewsDate($date);

        $new_item->save();
        $this->administration_answer = 'Newsentry successfully added!';
        $this->administration_status = STATUS_CODE_SUCCESS_INSERT;

    }

    //bestehenden Newseintrag updaten
    private function updateItem($id) {
        $exist_item = FfbNewsPeer::retrieveByPK($id);
        if($exist_item) {
            $exist_item->setNewsTitle($_POST['news_title']);
            $exist_item->setNewsText($_POST['news_text']);
            $exist_item->setNewsSymbol($_POST['news_symbol']);
            $exist_item->setNewsPriority($_POST['news_priority']);
            $exist_item->setNewsGameId($_POST['news_game_id']);
            $exist_item->save();
            $this->administration_answer = 'Existing Newsentry successfully updated!';
            $this->administration_status = STATUS_CODE_SUCCESS_UPDATE;
        }
    }

    //check if deleting is allowed
    private function validateDelete($id) {
        $item = FfbNewsPeer::retrieveByPK($id);
        $errors = array();
        if(!$item) {
            $errors[] = 'Newsentry not found! Wrong ID or site reloaded?';
            $this->errors = $errors;
            return false;
        }
        return true;
    }

    //Newseintrag löschen
    private function deleteItem($id) {
        $item = FfbNewsPeer::retrieveByPK($id);

        FfbNewsPeer::doDelete($item);
        $this->administration_answer = 'Existing Newsentry successfully deleted!';
        $this->administration_status = STATUS_CODE_SUCCESS_DELETE;
    }

    //Formular validieren
    private function validate() {
        $errors = array();

        //check for empty fields
        if (empty($_POST) || !$_POST['news_title'] || !$_POST['news_text'])
        {
            $errors[] = 'You have to fill out all fields marked with a *!';
        }

        require_once('Validate.php');
        if($_POST['news_priority'] && !Validate::number($_POST['news_priority'])) {
            $errors[] = 'Priority not valid!';
        }

        if(count($errors))
        {
            $this->errors = $errors;
            return false;
        }
        return true;
    }
}
?>
