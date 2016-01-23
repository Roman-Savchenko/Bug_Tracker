<?php

namespace Acme\BugBundle\Tests\Form\Type;

use Acme\BugBundle\Form\Type\CommentType;
use Acme\BugBundle\Entity\Comment;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{
    public function __construct()
    {
        $this->type = new CommentType();
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'comment'=>'Comment',
        );

        $form = $this->factory->create($this->type);
        $form->submit($formData);

        $object = new EntityConfigModel();
        $object->setClassName('NewEntityClassName');

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

}