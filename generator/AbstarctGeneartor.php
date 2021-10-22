<?php

declare(strict_types=1);

namespace SuareSu\FeroneApiConnector\Generator;

use Marvin255\FileSystemHelper\FileSystemHelperInterface;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;
use SplFileInfo;

/**
 * Abstarct class for generators.
 */
abstract class AbstarctGeneartor
{
    public const TYPE_OBJECT = 'object';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_STRING = 'string';
    public const PHP_TYPE_MAP = [
        self::TYPE_INTEGER => 'int',
        self::TYPE_STRING => 'string',
    ];

    protected FileSystemHelperInterface $fs;

    protected ?array $allowedEntites;

    public function __construct(FileSystemHelperInterface $fs, ?array $allowedEntites = null)
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

    protected function unifyNamespace(string $namespace): string
    {
        return trim($namespace, " \t\n\r\0\x0B\\");
    }

    protected function getPhpType(array $description, string $namespace): ?string
    {
        $type = $description['type'] ?? '';

        return self::PHP_TYPE_MAP[$type] ?? null;
    }
}
