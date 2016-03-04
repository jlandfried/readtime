<?php
namespace jlandfried\ReadTime\Counter;

use jlandfried\ReadTime\Counter\QueryCounterInterface;

use QueryPath\Query;

class DecrementCounter implements QueryCounterInterface {
  protected $initialDuration;
  protected $minDuration;
  protected $decrementBy;

  public function __construct($initialDuration = 12, $minDuration = 3, $decrementBy = 1) {
    $this->setMinDuration($minDuration);
    $this->setInitialDuration($initialDuration);
    $this->setDecrementBy($decrementBy);
  }

  public function count(Query $query, $selector) {
    $qty = $query->find($selector)->count();
    $individual_duration = $this->getInitialDuration();
    $full_duration = 0;
    $counter = 1;

    while ($counter <= $qty) {
      // Loop through, decrementing the amount of time each element takes
      // to read.
      if ($individual_duration > $this->getMinDuration()) {
        $full_duration += $individual_duration;
        $individual_duration = $individual_duration - $this->getDecrementBy();
      }
      // Until the minimum duration, then stop looping and multiply the minimum
      // read duration by the number of elements remaining.
      else {
        $remaining = $qty - $counter;
        $full_duration += $this->getMinDuration() * ($remaining + 1);
        break;
      }

      $counter++;
    }

    return $full_duration;
  }

  protected function countSelector() {

  }
  /**
   * @return mixed
   */
  public function getDecrementBy() {
    return $this->decrementBy;
  }

  /**
   * @param mixed $decrementBy
   */
  public function setDecrementBy($decrementBy) {
    $this->decrementBy = $decrementBy;
  }

  /**
   * @param int $initialDuration
   */
  public function setInitialDuration($initialDuration) {
    $this->initialDuration = $initialDuration;
  }

  /**
   * @param mixed $minDuration
   */
  public function setMinDuration($minDuration) {
    $this->minDuration = $minDuration;
  }

  /**
   * @return mixed
   */
  public function getInitialDuration() {
    return $this->initialDuration;
  }

  /**
   * @return mixed
   */
  public function getMinDuration() {
    return $this->minDuration;
  }

}
