<?php
namespace FP\BEBundle\Services\FormHandlers;

use FP\BEBundle\Services\DomainManagers\BasicDomainManager;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class BasicFormHandler
{
    private $domainManager;
    public $errors = null;

    public function __construct(BasicDomainManager $domainManager, FormFactoryInterface $factory)
    {
        $this->domainManager = $domainManager;
        $this->builder = $factory;
    }

    public function handle(FormInterface $form, Request $request)
    {
        if(!$request->isMethod("POST"))
        {
            $this->errors[] = "Not a POST request";
            return false;
        }

        $form->handleRequest($request);

        $crop_arr = $request->query->all();

        if(!$form->isValid())
        {
            $this->errors[] = $form->getErrors();
            return false;
        }

        $obj = $form->getData();

        $crop_arr = empty($crop_arr) ? false : $crop_arr;
        $result = $this->save($obj, $crop_arr);
        if($result)
        {
            return $result;
        }
        else
        {
            return false;
        }
    }

    protected function save($obj, $crop_arr)
    {
        $obj_result = $this->domainManager->saveRecord($obj, $crop_arr);
        if($obj_result === false)
        {
            $this->error = $this->domainManager->error;
            return false;
        }
        else
        {
            return $obj_result;
        }
    }

    public function createForm($type, $obj)
    {
        if(!$obj)
        {
            return false;
        }
        $form = $this->builder->create($type, $obj);
        return $form;
    }

    public function handleEditForm(FormInterface $form, Request $request)
    {
        if(!$request->isMethod("POST"))
        {
            $this->errors[] = "Not a POST request";
            return false;
        }

        $form->handleRequest($request);

        $crop_arr = $request->query->all();

        if(!$form->isValid())
        {
            $this->errors[] = $form->getErrors();
            return false;
        }

        $obj = $form->getData();

        $crop_arr = empty($crop_arr) ? false : $crop_arr;
        $result = $this->save($obj, $crop_arr);
        if($result)
        {
            return $result;
        }
        else
        {
            return false;
        }
    }

    public function createDeleteForm()
    {
        return $this->builder->createBuilder()
            ->getForm();
    }

    public function handleDeleteForm($obj, Request $request)
    {
        $result = $this->domainManager->removeRecord($obj);

        if(!$result)
        {
            return false;
        }

        return $obj;
    }

    public function handleSearchForm(Request $request, $entity)
    {
        $search_query = $request->query->get("q");
        $target_entity = $entity;

        if($search_query != null && $target_entity != null)
        {
            $entities = $this->domainManager->search($search_query, $target_entity);
            return $entities;
        }
        else
        {
            $this->errors[] = "Search query and target entity are required.";
            return null;
        }

    }

}