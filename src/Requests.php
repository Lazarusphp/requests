<?php

namespace LazarusPhp\Requests;

use Exception;
use Psr\Http\Message\ServerRequestInterface;
use LazarusPhp\Requests\InternalServerRequests;

class Requests
{
    private InternalServerRequests $requests;
    private string $method = "";
    private string|null $currentField = null;
    private array $data = [];
    private array $flags = [];

    public function __construct()
    {
        $this->requests = new InternalServerRequests();
        $this->method = $this->requests->getMethod();
        ($this->method === "POST") ? $this->isPost() : $this->isGet();
    }



    // Output Methods

    public function safeField($value, $default = '')
    {

        return htmlspecialchars((string) $this->input($value, $default), ENT_COMPAT, 'UTF-8', false);
    }

    // Validation RuleSet

    public function field(string $field): self
    {
        $this->currentField = null;
        if (!array_key_exists($field, $this->data)) {
            throw new Exception("Field {$field} Does not exist");
        }
        $this->currentField = $field;
        if (!isset($this->flags[$field])) {
            $this->flags[$field] = [];
        }

        return $this;
    }

    public function min(int $min): self
    {
        $this->isValidField();
        if (isset($this->flags[$this->currentField]['min'])) {
            throw new Exception("Minimum rule already applied to field '{$this->currentField}'");
        }

        if (mb_strlen($this->value()) < $min) {
            throw new Exception(
                "Field '{$this->currentField}' must be at least {$min} characters"
            );
        }

        $this->flags[$this->currentField]["min"] = true;
        return $this;
    }

    public function max(int $max): self
    {
        $this->isValidField();
        if (isset($this->flags[$this->currentField]['max'])) {
            throw new Exception("Maximum rule already applied to field '{$this->currentField}'");
        }

        if (mb_strlen($this->value()) > $max) {
            throw new Exception(
                "Field '{$this->currentField}' must not exceed Maximum Value of $max"
            );
        }

        $this->flags[$this->currentField]['max'] = true;

        return $this;
    }

    public function match(string $match): self
    {
        $this->isValidField();
        if (isset($this->flags[$this->currentField]['match'])) {
            throw new Exception("Match rule already applied to field '{$this->currentField}'");
        }

        if (!array_key_exists($match, $this->data)) {
            throw new Exception("Field '{$match}' does not exist");
        }

        if (empty($this->data[$match])) {
            throw new Exception("Field '{$match}' cannot be empty");
        }

        if ($this->value() !== $this->data[$match]) {
            throw new Exception("Field '{$this->currentField}' and confirmed field '{$match}' must match");
        }

        // Mark as applied
        $this->flags[$this->currentField]['match'] = true;

        return $this;
    }
    
    public function required(): self
    {
        $this->isValidField();

        if (isset($this->flags[$this->currentField]['required'])) {
            throw new Exception("Required rule already applied to field '{$this->currentField}'");
        }

        if (trim($this->value()) === '') {
            throw new Exception("Field '{$this->currentField}' is Required");
        }

        $this->flags[$this->currentField]['required'] = true;

        return $this;
    }

    public function post()
    {
        if ($this->method === "POST") {
            return true;
        }
        return false;
    }

    public function get()
    {
        if ($this->method === "GET") {
            return true;
        }
        return false;
    }

    // Private Methods

    private function value(): string
    {
        if ($this->currentField === null) {
            throw new Exception('No field selected');
        }

        return (string) ($this->data[$this->currentField] ?? '');
    }

    private function isValidField(): void
    {
        if ($this->currentField === null) {
            throw new Exception("No field selected for validation");
        }
    }

    private function isPost(): self
    {
        if ($this->method !== "POST") {
            throw new Exception("Request is not a Post Request");
        }

        $this->data = $this->requests->getParsedBody();
        return $this;
    }

    private function isGet(): self
    {
        if ($this->method !== "GET") {
            throw new Exception("This is not a Get Method");
        }

        $this->data = $this->requests->getQueryParams() ?? [];
        return $this;
    }

    private function input(string $key, mixed $default): mixed
    {
        return $this->data[$key] ?? $default;
    }

    // Magic Methods


    public function __get(string $name): string
    {
        if (!array_key_exists($name, $this->data)) {
            throw new Exception("Undefined request field '{$name}'");
        }

        $value = $this->data[$name];
        return $value;
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }
}
