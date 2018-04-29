<?php

namespace Sketch\Tpl;

/**
 * Class VariableTag
 * @package Sketch\Tpl
 */
class VariableTag extends Tag
{
    /**
     * @var string
     */
    private $pattern = '/{\s?([\w]+.?[\w]+.?[\w]+)\s?\|?\s?([\w]+)?\s?}/is';
    /**
     * @var array
     */
    private $match   = [];
    /**
     * @var array
     */
    private $matches = [];
    /**
     * @var string
     */
    private $replace = '';
    /**
     * @var string
     */
    private $variable = '';

    public function handle(): void
    {
        if (preg_match_all($this->pattern, self::$content, $this->matches, PREG_SET_ORDER)) {
            for ($i = 0; $i < count($this->matches); $i++) {
                $this->match = $this->matches[$i];
                $this->getVariable();
                $this->replace = '<?php echo('.$this->variable.'); ?>';
                $this->filter('upper');
                self::$content = str_replace($this->match[0], $this->replace, self::$content);
            };
        }
    }

    private function getVariable(): void
    {
        $explode = explode('.', $this->match[1]);

        $variable = $explode[0];

        $variable = '$'.$variable;

        for ($k = 1; $k < count($explode); $k++) {
            $variable .= "->".$explode[$k];
        }

        $this->variable = $variable;
    }

    /**
     * @param string $name
     */
    private function filter(string $name): void
    {
        $this->$name();
    }

    private function upper(): void
    {
        if (isset($this->match[2]) && $this->match[2] == 'capitalize') {
            $this->replace = '<?php echo(ucwords(strtolower('.$this->variable.'))); ?>';
        }
    }
}
