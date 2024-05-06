<?php

namespace App\Helpers\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class EnvironmentPathGenerator implements PathGenerator
{
    protected $path;

    public function __construct()
    {
        $this->path = app()->env;
    }

    public function getPath(Media $media): string
    {
        return $this->path . $media->id . "/";
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . "conversions/";
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . "responsive/";
    }
}
