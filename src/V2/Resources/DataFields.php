<?php

declare(strict_types=1);

namespace Dotdigital\V2\Resources;

use Dotdigital\Resources\AbstractResource;
use Dotdigital\V2\Models\DataFieldList;
use Dotdigital\V2\Models\DataField;

class DataFields extends AbstractResource
{
    public const RESOURCE_BASE = '/data-fields';

    /**
     * @return DataFieldList
     * @throws \Http\Client\Exception
     * @throws \Exception
     */
    public function show()
    {
        return new DataFieldList($this->get(self::RESOURCE_BASE));
    }

    /**
     * @return DataField
     * @throws \Http\Client\Exception
     * @throws \Exception
     */
    public function createDataField(string $name, string $type): DataField
    {
        $response = $this->post(
            self::RESOURCE_BASE,
            [
                "name" => $name,
                "type" => $type,
                "visibility" => 'Private',
                "defaultValue" => ''
            ]
        );

        return new DataField($response);
    }
}
