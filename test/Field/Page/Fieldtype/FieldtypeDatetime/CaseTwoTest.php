<?php

namespace ProcessWire\GraphQL\Test\Field\Page;

/**
 * It returns correct output when custom dateOutputFormat
 * is set.
 */

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Fieldtype\Traits\FieldtypeTestTrait;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class FieldtypeDatetimeCaseTwoTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['architect'],
    'legalFields' => ['born'],
  ];
  const FIELD_NAME = 'born';
  const FIELD_TYPE = 'FieldtypeDatetime';

  use FieldtypeTestTrait;
  use AccessTrait;

  public function testValue()
  {
    // set output format for born (Datetime) field
    Utils::fields()->get('born')->dateOutputFormat = 'j F Y H:i:s';

    $architect = Utils::pages()->get("template=architect");
    $query = "{
      architect(s: \"id=$architect->id\") {
        list {
          born
        }
      }
    }";
    $res = $this->execute($query);

    $this->assertTrue($architect->outputFormatting(), 'Output formatting is on.');
    $this->assertEquals(
      $architect->born,
      $res->data->architect->list[0]->born,
      'Retrieves correctly formatted datetime value.'
    );
  }

}