<?php

namespace App;

enum LectureStatus: string
{
    case PENDING = 'Pending';
    case ONGOING = 'Ongoing';
    case COMPLETED = 'Completed';
}
