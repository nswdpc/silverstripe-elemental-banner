<?php

namespace NSWDPC\Elemental\Models\Banner\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\Core\Config\Config;
use NSWDPC\Elemental\Models\Banner\ElementBanner;

class ElementBannerTest extends SapphireTest
{

    protected $usesDatabase = true;

    public function testAllowedFileTypes()
    {
        $allowed = ["jpg", "jpeg"];
        Config::modify()->set(
            ElementBanner::class,
            'allowed_file_types',
            $allowed
        );
        $element = ElementBanner::create();
        $this->assertEquals( $allowed, $element->getAllowedFileTypes() );
    }

}
