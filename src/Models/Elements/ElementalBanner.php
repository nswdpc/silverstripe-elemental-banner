<?php
namespace NSWDPC\Elemental\Models\Banner;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use gorriecoe\Link\Models\Link;
use NSWDPC\InlineLinker\InlineLinkCompositeField;

/**
 * ElementBanner adds a banner
 */
class ElementBanner extends BaseElement
{
    private static $icon = "font-icon-block-banner";

    private static $table_name = "ElementBanner";

    private static $title = "Banner";
    private static $description = "Display an banner";

    private static $singular_name = "Banner";
    private static $plural_name = "Banners";

    private static $allowed_file_types = ["jpg", "jpeg", "gif", "png", "webp"];

    public function getType()
    {
        return _t(__CLASS__ . ".BlockType", "Banner");
    }

    private static $db = [
        'HTML' => 'HTMLText'
    ];

    private static $has_one = [
        "Image" => Image::class,
        'BannerLink' => Link::class
    ];

    private static $summary_fields = [
        "Image.CMSThumbnail" => "Image",
        "Title" => "Title",
    ];

    private static $owns = ["Image"];

    public function getAllowedFileTypes()
    {
        $types = $this->config()->get("allowed_file_types");
        if (empty($types)) {
            $types = ['jpg', 'jpeg', 'gif', 'png', 'webp'];
        }
        $types = array_unique($types);
        return $types;
    }

    public function getCMSFields()
    {

        $this->beforeUpdateCMSFields(function ($fields) {
            $fields->removeByName(['BannerLinkID']);
            $fields->addFieldsToTab("Root.Main", [
                HTMLEditorField::create(
                    'HTML',
                    _t(
                        __CLASS__ . '.HTML',
                        'Content'
                    )
                ),
                UploadField::create(
                    "Image",
                    _t(__CLASS__ . ".SLIDE_IMAGE", "Image")
                )
                ->setAllowedExtensions($this->getAllowedFileTypes())
                ->setIsMultiUpload(false)
                ->setDescription(
                    _t(
                        __CLASS__ . "ALLOWED_FILE_TYPES",
                        "Allowed file types: {types}",
                        [
                            'types' => implode(",", $this->getAllowedFileTypes())
                        ]
                    )
                ),
                InlineLinkCompositeField::create(
                    'BannerLink',
                    _t(
                        __CLASS__ . 'LINK', 'Link'
                    ),
                    $this->owner
                ),

            ]);
        });
        return parent::getCMSFields();
    }
}
