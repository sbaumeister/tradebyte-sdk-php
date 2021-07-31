<?php

namespace Tradebyte\Stock\Model;

use SimpleXMLElement;

/**
 * @package Tradebyte
 */
class Article
{
    /**
     * @var string
     */
    protected $articleNumber;

    /**
     * @var integer
     */
    protected $stock;

    /**
     * @var string
     */
    protected $warehouseKey;

    /**
     * @return string|null
     */
    public function getArticleNumber(): ?string
    {
        return $this->articleNumber;
    }

    /**
     * @param string $articleNumber
     * @return Article
     */
    public function setArticleNumber(string $articleNumber): Article
    {
        $this->articleNumber = $articleNumber;
        return $this;
    }

    /**
     * @return integer|null
     */
    public function getStock(): ?int
    {
        return $this->stock;
    }

    /**
     * @param integer $stock
     * @return Article
     */
    public function setStock(int $stock): Article
    {
        $this->stock = $stock;
        return $this;
    }

    /**
     * @return string
     */
    public function getWarehouseKey(): ?string
    {
        return $this->warehouseKey;
    }

    /**
     * @param string $warehouseKey
     * @return Article
     */
    public function setWarehouseKey(string $warehouseKey): Article
    {
        $this->warehouseKey = $warehouseKey;
        return $this;
    }

    /**
     * @param SimpleXMLElement $xmlElement
     */
    public function fillFromSimpleXMLElement(SimpleXMLElement $xmlElement): void
    {
        $this->setArticleNumber((string)$xmlElement->A_NR);
        $this->setStock((int)$xmlElement->A_STOCK);
        if (isset($xmlElement->A_STOCK['key'])) {
            $this->setWarehouseKey((string)$xmlElement->A_STOCK['key']);
        }
    }

    /**
     * @return mixed[]
     */
    public function getRawData()
    {
        return [
            'article_number' => $this->getArticleNumber(),
            'stock' => $this->getStock(),
            'warehouse_key' => $this->getWarehouseKey(),
        ];
    }
}
