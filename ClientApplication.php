<?php

class ClientApplication {
    public function checkGearmanVersion(){
        try {
            if(function_exists(gearman_version)){
                return gearman_version();
            }else {
                throw new Exception("Gearman server not found");
            }
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }

    public function executeQueue($data = array(),$priority = 2){
        if(self::checkGearmanVersion()){
            $client = new GearmanClient();
            $client->addServer("127.0.0.1");
            switch((int)$priority){
                case 1:
                    $client->addTaskLowBackground("queue", json_encode($data));
                    $client->runTasks();
                    break;
                case 2:
                    $client->addTaskBackground("queue", json_encode($data));
                    $client->runTasks();
                    break;
                case 3:
                    $client->addTaskHighBackground("queue", json_encode($data));
                    $client->runTasks();
                    break;
            }

            //$client->addTaskBackground("queue", json_encode($data));

            return true;
        }
    }
}