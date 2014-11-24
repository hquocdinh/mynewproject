<?php
/**
 * Created by PhpStorm.
 * User: merino
 * Date: 11/21/2014
 * Time: 3:31 PM
 */
namespace vlbdreport;
require 'log_parser.php';


//$connection = new MongoClient();
//$fileCtrl = new File_Control("RoleLevelUp_20141118.log");
/*$strTest = "11/18/2014 0:00	5971915	26108	Thích Ni Cô	5	31	32	828	958	InsertExp";
$arrReturn = explode("\t", $strTest);
var_dump($arrReturn);*/

Log_Parser::parseLogContent("LOG_LEVEL_UP", 20141118);

//MongoDB_Control::openConnection();
//MongoDB_Control::checkCollectionExist("testCollection");