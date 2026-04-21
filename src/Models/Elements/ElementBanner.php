<?php

namespace NSWDPC\Elemental\Models\Banner;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use gorriecoe\Link\Models\Link;
use NSWDPC\InlineLinker\InlineLinkCompositeField;

/**
 * ElementBanner
 * A banner content block containing HTML content field, an image and a link
 * @author Mark
 * @author James
 * @property ?string $HTML
 * @property int $ImageID
 * @property int $BannerLinkID
 * @method \SilverStripe\Assets\Image Image()
 * @method \gorriecoe\Link\Models\Link BannerLink()
 */
class ElementBanner extends BaseElement
{
    private static string $icon = "font-icon-block-banner";

    private static string $table_name = "ElementBanner";

    private static string $title = "Banner";

    private static string $description = "Display a banner";

    private static string $singular_name = "Banner";

    private static string $plural_name = "Banners";

    private static array $allowed_file_types = ["jpg", "jpeg", "gif", "png", "webp"];

    /**
     * Element block type
     */
    #[\Override]
    public function getType()
    {
        return _t(self::class . ".BlockType", "Banner");
    }

    private static array $db = [
        'HTML' => 'HTMLText'
    ];

    private static array $has_one = [
        "Image" => Image::class,
        "BannerLink" => Link::class
    ];

    private static array $summary_fields = [
        "Image.CMSThumbnail" => "Image",
        "Title" => "Title",
    ];

    private static array $owns = [
        "Image"
    ];

    /**
     * Return allowed file types for image upload field
     */
    public function getAllowedFileTypes(): array
    {
        $types = $this->config()->get("allowed_file_types");
        if (empty($types)) {
            $types = ['jpg', 'jpeg', 'gif', 'png', 'webp'];
        }
        return array_unique($types);
    }

    /**
     * Return fields for CMS
     */
    #[\Override]
    public function getCMSFields()
    {

        $this->beforeUpdateCMSFields(function ($fields): void {
            $fields->removeByName(['BannerLinkID']);
            $fields->addFieldsToTab("Root.Main", [
                HTMLEditorField::create(
                    'HTML',
                    _t(
                        self::class . '.HTML',
                        'Content'
                    )
                ),
                UploadField::create(
                    "Image",
                    _t(self::class . ".SLIDE_IMAGE", "Image")
                )
                ->setAllowedExtensions($this->getAllowedFileTypes())
                ->setIsMultiUpload(false)
                ->setDescription(
                    _t(
                        self::class . "ALLOWED_FILE_TYPES",
                        "Allowed file types: {types}",
                        [
                            'types' => implode(",", $this->getAllowedFileTypes())
                        ]
                    )
                ),
                InlineLinkCompositeField::create(
                    'BannerLink',
                    _t(
                        self::class . 'LINK',
                        'Link'
                    ),
                    $this->owner
                ),

            ]);
        });
        return parent::getCMSFields();
    }
}
