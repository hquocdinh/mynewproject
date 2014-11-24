<?php
/**
 * Created by PhpStorm.
 * User: merino
 * Date: 11/21/2014
 * Time: 3:23 PM
 */
namespace vlbdreport;
require 'vlbd_db_keys.php';
require 'mongodb_control.php';
$RAW_LOG_FORMAT = array(
    "LOG_LEVEL_UP" => array(
        "fileName" => "RoleLevelUp_%d.log",
        "dataPattern" => array(
            0 => array("DB_Key" => VLBD_DB_Keys::DETAIL_TIME, "Data_Type" => "string"),
            1 => array("DB_Key" => VLBD_DB_Keys::PLAYER_ACCOUNT, "Data_Type" => "int"),
            2 => array("DB_Key" => VLBD_DB_Keys::PLAYER_ID, "Data_Type" => "int"),
            3 => array("DB_Key" => VLBD_DB_Keys::ROLE_NAME, "Data_Type" => "string"),
            4 => array("DB_Key" => VLBD_DB_Keys::SERVER_ID, "Data_Type" => "int"),
            5 => array("DB_Key" => VLBD_DB_Keys::OLD_LEVEL, "Data_Type" => "int"),
            6 => array("DB_Key" => VLBD_DB_Keys::CURRENT_LEVEL, "Data_Type" => "int"),
            7 => array("DB_Key" => VLBD_DB_Keys::CURRENT_EXP, "Data_Type" => "int"),
            8 => array("DB_Key" => VLBD_DB_Keys::PLAYING_TIME, "Data_Type" => "int"),
        )
    )
);

class Log_Parser {
    static function parseLogContent($logType, $date){
        echo sprintf("[%'*-80s]\n", $logType);
        //need to check if the file has been already parsed and data existed in DB
        $collectionName = $logType."_".$date;
        if (MongoDB_Control::isCollectionExist($collectionName) == true) {
            echo sprintf("[%'*80s]\n","");
            return;
        }else{
            MongoDB_Control::creatNewCollection($logType, $collectionName, $date);
        }
        global $RAW_LOG_FORMAT;
        $logFormat = $RAW_LOG_FORMAT[$logType];
        $fileName = "./unpacked/" . sprintf($logFormat["fileName"], $date);
        echo sprintf("Begin parsing file '%s'\n", $fileName);
        $returnedArray = array();
        $tmpLine = "";
        $myFile = fopen($fileName, "r") or die("Unable to open file ".$fileName);

        while(!feof($myFile)){
            $tmpLine = fgets($myFile);
            if ($tmpLine != ""){
                array_push($returnedArray, self::selectSomeFields(explode("\t", $tmpLine), $logFormat["dataPattern"]));
            }
        }

        fclose($myFile);
       // echo "so luong: ".count($returnedArray)."\n";
        echo "Finished!!! Total record: ".count($returnedArray)."\n";
        echo sprintf("[%'*80s]\n","");
    }

    static function selectSomeFields($arrInput, $pattern){
        $arrReturn = array();
        foreach($pattern as $key => $value){
            if($value["Data_Type"] != "string"){
                settype($arrInput[$key],$value["Data_Type"]);
            }
            array_push($arrReturn, array($value["DB_Key"] => $arrInput[$key]));
        }
        return $arrReturn;
    }

}