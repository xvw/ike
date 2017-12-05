<?php
declare (strict_types=1);

namespace ike;

/**
 * Define an Application
 */
class Application {

  protected $context;

  public function __construct() {
    $this->context = new Context();
  }

}
