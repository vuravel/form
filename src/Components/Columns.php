<?php

namespace Vuravel\Form\Components;

use Vuravel\Form\Layout;

class Columns extends Layout
{
    use Traits\VerticalAlignmentTrait;

    public $component = 'Columns';

    public $data = [
        //'alignClass' => 'vlAlignCenterMd',
        'breakpoint' => 'md'
    ];

    /**
     * The content will remain in columns no matter the viewport - i.e. the columns will not rearrange, even on mobile.
     *
     * @return     self
     */
    public function notResponsive()
    {
        return $this->breakpoint();
    }

    /**
     * The columns will re-arrange at that specific breakpoint. The default breakpoint is 'md'.
     *
     * @param      string  $breakpoint  A breakpoint value: 'xs', 'sm', 'md', 'lg', 'xl'.
     *
     * @return     self   
     */
    public function breakpoint($breakpoint = null)
    {
        $this->data(['breakpoint' => $breakpoint]);
        return $this;
    }

    /**
     * Removes the gutters (no padding between the columns).
     *
     * @return     self 
     */
    public function noGutters()
    {
        $this->data(['guttersClass' => 'no-gutters']);
        return $this;
    }

}
