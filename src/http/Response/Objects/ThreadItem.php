<?php

namespace InstagramAPI;

class ThreadItem extends Response
{
    /**
     * @var string
     */
    public $item_id;
    public $item_type;
    public $text;
    /**
     * @var string
     */
    public $user_id;
    public $timestamp;
}
