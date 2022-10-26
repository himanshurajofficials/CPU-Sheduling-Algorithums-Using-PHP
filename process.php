<?php
    //Phase 0.a
    class Process {
        public $name;
        public $id;
        public $arrival_time;
        public $burst_time;
        public $priority;
        public $time_quantum;
        public $waiting_time;
        public $turn_around_time;
        public $completion_time;
        public $service_time;
        function __construct ($name, $id, $arrival_time, $burst_time = 0, $priority = 0, $time_quantum = 5) {
            $this->name = $name;
            $this->id = $id;
            $this->arrival_time = $arrival_time;
            $this->burst_time = $burst_time;
            $this->priority = $priority;
            $this->time_quantum = $time_quantum;
            $this->waiting_time = 0;
            $this->turn_around_time = 0;
        }
    }

    //Phase 0.b
    function generateRandomNumber ($n, $type, $l=5, $h=20) {
        $numbers = array();
        if($type == "burst_time") {
            for($i = 1; $i <= $n; $i++) {
                array_push($numbers, mt_rand(5, 12));
            }
        } else if($type == "priority") {
            $numbers = range(1, 100);
        }
        shuffle($numbers);
        return $numbers;
    }

    //Phase 0.c
    function comparator_arrival($object1, $object2) {
        return $object1->arrival_time > $object2->arrival_time;
    }

    function comparator_burst($object1, $object2) {
        return $object1->burst_time > $object2->burst_time;
    }

    function comparator_priority($object1, $object2) {
        return $object1->priority > $object2->priority;
    }
    
    function fcfs($processes) {
        $service_time = array();
        $n = sizeof($processes);
        usort($processes, "comparator_arrival");
        $average_waiting_time = 0;
        $average_turn_around_time = 0;
        foreach($processes as $i => $process) {
            if($i == 0) {
                array_push($service_time, $process->arrival_time);
                $process->waiting_time = 0;
            } else {
                array_push($service_time, $service_time[$i-1] + $processes[$i-1]->burst_time);
                $process->waiting_time = $service_time[$i] - $process->arrival_time;
                if($process->waiting_time < 0) {
                    $process->waiting_time = 0;
                }
            }
            $process->turn_around_time = $process->burst_time + $process->waiting_time;
            $average_waiting_time += $process->waiting_time;
            $average_turn_around_time += $process->turn_around_time;
        }
        $average_waiting_time /= $n;
        $average_turn_around_time /= $n;
        return [$average_waiting_time, $average_turn_around_time, $processes];
    }

    function sjf($processes) {
        $service_time = array();
        $n = sizeof($processes);
        $average_turn_around_time = 0;
        $average_waiting_time = 0;
        usort($processes, "comparator_arrival");
        $arr_temp = array();
        array_push($arr_temp, $processes[0]);
        $i = 1;
        while ($processes[$i]->arrival_time == $processes[0]->arrival_time && $i < $n) {
            array_push($arr_temp, $processes[$i]);
            $i++;
        }
        if($i > 1) {
            usort($arr_temp, "comparator_burst");
            array_splice($processes, 0, $i, $arr_temp);
        }
        $arr_temp = array(); 
        for ($i=0; $i < $n; $i++) {
            $arr_temp = array();
            $total_time += $processes[$i]->burst_time;
            if($i == 0) {
                array_push($service_time, $processes[$i]->arrival_time);
                $processes[$i]->waiting_time = 0;
            } else {
                array_push($service_time, $service_time[$i-1] + $processes[$i-1]->burst_time);
                $processes[$i]->waiting_time = $service_time[$i] - $processes[$i]->arrival_time;
                if($processes[$i]->waiting_time < 0) {
                    $processes[$i]->waiting_time = 0;
                }
            }
            $processes[$i]->turn_around_time = $processes[$i]->burst_time + $processes[$i]->waiting_time;
            $average_waiting_time += $processes[$i]->waiting_time;
            $average_turn_around_time += $processes[$i]->turn_around_time;
            $j = $i+1;
            while ($processes[$j]->arrival_time <= $total_time && $j < $n) {
                array_push($arr_temp, $processes[$j]);
                $j++;
            }
            if(sizeof($arr_temp) > 1) {
                usort($arr_temp, "comparator_burst");
                array_splice($processes, $i+1, sizeof($arr_temp), $arr_temp);
            }
        }
        $average_waiting_time /= $n;
        $average_turn_around_time /= $n;
        return [$average_waiting_time, $average_turn_around_time, $processes];
    }

    function priority($processes) {
        $service_time = array();
        $n = sizeof($processes);
        $average_turn_around_time = 0;
        $average_waiting_time = 0;
        usort($processes, "comparator_arrival");
        $arr_temp = array();
        array_push($arr_temp, $processes[0]);
        $i = 1;
        while ($processes[$i]->arrival_time == $processes[0]->arrival_time && $i < $n) {
            array_push($arr_temp, $processes[$i]);
            $i++;
        }
        if($i > 1) {
            usort($arr_temp, "comparator_priority");
            array_splice($processes, 0, $i, $arr_temp);
        }
        $arr_temp = array(); 
        for ($i=0; $i < $n; $i++) {
            $arr_temp = array();
            $total_time += $processes[$i]->burst_time;
            if($i == 0) {
                array_push($service_time, $processes[$i]->arrival_time);
                $processes[$i]->waiting_time = 0;
            } else {
                array_push($service_time, $service_time[$i-1] + $processes[$i-1]->burst_time);
                $processes[$i]->waiting_time = $service_time[$i] - $processes[$i]->arrival_time;
                if($processes[$i]->waiting_time < 0) {
                    $processes[$i]->waiting_time = 0;
                }
            }
            $processes[$i]->turn_around_time = $processes[$i]->burst_time + $processes[$i]->waiting_time;
            $average_waiting_time += $processes[$i]->waiting_time;
            $average_turn_around_time += $processes[$i]->turn_around_time;
            $j = $i+1;
            while ($processes[$j]->arrival_time <= $total_time && $j < $n) {
                array_push($arr_temp, $processes[$j]);
                $j++;
            }
            if(sizeof($arr_temp) > 1) {
                usort($arr_temp, "comparator_priority");
                array_splice($processes, $i+1, sizeof($arr_temp), $arr_temp);
            }
        }
        $average_waiting_time /= $n;
        $average_turn_around_time /= $n;
        return [$average_waiting_time, $average_turn_around_time, $processes];
    }
?>