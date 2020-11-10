{*-------------------------------------------------------+
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
+-------------------------------------------------------*}

{crmScope extensionKey='de.ekir.events'}

  <h3>{ts}Presbyter-Tag{/ts}</h3>
  <div class="crm-section">
    <div class="label">{$form.presbyter_gtac.label}</div>
    <div class="content">{$form.presbyter_gtac.html}</div>
    <div class="clear"></div>
  </div>

  {* FOOTER *}
  <br>
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
{/crmScope}
