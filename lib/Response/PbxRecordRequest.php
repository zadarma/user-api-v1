<?php


namespace Zadarma_API\Response;


class PbxRecordRequest extends Response
{
    /** @var string The link to the file with the conversation. */
    public $link;

    /** @var string[] The links to the files with the conversation. Filled if call_id was not specified in request. */
    public $links;

    /** @var string Until what time will the link work. */
    public $lifetime_till;
}