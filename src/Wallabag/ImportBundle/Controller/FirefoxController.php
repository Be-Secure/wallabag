<?php

namespace Wallabag\ImportBundle\Controller;

use Craue\ConfigBundle\Util\Config;
use OldSound\RabbitMqBundle\RabbitMq\Producer as RabbitMqProducer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wallabag\ImportBundle\Import\FirefoxImport;
use Wallabag\ImportBundle\Redis\Producer as RedisProducer;

class FirefoxController extends BrowserController
{
    private FirefoxImport $firefoxImport;
    private Config $craueConfig;
    private RabbitMqProducer $rabbitMqProducer;
    private RedisProducer $redisProducer;

    public function __construct(FirefoxImport $firefoxImport, Config $craueConfig, RabbitMqProducer $rabbitMqProducer, RedisProducer $redisProducer)
    {
        $this->firefoxImport = $firefoxImport;
        $this->craueConfig = $craueConfig;
        $this->rabbitMqProducer = $rabbitMqProducer;
        $this->redisProducer = $redisProducer;
    }

    /**
     * @Route("/firefox", name="import_firefox")
     */
    public function indexAction(Request $request, TranslatorInterface $translator)
    {
        return parent::indexAction($request, $translator);
    }

    /**
     * {@inheritdoc}
     */
    protected function getImportService()
    {
        if ($this->craueConfig->get('import_with_rabbitmq')) {
            $this->firefoxImport->setProducer($this->rabbitMqProducer);
        } elseif ($this->craueConfig->get('import_with_redis')) {
            $this->firefoxImport->setProducer($this->redisProducer);
        }

        return $this->firefoxImport;
    }

    /**
     * {@inheritdoc}
     */
    protected function getImportTemplate()
    {
        return '@WallabagImport/Firefox/index.html.twig';
    }
}
