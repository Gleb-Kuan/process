<?php

namespace Process\Util;

use Process\Dumper\DumperInterface;
use Process\Dumper\GraphvizDumper;
use Process\ProcessMetadataBuilder;

/**
 * Class Dumper
 */
class Dumper
{
    const GRAPHVIZ_DUMPER = 'GRAPHVIZ_DUMPER';

    /**
     * Supported dumpers
     * @var array
     */
    protected static $dumpersMap = [
        self::GRAPHVIZ_DUMPER => GraphvizDumper::class,
    ];

    /**
     * @param array $configuration
     * @param string $dumper
     * @return string
     */
    public static function dump(array $configuration, string $dumper = self::GRAPHVIZ_DUMPER) : string
    {
        $processName = $configuration['name'];
        $processMetadataBuilder = new ProcessMetadataBuilder($processName);
        $processMetadata = $processMetadataBuilder->buildFromArray($configuration);
        if(!isset(self::$dumpersMap[$dumper])) {
            throw new \Exception(spintf("Unsupported dumper %s", $dumper));
        }
        /** @var DumperInterface $dumper */
        $dumper = new self::$dumpersMap[$dumper];
        return $dumper->dump($processMetadata);
    }
}