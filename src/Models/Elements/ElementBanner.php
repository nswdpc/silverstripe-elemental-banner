<?php

namespace NSWDPC\Elemental\Models\Banner;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\LinkField\Models\Link as CoreLink;
use SilverStripe\LinkField\Form\LinkField as CoreLinkField;

/**
 * ElementBanner
 * A banner content block containing HTML content field, an image and a link
 * @author Mark
 * @author James
 * @property ?string $HTML
 * @property int $ImageID
 * @method \SilverStripe\Assets\Image Image()
 * @method \SilverStripe\LinkField\Models\Link CoreBannerLink()
 * @property int $CoreBannerLinkID
 */
class ElementBanner extends BaseElement
{
    private static string $icon = "font-icon-block-banner";

    private static string $table_name = "ElementBanner";

    private static string $title = "Banner";

    private static string $class_description = "Display a banner";

    private static string $singular_name = "Banner";

    private static string $plural_name = "Banners";

    private static array $allowed_file_types = ["jpg", "jpeg", "gif", "png", "webp"];

    /**
     * This element is not inline editable until linkfield is migrated
     */
    private static bool $inline_editable = false;

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
        "CoreBannerLink" => CoreLink::class
    ];

    private static array $summary_fields = [
        "Image.CMSThumbnail" => "Image",
        "Title" => "Title",
    ];

    private static array $owns = [
        "Image",
        "CoreBannerLink"
    ];

    private static array $cascade_deletes = [
        "CoreBannerLink"
    ];

    private static array $cascade_duplicates = [
        "CoreBannerLink"
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
            $fields->removeByName(['CoreBannerLink']);
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
                $this->getLinkField()
            ]);
        });
        return parent::getCMSFields();
    }

    /**
     * Return the field used to render the link
     * based on migration state
     */
    protected function getLinkField(): CoreLinkField
    {
        return CoreLinkField::create('CoreBannerLink', _t(self::class . 'LINK', 'Link'));
    }

    /**
     * Template method
     */
    public function DerivedBannerLink(): CoreLink
    {
        return $this->getDerivedBannerLink();
    }

    /**
     * Returns the relevant link based on migration status
     */
    public function getDerivedBannerLink(): CoreLink
    {
        return $this->CoreBannerLink();
    }

    /**
     * Provide backwards compatible method for 2.x
     */
    public function BannerLink(): CoreLink
    {
        return $this->getBannerLink();
    }

    /**
     * Provide backwards compatible method for 2.x
     */
    public function getBannerLink(): CoreLink
    {
        return $this->getDerivedBannerLink();
    }

}
