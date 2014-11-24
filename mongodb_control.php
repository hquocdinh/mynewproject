<?php
/**
 * Created by PhpStorm.
 * User: merino
 * Date: 11/24/2014
 * Time: 3:18 PM
 */

namespace vlbdreport;


class MongoDB_Control {
    static function openConnection(){
        $connection = new \MongoClient("mongodb://localhost/VLBD_Report", array("username" => "admin", "password" => "P@ssw0rd"));
        return $connection;
    }
    static function isCollectionExist($collectionName){
        $connection = self::openConnection();
        $collection = $connection->VLBD_Report->$collectionName;
        $cursor = $collection->find();
        if($cursor->getNext()){
            echo sprintf("collection %s is existed\n", $collectionName);
            return true;
        }else{
            echo sprintf("collection %s is not existed\n", $collectionName);
            return false;
        }
    }
    static function creatNewCollection($logType, $collectionName, $date){
        $connection = self::openConnection();
        $newCollection = $connection->VLBD_Report->createCollection($collectionName);
        $newCollection->insert(array(VLBD_DB_Keys::LOG_TYPE => $logType));
        $newCollection->insert(array(VLBD_DB_Keys::LOG_TIME => $date));
        echo sprintf("Create new collection %s successfully!!!\n", $collectionName);
    }
}