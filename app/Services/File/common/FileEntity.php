<?php

namespace App\Services\File\common;

use App\Models\Course;
use Illuminate\Http\Request;

class FileEntity {

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getEntity($request)
    {

//dd($request->file());
        if($request['course_id']){
            $this->setEntity('course');
            $this->setEntityId($request['course_id']);
            $this->setEntityModel(Course::find($request['course_id']));
        } else {
            $this->setEntity(null);
            $this->setEntityId(null);
            $this->setEntityModel(null);
        }

    }

    private function setEntity($entity){
        $this->request->request->set('entity', $entity);
    }

    private function setEntityId($entityId){
        $this->request->request->set('entityId', $entityId);
    }

    private function setEntityModel($entityModel){
        $this->request->request->set('entityModel', $entityModel);
    }

}