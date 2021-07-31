<?php

namespace Tradebyte\Stock;

use Tradebyte\Client;
use Tradebyte\Stock\Model\Article;
use XMLWriter;

/**
 * @package Tradebyte
 */
class Handler
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param mixed[] $filter
     * @return Tbstock
     */
    public function getStockList($filter = []): Tbstock
    {
        return new Tbstock($this->client, 'stock/', $filter);
    }

    /**
     * @param string $filePath
     * @return Tbstock
     */
    public function getStockListFromFile(string $filePath): Tbstock
    {
        return new Tbstock($this->client, $filePath, [], true);
    }

    /**
     * @param string $filePath
     * @param array $filter
     * @return boolean
     */
    public function downloadStockList(string $filePath, array $filter = []): bool
    {
        return $this->client->getRestClient()->downloadFile($filePath, 'stock/', $filter);
    }

    /**
     * @param string $filePath
     * @return string
     */
    public function updateStockFromStockList(string $filePath): string
    {
        return $this->client->getRestClient()->postXMLFile($filePath, 'articles/stock');
    }

    /**
     * @param Article[] $stockArray
     * @return string
     */
    public function updateStock(array $stockArray): string
    {
        $xml = $this->toXml($stockArray);
        return $this->client->getRestClient()->postXML('articles/stock', $xml);
    }

    /**
     * @param array $stockArray
     * @return string
     */
    public function toXml(array $stockArray): string
    {
        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startElement('TBSTOCK');

        foreach ($stockArray as $stock) {
            $writer->startElement('ARTICLE');
            $writer->writeElement('A_NR', $stock->getArticleNumber());
            $writer->startElement('A_STOCK');
            if (!is_null($stock->getWarehouseKey())) {
                $writer->writeAttribute('identifier', 'name');
                $writer->writeAttribute('key', $stock->getWarehouseKey());
            }
            $writer->writeRaw($stock->getStock());
            $writer->endElement();
            $writer->endElement();
        }

        $writer->endElement();

        return $writer->outputMemory();
    }
}
