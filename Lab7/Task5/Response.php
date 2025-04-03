<?php
/**
 * Клас Response для керування HTTP-заголовками та буферизацією
 */
class Response {
    /**
     * Зберігає HTTP-статус код
     * @var int
     */
    private $statusCode = 200;
    
    /**
     * Зберігає масив HTTP-заголовків
     * @var array
     */
    private $headers = [];
    
    /**
     * Встановлює HTTP-статус код
     * 
     * @param int $code HTTP-статус код
     * @return Response Поточний об'єкт (для ланцюжкових викликів)
     */
    public function setStatus($code) {
        $this->statusCode = (int)$code;
        return $this;
    }
    
    /**
     * Додає HTTP-заголовок
     * 
     * @param string $header HTTP-заголовок
     * @return Response Поточний об'єкт (для ланцюжкових викликів)
     */
    public function addHeader($header) {
        $this->headers[] = $header;
        return $this;
    }
    
    /**
     * Відправляє відповідь з заголовками та контентом
     * 
     * @param string $content Контент для відправки
     */
    public function send($content) {
        // Запускаємо буферизацію, якщо вона ще не запущена
        if (ob_get_level() == 0) {
            ob_start();
        }
        
        // Очищаємо буфер виводу
        ob_clean();
        
        // Встановлюємо HTTP-статус код
        http_response_code($this->statusCode);
        
        // Додаємо всі заголовки
        foreach ($this->headers as $header) {
            header($header);
        }
        
        // Виводимо контент
        echo $content;
        
        // Відправляємо буфер клієнту
        ob_end_flush();
    }
}