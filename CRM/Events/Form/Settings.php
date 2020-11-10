<?php
/*-------------------------------------------------------+
| SYSTOPIA Remote Event Extension                        |
| Copyright (C) 2020 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

use CRM_Events_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Events_Form_Settings extends CRM_Core_Form
{
    public function buildQuickForm()
    {
        $this->add('wysiwyg', 'presbyter_gtac', E::ts('Zusätzliche Nutzervereinbarung'));

        $this->addButtons(
            [
                [
                    'type' => 'submit',
                    'name' => E::ts('Save'),
                    'isDefault' => true,
                ],
            ]
        );

        $this->setDefaults([
            'presbyter_gtac' => Civi::settings()->get('presbyter_gtac')
        ]);

        parent::buildQuickForm();
    }

    public function postProcess()
    {
        $values = $this->exportValues();
        Civi::settings()->set('presbyter_gtac', $values['presbyter_gtac']);
        parent::postProcess();
    }

}
