<?php
class Alerte{
    public $status;
    public $message;
    public $estRediriger = false;
    public $redirection = null;
   
    function __construct($status,$message){
        $this->status = $status;
        $this->message = $message;
    }
    function setRedirection($estRediriger,$redirigerVers){
        $this->estRediriger = $estRediriger;
        $this->redirection = $redirigerVers;
    }
    
}
?>