<?php

namespace App\Traits;

/**
 * Class XmlableTrait
 */
trait XmlableTrait
{
    /**
     * Turns an array into an XML
     *
     * @param $data
     * @param $xml_data
     */
    private function arrayToXML(array $data, &$xml_data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                /*
                 * If the array key is numeric, uses the first key of the child array to create the parent tag.
                 * With this structure, we can have a list of subnodes with same keys
                 * Example
                 *
                 * <OptionsList>
                 *      <option>...</option>
                 *      <option>...</option>
                 * </OptionsList>
                 *
                 * Useful in this case:
                 * $options[]['option'] =  [
                 *      'key' => 'value',
                 *      'key2' => 'value2',
                 * ];
                 *
                 */
                if (is_numeric($key)) {
                    $key = array_keys($value)[0];
                    $value = $value[$key];
                }

                $subnode = $xml_data->addChild($key);
                $this->arrayToXML($value, $subnode);
            } else {
                if (is_bool($value)) $value = (int)$value;
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
}
