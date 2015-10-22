<?php

namespace Acme\BugBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeBugBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
