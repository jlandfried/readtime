<?php
namespace jlandfried\ReadTime;


interface ReadTimeInterface {
  public function estimateCombinedReadTime(array $articles);

  public function estimateReadTime($article);

}