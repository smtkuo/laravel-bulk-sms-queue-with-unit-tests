<?php 

namespace App\Exceptions;

use Exception;

/**
 * Geçersiz argümanlar için özel istisna sınıfı.
 */
class InvalidArgumentException extends Exception
{
    /**
     * InvalidArgumentException constructor.
     * @param string $message Hata mesajı.
     * @param int $code Hata kodu (isteğe bağlı).
     * @param Exception $previous Önceki istisna (isteğe bağlı).
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        // Tüm Exception sınıfı işlevselliğini devral
        parent::__construct($message, $code, $previous);
    }

    /**
     * İstisna mesajını döndürmek için sihirli metod.
     * @return string İstisna mesajı.
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}