<input type='checkbox'
       class="ssicheckbox"
       id='<?php
       echo $this->getFieldName() ?>'
       name='<?php echo $this->getFieldName() ?>'
       value='1'
       <?php echo $this->getValue() === '1' ? 'checked' : '' ?>
       <?php echo $this->isHidden() ? 'hidden' : '' ?>
/>
