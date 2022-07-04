<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Module;

class ModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $module = Module::find($this->id);

        $data = [];
        $data['id'] = $this->id;
        $data['template_id'] = $this->template_id;
        $data['index'] = $this->index;
        if($module){
            foreach (['text', 'video', 'image', 'audio'] as $type){
                if($module->$type() !== null){
                    $sources = $module->$type()->get();
                    foreach ($sources as $key => $src){
                        $key =  $type . '_' . ++$key;
                        $data[$key] = $src->getSource();
                    }
                }
            }
        }


        return $data;
    }
}
