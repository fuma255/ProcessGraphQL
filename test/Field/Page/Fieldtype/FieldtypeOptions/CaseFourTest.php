<?php

/**
 * You can pass a plain string for options field that
 * stores single option
 */

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype\FieldtypeOptions;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;
use ProcessWire\NullPage;

class FieldtypeOptionsCaseFourTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['architects', 'architect'],
    'legalFields' => ['options', 'title'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
    $name = "new-architect";
    $title = "New Architect";
    $option = ['Mon', 'Thu', 'Sat'];
    $parent = "4111";
  	$query = 'mutation createPage ($page: ArchitectCreateInput!) {
  		createArchitect (page: $page) {
  			name
  			id
        title
        options {
          title
          value
          id
        }
  		}
  	}';
  	$variables = [
  		"page" => [
  			"parent" => $parent,
				"name" => $name,
				"title" => $title,
        "options" => $option,
  		]
  	];
  	$res = $this->execute($query, json_encode($variables));
    $newArchitect = Utils::pages()->get("template=architect, name=$name");
    $this->assertTrue(!$newArchitect instanceof NullPage, 'New Page is created.');
    $this->assertEquals($name, $newArchitect->name, 'New Page has correct name.');
    $this->assertEquals($title, $newArchitect->title, 'New Page has correct title.');
    $this->assertEquals('Mon', $newArchitect->options->eq(0)->title, 'New Page has correct option title at 0.');
    $this->assertEquals('Thursday', $newArchitect->options->eq(1)->value, 'New Page has correct option value at 1.');
    $this->assertEquals('6', $newArchitect->options->eq(2)->id, 'New Page has correct option id at 2.');
  }

}