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
 * ElementBanner
 * A banner content block containing HTML content field, an image and a link
 * @author Mark
 * @author James
 */
class ElementBanner extends BaseElement
{

    /**
     * @var string
     */
    private static $icon = "font-icon-block-banner";

    /**
     * @var string
     */
    private static $table_name = "ElementBanner";

    /**
     * @var string
     */
    private static $title = "Banner";

    /**
     * @var string
     */
    private static $description = "Display an banner";

    /**
     * @var string
     */
    private static $singular_name = "Banner";

    /**
     * @var string
     */
    private static $plural_name = "Banners";

    /**
     * @var array
     */
    private static $allowed_file_types = ["jpg", "jpeg", "gif", "png", "webp"];

    /**
     * Element block type
     */
    public function getType()
    {
        return _t(__CLASS__ . ".BlockType", "Banner");
    }

    /**
     * @var array
     */
    private static $db = [
        'HTML' => 'HTMLText'
    ];

    /**
     * @var array
     */
    private static $has_one = [
        "Image" => Image::class,
        "BannerLink" => Link::class
    ];

    /**
     * @var array
     */
    private static $summary_fields = [
        "Image.CMSThumbnail" => "Image",
        "Title" => "Title",
    ];

    /**
     * @var array
     */
    private static $owns = [
        "Image"
    ];

    /**
     * Return allowed file types for image upload field
     */
    public function getAllowedFileTypes()
    {
        $types = $this->config()->get("allowed_file_types");
        if (empty($types)) {
            $types = ['jpg', 'jpeg', 'gif', 'png', 'webp'];
        }
        $types = array_unique($types);
        return $types;
    }

    /**
     * Return fields for CMS
     */
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
