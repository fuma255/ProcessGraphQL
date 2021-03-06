<?php

namespace ProcessWire\GraphQL\Field\Page\Fieldtype;

use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Scalar\IntType;
use ProcessWire\Template;
use ProcessWire\FieldtypePage as PWFieldtypePage;
use ProcessWire\GraphQL\Type\Object\PageArrayType;
use ProcessWire\GraphQL\Type\Object\TemplatedPageArrayType;
use ProcessWire\GraphQL\Field\Page\Fieldtype\AbstractFieldtype;
use ProcessWire\GraphQL\Type\Scalar\SelectorType;
use ProcessWire\Page;

class FieldtypePage extends AbstractFieldtype {

  public function getDefaultType()
  {
    // if template is chosen for the FieldtypePage
    // then we resolve to TemplatedPageArrayType
    if ($this->field->template_id) {
      $template = \ProcessWire\wire('templates')->get($this->field->template_id);
      if ($template instanceof Template) return new TemplatedPageArrayType($template);
    }
      
    return new PageArrayType();   
  }

  public function getInputfieldType($type = null)
  {
    return parent::getInputfieldType(new ListType(new IntType()));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $defaultSelector = new SelectorType();
    $fieldName = $this->field->name;
    $field = \ProcessWire\wire('fields')->get($fieldName);
    $field->derefAsPage = PWFieldtypePage::derefAsPageArray;
    return $value->$fieldName->find($defaultSelector->serialize(""));
  }

  public function setValue(Page $page, $value)
  {
    $fieldName = $this->field->name;
    $page->$fieldName = implode('|', $value);
  }

}