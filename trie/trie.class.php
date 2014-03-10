<?php

class Trie {
    private $trie = array();
    private $value = NULL;

    function Trie($value = NULL) {
        $this->value = $value;
    }

    function add($string, $value, $overWrite = TRUE) {
        if (empty($string)) {
            if (is_null($this->value) || $overWrite) {
                $this->value = $value;
            }

            return;
        }

        foreach ($this->trie as $prefix => $trie) {
            $prefixLength = strlen($prefix);
            $head         = substr($string, 0, $prefixLength);
            $headLength   = strlen($head);

            $equals      = TRUE;
            $equalPrefix = "";
            for ($i = 0; $i < $prefixLength; ++$i) {
                //Split
                if ($i >= $headLength) {
                    $equalTrie                            = new Trie($value);
                    $this->trie[$equalPrefix]             = $equalTrie;
                    $equalTrie->trie[substr($prefix, $i)] = $trie;
                    unset($this->trie[$prefix]);
                    return;
                } else {
                    if ($prefix[$i] != $head[$i]) {
                        if ($i > 0) {
                            $equalTrie                            = new Trie();
                            $this->trie[$equalPrefix]             = $equalTrie;
                            $equalTrie->trie[substr($prefix, $i)] = $trie;
                            $equalTrie->trie[substr($string, $i)] = new Trie($value);
                            unset($this->trie[$prefix]);
                            return;
                        }
                        $equals = FALSE;
                        break;
                    }
                }

                $equalPrefix .= $head[$i];
            }

            if ($equals) {
                $trie->add(substr($string, $prefixLength), $value, $overWrite);
                return;
            }
        }

        $this->trie[$string] = new Trie($value);
    }

    private function searchTrie($string) {
        if (empty($string)) {
            return array($string, $this);
        }

        $stringLength = strlen($string);
        foreach ($this->trie as $prefix => $trie) {
            $prefixLength = strlen($prefix);
            if ($prefixLength > $stringLength) {
                $prefix = substr($prefix, 0, $stringLength);
                if ($prefix == $string) {
                    return array($string, $this);
                }
            }
            $head = substr($string, 0, $prefixLength);

            if ($head == $prefix) {
                return $trie->searchTrie(substr($string, $prefixLength));
            }
        }

        return NULL;
    }

    function search($string) {
        if (empty($string)) {
            return $this->value;
        }

        foreach ($this->trie as $prefix => $trie) {
            $prefixLength = strlen($prefix);
            $head         = substr($string, 0, $prefixLength);

            if ($head == $prefix) {
                return $trie->search(substr($string, $prefixLength));
            }
        }

        return NULL;
    }

    function searchMultiple($array, $delimeter = ' ') {
        $size  = count($array);
        $value = NULL;

        for ($j = 0; $j < $size; ++$j) {
            $trie  = $this;
            $delim = '';
            $key   = '';

            for ($i = $j; $i < $size; ++$i) {
                $key .= $delim . $array[$i];
                $ret = $trie->searchTrie($key);
                if (is_null($ret)) {
                    break;
                }

                $trie  = $ret[1];
                $key   = $ret[0];
                $delim = $delimeter;
                if (!is_null($trie->value)) {
                    $value = $trie->value;
                }
            }

            if (!is_null($value)) {
                return $value;
            }
        }

        return NULL;
    }
}

?>