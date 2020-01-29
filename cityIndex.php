<!DOCTYPE html>
<!-- source: http://www.codexworld.com/php-crud-operations-jquery-ajax-mysql/ -->
<!-- Information: 
All operations will happen on a single page without page refresh. 
Also, bootstrap table structure will be used for styling the list, form fields, and links.--->


<!-- /////////// CITY TABLE ///////////////--->
<html lang="en">
<head>
<title>PHP CRUD</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<style type="text/css">
table tr th, table tr td{font-size: 1.2rem;}
.row{ margin:20px 20px 20px 20px;width: 100%;}
.glyphicon{font-size: 20px;}
.glyphicon-plus{float: right;}
a.glyphicon{text-decoration: none;}
a.glyphicon-trash{margin-left: 10px;}
.none{display: none;}
</style>
<script>
function getCitys(){
    $.ajax({
        type: 'POST',
        url: 'cityAction.php',
        data: 'action_type=view&'+$("#cityForm").serialize(),
        success:function(html){
            $('#cityData').html(html);
        }
    });
}
function cityAction(type,id){
    id = (typeof id == "undefined")?'':id;
    var statusArr = {add:"added",edit:"updated",delete:"deleted"};
    var cityData = '';
    if (type == 'add') {
        cityData = $("#addForm").find('.form').serialize()+'&action_type='+type+'&id='+id;
    }else if (type == 'edit'){
        cityData = $("#editForm").find('.form').serialize()+'&action_type='+type;
    }else{
        cityData = 'action_type='+type+'&id='+id;
    }
    $.ajax({
        type: 'POST',
        url: 'cityAction.php',
        data: cityData,
        success:function(msg){
            if(msg == 'ok'){
                alert('City data has been '+statusArr[type]+' successfully.');
                getCitys();
                $('.form')[0].reset();
                $('.formData').slideUp();
            }else{
                alert('Some problem occurred, please try again.');
            }
        }
    });
}
function editCity(id){
    $.ajax({
        type: 'POST',
        dataType:'JSON',
        url: 'cityAction.php',
        data: 'action_type=data&id='+id,
        success:function(data){
            $('#idEdit').val(data.id);
            $('#countryEdit').val(data.country);
            $('#districtEdit').val(data.district);
            $('#nameEdit').val(data.name);
            $('#areaEdit').val(data.area);
            $('#editForm').slideDown();
        }
        
        
    });
}
</script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="panel panel-default users-content">
            <div class="panel-heading">City <a href="javascript:void(0);" class="glyphicon glyphicon-plus" id="addLink" onclick="javascript:$('#addForm').slideToggle();"></a></div>
            <div class="panel-body none formData" id="addForm">
                
                 <!--- ADD ADD ADD ADD --->
                 
                <h2 id="actionLabel">Add Country</h2>
                <form class="form" id="cityForm">
                    <div class="form-group">
                        <label>Country</label>
                        <input type="text" class="form-control" name="country" id="country"/>
                    </div>
                    <div class="form-group">
                        <label>District</label>
                        <input type="text" class="form-control" name="district" id="district"/>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" id="name"/>
                    </div>
                    
                       <div class="form-group">
                        <label>Area</label>
                        <input type="text" class="form-control" name="area" id="area"/>
                    </div>
                  
                    <a href="javascript:void(0);" class="btn btn-warning" onclick="$('#addForm').slideUp();">Cancel</a>
                    <a href="javascript:void(0);" class="btn btn-success" onclick="cityAction('add')">Add City</a>
                </form>
            </div>
            
            <!--- EDIT EDIT EDIT EDIT --->
            
            <div class="panel-body none formData" id="editForm">
                <h2 id="actionLabel">Edit Country</h2>
                <form class="form" id="cityForm">
                    <div class="form-group">
                     <label>Country</label>
                        <input type="text" class="form-control" name="country" id="countryEdit"/>
                    </div>
                    <div class="form-group">
                        <label>District</label>
                        <input type="text" class="form-control" name="district" id="disctrictEdit"/>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" id="nameEdit"/>
                    </div>
                    
                     <div class="form-group">
                        <label>Area</label>
                        <input type="text" class="form-control" name="area" id="area"/>
                    </div>
                    <input type="hidden" class="form-control" name="id" id="idEdit"/>
                    <a href="javascript:void(0);" class="btn btn-warning" onclick="$('#editForm').slideUp();">Cancel</a>
                    <a href="javascript:void(0);" class="btn btn-success" onclick="cityAction('edit')">Update City</a>
                </form>
                

                
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Country</th>
                        <th>District</th>
                        <th>Name</th>
                        <th>Area</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cityData">
                    <?php
                        include 'DB.php';
                        $db = new DB();
                        $citys = $db->getRows('citys',array('order_by'=>'id DESC'));
                        if(!empty($citys)): $count = 0; foreach($citys as $city): $count++;
                    ?>
                    <tr>
                        <td><?php echo '#'.$count; ?></td>
                        <td><?php echo $city['country']; ?></td>
                        <td><?php echo $city['district']; ?></td>
                        <td><?php echo $city['name']; ?></td>
                        <td><?php echo $city['area']; ?></td>
                        <td>
                            <a href="javascript:void(0);" class="glyphicon glyphicon-edit" onclick="editCity('<?php echo $city['id']; ?>')"></a>
                            <a href="javascript:void(0);" class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure to delete data?')?cityAction('delete','<?php echo $city['id']; ?>'):false;"></a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5">No city to view from the DB.....</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>