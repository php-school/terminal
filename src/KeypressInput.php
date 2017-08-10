<?php

namespace PhpSchool\Terminal;

use MyCLabs\Enum\Enum;

/**
 * @author Michael Woodward <mikeymike.mw@gmail.com>
 *
 * @method static UP()
 * @method static DOWN()
 * @method static ENTER()
 */
class KeypressInput extends Enum
{
    const UP    = 'up';
    const DOWN  = 'down';
    const ENTER = 'enter';
}
