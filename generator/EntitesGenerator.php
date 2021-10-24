<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Generator;

use InvalidArgumentException;
use JsonSerializable;
use Nette\PhpGenerator\PhpFile;
use RuntimeException;

/**
 * Object that can create php classes by schemas from swagger.
 */
class EntitesGenerator extends AbstarctGeneartor
{
    /**
     * @return array<string, PhpFile>
     */
    protected function createFiles(array $entites, string $namespace): array
    {
        $files = [];

        foreach ($entites as $name => $entity) {
            if (!\in_array($name, $this->allowedEntites)) {
                continue;
            }
            $tempFiles = $this->createFile($name, $entity, $namespace, $entites);
            $files = array_merge($files, $tempFiles);
        }

        return $files;
    }

    /**
     * @return PhpFile[]
     */
    private function createFile(string $className, array $description, string $namespaceName, array $otherEntites): array
    {
        $result = [];

        $className = $this->unifyClassName($className);

        $result[$className] = $phpFile = new PhpFile();
        $phpFile->setStrictTypes();

        $namespace = $phpFile->addNamespace($namespaceName);
        $namespace->addUse(InvalidArgumentException::class);
        $namespace->addUse(JsonSerializable::class);

        $class = $namespace->addClass($className)->addImplement(JsonSerializable::class);

        $constructorBody = "\$apiResponse = array_change_key_case(\$apiResponse, CASE_LOWER);\n\n";
        $jsonSerializeBody = '';
        $requiredProperties = (array) ($description['required'] ?? []);
        $properties = (array) ($description['properties'] ?? []);
        $allowSetters = \in_array($className, $this->allowedSetters);

        foreach ($properties as $propertyName => $propertyDescription) {
            if ($propertyDescription['type'] === self::TYPE_OBJECT) {
                $nestedObjectName = $className . $this->unifyClassName($propertyName);
                $nestedClasses = $this->createFile($nestedObjectName, $propertyDescription, $namespaceName, $otherEntites);
                $result = array_merge($result, $nestedClasses);
                $type = $this->getPhpType(
                    ['$ref' => '#/components/schemas/' . $nestedObjectName],
                    $namespaceName
                );
            } elseif ($propertyDescription['type'] === self::TYPE_ARRAY && $propertyDescription['items']['type'] === self::TYPE_OBJECT) {
                $nestedObjectName = $className . $this->unifyClassName($propertyName);
                $nestedClasses = $this->createFile($nestedObjectName, $propertyDescription['items'], $namespaceName, $otherEntites);
                $result = array_merge($result, $nestedClasses);
                $type = $this->getPhpType(
                    [
                        'type' => self::TYPE_ARRAY,
                        'items' => [
                            '$ref' => '#/components/schemas/' . $nestedObjectName,
                        ],
                    ],
                    $namespaceName
                );
            } else {
                $type = $this->getPhpType($propertyDescription, $namespaceName);
            }

            if (
                !empty($otherEntites[$type['ref']])
                && $otherEntites[$type['ref']]['type'] !== self::TYPE_OBJECT
                && $otherEntites[$type['ref']]['type'] !== self::TYPE_ARRAY
            ) {
                // php7 doesn't have enums so suppose it's just a string
                $propertyDescription = $otherEntites[$type['ref']];
                $type = $this->getPhpType($propertyDescription, $namespaceName);
            }

            if (!empty($type['use'])) {
                $namespace->addUse($type['use']);
            }

            if ($type === null) {
                throw new RuntimeException("Can't recognize '" . ($propertyDescription['type'] ?? '') . "' type");
            }

            $unifiedPropertyName = $this->unifyFieldName($propertyName);
            $isRequired = \in_array($propertyName, $requiredProperties) || $propertyDescription['type'] === self::TYPE_ARRAY;

            $property = $class->addProperty($unifiedPropertyName)
                ->setType($type['php'])
                ->setVisibility('private')
            ;
            if (!$isRequired) {
                $property->setNullable();
            }
            if (!empty($propertyDescription['description'])) {
                $property->addComment($propertyDescription['description']);
            }
            if (!empty($type['phpDoc'])) {
                $property->addComment("\n@var {$type['phpDoc']}");
            }

            $getterName = $this->unifyGetterName($unifiedPropertyName);
            $getter = $class->addMethod($getterName)
                ->setVisibility('public')
                ->setReturnType($type['php'])
                ->addBody("return \$this->{$unifiedPropertyName};")
            ;
            if (!$isRequired) {
                $getter->setReturnNullable();
            }
            if (!empty($type['phpDoc'])) {
                $getter->addComment("@return {$type['phpDoc']}");
            }

            if ($allowSetters) {
                $setterName = $this->unifySetterName($unifiedPropertyName);
                $setter = $class->addMethod($setterName)
                    ->setVisibility('public')
                    ->setReturnType('self')
                    ->addBody("\$this->{$unifiedPropertyName} = \$value;\n\nreturn \$this;")
                ;
                $param = $setter->addParameter('value')->setType($type['php']);
                if (!$isRequired) {
                    $param->setNullable();
                }
                if (!empty($type['phpDoc'])) {
                    $setter->addComment("@param {$type['phpDoc']} \$value");
                }
            }

            $lcName = strtolower($propertyName);
            if ($type['isPrimitive']) {
                $jsonSerializeBody .= "    \"{$propertyName}\" => \$this->{$unifiedPropertyName},\n";
                if ($isRequired) {
                    $constructorBody .= "\$this->{$unifiedPropertyName} = " . ($type['php'] ? "({$type['php']})" : '') . " (\$apiResponse['{$lcName}'] ?? null);\n";
                } else {
                    $constructorBody .= "\$this->{$unifiedPropertyName} = isset(\$apiResponse['{$lcName}']) ? " . ($type['php'] ? "({$type['php']})" : '') . " \$apiResponse['{$lcName}'] : null;\n";
                }
            } elseif ($propertyDescription['type'] === self::TYPE_ARRAY) {
                $constructorBody .= "\$this->{$unifiedPropertyName} = [];\n";
                $constructorBody .= "foreach ((\$apiResponse['{$lcName}'] ?? []) as \$tmpItem) {\n";
                if (!empty($type['class'])) {
                    $jsonSerializeBody .= "    \"{$propertyName}\" => array_map(fn ({$type['class']} \$item): array => \$item->jsonSerialize(), \$this->{$unifiedPropertyName}),\n";
                    $constructorBody .= "    \$this->{$unifiedPropertyName}[] = new {$type['class']}(is_array(\$tmpItem) ? \$tmpItem : []);\n";
                } elseif (!empty($type['primitive'])) {
                    $jsonSerializeBody .= "    \"{$propertyName}\" => \$this->{$unifiedPropertyName},\n";
                    $constructorBody .= "    \$this->{$unifiedPropertyName}[] = ({$type['primitive']}) \$tmpItem;\n";
                } else {
                    $jsonSerializeBody .= "    \"{$propertyName}\" => \$this->{$unifiedPropertyName},\n";
                    $constructorBody .= "    \$this->{$unifiedPropertyName}[] = \$tmpItem;\n";
                }
                $constructorBody .= "}\n";
            } else {
                if ($isRequired) {
                    $jsonSerializeBody .= "    \"{$propertyName}\" => \$this->{$unifiedPropertyName}->jsonSerialize(),\n";
                    $constructorBody .= "\$this->{$unifiedPropertyName} = new {$type['class']}(\$apiResponse['{$lcName}'] ?? []);\n";
                } else {
                    $jsonSerializeBody .= "    \"{$propertyName}\" => \$this->{$unifiedPropertyName} ? \$this->{$unifiedPropertyName}->jsonSerialize() : null,\n";
                    $constructorBody .= "\$this->{$unifiedPropertyName} = isset(\$apiResponse['{$lcName}']) ? new {$type['class']}(\$apiResponse['{$lcName}']) : null;\n";
                }
            }

            $enums = $propertyDescription['enum'] ?? [];
            foreach ($enums as $enum) {
                $name = $this->unifyConstantName($unifiedPropertyName . '_' . $this->transliterate($enum));
                $class->addConstant($name, $enum)->setPublic();
            }
        }

        $constructor = $class->addMethod('__construct')->setVisibility('public')->addBody(trim($constructorBody));
        $constructParam = $constructor->addParameter('apiResponse')->setType('array');
        if ($allowSetters) {
            $constructParam->setDefaultValue([]);
        }

        $class->addMethod('jsonSerialize')
            ->setVisibility('public')
            ->addBody($jsonSerializeBody ? "return [\n" . trim($jsonSerializeBody) . "\n];" : 'return [];')
            ->setReturnType('array')
        ;

        return $result;
    }
}
