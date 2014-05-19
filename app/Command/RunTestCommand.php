<?php
/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */



namespace Command;


use Hatching\Model\Project;
use Hatching\Model\Test;

class RunTestCommand extends CommandBase {

    public function run(){


        // initilize vars
        $projectName = $this->getArgValue("project-name");
        $projectId   = $this->getArgValue("project-id");


        // check requirements
        if(!$projectName && !$projectId){
            echo "No project specified";
            return ;
        }

        if($projectName){
            $project = Project::getByName($this->getMongo(),$projectName);
        }else{
            $project = Project::getById($this->getMongo(),$projectId);
        }

        if(!$project){
            echo "Project not found";
            return false;
        }


        // check the project is configured for hatching
        $outputFile = "/tmp/.hatching.run." . $this->getCli()->daemon()->getCsId() . ".log";
        $projectRoot = APP_ROOT . "/data/frozen-cart";
        $conf = $project->getProjectConfiguration();

        if(!$conf)
            return false; // todo error



        // CREATE AND PERSIST THE TEST MODEL
        $testModel = new Test();
        $testModel->dateBegin = time();
        $testModel->projectId = $project->_id;
        $storableTest = $testModel->getStorable();
        $this->getMongo()->test->insert($storableTest);
        $testModel->_id = $storableTest["_id"];


        // initialize the buffer
        file_put_contents($outputFile,"");

        // initialize the exit status
        $exitStatus = 0;

        // say that we start
        $this->__newLine($outputFile,"\e[0;44m         Test STARTED         \e[0m ");
        echo 'Test Starting';
        echo PHP_EOL;

        // chdir to project root
        $beforeCwd = getcwd();
        chdir($projectRoot);

        // run the tests
        $run = $conf->getRun();
        foreach($run as $r){

            $newCmd =  "($r) 1>>$outputFile 2>&1 ";


            $this->__newLine($outputFile,"");

            $this->__newLine($outputFile,str_repeat("-",20));
            $this->__newLine($outputFile , "---> running command : \e[0;34m" . $r . "\e[0m");

            passthru($newCmd, $exitStatus);



            if($exitStatus !== 0){
                $this->__newLine($outputFile,"\e[0;31mTest didnt pass, exiting now\e[0m");
                $this->__newLine($outputFile,str_repeat("-",20));
                $this->__newLine($outputFile,PHP_EOL);
                break;
            }else{
                $this->__newLine($outputFile,"\e[0;32mOK\e[0m");
                $this->__newLine($outputFile,str_repeat("-",20));
                $this->__newLine($outputFile,"");
            }

        }
        chdir($beforeCwd);

        // update the model
        $testModel->dateEnd = time();
        $testModel->status = $exitStatus;
        $testModel->executionString = file_get_contents($outputFile);
        $this->getMongo()->test->update(array("_id"=> $testModel->_id) , $testModel->getStorable() );


        // delete the buffer
        unlink($outputFile);


        // exit with a nice message
        if($exitStatus !== 0)
            $message = "\e[0;41m         Test FAILED         \e[0m ";
        else
            $message = "\e[0;42m         Test SUCCEED         \e[0m ";

        $this->__newLine($outputFile,$message);
        echo $message;



    }

    private function __newLine($file,$message){
        file_put_contents($file,$message . PHP_EOL ,FILE_APPEND);
    }

}