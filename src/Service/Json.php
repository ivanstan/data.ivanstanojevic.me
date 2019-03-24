<?php /** @noinspection PhpAssignmentInConditionInspection */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class Json
{
    public function decode(string $file): array
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }

        $result = json_decode(file_get_contents($file), true);

        if ($result === null && $error = json_last_error() !== JSON_ERROR_NONE) {
            switch ($error) {
                case JSON_ERROR_DEPTH:
                    $message = 'Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $message = 'Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $message = 'Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $message = 'Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $message = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    $message = 'Unknown';
            }

            throw new \RuntimeException(
                \sprintf('JSON decode error \'%s\' occurred during parsing file: %s', $message, $file)
            );
        }

        return $result;
    }
}
