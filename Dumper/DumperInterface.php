<?php

namespace Process\Dumper;

use Process\Metadata\ProcessMetadata;

interface DumperInterface
{
    /**
     * @param ProcessMetadata $processMetadata
     */
    public function dump(ProcessMetadata $processMetadata);
}