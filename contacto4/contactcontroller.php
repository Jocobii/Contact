<?php
require_once('models/contact.php');
require_once('models/exceptions/recordnotfoundexception.php');
//allow acces 
header('Access-Control-Allow-Origin:*');
if($_SERVER['REQUEST_METHOD']=='GET'){
if(isset($_GET['id'])){
    try{
        $c = new Contact($_GET['id']);
        echo json_encode(array(
            'status' => 0,
            'contact' => json_decode($c->toJson())
        ));
    }
    catch(RecordNotFoundException $ex){
        echo json_encode(array(
            'status' => 1,
            'errorMessage' => 'error'
        ));
    }
 
}else{
    echo json_encode(array(
        'status' => 0,
        'contact' => json_decode(Contact::getAlltoJson())
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