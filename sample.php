<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        require_once("process.php");
        $n = $_POST["n_process"];
        $scheduling_type = $_POST["scheduling_type"];
        $processes = array();
        for ($i=1; $i<=$n; $i++) {
            $name = $_POST["pname".$i];
            $id = $_POST["pid".$i];
            $arrival_time = $_POST["arrival_time".$i];
            $burst_time = $_POST["burst_time".$i];
            if($_POST["priority".$i]) {
                $priority = $_POST["priority".$i];
            } else {
                $priority = 0;
            }
            if($_POST["time_quantum".$i]) {
                $time_quantum = $_POST["time_quantum".$i];
            } else {
                $time_quantum = 5;
            }
            array_push($processes, new Process($name, $id, $arrival_time, $burst_time, $priority, $time_quantum));
        }
        $output = array();
        switch ($scheduling_type) {
            case "fcfs": $output = fcfs($processes); $processes = $output[2]; break;
            case "sjf": $output = sjf($processes); $processes = $output[2]; break;
            case "round_robin": $output = round_robin($processes); $processes = $output[2]; break;
            case "priority": $output = priority($processes); $processes = $output[2]; break;
            default: $output = [0, 0];
        }
        echo "<p>The order of the processes in the table is the same as their execution order</p><table style='border: black solid 1px; text-align: center;'><tr><th>Process ID</th><th>Process Name</th><th>Arrival Time</th><th>Burst Time</th><th>Waiting Time</th><th>Turn Around Time</th></tr>";
        foreach ($processes as $key => $obj) {
            echo "<tr><td>$obj->id</td><td>$obj->name</td><td>$obj->arrival_time</td><td>$obj->burst_time</td><td>$obj->waiting_time</td><td>$obj->turn_around_time</td></tr>";
        }
        echo "</table>";
        echo "<p>Average Waiting Time: $output[0]</p><p>Average Turn Around Time: $output[1]</p></div>";
    ?>
</body>
</html>
