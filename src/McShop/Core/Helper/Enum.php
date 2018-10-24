<?php declare(strict_types=1);


namespace McShop\Core\Helper;

use Doctrine\Common\Inflector\Inflector;
use ReflectionClass;

class Enum
{
    /**
     * @var string|int
     */
    protected $value;

    /**
     * Enum load
     * @param string|int $value
     * @return Enum
     * @throws \Exception
     */
    public function load($value): self
    {
        if (!in_array($value, array_values($this->getListWithValues()), true)) {
            throw new \Exception(sprintf("Invalid value \"%s\" for enum %s", $value, static::class));
        }

        $this->value = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getListWithValues(): array
    {
        $result = [];
        $list = $this->getList();

        foreach ($list as $constant) {
            $result[$constant] = constant(static::class . '::' . $constant);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        try {
            $result = (new ReflectionClass(static::class))->getConstants();
        } catch (\ReflectionException $exception) {
            $result = [];
        }

        return array_keys($result);
    }

    /**
     * getTranslationForItem
     *
     * @param $value
     *
     * @return string
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function getTranslationForItem($value): string
    {
        $constantName = null;
        $list = $this->getListWithValues();
        if (in_array($value, $list, true)) {
            $constantName = array_flip($list)[$value];
        }

        if (!$constantName && isset($list[$value])) {
            $constantName = $value;
        }

        if (!$constantName) {
            throw new \Exception(sprintf("Constant this name or value \"%s\" for enum %s not found", $value, static::class));
        }

        $shortName = Inflector::tableize((new ReflectionClass(static::class))->getShortName());

        return 'enum.'.str_replace('_enum', '', $shortName).'.'.strtolower(str_replace('_', '.', $constantName));
    }

    /**
     * getTranslationsWithValues
     *
     * @return array
     * @throws \ReflectionException
     */
    public function getTranslationsWithValues()
    {
        $list = $this->getListWithValues();
        $result = [];
        foreach ($list as $key => $item) {
            $result[$this->getTranslationForItem($key)] = $item;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
