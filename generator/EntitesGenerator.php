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
            $isRequired = \in_array($propertyName, $requiredProperties);

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
                $propertyDescription = $otherEntites[$type['ref']];
                $type = $this->getPhpType($propertyDescription, $namespaceName);
            } elseif (!empty($type['ref'])) {
                $namespace->addUse($type['php']);
            }

            if ($type === null) {
                throw new RuntimeException("Can't recognize '" . ($description['type'] ?? '') . "' type");
            }

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

            if (!empty($propertyDescription['enum'])) {
                foreach ($propertyDescription['enum'] as $value) {
                    $constantName = $this->unifyConstantName($unifiedPropertyName . '_' . $value);
                    $class->addConstant($constantName, $value)->setPublic();
                }
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

            if (!$type['isPrimitive'] && $isRequired) {
                $constructorBody .= "\$this->{$unifiedPropertyName} = new {$type['class']}(\$apiResponse['{$propertyName}'] ?? []);\n";
            } elseif (!$type['isPrimitive']) {
                $constructorBody .= "\$this->{$unifiedPropertyName} = isset(\$apiResponse['{$propertyName}']) ? new {$type['class']}(\$apiResponse['{$propertyName}']) : null;\n";
            } elseif ($isRequired) {
                $constructorBody .= "\$this->{$unifiedPropertyName} = ({$type['php']}) (\$apiResponse['{$propertyName}'] ?? null);\n";
            } else {
                $constructorBody .= "\$this->{$unifiedPropertyName} = isset(\$apiResponse['{$propertyName}']) ? ({$type['php']}) \$apiResponse['{$propertyName}'] : null;\n";
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
