<?php

namespace rnpagebuilder\Pages;

use phpDocumentor\Reflection\Type;
use rnpagebuilder\core\PageBase;

class Help extends PageBase
{

    public function Render()
    {
        ?>
<div style="padding: 10px">
    <div style="padding: 20px; background-color: white; border: 1px solid #ccc;max-width: 900px;">
        <h1>Documentation</h1>
        <p>If you are not sure how to do something please check the <a target="_blank" href="https://formwiz.rednao.com/documentation/">documentation here</a></p>
    </div>

    <div style="padding: 20px; background-color: white; border: 1px solid #ccc;max-width: 900px;margin-top: 20px">
        <h1>Support</h1>
        <p>If you don't fine the answer of your question in the documentation or have any suggestion or issue <a target="_blank" href="https://formwiz.rednao.com/contact-us/">let us know</a></p>
    </div>
</div>

<?php

    }
}