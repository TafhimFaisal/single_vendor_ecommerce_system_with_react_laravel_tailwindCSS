<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest
{
    public $request;

    public function __construct(array $request)
    {
        $this->request = $request;
        $this->validator();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return  [
            'address'   => 'required',
            'email'     => 'required',
            'phone'     => 'required',
            'status'    => '',
            'user_id'   => 'required'
        ];
    }

    public function message()
    {
        return  [

        ];
    }

    public function validator()
    {

        $this->validator =  $this->authorize() ? Validator::make(
                                $this->request,
                                $this->rules(),
                                $this->message()
                            ) : 'unauthorised' ;
    }
}
