<?php

namespace ProcessWire\GraphQL\Type\Input\TemplatedPage;

use Youshido\GraphQL\Type\InputObject\AbstractInputObjectType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;;
use ProcessWire\Template;
use ProcessWire\GraphQL\Utils;

class UpdateInputType extends AbstractInputObjectType {

  protected $template;

  /**
   * Construct the TemplatedPageInputType.
   * @param Template $template The ProcessWire template that is used to generate
   * this's type's fields.
   */
  public function __construct(Template $template)
  {
    $this->template = $template;
    parent::__construct([]);
  }

  public Static function normalizeName($name)
  {
    return str_replace('-', '_', $name);
  }

  public function getName()
  {
    return ucfirst(self::normalizeName($this->template->name)) . 'UpdateInput';
  }

  public function getDescription()
  {
    return "UpdateInputType for pages with template {$this->template->name}.";
  }

  public function build($config)
  {
    // parent
    $config->addField('parent', [
      'type' => new StringType(),
      'description' => 'Id or the path of the parent page.',
    ]);

    // name
    $config->addField('name', [
      'type' => new StringType(),
      'description' => 'ProcessWire page name.',
    ]);
    
    // the list of input fields we do not 
    // for now
    $unsupportedFieldtypes = [
      'FieldtypeFile',
      'FieldtypeImage',
    ];

    $legalFieldsName = Utils::moduleConfig()->legalFields->implode('|', 'name');
    foreach ($this->template->fields->find("name=$legalFieldsName") as $field) {

      // get the field's GraphQL input class
      $className = $field->type->className();
      if (in_array($className, $unsupportedFieldtypes)) {
        continue;
      }


      // all fields are optional for update operation
      // we set the required property of the field
      // to null, only to set it back after we use it.
      $required = $field->required;
      $field->required = null;

      $f = Utils::pwFieldToGraphQlField($field);
      if (!is_null($f)) {
        $config->addField($f->getName(), [
          'type' => $f->getInputfieldType(),
          'description' => $f->getDescription(),
        ]);
      }

      $field->required = $required;
    }
  }

}
