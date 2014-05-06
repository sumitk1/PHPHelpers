<?php

class Node {
    public $data;
    public $link;

    function __construct($data, $next = NULL) {
        $this->data = $data;
        $this->link = $next;
    }
}

class CircularLinkedList {
    private $first;
    private $current;
    private $count;

    function __construct() {
        $this->count   = 0;
        $this->first   = NULL;
        $this->current = NULL;
    }

    /**
     * This function returns if the list is empty or not
     * @return bool - Is the list empty
     */
    function isEmpty() {
        return ($this->first == NULL);
    }

    /**
     * This function inserts the data in the Linked List
     *
     * @param $data - The item to be inserted in the Linked List
     */
    function pushInLinkedList($data) {
        $newNode = new Node($data);
        if ($this->isEmpty()) {
            $this->first   = $newNode;
            $this->current = $this->first;
        } else {
            $list = $this->first;
            while ($list->link != NULL) {
                $list = $list->link;
            }
            $list->link = $newNode;
        }
        $this->count++;
    }

    /**
     * This function inserts the element in the Circular Linked List
     *
     * @param $data - The item to be inserted in the Linked List
     */
    function pushInCircularLinkedList($data) {

        if ($this->isEmpty()) {
            $this->first   = new Node($data);
            $this->current = $this->first;
            $this->count++;
        } else {
            $this->current->link = new Node($data, $this->first);
            $this->current       = $this->current->link;
            $this->count++;
        }
    }

    /**
     * This function returns the position of the value searched in the list else False if not found
     *
     * @param $value - The value to search in the list
     *
     * @return bool
     */
    function find($value) {
        $list     = $this->first;
        $count    = $this->count;
        $position = 1;
        while ($list->link != NULL || $count > 0) {
            if ($list->data == $value) {
                $this->current = $list;

                return $position;
            } else {
                $list = $list->link;
                $count--;
                $position++;
            }
        }

        return FALSE;
    }

    /**
     * This function moves the current pointer to the next node and returns the data of that node
     * @return int - The data of the Node element
     */
    function getNext() {
        $this->current = $this->current->link;
        $result        = $this->current->data;

        return $result;
    }

    /**
     * This function returns the pointer to the first node in the List
     * @return Node
     */
    public function getFirst() {
        return $this->first;
    }

    /**
     * This function returns the pointer to the current node in the List
     * @return Node
     */
    public function getCurrent() {
        return $this->current;
    }

    /**
     * This function prints the current state of the Circular linked list
     * It shows 0 for chair empty and 1 as chair occupied
     */
    public function printList() {
        $list  = $this->first;
        $count = $this->count;
        while ($count > 0) {
            echo " " . $list->data;
            $list = $list->link;
            $count--;
        }
    }

    /**
     * This function finds the first position of the chair which is not empty
     *
     * @return int - The position of the survivor
     */
    public function findSurvivorPosition() {
        $list     = $this->first;
        $count    = $this->count;
        $position = 1;
        while ($count > 0) {
            if ($list->data) {
                break;
            } else {
                $position++;
            }
            $list = $list->link;
            $count--;
        }

        return $position;
    }
}

// New object of the CircularLinkedList
$circularLinkedList = new CircularLinkedList();

$count      = 100;
$skip       = 0; // This is a flag that if set shows that we have already moved one person and we need to skip the current chair
$isOccupied = 1;
for ($i = 0; $i < $count; $i++) {
    // Initializing all the elements of the Circular linked list to 1 - meaning all the chairs are occupied
    $circularLinkedList->pushInCircularLinkedList($isOccupied);
}

// The current pointer after the above initialization points to the last node
// The operation below makes it point to the first node
$circularLinkedList->getNext();

echo "Initial List: ";
$circularLinkedList->printList();

while ($count != 1 && $count > 0) {
    $data = $circularLinkedList->getCurrent()->data;
    //echo "<br /> $count - $skip - $data<br />";
    if ($count == 100) { // First node
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

    if ($count == 1) {
        break;
    }

}

echo "\nFinal List: ";
$circularLinkedList->printList();
echo "\n\nThe position of the survivor is = " . $circularLinkedList->findSurvivorPosition();

?>