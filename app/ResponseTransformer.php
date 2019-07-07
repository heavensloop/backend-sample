<?php

namespace App;
use Carbon\Carbon;

trait ResponseTransformer {
  
  public function mapEpisodeWithComments($episode, $comments) {
    return [
        "id" => $episode->id,
        "name" => $episode->name,
        "episode" => $episode->episode,
        "release_date" => Carbon::parse($episode->air_date)->toDateTimeString(),
        "comments" => $comments
    ];
  }

  private function getPage($page_no, $template) {
      return $template . "?page={$page_no}";
  }

  public function transformPagination($result, $data, $url_template, $current_page) {
    $info = $result->info;
    $per_page = 20;
    $previous_page = $info->pages == 1 ? null : $this->getPage($current_page - 1, $url_template);
    $nex_page = $info->pages == $current_page ? null : $this->getPage($current_page + 1, $url_template);
    $from = ($current_page * $per_page) - $per_page + 1;
    $to = $from + count($data);
    $pagination = [
      "current_page" => $current_page,
      "data" => $data,
      "first_page_url" => $this->getPage(1, $url_template),
      "from" => $from,
      "last_page" => $info->pages,
      "last_page_url" => $this->getPage($info->pages, $url_template),
      "next_page_url" => $nex_page,
      "path" => $url_template,
      "per_page" => $per_page,
      "prev_page_url" => $previous_page,
      "to" => $to,
      "total" => $info->count
    ];

    return $pagination;
  }
}