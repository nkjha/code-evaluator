<?php

namespace App\Services;

class CodeExecutorService
{
    /**
     * Execute code and return output
     */
    public static function execute(string $language, string $code, string $input = ''): string
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'code_');
        $tmpInput = tempnam(sys_get_temp_dir(), 'input_');
        
        try {
            // Write code to temporary file
            file_put_contents($tmpFile, $code);
            file_put_contents($tmpInput, $input);

            $output = '';
            $command = '';

            if ($language === 'python') {
                $command = "python3 {$tmpFile} < {$tmpInput} 2>&1";
            } elseif ($language === 'php') {
                $command = "php {$tmpFile} < {$tmpInput} 2>&1";
            } elseif ($language === 'javascript') {
                $command = "node {$tmpFile} < {$tmpInput} 2>&1";
            } else {
                return "Unsupported language: {$language}";
            }

            // Execute code with timeout
            $output = shell_exec("timeout 10 {$command}");
            
            return $output ?? '';
        } finally {
            @unlink($tmpFile);
            @unlink($tmpInput);
        }
    }
}
