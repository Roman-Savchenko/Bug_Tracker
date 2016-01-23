<?php

namespace Acme\BugBundle\Tests\Form\Type;

use Acme\BugBundle\Form\Type\ProjectType;
use Acme\BugBundle\Entity\Project;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

class ProjectTypeTest extends TypeTestCase
{
    protected function setUp()
    {
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->getFormFactory();
    }

    protected function getExtensions()
    {
        $mockEntityType = $this->getMock('Symfony\Bridge\Doctrine\Form\Type\EntityType');

        $mockEntityType->expects($this->any())->method('getName')
            ->will($this->returnValue('entity'));


        return array(new ValidatorExtension(array(
            $mockEntityType->getName() => $mockEntityType,
        ), array()));

    }
    public function testSubmitValidData()
    {
        $formData = array(
            'label' => 'test',
            'summary' => 'test2',
        );

        $type = new ProjectType();
        $form = $this->factory->create($type);

//        $object = Project::fromArray($formData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
//        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}