<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Type\Scalar\StringType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;

class FieldtypeEmail extends AbstractFieldtype {

  public function getDefaultType()
  {
    return new StringType();
  }

}