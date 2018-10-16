<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Security
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\Security\Console\Command;

use Magento\Framework\App\Config\Storage\Writer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Reset
 * @package Mageplaza\Security\Console\Command
 */
class Reset extends Command
{
    /**
     * @var Writer
     */
    protected $_writer;

    /**
     * @var array
     */
    public $pathsReset = [
        'blacklist' => 'security/black_white_list/black_list',
        'whitelist' => 'security/black_white_list/white_list'
    ];

    /**
     * Reset constructor.
     * @param Writer $writer
     * @param null $name
     */
    public function __construct(
        Writer $writer,
        $name = null
    )
    {
        $this->_writer = $writer;
        parent::__construct($name);
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('security:reset')
            ->setDescription('Reset Security Black-Whitelist')
            ->setDefinition([
                new InputArgument(
                    'value',
                    InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                    'Space-separated list of index types or omit to apply to all indexes.'
                )
            ]);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('value')) {
            $requestedTypes = $input->getArgument('value');
            $requestedTypes = array_filter(array_map('trim', $requestedTypes), 'strlen');
        }

        if (empty($requestedTypes)) {
            try {
                $list = '';
                foreach ($this->pathsReset as $key => $path) {
                    $this->reset($path);

                    $list .= ($list ? ', ' : '') . ucfirst($key);
                }
                $output->writeln('<info>' . $list . ' Reset Successfully!</info>');
            } catch (\Exception $e) {
                $output->writeln("<error>{$e->getMessage()}</error>");
            }

            return;
        }

        foreach ($requestedTypes as $item) {
            if (isset($this->pathsReset[$item])) {
                try {
                    $this->reset('white_list');
                    $output->writeln('<info>' . ucfirst($item) . ' Reset Successfully!</info>');
                } catch (\Exception $e) {
                    $output->writeln("<error>{$e->getMessage()}</error>");
                }
            } else {
                $output->writeln("<error>Wrong value '" . $item . "'</error>");
            }
        }
    }

    /**
     * Reset config value
     *
     * @param $path
     */
    private function reset($path)
    {
        $this->_writer->save(
            $path,
            '',
            \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            \Magento\Store\Model\Store::DEFAULT_STORE_ID
        );
    }
}
