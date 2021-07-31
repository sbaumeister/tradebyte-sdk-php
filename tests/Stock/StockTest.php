<?php

namespace Tradebyte\Stock;

use Tradebyte\Base;
use Tradebyte\Client;
use Tradebyte\Stock\Model\Article;

/**
 * @package Tradebyte
 */
class StockTest extends Base
{
    /**
     * @return void
     */
    public function testGetStockListFromFile(): void
    {
        $stockHandler = (new Client())->getStockHandler();
        $catalog = $stockHandler->getStockListFromFile(__DIR__ . '/../files/stock.xml');
        $stockIterator = $catalog->getStock();
        $stockIterator->rewind();
        $stockModel = $stockIterator->current();

        $this->assertSame([
            'article_number' => 'a_nr_test',
            'stock' => 2,
            'warehouse_key' => null,
        ], $stockModel->getRawData());

        $this->assertEquals('1602870040', $catalog->getChangeDate());
    }

    /**
     * @return void
     */
    public function testStockObjectGetRawData(): void
    {
        $article = new Article();
        $article->setStock(20);
        $article->setArticleNumber('123456');
        $this->assertSame([
            'article_number' => '123456',
            'stock' => 20,
            'warehouse_key' => null,
        ], $article->getRawData());
    }

    public function testXml(): void
    {
        $article = new Article();
        $article->setStock(20);
        $article->setArticleNumber('123456');
        $article->setWarehouseKey('warehouse-1');
        $stockHandler = (new Client())->getStockHandler();
        $expectedXml = ltrim('
<TBSTOCK><ARTICLE><A_NR>123456</A_NR><A_STOCK identifier="name" key="warehouse-1">20</A_STOCK></ARTICLE></TBSTOCK>');
        $this->assertEquals($expectedXml, $stockHandler->toXml([$article]));
    }
}
