<?php

namespace Vuravel\Form;

use Vuravel\Catalog\Components\Traits\DoesSorting;
use Vuravel\Form\Component;

class Trigger extends Component
{
    use Traits\LabelInfoComment;
    use Traits\RelatesToFormSubmission;
    use DoesSorting;
    use Traits\TriggerStyles;
}