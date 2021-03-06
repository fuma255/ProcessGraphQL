<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class PageArrayFirstFieldTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['city'],
    'legalFields' => ['title'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$firstCity = Utils::pages()->find("template=city")->first();
  	$query = "{
  		city {
  			first {
          name
          id
          title
  			}
  		}
  	}";
  	$res = $this->execute($query);
    $this->assertEquals($firstCity->name, $res->data->city->first->name, 'Retrieves correct name of the first page.');
    $this->assertEquals($firstCity->id, $res->data->city->first->id, 'Retrieves correct id of the first page.');
  	$this->assertEquals($firstCity->title, $res->data->city->first->title, 'Retrieves correct title of the first page.');
  }

}