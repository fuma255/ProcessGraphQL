<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class FieldtypeOptionsCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['architect'],
    'legalFields' => ['options'],
  ];

  use AccessTrait;
  
  public function testValue()
  {
    $architect = Utils::pages()->get("template=architect, options!=''");
    $query = "{
      architect (s: \"id=$architect->id\") {
        list {
          options {
            title
            value
            id
          }
        }
      }
    }";
    $res = $this->execute($query);
    $this->assertEquals($architect->options->eq(0)->title, $res->data->architect->list[0]->options[0]->title, 'Retrieves correct option title at 0.');
    $this->assertEquals($architect->options->eq(1)->value, $res->data->architect->list[0]->options[1]->value, 'Retrieves correct option value at 1.');
    $this->assertEquals($architect->options->eq(2)->id, $res->data->architect->list[0]->options[2]->id, 'Retrieves correct option id at 2.');
  }

}