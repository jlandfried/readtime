<?php
namespace jlandfried\ReadTime\Test;


use jlandfried\ReadTime\Counter\DecrementCounter;
use jlandfried\ReadTime\Counter\WordCounter;
use jlandfried\ReadTime\ReadTime;
use PHPUnit_Framework_TestCase;

/**
 * @group readtimer
 */
class ReadTimeTest extends PHPUnit_Framework_TestCase {

  private $markup = [];

  public function setUp() {
    $this->markup['img_markup'] = <<<EOF
<p>
    <img src="test.jpg">
    <img src="test.jpg">
    <img src="test.jpg">
    <img src="test.jpg">
    <img src="test.jpg">
    <img src="test.jpg">
</p>
EOF;

    $this->markup['text_markup'] = <<<EOF
<p>Quisque velit nisi, pretium ut lacinia in, elementum id enim. Vivamus magna justo,
lacinia eget consectetur sed, convallis at tellus. Curabitur non nulla sit amet nisl
tempus convallis quis ac lectus. Sed porttitor lectus nibh. Vestibulum ante ipsum primis
in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet
aliquam vel, ullamcorper sit amet ligula. Pellentesque in ipsum id orci porta dapibus. Mauris
blandit aliquet elit, eget tincidunt nibh pulvinar a. Nulla porttitor accumsan tincidunt.
Curabitur aliquet quam id dui posuere blandit. Sed porttitor lectus nibh.</p>
EOF;
  }

  public function testDecrementCounter() {
    $config = ['img' => new DecrementCounter(12, 10)];
    $timer = new ReadTime();
    $timer->setConfig($config);
    $this->assertEquals(63, $timer->estimateReadTime($this->markup['img_markup']));
  }

  public function testWordCounter() {
    $timer = new ReadTime();
    $this->assertEquals(20, $timer->estimateReadTime($this->markup['text_markup']));
  }

  public function testCombinedCounter() {
    $config = ['img' => new DecrementCounter(12, 10)];
    $timer = new ReadTime();
    $timer->setConfig($config);
    $this->assertEquals(83, $timer->estimateCombinedReadTime($this->markup));
  }

  public function testSetWordCounter() {
    $timer = new ReadTime();
    $word_count_config = new WordCounter(100);
    $timer->setWordCounter($word_count_config);
    $this->assertEquals($word_count_config, $timer->getWordCounter());
  }

}
