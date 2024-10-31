<?php


namespace rnpagebuilder\core\Managers\ExceptionManager;


use rnpagebuilder\core\Loader;

class ExceptionManager
{
    /** @var \Exception */
    public $Exception;
    /** @var Loader */
    public $Loader;
    public function __construct($loader,$exception)
    {
        $this->Loader=$loader;
        $this->Exception=$exception;
    }


    public function PrintErrorToScreen(){
        ob_start();
        ?>
        <div style="display: flex;flex-direction: column;justify-content: center;align-items: center;text-align: center;height: 100%;width: 100%;font-family: Verdana;">
            <div>

                <img style="margin: auto;" src="<?php echo esc_attr($this->Loader->URL)?>/images/error.png">
                <h1 style="font-size: 50px;">Sorry an error occurred =(</h1>
                <p><?php echo esc_html($this->Exception->getMessage())?></p>
                <p style="font-weight: bold;text-align: left;margin-top: 50px;margin-bottom: 5px;">How can i fix the issue?</p>
                <ul style="text-align: left;margin-top: 7px;">
                    <li style="margin-bottom: 5px">Please review the exception message and see if there is something you can do to fix it</li>
                    <li style="margin-bottom: 5px">If you get this error after previewing a page please use a live page instead of previewing it</li>
                    <li style="margin-bottom: 5px">If you enable the log you might find a more detailed information about the issue in this page and in the log</li>
                    <li style="margin-bottom: 5px">If you need assistance please contact support and send them the log information</li>
                </ul>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

}