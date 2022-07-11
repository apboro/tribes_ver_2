<?php

namespace App\Exceptions;

trait PrettyArrayToString
{
    protected function arrayToPrettyString(array $data): string
    {
        $text = "";
        foreach ($data as $key => $entity) {
            if(is_string($entity)){
                $text .= $key." : ".$entity .";". PHP_EOL;
            } elseif (is_array($entity)) {
                $this->arrayToPrettyString($entity);
            }

        }
        return $text;
    }

    protected function getExceptionTraceAsString(\Throwable $e) {
        $rtn = "";
        $count = 0;
        foreach ($e->getTrace() as $frame) {
            $args = "";
            if (isset($frame['args'])) {
                $args = array();
                foreach ($frame['args'] as $arg) {
                    if (is_string($arg)) {
                        $args[] = "'" . $arg . "'";
                    } elseif (is_array($arg)) {
                        $args[] = "Array";
                    } elseif (is_null($arg)) {
                        $args[] = 'NULL';
                    } elseif (is_bool($arg)) {
                        $args[] = ($arg) ? "true" : "false";
                    } elseif (is_object($arg)) {
                        $args[] = get_class($arg);
                    } elseif (is_resource($arg)) {
                        $args[] = get_resource_type($arg);
                    } else {
                        $args[] = $arg;
                    }
                }
                $args = join(", ", $args);
            }
            $rtn .= sprintf(
                "#%s %s(%s): %s%s%s(%s)\n",
                $count,
                $frame['file'] ?? '',
                $frame['line'] ?? '',
                isset($frame['class']) ? $frame['class'] : '',
                isset($frame['type']) ? $frame['type'] : '', // "->" or "::"
                $frame['function'] ?? '',
                $args
            );
            $count++;
        }
        return $rtn;
    }
}