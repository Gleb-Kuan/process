<?php

namespace Process\Dumper;

use Process\Metadata\ProcessMetadata;

class GraphvizDumper implements DumperInterface
{
    /**
     * @param ProcessMetadata $processMetadata
     * @return string
     */
    public function dump(ProcessMetadata $processMetadata)
    {
        $graphDescription = '';
        foreach ($processMetadata->getFlows() as $flow) {
            $graphDescription .= sprintf("  %s -> %s;\n", $flow[0], $flow[1]);
        }
        return sprintf("digraph process_%s {\n%s\n}", $processMetadata->getProcessName(), $graphDescription);
    }
}