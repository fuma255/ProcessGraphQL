<?php

namespace ProcessWire\GraphQL\Field\Traits;

use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\InputField;
use Youshido\GraphQL\Type\Scalar\StringType;
use ProcessWire\GraphQL\Utils;
use ProcessWire\NullPage;
use ProcessWire\FieldtypeDatetime;

trait DatetimeResolverTrait {

  public function build(FieldConfig $config)
  {
    $config->addArgument(new InputField([
      'name' => 'format',
      'type' => new StringType(),
      'description' => 'PHP date formatting string. Refer to https://devdocs.io/php/function.date',
    ]));
  }

  public function resolve($value, array $args, ResolveInfo $info)
  {
    $fieldName = $this->getName();
    
    if (isset($args['format'])) {
      $format = $args['format'];
      $rawValue = $value->$fieldName;
      $field = Utils::fields()->get($fieldName);
      if ($field && $field->type instanceof FieldtypeDatetime) {
        $rawValue = $value->getUnformatted($fieldName);
      }
      if ($rawValue) {
        return date($format, $rawValue);
      } else {
        return "";
      }
    }
    
    return $value->$fieldName;
  }
}