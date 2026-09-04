<?php

  /**
 * news.php
 *
 * @author Gritschacher Tobias
 * @copyright 10/2008
 * @version 0.1
 */

  class news extends FFB_Auth_User
  {
      public function __construct()
      {
          parent::__construct();
      }

      public function __default()
      {
      }

      //returns list of all visible games
      public function getNewsList()
      {
          //$selected_site = 4;
          //$limit = 5;
          $selected_site = $_POST['selected_site'];
          //$limit = $_POST['limit'];
          $limit = MAX_NEWS_PER_SITE;

          $criteria = new Criteria();
          $criteria->add(FfbNewsPeer::NEWS_GAME_ID, $this->session->game_id_player);
          $criteria->addOr(FfbNewsPeer::NEWS_GAME_ID, 0);

          $criteria->addDescendingOrderByColumn(FfbNewsPeer::NEWS_ID);

          $news = $this->returnGetNewsByCriteria($criteria);

          //$this->num_sites = round(count($news)/$limit, 0);
          $this->num_sites = ceil(count($news)/$limit);

          $slice = array_slice($news, ($selected_site-1)*$limit, $limit);

          $this->num_results = count($slice);
          $this->news = $slice;
      }

      private function returnGetNewsByCriteria($criteria) {
          $items = FfbNewsPeer::doSelect($criteria);
          $news = array();
          $i=0;
          if(items) {
              foreach($items as $item) {
                  $news[$i]["news_id"] = $item->getNewsId();
                  $news[$i]["news_title"] = $item->getNewsTitle();
                  $date = strtotime($item->getNewsDate());
                  $news[$i]["news_date"] = date('d.m.Y H:i', $date);
                  $news[$i]["news_text"] = nl2br($item->getNewsText());
                  if($item->getNewsSymbol()) {
                      $news[$i]["news_symbol"] = $item->getNewsSymbol();
                  } else {
                      $news[$i]["news_symbol"] = 0;
                  }
                  $i++;
              }
          }
          //$this->num_results = $i;
          //$this->news = $news;
          return $news;
      }

      public function __destruct()
      {
          parent::__destruct();
      }
  }

?>
