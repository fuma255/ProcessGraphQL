<?php

/**
 * A page cannot be updated if it's template is not
 * legal
 */

namespace ProcessWire\GraphQL\Test\Field\Mutation\UpdatePage;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;
use \ProcessWire\GraphQL\Test\Field\Page\Traits\AccessTrait;

class UpdatePageCaseOneTest extends GraphQLTestCase {

  const accessRules = [
    'legalTemplates' => ['city'],
    'legalFields' => ['featured', 'height', 'floors', 'body'],
  ];

  use AccessTrait;
	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = 'mutation updatePage ($id: Int!, $page: SkyscraperUpdateInput!) {
  		updateSkyscraper (id: $id, page: $page) {
  			title
  		}
  	}';
  	$variables = [
  		"page" => [
				"title" => "Old Building Sky"
  		],
      "id" => $skyscraper->id
  	];
  	$res = $this->execute($query, json_encode($variables));
    $this->assertEquals(1, count($res->errors), 'updateSkyscraper is not available if `skyscraper` template is not legal.');
  	$this->assertTrue($skyscraper->title !== $variables['page']['title'], 'updateSkyscraper does not update the `title`.');
  }

}