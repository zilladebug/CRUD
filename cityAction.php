<?php
//Handles request from the cityIndex.php file and returns the data.
//The code is executed based on the action_type
//It can be 5 types: data, view, add, edit, delete
//
//DATA: data returns the single user data based on the id as JSON format.
//VIEW: returns all the users data as HTML format.
//ADD: insert the record in the database and return the status.
//EDIT: updates the record in the database and returns the status.
//DELETE: deletes the record from the database and returns the status.


include 'DB.php';
$db = new DB();
$tblName = 'citys';
if(isset($_POST['action_type']) && !empty($_POST['action_type'])){
    //DATA//////
    if($_POST['action_type'] == 'data'){
        $conditions['where'] = array('id'=>$_POST['id']);
        $conditions['return_type'] = 'single';
        $city = $db->getRows($tblName,$conditions);
        echo json_encode($city);
    //VIEW//////
    }elseif($_POST['action_type'] == 'view'){
        $citys = $db->getRows($tblName,array('order_by'=>'id DESC'));
        if(!empty($citys)){
            $count = 0;
            foreach($citys as $city): $count++;
                echo '<tr>';
                echo '<td>#'.$count.'</td>';
                echo '<td>'.$city['country'].'</td>';
                echo '<td>'.$city['district'].'</td>';
                echo '<td>'.$city['name'].'</td>';
                echo '<td>'.$city['area'].'</td>';
                echo '<td><a href="javascript:void(0);" class="glyphicon glyphicon-edit" onclick="editCity(\''.$city['id'].'\')"></a><a href="javascript:void(0);" class="glyphicon glyphicon-trash" onclick="return confirm(\'Are you sure to delete data?\')?cityAction(\'delete\',\''.$city['id'].'\'):false;"></a></td>';
                echo '</tr>';
            endforeach;
        }else{
            echo '<tr><td colspan="5">No cities found......</td></tr>';
        }
    //ADD//////
    }elseif($_POST['action_type'] == 'add'){
        $cityData = array(
            'country' => $_POST['country'],
            'district' => $_POST['district'],
            'name' => $_POST['name'],
            'area' => $_POST['area']
          
        );
        $insert = $db->insert($tblName,$cityData);
        echo $insert?'ok':'err';
    //EDIT//////
    }elseif($_POST['action_type'] == 'edit'){
        if(!empty($_POST['id'])){
            $cityData = array(
            'country' => $_POST['country'],
            'district' => $_POST['district'],
            'name' => $_POST['name'],
            'area' => $_POST['area']
           
            );
            $condition = array('id' => $_POST['id']);
            $update = $db->update($tblName,$cityData,$condition);
            echo $update?'ok':'err';
        }
    //DELETE//////
    }elseif($_POST['action_type'] == 'delete'){
        if(!empty($_POST['id'])){
            $condition = array('id' => $_POST['id']);
            $delete = $db->delete($tblName,$condition);
            echo $delete?'ok':'err';
        }
    }
    exit;
}
?>