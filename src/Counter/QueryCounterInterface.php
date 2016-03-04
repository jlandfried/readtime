<?php

namespace jlandfried\ReadTime\Counter;

use QueryPath\Query;

interface QueryCounterInterface {
  public function count(Query $query, $selector);
}