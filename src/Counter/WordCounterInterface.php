<?php
namespace jlandfried\ReadTime\Counter;


interface WordCounterInterface {
  public function count($text);
}