<?php

namespace PhpSchool\Terminal;

use function in_array;

/**
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class InputCharacter
{
    /**
     * @var string
     */
    private $data;

    public const UP = 'UP';
    public const DOWN = 'DOWN';
    public const RIGHT = 'RIGHT';
    public const LEFT = 'LEFT';
    public const CTRLA = 'CTRLA';
    public const CTRLB = 'CTRLB';
    public const CTRLE = 'CTRLE';
    public const CTRLF = 'CTRLF';
    public const BACKSPACE = 'BACKSPACE';
    public const CTRLW = 'CTRLW';
    public const ENTER = 'ENTER';
    public const TAB = 'TAB';

    private static $controls = [
        "\033[A" => self::UP,
        "\033[B" => self::DOWN,
        "\033[C" => self::RIGHT,
        "\033[D" => self::LEFT,
        "\001"   => self::CTRLA,
        "\002"   => self::CTRLB,
        "\005"   => self::CTRLE,
        "\006"   => self::CTRLF,
        "\010"   => self::BACKSPACE,
        "\177"   => self::BACKSPACE,
        "\027"   => self::CTRLW,
        "\n"     => self::ENTER,
        "\t"     => self::TAB,
    ];

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function isControl() : bool
    {
        return isset(static::$controls[$this->data]);
    }

    public function isNotControl() : bool
    {
        return ! $this->isControl();
    }

    public function get() : string
    {
        return $this->data;
    }

    public function getControl() : string
    {
        if (!isset(static::$controls[$this->data])) {
            throw new \RuntimeException(sprintf('Character "%s" is not a control', $this->data));
        }

        return static::$controls[$this->data];
    }

    public function __toString() : string
    {
        return $this->get();
    }

    public static function controlExists(string $controlName) : bool
    {
        return in_array($controlName, static::$controls, true);
    }

    public static function getControls() : array
    {
        return array_values(array_unique(static::$controls));
    }

    public static function fromControlName(string $controlName) : self
    {
        if (!in_array($controlName, static::$controls, true)) {
            throw new \InvalidArgumentException(sprintf('Control "%s" does not exist', $controlName));
        }

        return new static(array_search($controlName, static::$controls, true));
    }
}
