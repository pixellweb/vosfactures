<?php

namespace PixellWeb\Vosfactures\app\Enums;

enum Status: string
{
    case ISSUED = 'issued';
    case SENT = 'sent';
    case PAID = 'paid';
    case PARTIAL = 'partial';
    case REJECTED = 'rejected';
    case ACCEPTED = 'accepted';
}