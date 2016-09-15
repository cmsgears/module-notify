<?php
namespace cmsgears\notify\common\models\base;

class NotifyTables {

    // Entities -------------

    const TABLE_ACTIVITY			= 'cmg_notify_activity';
    const TABLE_EVENT				= 'cmg_notify_event';
    const TABLE_NOTIFICATION		= 'cmg_notify_notification';

    // Resources ------------

    const TABLE_EVENT_REMINDER		= 'cmg_notify_event_reminder';

    // Mappers & Traits -----

    const TABLE_EVENT_PARTICIPANT	= 'cmg_notify_event_participant';
}

?>