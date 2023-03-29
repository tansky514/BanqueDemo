<?php

class RenderInfos{
    public $UserInfos;
    public $Transactions;
    public $Status;
    
    function __construct($UserInfos,$Transactions){
        $this->UserInfos = $UserInfos;
        $this->Transactions = $Transactions;
    }
    function setStatus($status){
        $this->Status=$status;
    }
}

?>