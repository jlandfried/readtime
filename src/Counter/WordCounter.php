<?php

namespace jlandfried\ReadTime\Counter;



use QueryPath\Query;

class WordCounter implements WordCounterInterface {
  protected $wordsPerMinute;
  protected $wordsPerSecond;

  /**
   * WordCounter constructor.
   * @param int $wpm
   */
  public function __construct($wpm = 275) {
    $this->setWordsPerMinute($wpm);
    $this->setWordsPerSecond($this->calculateWordsPerSecond());
  }

  /**
   * @return mixed
   */
  public function getWordsPerSecond() {
    return $this->wordsPerSecond;
  }

  /**
   * @param mixed $wordsPerSecond
   */
  public function setWordsPerSecond($wordsPerSecond) {
    $this->wordsPerSecond = $wordsPerSecond;
  }

  protected function calculateWordsPerSecond() {
    return $this->getWordsPerMinute() / 60;
  }

  /**
   * @return int
   */
  public function getWordsPerMinute() {
    return $this->wordsPerMinute;
  }

  /**
   * @param int $wordsPerMinute
   */
  public function setWordsPerMinute($wordsPerMinute) {
    $this->wordsPerMinute = $wordsPerMinute;
  }

  public function count($text) {
    $word_count = str_word_count($text);
    return round($word_count / $this->getWordsPerSecond());
  }
}