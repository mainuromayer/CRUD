<?php

define('DB_NAME', 'C:\xampp\htdocs\PHP_Laravel(Ostad)\Advance\CRUD\data\db.txt');
function seed(){
    $data = array(
        array(
            'id' => 1,
            'fname' => 'Mainur',
            'lname' => 'Rahaman',
            'roll' => '22'
        ),
        array(
            'id' => 2,
            'fname' => 'Miraj',
            'lname' => 'Hossain',
            'roll' => '26'
        ),
        array(
            'id' => 3,
            'fname' => 'Siam',
            'lname' => 'Ahmed',
            'roll' => '16'
        ),
        array(
            'id' => 4,
            'fname' => 'Fahim',
            'lname' => 'Rahaman',
            'roll' => '34'
        ),
    );

    $serializedData = serialize($data);
    file_put_contents(DB_NAME, $serializedData, LOCK_EX);
}


function generateReport(){
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData); ?>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Roll</th>
                <th width="25%">Action</th>
            </tr>
        </thead>
        <?php foreach ($students as $student): ?>
        <tbody>
            <tr>
                <td><?php printf('%s %s', $student['fname'], $student['lname']); ?></td>
                <td><?php printf('%s', $student['roll']); ?></td>
                <td><?php printf('<a href="/CRUD/index.php?task=edit&id=%s">Edit</a> | <a class="delete" href="/CRUD/index.php?task=delete&id=%s">Delete</a>', $student['id'], $student['id']); ?></td>
            </tr>
        </tbody>
        <?php endforeach; ?>
    </table>

<?php
}

function addStudent($fname, $lname, $roll){
    $found = false;
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);

    foreach ($students as $_student){
        if ($_student['roll'] == $roll){
            $found = true;
            break;
        }
    }
    if (!$found){
        $newId = getNewId($students);
        $student = array(
            'id' => $newId,
            'fname' => $fname,
            'lname' => $lname,
            'roll' => $roll
        );

        array_push($students, $student);

        $serializedData = serialize($students);
        file_put_contents(DB_NAME, $serializedData, LOCK_EX);
        return true;
    }
    return false;
}

function getStudent($id){
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);

    foreach ($students as $student){
        if ($student['id'] == $id){
            return $student;
        }
    }
    return false;
}

function updateStudent($id, $fname, $lname, $roll){
    $found = false;
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    foreach ($students as $_student){
        if ($_student['roll'] == $roll && $_student['id'] != $id){
            $found = true;
            break;
        }
    }
    if (!$found){
        $students[$id-1]['fname'] = $fname;
        $students[$id-1]['lname'] = $lname;
        $students[$id-1]['roll'] = $roll;

        $serializedData = serialize($students);
        file_put_contents(DB_NAME, $serializedData, LOCK_EX);
        return true;
    }
    return false;
}

function deleteStudent($id){
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);

    foreach ($students as $offset=>$student){
        if ($student['id'] == $id){
            unset($students[$offset]);
        }
    }

    $serializedData = serialize($students);
    file_put_contents(DB_NAME, $serializedData, LOCK_EX);
}

//function printRaw(){
//    $serializedData = file_get_contents(DB_NAME);
//    $students = unserialize($serializedData);
//
//    print_r($students);
//}

function getNewId($students){
    $maxId = max(array_column($students, 'id'));
    return $maxId+1;
}

?>


