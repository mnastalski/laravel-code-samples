<?php

namespace App\Services\FileUpload;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use LogicException;

class PublicDiskManager
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $folder;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $storage;

    /**
     * @param FilesystemManager $storageFactory
     */
    public function __construct(
        FilesystemManager $storageFactory
    ) {
        $this->storage = $storageFactory->disk('public');
    }

    /**
     * @param UploadedFile $file
     * @return string
     * @throws \Exception
     */
    public function store(UploadedFile $file): string
    {
        return $this->storeFile($file, $this->fullPath(), $this->name());
    }

    /**
     * @param UploadedFile[] $files
     * @return string[]
     * @throws \Exception
     */
    public function storeMultiple(array $files): array
    {
        $paths = [];

        foreach ($files as $key => $file) {
            $paths[$key] = $this->store($file);
        }

        return $paths;
    }

    /**
     * Delete files by provided path(s).
     *
     * @param string|array $paths
     * @return bool
     */
    public function delete($paths): bool
    {
        return $this->storage->delete(
            Arr::wrap($paths)
        );
    }

    /**
     * Delete directory at provided path.
     *
     * @param string $path
     * @return bool
     */
    public function deleteDirectory(string $path): bool
    {
        return $this->storage->deleteDirectory($path);
    }

    /**
     * @param string $path
     * @return bool
     * @throws \Exception
     */
    public function deleteFolder(string $path): bool
    {
        return $this->deleteDirectory(
            "{$this->getPath()}/{$path}"
        );
    }

    /**
     * @param string $path
     * @return \App\Services\FileUpload\PublicDiskManager
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        if (!$this->path) {
            throw new LogicException("The \$path parameter must be specified.");
        }

        return $this->path;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function fullPath(): string
    {
        return "{$this->getPath()}/{$this->folder()}";
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function folder(): string
    {
        return $this->folder ?: Str::uuid()->toString();
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function name(): string
    {
        return $this->name ?: Str::uuid()->toString();
    }

    /**
     * Upload a file to storage.
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string $name
     * @return string
     */
    protected function storeFile(UploadedFile $file, string $path, string $name): string
    {
        $extension = $file->getClientOriginalExtension();
        $fullName = "{$name}.{$extension}";

        $this->storage->putFileAs($path, $file, $fullName);

        return "{$path}/{$fullName}";
    }
}
