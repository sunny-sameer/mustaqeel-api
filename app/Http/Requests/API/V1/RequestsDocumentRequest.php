<?php

namespace App\Http\Requests\API\V1;

use App\Http\Requests\API\V1\Traits\FailedValidationTrait;
use App\Repositories\V1\Admin\GenericInterface;
use App\Repositories\V1\Requests\RequestsInterface;
use Illuminate\Foundation\Http\FormRequest;

class RequestsDocumentRequest extends FormRequest
{
    use FailedValidationTrait;
    protected $requestsInterface;
    protected $genericInterface;

    /**
     * Create a new form request instance.
     */
    public function __construct(GenericInterface $genericInterface, RequestsInterface $requestsInterface)
    {
        parent::__construct();
        $this->requestsInterface = $requestsInterface;
        $this->genericInterface = $genericInterface;
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $validation = [];

        $request = $this->requestsInterface->getRequest($this->input('id'));

        $catSlug = null;
        if(isset($request->category->slug)) {
            $catSlug = $request->category->slug;
        }

        $ff = $this->genericInterface->getSingleFormField($this->input('key'),$catSlug);

        if(isset($ff->id)){
            if($ff->formMetas->isRequired){
                $validation[] = 'required';
            }else{
                $validation[] = 'nullable';
            }
        }else{
            return [
                'id' => 'required|exists:requests,id',
                'key' => 'required|exists:form_fields,slug',
                'document' => 'required|max:2048|mimes:png,jpeg,jpg,pdf,doc,docx',
            ];
        }

        $validations[] = 'max:2048';
        $validation[] = 'mimes:'.$ff->extensions ?? 'png,jpeg,jpg,pdf,doc,docx';

        return [
            'id' => 'required|exists:requests,id',
            'key' => 'required|exists:form_fields,slug',
            'document' => $validations,
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'The request id is required.',
            'id.exists' => 'The request id is invalid.',

            'key.required' => 'The key is required.',
            'key.exists' => 'The key is invalid.',

            'document.required' => 'The document is required.',
            'document.max' => 'The document may not be greater than 2 MB.',
            'document.mimes' => 'The document must be a PNG, JPG, or JPEG, PDF, DOC, DOCX file.',
        ];
    }

    /**
     * Always return JSON for APIs.
     */
    public function wantsJson(): bool
    {
        return true;
    }
}
