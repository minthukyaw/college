<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of XmlArray
 *
 * @author kthant
 */
class Ezy_Util_XmlArray {

    private static $_self;

    private function __construct(){

    }
    /**
     * @desc : "key" => "text value" is for attribute
     * 		    [0-9] => "text value" is for text node
     * 			"key" => array()  for child node
     * 			[0-9] => array() for child node to have same name of child node at the same tree depth level
     * @param array $A
     * @return unknown_type
     */
    public function getXML(array $A) {
        if (count($A) != 1)
            throw new Exception('There can be only one root for XML document');

        $dom = new DOMDocument('1.0', 'UTF-8');

        if ($this->is_assoc($A)) {
            foreach ($A as $key => $value) {
                $rootNode = $dom->createElement($key);
                if (is_array($value) && !empty($value)) {
                    $this->processNode($value, $rootNode, $dom);
                } else if (is_array($value) && empty($value)) {
                    $text = $dom->createTextNode($key);
                    $rootNode->appendChild($text);
                } else {
                    throw new Exception('Malformed array for XML representation!');
                }
            }
        } else {
            $rootNode = $dom->createElement($A[0]);
        }

        return $dom->saveXML($rootNode);
    }

    public function getArray($xmlString) {
        if (empty($xmlString))
            throw new Exception('Empty XML string');
        $doc = new DOMDocument();
        $doc->loadXML($xmlString);

        $A = array($doc->documentElement->nodeName => array());

        $this->processArray($A[$doc->documentElement->nodeName], $doc->documentElement);

        return $A;
    }

    private function processNode(array $A, &$parent, $dom) {
        if ($this->is_assoc($A)) {
            foreach ($A as $key => $value) {

                if (is_numeric($key) && is_array($value)) {
                    if ($this->is_assoc($value)) {
                        $this->processNode($value, $parent, $dom);
                    } else {
                        foreach ($value as $each)
                            $this->processNode($each, $parent, $dom);
                    }
                } else if (is_numeric($key) && !is_array($value)) {
                    $text = $dom->createTextNode($value);
                    $parent->appendChild($text);
                } else if (is_array($value) && !empty($value)) {
                    $node = $dom->createElement($key);
                    $parent->appendChild($node);
                    $this->processNode($value, $node, $dom);
                } else {
                    $parent->setAttribute($key, $value);
                }
            }
        } else {
            foreach ($A as $a) {
                if (is_array($a)) {
                    $this->processNode($a, $parent, $dom);
                }
                else
                    $parent->appendChild($dom->createTextNode($a));
            }
        }
    }

    private function processArray(&$currentA, $currentNode) {

        $numericKey = 0;

        if ($currentNode->hasAttributes()) {
            foreach ($currentNode->attributes as $attr) {
                $currentA[$attr->nodeName] = $attr->nodeValue;
            }
        }

        if ($currentNode->hasChildNodes()) {
            foreach ($currentNode->childNodes as $child) {
                if ($child->nodeType === XML_TEXT_NODE) {
                    //Text Node
                    if (!empty($child->wholeText)) {
                        $currentA[$numericKey] = $child->wholeText;
                        $numericKey++;
                    }
                } else {
                    if (isset($currentA[$child->nodeName])) {
                        $temp = $currentA[$child->nodeName];
                        $currentA[$child->nodeName] = array($temp);
                        //$currentA[$numericKey] = array($child->nodeName => array());
                        $this->processArray($currentA[$child->nodeName][], $child);
                        $numericKey++;
                    } else {
                        $currentA[$child->nodeName] = array();
                        $this->processArray($currentA[$child->nodeName], $child);
                    }
                }
            }
        }
    }

    protected function is_assoc($array) {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }

    public static function getInstance(){
        if(!isset(self::$_self)){
            self::$_self = new Ezy_Util_XmlArray();
        }

        return self::$_self;
    }

}
