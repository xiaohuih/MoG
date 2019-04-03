<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Encore\Admin\Form\Field;
use Encore\Admin\Form as FormBase;

class Form extends FormBase
{
    /**
     * Destroy data entity and remove files.
     *
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        collect(explode(',', $id))->filter()->each(function ($id) {
            $model = get_class($this->model)::findOrFail($id);

            if ($this->isSoftDeletes && $model->trashed()) {
                $this->deleteFiles($model, true);
                $model->forceDelete();

                return;
            }

            $this->deleteFiles($model);
            $model->delete();
        });

        return true;
    }
    
    /**
     * Store a new record.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $data = Input::all();

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            return back()->withInput()->withErrors($validationMessages);
        }

        if (($response = $this->prepare($data)) instanceof Response) {
            return $response;
        }
        
        $inserts = $this->prepareInsert($this->updates);

        foreach ($inserts as $column => $value) {
            $this->model->setAttribute($column, $value);
        }

        if (($response = $this->model->save()) instanceof Response) {
            return $response;
        }

        if (($response = $this->callSaved()) instanceof Response) {
            return $response;
        }

        if ($response = $this->ajaxResponse(trans('admin.save_succeeded'))) {
            return $response;
        }

        return $this->redirectAfterStore();
    }

    /**
     * Handle update.
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id, $data = null)
    {
        $data = ($data) ?: Input::all();

        $isEditable = $this->isEditable($data);

        $data = $this->handleEditable($data);

        $data = $this->handleFileDelete($data);

        if ($this->handleOrderable($id, $data)) {
            return response([
                'status'  => true,
                'message' => trans('admin.update_succeeded'),
            ]);
        }

        /* @var Model $this->model */
        $builder = $this->model();

        if ($this->isSoftDeletes) {
            $builder = $builder->withTrashed();
        }

        //$this->model = $builder->with($this->getRelations())->findOrFail($id);

        $this->setFieldOriginalValue();

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            if (!$isEditable) {
                return back()->withInput()->withErrors($validationMessages);
            } else {
                return response()->json(['errors' => array_dot($validationMessages->getMessages())], 422);
            }
        }

        if (($response = $this->prepare($data)) instanceof Response) {
            return $response;
        }

        DB::transaction(function () {
            $updates = $this->prepareUpdate($this->updates);

            foreach ($updates as $column => $value) {
                /* @var Model $this->model */
                $this->model->setAttribute($column, $value);
            }

            $this->model->save();

            $this->updateRelation($this->relations);
        });

        if (($result = $this->callSaved()) instanceof Response) {
            return $result;
        }

        if ($response = $this->ajaxResponse(trans('admin.update_succeeded'))) {
            return $response;
        }

        return $this->redirectAfterUpdate($id);
    }

    /**
     * Set all fields value in form.
     *
     * @param $id
     *
     * @return void
     */
    protected function setFieldValue($id)
    {
        $relations = $this->getRelations();

        $builder = $this->model();

        if ($this->isSoftDeletes) {
            $builder = $builder->withTrashed();
        }

        $this->model = get_class($builder)::findOrFail($id);

        $this->callEditing();

//        static::doNotSnakeAttributes($this->model);

        $data = $this->model->toArray();

        $this->builder->fields()->each(function (Field $field) use ($data) {
            if (!in_array($field->column(), $this->ignored)) {
                $field->fill($data);
            }
        });
    }
}