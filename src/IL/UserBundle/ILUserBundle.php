<?php

namespace IL\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ILUserBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }
}
