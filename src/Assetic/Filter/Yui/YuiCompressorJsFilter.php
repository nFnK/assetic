<?php

namespace Assetic\Filter\Yui;

use Assetic\Asset\AssetInterface;

/**
 * Javascript YUI compressor filter.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class YuiCompressorJsFilter extends BaseYuiCompressorFilter
{
    private $nomunge;
    private $preserveSemi;
    private $disableOptimizations;

    public function setNomunge($nomunge = true)
    {
        $this->nomunge = $nomunge;
    }

    public function setPreserveSemi($preserveSemi)
    {
        $this->preserveSemi = $preserveSemi;
    }

    public function setDisableOptimizations($disableOptimizations)
    {
        $this->disableOptimizations = $disableOptimizations;
    }

    public function filterDump(AssetInterface $asset)
    {
        $options = array();

        if ($this->nomunge) {
            $options[] = '--nomunge';
        }

        if ($this->preserveSemi) {
            $options[] = '--preserve-semi';
        }

        if ($this->disableOptimizations) {
            $options[] = '--disable-optimizations';
        }

        $asset->setBody($this->compress($asset->getBody(), 'js', $options));
    }
}
