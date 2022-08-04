<?php

namespace App\Services\File\common;

use App\Models\Course;
use Illuminate\Http\Request;

class FileEntity {

    public function getEntity($request)
    {
        if($request['course_id']){
            $this->setEntity($request,'course');
            $this->setEntityId($request, $request['course_id']);
            $this->setEntityModel($request,Course::find($request['course_id']));
        } elseif ($request['entity'] == 'donate'){
            $this->setEntity($request,'donate');
            $this->setEntityId($request,null);
            $this->setEntityModel($request,null);
        } elseif ($request['entity'] == 'tariff') {
            $this->setEntity($request,'tariff');
            $this->setEntityId($request,null);
            $this->setEntityModel($request,null);
        }
        else {
            $this->setEntity($request,null);
            $this->setEntityId($request,null);
            $this->setEntityModel($request,null);
        }

    }

    private function setEntity($request, $entity){
        $request->request->add(['entity' => $entity]);
    }

    private function setEntityId($request, $entityId){
        $request->request->set('entityId', $entityId);
    }

    private function setEntityModel($request, $entityModel){
        $request->request->set('entityModel', $entityModel);
    }

}