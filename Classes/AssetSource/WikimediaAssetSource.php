<?php
namespace DL\AssetSource\Wikimedia\AssetSource;

/*
 * This file is part of the DL.AssetSource.Wikimedia package.
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use DL\AssetSource\Wikimedia\Api\WikimediaClient;
use Neos\Media\Domain\Model\AssetSource\AssetProxyRepositoryInterface;
use Neos\Media\Domain\Model\AssetSource\AssetSourceInterface;
use Neos\Media\Domain\Model\AssetSource\Neos\NeosAssetProxyRepository;
use Neos\Utility\Arrays;

final class WikimediaAssetSource implements AssetSourceInterface
{
    /**
     * @var WikimediaClient
     */
    protected $wikimediaClient = null;

    /**
     * @var string
     */
    private $assetSourceIdentifier;

    /**
     * @var NeosAssetProxyRepository
     */
    private $assetProxyRepository;

    /**
     * @var array
     */
    protected $assetSourceOptions;

    /**
     * PexelsAssetSource constructor.
     * @param string $assetSourceIdentifier
     * @param array $assetSourceOptions
     */
    public function __construct(string $assetSourceIdentifier, array $assetSourceOptions)
    {
        $this->assetSourceIdentifier = $assetSourceIdentifier;
        $this->assetSourceOptions = $assetSourceOptions;
    }

    /**
     * This factory method is used instead of a constructor in order to not dictate a __construct() signature in this
     * interface (which might conflict with an asset source's implementation or generated Flow proxy class).
     *
     * @param string $assetSourceIdentifier
     * @param array $assetSourceOptions
     * @return AssetSourceInterface
     */
    public static function createFromConfiguration(string $assetSourceIdentifier, array $assetSourceOptions): AssetSourceInterface
    {
        return new static($assetSourceIdentifier, $assetSourceOptions);
    }

    /**
     * A unique string which identifies the concrete asset source.
     * Must match /^[a-z][a-z0-9-]{0,62}[a-z]$/
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->assetSourceIdentifier;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return 'Wikipedia';
    }

    /**
     * @return AssetProxyRepositoryInterface
     */
    public function getAssetProxyRepository(): AssetProxyRepositoryInterface
    {
        if ($this->assetProxyRepository === null) {
            $this->assetProxyRepository = new WikimediaAssetProxyRepository($this);
        }

        return $this->assetProxyRepository;
    }

    /**
     * @return WikimediaClient
     */
    public function getWikimediaClient(): WikimediaClient
    {
        if ($this->wikimediaClient === null) {
            $this->wikimediaClient = new WikimediaClient($this->getOption('domain'));
        }

        return $this->wikimediaClient;
    }

    /**
     * @param string $optionPath
     * @return mixed
     */
    public function getOption(string $optionPath)
    {
        return Arrays::getValueByPath($this->assetSourceOptions, $optionPath);
    }

    /**
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return true;
    }
}
