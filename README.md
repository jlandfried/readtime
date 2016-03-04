[![Build Status](https://travis-ci.org/jlandfried/readtime.svg?branch=master)](https://travis-ci.org/jlandfried/readtime)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jlandfried/readtime/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jlandfried/readtime/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jlandfried/readtime/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jlandfried/readtime/?branch=master)

ReadTime is a flexible tool to allow calculation of the estimated amount of time it will take a human to read content in the form of HTML markup.

**Simplest usage example:**

```php
use jlandfried\ReadTime\ReadTime;

$markup = '<p>some HTML markup</p>';
$timer = new ReadTime().
$timer->estimateReadTime($markup);

```

This will parse the text within the `$markup` variable and determine how long it will take an average person to read the text contained within. By default, the average reading speed is set to 275 words per minute (as determined by [Medium](https://medium.com) in [this article](https://medium.com/the-story/read-time-and-you-bc2048ab620c)).

It then returns the amount of time (in seconds) that the expected average user will take to read the content.

### Counters ###
ReadTime supports the concept of counters, which are additional checks on supplied markup that will allow users to use css3 style selectors to find elements within the provided markup that may need to be counted differently (ex: images).

Adding configuration for counters is easy, just pass the `setConfig` method an `array` keyed by the selector you want to parse for, and use configured counters for the values.

An example:
```php
use jlandfried\ReadTime\ReadTime;
use jlandfried\ReadTime\Counter\DecrementCounter;


$markup = '<p>some HTML markup <img src="some-image.png"> with <img src="some-image2.png"> images.</p>';

$config = ['img' => new DecrementCounter()];
$timer = new ReadTime();
$timer->setConfig($config);

$timer->estimateReadTime($markup);
```

ReadTime uses two different types of counters:
* QueryCounter
  * Takes a QueryPath query object, and parses it for a css-style selector (like in the image example above). It then uses logic its own logic to determine how long the average user will spend browsing past all of the instances of the selector. Often this may be more complicated than just saying that a user spends a fixed amount of time on each instance of the selector.
* WordCounter
  * Takes in a plan string and returns a numeric value estimating how long it will take an average person to read the text.

#### Provided Counters ####

##### QueryCounters #####
* `DecrementCounter($initialDuration, $minDuration, $decrementBy)`
 * Loops through however many instances of the provided css3 style selector exist in the markup being estimated, and for each instance it decreases the amount of time the user is expected to spend viewing it. This is explained in [this medium article](https://medium.com/the-story/read-time-and-you-bc2048ab620c). The gist of it is that if there are a lot of images, chances are that users will spend a while looking at some, and browse by others quickly. The more images there are, the more scanning the user does.
 * `DecrementCounter` is configured by default to match Medium's times listed in the article referenced above.

##### WordCounters #####
* `WordCounter($wpm)`
 * Takes a `string` and performs a word count on it. Then divides the number of words by the computed number of words per second that a user is expected to be able to read. 
 * By default, `WordCounter` is configured for users who read ~275 words per minute.
