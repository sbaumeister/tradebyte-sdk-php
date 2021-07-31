<?php

namespace Tradebyte\Stock\Tbstock;

use InvalidArgumentException;
use Tradebyte\Stock\Model\Article;
use XMLReader;
use Tradebyte\Base;

/**
 * @package Tradebyte
 */
class Iterator extends Base\Iterator implements \Iterator
{
    /**
     * @var string
     */
    protected $changeDate;

    /**
     * @return string
     */
    public function getChangeDate(): ?string
    {
        if (!$this->getIsOpen()) {
            $this->open();
        }

        return $this->changeDate;
    }

    /**
     * @return Article
     */
    public function current(): Article
    {
        return $this->current;
    }

    /**
     * @return void
     */
    public function next()
    {
        while ($this->xmlReader->read()) {
            if (
                $this->xmlReader->nodeType == XMLReader::ELEMENT
                && $this->xmlReader->depth === 1
                && $this->xmlReader->name == 'ARTICLE'
            ) {
                $xmlElement = new \SimpleXMLElement($this->xmlReader->readOuterXML());
                $model = new Article();
                $model->fillFromSimpleXMLElement($xmlElement);
                $this->current = $model;
                return;
            }
        }

        $this->current = null;
    }

    /**
     * @return void
     */
    public function open()
    {
        if ($this->getIsOpen()) {
            $this->close();
        }

        if ($this->openLocalFilepath) {
            $this->xmlReader = new XMLReader();

            if (@$this->xmlReader->open($this->url) === false) {
                throw new InvalidArgumentException('can not open file ' . $this->url);
            }
        } else {
            $this->xmlReader = $this->client->getRestClient()->getXML($this->url, $this->filter);
        }

        while ($this->xmlReader->read()) {
            if (
                $this->xmlReader->nodeType == XMLReader::ELEMENT
                && $this->xmlReader->depth == 0
                && $this->xmlReader->name == 'TBSTOCK'
            ) {
                $this->changeDate = $this->xmlReader->getAttribute('changedate');
                break;
            }
        }

        $this->isOpen = true;
    }
}
