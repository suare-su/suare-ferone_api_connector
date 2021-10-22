<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Generator;

use Marvin255\FileSystemHelper\FileSystemHelperInterface;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;
use RuntimeException;
use SplFileInfo;

/**
 * Abstarct class for generators.
 */
abstract class AbstarctGeneartor
{
    public const TYPE_OBJECT = 'object';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_STRING = 'string';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_NUMBER = 'number';
    public const TYPE_ARRAY = 'array';
    public const PHP_TYPE_MAP = [
        self::TYPE_INTEGER => 'int',
        self::TYPE_STRING => 'string',
        self::TYPE_BOOLEAN => 'bool',
        self::TYPE_NUMBER => 'float',
        self::TYPE_ARRAY => 'array',
    ];

    protected FileSystemHelperInterface $fs;

    protected array $allowedEntites;

    public function __construct(FileSystemHelperInterface $fs, array $allowedEntites = [])
    {
        $this->fs = $fs;
        $this->allowedEntites = $allowedEntites;
    }

    /**
     * @return array<string, PhpFile>
     */
    abstract protected function createFiles(array $entites, string $namespace): array;

    public function generate(array $entites, SplFileInfo $destFolder, string $namespace): void
    {
        $files = $this->createFiles($entites, $this->unifyNamespace($namespace));

        $this->fs->mkdirIfNotExist($destFolder);
        $this->fs->emptyDir($destFolder);

        $printer = new PsrPrinter();
        foreach ($files as $fileName => $file) {
            $path = $destFolder->getRealPath() . "/{$fileName}.php";
            $content = $printer->printFile($file);
            file_put_contents($path, $content);
        }
    }

    protected function unifyClassName(string $className): string
    {
        $name = strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $className));
        $arName = array_map('ucfirst', explode('_', $name));

        return implode('', $arName);
    }

    protected function unifyFieldName(string $fieldName): string
    {
        return lcfirst($this->unifyClassName($fieldName));
    }

    protected function unifyGetterName(string $fieldName): string
    {
        return 'get' . $this->unifyClassName($fieldName);
    }

    protected function unifySetterName(string $fieldName): string
    {
        return 'set' . $this->unifyClassName($fieldName);
    }

    protected function unifyConstantName(string $constantName): string
    {
        return strtoupper(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $constantName));
    }

    protected function unifyNamespace(string $namespace): string
    {
        return trim($namespace, " \t\n\r\0\x0B\\");
    }

    protected function getPhpType(array $description, string $namespace): ?array
    {
        $type = $description['type'] ?? null;

        if ($type === self::TYPE_ARRAY) {
            $return = [
                'isPrimitive' => false,
                'php' => 'array',
            ];
            if (!empty($description['items']['$ref']) && preg_match('#.*/([^/]+)$#', $description['items']['$ref'], $matches)) {
                $fqcn = $this->unifyNamespace($namespace) . '\\' . $this->unifyClassName($matches[1]);
                $cn = $this->unifyClassName($matches[1]);
                $return['phpDoc'] = "{$cn}[]";
                $return['ref'] = $matches[1];
                $return['class'] = $cn;
                $return['use'] = $fqcn;
            } elseif (isset(self::PHP_TYPE_MAP[$description['items']['type']])) {
                $return['phpDoc'] = self::PHP_TYPE_MAP[$description['items']['type']] . '[]';
                $return['primitive'] = self::PHP_TYPE_MAP[$description['items']['type']];
            } else {
                throw new RuntimeException("Can't recognize array type");
            }

            return $return;
        } elseif (!empty($description['$ref']) && preg_match('#.*/([^/]+)$#', $description['$ref'], $matches)) {
            $fqcn = $this->unifyNamespace($namespace) . '\\' . $this->unifyClassName($matches[1]);
            $cn = $this->unifyClassName($matches[1]);

            return [
                'isPrimitive' => false,
                'php' => $fqcn,
                'use' => $fqcn,
                'class' => $cn,
                'ref' => $matches[1],
            ];
        } elseif (isset(self::PHP_TYPE_MAP[$type])) {
            return [
                'isPrimitive' => true,
                'php' => self::PHP_TYPE_MAP[$type],
            ];
        }

        return null;
    }
}
