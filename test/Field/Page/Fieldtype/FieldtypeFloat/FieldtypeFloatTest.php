<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Test\GraphqlTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;
use \ProcessWire\GraphQL\Utils;

class FieldtypeFloatTest extends GraphqlTestCase {  

  const accessRules = [
    'legalTemplates' => ['skyscraper'],
    'legalFields' => ['height'],
  ];
  const FIELD_NAME = 'height';
  const FIELD_TYPE = 'FieldtypeFloat';

  use FieldtypeTestTrait;
  use AccessTrait;

  public function testValue()
  {
    $skyscraper = Utils::pages()->find("template=skyscraper, sort=random")->first();
    $query = "{
      skyscraper(s: \"id=$skyscraper->id\") {
        list {
          height
        }
      }
    }";
    $res = $this->execute($query);
    $this->assertEquals($skyscraper->height, $res->data->skyscraper->list[0]->height, 'Retrieves field value.');
  }

}