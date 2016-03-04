<?php
namespace jlandfried\ReadTime;

use jlandfried\ReadTime\Counter\DecrementCounter;
use jlandfried\ReadTime\Counter\QueryCounterInterface;
use jlandfried\ReadTime\Counter\WordCounter;
use QueryPath;

class ReadTime implements ReadTimeInterface {
  protected $config = [];

  protected $wordCounter;

  protected $wordsPerMinute = 275;

  public function setConfig(array $config) {
    $this->config = $config;
  }

  public function setWordCounter($counter) {
    $this->wordCounter = $counter;
  }

  public function getWordCounter() {
    return $this->wordCounter ? $this->wordCounter : new WordCounter();
  }

  /**
   * @param array $articles
   * @return number
   */
  public function estimateCombinedReadTime(array $articles) {
    $times = [];
    foreach($articles as $article) {
      $times[] = $this->estimateReadTime($article);
    }
    var_dump($times);
    return array_sum($times);
  }

  /**
   * @param $markup
   * @return int
   */
  public function estimateReadTime($markup) {
    $parser = $this->getQuery($markup);
    $config = $this->getConfig();
    $read_time = 0;

    foreach ($config as $selector => $counter) {
      $read_time += $counter->count($parser, $selector);
    }

    $read_time += $this->estimateWordCountTime($parser);

    return $read_time;
  }

  public function estimateWordCountTime($parser) {
    $text = $parser->text();
    $counter = $this->getWordCounter();
    return $counter->count($text);
  }

  /**
   * @param $markup
   * @return \QueryPath
   */
  protected function getQuery($markup) {
    return QueryPath::withHTML5($markup);
  }

  private function getConfig() {
    return $this->config;
  }

}
