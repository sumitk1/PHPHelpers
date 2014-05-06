<?php

class Node{
    public $data;
    public $link;

    function __construct($data, $next = NULL){
        $this->data = $data;
        $this->link = $next;
    }
}

class CircularLinkedList{
    private $first;
    private $current;
    private $count;

    function __construct(){
        $this->count = 0;
        $this->first = null;
        $this->current = null;
    }

    function isEmpty(){
        return ($this->first == NULL);
    }

    function pushLL($data){
        $p = new Node($data);
        if($this->isEmpty()){
            $this->first = $p;
            $this->current = $this->first;
        }
        else{
            $q = $this->first;
            while($q->link != null)
                $q = $q->link;
            $q->link = $p;
        }
        $this->count++;
    }

    function pushCLL($data){

        if($this->isEmpty()) {
            $this->first   = new Node($data);
            $this->current = $this->first;
            $this->count++;
        }
        else {
            $this->current->link = new Node($data, $this->first);
            $this->current = $this->current->link;
            $this->count++;
        }
    }
    function find($value){
        $q = $this->first;
        $count = $this->count;
        while($q->link != null || $count > 0){
            if($q->data == $value)
                $this->current = $q;
            $q = $q->link;
            $count--;
        }
        return false;
    }

    function getNext(){
        $this->current = $this->current->link;
        $result = $this->current->data;
        return $result;
    }

    public function getFirst () {
        return $this->first;
    }

    public function getCurrent () {
        return $this->current;
    }
    
    public function printList () {
        $q = $this->first;
        $count = $this->count;
        while($count > 0){
            echo " - " . $q->data . " - <br/>";
            $q = $q->link;
            $count--;
        }
    }
    
    public function findSurvivorPosition () {
        $q = $this->first;
        $count = $this->count;
        $position = 1; 
        while($count > 0){
            if ($q->data) {
                break;
            } else {
                $position++;
            }
            $q = $q->link;
            $count--;
        }
        return $position;
    }
}

$circularLinkedList = new CircularLinkedList();

$count = 10;
$skip  = 0;
$d = 1;
for($i = 0; $i < $count; $i++) {
    $circularLinkedList->pushCLL($d);
}

$circularLinkedList->getNext();

echo "<br/><br/>";

while ($count != 1 && $count > 0) {
    $data = $circularLinkedList->getCurrent()->data;
    //echo "<br /> $count - $skip - $data<br />";
    if ($count == 10) { // First node
        $circularLinkedList->getCurrent()->data = "0";
        $circularLinkedList->getNext();
        $skip++;
        $count--;
        continue;
    }

    if ($skip == 1 && $data == 1) {
        $circularLinkedList->getNext();
        $skip--;
        continue;
    }
    if ($skip == 0 && $data == 1 && $count != 100) {
        $circularLinkedList->getCurrent()->data = "0";
        $circularLinkedList->getNext();
        $skip++;
        $count--;
        continue;
    }
    if ($data == 0 && $count != 100) {
        $circularLinkedList->getNext();   
        continue;
     }
     
     if ($count == 1) break;

}   

$circularLinkedList->printList();
echo "<br/>The position of the survivor is = " .$circularLinkedList->findSurvivorPosition(); 

?>