<?php
require_once('models/contacttype.php');
if($_SERVER['REQUEST_METHOD']=='GET'){
if(isset($_GET['id'])){
    try{
        $ct = new ContactType($_GET['id']);
        echo json_encode(array(
            'status' => 0,
            'contactType'=>json_decode($ct->toJson())
        ));
    }
    catch(RecordNotFoundException $ex){
        echo json_encode(array(
            'status'=>1,
            'errorMessage'=>$ex->getMesage()
        ));
    }
 
}else{
    echo json_encode(array(
        'status'=>0,
        'contactType'=>json_decode(ContactType::getAlltoJson())
    ));
}

}

if($_SERVER['REQUEST_METHOD']=='POST'){

    echo 'post';
}
if($_SERVER['REQUEST_METHOD']=='PUT'){

    echo 'put';
}

if($_SERVER['REQUEST_METHOD']=='DELETE'){

    echo 'delete';
}
?>