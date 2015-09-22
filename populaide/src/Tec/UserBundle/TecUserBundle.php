<?php

namespace Tec\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TecUserBundle extends Bundle
{
    public function getParent(){
        return 'FOSUserBundle';
    }

}
