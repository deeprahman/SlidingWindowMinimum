<?php

final class SWM
{
    public static function main(){
        $array = [1,10,3,1,1,3,5,5,23,6,6];
        $win_size = 4;
        $obj = new SWM();
        print_r($obj->naive($array,$win_size));

        printf(PHP_EOL);
        echo "Using Optimized method:", PHP_EOL;
       
        print_r($obj->optimized($array,$win_size));
    }

    public function naive(array $array, int $win):array
    {
        $result = [];
        $length = sizeof($array);
        $main_ptr = $win_ptr = 0; // points to array item
        $window_min = null;
        if ( $length === 0 || $win > $length ) {
            throw new Exception("Invalid argument values provided!");
        }
        while(($length - $win) >= ($win_ptr = $main_ptr)){ //O(nm)
            $window_min = $array[ $main_ptr ];
            while( $win_ptr < ($main_ptr + $win) ){
                $current_window_item = $array[ $win_ptr ];
                $window_min =  min($window_min, $current_window_item);
                $win_ptr++;
            }
            array_push($result, $window_min);
            $main_ptr++;
        }
        return $result;
    }

    public function optimized(array $array, int $win):array // O(n)
    {
        $result = [];
        $length = sizeof($array);
        
        $q = new SplQueue(); // The queue never exceed four items
        if ( $length === 0 || $win > $length ) {
            throw new Exception("Invalid argument values provided!");
        }

        array_push($result,$this->init($q, $array, $win)); // fill the queue for the first window or frame

        $result =  array_merge($result, $this->slideAndFindMin($q,$array,$win,$length));
        
        return $result;

    }

    private function init(SplQueue &$q, $array, $win):mixed // O(1)
    {
        $main_ptr = 0; // points to array item
        while( $main_ptr < $win){
            while( ! $q->isEmpty() && ($array[ $main_ptr ] <= $array[ $q->top() ]) ){
                $q->pop();
            }
            $q->enqueue($main_ptr);
            $main_ptr++;
        }
        return $array[$q->bottom()];
    }

    private function slideAndFindMin(SplQueue $q, $array, $win, $length):array // O(n)
    {
        $ret = [];
        $main_ptr = $win; // start from the first unvisited item
        while( $main_ptr < $length ){ //O(n)

            while( ! $q->isEmpty() && $q->bottom() <= $main_ptr - $win ){ //O(1)
                $q->dequeue();
            }
            while( ! $q->isEmpty() && ($array[ $main_ptr ] <= $array[ $q->top() ]) ){ // O(1)
                $q->pop();
            }
            $q->enqueue($main_ptr);
            array_push($ret, $array[$q->bottom()]);
            $main_ptr++;
        }
        return $ret;
    }
}

SWM::main();