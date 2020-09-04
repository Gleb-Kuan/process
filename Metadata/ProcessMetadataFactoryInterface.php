<?php

namespace Process\Metadata;

/**
 * Interface ProcessMetadataFactoryInterface
 */
interface ProcessMetadataFactoryInterface
{
    public function createMetadataForProcess(string $processName): ProcessMetadata;
}