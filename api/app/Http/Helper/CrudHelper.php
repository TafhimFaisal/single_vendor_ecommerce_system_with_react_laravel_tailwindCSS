<?php
namespace App\Http\Helper;
use Illuminate\Http\Request;
use Session;
use Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CrudHelper {

    protected $model;
    protected $upload;
    protected $images;
    protected $type;

    public function __construct($model,array $images = [],$type = null)
    {
        $this->model    = $model;
        $this->images   = $images;
        $this->type     = $type;
    }

    public function changeModel($model)
    {
        $this->model = $model;
    }

    public function changeType($type)
    {
        $this->type = $type;
    }

    public function get($id = null,array $query = [])
    {

        if($id){
            $data = $this->model->find($id);
            return response()->json([
                'message' => $this->type.' fatch Successfully',
                'data' => $data,
                'type' => 'get '.$this->type
            ],200);
        }

        if(!empty($query)){
            $data = $this->model->where($query)->get();
            return response()->json([
                'message' => $this->type.' fatch Successfully',
                'data' => $data,
                'type' => 'get '.$this->type
            ],200);
        }

        $data = $this->model->all();
        return response()->json([
            'message' => $this->type.' fatch Successfully',
            'data' => $data,
            'type' => 'get all '.$this->type
        ],200);

    }

    public function store($validat)
    {
        try {
            if($validat->validator->fails()){
                return response()->json([
                    'message' => 'somthing went wrong',
                    'data' => $validat->validator->errors(),
                    'validation' => false,
                    'type' => 'store '.$this->type
                ],400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'oops somthing went wrong !!!'
            ],401);
        }

        $data = $validat->validator->validate();

        // storing images
        if(!empty($this->images)){
            foreach ($this->images as $image) {
                if(isset($data[$image])){
                    $address = $this->storeImage($data[$image]);
                    $data[$image] = $address;
                }
            }
        }

        $data = $this->model->create($data);

        return response()->json([
            'message' => $this->type.' Stored Successfully',
            'data' => $data,
            'validation' => true,
            'type' => 'store '.$this->type
        ],200);

    }

    public function destroy($model)
    {
        if(!empty($this->images)){
            foreach ($this->images as $image) {
                if($model->$image){
                    $this->deleteImage($model->$image);
                }
            }
        }

        $model->delete();
        return response()->json([
            'message' => $this->type.' Deleted Successfully'
        ],200);

    }

    public function update($model,$validat)
    {
        try {
            if($validat->validator->fails()){
                return response()->json([
                    'message' => 'somthing went wrong',
                    'data' => $validat->validator->errors(),
                    'validation' => false,
                    'type' => 'update '.$this->type
                ],400);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'oops somthing went wrong !!!'
            ],401);
        }

        $data = $validat->validator->validate();
        if(!empty($this->images)){
            foreach ($this->images as $image) {
                if(isset($data[$image])){
                    if( strpos($data[$image],';base64,') > 0){
                        $this->deleteImage($model->$image);
                        $url = $this->storeImage($data[$image]);
                        $data[$image] = $url;
                    }
                }
            }
        }

        $model->update($data);
        return response()->json([
            'message' => $this->type.' is successfully updated.',
            'data' => $model,
            'validation' => true,
            'type' => 'update '.$this->type
        ],200);

    }

    public function storeImage($image)
    {
        $folderPath = $_SERVER['DOCUMENT_ROOT'].'\\images\\'.$this->type;
        $uuid = (string) Str::uuid();

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $file_name = $uuid.'product_image';
        $image_parts = explode(";base64,", $image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $image = $folderPath.'\\'.$file_name .'.'.$image_type;

        file_put_contents($image, $image_base64);
        return asset(str_replace( '\\','/',asset(explode($_SERVER['DOCUMENT_ROOT'].'\\',$image)[1])));

    }

    public function deleteImage($image)
    {
        $image = str_replace( "/","\\",str_replace( asset('/') ,$_SERVER['DOCUMENT_ROOT']."\\", $image) );
        if (file_exists($image)) {
            unlink($image);
        }
    }
}
