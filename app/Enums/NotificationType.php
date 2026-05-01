<?php

namespace App\Enums;

class NotificationType
{
    const OFFER_ACCEPTED = 'offer_accepted';
    const ORDER_STATUS_CHANGED = 'order_status_changed';
    const NEW_FINAL_PRICE = 'new_final_price';
    const APPROVAL_REQUIRED = 'approval_required';
    const NEW_MESSAGE = 'new_message';
    const ACCOUNT_VERIFIED = 'account_verified';
    const ACCOUNT_BLOCKED = 'account_blocked';
    const ACCOUNT_UNBLOCKED = 'account_unblocked';
}