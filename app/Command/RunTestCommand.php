<?php
/**
 * @copyright (c) Soufiane GHZAL <sghzal@gmail.com>
 * view LICENSE file for license informations
 */



namespace Command;


use Hatching\Model\Project;

class RunTestCommand extends CommandBase {

    public function run(){


        $projectName = $this->getArgValue("project-name");
        $projectId   = $this->getArgValue("project-id");

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



        $outputFile = "/tmp/outputTest.log";

        $projectRoot = APP_ROOT . "/data/frozen-cart";


        $project = new Project( $projectRoot );

        $conf = $project->getProjectConfiguration();


        if(!$conf)
            return false; // todo error

        $run = $conf->getRun();

        $exitStatus = 0;


        file_put_contents($outputFile,"");


        $this->__newLine($outputFile,"\e[0;44m         Test STARTED         \e[0m ");

        $beforeCwd = getcwd();
        chdir($projectRoot);
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