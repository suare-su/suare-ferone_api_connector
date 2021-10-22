<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Generator;

use InvalidArgumentException;
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

        $class = $namespace->addClass($className);

        $constructorBody = '';
        $requiredProperties = (array) ($description['required'] ?? []);
        $properties = (array) ($description['properties'] ?? []);

        foreach ($properties as $propertyName => $propertyDescription) {
            $unifiedPropertyName = $this->unifyFieldName($propertyName);
            $type = $this->getPhpType($propertyDescription, $namespaceName);

            if ($propertyDescription['type'] === self::TYPE_OBJECT) {
                $nestedObjectName = $className . $this->unifyClassName($propertyName);
                $nestedClasses = $this->createFile($nestedObjectName, $propertyDescription, $namespaceName, $otherEntites);
                $result = array_merge($result, $nestedClasses);
                $type = $this->getPhpType(
                    ['$ref' => '#/components/schemas/' . $nestedObjectName],
                    $namespaceName
                );
            }

            if (!empty($otherEntites[$type['ref']]['enum'])) {
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

            if ($type['isPrimitive']) {
                if ($isRequired) {
                    $constructorBody .= "\$this->{$unifiedPropertyName} = ({$type['php']}) (\$apiResponse['{$propertyName}'] ?? null);\n";
                } else {
                    $constructorBody .= "\$this->{$unifiedPropertyName} = isset(\$apiResponse['{$propertyName}']) ? ({$type['php']}) \$apiResponse['{$propertyName}'] : null;\n";
                }
            } elseif ($propertyDescription['type'] === self::TYPE_ARRAY) {
                $constructorBody .= "\$this->{$unifiedPropertyName} = [];\n";
                $constructorBody .= "foreach ((\$apiResponse['{$propertyName}'] ?? []) as \$tmpItem) {\n";
                if (!empty($type['class'])) {
                    $constructorBody .= "    \$this->{$unifiedPropertyName}[] = new {$type['class']}(is_array(\$tmpItem) ? \$tmpItem : []);\n";
                } elseif (!empty($type['primitive'])) {
                    $constructorBody .= "    \$this->{$unifiedPropertyName}[] = ({$type['primitive']}) \$tmpItem;\n";
                } else {
                    $constructorBody .= "    \$this->{$unifiedPropertyName}[] = \$tmpItem;\n";
                }
                $constructorBody .= "}\n";
            } else {
                if ($isRequired) {
                    $constructorBody .= "\$this->{$unifiedPropertyName} = new {$type['class']}(\$apiResponse['{$propertyName}'] ?? []);\n";
                } else {
                    $constructorBody .= "\$this->{$unifiedPropertyName} = isset(\$apiResponse['{$propertyName}']) ? new {$type['class']}(\$apiResponse['{$propertyName}']) : null;\n";
                }
            }
        }

        $class->addMethod('__construct')
            ->setVisibility('public')
            ->addBody(trim($constructorBody))
            ->addParameter('apiResponse')
            ->setType('array')
        ;

        return $result;
    }
}
