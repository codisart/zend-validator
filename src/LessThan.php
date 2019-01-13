<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Validator;

use Traversable;
use Zend\Stdlib\ArrayUtils;

class LessThan extends AbstractValidator
{
    const NOT_LESS           = 'notLessThan';
    const NOT_LESS_INCLUSIVE = 'notLessThanInclusive';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_LESS           => "The input is not less than '%max%'",
        self::NOT_LESS_INCLUSIVE => "The input is not less or equal than '%max%'"
    ];

    /**
     * Additional variables available for validation failure messages
     *
     * @var array
     */
    protected $messageVariables = [
        'max' => 'max'
    ];

    /**
     * Maximum value
     *
     * @var mixed
     */
    protected $max;

    /**
     * Whether to do inclusive comparisons, allowing equivalence to max
     *
     * If false, then strict comparisons are done, and the value may equal
     * the max option
     *
     * @var bool
     */
    protected $inclusive;

    /**
     * Sets validator options
     *
     * @param  array|Traversable $options
     * @throws Exception\InvalidArgumentException
     */
    public function __construct($options = null)
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }
        if (! is_array($options)) {
            $options = $this->setOptions(...func_get_args())
        }

        $this->shouldHaveAMaxValue($options);

        $this->setMax($options['max']);
        $this->setInclusive($options['inclusive']);

        parent::__construct($options);
    }

    private function setOptions($max = null, $inclusive = false)
    {
        return [
            'max' => $max,
            'inclusive' => $inclusive,
        ];
    }

    private function shouldHaveAMaxValue(array $options)
    {
        if (! array_key_exists('max', $options)) {
            throw new Exception\InvalidArgumentException("Missing option 'max'");
        }
    }

    /**
     * Returns the max option
     *
     * @return mixed
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Sets the max option
     *
     * @param  mixed $max
     * @return LessThan Provides a fluent interface
     */
    public function setMax($max)
    {
        $this->max = $max;
        return $this;
    }

    /**
     * Returns the inclusive option
     *
     * @return bool
     */
    public function getInclusive()
    {
        return $this->inclusive;
    }

    /**
     * Sets the inclusive option
     *
     * @param  bool $inclusive
     * @return LessThan Provides a fluent interface
     */
    public function setInclusive($inclusive)
    {
        $this->inclusive = $inclusive;
        return $this;
    }

    /**
     * Returns true if and only if $value is less than max option, inclusively
     * when the inclusive option is true
     *
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);

        if ($this->inclusive) {
            if ($value > $this->max) {
                $this->error(self::NOT_LESS_INCLUSIVE);
                return false;
            }
            return true;
        }

        if ($value >= $this->max) {
            $this->error(self::NOT_LESS);
            return false;
        }
        return true;
    }
}
