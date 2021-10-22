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
            $type = $entity['type'] ?? '';
            if ($type !== 'object' || !empty($this->allowedEntites) && !\in_array($name, $this->allowedEntites)) {
                continue;
            }
            $fileName = $this->unifyClassName($name);
            $files[$fileName] = $this->createFile($fileName, $entity, $namespace, $entites);
        }

        return $files;
    }

    private function createFile(string $className, array $description, string $namespaceName, array $otherEntites): PhpFile
    {
        $phpFile = new PhpFile();
        $phpFile->setStrictTypes();

        $namespace = $phpFile->addNamespace($namespaceName);
        $namespace->addUse(InvalidArgumentException::class);

        $class = $namespace->addClass($className);

        $constructorBody = '';
        $requiredProperties = (array) ($description['required'] ?? []);
        $properties = (array) ($description['properties'] ?? []);
        foreach ($properties as $propertyName => $propertyDescription) {
            $unifiedPropertyName = $this->unifyFieldName($propertyName);
            $phpPropertyType = $this->getPhpType($propertyDescription, $namespaceName);
            $isRequired = \in_array($propertyName, $requiredProperties);
            if ($phpPropertyType === null) {
                throw new RuntimeException("Can't recognize '" . ($description['type'] ?? '') . "' type");
            }

            $property = $class->addProperty($unifiedPropertyName)
                ->setType($phpPropertyType)
                ->setVisibility('private')
            ;
            if (!$isRequired) {
                $property->setNullable();
            }
            if (!empty($propertyDescription['description'])) {
                $property->addComment($propertyDescription['description']);
            }

            $getterName = $this->unifyGetterName($unifiedPropertyName);
            $getter = $class->addMethod($getterName)
                ->setVisibility('public')
                ->setReturnType($phpPropertyType)
                ->addBody("return \$this->{$unifiedPropertyName};")
            ;
            if (!$isRequired) {
                $getter->setReturnNullable();
            }

            if ($isRequired) {
                $constructorBody .= "\$this->{$unifiedPropertyName} = ({$phpPropertyType}) (\$apiResponse['{$propertyName}'] ?? null);\n";
            } else {
                $constructorBody .= "\$this->{$unifiedPropertyName} = isset(\$apiResponse['{$propertyName}']) ? ({$phpPropertyType}) \$apiResponse['{$propertyName}'] : null;\n";
            }
        }

        $constructor = $class->addMethod('__construct')
            ->setVisibility('public')
            ->addBody(trim($constructorBody))
        ;
        $constructor->addParameter('apiResponse')->setType('array');

        return $phpFile;
    }
}
